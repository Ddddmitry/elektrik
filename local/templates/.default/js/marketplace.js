$(document).ready(() => {

  function fillAllRatingsAjax() {
    if ($('.js-rait').length != 0) {
      $('.js-rait').each(function () {
        let that = $(this);
        let rait = Number(that.attr('data-rait').replace(',', '.').replace(' ', ''));
        let wrWidth = that.closest('.js-raitWr').width();
        let fullNumber = Math.floor(rait);
        let fractionNumber = Number(String(rait).split('.')[1] || 0) / 10;
        let percentLast = Math.asin(2 * fractionNumber - 1) / Math.PI + 0.5;
        let percentStar = 20;
        let result = (fullNumber * percentStar) + (percentLast * percentStar);
        let widthRait = Math.ceil(wrWidth * (result / 100));
        that.css({'width': widthRait});
      });
    }
  }

  function applyFilter($formFilter, $formFilterDetails) {
    $('.js-popupSearchResult-input').blur();

    let $contractorsList = $('.js-marketplace-list');
    let action = $formFilter.attr('action');
    let fields = $formFilter.serializeArray();

    if (!fields[3].value) {
      fields[2].value = '';
    }
    fields = $.param(fields);

    fields += "&" + $formFilterDetails.serialize();

    if ($formFilter.data('contractors-filter') === 'ajax') {
      $.ajax({
        url: action,
        data: fields,
        type: 'GET',
        success: (data) => {
          $formFilter.attr('data-filter-params', fields);
          $contractorsList.html(data);
          fillAllRatingsAjax();
          $contractorsList.data('page', 1);
          $('.js-contractors-filter-clean').addClass('show');
        },
      })
    } else {
      window.location = action + "?" + fields;
    }
  }

  let $formFilter = $('[data-contractors-filter]');
  let $formFilterDetails = $('[data-contractors-filter-details]');
  let $contractorsList = $('.js-marketplace-list');

  let $contractorsSearchService = $('input[name=contractors-search]');
  let $contractorsSearchLocation = $('input[name=contractors-location]');


  // Фильтр исполнителей
  if ($formFilter.length) {
    let urlArray = window.location.href.split("?");
    if (urlArray[1]) $formFilter.attr('data-filter-params', urlArray[1]);

    $formFilter.on('submit', (e) => {
      e.preventDefault();
      applyFilter($formFilter, $formFilterDetails);
    });
  }


  // Применение фильтра после изменения дополнительного фильтра
  $formFilterDetails.find('.js-chosen').on('change', function (e) {
    applyFilter($formFilter, $formFilterDetails);
  });


  // Сортировка
  $('.js-sort a').on('click', function (e) {
    e.preventDefault();
    let action = $formFilter.attr('action');
    let fields = $formFilter.attr('data-filter-params');
    let sortButton = $(this).parent();
    if (sortButton.hasClass('selected')) {
      if (sortButton.data('order') == 'asc') {
        sortButton.data('order', 'desc');
        sortButton.addClass('desc');
      } else {
        sortButton.data('order', 'asc');
        sortButton.removeClass('desc');
      }
    } else {
      $('.js-sort').removeClass('selected');
      sortButton.addClass('selected');
    }
    fields = fields + (fields ? '&' : '') + 'sort_by=' + sortButton.data('by') + '&sort_order=' + sortButton.data('order');
    $.ajax({
      url: action,
      data: fields,
      type: 'GET',
      success: (data) => {
        $formFilter.attr('data-sort-params', fields);
        $contractorsList.html(data);
        fillAllRatingsAjax();
        $contractorsList.data('page', 1);
      },
    })
  });

  $('.js-sort-mobile').chosen({
    disable_search_threshold: 10
  }).change(function (event) {
    let action = $formFilter.attr('action');
    let fields = $formFilter.attr('data-filter-params');
    let sortBy = $(event.target).find(':selected').data('by');
    let sortOrder = $(event.target).find(':selected').data('order')
    fields = fields + (fields ? '&' : '') + 'sort_by=' + sortBy + '&sort_order=' + sortOrder;
    $.ajax({
      url: action,
      data: fields,
      type: 'GET',
      success: (data) => {
        $formFilter.attr('data-sort-params', fields);
        $contractorsList.html(data);
        fillAllRatingsAjax();
        $contractorsList.data('page', 1);
      },
    });

    $(this).next('.chosen-container').addClass('active');
  });


  // Очистка фильтра
  $('.js-contractors-filter-clean').on('click', function (e) {
    e.preventDefault();
    $formFilter.attr('data-filter-params', '');
    $formFilter.attr('data-sort-params', '');
    $formFilterDetails.find('select').val('');
    $formFilterDetails.find('.chosen-container').removeClass('active');
    $formFilterDetails.find('select').trigger("chosen:updated");
    $formFilter.find('input').val('');
    $formFilter.find('label').removeClass('active');
    applyFilter($formFilter, $formFilterDetails);
  });


  // AJAX-пагинация
  $('.js-marketList-more').on('click', function (e) {
    e.preventDefault();

    let nextPage = $contractorsList.data('page') + 1;

    let action = $formFilter.attr('action');
    let fields = $formFilter.attr('data-filter-params');
    fields = fields + (fields ? '&' : '') + $formFilter.attr('data-sort-params');
    fields = fields + (fields ? '&' : '') + 'page=' + nextPage;

    $.ajax({
      url: action,
      data: fields,
      type: 'GET',
      success: (data) => {
        $contractorsList.data('page', nextPage);
        $contractorsList.append(data);
        fillAllRatingsAjax();
      },
    })
  });


  // Всплывающие подсказки при заполнении поля "Услуга и специалист" в форме поиска
  if ($contractorsSearchService.length) {
    $contractorsSearchService.on('keyup', (e) => {
      e.preventDefault();
      let searchPhrase = $(e.target).val();
      let data = {
        searchPhrase: searchPhrase,
        limit: 5,
      };
      $.ajax({
        url: '/api/contractors.service.suggest/',
        data: JSON.stringify(data),
        type: 'POST',
        contentType: 'application/json',
        success: (data) => {
          let popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
          popup.html('');
          if (data.result.CATEGORIES) {
            for (let i = 0; i < data.result.CATEGORIES.length; i++) {
              if (data.result.CATEGORIES[i].ITEMS.length != 0) {
                popup.append('<div class="popupSearchResult__title popupSearchResult__block js-popupSearchResult__title">' + data.result.CATEGORIES[i].TITLE + '</div>');
              }
              for (let i2 = 0; i2 < data.result.CATEGORIES[i].ITEMS.length; i2++) {
                popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result.CATEGORIES[i].ITEMS[i2].ID + '">' + data.result.CATEGORIES[i].ITEMS[i2].NAME + '</div>');
              }
            }
          }
          popup.slideDown(200);
        },
      })
    });
  }


  // Всплывающие подсказки при заполнении поля местоположения в форме поиска
  if ($contractorsSearchLocation.length) {
    let handle;
    $contractorsSearchLocation.on('keyup', (e) => {
      e.preventDefault();
      clearTimeout(handle);
      handle = setTimeout(function () {
        if (e.target.value.length > 2) {
          let searchPhrase = $(e.target).val();
          let data = {
            searchPhrase: searchPhrase,
            onlyCities: true,
          };
          if ($(e.target).data('restricted')) {
            data['restricted'] = $(e.target).data('restricted');
          }
          $.ajax({
            url: '/api/contractors.location.suggest/',
            data: JSON.stringify(data),
            type: 'POST',
            contentType: 'application/json',
            success: (data) => {
              let popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
              popup.html('');
              if (data.result != undefined) {
                for (let i = 0; i < data.result.length; i++) {
                  popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result[i]['id'] + '">' + data.result[i]['value'] + '</div>');
                }
                popup.slideDown(200);
              }
            },
          })
        }
      }, 500);
    });
  }

  $(document).on('click', '.js-popupSearchResult__result', function () {
    let text = $(this).text();
    let value = $(this).attr('data-result');
    let inputText = $(this).closest('.formGroup').find('.js-popupSearchResult-input');
    let inputValue = $(this).closest('.formGroup').find('.js-popupSearchResult-inputHidden');
    inputText.val(text);
    inputValue.val(value);
  });

  $(document).on('blur', '.js-popupSearchResult-input', function (e) {
    let that = $(this);
    setTimeout(function () {
      that.closest('.formGroup').find('.js-popupSearchResult').slideUp(200);
    }, 200);
  });

  $(document).on('focus', '.js-popupSearchResult-input', function () {
    let popup = $(this).closest('.formGroup').find('.js-popupSearchResult');
    if (popup.html() != '') {
      popup.slideDown(200);
    }
  });


  // Смена города пользователя в рамках функционала подбора соседних городов
  $(document).on('click', '.js-set-closest-city', function (e) {
    e.preventDefault();

    $.ajax({
      url: '/api/cities.change/',
      data: {
        'search_city-id': $(e.target).data('id'),
      },
      type: 'POST',
      success: (data) => {
        if (data.result) {
          window.location.reload();
        }
      },
    })

  });


  // AJAX-пагинация при прокрутке
  if ($('.js-marketList-more').length != 0) {
    let focusButton = false;
    $(window).scroll(function () {

      if ($(window).scrollTop() + window.innerHeight * 1.6 > $('.js-marketList-more').offset().top && focusButton == false && $('#js-more-button').is(':visible')) {

        focusButton = true;
        let nextPage = $contractorsList.data('page') + 1;

        let action = $formFilter.attr('action');
        let fields = $formFilter.attr('data-filter-params');
        fields = fields + (fields ? '&' : '') + $formFilter.attr('data-sort-params');
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage;

        $.ajax({
          url: action,
          data: fields,
          type: 'GET',
          success: (data) => {
            focusButton = false;
            $contractorsList.data('page', nextPage);
            $contractorsList.append(data);
            fillAllRatingsAjax();
          },
        });

      }
    });
  }


});
