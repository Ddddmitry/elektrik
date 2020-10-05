//Определение автозаполнения webkit браузеров
let isWebKit = !!window.webkitURL;

function checkWebkitAutofill(that) {
  let webkitAutofill = false;
  if (isWebKit) {
    webkitAutofill = that.is(':-webkit-autofill');
  }
  return webkitAutofill;
}

//Смещение label при фокусе или если в поле есть текст
function labelTransfer(that, focus) {
  if (that.closest('.formGroup').length != 0
    && that.closest('.formGroup').find('label').length != 0
    && that.closest('.formGroup').find('.custCheckbox').length == 0
    && that.closest('.formGroup').find('.custRadio').length == 0) {

    if (that.val().length > 0 || checkWebkitAutofill(that) || focus) {
      if (that.attr('data-placeholder') != undefined) {
        that.attr({'placeholder': ''});
      }
      that.closest('.formGroup').find('label').addClass('active');
      if (that.closest('.formGroup').find('.showPass').length != 0) {
        that.closest('.formGroup').find('.showPass').addClass('active');
        if (that.closest('.formGroup').find('.passwordField').length != 0) {
          that.closest('.formGroup').find('.passwordField').addClass('activeLabel');
        }
      }
      setTimeout(function () {
        // let labelWidth = that.closest('.formGroup').find('label').innerWidth();
        if (that.closest('.formGroup').hasClass('formGroup_search') == false) {
          // that.css({'padding-right': labelWidth });
        } else {
          that.closest('.formGroup').find('.js-cleanIcon').addClass('active');
        }
      }, 300);
    } else {
      // let paddingRightStart = that.attr('data-padding-right');
      // that.css({ 'padding-right': paddingRightStart });
      if (that.attr('data-placeholder') != undefined) {
        that.attr({'placeholder': that.attr('data-placeholder')});
      }
      that.closest('.formGroup').find('label').removeClass('active');
      if (that.closest('.formGroup').find('.showPass').length != 0) {
        that.closest('.formGroup').find('.showPass').removeClass('active');
        if (that.closest('.formGroup').find('.passwordField').length != 0) {
          that.closest('.formGroup').find('.passwordField').removeClass('activeLabel');
        }
      }
      if (that.closest('.formGroup').hasClass('formGroup_search')) {
        that.closest('.formGroup').find('.js-cleanIcon').removeClass('active');
      }
    }
  }
}

//Проверка обязательных полей на заполненность
function requiredFull(thisInp) {
    let thisWrap = thisInp.closest('.formGroup');
    setTimeout(function () {
      let valArray = thisInp.val().split('');
      if (valArray[0] == ' ') {
        console.log(valArray);
        for (let i = 0; i < valArray.length; i++) {
          if (valArray[i] == ' ') {
            console.log(i);

              valArray.splice(i, 1);
              thisInp.val(valArray.join(''));

          } else {
            thisInp.val(valArray.join(''));
            break;
          }
        }
      }
    }, 200);

    if (thisWrap.hasClass('required')) {
        if (thisInp.attr('type') == 'radio' || thisInp.attr('type') == 'checkbox') {
            thisWrap.find('input').each(function () {
                if ($(this).is(':checked')) {
                    thisWrap.removeClass('notValid');
                    thisInp.removeClass('notValid');
                    return false;
                } else {
                    thisWrap.addClass('notValid');
                    thisInp.addClass('notValid');
                }
            });
        } else {
            if (thisInp.val() != '') {
                thisWrap.removeClass('notValid');
                thisInp.removeClass('notValid');
            } else {
                thisWrap.addClass('notValid');
                thisInp.addClass('notValid');
            }
        }
    }
}

//Валидация Email
function validEmail(thisInp) {
  let thisWrap = thisInp.closest('.formGroup');
  let errorBlock = thisInp.closest('.formGroup').find('.js-errorInput');
  if (thisInp.attr('type') == 'email') {
    let pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
    if (thisInp.val() != '') {
      if (thisInp.val().search(pattern) == 0) {
        thisWrap.removeClass('notValidEmail');
        thisWrap.find('.errorEmail').remove();
        errorBlock.text('');
      } else {
        thisWrap.addClass('notValidEmail');
        errorBlock.text('Адрес электронной почты должен соответствовать шаблону "xxxx@xxx.xx"');
      }
    } else {
      thisWrap.removeClass('notValidEmail');
      thisWrap.find('.validText').remove();
    }
  }
}

