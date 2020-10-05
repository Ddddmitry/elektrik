$(document).ready(() => {

  let $articlesSearch = $('[data-articles-search]');
  let $articlesFilter = $('[data-articles-filter]');
  let $articlesList = $('.js-articles-list');
  let $articlesClear = $('.js-articles-filter-clear');
  let $commentsList = $('.js-comments-list');

  /**
   * $.parseParams - parse query string paramaters into an object.
   */
  (function($) {
    var re = /([^&=]+)=?([^&]*)/g;
    var decodeRE = /\+/g;
    var decode = function (str) {return decodeURIComponent( str.replace(decodeRE, " ") );};
    $.parseParams = function(query) {
      var params = {}, e;
      while ( e = re.exec(query) ) {
        var k = decode( e[1] ), v = decode( e[2] );
        if (k.substring(k.length - 2) === '[]') {
          k = k.substring(0, k.length - 2);
          (params[k] || (params[k] = [])).push(v);
        }
        else params[k] = v;
      }
      return params;
    };
  })(jQuery);

  if ($articlesSearch.length) {
    $articlesSearch.on('submit', (e) => {
      $articlesSearch.find('input')[0].value = $articlesSearch.find('input')[0].value.replace(/</g, "< ");
    });
  }

  $('.js-articles-filter-type').on('click', (e) => {
    e.preventDefault();
    let $typeElement = $(e.delegateTarget);
    let typeID = (!$typeElement.hasClass('active')) ? $typeElement.data('type') : '';
    let fields;
    if ($articlesFilter.attr('data-filter-params')) {
      let filterParams = $.parseParams($articlesFilter.attr('data-filter-params'));
      filterParams.type = typeID;
      fields = $.param(filterParams)
    } else {
      fields = "&type=" + typeID;
    }
    $.ajax({
      url: '',
      data: fields,
      type: 'GET',
      success: (data) => {
        if (!$typeElement.hasClass('active')) {
          $('.js-articles-filter-type').removeClass('active');
          $typeElement.addClass('active');
        } else {
          $('.js-articles-filter-type').removeClass('active');
        }
        $articlesFilter.attr('data-filter-params', fields);
        if ($('.js-articles-filter-type').hasClass('active') || $('.js-articles-filter-tag').hasClass('active')) {
          $('.js-articles-filter-clear-block').addClass('show');
        } else {
          $('.js-articles-filter-clear-block').removeClass('show');
        }
        $articlesList.html(data);
        $articlesList.data('page', 1);
      },
    })

  });

  $('.js-articles-filter-tag').on('click', (e) => {
    e.preventDefault();
    let $tagElement = $(e.delegateTarget);
    let tagID = (!$tagElement.hasClass('active')) ? $tagElement.data('tag') : '';
    let fields;
    if ($articlesFilter.attr('data-filter-params')) {
      let filterParams = $.parseParams($articlesFilter.attr('data-filter-params'));
      filterParams.tag = tagID;
      fields = $.param(filterParams)
    } else {
      fields = "&tag=" + tagID;
    }
    $.ajax({
      url: '',
      data: fields,
      type: 'GET',
      success: (data) => {
        if (!$tagElement.hasClass('active')) {
          $('.js-articles-filter-tag').removeClass('active');
          $tagElement.addClass('active');
        } else {
          $('.js-articles-filter-tag').removeClass('active');
        }
        $articlesFilter.attr('data-filter-params', fields);
        if ($('.js-articles-filter-type').hasClass('active') || $('.js-articles-filter-tag').hasClass('active')) {
          $('.js-articles-filter-clear-block').addClass('show')
        } else {
          $('.js-articles-filter-clear-block').removeClass('show');
        }
        $articlesList.html(data);
        $articlesList.data('page', 1);
      },
    })

  });

  $articlesClear.on('click', (e) => {
    e.preventDefault();
    $.ajax({
      url: '',
      data: '',
      type: 'GET',
      success: (data) => {
        $('.js-articles-filter-type').removeClass('active');
        $('.js-articles-filter-tag').removeClass('active');
        $articlesFilter.attr('data-filter-params', '');
        $('.js-articles-filter-clear-block').removeClass('show');
        $articlesList.html(data);
        $articlesList.data('page', 1);
      },
    })
  });


  // Пагинация статей
  $('.js-articles-more').on('click', function (e) {
    e.preventDefault();
    let nextPage = $articlesList.data('page') + 1;
    let fields = $articlesFilter.attr('data-filter-params');
    fields = fields + (fields ? '&' : '') + 'page=' + nextPage;
    $.ajax({
      url: "",
      data: fields,
      type: 'GET',
      success: (data) => {
        $articlesList.data('page', nextPage);
        $articlesList.append(data);
      },
    })
  });


  // Пометка комментариев как прочитанных
  if ($commentsList.length) {
      if ($commentsList.attr('mark-as-viewed')) {
        $.post( "/api/articles.update.viewed/", {articleID: $commentsList.attr('mark-as-viewed') });
      }
  }


  // Пагинация комментариев к статье
  $('.js-comments-more a').on('click', function (e) {
    e.preventDefault();

    let nextPage = $commentsList.data('page') + 1;
    let article = $commentsList.data('article');

    let fields = 'page=' + nextPage + '&article=' + article;

    $.ajax({
      url: '/ajax/articles-detail-comments.php',
      data: fields,
      type: 'GET',
      success: (data) => {
        $commentsList.data('page', nextPage);
        $commentsList.append(data);
      },
    });
  });


  // Добавление, изменение или удаление оценки комментария
  $(document).on('click', '.js-comment-like', function (e) {
    e.preventDefault();
    let $like = $(e.target).closest('.js-comment-like');

    let fields = 'type=' + $like.data('type') + '&comment=' + $like.data('id');

    $.ajax({
      url: '/api/articles.comments.like/',
      data: fields,
      type: 'POST',
      success: (data) => {
        if (data.result) {
          $like.parent().find('.js-comment-like--like .js-likes-count').html(data.result['LIKE']);
          $like.parent().find('.js-comment-like--dislike .js-likes-count').html(data.result['DISLIKE']);
          $('.js-comment-like[data-id=' + $like.data('id') + ']').removeClass('active');
          if (!data.result['IS_REMOVED']) {
            $like.addClass('active');
          }
        }
      },
    });
  });

    if ($('.js-articles-more').length != 0) {
        let focusButton = false;
        $(window).scroll(function () {

            if ($('#js-more-button').is(':visible')) {
              if ($(window).scrollTop() + window.innerHeight * 1.6 > $('.js-articles-more').offset().top && focusButton == false) {

                focusButton = true;
                let nextPage = $articlesList.data('page') + 1;
                let fields = $articlesFilter.attr('data-filter-params');
                fields = fields + (fields ? '&' : '') + 'page=' + nextPage;
                $.ajax({
                  url: "",
                  data: fields,
                  type: 'GET',
                  success: (data) => {
                    focusButton = false;

                    $articlesList.data('page', nextPage);
                    $articlesList.append(data);
                  },
                });

              }

            }

        });
    }



});


