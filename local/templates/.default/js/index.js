import sprite from './sprite.js'; //Генератор svg спрайта
import './ajax.js'; //Ajax
import './components/forms.js'; //Работа с формами
import './register.js'; //Ajax
import './marketplace.js';
import './marketplace-detail.js';
import './articles.js';
import './service.js';
import { setCookie } from './functions.js';

'use strict';

//Действия при изменении размера окна или перевороте экрана телефона
function forResize() {
    let coef = 1;
    let width = 'device-width';
    let screenSide = screen.width;
    if (screen.width > screen.height) {
        screenSide = screen.height;
    }
    if (screenSide < 360) {
        coef = (screenSide / 360).toFixed(2);
    }
    $('meta[name=viewport]').attr('content', 'width=' + width + ', minimum-scale=' + coef + ', initial-scale=' + coef + ', maximum-scale=3, user-scalable=yes');

    let footerHeight = 0;
    if ($('.js-footer').length != 0) {
        footerHeight = $('.js-footer').outerHeight(true);
    }
    let appHeight = window.innerHeight - footerHeight;
    // console.log($(document).height());
    // console.log(window.innerHeight);
    $('.js-app-inner').css({ 'minHeight': appHeight });

    if ($('.js-showhide').length != 0) {
        if ($(window).width() > 991) {
            $('.js-showhide').each(function () {
                $(this).find('.js-showhide-toggler').removeClass('active');
                $(this).find('.js-showhide-content').slideDown();
            });
        }
    }

    $('.js-header-helper').height($('.js-header').height());
}

//Действия при работе с хешем
function hashWork() {
    let currentHash = window.location.hash.substr(1);
    if ($('.js-slider-tabs').length != 0) {
        if (currentHash == 'client' || currentHash == 'performer') {
            toggleTab($('.js-slider-tabs').closest('.js-tabs').find('.js-tabs-buttons-single-inner[href="#' + currentHash + '"]'), true);
        } else {
            toggleTab($('.js-slider-tabs').closest('.js-tabs').find('.js-tabs-buttons-single-inner.active'), true);
        }
    }
}