function validLength(thisInp) {
  let thisWrap = thisInp.closest('.formGroup');
  if (thisInp.data('min-length')) {
    if (!thisInp.val().length || thisInp.val().length >= thisInp.data('min-length')) {
      thisWrap.removeClass('notValidLength');
    } else {
      thisWrap.addClass('notValidLength');
    }
  }
}

//Проверка всех типов валидации и последующая разблокировка кнопки submit
function unlockSubmit(thisInp) {
  let form = thisInp.closest('form');
  if (form.find('.formGroup').length != 0) {
    if (form.find('.formGroup.required.notValid').length == 0 &&
      form.find('.formGroup.notValidEmail').length == 0 &&
      form.find('.formGroup.notValidLength').length == 0 &&
      form.find('.formGroup.notValidPass').length == 0 &&
      form.find('.formGroup.notValidCode').length == 0 &&
      form.find('.formGroup.notStrongPass').length == 0 &&
      form.find('.formGroup.notValidTel').length == 0 &&
      form.find('.formGroup.lockCode').length == 0 &&
      form.find('.formGroup.notValidCharacters').length == 0) {
      form.find('[type=submit]').removeAttr('disabled');
      form.find('[type=submit]').closest('.formGroup').removeClass('disabled');
    } else {
      form.find('[type=submit]').attr('disabled', 'disabled');
      form.find('[type=submit]').closest('.formGroup').addClass('disabled');
    }
  }
}

//Проверка на нужное количество символов (включительно)
function validCharacters(thisInp) {
  let valid = false;
  let thisWrap = thisInp.closest('.formGroup');
  if (thisInp.val() != '') {
    if (thisInp.attr('minlength') !== undefined || thisInp.attr('maxlength') !== undefined) {
      let thisVal = thisInp.val();
      if (thisWrap.hasClass('numberValidation')) {
        thisVal = thisVal.replace(/\D+/g, "");
        // thisVal = String(thisVal);
      }
      if (thisInp.attr('minlength') === undefined) {
        if (thisVal.length <= parseInt(thisInp.attr('maxlength'), 10)) {
          thisWrap.removeClass('notValidCharacters');
          valid = true;
        } else if (thisVal.length > parseInt(thisInp.attr('maxlength'), 10)) {
          thisWrap.addClass('notValidCharacters');
        }
      } else if (thisInp.attr('maxlength') === undefined) {
        if (thisVal.length >= parseInt(thisInp.attr('minlength'), 10)) {
          thisWrap.removeClass('notValidCharacters');
          valid = true;
        } else if (thisVal.length < parseInt(thisInp.attr('minlength'), 10)) {
          thisWrap.addClass('notValidCharacters');
        }
      } else {
        if (thisVal.length >= parseInt(thisInp.attr('minlength'), 10) && thisVal.length <= parseInt(thisInp.attr('maxlength'), 10)) {
          thisWrap.removeClass('notValidCharacters');
          valid = true;
        } else if (thisVal.length < parseInt(thisInp.attr('minlength'), 10) || thisVal.length > parseInt(thisInp.attr('maxlength'), 10)) {
          thisWrap.addClass('notValidCharacters');
        }
      }
    }
  } else {
    thisWrap.removeClass('notValidCharacters');
  }
  return valid;
}

// Проверка на совпадение полей
function commPass(thisInp) {
  let thisWrap = thisInp.closest('.formGroup');
  if (thisWrap.attr('data-comPass') != '') {
    var currentDataPass = thisWrap.attr('data-comPass');
    var allFull = '';
    var valPass = [];
    var valid = '';
    var cache = thisInp.val();
    $('.formGroup[data-comPass=' + currentDataPass + ']').each(function () {
      if ($(this).find('input').val() == '') {
        allFull = 'no';
        return false;
      } else {
        allFull = 'yes';
      }
    });
    if (allFull == 'yes') {
      $('.formGroup[data-comPass=' + currentDataPass + ']').each(function () {
        valPass.push($(this).find('input').val());
      });
    }
    for (var i = 0; i < valPass.length; i++) {
      if (cache != valPass[i]) {
        valid = 'no';
        break;
      } else {
        valid = 'yes';
      }
    }
    if (valid == 'no') {
      $('.formGroup[data-comPass=' + currentDataPass + ']').each(function () {
        $(this).addClass('notValidPass');
      });
      let error = thisWrap.closest('form').find('.formGroup[data-comPass=' + currentDataPass + ']').last().find('.js-errorInput');
      if (error.text().length == 0) {
        error.text('Пароли должны совпадать');
      }
    } else {
      let error = thisWrap.closest('form').find('.formGroup[data-comPass=' + currentDataPass + ']').last().find('.js-errorInput');
      error.text('');
      $('.formGroup[data-comPass=' + currentDataPass + ']').each(function () {
        $(this).removeClass('notValidPass');
      });
    }
  }
}

