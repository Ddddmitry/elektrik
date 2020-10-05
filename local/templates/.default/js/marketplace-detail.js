$(document).ready(() => {

  function fillAllRatingsAjax() {
    if ($('.js-rait').length != 0) {
      $('.js-rait').each(function () {
        let that = $(this);
        let rait = Number(that.attr('data-rait').replace(',','.').replace(' ',''));
        let wrWidth = that.closest('.js-raitWr').width();
        let fullNumber = Math.floor(rait);
        let fractionNumber = Number(String(rait).split('.')[1] || 0)/10;
        let percentLast = Math.asin( 2 * fractionNumber - 1 ) / Math.PI + 0.5;
        let percentStar = 20;
        let result = (fullNumber * percentStar) + (percentLast * percentStar);
        let widthRait = Math.ceil(wrWidth * (result/100));
        that.css({'width':widthRait});
      });
    }
  }

  let $marketplaceDetail = $('.js-contractor-detail-viewed');
  let $showContactsButton = $('.js-marketDetail-about-helper-contactsButton');
  let $snowContactsButtonMobile = $('.js-marketDetail-about-helper-contactsButton_mobile');

  $showContactsButton.add($snowContactsButtonMobile).on('click', function (e) {
    $.ajax({
      url: '/api/statistics.add/',
      data: {
        type: 'contacts',
        user: $marketplaceDetail.data('contractor-user'),
        session: $marketplaceDetail.data('user-session'),
      },
      type: 'POST',
    });
  });

  if ($marketplaceDetail.length) {
    $.ajax({
      url: '/api/statistics.add/',
      data: {
        type: 'detail',
        user: $marketplaceDetail.data('contractor-user'),
        session: $marketplaceDetail.data('user-session'),
      },
      type: 'POST',
    });
  }

  /* Фильтрация отзывов по оценкам */
  $('.js-filter-reviews').on('click', function (e) {
    e.preventDefault();

    let $reviewsList = $('.js-reviews-list');

    let $filterButton = $(e.target);
    let filterMark = $filterButton.data('filter-mark');
    let user = $reviewsList.data('user');

    $reviewsList.data('filter-mark', filterMark);

    let fields = 'mark=' + filterMark + '&user=' + user;

    $.ajax({
      url: '/ajax/marketplace-detail-reviews.php',
      data: fields,
      type: 'GET',
      success: (data) => {
        $reviewsList.data('page', 1);
        $reviewsList.html(data);
        fillAllRatingsAjax();
      },
    });
  });

  /* Подгрузка отзывов на детальной странице исполнителя */
  $('.js-reviews-more a').on('click', function (e) {
    e.preventDefault();

    let $reviewsList = $('.js-reviews-list');

    let nextPage = $reviewsList.data('page') + 1;
    let user = $reviewsList.data('user');
    let markFilter = $reviewsList.data('filter-mark');

    let fields = 'page=' + nextPage + '&user=' + user;
    if (markFilter) {
      fields = fields + '&mark=' + markFilter;
    }

    $.ajax({
      url: '/ajax/marketplace-detail-reviews.php',
      data: fields,
      type: 'GET',
      success: (data) => {
        $reviewsList.data('page', nextPage);
        $reviewsList.append(data);
        fillAllRatingsAjax();
      },
    });
  });

  // Открытие формы редактирования отзыва
  $(document).on('click', '.js-edit-review', function (e) {
    e.preventDefault();
    let $review = $($(e.target).closest('.marketDetail-reviews-list__single')[0]);
    let $text = $review.find('.marketDetail-reviews-list__text');
    $text.slideToggle(300);
  });

  // Установка выбранных оценок для форм редактирования отзывов
  let $reviewEditRadio = $('.js-review-edit-rate-radio');
  if ($reviewEditRadio.length) {
    $reviewEditRadio.each(function() {
      $(this).find('[value=' + $(this).data('checked') + ']').attr('checked', 'checked');
      console.log($(this).find('[name=review-mark]'));
      //$(this).find('[name=review-mark]').val([$(this).data('checked')]);
      //$(this).closest('.formGroup_raiting').removeClass('notValid');
    });
  }

});