//Переключение табов
function toggleTab(that) {
    let skipAnimate = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    let currentId = that.attr('href');
    let currentIndexSlide = that.closest('.js-tabs').find('.js-tabs-content [data-id=' + currentId.substr(1) + ']').index();
    // if(history.pushState) {
    //     history.pushState(null, null, currentId);
    // } else {
    //     window.location.hash = currentId;
    // }
    that.closest('.js-tabs-buttons').find('.js-tabs-buttons-single-inner').each(function () {
        if ($(this).attr('href') == currentId) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
    that.closest('.js-tabs').find('.js-tabs-content').slick('slickGoTo', currentIndexSlide, skipAnimate);
}

//Показать скрыть блок с вопросом "Ваш город"
function cityQuestToggle () {
    $('.js-header-city__quest-content').toggleClass('active');
}

//Закрашивание звезд рейтинга
function starRaiting (that, number) {
    let rait = 0;
    // let rait = 0;
    if (number) {
        rait = Number(number);
    } else {
        rait = Number(that.attr('data-rait').replace(',','.').replace(' ',''));
    }
    let wrWidth = that.closest('.js-raitWr').width();
    let fullNumber = Math.floor(rait); //Расчет количества полностью закрашенных звезд
    let fractionNumber = Number(String(rait).split('.')[1] || 0)/10; //Получение дробной части
    let percentLast = Math.asin( 2 * fractionNumber - 1 ) / Math.PI + 0.5; //Расчет степени закрашивания последней звезды по синусоиде
    let percentStar = 20; //Сколько процентов занимает одна звезда
    let result = (fullNumber * percentStar) + (percentLast * percentStar);
    //Переводим проценты в пиксели, чтобы браузер не делал ширину дробями,
    // иначе некоторые дроби выглядят не совершенно, например (4,5)
    let widthRait = Math.ceil(wrWidth * (result/100));
    that.css({'width':widthRait});
}

//Определение мобильников и планшетов
window.mobileAndTabletcheck = function() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};

$(document).ready(function () {

    if ($('.js-formSolo').length != 0) {
        $('.js-app-inner').addClass('app-inner_flex');
    }

    if ($.fn.chosen) {
        $('.js-chosen').chosen({
            no_results_text: 'Нет результатов по запросу:',
            disable_search_threshold: 10
        }).change(function(){
            $(this).next('.chosen-container').addClass('active');
        });
    }
    if (mobileAndTabletcheck) {
        $('.js-chosen').each(function () {
            let placeholder = $(this).attr('data-placeholder');
            $(this).find('option:first-child').text(placeholder);
        });
    }

    $('a').each(function () {
        let href = $(this).attr('href');
        if (href != undefined) {
            if ((href.indexOf("http") != -1 || href.indexOf(".pdf") != -1) && href.indexOf("://" + location.hostname) == -1) {
                $(this).attr('target', '_blank');
            }
        }
    });



    //Закрашивание звезд рейтинга
    if ($('.js-rait').length != 0) {
        $('.js-rait').each(function () {
            starRaiting ($(this));
        });
    }
    $('.js-raitRadio__radio-tag').hover(
        function() {
            let target = $(this).closest('.js-raitRadio').find('.js-rait');
            let raiting = $(this).val();
            starRaiting (target, raiting);
        }, function() {
            let target = $(this).closest('.js-raitRadio').find('.js-rait');
            let raiting = $(this).closest('.js-raitRadio').find('.js-raitRadio__radio-tag:checked').val();
            starRaiting (target, raiting);
        }
    );

    $('.formGroup [type=tel]').inputmask({
        mask: '+7 (999) 999-99-99',
        showMaskOnHover: false,
        clearIncomplete: true
    });



    if ($.fn.slick) {
        $('.js-slider-formSolo').slick({
            arrows: false,
            dots: true,
            rows: 0,
        });

        $('.js-slider-tabs').slick({
            arrows: false,
            dots: false,
            infinite: false,
            swipe: false,
            rows: 0,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        adaptiveHeight: true,
                    }
                },
            ]
        });
        $('.js-htmlArea-slider').slick({
            arrows: true,
            dots: true,
            rows: 0,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                    }
                },
            ]
        });
        $('.js-articlesPage-helper-articles').slick({
            arrows: false,
            dots: true,
            rows: 0,
            variableWidth: true,
        });
    }

    $('[data-fancybox]').fancybox({
        touch: false,
        // afterShow: function(instance, current) {
        //     console.log(1);
        //     let form = $(current.$content[0]).find('form');
        //     if (form.length != 0) {
        //         unlockSubmit(form.find('[type=submit]'));
        //     }
        // }
    });

    //Показать/скрыть блок
    $(document).on('click', '.js-showhide-toggler', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).closest('.js-showhide').find('.js-showhide-content').slideToggle();
    });
    function functionsMenuToggle (that) {
        $('.js-header-user').each(function () {
            $(this).removeClass('active');
            $(this).closest('.js-header-person').find('.js-header-person-menu').removeClass('active');
        });
        setTimeout(function () {
            if (that.hasClass('active')) {
                that.toggleClass('active');
                that.closest('.js-header-person').find('.js-header-person-menu').toggleClass('active');
            } else {
                that.toggleClass('active');
                that.closest('.js-header-person').find('.js-header-person-menu').toggleClass('active');
            }
        }, 10);


    }
    $(document).on('click', '.js-header-user', function (e) {
        e.preventDefault();
        functionsMenuToggle ($(this));
    });
    $(document).on('click', 'body', function (e) {
        if ($('.js-header-user')) {
            $('.js-header-user').each(function () {
                if ($(this).hasClass('active') && $(e.target).closest('.js-header-person').length == 0) {
                    $('.js-header-user').removeClass('active');
                    $('.js-header-user').closest('.js-header-person').find('.js-header-person-menu').removeClass('active');
                }
            });

        }

        if ($('.js-header-city__quest-content')) {
            if ($('.js-header-city__quest-content').hasClass('active') && $(e.target).closest('.js-header-city__quest-content').length == 0) {
                cityQuestToggle ();
            }
        }

    });
    $(document).on('click', '.js-header-city__quest-toggler', function (e) {
        e.preventDefault();
        cityQuestToggle ();
    });
    $(document).on('click', '.js-showhide2-toggler', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).closest('.js-showhide2').find('.js-showhide2-content').slideToggle();
    });

    $(document).on('click', '.js-tpQuestions-toggler', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).closest('.js-tpQuestions-single').toggleClass('active');
        $(this).closest('.js-tpQuestions-single').find('.js-tpQuestions-text').toggleClass('active');
        $(this).closest('.js-tpQuestions-single').find('.js-tpQuestions-answer').toggleClass('active');
    });



    //Показать контакты
    $(document).on('click', '.js-marketDetail-about-helper-contactsButton', function (e) {
        e.preventDefault();
        $('.js-marketDetail-about-helper-contacts').slideToggle(300);
        $(this).slideToggle(300);
    });

    //Показать контакты2
    $(document).on('click', '.js-marketDetail-about-helper-contactsButton_mobile', function (e) {
        e.preventDefault();
        $('.js-marketDetail-about-helper-contacts_mobile').slideToggle(300);
        $(this).slideToggle(300);
    });

    //Скрыть оповещение
    $(document).on('click', '.js-notification-close', function (e) {
        e.preventDefault();
        $(this).closest('.js-notification').slideUp(200);
    });

    //Показывать скрытые работы
    if ($('.js-marketDetail-works-list').length != 0) {
        let amount = $('.js-marketDetail-works-list__single').length;
        if (amount > 4) {
            let height = $('.js-marketDetail-works-list__single').outerHeight();
            $('.js-marketDetail-works-list').height(height*2);
        }
    }
    $(document).on('click', '.js-marketDetail-works-more', function (e) {
        e.preventDefault();
        let amount = $('.js-marketDetail-works-list__single').length;
        let height = $('.js-marketDetail-works-list__single').outerHeight();
        let widthPa = $('.js-marketDetail-works-list').outerWidth();
        let width = $('.js-marketDetail-works-list__single').outerWidth();
        let percent = width/widthPa;
        let list = $(this).closest('.js-marketDetail-works').find('.js-marketDetail-works-list');
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            list.height(height*Math.ceil(amount*percent));
        } else {
            list.height(height*2);
        }
    });


    //Показывать скрытые услуги
    $(document).on('click', '.js-marketDetail-about-serviceList__more', function (e) {
        e.preventDefault();
        let that = $(this);
        let height = 0;
        $(this).toggleClass('active');
        $(this).closest('.js-marketDetail-about-serviceList__single')
            .find('.js-marketDetail-about-serviceList__sublist-single')
            .each(function (index) {
                height = height + $(this).height();
                if (that.hasClass('active') == false && index == 2) {
                    return false;
                }
            });
        $(this).closest('.js-marketDetail-about-serviceList__single')
            .find('.js-marketDetail-about-serviceList__sublist').height(height);
    });
    if ($('.js-marketDetail-about-serviceList__sublist').length != 0) {
        $('.js-marketDetail-about-serviceList__sublist').each(function () {
            let height = 0;
            $(this).find('.js-marketDetail-about-serviceList__sublist-single').each(function (index) {
                height = height + $(this).height();
                if (index == 2) {
                    return false;
                }
            });
            $(this).height(height);
        });
    }

    //Переключение табов
    $(document).on('click', '.js-tabs-buttons-single-inner', function (e) {
        e.preventDefault();
        if ($(this).hasClass('js-set-cookie')) {
          setCookie('social-user-type', $(this).attr('href'));
        }
        toggleTab($(this));
    });

    // Скрыть/показать меню
    $(document).on('click', '.js-mainMenu-toggle', function (e) {
        e.preventDefault();
        $('.js-mainMenu').toggleClass('menuActive');
        $('.js-menuUnder').toggleClass('menuActive');
    });

    // Скрыть/показать уведомления
    $(document).on('click', '.js-notification-toggle', function (e) {
        e.preventDefault();
        let $alert = $(e.target).closest('.header-person__alert');
        if ($alert.hasClass('new')) {
            $.post( "/api/notifications.update.viewed/", {});
            $alert.removeClass('new');
        }
    });

    function forScroll () {
        if ($(window).scrollTop() > 1000) {
            $('.js-upWindow').addClass('active');
        } else {
            $('.js-upWindow').removeClass('active');
        }
    }
    forScroll ();

    $(window).on('scroll', function () {
        forScroll();
    });

    // Скрыть/показать меню бекофиса
    $(document).on('click', '.js-backofficeMenu-toggle', function (e) {
        e.preventDefault();
        $('.js-backofficeMenu-toggle').toggleClass('menuActive');
        $('.js-backofficeMenu').toggleClass('menuActive');
    });

    forResize();
    $(window).resize(function () {
        forScroll();
        forResize();
    });
    window.addEventListener("orientationchange", function () {
        forResize();
    }, false);

    hashWork();

    $(document).on('click', '.js-upWindow', function () {
        $('html, body').animate({
            scrollTop: 0
        }, 200);
    });



});
$(window).on('load', function () {
    forResize();
});