// Проверка надежности пароля
function strongPass(thisInp) {
  if (thisInp.attr('data-pass-set') == 'yes') {
    let thisWrap = thisInp.closest('.formGroup');
    let errorBlock = thisInp.closest('.formGroup').find('.js-errorInput');
    if (thisInp.val().length > 0) {
      if (thisInp.val().match(/[A-Z]/) && thisInp.val().match(/[0-9]/)) {
        thisWrap.removeClass('notStrongPass');
        errorBlock.text('');
      } else {
        thisWrap.addClass('notStrongPass');
        errorBlock.text('Пароль недостаточно надежен');
      }
    } else {
      thisWrap.removeClass('notStrongPass');
      errorBlock.text('');
    }
  }
}

window.onload = function () {
  // Перебираем обязательные поля и проставляем им класс notValid,
  // а также пробегаемся по всем полям и работаем с их label (функция labelTransfer)
  if ($('form').length != 0) {
    setTimeout(function () {
      $('form').each(function () {
        $(this).find('input:not([type=submit]),textarea,select').each(function () {
          if ($(this).attr('type') != 'radio' || $(this).attr('type') != 'checkbox') {
            // $(this).attr({ 'data-padding-right': $(this).css('paddingRight') });
            labelTransfer($(this));
          }
          if ($(this).closest('.formGroup').length != 0) {
            if ($(this).closest('.formGroup').hasClass('required')) {
              if ($(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked') == false) {
                  $(this).closest('.formGroup').addClass('notValid');
                }
              } else {
                if ($(this).val() == '') {
                  $(this).closest('.formGroup').addClass('notValid');
                  $(this).addClass('notValid');
                }
              }
              unlockSubmit($(this));
            }
          }
        });
      });
    }, 100);
  }
};

