import { setCookie } from './functions.js';

$(document).ready(() => {

    let $formAuth = $('form[data-form-auth]');
    let $formRegister = $('form[data-form-register]');
    let $formRegisterInfo = $('form[data-form-register-info]');
    let $formRestore = $('form[data-form-restore]');
    let $formRestoreConfirm = $('form[data-form-restore-confirm]');
    let $formAddReview = $('form[data-form-review]');
    let $formEmaster = $('form[data-form-emaster]');
    let $formArticle = $('form[data-form-article]');
    let $formChangePassword = $('form[data-form-change-password]');
    let $formChangeName = $('form[data-form-change-name]');
    let $formSearchCity = $('form[data-form-search-city]');

    if ($formRestoreConfirm.length) {
        $formRestoreConfirm.on('submit', (e) => {
            e.preventDefault();
            let fields = $formRestoreConfirm.serialize();
            let action = $formRestoreConfirm.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $formRestoreConfirm.find('[data-form-alert]').hide().html();
                $formRestoreConfirm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {
                            let errorBlock = $('.js-form-error');
                            errorBlock.html(data.result);
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 5000)
                        } else if (data.error) {
                            let errorBlock = $('.js-form-error');
                            errorBlock.html(data.error);
                        }
                        $formRestoreConfirm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        });
    }

    if ($formRestore.length) {
        $formRestore.on('submit', (e) => {
            e.preventDefault();
            let fields = $formRestore.serialize();
            let action = $formRestore.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $formRestore.find('[data-form-alert]').hide().html();
                $formRestore.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if(data.result) {
                          $formRestore.html('');
                          $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
                        } else if (data.error) {
                            let errorBlock = $('.js-errorInput');
                            errorBlock.html(data.error);
                        }
                        $formRestore.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        });
    }

    if ($formAuth.length) {
        setCookie('social-user-type', "#client");
        $formAuth.on('submit', (e) => {
            e.preventDefault();
            let fields = $formAuth.serialize();
            let action = $formAuth.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $formAuth.find('[data-form-alert]').hide().html();
                $formAuth.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if(data.result) {
                            window.location.reload();
                        } else if (data.error) {
                            let errorBlock = $('.js-form-error');
                            errorBlock.html(data.error);
                        }
                        $formAuth.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        });
    }

    if ($formRegister.length) {
        setCookie('social-user-type', "#contractor");
        $formRegister.on('submit', (e) => {
            e.preventDefault();
            let $currentFormRegister = $(e.target);
            let fields = $currentFormRegister.serialize();
            let action = $currentFormRegister.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $currentFormRegister.find('[data-form-alert]').hide().html();
                $currentFormRegister.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {
                            if (data.result['USER_TYPE'] === 'contractor') {
                                let reloadURL = new URL(window.location.href);
                                reloadURL.searchParams.set('user-type', data.result['USER_TYPE']);
                                reloadURL.searchParams.set('user-id', data.result['ID']);
                                reloadURL.searchParams.set('hash', data.result['HASH']);
                                window.location.href = reloadURL.href;
                            } else {
                                $('.js-tabs').html('');
                                $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
                                $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
                            }
                        } else if (data.error) {
                            let errorBlock = $('.js-form-error-' + $currentFormRegister.attr('id'));
                            errorBlock.html(data.error);
                        }
                        $currentFormRegister.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }

        })

    }

  if ($formRegisterInfo.length) {
    $formRegisterInfo.on('submit', (e) => {
      e.preventDefault();
      let $currentFormRegisterInfo = $(e.target);
      let formData = new FormData($currentFormRegisterInfo[0]);
      let action = $currentFormRegisterInfo.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $currentFormRegisterInfo.find('[data-form-alert]').hide().html();
        $currentFormRegisterInfo.find('[data-form-submit]').attr('disabled', true);
        $.ajax({
          url: action,
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'POST',
          success: (data) => {
            data = JSON.parse(data);
            if (data.result) {
                $('.js-tabs').html('');
                $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
                $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
            } else if (data.error) {
              let errorBlock = $('.js-form-error-' + $currentFormRegisterInfo.attr('id'));
              errorBlock.html(data.error);
            }
            $currentFormRegisterInfo.find('[data-form-submit]').attr('disabled', false);
          },
        })
      }

    })
  }

  // Отправка формы добавление отзыва
  if ($formAddReview.length) {
    $formAddReview.on('submit', (e) => {
      e.preventDefault();
      let fields = $formAddReview.serialize();
      let action = $formAddReview.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          data: fields,
          type: 'POST',
          success: (data) => {
            if(data.result) {
                $('.js-form-comment').html(data.result["MESSAGE"]);
            } else if (data.error) {
                let errorBlock = $('.js-form-error');
                errorBlock.html(data.error);
            }
            $formAuth.find('[data-form-submit]').attr('disabled', false);
          },
        })
      }
    });
  }


  // Отправка формы редактирования отзыва
  $(document).on('submit', 'form[data-form-review-edit]', (e) => {
    e.preventDefault();
    let $formEditReview = $(e.target);
    let fields = $formEditReview.serialize();
    let action = $formEditReview.attr('action');
    if (!action) {
      alert('Не указан обработчик запроса');
      return false;
    } else {
      $.ajax({
        url: action,
        data: fields,
        type: 'POST',
        success: (data) => {
          if (data.result) {
            console.log($formEditReview);
            $formEditReview.closest('.marketDetail-reviews-list__single').html(data.result["MESSAGE"]);
          } else if (data.error) {
            let errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
          $formAuth.find('[data-form-submit]').attr('disabled', false);
        },
      })
    }
  });


  // Удаление отзыва
  $(document).on('click', '.js-delete-review', (e) => {
    e.preventDefault();
    let data = {
      'id': $(e.target).data('id'),
    };
    $.ajax({
      url: '/api/reviews.delete/',
      data: JSON.stringify(data),
      type: 'POST',
      success: (data) => {
        if (data.result) {
          let $comment = $(e.target).closest('.marketDetail-reviews-list__single');
          $comment.slideUp(300);
        }
      },
    })
  });



  // Отправка формы добавления комментария
  $(document).on('submit', 'form[data-form-comment]', (e) => {
    e.preventDefault();
    let $formAddComment = $(e.target);
    let $submitButton = $(e.target).find('[type=submit]');
    let fields = $formAddComment.serialize();
    let action = $formAddComment.attr('action');
    if (!action) {
      alert('Не указан обработчик запроса');
      return false;
    } else {
      $submitButton.attr("disabled", true);
      $.ajax({
        url: action,
        data: fields,
        type: 'POST',
        success: (data) => {
          if (data.result) {
            window.location.reload();
          } else if (data.error) {
            let errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
        },
      })
    }
  });


    // Отправка формы изменения комментария
    $(document).on('submit', 'form[data-form-comment-edit]', (e) => {
        e.preventDefault();
        let $formEditComment = $(e.target);
        let fields = $formEditComment.serialize();
        let action = $formEditComment.attr('action');
        if (!action) {
            alert('Не указан обработчик запроса');
            return false;
        } else {
            $.ajax({
                url: action,
                data: fields,
                type: 'POST',
                success: (data) => {
                    if (data.result) {
                        let text = $formEditComment.find('textarea').val();
                        let $comment = $formEditComment.closest('.js-marketDetail-comment-single');
                        $comment.children('.js-marketDetail-reviews-list__text').html(text);
                        $comment.children('.js-marketDetail-reviews-list__text').slideToggle(300);
                        $comment.children('.js-marketDetail-reviews-list__edit-form').slideToggle(300);
                        $comment.children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
                    } else if (data.error) {
                        let errorBlock = $('.js-form-error');
                        errorBlock.html(data.error);
                    }
                },
            })
        }
    });


    // Удаление комментария
    $(document).on('click', '.js-marketDetail-reviews-list__delete', (e) => {
        e.preventDefault();
        let data = {
            id: $(e.target).data('id'),
        };
        $.ajax({
            url: '/api/comments.delete/',
            data: JSON.stringify(data),
            type: 'POST',
            success: (data) => {
                if (data.result) {
                    let $comment =$(e.target).closest('.js-marketDetail-comment-single');
                    $comment.slideUp(300);
                }
            },
        })
    });


  if ($formEmaster.length) {
    $formEmaster.on('submit', (e) => {
      e.preventDefault();
      let formData = new FormData($formEmaster[0]);
      let action = $formEmaster.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'POST',
          success: (data) => {
            if (data.result) {
              $('#make_sertificate_electric .popup-content').html('<div class="fancybox-title">Спасибо за заявку!</div><p>Мы сообщим Вам, когда заявка будет рассмотрена нашими специалистами.</p>');
            } else if (data.error) {
              $('#make_sertificate_electric .popup-content').html('<div class="fancybox-title">Заявка уже существует</div><p>Ваша заявка уже находится на рассмотрении.</p>');
            }
          },
        })
      }
    });
  }

  if ($formArticle.length) {
    $formArticle.on('submit', (e) => {
      e.preventDefault();
      let formData = new FormData($formArticle[0]);
      let action = $formArticle.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'POST',
          success: (data) => {
            if (data.result) {
              $('#new_article').html('<div class="fancybox-title">Спасибо за предложенную статью!</div><p>Мы сообщим Вам, когда статья будет рассмотрена нашими специалистами и опубликована.</p>');
            } else if (data.error) {
              let errorBlock = $('.js-errorInput');
              errorBlock.html(data.error);
            }
          },
        })
      }
    });
  }

  if ($formChangePassword.length) {
    $formChangePassword.on('submit', (e) => {
      e.preventDefault();
      let formData = new FormData($formChangePassword[0]);
      let action = $formChangePassword.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'POST',
          success: (data) => {
            if (data.result) {
              $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
              $('.js-form-content').html('');
            } else if (data.error) {
              let errorBlock = $('.js-form-error');
              let errorMessage;
              if (data.error === 'wrong_old_password') {
                errorMessage = 'Неверный старый пароль'
              } else {
                errorMessage = data.error;
              }
              errorBlock.html(errorMessage);
            }
          },
        })
      }
    });
  }

  // Отправка формы смены ФИО
  if ($formChangeName.length) {
    $formChangeName.on('submit', (e) => {
      e.preventDefault();
      let formData = new FormData($formChangeName[0]);
      let action = $formChangeName.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'POST',
          success: (data) => {
            if (data.result) {
              $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
              $('.js-form-content').html('');
            } else if (data.error) {
              let errorBlock = $('.js-form-error');
              errorBlock.html(data.error);
            }
          },
        })
      }
    });
  }


  // Всплывающие подсказки при заполнении поля выбора местоположения пользователя
  let $inputSearchCity = $('#search_city-search');
  if ($inputSearchCity.length) {
    let handle;
    $inputSearchCity.on('keyup', (e) => {
      e.preventDefault();
      clearTimeout(handle);
      handle = setTimeout(function () {
        let searchPhrase = $(e.target).val();
        let data = {
          searchPhrase: searchPhrase,
          showOnlyCities: true,
        };
        $.ajax({
          url: '/api/contractors.location.suggest/',
          data: JSON.stringify(data),
          type: 'POST',
          contentType : 'application/json',
          success: (data) => {
            let popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
            popup.html('');
            for (let i = 0; i < data.result.length; i++) {
              popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result[i]['id'] + '">' + data.result[i]['value'] + '</div>');
            }
            popup.slideDown(200);
          },
        })
      }, 500);
    });
  }

  $(document).on('click', '.js-popupSearchResult__result', function () {
    $('#city-change-submit').removeAttr('disabled');
  });

  // Выбор города из предустановленного списка
  $(document).on('click', '.js-impotantTowns__single-link', (e) => {
    e.preventDefault();
    $('.js-impotantTowns__single-link').removeClass('active');
    $(e.target).addClass('active');
    $('#search_city-search').val($(e.target).data('path'));
    $('#search_city-id').val($(e.target).data('id'));
    $('#city-change-submit').removeAttr("disabled");
  });


  // Подтверждение выбора города
  if ($formSearchCity.length) {
    $formSearchCity.on('submit', (e) => {
      e.preventDefault();
      let fields = $formSearchCity.serialize();
      $.ajax({
        url: '/api/cities.change/',
        data: fields,
        type: 'POST',
        success: (data) => {
          if (data.result) {
            $.fancybox.close();
            window.location = window.location.href.split("?")[0];
          } else if (data.error) {
            let errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
          $formSearchCity.find('#city-change-submit').attr('disabled', false);
        },
      })

    });
  }


});