$(document).ready(function () {

  $(document).on('click', 'button[type=submit], input[type=submit]', function (e) {
    let that = $(this);
    $(this).addClass('target');
    setTimeout(function () {
      that.removeClass('target');
    }, 3000);
  });

  // Переключение формы ответа на комментарий
  $(document).on('click', '.js-marketDetail-reviews-list__answer-toggler', function (e) {
    e.preventDefault();
    $(this).closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
    $(this).closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-form').slideToggle(300);
    let that = $(this);
    setTimeout(function () {
      labelTransfer(that.closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-form').find('textarea'));
    }, 100);
  });

  // Переключение формы редактирования комментария
  $(document).on('click', '.js-marketDetail-reviews-list__edit-toggler', function (e) {
    e.preventDefault();
    $(this).closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
    $(this).closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__text').slideToggle(300);
    $(this).closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__edit-form').slideToggle(300);
    let that = $(this);
    setTimeout(function () {
      labelTransfer(that.closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-form').find('textarea'));
    }, 100);
  });

  // Очистка поля
  $(document).on('click', '.js-cleanIcon', function (e) {
    e.preventDefault();
    let input = $(this).closest('.formGroup').find('input');
    input.val('');
    setTimeout(function () {
      labelTransfer(input);
    }, 300);
  });

  // Очистка поля
  $(document).on('click', '#search_articles .js-cleanIcon', function (e) {
    e.preventDefault();
    let input = $(this).closest('.formGroup').find('input');
    input.val('');
    window.location = window.location.href.split("?")[0];
  });

  // Работа с полем типа файл
  let imagesPreview = function (input, placeToInsertImagePreview) {

    if (input.files) {
      let filesAmount = input.files.length;
      for (let i = 0; i < filesAmount; i++) {
        let reader = new FileReader();
        reader.onload = function (event) {
          let single = placeToInsertImagePreview.find('.js-custFile-img-list-single:first-child').clone();
          single.attr({'data-index': i});
          single.find('img').attr({'src': event.target.result});
          placeToInsertImagePreview.append(single);
        };
        reader.readAsDataURL(input.files[i]);
      }
    }
  };
  $('.js-custFile-img [type=file]').on('change', function (e) {

    if ($(this).attr('data-limit')) {

      if (this.files.length == $(this).attr('data-limit')) {
        console.log($(this).parent().parent());
        $(this).parent().parent().find('.custLabel').hide();
      }

      if (this.files.length > $(this).attr('data-limit')) {
        $('.custFile-note').html('Можно загрузить не более ' + $(this).attr('data-limit') + ' файлов');
        $('.custFile-note').addClass('custFile-note--error');
        this.value = '';
        e.preventDefault();

        return false;
      }
    }

    let singleStart = $(this).closest('.formGroup').find('.js-custFile-img-list').find('.js-custFile-img-list-single:first-child').clone();
    $(this).closest('.formGroup').find('.js-custFile-img-list').html('');
    $(this).closest('.formGroup').find('.js-custFile-img-list').append(singleStart);
    imagesPreview(this, $(this).closest('.formGroup').find('.js-custFile-img-list'));
    $(this).closest('.formGroup').find('.js-custFile-img-list').css({'display': 'flex'});
    $(this).closest('.js-custFile-img').hide();
    $(this).closest('.formGroup').find('.js-custFile-img-alone').show();
  });

  $('.js-custFile-img-alone').on('change', '#sertificateElectric-pasport-alone', function () {
    let input = $('.js-custFile-img [type=file]');

    if (input.attr('data-limit')) {
      if ($('.js-custFile-img-list').find('.js-custFile-img-list-single').length == input.attr('data-limit')) {
        $('#sertificateElectric').find('.custLabel').hide();
      }
    }

    imagesPreview(this, $(this).closest('.formGroup').find('.js-custFile-img-list'));
    $(this).removeAttr('id');
    $('.js-custFile-img-alone-list').append(
      '<input type="file"' +
      '  value=""' +
      '  name="sertificateElectric-pasport-alone[]"' +
      '  id="sertificateElectric-pasport-alone"' +
      '  accept="image/jpg,image/jpeg,image/png,image/gif"' +
      '  required>'
    );
  });

  $(document).on('click', '.js-custFile-img-list-single-delete', function (e) {
    $(this).closest('.js-custFile-img-list-single').remove();

    if ($('.js-custFile-img-list').find('.js-custFile-img-list-single').length == 4) {
      $('#sertificateElectric').find('.custLabel').show();
    }

    if ($('.js-custFile-img-list').find('.js-custFile-img-list-single').length == 1) {
      $('.js-custFile-img').show();
      $('.js-custFile-img-alone').hide();
      $('.js-custFile-img-list').hide();
      $('.js-custFile-img-form').find('[type=submit]').attr({'disabled': 'disabled'});
    }
  });


  $(document).on('change', '.js-custFile [type=file]', function (e) {
    let maxSize = 999999999999999999999; //Максимальный размер файла
    if ($(this).attr('data-max-size') != undefined) {
      maxSize = parseInt($(this).attr('data-max-size'));
    }
    let list = $(this).closest('.js-custFile').find('.js-custFile-list');
    let single = $(this).closest('.js-custFile').find('.js-custFile-list-single');
    let response = $(this).closest('.js-custFile').find('.js-custFile-response');
    if (this.files[0] != undefined) {
      if (this.files[0].size > maxSize) {
        $(this).val();
        list.hide();
        response.show();
      } else {
        response.hide();
        single.text(this.files[0].name).attr({'title': this.files[0].name});
        list.show();
      }
    }
  });

  //Подстановка текста в поле
  $(document).on('click', '.js-substitution-button', function (e) {
    e.preventDefault();
    let inputId = $(this).attr('data-input');
    let value = $(this).attr('data-value');
    $('#' + inputId).val(value).trigger('chosen:updated');
    labelTransfer($('#' + inputId));
  });

  //Кнопка показать пароль
  $(document).on('click', '.js-showPassButton', function (event) {
    event.preventDefault();
    $(this).toggleClass('selected');
    let that = $(this);
    $(this).closest('.formGroup').find('.passwordField').each(function () {
      if (that.hasClass('selected')) {
        $(this).attr({'type': 'text'});
        $(this).focus();
      } else {
        $(this).attr({'type': 'password'});
        $(this).focus();
      }
    });
  });


  $('.formGroup input,textarea').focus(function (event) {
    labelTransfer($(this), true);
  });
  $('.formGroup input,textarea').blur(function (event) {
    labelTransfer($(this));
  });

  $(document).on('change input', '.formGroup select,input,textarea', function (event) {
    labelTransfer($(this));
    validCharacters($(this));
    validEmail($(this));
    validLength($(this));
    requiredFull($(this));
    unlockSubmit($(this));
  });
  $(document).on('keyup', '.formGroup input,textarea', function (event) {
    labelTransfer($(this));
    validCharacters($(this));
    validEmail($(this));
    validLength($(this));
    requiredFull($(this));
    strongPass($(this));
    commPass($(this));
    unlockSubmit($(this));
  });
  $('input,select,textarea').each(function () {
    if ($(this).attr('placeholder') != undefined) {
      $(this).attr({'data-placeholder': $(this).attr('placeholder')});
    }
  });
});
