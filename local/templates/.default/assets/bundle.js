webpackJsonp([0],[
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

var Sprite = __webpack_require__(262);
var globalSprite = new Sprite();

if (document.body) {
  globalSprite.elem = globalSprite.render(document.body);
} else {
  document.addEventListener('DOMContentLoaded', function () {
    globalSprite.elem = globalSprite.render(document.body);
  }, false);
}

module.exports = globalSprite;


/***/ }),
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */,
/* 12 */,
/* 13 */,
/* 14 */,
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */,
/* 19 */,
/* 20 */,
/* 21 */,
/* 22 */,
/* 23 */,
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */,
/* 41 */,
/* 42 */,
/* 43 */,
/* 44 */,
/* 45 */,
/* 46 */,
/* 47 */,
/* 48 */,
/* 49 */,
/* 50 */,
/* 51 */,
/* 52 */,
/* 53 */,
/* 54 */,
/* 55 */,
/* 56 */,
/* 57 */,
/* 58 */,
/* 59 */,
/* 60 */,
/* 61 */,
/* 62 */,
/* 63 */,
/* 64 */,
/* 65 */,
/* 66 */,
/* 67 */,
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.setCookie = setCookie;
function setCookie(name, value) {
  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};


  options = {
    path: '/'
  };

  var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

  for (var optionKey in options) {
    updatedCookie += "; " + optionKey;
    var optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }

  document.cookie = updatedCookie;
}

/***/ }),
/* 69 */,
/* 70 */,
/* 71 */,
/* 72 */,
/* 73 */,
/* 74 */,
/* 75 */,
/* 76 */,
/* 77 */,
/* 78 */,
/* 79 */,
/* 80 */,
/* 81 */,
/* 82 */,
/* 83 */,
/* 84 */,
/* 85 */,
/* 86 */,
/* 87 */,
/* 88 */,
/* 89 */,
/* 90 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(process) {// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.

// resolves . and .. elements in a path array with directory names there
// must be no slashes, empty elements, or device names (c:\) in the array
// (so also no leading and trailing slashes - it does not distinguish
// relative and absolute paths)
function normalizeArray(parts, allowAboveRoot) {
  // if the path tries to go above the root, `up` ends up > 0
  var up = 0;
  for (var i = parts.length - 1; i >= 0; i--) {
    var last = parts[i];
    if (last === '.') {
      parts.splice(i, 1);
    } else if (last === '..') {
      parts.splice(i, 1);
      up++;
    } else if (up) {
      parts.splice(i, 1);
      up--;
    }
  }

  // if the path is allowed to go above the root, restore leading ..s
  if (allowAboveRoot) {
    for (; up--; up) {
      parts.unshift('..');
    }
  }

  return parts;
}

// Split a filename into [root, dir, basename, ext], unix version
// 'root' is just a slash, or nothing.
var splitPathRe =
    /^(\/?|)([\s\S]*?)((?:\.{1,2}|[^\/]+?|)(\.[^.\/]*|))(?:[\/]*)$/;
var splitPath = function(filename) {
  return splitPathRe.exec(filename).slice(1);
};

// path.resolve([from ...], to)
// posix version
exports.resolve = function() {
  var resolvedPath = '',
      resolvedAbsolute = false;

  for (var i = arguments.length - 1; i >= -1 && !resolvedAbsolute; i--) {
    var path = (i >= 0) ? arguments[i] : process.cwd();

    // Skip empty and invalid entries
    if (typeof path !== 'string') {
      throw new TypeError('Arguments to path.resolve must be strings');
    } else if (!path) {
      continue;
    }

    resolvedPath = path + '/' + resolvedPath;
    resolvedAbsolute = path.charAt(0) === '/';
  }

  // At this point the path should be resolved to a full absolute path, but
  // handle relative paths to be safe (might happen when process.cwd() fails)

  // Normalize the path
  resolvedPath = normalizeArray(filter(resolvedPath.split('/'), function(p) {
    return !!p;
  }), !resolvedAbsolute).join('/');

  return ((resolvedAbsolute ? '/' : '') + resolvedPath) || '.';
};

// path.normalize(path)
// posix version
exports.normalize = function(path) {
  var isAbsolute = exports.isAbsolute(path),
      trailingSlash = substr(path, -1) === '/';

  // Normalize the path
  path = normalizeArray(filter(path.split('/'), function(p) {
    return !!p;
  }), !isAbsolute).join('/');

  if (!path && !isAbsolute) {
    path = '.';
  }
  if (path && trailingSlash) {
    path += '/';
  }

  return (isAbsolute ? '/' : '') + path;
};

// posix version
exports.isAbsolute = function(path) {
  return path.charAt(0) === '/';
};

// posix version
exports.join = function() {
  var paths = Array.prototype.slice.call(arguments, 0);
  return exports.normalize(filter(paths, function(p, index) {
    if (typeof p !== 'string') {
      throw new TypeError('Arguments to path.join must be strings');
    }
    return p;
  }).join('/'));
};


// path.relative(from, to)
// posix version
exports.relative = function(from, to) {
  from = exports.resolve(from).substr(1);
  to = exports.resolve(to).substr(1);

  function trim(arr) {
    var start = 0;
    for (; start < arr.length; start++) {
      if (arr[start] !== '') break;
    }

    var end = arr.length - 1;
    for (; end >= 0; end--) {
      if (arr[end] !== '') break;
    }

    if (start > end) return [];
    return arr.slice(start, end - start + 1);
  }

  var fromParts = trim(from.split('/'));
  var toParts = trim(to.split('/'));

  var length = Math.min(fromParts.length, toParts.length);
  var samePartsLength = length;
  for (var i = 0; i < length; i++) {
    if (fromParts[i] !== toParts[i]) {
      samePartsLength = i;
      break;
    }
  }

  var outputParts = [];
  for (var i = samePartsLength; i < fromParts.length; i++) {
    outputParts.push('..');
  }

  outputParts = outputParts.concat(toParts.slice(samePartsLength));

  return outputParts.join('/');
};

exports.sep = '/';
exports.delimiter = ':';

exports.dirname = function(path) {
  var result = splitPath(path),
      root = result[0],
      dir = result[1];

  if (!root && !dir) {
    // No dirname whatsoever
    return '.';
  }

  if (dir) {
    // It has a dirname, strip trailing slash
    dir = dir.substr(0, dir.length - 1);
  }

  return root + dir;
};


exports.basename = function(path, ext) {
  var f = splitPath(path)[2];
  // TODO: make this comparison case-insensitive on windows?
  if (ext && f.substr(-1 * ext.length) === ext) {
    f = f.substr(0, f.length - ext.length);
  }
  return f;
};


exports.extname = function(path) {
  return splitPath(path)[3];
};

function filter (xs, f) {
    if (xs.filter) return xs.filter(f);
    var res = [];
    for (var i = 0; i < xs.length; i++) {
        if (f(xs[i], i, xs)) res.push(xs[i]);
    }
    return res;
}

// String.prototype.substr - negative index don't work in IE8
var substr = 'ab'.substr(-1) === 'b'
    ? function (str, start, len) { return str.substr(start, len) }
    : function (str, start, len) {
        if (start < 0) start = str.length + start;
        return str.substr(start, len);
    }
;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(91)))

/***/ }),
/* 91 */,
/* 92 */,
/* 93 */
/***/ (function(module, exports) {

/* (ignored) */

/***/ }),
/* 94 */,
/* 95 */,
/* 96 */,
/* 97 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

var _sprite = __webpack_require__(197);

var _sprite2 = _interopRequireDefault(_sprite);

__webpack_require__(190);

__webpack_require__(192);

__webpack_require__(195);

__webpack_require__(194);

__webpack_require__(193);

__webpack_require__(191);

__webpack_require__(196);

var _functions = __webpack_require__(68);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

//Работа с формами
//Генератор svg спрайта
'use strict';

//Действия при изменении размера окна или перевороте экрана телефона
//Ajax
//Ajax
function forResize() {
    var coef = 1;
    var width = 'device-width';
    var screenSide = screen.width;
    if (screen.width > screen.height) {
        screenSide = screen.height;
    }
    if (screenSide < 360) {
        coef = (screenSide / 360).toFixed(2);
    }
    $('meta[name=viewport]').attr('content', 'width=' + width + ', minimum-scale=' + coef + ', initial-scale=' + coef + ', maximum-scale=3, user-scalable=yes');

    var footerHeight = 0;
    if ($('.js-footer').length != 0) {
        footerHeight = $('.js-footer').outerHeight(true);
    }
    var appHeight = window.innerHeight - footerHeight;
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
    var currentHash = window.location.hash.substr(1);
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
    var skipAnimate = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    var currentId = that.attr('href');
    var currentIndexSlide = that.closest('.js-tabs').find('.js-tabs-content [data-id=' + currentId.substr(1) + ']').index();
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
function cityQuestToggle() {
    $('.js-header-city__quest-content').toggleClass('active');
}

//Закрашивание звезд рейтинга
function starRaiting(that, number) {
    var rait = 0;
    // let rait = 0;
    if (number) {
        rait = Number(number);
    } else {
        rait = Number(that.attr('data-rait').replace(',', '.').replace(' ', ''));
    }
    var wrWidth = that.closest('.js-raitWr').width();
    var fullNumber = Math.floor(rait); //Расчет количества полностью закрашенных звезд
    var fractionNumber = Number(String(rait).split('.')[1] || 0) / 10; //Получение дробной части
    var percentLast = Math.asin(2 * fractionNumber - 1) / Math.PI + 0.5; //Расчет степени закрашивания последней звезды по синусоиде
    var percentStar = 20; //Сколько процентов занимает одна звезда
    var result = fullNumber * percentStar + percentLast * percentStar;
    //Переводим проценты в пиксели, чтобы браузер не делал ширину дробями,
    // иначе некоторые дроби выглядят не совершенно, например (4,5)
    var widthRait = Math.ceil(wrWidth * (result / 100));
    that.css({ 'width': widthRait });
}

//Определение мобильников и планшетов
window.mobileAndTabletcheck = function () {
    var check = false;
    (function (a) {
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
    })(navigator.userAgent || navigator.vendor || window.opera);
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
        }).change(function () {
            $(this).next('.chosen-container').addClass('active');
        });
    }
    if (mobileAndTabletcheck) {
        $('.js-chosen').each(function () {
            var placeholder = $(this).attr('data-placeholder');
            $(this).find('option:first-child').text(placeholder);
        });
    }

    $('a').each(function () {
        var href = $(this).attr('href');
        if (href != undefined) {
            if ((href.indexOf("http") != -1 || href.indexOf(".pdf") != -1) && href.indexOf("://" + location.hostname) == -1) {
                $(this).attr('target', '_blank');
            }
        }
    });

    //Закрашивание звезд рейтинга
    if ($('.js-rait').length != 0) {
        $('.js-rait').each(function () {
            starRaiting($(this));
        });
    }
    $('.js-raitRadio__radio-tag').hover(function () {
        var target = $(this).closest('.js-raitRadio').find('.js-rait');
        var raiting = $(this).val();
        starRaiting(target, raiting);
    }, function () {
        var target = $(this).closest('.js-raitRadio').find('.js-rait');
        var raiting = $(this).closest('.js-raitRadio').find('.js-raitRadio__radio-tag:checked').val();
        starRaiting(target, raiting);
    });

    $('.formGroup [type=tel]').inputmask({
        mask: '+7 (999) 999-99-99',
        showMaskOnHover: false,
        clearIncomplete: true
    });

    if ($.fn.slick) {
        $('.js-slider-formSolo').slick({
            arrows: false,
            dots: true,
            rows: 0
        });

        $('.js-slider-tabs').slick({
            arrows: false,
            dots: false,
            infinite: false,
            swipe: false,
            rows: 0,
            responsive: [{
                breakpoint: 767,
                settings: {
                    adaptiveHeight: true
                }
            }]
        });
        $('.js-htmlArea-slider').slick({
            arrows: true,
            dots: true,
            rows: 0,
            adaptiveHeight: true,
            responsive: [{
                breakpoint: 767,
                settings: {
                    arrows: false
                }
            }]
        });
        $('.js-articlesPage-helper-articles').slick({
            arrows: false,
            dots: true,
            rows: 0,
            variableWidth: true
        });
    }

    $('[data-fancybox]').fancybox({
        touch: false
    });

    //Показать/скрыть блок
    $(document).on('click', '.js-showhide-toggler', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $(this).closest('.js-showhide').find('.js-showhide-content').slideToggle();
    });
    function functionsMenuToggle(that) {
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
        functionsMenuToggle($(this));
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
                cityQuestToggle();
            }
        }
    });
    $(document).on('click', '.js-header-city__quest-toggler', function (e) {
        e.preventDefault();
        cityQuestToggle();
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
        var amount = $('.js-marketDetail-works-list__single').length;
        if (amount > 4) {
            var height = $('.js-marketDetail-works-list__single').outerHeight();
            $('.js-marketDetail-works-list').height(height * 2);
        }
    }
    $(document).on('click', '.js-marketDetail-works-more', function (e) {
        e.preventDefault();
        var amount = $('.js-marketDetail-works-list__single').length;
        var height = $('.js-marketDetail-works-list__single').outerHeight();
        var widthPa = $('.js-marketDetail-works-list').outerWidth();
        var width = $('.js-marketDetail-works-list__single').outerWidth();
        var percent = width / widthPa;
        var list = $(this).closest('.js-marketDetail-works').find('.js-marketDetail-works-list');
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            list.height(height * Math.ceil(amount * percent));
        } else {
            list.height(height * 2);
        }
    });

    //Показывать скрытые услуги
    $(document).on('click', '.js-marketDetail-about-serviceList__more', function (e) {
        e.preventDefault();
        var that = $(this);
        var height = 0;
        $(this).toggleClass('active');
        $(this).closest('.js-marketDetail-about-serviceList__single').find('.js-marketDetail-about-serviceList__sublist-single').each(function (index) {
            height = height + $(this).height();
            if (that.hasClass('active') == false && index == 2) {
                return false;
            }
        });
        $(this).closest('.js-marketDetail-about-serviceList__single').find('.js-marketDetail-about-serviceList__sublist').height(height);
    });
    if ($('.js-marketDetail-about-serviceList__sublist').length != 0) {
        $('.js-marketDetail-about-serviceList__sublist').each(function () {
            var height = 0;
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
            (0, _functions.setCookie)('social-user-type', $(this).attr('href'));
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
        var $alert = $(e.target).closest('.header-person__alert');
        if ($alert.hasClass('new')) {
            $.post("/api/notifications.update.viewed/", {});
            $alert.removeClass('new');
        }
    });

    function forScroll() {
        if ($(window).scrollTop() > 1000) {
            $('.js-upWindow').addClass('active');
        } else {
            $('.js-upWindow').removeClass('active');
        }
    }
    forScroll();

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
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 98 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(214);

var files = __webpack_require__(188);
files.keys().forEach(files);

/***/ }),
/* 99 */,
/* 100 */,
/* 101 */,
/* 102 */,
/* 103 */,
/* 104 */,
/* 105 */,
/* 106 */,
/* 107 */,
/* 108 */,
/* 109 */,
/* 110 */,
/* 111 */,
/* 112 */,
/* 113 */,
/* 114 */,
/* 115 */,
/* 116 */,
/* 117 */,
/* 118 */,
/* 119 */,
/* 120 */,
/* 121 */,
/* 122 */,
/* 123 */,
/* 124 */,
/* 125 */,
/* 126 */,
/* 127 */,
/* 128 */,
/* 129 */,
/* 130 */,
/* 131 */,
/* 132 */,
/* 133 */,
/* 134 */,
/* 135 */,
/* 136 */,
/* 137 */,
/* 138 */,
/* 139 */,
/* 140 */,
/* 141 */,
/* 142 */,
/* 143 */,
/* 144 */,
/* 145 */,
/* 146 */,
/* 147 */,
/* 148 */,
/* 149 */,
/* 150 */,
/* 151 */,
/* 152 */,
/* 153 */,
/* 154 */,
/* 155 */,
/* 156 */,
/* 157 */,
/* 158 */,
/* 159 */,
/* 160 */,
/* 161 */,
/* 162 */,
/* 163 */,
/* 164 */,
/* 165 */,
/* 166 */,
/* 167 */,
/* 168 */,
/* 169 */,
/* 170 */,
/* 171 */,
/* 172 */,
/* 173 */,
/* 174 */,
/* 175 */,
/* 176 */,
/* 177 */,
/* 178 */,
/* 179 */,
/* 180 */,
/* 181 */,
/* 182 */,
/* 183 */,
/* 184 */,
/* 185 */,
/* 186 */,
/* 187 */,
/* 188 */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./_articles.scss": 215,
	"./_articlesPage.scss": 216,
	"./_breadcrumbs.scss": 217,
	"./_footer.scss": 218,
	"./_forWho.scss": 219,
	"./_formSolo.scss": 220,
	"./_header.scss": 221,
	"./_mainMenu.scss": 222,
	"./_marketplace.scss": 223,
	"./_notification.scss": 224,
	"./_search.scss": 225,
	"./_stages.scss": 226,
	"./_types.scss": 227,
	"./backoffice.scss": 228
};
function webpackContext(req) {
	return __webpack_require__(webpackContextResolve(req));
};
function webpackContextResolve(req) {
	var id = map[req];
	if(!(id + 1)) // check for number or string
		throw new Error("Cannot find module '" + req + "'.");
	return id;
};
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 188;

/***/ }),
/* 189 */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./add.svg": 231,
	"./arrow-down.svg": 232,
	"./bell.svg": 233,
	"./burger.svg": 234,
	"./close.svg": 235,
	"./comm.svg": 236,
	"./comment.svg": 237,
	"./cross.svg": 238,
	"./dislike.svg": 239,
	"./download.svg": 240,
	"./edit.svg": 241,
	"./fb.svg": 242,
	"./geo.svg": 243,
	"./like.svg": 244,
	"./logo-2.svg": 245,
	"./logo.svg": 246,
	"./notification.svg": 247,
	"./ok.svg": 248,
	"./preloader.svg": 249,
	"./red-cross.svg": 250,
	"./search.svg": 251,
	"./stages-design.svg": 252,
	"./stages-installation.svg": 253,
	"./stages-repairs.svg": 254,
	"./star-grey-5.svg": 255,
	"./star-grey.svg": 256,
	"./star-red-5.svg": 257,
	"./star-red.svg": 258,
	"./video.svg": 259,
	"./vk.svg": 260,
	"./zoom.svg": 261
};
function webpackContext(req) {
	return __webpack_require__(webpackContextResolve(req));
};
function webpackContextResolve(req) {
	var id = map[req];
	if(!(id + 1)) // check for number or string
		throw new Error("Cannot find module '" + req + "'.");
	return id;
};
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 189;

/***/ }),
/* 190 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function collect(that) {
    var fields = that.serializeObject();
    var data = {
        fields: fields,
        formId: that.attr('id')
    };
    return data;
}

function errorInput(response) {
    var errorBlock = $('[name=' + response['error']['field'] + ']').closest('.formGroup').find('.js-errorInput');
    var inputWr = $('[name=' + response['error']['field'] + ']').closest('.formGroup');
    if (response['error'].length != 0 && errorBlock.length != 0) {
        errorBlock.text(response['error']['text']);
        inputWr.addClass('errorInput');
    }
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 191 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($, jQuery) {

$(document).ready(function () {

  var $articlesSearch = $('[data-articles-search]');
  var $articlesFilter = $('[data-articles-filter]');
  var $articlesList = $('.js-articles-list');
  var $articlesClear = $('.js-articles-filter-clear');
  var $commentsList = $('.js-comments-list');

  /**
   * $.parseParams - parse query string paramaters into an object.
   */
  (function ($) {
    var re = /([^&=]+)=?([^&]*)/g;
    var decodeRE = /\+/g;
    var decode = function decode(str) {
      return decodeURIComponent(str.replace(decodeRE, " "));
    };
    $.parseParams = function (query) {
      var params = {},
          e;
      while (e = re.exec(query)) {
        var k = decode(e[1]),
            v = decode(e[2]);
        if (k.substring(k.length - 2) === '[]') {
          k = k.substring(0, k.length - 2);
          (params[k] || (params[k] = [])).push(v);
        } else params[k] = v;
      }
      return params;
    };
  })(jQuery);

  if ($articlesSearch.length) {
    $articlesSearch.on('submit', function (e) {
      $articlesSearch.find('input')[0].value = $articlesSearch.find('input')[0].value.replace(/</g, "< ");
    });
  }

  $('.js-articles-filter-type').on('click', function (e) {
    e.preventDefault();
    var $typeElement = $(e.delegateTarget);
    var typeID = !$typeElement.hasClass('active') ? $typeElement.data('type') : '';
    var fields = void 0;
    if ($articlesFilter.attr('data-filter-params')) {
      var filterParams = $.parseParams($articlesFilter.attr('data-filter-params'));
      filterParams.type = typeID;
      fields = $.param(filterParams);
    } else {
      fields = "&type=" + typeID;
    }
    $.ajax({
      url: '',
      data: fields,
      type: 'GET',
      success: function success(data) {
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
      }
    });
  });

  $('.js-articles-filter-tag').on('click', function (e) {
    e.preventDefault();
    var $tagElement = $(e.delegateTarget);
    var tagID = !$tagElement.hasClass('active') ? $tagElement.data('tag') : '';
    var fields = void 0;
    if ($articlesFilter.attr('data-filter-params')) {
      var filterParams = $.parseParams($articlesFilter.attr('data-filter-params'));
      filterParams.tag = tagID;
      fields = $.param(filterParams);
    } else {
      fields = "&tag=" + tagID;
    }
    $.ajax({
      url: '',
      data: fields,
      type: 'GET',
      success: function success(data) {
        if (!$tagElement.hasClass('active')) {
          $('.js-articles-filter-tag').removeClass('active');
          $tagElement.addClass('active');
        } else {
          $('.js-articles-filter-tag').removeClass('active');
        }
        $articlesFilter.attr('data-filter-params', fields);
        if ($('.js-articles-filter-type').hasClass('active') || $('.js-articles-filter-tag').hasClass('active')) {
          $('.js-articles-filter-clear-block').addClass('show');
        } else {
          $('.js-articles-filter-clear-block').removeClass('show');
        }
        $articlesList.html(data);
        $articlesList.data('page', 1);
      }
    });
  });

  $articlesClear.on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: '',
      data: '',
      type: 'GET',
      success: function success(data) {
        $('.js-articles-filter-type').removeClass('active');
        $('.js-articles-filter-tag').removeClass('active');
        $articlesFilter.attr('data-filter-params', '');
        $('.js-articles-filter-clear-block').removeClass('show');
        $articlesList.html(data);
        $articlesList.data('page', 1);
      }
    });
  });

  // Пагинация статей
  $('.js-articles-more').on('click', function (e) {
    e.preventDefault();
    var nextPage = $articlesList.data('page') + 1;
    var fields = $articlesFilter.attr('data-filter-params');
    fields = fields + (fields ? '&' : '') + 'page=' + nextPage;
    $.ajax({
      url: "",
      data: fields,
      type: 'GET',
      success: function success(data) {
        $articlesList.data('page', nextPage);
        $articlesList.append(data);
      }
    });
  });

  // Пометка комментариев как прочитанных
  if ($commentsList.length) {
    if ($commentsList.attr('mark-as-viewed')) {
      $.post("/api/articles.update.viewed/", { articleID: $commentsList.attr('mark-as-viewed') });
    }
  }

  // Пагинация комментариев к статье
  $('.js-comments-more a').on('click', function (e) {
    e.preventDefault();

    var nextPage = $commentsList.data('page') + 1;
    var article = $commentsList.data('article');

    var fields = 'page=' + nextPage + '&article=' + article;

    $.ajax({
      url: '/ajax/articles-detail-comments.php',
      data: fields,
      type: 'GET',
      success: function success(data) {
        $commentsList.data('page', nextPage);
        $commentsList.append(data);
      }
    });
  });

  // Добавление, изменение или удаление оценки комментария
  $(document).on('click', '.js-comment-like', function (e) {
    e.preventDefault();
    var $like = $(e.target).closest('.js-comment-like');

    var fields = 'type=' + $like.data('type') + '&comment=' + $like.data('id');

    $.ajax({
      url: '/api/articles.comments.like/',
      data: fields,
      type: 'POST',
      success: function success(data) {
        if (data.result) {
          $like.parent().find('.js-comment-like--like .js-likes-count').html(data.result['LIKE']);
          $like.parent().find('.js-comment-like--dislike .js-likes-count').html(data.result['DISLIKE']);
          $('.js-comment-like[data-id=' + $like.data('id') + ']').removeClass('active');
          if (!data.result['IS_REMOVED']) {
            $like.addClass('active');
          }
        }
      }
    });
  });

  if ($('.js-articles-more').length != 0) {
    var focusButton = false;
    $(window).scroll(function () {

      if ($('#js-more-button').is(':visible')) {
        if ($(window).scrollTop() + window.innerHeight * 1.6 > $('.js-articles-more').offset().top && focusButton == false) {

          focusButton = true;
          var nextPage = $articlesList.data('page') + 1;
          var fields = $articlesFilter.attr('data-filter-params');
          fields = fields + (fields ? '&' : '') + 'page=' + nextPage;
          $.ajax({
            url: "",
            data: fields,
            type: 'GET',
            success: function success(data) {
              focusButton = false;

              $articlesList.data('page', nextPage);
              $articlesList.append(data);
            }
          });
        }
      }
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6), __webpack_require__(6)))

/***/ }),
/* 192 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

//Определение автозаполнения webkit браузеров
var isWebKit = !!window.webkitURL;

function checkWebkitAutofill(that) {
  var webkitAutofill = false;
  if (isWebKit) {
    webkitAutofill = that.is(':-webkit-autofill');
  }
  return webkitAutofill;
}

//Смещение label при фокусе или если в поле есть текст
function labelTransfer(that, focus) {
  if (that.closest('.formGroup').length != 0 && that.closest('.formGroup').find('label').length != 0 && that.closest('.formGroup').find('.custCheckbox').length == 0 && that.closest('.formGroup').find('.custRadio').length == 0) {

    if (that.val().length > 0 || checkWebkitAutofill(that) || focus) {
      if (that.attr('data-placeholder') != undefined) {
        that.attr({ 'placeholder': '' });
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
        that.attr({ 'placeholder': that.attr('data-placeholder') });
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
  var thisWrap = thisInp.closest('.formGroup');
  setTimeout(function () {
    var valArray = thisInp.val().split('');
    if (valArray[0] == ' ') {
      console.log(valArray);
      for (var i = 0; i < valArray.length; i++) {
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
  var thisWrap = thisInp.closest('.formGroup');
  var errorBlock = thisInp.closest('.formGroup').find('.js-errorInput');
  if (thisInp.attr('type') == 'email') {
    var pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
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
  var thisWrap = thisInp.closest('.formGroup');
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
  var form = thisInp.closest('form');
  if (form.find('.formGroup').length != 0) {
    if (form.find('.formGroup.required.notValid').length == 0 && form.find('.formGroup.notValidEmail').length == 0 && form.find('.formGroup.notValidLength').length == 0 && form.find('.formGroup.notValidPass').length == 0 && form.find('.formGroup.notValidCode').length == 0 && form.find('.formGroup.notStrongPass').length == 0 && form.find('.formGroup.notValidTel').length == 0 && form.find('.formGroup.lockCode').length == 0 && form.find('.formGroup.notValidCharacters').length == 0) {
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
  var valid = false;
  var thisWrap = thisInp.closest('.formGroup');
  if (thisInp.val() != '') {
    if (thisInp.attr('minlength') !== undefined || thisInp.attr('maxlength') !== undefined) {
      var thisVal = thisInp.val();
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
  var thisWrap = thisInp.closest('.formGroup');
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
      var error = thisWrap.closest('form').find('.formGroup[data-comPass=' + currentDataPass + ']').last().find('.js-errorInput');
      if (error.text().length == 0) {
        error.text('Пароли должны совпадать');
      }
    } else {
      var _error = thisWrap.closest('form').find('.formGroup[data-comPass=' + currentDataPass + ']').last().find('.js-errorInput');
      _error.text('');
      $('.formGroup[data-comPass=' + currentDataPass + ']').each(function () {
        $(this).removeClass('notValidPass');
      });
    }
  }
}

// Проверка надежности пароля
function strongPass(thisInp) {
  if (thisInp.attr('data-pass-set') == 'yes') {
    var thisWrap = thisInp.closest('.formGroup');
    var errorBlock = thisInp.closest('.formGroup').find('.js-errorInput');
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
    var that = $(this);
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
    var that = $(this);
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
    var that = $(this);
    setTimeout(function () {
      labelTransfer(that.closest('.js-marketDetail-comment-single').children('.js-marketDetail-reviews-list__answer-form').find('textarea'));
    }, 100);
  });

  // Очистка поля
  $(document).on('click', '.js-cleanIcon', function (e) {
    e.preventDefault();
    var input = $(this).closest('.formGroup').find('input');
    input.val('');
    setTimeout(function () {
      labelTransfer(input);
    }, 300);
  });

  // Очистка поля
  $(document).on('click', '#search_articles .js-cleanIcon', function (e) {
    e.preventDefault();
    var input = $(this).closest('.formGroup').find('input');
    input.val('');
    window.location = window.location.href.split("?")[0];
  });

  // Работа с полем типа файл
  var imagesPreview = function imagesPreview(input, placeToInsertImagePreview) {

    if (input.files) {
      var filesAmount = input.files.length;

      var _loop = function _loop(i) {
        var reader = new FileReader();
        reader.onload = function (event) {
          var single = placeToInsertImagePreview.find('.js-custFile-img-list-single:first-child').clone();
          single.attr({ 'data-index': i });
          single.find('img').attr({ 'src': event.target.result });
          placeToInsertImagePreview.append(single);
        };
        reader.readAsDataURL(input.files[i]);
      };

      for (var i = 0; i < filesAmount; i++) {
        _loop(i);
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

    var singleStart = $(this).closest('.formGroup').find('.js-custFile-img-list').find('.js-custFile-img-list-single:first-child').clone();
    $(this).closest('.formGroup').find('.js-custFile-img-list').html('');
    $(this).closest('.formGroup').find('.js-custFile-img-list').append(singleStart);
    imagesPreview(this, $(this).closest('.formGroup').find('.js-custFile-img-list'));
    $(this).closest('.formGroup').find('.js-custFile-img-list').css({ 'display': 'flex' });
    $(this).closest('.js-custFile-img').hide();
    $(this).closest('.formGroup').find('.js-custFile-img-alone').show();
  });

  $('.js-custFile-img-alone').on('change', '#sertificateElectric-pasport-alone', function () {
    var input = $('.js-custFile-img [type=file]');

    if (input.attr('data-limit')) {
      if ($('.js-custFile-img-list').find('.js-custFile-img-list-single').length == input.attr('data-limit')) {
        $('#sertificateElectric').find('.custLabel').hide();
      }
    }

    imagesPreview(this, $(this).closest('.formGroup').find('.js-custFile-img-list'));
    $(this).removeAttr('id');
    $('.js-custFile-img-alone-list').append('<input type="file"' + '  value=""' + '  name="sertificateElectric-pasport-alone[]"' + '  id="sertificateElectric-pasport-alone"' + '  accept="image/jpg,image/jpeg,image/png,image/gif"' + '  required>');
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
      $('.js-custFile-img-form').find('[type=submit]').attr({ 'disabled': 'disabled' });
    }
  });

  $(document).on('change', '.js-custFile [type=file]', function (e) {
    var maxSize = 999999999999999999999; //Максимальный размер файла
    if ($(this).attr('data-max-size') != undefined) {
      maxSize = parseInt($(this).attr('data-max-size'));
    }
    var list = $(this).closest('.js-custFile').find('.js-custFile-list');
    var single = $(this).closest('.js-custFile').find('.js-custFile-list-single');
    var response = $(this).closest('.js-custFile').find('.js-custFile-response');
    if (this.files[0] != undefined) {
      if (this.files[0].size > maxSize) {
        $(this).val();
        list.hide();
        response.show();
      } else {
        response.hide();
        single.text(this.files[0].name).attr({ 'title': this.files[0].name });
        list.show();
      }
    }
  });

  //Подстановка текста в поле
  $(document).on('click', '.js-substitution-button', function (e) {
    e.preventDefault();
    var inputId = $(this).attr('data-input');
    var value = $(this).attr('data-value');
    $('#' + inputId).val(value).trigger('chosen:updated');
    labelTransfer($('#' + inputId));
  });

  //Кнопка показать пароль
  $(document).on('click', '.js-showPassButton', function (event) {
    event.preventDefault();
    $(this).toggleClass('selected');
    var that = $(this);
    $(this).closest('.formGroup').find('.passwordField').each(function () {
      if (that.hasClass('selected')) {
        $(this).attr({ 'type': 'text' });
        $(this).focus();
      } else {
        $(this).attr({ 'type': 'password' });
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
      $(this).attr({ 'data-placeholder': $(this).attr('placeholder') });
    }
  });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 193 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

$(document).ready(function () {

  function fillAllRatingsAjax() {
    if ($('.js-rait').length != 0) {
      $('.js-rait').each(function () {
        var that = $(this);
        var rait = Number(that.attr('data-rait').replace(',', '.').replace(' ', ''));
        var wrWidth = that.closest('.js-raitWr').width();
        var fullNumber = Math.floor(rait);
        var fractionNumber = Number(String(rait).split('.')[1] || 0) / 10;
        var percentLast = Math.asin(2 * fractionNumber - 1) / Math.PI + 0.5;
        var percentStar = 20;
        var result = fullNumber * percentStar + percentLast * percentStar;
        var widthRait = Math.ceil(wrWidth * (result / 100));
        that.css({ 'width': widthRait });
      });
    }
  }

  var $marketplaceDetail = $('.js-contractor-detail-viewed');
  var $showContactsButton = $('.js-marketDetail-about-helper-contactsButton');
  var $snowContactsButtonMobile = $('.js-marketDetail-about-helper-contactsButton_mobile');

  $showContactsButton.add($snowContactsButtonMobile).on('click', function (e) {
    $.ajax({
      url: '/api/statistics.add/',
      data: {
        type: 'contacts',
        user: $marketplaceDetail.data('contractor-user'),
        session: $marketplaceDetail.data('user-session')
      },
      type: 'POST'
    });
  });

  if ($marketplaceDetail.length) {
    $.ajax({
      url: '/api/statistics.add/',
      data: {
        type: 'detail',
        user: $marketplaceDetail.data('contractor-user'),
        session: $marketplaceDetail.data('user-session')
      },
      type: 'POST'
    });
  }

  /* Фильтрация отзывов по оценкам */
  $('.js-filter-reviews').on('click', function (e) {
    e.preventDefault();

    var $reviewsList = $('.js-reviews-list');

    var $filterButton = $(e.target);
    var filterMark = $filterButton.data('filter-mark');
    var user = $reviewsList.data('user');

    $reviewsList.data('filter-mark', filterMark);

    var fields = 'mark=' + filterMark + '&user=' + user;

    $.ajax({
      url: '/ajax/marketplace-detail-reviews.php',
      data: fields,
      type: 'GET',
      success: function success(data) {
        $reviewsList.data('page', 1);
        $reviewsList.html(data);
        fillAllRatingsAjax();
      }
    });
  });

  /* Подгрузка отзывов на детальной странице исполнителя */
  $('.js-reviews-more a').on('click', function (e) {
    e.preventDefault();

    var $reviewsList = $('.js-reviews-list');

    var nextPage = $reviewsList.data('page') + 1;
    var user = $reviewsList.data('user');
    var markFilter = $reviewsList.data('filter-mark');

    var fields = 'page=' + nextPage + '&user=' + user;
    if (markFilter) {
      fields = fields + '&mark=' + markFilter;
    }

    $.ajax({
      url: '/ajax/marketplace-detail-reviews.php',
      data: fields,
      type: 'GET',
      success: function success(data) {
        $reviewsList.data('page', nextPage);
        $reviewsList.append(data);
        fillAllRatingsAjax();
      }
    });
  });

  // Открытие формы редактирования отзыва
  $(document).on('click', '.js-edit-review', function (e) {
    e.preventDefault();
    var $review = $($(e.target).closest('.marketDetail-reviews-list__single')[0]);
    var $text = $review.find('.marketDetail-reviews-list__text');
    $text.slideToggle(300);
  });

  // Установка выбранных оценок для форм редактирования отзывов
  var $reviewEditRadio = $('.js-review-edit-rate-radio');
  if ($reviewEditRadio.length) {
    $reviewEditRadio.each(function () {
      $(this).find('[value=' + $(this).data('checked') + ']').attr('checked', 'checked');
      console.log($(this).find('[name=review-mark]'));
      //$(this).find('[name=review-mark]').val([$(this).data('checked')]);
      //$(this).closest('.formGroup_raiting').removeClass('notValid');
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 194 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

$(document).ready(function () {

  function fillAllRatingsAjax() {
    if ($('.js-rait').length != 0) {
      $('.js-rait').each(function () {
        var that = $(this);
        var rait = Number(that.attr('data-rait').replace(',', '.').replace(' ', ''));
        var wrWidth = that.closest('.js-raitWr').width();
        var fullNumber = Math.floor(rait);
        var fractionNumber = Number(String(rait).split('.')[1] || 0) / 10;
        var percentLast = Math.asin(2 * fractionNumber - 1) / Math.PI + 0.5;
        var percentStar = 20;
        var result = fullNumber * percentStar + percentLast * percentStar;
        var widthRait = Math.ceil(wrWidth * (result / 100));
        that.css({ 'width': widthRait });
      });
    }
  }

  function applyFilter($formFilter, $formFilterDetails) {
    $('.js-popupSearchResult-input').blur();

    var $contractorsList = $('.js-marketplace-list');
    var action = $formFilter.attr('action');
    var fields = $formFilter.serializeArray();

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
        success: function success(data) {
          $formFilter.attr('data-filter-params', fields);
          $contractorsList.html(data);
          fillAllRatingsAjax();
          $contractorsList.data('page', 1);
          $('.js-contractors-filter-clean').addClass('show');
        }
      });
    } else {
      window.location = action + "?" + fields;
    }
  }

  var $formFilter = $('[data-contractors-filter]');
  var $formFilterDetails = $('[data-contractors-filter-details]');
  var $contractorsList = $('.js-marketplace-list');

  var $contractorsSearchService = $('input[name=contractors-search]');
  var $contractorsSearchLocation = $('input[name=contractors-location]');

  // Фильтр исполнителей
  if ($formFilter.length) {
    var urlArray = window.location.href.split("?");
    if (urlArray[1]) $formFilter.attr('data-filter-params', urlArray[1]);

    $formFilter.on('submit', function (e) {
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
    var action = $formFilter.attr('action');
    var fields = $formFilter.attr('data-filter-params');
    var sortButton = $(this).parent();
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
      success: function success(data) {
        $formFilter.attr('data-sort-params', fields);
        $contractorsList.html(data);
        fillAllRatingsAjax();
        $contractorsList.data('page', 1);
      }
    });
  });

  $('.js-sort-mobile').chosen({
    disable_search_threshold: 10
  }).change(function (event) {
    var action = $formFilter.attr('action');
    var fields = $formFilter.attr('data-filter-params');
    var sortBy = $(event.target).find(':selected').data('by');
    var sortOrder = $(event.target).find(':selected').data('order');
    fields = fields + (fields ? '&' : '') + 'sort_by=' + sortBy + '&sort_order=' + sortOrder;
    $.ajax({
      url: action,
      data: fields,
      type: 'GET',
      success: function success(data) {
        $formFilter.attr('data-sort-params', fields);
        $contractorsList.html(data);
        fillAllRatingsAjax();
        $contractorsList.data('page', 1);
      }
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

    var nextPage = $contractorsList.data('page') + 1;

    var action = $formFilter.attr('action');
    var fields = $formFilter.attr('data-filter-params');
    fields = fields + (fields ? '&' : '') + $formFilter.attr('data-sort-params');
    fields = fields + (fields ? '&' : '') + 'page=' + nextPage;

    $.ajax({
      url: action,
      data: fields,
      type: 'GET',
      success: function success(data) {
        $contractorsList.data('page', nextPage);
        $contractorsList.append(data);
        fillAllRatingsAjax();
      }
    });
  });

  // Всплывающие подсказки при заполнении поля "Услуга и специалист" в форме поиска
  if ($contractorsSearchService.length) {
    $contractorsSearchService.on('keyup', function (e) {
      e.preventDefault();
      var searchPhrase = $(e.target).val();
      var data = {
        searchPhrase: searchPhrase,
        limit: 5
      };
      $.ajax({
        url: '/api/contractors.service.suggest/',
        data: JSON.stringify(data),
        type: 'POST',
        contentType: 'application/json',
        success: function success(data) {
          var popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
          popup.html('');
          if (data.result.CATEGORIES) {
            for (var i = 0; i < data.result.CATEGORIES.length; i++) {
              if (data.result.CATEGORIES[i].ITEMS.length != 0) {
                popup.append('<div class="popupSearchResult__title popupSearchResult__block js-popupSearchResult__title">' + data.result.CATEGORIES[i].TITLE + '</div>');
              }
              for (var i2 = 0; i2 < data.result.CATEGORIES[i].ITEMS.length; i2++) {
                popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result.CATEGORIES[i].ITEMS[i2].ID + '">' + data.result.CATEGORIES[i].ITEMS[i2].NAME + '</div>');
              }
            }
          }
          popup.slideDown(200);
        }
      });
    });
  }

  // Всплывающие подсказки при заполнении поля местоположения в форме поиска
  if ($contractorsSearchLocation.length) {
    var handle = void 0;
    $contractorsSearchLocation.on('keyup', function (e) {
      e.preventDefault();
      clearTimeout(handle);
      handle = setTimeout(function () {
        if (e.target.value.length > 2) {
          var searchPhrase = $(e.target).val();
          var data = {
            searchPhrase: searchPhrase,
            onlyCities: true
          };
          if ($(e.target).data('restricted')) {
            data['restricted'] = $(e.target).data('restricted');
          }
          $.ajax({
            url: '/api/contractors.location.suggest/',
            data: JSON.stringify(data),
            type: 'POST',
            contentType: 'application/json',
            success: function success(data) {
              var popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
              popup.html('');
              if (data.result != undefined) {
                for (var i = 0; i < data.result.length; i++) {
                  popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result[i]['id'] + '">' + data.result[i]['value'] + '</div>');
                }
                popup.slideDown(200);
              }
            }
          });
        }
      }, 500);
    });
  }

  $(document).on('click', '.js-popupSearchResult__result', function () {
    var text = $(this).text();
    var value = $(this).attr('data-result');
    var inputText = $(this).closest('.formGroup').find('.js-popupSearchResult-input');
    var inputValue = $(this).closest('.formGroup').find('.js-popupSearchResult-inputHidden');
    inputText.val(text);
    inputValue.val(value);
  });

  $(document).on('blur', '.js-popupSearchResult-input', function (e) {
    var that = $(this);
    setTimeout(function () {
      that.closest('.formGroup').find('.js-popupSearchResult').slideUp(200);
    }, 200);
  });

  $(document).on('focus', '.js-popupSearchResult-input', function () {
    var popup = $(this).closest('.formGroup').find('.js-popupSearchResult');
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
        'search_city-id': $(e.target).data('id')
      },
      type: 'POST',
      success: function success(data) {
        if (data.result) {
          window.location.reload();
        }
      }
    });
  });

  // AJAX-пагинация при прокрутке
  if ($('.js-marketList-more').length != 0) {
    var focusButton = false;
    $(window).scroll(function () {

      if ($(window).scrollTop() + window.innerHeight * 1.6 > $('.js-marketList-more').offset().top && focusButton == false && $('#js-more-button').is(':visible')) {

        focusButton = true;
        var nextPage = $contractorsList.data('page') + 1;

        var action = $formFilter.attr('action');
        var fields = $formFilter.attr('data-filter-params');
        fields = fields + (fields ? '&' : '') + $formFilter.attr('data-sort-params');
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage;

        $.ajax({
          url: action,
          data: fields,
          type: 'GET',
          success: function success(data) {
            focusButton = false;
            $contractorsList.data('page', nextPage);
            $contractorsList.append(data);
            fillAllRatingsAjax();
          }
        });
      }
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 195 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

var _functions = __webpack_require__(68);

$(document).ready(function () {

  var $formAuth = $('form[data-form-auth]');
  var $formRegister = $('form[data-form-register]');
  var $formRegisterInfo = $('form[data-form-register-info]');
  var $formRestore = $('form[data-form-restore]');
  var $formRestoreConfirm = $('form[data-form-restore-confirm]');
  var $formAddReview = $('form[data-form-review]');
  var $formEmaster = $('form[data-form-emaster]');
  var $formArticle = $('form[data-form-article]');
  var $formChangePassword = $('form[data-form-change-password]');
  var $formChangeName = $('form[data-form-change-name]');
  var $formSearchCity = $('form[data-form-search-city]');

  if ($formRestoreConfirm.length) {
    $formRestoreConfirm.on('submit', function (e) {
      e.preventDefault();
      var fields = $formRestoreConfirm.serialize();
      var action = $formRestoreConfirm.attr('action');
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
          success: function success(data) {
            if (data.result) {
              var errorBlock = $('.js-form-error');
              errorBlock.html(data.result);
              setTimeout(function () {
                window.location.href = data.redirect;
              }, 5000);
            } else if (data.error) {
              var _errorBlock = $('.js-form-error');
              _errorBlock.html(data.error);
            }
            $formRestoreConfirm.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  if ($formRestore.length) {
    $formRestore.on('submit', function (e) {
      e.preventDefault();
      var fields = $formRestore.serialize();
      var action = $formRestore.attr('action');
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
          success: function success(data) {
            if (data.result) {
              $formRestore.html('');
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
            } else if (data.error) {
              var errorBlock = $('.js-errorInput');
              errorBlock.html(data.error);
            }
            $formRestore.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  if ($formAuth.length) {
    (0, _functions.setCookie)('social-user-type', "#client");
    $formAuth.on('submit', function (e) {
      e.preventDefault();
      var fields = $formAuth.serialize();
      var action = $formAuth.attr('action');
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
          success: function success(data) {
            if (data.result) {
              window.location.reload();
            } else if (data.error) {
              var errorBlock = $('.js-form-error');
              errorBlock.html(data.error);
            }
            $formAuth.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  if ($formRegister.length) {
    (0, _functions.setCookie)('social-user-type', "#contractor");
    $formRegister.on('submit', function (e) {
      e.preventDefault();
      var $currentFormRegister = $(e.target);
      var fields = $currentFormRegister.serialize();
      var action = $currentFormRegister.attr('action');
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
          success: function success(data) {
            if (data.result) {
              if (data.result['USER_TYPE'] === 'contractor') {
                var reloadURL = new URL(window.location.href);
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
              var errorBlock = $('.js-form-error-' + $currentFormRegister.attr('id'));
              errorBlock.html(data.error);
            }
            $currentFormRegister.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  if ($formRegisterInfo.length) {
    $formRegisterInfo.on('submit', function (e) {
      e.preventDefault();
      var $currentFormRegisterInfo = $(e.target);
      var formData = new FormData($currentFormRegisterInfo[0]);
      var action = $currentFormRegisterInfo.attr('action');
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
          success: function success(data) {
            data = JSON.parse(data);
            if (data.result) {
              $('.js-tabs').html('');
              $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
            } else if (data.error) {
              var errorBlock = $('.js-form-error-' + $currentFormRegisterInfo.attr('id'));
              errorBlock.html(data.error);
            }
            $currentFormRegisterInfo.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  // Отправка формы добавление отзыва
  if ($formAddReview.length) {
    $formAddReview.on('submit', function (e) {
      e.preventDefault();
      var fields = $formAddReview.serialize();
      var action = $formAddReview.attr('action');
      if (!action) {
        alert('Не указан обработчик запроса');
        return false;
      } else {
        $.ajax({
          url: action,
          data: fields,
          type: 'POST',
          success: function success(data) {
            if (data.result) {
              $('.js-form-comment').html(data.result["MESSAGE"]);
            } else if (data.error) {
              var errorBlock = $('.js-form-error');
              errorBlock.html(data.error);
            }
            $formAuth.find('[data-form-submit]').attr('disabled', false);
          }
        });
      }
    });
  }

  // Отправка формы редактирования отзыва
  $(document).on('submit', 'form[data-form-review-edit]', function (e) {
    e.preventDefault();
    var $formEditReview = $(e.target);
    var fields = $formEditReview.serialize();
    var action = $formEditReview.attr('action');
    if (!action) {
      alert('Не указан обработчик запроса');
      return false;
    } else {
      $.ajax({
        url: action,
        data: fields,
        type: 'POST',
        success: function success(data) {
          if (data.result) {
            console.log($formEditReview);
            $formEditReview.closest('.marketDetail-reviews-list__single').html(data.result["MESSAGE"]);
          } else if (data.error) {
            var errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
          $formAuth.find('[data-form-submit]').attr('disabled', false);
        }
      });
    }
  });

  // Удаление отзыва
  $(document).on('click', '.js-delete-review', function (e) {
    e.preventDefault();
    var data = {
      'id': $(e.target).data('id')
    };
    $.ajax({
      url: '/api/reviews.delete/',
      data: JSON.stringify(data),
      type: 'POST',
      success: function success(data) {
        if (data.result) {
          var $comment = $(e.target).closest('.marketDetail-reviews-list__single');
          $comment.slideUp(300);
        }
      }
    });
  });

  // Отправка формы добавления комментария
  $(document).on('submit', 'form[data-form-comment]', function (e) {
    e.preventDefault();
    var $formAddComment = $(e.target);
    var $submitButton = $(e.target).find('[type=submit]');
    var fields = $formAddComment.serialize();
    var action = $formAddComment.attr('action');
    if (!action) {
      alert('Не указан обработчик запроса');
      return false;
    } else {
      $submitButton.attr("disabled", true);
      $.ajax({
        url: action,
        data: fields,
        type: 'POST',
        success: function success(data) {
          if (data.result) {
            window.location.reload();
          } else if (data.error) {
            var errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
        }
      });
    }
  });

  // Отправка формы изменения комментария
  $(document).on('submit', 'form[data-form-comment-edit]', function (e) {
    e.preventDefault();
    var $formEditComment = $(e.target);
    var fields = $formEditComment.serialize();
    var action = $formEditComment.attr('action');
    if (!action) {
      alert('Не указан обработчик запроса');
      return false;
    } else {
      $.ajax({
        url: action,
        data: fields,
        type: 'POST',
        success: function success(data) {
          if (data.result) {
            var text = $formEditComment.find('textarea').val();
            var $comment = $formEditComment.closest('.js-marketDetail-comment-single');
            $comment.children('.js-marketDetail-reviews-list__text').html(text);
            $comment.children('.js-marketDetail-reviews-list__text').slideToggle(300);
            $comment.children('.js-marketDetail-reviews-list__edit-form').slideToggle(300);
            $comment.children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
          } else if (data.error) {
            var errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
        }
      });
    }
  });

  // Удаление комментария
  $(document).on('click', '.js-marketDetail-reviews-list__delete', function (e) {
    e.preventDefault();
    var data = {
      id: $(e.target).data('id')
    };
    $.ajax({
      url: '/api/comments.delete/',
      data: JSON.stringify(data),
      type: 'POST',
      success: function success(data) {
        if (data.result) {
          var $comment = $(e.target).closest('.js-marketDetail-comment-single');
          $comment.slideUp(300);
        }
      }
    });
  });

  if ($formEmaster.length) {
    $formEmaster.on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData($formEmaster[0]);
      var action = $formEmaster.attr('action');
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
          success: function success(data) {
            if (data.result) {
              $('#make_sertificate_electric .popup-content').html('<div class="fancybox-title">Спасибо за заявку!</div><p>Мы сообщим Вам, когда заявка будет рассмотрена нашими специалистами.</p>');
            } else if (data.error) {
              $('#make_sertificate_electric .popup-content').html('<div class="fancybox-title">Заявка уже существует</div><p>Ваша заявка уже находится на рассмотрении.</p>');
            }
          }
        });
      }
    });
  }

  if ($formArticle.length) {
    $formArticle.on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData($formArticle[0]);
      var action = $formArticle.attr('action');
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
          success: function success(data) {
            if (data.result) {
              $('#new_article').html('<div class="fancybox-title">Спасибо за предложенную статью!</div><p>Мы сообщим Вам, когда статья будет рассмотрена нашими специалистами и опубликована.</p>');
            } else if (data.error) {
              var errorBlock = $('.js-errorInput');
              errorBlock.html(data.error);
            }
          }
        });
      }
    });
  }

  if ($formChangePassword.length) {
    $formChangePassword.on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData($formChangePassword[0]);
      var action = $formChangePassword.attr('action');
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
          success: function success(data) {
            if (data.result) {
              $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
              $('.js-form-content').html('');
            } else if (data.error) {
              var errorBlock = $('.js-form-error');
              var errorMessage = void 0;
              if (data.error === 'wrong_old_password') {
                errorMessage = 'Неверный старый пароль';
              } else {
                errorMessage = data.error;
              }
              errorBlock.html(errorMessage);
            }
          }
        });
      }
    });
  }

  // Отправка формы смены ФИО
  if ($formChangeName.length) {
    $formChangeName.on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData($formChangeName[0]);
      var action = $formChangeName.attr('action');
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
          success: function success(data) {
            if (data.result) {
              $('.js-form-title').html(data.result["MESSAGE"]["TITLE"]);
              $('.js-form-text').html(data.result["MESSAGE"]["TEXT"]);
              $('.js-form-content').html('');
            } else if (data.error) {
              var errorBlock = $('.js-form-error');
              errorBlock.html(data.error);
            }
          }
        });
      }
    });
  }

  // Всплывающие подсказки при заполнении поля выбора местоположения пользователя
  var $inputSearchCity = $('#search_city-search');
  if ($inputSearchCity.length) {
    var handle = void 0;
    $inputSearchCity.on('keyup', function (e) {
      e.preventDefault();
      clearTimeout(handle);
      handle = setTimeout(function () {
        var searchPhrase = $(e.target).val();
        var data = {
          searchPhrase: searchPhrase,
          showOnlyCities: true
        };
        $.ajax({
          url: '/api/contractors.location.suggest/',
          data: JSON.stringify(data),
          type: 'POST',
          contentType: 'application/json',
          success: function success(data) {
            var popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult');
            popup.html('');
            for (var i = 0; i < data.result.length; i++) {
              popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result" data-result="' + data.result[i]['id'] + '">' + data.result[i]['value'] + '</div>');
            }
            popup.slideDown(200);
          }
        });
      }, 500);
    });
  }

  $(document).on('click', '.js-popupSearchResult__result', function () {
    $('#city-change-submit').removeAttr('disabled');
  });

  // Выбор города из предустановленного списка
  $(document).on('click', '.js-impotantTowns__single-link', function (e) {
    e.preventDefault();
    $('.js-impotantTowns__single-link').removeClass('active');
    $(e.target).addClass('active');
    $('#search_city-search').val($(e.target).data('path'));
    $('#search_city-id').val($(e.target).data('id'));
    $('#city-change-submit').removeAttr("disabled");
  });

  // Подтверждение выбора города
  if ($formSearchCity.length) {
    $formSearchCity.on('submit', function (e) {
      e.preventDefault();
      var fields = $formSearchCity.serialize();
      $.ajax({
        url: '/api/cities.change/',
        data: fields,
        type: 'POST',
        success: function success(data) {
          if (data.result) {
            $.fancybox.close();
            window.location = window.location.href.split("?")[0];
          } else if (data.error) {
            var errorBlock = $('.js-form-error');
            errorBlock.html(data.error);
          }
          $formSearchCity.find('#city-change-submit').attr('disabled', false);
        }
      });
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 196 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($, jQuery) {

$(document).ready(function () {

    var $serviceFilter = $('[data-service-filter]');
    var $serviceList = $('.js-service-list');

    /**
     * $.parseParams - parse query string paramaters into an object.
     */
    (function ($) {
        var re = /([^&=]+)=?([^&]*)/g;
        var decodeRE = /\+/g;
        var decode = function decode(str) {
            return decodeURIComponent(str.replace(decodeRE, " "));
        };
        $.parseParams = function (query) {
            var params = {},
                e;
            while (e = re.exec(query)) {
                var k = decode(e[1]),
                    v = decode(e[2]);
                if (k.substring(k.length - 2) === '[]') {
                    k = k.substring(0, k.length - 2);
                    (params[k] || (params[k] = [])).push(v);
                } else params[k] = v;
            }
            return params;
        };
    })(jQuery);

    $('.js-service-filter-type').on('click', function (e) {
        e.preventDefault();
        var $typeElement = $(e.delegateTarget);
        var typeID = !$typeElement.hasClass('active') ? $typeElement.data('type') : '';
        var fields = void 0;
        if ($serviceFilter.attr('data-filter-params')) {
            var filterParams = $.parseParams($serviceFilter.attr('data-filter-params'));
            filterParams.type = typeID;
            fields = $.param(filterParams);
        } else {
            fields = "&type=" + typeID;
        }
        $.ajax({
            url: '',
            data: fields,
            type: 'GET',
            success: function success(data) {
                if (!$typeElement.hasClass('active')) {
                    $('.js-service-filter-type').removeClass('active');
                    $typeElement.addClass('active');
                } else {
                    $('.js-service-filter-type').removeClass('active');
                }
                $serviceFilter.attr('data-filter-params', fields);

                var response = $(data);
                response = response.filter('div');
                $serviceList.html(response);
            }
        });
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6), __webpack_require__(6)))

/***/ }),
/* 197 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function($) {

var _twig = __webpack_require__(263);

var _twig2 = _interopRequireDefault(_twig);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(document).ready(function () {
  var files = __webpack_require__(189);
  files.keys().forEach(files);

  var sprOut = $('#sprite-output');
  if (sprOut.length) {
    var sprKeys = files.keys();

    var template = _twig2.default.twig({
      data: '<span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-{{icon.name}}" /></svg></span>'
    });

    var fullHtml = '';
    for (var i = sprKeys.length - 1; i >= 0; i--) {
      var icon = sprKeys[i].slice(2, -4);
      var iconNameTpl = '<p>' + icon + '</p>';
      //console.log(icon);
      var html = '<div class="sprite-output__item">' + iconNameTpl + template.render({
        icon: {
          'name': icon
        }
      }) + '</div>';
      fullHtml += html;
    }
    sprOut.html(fullHtml);
  };
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 198 */,
/* 199 */,
/* 200 */,
/* 201 */,
/* 202 */,
/* 203 */,
/* 204 */,
/* 205 */,
/* 206 */,
/* 207 */,
/* 208 */,
/* 209 */,
/* 210 */,
/* 211 */,
/* 212 */,
/* 213 */,
/* 214 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 215 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"articles":"articles","articles-list":"articles-list","articles-list__single":"articles-list__single","articles-list__single-text":"articles-list__single-text","articles-list__single-inner":"articles-list__single-inner","articles-list__single-mark":"articles-list__single-mark","svg-icon":"svg-icon","articles-list__single-category":"articles-list__single-category","articles-list__single-separator":"articles-list__single-separator","articles-list__single-title":"articles-list__single-title","articles-list_serv":"articles-list_serv"};

/***/ }),
/* 216 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"articlesPage":"articlesPage","articlesPage-inner":"articlesPage-inner","articlesPage__title":"articlesPage__title","articlesPage-main":"articlesPage-main","articlesPage-main--content":"articlesPage-main--content","articlesPage-helper":"articlesPage-helper","articlesPage-helper__title":"articlesPage-helper__title","articlesPage-helper__block":"articlesPage-helper__block","articlesPage-helper__showFilters":"articlesPage-helper__showFilters","articlesPage-helper__showFilters-button":"articlesPage-helper__showFilters-button","svg-icon":"svg-icon","active":"active","articlesPage-helper__showFilters-clean":"articlesPage-helper__showFilters-clean","show":"show","articlesPage-helper-filters-inner":"articlesPage-helper-filters-inner","articlesPage-helper-category":"articlesPage-helper-category","articlesPage-helper-category-list":"articlesPage-helper-category-list","articlesPage-helper-category-list__single":"articlesPage-helper-category-list__single","articlesPage-helper-category-list__single-button":"articlesPage-helper-category-list__single-button","articlesPage-helper-category_detail":"articlesPage-helper-category_detail","articlesPage-helper-themes":"articlesPage-helper-themes","articlesPage-helper-themes--clear":"articlesPage-helper-themes--clear","articlesPage-helper-themes-list":"articlesPage-helper-themes-list","articlesPage-helper-themes-list__single":"articlesPage-helper-themes-list__single","articlesPage-helper-themes-list__single-button":"articlesPage-helper-themes-list__single-button","articlesPage-helper-themes_detail":"articlesPage-helper-themes_detail","articlesPage-helper-data":"articlesPage-helper-data","articlesPage-helper-data__author":"articlesPage-helper-data__author","articlesPage-helper-data__date":"articlesPage-helper-data__date","articlesPage-helper-data__comment":"articlesPage-helper-data__comment","articlesPage-helper-data__comment-text":"articlesPage-helper-data__comment-text","articlesPage-helper-data__clear":"articlesPage-helper-data__clear","articlesPage-helper__button":"articlesPage-helper__button","articlesPage-helper-articles":"articlesPage-helper-articles","articlesPage-helper-articles-list":"articlesPage-helper-articles-list","articlesPage-helper-articles-list__single":"articlesPage-helper-articles-list__single","articlesPage-helper-articles-list__title":"articlesPage-helper-articles-list__title","articlesPage-helper-articles-list__description":"articlesPage-helper-articles-list__description","articlesPage-helper-articles-list__author":"articlesPage-helper-articles-list__author","articlesPage-helper-articles-list__date":"articlesPage-helper-articles-list__date","slick-track":"slick-track","slick-slide":"slick-slide","slick-dots":"slick-dots","slick-active":"slick-active","articlesPage-helper_detail":"articlesPage-helper_detail","articlesPage__separator":"articlesPage__separator","articlesPage-helper_mobile":"articlesPage-helper_mobile","articlesPage-count":"articlesPage-count","articlesPage-list":"articlesPage-list","articlesPage-list__single":"articlesPage-list__single","articlesPage-list__single-inner":"articlesPage-list__single-inner","articlesPage-list__banner":"articlesPage-list__banner","articlesPage-list__navigation":"articlesPage-list__navigation","articlesPage-list__type":"articlesPage-list__type","articlesPage-list__type--inactive":"articlesPage-list__type--inactive","articlesPage-list-themes":"articlesPage-list-themes","articlesPage-list-themes__single":"articlesPage-list-themes__single","articlesPage-list__title":"articlesPage-list__title","articlesPage-list__description":"articlesPage-list__description","articlesPage-list__img":"articlesPage-list__img","articlesPage-list__img-tag":"articlesPage-list__img-tag","articlesPage-list__info":"articlesPage-list__info","articlesPage-list__author":"articlesPage-list__author","articlesPage-list__date":"articlesPage-list__date","articlesPage__button":"articlesPage__button","articlesPage-comments":"articlesPage-comments","articlesPage-comments__form":"articlesPage-comments__form","articlesPage-comments-bottom":"articlesPage-comments-bottom","articlesPage_detail":"articlesPage_detail"};

/***/ }),
/* 217 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"breadcrumbs":"breadcrumbs","breadcrumbs-list":"breadcrumbs-list","breadcrumbs-list__single":"breadcrumbs-list__single","svg-icon":"svg-icon","breadcrumbs_padding":"breadcrumbs_padding","breadcrumbs_backoffice":"breadcrumbs_backoffice"};

/***/ }),
/* 218 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"footer":"footer","menuActive":"menuActive","footer-top":"footer-top","footer-bottom":"footer-bottom","footer-bottom-inner":"footer-bottom-inner","footer-bottom__single":"footer-bottom__single","footer-menu":"footer-menu","footer-menu__single":"footer-menu__single"};

/***/ }),
/* 219 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"forWho":"forWho","forWho-list":"forWho-list","forWho-list__single":"forWho-list__single","forWho-list__single-text":"forWho-list__single-text"};

/***/ }),
/* 220 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"formSolo":"formSolo","formSolo-inner":"formSolo-inner","formSolo-info":"formSolo-info","formSolo-info-logo":"formSolo-info-logo","formSolo-info-logo-img":"formSolo-info-logo-img","formSolo-info-slider":"formSolo-info-slider","formSolo-info-slider__single":"formSolo-info-slider__single","formSolo-info-slider__img":"formSolo-info-slider__img","formSolo-info-slider__text":"formSolo-info-slider__text","formSolo-info-slider__comm":"formSolo-info-slider__comm","formSolo-info-slider__comm-img":"formSolo-info-slider__comm-img","svg-icon":"svg-icon","formSolo-info-slider__author":"formSolo-info-slider__author","slick-list":"slick-list","slick-track":"slick-track","slick-dots":"slick-dots","slick-active":"slick-active","formSolo-workarea":"formSolo-workarea","formSolo-workarea-toggle":"formSolo-workarea-toggle","formSolo-workarea-toggle__back":"formSolo-workarea-toggle__back","formSolo-workarea-toggle__back-arrow":"formSolo-workarea-toggle__back-arrow","formSolo-workarea-toggle__text":"formSolo-workarea-toggle__text","formSolo-workarea-toggle_inside":"formSolo-workarea-toggle_inside","formSolo-workarea-toggle__button":"formSolo-workarea-toggle__button","formSolo-workarea__title":"formSolo-workarea__title","formSolo-workarea-main":"formSolo-workarea-main","formSolo-workarea-toggler":"formSolo-workarea-toggler","formSolo-workarea-toggler__single-inner":"formSolo-workarea-toggler__single-inner","formSolo-workarea-toggler__single":"formSolo-workarea-toggler__single","formSolo-workarea-content":"formSolo-workarea-content","formSolo-workarea-content-inner":"formSolo-workarea-content-inner","slick-slide":"slick-slide","formSolo-workarea-content-social":"formSolo-workarea-content-social","formSolo-workarea-content-social-list":"formSolo-workarea-content-social-list","formSolo-workarea-content-social-list__single":"formSolo-workarea-content-social-list__single","formSolo-workarea-response_message":"formSolo-workarea-response_message","formSolo-workarea-response_form":"formSolo-workarea-response_form"};

/***/ }),
/* 221 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"header":"header","header-helper":"header-helper","header-helper2":"header-helper2","header__top":"header__top","header__top_backoffice":"header__top_backoffice","header-topInner":"header-topInner","header-topInner__block":"header-topInner__block","header-topInner__block_title":"header-topInner__block_title","header-topInner__burger":"header-topInner__burger","header-topInner__burger-line":"header-topInner__burger-line","header-topInner__logo":"header-topInner__logo","header-topInner__logo-img":"header-topInner__logo-img","header-city-quest":"header-city-quest","active":"active","header-city-quest-inner":"header-city-quest-inner","header-city-quest__title":"header-city-quest__title","header-city-quest__buttons":"header-city-quest__buttons","header-city-quest__buttons-br":"header-city-quest__buttons-br","button":"button","formGroup__action":"formGroup__action","header-person":"header-person","header-person__block":"header-person__block","header-person__functions":"header-person__functions","header-person__functions-name":"header-person__functions-name","header-person__functions-name-inner":"header-person__functions-name-inner","svg-icon":"svg-icon","selected":"selected","header-person__alert":"header-person__alert","header-person__alert-bell":"header-person__alert-bell","new":"new","header-person-menu":"header-person-menu","header-person-menu--notifications":"header-person-menu--notifications","header-person-menu-inner":"header-person-menu-inner","header-person-menu__type":"header-person-menu__type","header-person-menu__type_new":"header-person-menu__type_new","header-person-menu__type-title":"header-person-menu__type-title","header-person-menu__single":"header-person-menu__single","header-person-menu__single_logout":"header-person-menu__single_logout","header-person-menu__single_notification":"header-person-menu__single_notification","header-person-menu_zak":"header-person-menu_zak","header__bottom":"header__bottom","header-bottomInner":"header-bottomInner","header-burger":"header-burger","header-burger__line":"header-burger__line","header-logo":"header-logo","header-logoInner":"header-logoInner","header-logoInner-tag":"header-logoInner-tag"};

/***/ }),
/* 222 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"mainMenu":"mainMenu","menuActive":"menuActive","mainMenu__vyal":"mainMenu__vyal","mainMenu__blackSide":"mainMenu__blackSide","mainMenu__inner":"mainMenu__inner","mainMenu-content":"mainMenu-content","mainMenu-close":"mainMenu-close","mainMenu-close__line":"mainMenu-close__line","mainMenu-nav":"mainMenu-nav","mainMenu-nav__item":"mainMenu-nav__item","mainMenu-nav__item-inner":"mainMenu-nav__item-inner","mainMenu-nav__separator":"mainMenu-nav__separator","mainMenu-button":"mainMenu-button","mainMenu-buttons":"mainMenu-buttons","mainMenu-buttons__single":"mainMenu-buttons__single","mainMenu-buttons__single--location":"mainMenu-buttons__single--location","header-person__functions-name":"header-person__functions-name","svg-icon":"svg-icon","header-person-menu":"header-person-menu"};

/***/ }),
/* 223 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"marketplace":"marketplace","marketplace__title":"marketplace__title","marketplace__button":"marketplace__button","marketSort":"marketSort","marketSort-inner":"marketSort-inner","search__filter-single":"search__filter-single","chosen-container":"chosen-container","marketSort__title":"marketSort__title","marketSort-list":"marketSort-list","marketSort-list__single":"marketSort-list__single","marketSort-list__single-inner":"marketSort-list__single-inner","marketSort-list__single-inner-arrow":"marketSort-list__single-inner-arrow","marketSort-list__single-inner-arrow-inner":"marketSort-list__single-inner-arrow-inner","selected":"selected","desc":"desc","marketSort_mobile":"marketSort_mobile","marketList":"marketList","marketList__count":"marketList__count","marketList__single":"marketList__single","marketList__single-inner":"marketList__single-inner","marketList__side":"marketList__side","marketList__side_one":"marketList__side_one","marketList__side_two":"marketList__side_two","marketList__img":"marketList__img","marketList__img-tag":"marketList__img-tag","marketList__numbers":"marketList__numbers","marketList__numbers-rait":"marketList__numbers-rait","svg-icon":"svg-icon","marketList__numbers-rait-inner":"marketList__numbers-rait-inner","marketList__numbers-raitNumber":"marketList__numbers-raitNumber","marketList__numbers-reviews":"marketList__numbers-reviews","button2":"button2","marketList__title":"marketList__title","marketList__geo":"marketList__geo","marketList__desc":"marketList__desc","marketList__info":"marketList__info","marketList__servPr":"marketList__servPr","marketList__service":"marketList__service","marketList__price":"marketList__price","marketList__review":"marketList__review","marketList__review-title":"marketList__review-title","marketList__review-text":"marketList__review-text","marketDetail":"marketDetail","marketDetail__title":"marketDetail__title","marketDetail__title-accent":"marketDetail__title-accent","marketDetail__more":"marketDetail__more","active":"active","marketDetail-about":"marketDetail-about","marketDetail-about__main":"marketDetail-about__main","marketDetail-about__main-block":"marketDetail-about__main-block","marketDetail-about__main-block_top":"marketDetail-about__main-block_top","marketDetail-about__main-block_master":"marketDetail-about__main-block_master","marketDetail-about__main-block_services":"marketDetail-about__main-block_services","marketDetail-about__main-block_mobile":"marketDetail-about__main-block_mobile","marketDetail-about__img":"marketDetail-about__img","marketDetail-about__img-tag":"marketDetail-about__img-tag","marketDetail-about__text":"marketDetail-about__text","marketDetail-about__properties":"marketDetail-about__properties","marketDetail-about__properties-single":"marketDetail-about__properties-single","marketDetail-about__properties-block":"marketDetail-about__properties-block","marketDetail-about__properties-block_title":"marketDetail-about__properties-block_title","marketDetail-about__properties-block_desc":"marketDetail-about__properties-block_desc","marketDetail-about__properties-block_language":"marketDetail-about__properties-block_language","marketDetail-about__properties-block-br":"marketDetail-about__properties-block-br","marketDetail-about__properties-block-link":"marketDetail-about__properties-block-link","marketDetail-about-serviceList":"marketDetail-about-serviceList","marketDetail-about-serviceList__single":"marketDetail-about-serviceList__single","marketDetail-about-serviceList__sublist":"marketDetail-about-serviceList__sublist","marketDetail-about-serviceList__title":"marketDetail-about-serviceList__title","marketDetail-about-serviceList__separator":"marketDetail-about-serviceList__separator","marketDetail-about-serviceList__sublist-single":"marketDetail-about-serviceList__sublist-single","marketDetail-about-serviceList__sublist-block":"marketDetail-about-serviceList__sublist-block","marketDetail-about-serviceList__sublist-block_text":"marketDetail-about-serviceList__sublist-block_text","marketDetail-about-serviceList__sublist-block_value":"marketDetail-about-serviceList__sublist-block_value","marketDetail-about-serviceList__sublist-text":"marketDetail-about-serviceList__sublist-text","marketDetail-about-serviceList__sublist-text-line":"marketDetail-about-serviceList__sublist-text-line","marketDetail-about-serviceList_backoffice":"marketDetail-about-serviceList_backoffice","marketDetail-about__title":"marketDetail-about__title","marketDetail-about__helper":"marketDetail-about__helper","marketDetail-about__helper-block":"marketDetail-about__helper-block","marketDetail-reviews__comment-lock":"marketDetail-reviews__comment-lock","marketDetail-about__helper-block_mobile":"marketDetail-about__helper-block_mobile","marketDetail-about__helper-block_contacts":"marketDetail-about__helper-block_contacts","marketDetail-about__helper-block_contacts-inner":"marketDetail-about__helper-block_contacts-inner","marketDetail-about__helper-block_docs":"marketDetail-about__helper-block_docs","marketDetail-about__contactsButton":"marketDetail-about__contactsButton","marketDetail-about__contacts-info":"marketDetail-about__contacts-info","marketDetail-about__contacts-tel":"marketDetail-about__contacts-tel","marketDetail-about__contacts-mail":"marketDetail-about__contacts-mail","marketDetail-about-numbersList":"marketDetail-about-numbersList","marketDetail-about-numbersList__single":"marketDetail-about-numbersList__single","marketDetail-about-numbersList__title":"marketDetail-about-numbersList__title","marketDetail-about-numbersList__value":"marketDetail-about-numbersList__value","marketDetail-about-docsList":"marketDetail-about-docsList","marketDetail-about-docsList__single":"marketDetail-about-docsList__single","marketDetail-about-docsList__img":"marketDetail-about-docsList__img","marketDetail-works":"marketDetail-works","marketDetail-works-list":"marketDetail-works-list","marketDetail-works-list__single":"marketDetail-works-list__single","marketDetail-works-list__single-inner":"marketDetail-works-list__single-inner","marketDetail-works-list__no-data":"marketDetail-works-list__no-data","marketDetail-works-list__img":"marketDetail-works-list__img","marketDetail-reviews":"marketDetail-reviews","marketDetail-reviews-inner":"marketDetail-reviews-inner","marketDetail-reviews__main":"marketDetail-reviews__main","marketDetail-reviews__helper":"marketDetail-reviews__helper","marketDetail-reviews__helper-top":"marketDetail-reviews__helper-top","marketDetail-reviews__helper-title":"marketDetail-reviews__helper-title","marketDetail-reviews__numbers":"marketDetail-reviews__numbers","marketDetail-reviews__numbers-current":"marketDetail-reviews__numbers-current","marketDetail-reviews__numbers-max":"marketDetail-reviews__numbers-max","marketDetail-reviews__filter":"marketDetail-reviews__filter","marketDetail-reviews__filter-single":"marketDetail-reviews__filter-single","marketDetail-reviews__filter-rait":"marketDetail-reviews__filter-rait","marketDetail-reviews__filter-amount":"marketDetail-reviews__filter-amount","marketDetail-reviews__comment-unlock":"marketDetail-reviews__comment-unlock","marketDetail-reviews__comment-form":"marketDetail-reviews__comment-form","formGroup":"formGroup","formGroup_raiting":"formGroup_raiting","disabled":"disabled","marketDetail-reviews__comment-textarea":"marketDetail-reviews__comment-textarea","notValid":"notValid","marketDetail-reviews-list":"marketDetail-reviews-list","marketDetail-reviews-list_comments":"marketDetail-reviews-list_comments","marketDetail-reviews-list__single":"marketDetail-reviews-list__single","marketDetail-reviews-list__top":"marketDetail-reviews-list__top","marketDetail-reviews-list__author":"marketDetail-reviews-list__author","marketDetail-reviews-list__date":"marketDetail-reviews-list__date","marketDetail-reviews-list__rait":"marketDetail-reviews-list__rait","marketDetail-reviews-list__text--edit":"marketDetail-reviews-list__text--edit","marketDetail-reviews-list__answer":"marketDetail-reviews-list__answer","marketDetail-reviews-list__answer-edit":"marketDetail-reviews-list__answer-edit","marketDetail-reviews-list__answer-top":"marketDetail-reviews-list__answer-top","marketDetail-reviews-list__answer-text":"marketDetail-reviews-list__answer-text","marketDetail-reviews-list__answer-bottom":"marketDetail-reviews-list__answer-bottom","marketDetail-reviews-list__answer-form":"marketDetail-reviews-list__answer-form","marketDetail-reviews-list__answer-cancel":"marketDetail-reviews-list__answer-cancel","marketDetail-reviews-list__answer-cancel-line":"marketDetail-reviews-list__answer-cancel-line","marketDetail-reviews-list__bottom":"marketDetail-reviews-list__bottom","marketDetail-reviews-list__bottom-button":"marketDetail-reviews-list__bottom-button","marketDetail-reviews-list__form":"marketDetail-reviews-list__form","marketDetail-reviews-list__likes":"marketDetail-reviews-list__likes","marketDetail-reviews-list__likes-single":"marketDetail-reviews-list__likes-single","marketDetail-reviews-list__edit-button":"marketDetail-reviews-list__edit-button","marketDetail-reviews-bottom":"marketDetail-reviews-bottom"};

/***/ }),
/* 224 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"notification":"notification","notification-inner":"notification-inner","notification__text":"notification__text","svg-icon":"svg-icon","notification__button":"notification__button","notification__close":"notification__close","notification__close-inner":"notification__close-inner","notification__close-inner-line":"notification__close-inner-line"};

/***/ }),
/* 225 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"search":"search","search_main":"search_main","search-inner":"search-inner","search__back-img":"search__back-img","search__back-gradient":"search__back-gradient","search__back":"search__back","search_md":"search_md","search_sm":"search_sm","search_xs":"search_xs","search__title":"search__title","search__title-accent":"search__title-accent","search__action":"search__action","search__form":"search__form","formGroup":"formGroup","chosen-container":"chosen-container","chosen-container-single":"chosen-container-single","chosen-search":"chosen-search","search__example":"search__example","search__example-title":"search__example-title","search__example-list":"search__example-list","search__example-single":"search__example-single","search__example_inline":"search__example_inline","search__filter":"search__filter","search__filter-list":"search__filter-list","search__filter-single":"search__filter-single","chosen-drop":"chosen-drop","chosen-results":"chosen-results","active-result":"active-result","no-results":"no-results","highlighted":"highlighted","chosen-single":"chosen-single","chosen-default":"chosen-default","active":"active","search__filter-clean":"search__filter-clean","show":"show","svg-icon":"svg-icon","search__label":"search__label","findIcon":"findIcon","formGroup_search":"formGroup_search","closeIcon":"closeIcon"};

/***/ }),
/* 226 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"stages-list":"stages-list","stages-list__single":"stages-list__single","stages-list__single-img":"stages-list__single-img","stages-list__single-img-tag":"stages-list__single-img-tag","stages-list__single-title":"stages-list__single-title"};

/***/ }),
/* 227 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"types":"types","types__list":"types__list","types__list-single":"types__list-single","types__list-title":"types__list-title","types__list-subTypes-single":"types__list-subTypes-single","types__bottom":"types__bottom"};

/***/ }),
/* 228 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin
module.exports = {"backoffice":"backoffice","backoffice-inner":"backoffice-inner","backoffice__mobileTitle":"backoffice__mobileTitle","backoffice__title":"backoffice__title","backoffice__title_typetwo":"backoffice__title_typetwo","personMenu":"personMenu","personMenu-inner":"personMenu-inner","personMenu__single":"personMenu__single","router-link-active":"router-link-active","personMenu__number":"personMenu__number","personMenu_edit":"personMenu_edit","backofficeMain":"backofficeMain","backofficeMain-inner":"backofficeMain-inner","selectWrapper-placeholder":"selectWrapper-placeholder","multiselect__single":"multiselect__single","multiselect__placeholder":"multiselect__placeholder","multiselect":"multiselect","multiselect--active":"multiselect--active","multiselect--disabled":"multiselect--disabled","multiselect__content-wrapper":"multiselect__content-wrapper","multiselect__content":"multiselect__content","multiselect__option--selected":"multiselect__option--selected","multiselect__option--highlight":"multiselect__option--highlight","articlesPage-list":"articlesPage-list","articlesPage-list__single":"articlesPage-list__single","articlesPage-list__single-inner":"articlesPage-list__single-inner","articlesPage-helper-data__comment":"articlesPage-helper-data__comment","articlesPage-helper-data__comment-new":"articlesPage-helper-data__comment-new","articlesPage-helper-data__comment-text":"articlesPage-helper-data__comment-text","marketDetail-reviews-list":"marketDetail-reviews-list","marketDetail-reviews-list__new":"marketDetail-reviews-list__new","marketDetail-reviews-list__single":"marketDetail-reviews-list__single","marketDetail-reviews-list__answer":"marketDetail-reviews-list__answer","marketDetail-reviews__comment-textarea":"marketDetail-reviews__comment-textarea","notValid":"notValid","backofficeMain-block":"backofficeMain-block","backofficeMain-block-inner":"backofficeMain-block-inner","backofficeMain-block__edit":"backofficeMain-block__edit","svg-icon":"svg-icon","backofficeMain-block__note":"backofficeMain-block__note","backofficeMain-block__top":"backofficeMain-block__top","button3":"button3","backofficeMain-block__top-sort":"backofficeMain-block__top-sort","backofficeMain-block__top-sort-text":"backofficeMain-block__top-sort-text","backofficeMain-hr":"backofficeMain-hr","backofficeMain_faq":"backofficeMain_faq","masterService-inner":"masterService-inner","masterEducation-main":"masterEducation-main","backofficeServices-cityList":"backofficeServices-cityList","masterEducation__single_speial":"masterEducation__single_speial","masterEducation__single-block":"masterEducation__single-block","masterEducation-helper":"masterEducation-helper","masterEducation__single":"masterEducation__single","masterEducation__single-title":"masterEducation__single-title","masterEducation__single-value":"masterEducation__single-value","button":"button","masterEducation__single-value-link":"masterEducation__single-value-link","masterEducation-sertificate":"masterEducation-sertificate","masterEducation-sertificate__single":"masterEducation-sertificate__single","masterEducation-sertificate__inner":"masterEducation-sertificate__inner","masterEducation-sertificate__img":"masterEducation-sertificate__img","masterNotification-inner":"masterNotification-inner","masterNotification__text":"masterNotification__text","masterWorks-inner":"masterWorks-inner","masterWorks-content":"masterWorks-content","masterWorks-content__single":"masterWorks-content__single","masterWorks-content__single-inner":"masterWorks-content__single-inner","masterWorks-content__single-img":"masterWorks-content__single-img","masterData-inner":"masterData-inner","masterData-content":"masterData-content","masterData-img":"masterData-img","masterData-img-tag":"masterData-img-tag","masterData-info":"masterData-info","masterData-info__single":"masterData-info__single","masterData-info__single-block":"masterData-info__single-block","masterData-info__single-title":"masterData-info__single-title","masterData-info__single-value":"masterData-info__single-value","masterInfo-content":"masterInfo-content","masterInfo-content__single":"masterInfo-content__single","masterInfo-content__single-block":"masterInfo-content__single-block","masterInfo-content__single-title":"masterInfo-content__single-title","masterInfo-content__single-value":"masterInfo-content__single-value","masterInfo-content__single-experience":"masterInfo-content__single-experience","masterInfo-content__single-experience-block":"masterInfo-content__single-experience-block","masterInfo-content__single-experience-date":"masterInfo-content__single-experience-date","masterInfo-content__single-experience-place":"masterInfo-content__single-experience-place","masterInfo-content__single-language":"masterInfo-content__single-language","backofficeNews":"backofficeNews","backofficeNews-inner":"backofficeNews-inner","backofficeNews-list__single":"backofficeNews-list__single","backofficeNews-list__single-date":"backofficeNews-list__single-date","backofficeNews-list__single-title":"backofficeNews-list__single-title","backofficeNews-list__single-title-link":"backofficeNews-list__single-title-link","personStats":"personStats","personStats-inner":"personStats-inner","personStats-types-toggler":"personStats-types-toggler","personStats-types-toggler__single-inner":"personStats-types-toggler__single-inner","personStats-types-list__single":"personStats-types-list__single","slick-slide":"slick-slide","personStats-types-list__block":"personStats-types-list__block","personStats-types-list__title":"personStats-types-list__title","personStats-types-list__number":"personStats-types-list__number","personStats-types-list__number_important":"personStats-types-list__number_important","personStats-types-list__number-note":"personStats-types-list__number-note","backofficeMenu":"backofficeMenu","menuActive":"menuActive","backofficeMenu-inner":"backofficeMenu-inner","backofficeMenu__vyal":"backofficeMenu__vyal","backofficeMenu__close":"backofficeMenu__close","backofficeMenu__close-line":"backofficeMenu__close-line","backofficeMenu-person":"backofficeMenu-person","backofficeMenu-person__single":"backofficeMenu-person__single","backofficeMenu-person__single-link":"backofficeMenu-person__single-link","backofficeMenu__separator":"backofficeMenu__separator","backofficeMenu-functions":"backofficeMenu-functions","backofficeMenu-functions__single":"backofficeMenu-functions__single","backofficeMenu-functions__single-link":"backofficeMenu-functions__single-link","backofficeMenu-functions__single_logout":"backofficeMenu-functions__single_logout","backofficeEditMenu":"backofficeEditMenu","backofficeEditMenu-inner":"backofficeEditMenu-inner","backofficeEditMenu__helper":"backofficeEditMenu__helper","backofficeEditMenu__single":"backofficeEditMenu__single","backofficeEditMenu__single-link":"backofficeEditMenu__single-link","backofficeEdit":"backofficeEdit","backofficeEdit-form":"backofficeEdit-form","backofficeEdit-block":"backofficeEdit-block","backofficeEdit-block-inner":"backofficeEdit-block-inner","backofficeEdit-hr":"backofficeEdit-hr","backofficeEdit_info":"backofficeEdit_info","backofficeFormGroup":"backofficeFormGroup","backofficeFormGroup__block":"backofficeFormGroup__block","backofficeFormGroup__block_flexCheckbox":"backofficeFormGroup__block_flexCheckbox","custCheckbox":"custCheckbox","backofficeFormGroup__label":"backofficeFormGroup__label","backofficeFormGroup__label_big":"backofficeFormGroup__label_big","backofficeFormGroup__label-inner":"backofficeFormGroup__label-inner","backofficeFormGroup__field":"backofficeFormGroup__field","backofficeFormGroup__field-inner":"backofficeFormGroup__field-inner","backofficeFormGroup__field-text":"backofficeFormGroup__field-text","backofficeFormGroup__error":"backofficeFormGroup__error","backofficeFormGroup__logo":"backofficeFormGroup__logo","backofficeFormGroup__logo--article-cover":"backofficeFormGroup__logo--article-cover","backofficeFormGroup__logo-img":"backofficeFormGroup__logo-img","backofficeFormGroup__logo-tag":"backofficeFormGroup__logo-tag","backofficeFormGroup__logo-file":"backofficeFormGroup__logo-file","backofficeFormGroup__logo-note":"backofficeFormGroup__logo-note","backofficeFormGroup_empty":"backofficeFormGroup_empty","backofficeCustFile-hiddenMd":"backofficeCustFile-hiddenMd","backofficeCustFile-visionMd":"backofficeCustFile-visionMd","backofficeCustFile_alone":"backofficeCustFile_alone","backofficeCustFile":"backofficeCustFile","backofficeCustFile-response":"backofficeCustFile-response","backofficeExperience__new":"backofficeExperience__new","backofficeExperience__new-inner":"backofficeExperience__new-inner","backofficeExperience-list":"backofficeExperience-list","backofficeExperience-list__single":"backofficeExperience-list__single","backofficeExperience-list__single_start":"backofficeExperience-list__single_start","backofficeExperience-list__single-dash":"backofficeExperience-list__single-dash","backofficeExperience-list__extralarge":"backofficeExperience-list__extralarge","backofficeExperience-list__large":"backofficeExperience-list__large","backofficeExperience-list__medium":"backofficeExperience-list__medium","backofficeExperience-list__medium_sert":"backofficeExperience-list__medium_sert","backofficeExperience-list__small":"backofficeExperience-list__small","backofficeExperience-list__currentPlace":"backofficeExperience-list__currentPlace","backofficeExperience-list__place":"backofficeExperience-list__place","backofficeExperience-list__delete":"backofficeExperience-list__delete","backofficeExperience-list__delete-line":"backofficeExperience-list__delete-line","backofficeExperience-list__delete_emulation":"backofficeExperience-list__delete_emulation","backofficeExperience-list__text":"backofficeExperience-list__text","backofficeExperience-list__img":"backofficeExperience-list__img","backofficeExperience-list__img-link":"backofficeExperience-list__img-link","backofficeExperience-list__img-tag":"backofficeExperience-list__img-tag","backofficeExperience-list__fields":"backofficeExperience-list__fields","backofficeExperience-list__label":"backofficeExperience-list__label","backofficeExperience-list__space":"backofficeExperience-list__space","backofficeExperience":"backofficeExperience","slideToggler":"slideToggler","selectWrapper":"selectWrapper","disabled":"disabled","multiselect-placeholder":"multiselect-placeholder","selectWrapper_loc":"selectWrapper_loc","multiselect_loc":"multiselect_loc","backofficeServices-list":"backofficeServices-list","backofficeServices-list__single":"backofficeServices-list__single","backofficeServices-list__block":"backofficeServices-list__block","backofficeServices-list__title":"backofficeServices-list__title","backofficeServices-list__comment":"backofficeServices-list__comment","backofficeServices-list__delete":"backofficeServices-list__delete","backofficeServices-list_add":"backofficeServices-list_add","backofficeServices-sublist__single":"backofficeServices-sublist__single","backofficeServices-sublist__title":"backofficeServices-sublist__title","backofficeServices-sublist__price":"backofficeServices-sublist__price","backofficeServices-sublist__input":"backofficeServices-sublist__input","backofficeServices-sublist__money":"backofficeServices-sublist__money","backofficeServices-sublist__text":"backofficeServices-sublist__text","backofficeServices-sublist":"backofficeServices-sublist","backofficeServices-sublist__deleteBlock":"backofficeServices-sublist__deleteBlock","backofficeServices-sublist__deleteBlock-text":"backofficeServices-sublist__deleteBlock-text","backofficeServices__delete":"backofficeServices__delete","backofficeServices__delete-line":"backofficeServices__delete-line","backofficeServices__delete_loc":"backofficeServices__delete_loc","backofficeServices-cityList__single":"backofficeServices-cityList__single","backofficeServices-cityList__single__delete-line":"backofficeServices-cityList__single__delete-line","backofficeServices-cityList__name":"backofficeServices-cityList__name","backofficeServices-cityList__delete":"backofficeServices-cityList__delete","backofficeServices-cityList__delete-line":"backofficeServices-cityList__delete-line","backofficeFaq":"backofficeFaq","backofficeFaq-list__single":"backofficeFaq-list__single","backofficeFaq-list__title-inner":"backofficeFaq-list__title-inner","active":"active","backofficeFaq-list__content":"backofficeFaq-list__content","tpQuestions":"tpQuestions","tpQuestions__single":"tpQuestions__single","tpQuestions__title":"tpQuestions__title","tpQuestions__title-inner":"tpQuestions__title-inner","tpQuestions__text":"tpQuestions__text","tpQuestions__answer":"tpQuestions__answer","tpQuestions__answer-author":"tpQuestions__answer-author","tpQuestions__answer-date":"tpQuestions__answer-date","tpQuestions__answer-text":"tpQuestions__answer-text","backofficeWorks":"backofficeWorks","backofficeWorks-list":"backofficeWorks-list","backofficeWorks-list__single":"backofficeWorks-list__single","backofficeWorks-list__img":"backofficeWorks-list__img","backofficeWorks-list__img-note":"backofficeWorks-list__img-note","backofficeWorks-list__img-file":"backofficeWorks-list__img-file","backofficeWorks-list__img-link":"backofficeWorks-list__img-link","backofficeWorks-list__img-tag":"backofficeWorks-list__img-tag","backofficeWorks-list__text":"backofficeWorks-list__text","backofficeWorks-list__text-group":"backofficeWorks-list__text-group","backofficeWorks-list__delete":"backofficeWorks-list__delete","typePlace-list":"typePlace-list","typePlace-list__single":"typePlace-list__single","typePlace-list__ok":"typePlace-list__ok","typePlace-list__not":"typePlace-list__not","typePlace-list__not-line":"typePlace-list__not-line","button-article-edit":"button-article-edit"};

/***/ }),
/* 229 */,
/* 230 */
/***/ (function(module, exports) {

(function(host) {

  var properties = {
    browser: [
      [/msie ([\.\_\d]+)/, "ie"],
      [/trident\/.*?rv:([\.\_\d]+)/, "ie"],
      [/firefox\/([\.\_\d]+)/, "firefox"],
      [/chrome\/([\.\_\d]+)/, "chrome"],
      [/version\/([\.\_\d]+).*?safari/, "safari"],
      [/mobile safari ([\.\_\d]+)/, "safari"],
      [/android.*?version\/([\.\_\d]+).*?safari/, "com.android.browser"],
      [/crios\/([\.\_\d]+).*?safari/, "chrome"],
      [/opera/, "opera"],
      [/opera\/([\.\_\d]+)/, "opera"],
      [/opera ([\.\_\d]+)/, "opera"],
      [/opera mini.*?version\/([\.\_\d]+)/, "opera.mini"],
      [/opios\/([a-z\.\_\d]+)/, "opera"],
      [/blackberry/, "blackberry"],
      [/blackberry.*?version\/([\.\_\d]+)/, "blackberry"],
      [/bb\d+.*?version\/([\.\_\d]+)/, "blackberry"],
      [/rim.*?version\/([\.\_\d]+)/, "blackberry"],
      [/iceweasel\/([\.\_\d]+)/, "iceweasel"],
      [/edge\/([\.\d]+)/, "edge"]
    ],
    os: [
      [/linux ()([a-z\.\_\d]+)/, "linux"],
      [/mac os x/, "macos"],
      [/mac os x.*?([\.\_\d]+)/, "macos"],
      [/os ([\.\_\d]+) like mac os/, "ios"],
      [/openbsd ()([a-z\.\_\d]+)/, "openbsd"],
      [/android/, "android"],
      [/android ([a-z\.\_\d]+);/, "android"],
      [/mozilla\/[a-z\.\_\d]+ \((?:mobile)|(?:tablet)/, "firefoxos"],
      [/windows\s*(?:nt)?\s*([\.\_\d]+)/, "windows"],
      [/windows phone.*?([\.\_\d]+)/, "windows.phone"],
      [/windows mobile/, "windows.mobile"],
      [/blackberry/, "blackberryos"],
      [/bb\d+/, "blackberryos"],
      [/rim.*?os\s*([\.\_\d]+)/, "blackberryos"]
    ],
    device: [
      [/ipad/, "ipad"],
      [/iphone/, "iphone"],
      [/lumia/, "lumia"],
      [/htc/, "htc"],
      [/nexus/, "nexus"],
      [/galaxy nexus/, "galaxy.nexus"],
      [/nokia/, "nokia"],
      [/ gt\-/, "galaxy"],
      [/ sm\-/, "galaxy"],
      [/xbox/, "xbox"],
      [/(?:bb\d+)|(?:blackberry)|(?: rim )/, "blackberry"]
    ]
  };

  var UNKNOWN = "Unknown";

  var propertyNames = Object.keys(properties);

  function Sniffr() {
    var self = this;

    propertyNames.forEach(function(propertyName) {
      self[propertyName] = {
        name: UNKNOWN,
        version: [],
        versionString: UNKNOWN
      };
    });
  }

  function determineProperty(self, propertyName, userAgent) {
    properties[propertyName].forEach(function(propertyMatcher) {
      var propertyRegex = propertyMatcher[0];
      var propertyValue = propertyMatcher[1];

      var match = userAgent.match(propertyRegex);

      if (match) {
        self[propertyName].name = propertyValue;

        if (match[2]) {
          self[propertyName].versionString = match[2];
          self[propertyName].version = [];
        } else if (match[1]) {
          self[propertyName].versionString = match[1].replace(/_/g, ".");
          self[propertyName].version = parseVersion(match[1]);
        } else {
          self[propertyName].versionString = UNKNOWN;
          self[propertyName].version = [];
        }
      }
    });
  }

  function parseVersion(versionString) {
    return versionString.split(/[\._]/).map(function(versionPart) {
      return parseInt(versionPart);
    });
  }

  Sniffr.prototype.sniff = function(userAgentString) {
    var self = this;
    var userAgent = (userAgentString || navigator.userAgent || "").toLowerCase();

    propertyNames.forEach(function(propertyName) {
      determineProperty(self, propertyName, userAgent);
    });
  };


  if (typeof module !== 'undefined' && module.exports) {
    module.exports = Sniffr;
  } else {
    host.Sniffr = new Sniffr();
    host.Sniffr.sniff(navigator.userAgent);
  }
})(this);


/***/ }),
/* 231 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-add\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke-linecap=\"square\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path d=\"M3.5 11.5h17.202M11.75 3.5v17.202\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-add");

/***/ }),
/* 232 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 16 16\" id=\"spr-arrow-down\" > <path fill=\"none\" fill-rule=\"nonzero\" stroke-width=\"2\" d=\"M13.314 5.657l-5.657 5.657L2 5.657\"/> </symbol>";
module.exports = sprite.add(image, "spr-arrow-down");

/***/ }),
/* 233 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-bell\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"\" stroke-width=\"2\"> <path d=\"M7.186 10.698l.967-3.88a5 5 0 1 1 9.703 2.418l-.967 3.881M18.59 18.695a3 3 0 0 1-2.185-3.637l.484-1.94\"/> <path d=\"M18.59 18.695L3.065 14.824a3 3 0 0 0 3.637-2.185l.484-1.94M8.161 19.186s.627 1.187 1.699 1.455c1.072.267 2.182-.487 2.182-.487\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-bell");

/***/ }),
/* 234 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 50 43\" id=\"spr-burger\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <g> <path d=\"M0,0v5h50V0H0z M0,24h50v-5H0V24z M0,43h50v-5H0V43z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-burger");

/***/ }),
/* 235 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-close\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"#7F7F7F\" stroke-linecap=\"square\" stroke-width=\"2\"> <path d=\"M4.438 3.938l16.47 16.47M20.562 3.938l-16.47 16.47\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-close");

/***/ }),
/* 236 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 36 33\" id=\"spr-comm\" > <g fill=\"#D70926\" fill-rule=\"nonzero\"> <path d=\"M3.328 0h10.987c.564 0 1.022.377 1.022.842 0 .105-.025.21-.071.308L8.643 15.013c-.207.433.051.921.577 1.092.119.038.246.058.374.058h1.524c.565 0 1.022.377 1.022.842a.72.72 0 0 1-.097.357l-8.63 15.154c-.24.42-.848.602-1.359.404-.411-.159-.646-.522-.576-.892l2.828-14.893c.087-.46-.295-.89-.852-.962a1.237 1.237 0 0 0-.158-.01H1.022c-.564 0-1.022-.377-1.022-.842 0-.036.003-.073.009-.11L2.314.733C2.381.313 2.814 0 3.328 0zM23.328 0h10.987c.564 0 1.022.377 1.022.842 0 .105-.025.21-.071.308l-6.623 13.863c-.207.433.051.921.577 1.092.119.038.246.058.374.058h1.524c.565 0 1.022.377 1.022.842a.72.72 0 0 1-.097.357l-8.63 15.154c-.24.42-.848.602-1.359.404-.411-.159-.646-.522-.576-.892l2.828-14.893c.087-.46-.295-.89-.852-.962a1.237 1.237 0 0 0-.158-.01h-2.274c-.564 0-1.022-.377-1.022-.842 0-.036.003-.073.009-.11L22.314.733c.067-.419.5-.732 1.014-.732z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-comm");

/***/ }),
/* 237 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-comment\" > <path fill=\"none\" fill-rule=\"evenodd\" stroke=\"#7F7F7F\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 17v3l-3.5-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2z\"/> </symbol>";
module.exports = sprite.add(image, "spr-comment");

/***/ }),
/* 238 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 29.263 29.263\" id=\"spr-cross\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <path d=\"M28.764,26.407L16.988,14.631L28.774,2.845c0.651-0.652,0.652-1.706,0.001-2.357\r\n\tc-0.65-0.65-1.705-0.65-2.356,0.001L14.632,12.275L2.856,0.499C2.206-0.152,1.151-0.151,0.5,0.5s-0.651,1.706-0.001,2.356\r\n\tl11.776,11.776L0.489,26.418c-0.651,0.651-0.651,1.706-0.001,2.356c0.651,0.651,1.705,0.65,2.356-0.001L14.63,16.988l11.776,11.776\r\n\tc0.65,0.65,1.705,0.65,2.355-0.001C29.415,28.112,29.415,27.057,28.764,26.407z\"/> </symbol>";
module.exports = sprite.add(image, "spr-cross");

/***/ }),
/* 239 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-dislike\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path d=\"M14.714 14.634c.145.861.29 3.072-.008 4.522-.18.881-.48 1.437-.798 1.79-.521.654-1.32.917-1.929.582-.09-.049-.171-.11-.244-.179-.247-.234-.392-.572-.426-.95a1.94 1.94 0 0 1-.008-.207c-.005-.387.03-.9.103-1.59-.26-1.786-1.314-3.501-1.712-4.097l-.505-.02a.392.392 0 0 1-.377-.39v-8.68c0-.607.525-.606.525-.606h9.074c.634 0 1.149.515 1.149 1.15v1.307c.634 0 .693.514.693 1.149v1.307c.634 0 .53.514.53 1.149V12.179c.635 0 .775.513.775 1.148v.159c0 .634-.514 1.148-1.148 1.148h-5.694zM5.725 14V4.92\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-dislike");

/***/ }),
/* 240 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-download\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke-linecap=\"square\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path d=\"M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-download");

/***/ }),
/* 241 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-edit\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"#7F7F7F\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path stroke-linecap=\"round\" d=\"M19.435 3l2.121 2.121L8.121 18.556 6 16.435z\"/> <path stroke-linecap=\"square\" d=\"M5.85 20.828l-2.524.379.402-2.5\"/> <path stroke-linecap=\"round\" d=\"M16.967 5.967l1.69 1.69\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-edit");

/***/ }),
/* 242 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 42 42\" id=\"spr-fb\" > <g fill=\"none\" fill-rule=\"evenodd\"> <path stroke=\"#c7c7c7\" d=\"M37.024 1H4.937A3.937 3.937 0 0 0 1 4.937v32.087a3.937 3.937 0 0 0 3.937 3.937h32.087a3.937 3.937 0 0 0 3.937-3.937V4.937A3.937 3.937 0 0 0 37.024 1\"/> <path fill=\"#c7c7c7\" d=\"M23.218 35.973V22.789h4.425l.663-5.138h-5.088v-3.28c0-1.488.413-2.502 2.546-2.502h2.721V7.272c-.47-.063-2.086-.203-3.964-.203-3.923 0-6.609 2.395-6.609 6.792v3.79h-4.436v5.137h4.436v13.184h5.306z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-fb");

/***/ }),
/* 243 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 16 16\" id=\"spr-geo\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <defs> <path id=\"spr-geo_a\" d=\"M7.962 6.25a1.74 1.74 0 0 0-1.73-1.75A1.74 1.74 0 0 0 4.5 6.25c0 .966.776 1.748 1.731 1.748A1.74 1.74 0 0 0 7.962 6.25zm1 0a2.74 2.74 0 0 1-2.73 2.748A2.74 2.74 0 0 1 3.5 6.25 2.74 2.74 0 0 1 6.231 3.5a2.74 2.74 0 0 1 2.731 2.75zM6.206 1C3.326 1 1 3.256 1 6.028c0 .549.082 1.12.239 1.71.361 1.361 1.104 2.789 2.115 4.233a24.064 24.064 0 0 0 2.31 2.808.776.776 0 0 0 1.085 0 21.526 21.526 0 0 0 .73-.794 24.064 24.064 0 0 0 1.58-2.014c1.01-1.444 1.754-2.872 2.115-4.233a6.67 6.67 0 0 0 .238-1.71C11.412 3.256 9.087 1 6.206 1zm0-1c3.422 0 6.206 2.693 6.206 6.028a7.66 7.66 0 0 1-.272 1.966c-.382 1.441-1.149 2.96-2.262 4.55a25.04 25.04 0 0 1-2.194 2.704c-.092.099-.172.182-.227.236a1.776 1.776 0 0 1-2.502 0 22.538 22.538 0 0 1-.774-.842 25.04 25.04 0 0 1-1.646-2.097C1.42 10.954.655 9.435.272 7.994A7.66 7.66 0 0 1 0 6.028C0 2.693 2.784 0 6.206 0z\"/> </defs> <g fill=\"none\" fill-rule=\"evenodd\" transform=\"translate(2)\"> <mask id=\"spr-geo_b\" fill=\"#fff\"> <use xlink:href=\"#spr-geo_a\"/> </mask> <use fill=\"#979797\" fill-rule=\"nonzero\" xlink:href=\"#spr-geo_a\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-geo");

/***/ }),
/* 244 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-like\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path d=\"M14.714 9.366c.145-.861.29-3.072-.008-4.522-.18-.881-.48-1.437-.798-1.79-.521-.654-1.32-.917-1.929-.582-.09.049-.171.11-.244.179-.247.234-.392.572-.426.95a1.94 1.94 0 0 0-.008.207c-.005.387.03.9.103 1.59-.26 1.786-1.314 3.501-1.712 4.097l-.505.02a.392.392 0 0 0-.377.39v8.68c0 .607.525.606.525.606h9.074c.634 0 1.149-.515 1.149-1.15v-.158-1.149c.634 0 .693-.514.693-1.149v-.158-1.149c.634 0 .53-.514.53-1.149v-.158-1.15c.635 0 .775-.513.775-1.148v-.159c0-.634-.514-1.148-1.148-1.148h-5.694zM5.725 10v9.08\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-like");

/***/ }),
/* 245 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 262 65\" id=\"spr-logo-2\" > <g fill=\"none\" fill-rule=\"evenodd\"> <path fill=\"#FFF\" d=\"M36.035 36.289l-4.99-2.88a.648.648 0 0 0-.804.13c-1.337 1.486-3.534 2.525-6.226 2.525-.602 0-1.184-.041-1.747-.12a7.115 7.115 0 0 1-2.118-.522 7.613 7.613 0 0 1-1.241-.602H9.565c2.479 4.869 7.532 8.096 14.333 8.096 5.33 0 9.555-2.055 12.324-5.662a.653.653 0 0 0-.187-.965M17.62 28.15l2.014 2.014-3.927.494a9.306 9.306 0 0 1-.586-3.268 9.22 9.22 0 0 1 1.157-4.504c.929-1.94 2.522-3.252 4.642-3.799.06-.017.12-.03.18-.048l.092-.024a7.118 7.118 0 0 1 1.71-.209h.033c.125-.004.249-.01.377-.01 2.67 0 5.336 1.215 6.666 4.133l-12.1 4.151a.645.645 0 0 0-.257 1.07m19.599-6.205c-2.01-5.843-7.204-9.943-13.908-9.943-9.192 0-15.456 6.732-15.456 15.457 0 1.52.192 2.976.555 4.35L.139 32.86c-.193.018-.181.301.012.303l25.802.02a.637.637 0 0 0 .504-1.04l-2.14-2.444 6.143-.307 7.025-.406a.653.653 0 0 0 .613-.615 16.908 16.908 0 0 0-.878-6.426\"/> <path fill=\"#1E1E1E\" d=\"M107.064 41.859L96.597 28.805v12.66a.63.63 0 0 1-.63.63h-6.295a.629.629 0 0 1-.629-.63V1.74c0-.347.282-.628.63-.628h6.294c.348 0 .63.28.63.628V25.7l9.881-12.638a.626.626 0 0 1 .495-.241h7.39c.528 0 .82.615.486 1.026l-10.933 13.436 11.28 13.785a.629.629 0 0 1-.487 1.027h-7.155a.625.625 0 0 1-.49-.236M190.866 41.859l-10.467-13.054v12.66a.628.628 0 0 1-.629.63h-6.295a.629.629 0 0 1-.629-.63V1.74c0-.346.282-.627.629-.627h6.295c.348 0 .629.28.629.627V25.7l9.881-12.637a.628.628 0 0 1 .496-.241h7.387a.63.63 0 0 1 .488 1.026l-10.933 13.435 11.279 13.785a.63.63 0 0 1-.487 1.027h-7.154a.63.63 0 0 1-.49-.236M126.76 20.082V32.26c0 2.995 2.054 3.204 5.959 3.019a.627.627 0 0 1 .657.627v5.621a.633.633 0 0 1-.57.627c-10.155.998-13.6-2.135-13.6-9.894V4.144c0-.277.183-.522.449-.601l6.294-2.404a.629.629 0 0 1 .81.601v11.082h4.17c.284 0 .533.19.607.463l1.625 6.002a.63.63 0 0 1-.607.795h-5.794zM155.352 12.885v7.1a.623.623 0 0 1-.656.625c-3.799-.205-8.243 1.683-8.243 7.492v13.365a.629.629 0 0 1-.629.628h-6.295a.628.628 0 0 1-.629-.628V13.45c0-.347.281-.628.63-.628h6.294c.347 0 .63.28.63.628v4.405c1.43-3.74 4.77-5.412 8.227-5.602a.634.634 0 0 1 .671.63M160.433 12.822h6.296c.347 0 .63.281.63.63v28.014a.629.629 0 0 1-.63.63h-6.296a.628.628 0 0 1-.629-.63V13.451c0-.348.281-.629.63-.629m-1.508-8.139c0-2.517 2.108-4.683 4.625-4.683 2.576 0 4.684 2.166 4.684 4.683 0 2.518-2.108 4.625-4.684 4.625-2.517 0-4.625-2.107-4.625-4.625M43.093 1.11h6.294c.349 0 .63.28.63.627v39.725a.628.628 0 0 1-.63.63h-6.294a.628.628 0 0 1-.629-.63V1.737c0-.347.282-.628.63-.628M231.089 12.885v7.1a.623.623 0 0 1-.656.625c-3.8-.205-8.242 1.683-8.242 7.492v13.365a.63.63 0 0 1-.63.628h-6.295a.629.629 0 0 1-.63-.628V13.45c0-.347.283-.628.63-.628h6.296a.63.63 0 0 1 .629.628v4.405c1.429-3.74 4.769-5.412 8.227-5.602a.634.634 0 0 1 .67.63M201.34 37.878a4.973 4.973 0 0 1 4.977-4.976 4.972 4.972 0 0 1 4.976 4.976 4.971 4.971 0 0 1-4.976 4.977 4.973 4.973 0 0 1-4.976-4.977M261.665 13.451v28.015a.628.628 0 0 1-.63.63c-.6 0-6.923-2.52-6.923-2.52-1.756 2.634-4.801 3.34-8.722 3.34-6.207 0-11.066-4.335-11.066-12.12V13.45c0-.348.28-.629.628-.629h6.295c.348 0 .629.281.629.63v16.405c0 3.982 2.4 6.031 5.797 6.031 3.687 0 6.439-2.166 6.439-7.259V13.451c0-.348.282-.629.629-.629h6.295c.348 0 .629.281.629.63M61.925 27.492c0-1.127.046-1.846.3-2.785h-.007c.01-.045.028-.082.039-.127.005-.018.007-.033.013-.05h.004c.923-3.634 3.64-5.737 7.613-5.737 2.652 0 5.297 1.207 6.635 4.084l-14.585 5.037c-.004-.14-.012-.274-.012-.422m20.348 8.601l-4.668-2.69a.619.619 0 0 0-.774.118c-1.336 1.496-3.54 2.543-6.241 2.543-2.754 0-5.124-.807-6.685-2.57l13.816-4.772 6.312-2.18.01-.004a.766.766 0 0 0 .553-.698c-.174-1.885-.634-3.665-1.367-5.28-2.313-5.09-7.185-8.558-13.342-8.558-9.191 0-15.457 6.732-15.457 15.456 0 8.723 6.206 15.457 16.042 15.457 5.349 0 9.585-2.068 12.353-5.699.227-.299.129-.728-.552-1.123M16.32 58.2c-.309-1.77-1.606-2.53-2.945-2.53-1.668 0-2.8.988-3.11 2.53h6.055zm-2.739 4.243c1.236 0 2.162-.554 2.657-1.275l1.833 1.07c-.948 1.4-2.492 2.244-4.51 2.244-3.399 0-5.602-2.326-5.602-5.416 0-3.047 2.203-5.416 5.437-5.416 3.07 0 5.17 2.47 5.17 5.438 0 .308-.042.638-.083.925h-8.197c.35 1.608 1.647 2.43 3.295 2.43zM19.907 59.066c0-3.068 2.328-5.416 5.438-5.416 2.018 0 3.768 1.05 4.593 2.656l-1.916 1.114c-.453-.97-1.462-1.586-2.697-1.586-1.813 0-3.194 1.378-3.194 3.232 0 1.854 1.38 3.234 3.194 3.234 1.235 0 2.244-.638 2.739-1.586l1.915 1.092c-.866 1.627-2.616 2.676-4.634 2.676-3.11 0-5.438-2.367-5.438-5.416M39.864 56.06h-3.357v8.154h-2.224V56.06h-3.377v-2.143h8.958zM47.322 60.818c0-.865-.68-1.4-1.545-1.4H43.49v2.8h2.287c.865 0 1.545-.556 1.545-1.4m2.183 0c0 2.018-1.565 3.396-3.748 3.396h-4.49V53.918h2.223v3.502h2.267c2.183 0 3.748 1.359 3.748 3.398M62.916 64.214l-4.222-4.758v4.758H56.47V53.917h2.224v4.551l3.996-4.55h2.72l-4.532 5.046 4.695 5.25zM74.434 59.066c0-1.873-1.4-3.254-3.213-3.254-1.81 0-3.212 1.38-3.212 3.254 0 1.875 1.401 3.254 3.212 3.254 1.813 0 3.213-1.38 3.213-3.254m-8.649 0c0-3.068 2.43-5.416 5.436-5.416 3.028 0 5.438 2.348 5.438 5.416 0 3.049-2.41 5.416-5.438 5.416-3.006 0-5.436-2.367-5.436-5.416M87.745 53.917v10.297H85.52v-4.097h-4.716v4.098H78.58V53.916h2.225v4.099h4.716v-4.099zM98.097 56.06h-3.355v8.154h-2.226V56.06H89.14v-2.143h8.959zM107.635 59.066c0-1.914-1.4-3.295-3.254-3.295-1.853 0-3.254 1.38-3.254 3.295 0 1.916 1.401 3.295 3.255 3.295 1.853 0 3.253-1.38 3.253-3.295zm2.224-5.148v10.296h-2.224v-1.482c-.782 1.092-1.998 1.75-3.604 1.75-2.8 0-5.127-2.367-5.127-5.416 0-3.068 2.327-5.416 5.127-5.416 1.606 0 2.822.658 3.604 1.73v-1.462h2.224zM118.86 64.214l-4.222-4.758v4.758h-2.224V53.917h2.224v4.551l3.995-4.55h2.72l-4.532 5.046 4.695 5.25zM131.255 56.06H127.9v8.154h-2.225V56.06h-3.377v-2.143h8.958z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-logo-2");

/***/ }),
/* 246 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 336 42\" id=\"spr-logo\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <defs> <path id=\"spr-logo_a\" d=\"M.714.986h29.822V42H.714z\"/> <path id=\"spr-logo_c\" d=\"M.584.165H30.75v30.914H.584z\"/> </defs> <g fill=\"none\" fill-rule=\"evenodd\"> <path fill=\"#D70926\" d=\"M36.035 24.287l-4.99-2.88a.649.649 0 0 0-.804.13c-1.337 1.486-3.534 2.525-6.226 2.525-.602 0-1.185-.041-1.747-.12a7.114 7.114 0 0 1-2.118-.521 7.536 7.536 0 0 1-1.241-.603H9.565c2.479 4.869 7.532 8.096 14.333 8.096 5.33 0 9.555-2.055 12.324-5.661a.654.654 0 0 0-.187-.966M37.219 9.943C35.209 4.101 30.016.001 23.313.001 14.12.001 7.856 6.733 7.856 15.458c0 1.518.192 2.975.555 4.348L.139 20.86c-.192.02-.181.302.012.304l25.802.018a.637.637 0 0 0 .504-1.04l-2.138-2.443 6.14-.308 7.027-.406a.651.651 0 0 0 .612-.614 16.91 16.91 0 0 0-.879-6.427M17.62 16.148l2.012 2.015-3.926.494a9.311 9.311 0 0 1-.586-3.269c0-1.649.424-3.192 1.158-4.504.928-1.94 2.52-3.252 4.64-3.797.062-.019.12-.033.18-.05.032-.008.063-.015.094-.024a7.162 7.162 0 0 1 1.71-.209h.033c.125-.004.248-.01.377-.01 2.67 0 5.336 1.215 6.666 4.133l-12.1 4.152a.644.644 0 0 0-.258 1.07\"/> <path fill=\"#FFF\" d=\"M67.363 30.093h-6.192a.68.68 0 0 1-.682-.68V8.08h-8.08v9.78c0 9.003-4.215 12.905-11.729 12.296a.689.689 0 0 1-.625-.684V23.82c0-.404.354-.718.753-.67 2.624.306 4.05-1.52 4.05-5.702V1.499a.68.68 0 0 1 .68-.678h21.825a.68.68 0 0 1 .681.678v27.914a.68.68 0 0 1-.68.68M161.167 8.08h-8.454v21.333a.68.68 0 0 1-.681.681h-6.191a.68.68 0 0 1-.681-.68V8.08h-8.571a.681.681 0 0 1-.68-.682V1.5a.68.68 0 0 1 .68-.68h24.578a.68.68 0 0 1 .68.68v5.898a.681.681 0 0 1-.68.682M227.051 1.5v27.914a.68.68 0 0 1-.68.68h-6.017a.68.68 0 0 1-.68-.68V13.467l-13.556 16.381a.68.68 0 0 1-.522.246h-4.62a.68.68 0 0 1-.684-.68V1.5c0-.375.306-.68.683-.68h6.014c.377 0 .682.305.682.68v15.947l13.556-16.38a.675.675 0 0 1 .522-.247h4.621c.375 0 .681.305.681.68M250.359 29.86l-10.467-13.056v12.66a.63.63 0 0 1-.63.63h-6.294a.63.63 0 0 1-.63-.63V1.46c0-.349.284-.629.63-.629h6.295c.346 0 .629.28.629.63v12.241l9.88-12.639a.632.632 0 0 1 .497-.243h7.388c.528 0 .82.616.487 1.025l-10.932 13.437 11.279 13.784a.629.629 0 0 1-.486 1.027h-7.156a.63.63 0 0 1-.49-.234M125.427 29.86L114.96 16.803v12.66a.628.628 0 0 1-.63.63h-6.294a.63.63 0 0 1-.63-.63V1.46c0-.349.284-.629.63-.629h6.295c.348 0 .629.28.629.63v12.241l9.882-12.639a.63.63 0 0 1 .495-.243h7.388c.53 0 .82.616.487 1.025l-10.933 13.437 11.279 13.784a.628.628 0 0 1-.486 1.027h-7.154a.633.633 0 0 1-.491-.234M291.095 0c-4.13 0-7.13 1.24-9.51 3.73a.635.635 0 0 0-.422-.45L274.74.82c-.254-.064-.662.155-.682.658v39.647c0 .375.305.68.682.68h6.19a.682.682 0 0 0 .681-.68V26.641c2.108 2.692 5.211 4.273 9.485 4.273 7.847 0 14.285-6.732 14.285-15.457C305.38 6.732 298.942 0 291.095 0m-1.405 23.713c-4.623 0-8.076-3.334-8.08-8.248v-.016c.004-4.914 3.457-8.248 8.08-8.248 4.684 0 8.137 3.338 8.137 8.256s-3.453 8.256-8.137 8.256\"/> <g transform=\"translate(305 -.165)\"> <mask id=\"spr-logo_b\" fill=\"#fff\"> <use xlink:href=\"#spr-logo_a\"/> </mask> <path fill=\"#FFF\" d=\"M29.855.986h-6.616a.68.68 0 0 0-.646.467l-6.344 19.205L8.575 1.414A.685.685 0 0 0 7.94.986H1.394a.68.68 0 0 0-.63.937L12.21 29.908c-1.325 3.474-3.27 4.924-6.82 5.03a.688.688 0 0 0-.675.68v5.692c0 .367.295.678.662.686 6.705.146 11.846-3.571 14.795-11.737L30.495 1.898a.68.68 0 0 0-.64-.912\" mask=\"url(#spr-logo_b)\"/> </g> <g transform=\"translate(72 -.165)\"> <mask id=\"spr-logo_d\" fill=\"#fff\"> <use xlink:href=\"#spr-logo_c\"/> </mask> <path fill=\"#FFF\" d=\"M28.428 24.257l-4.668-2.69a.614.614 0 0 0-.773.119c-1.336 1.496-3.541 2.543-6.243 2.543-2.754 0-5.123-.808-6.683-2.572l13.815-4.77 6.312-2.181.01-.004a.764.764 0 0 0 .552-.7c-.174-1.884-.633-3.663-1.367-5.278C27.072 3.634 22.2.164 16.04.164 6.85.165.584 6.9.584 15.623c0 8.724 6.207 15.457 16.043 15.457 5.35 0 9.584-2.067 12.354-5.7.226-.298.129-.726-.553-1.122M8.08 15.655c0-1.127.045-1.844.3-2.785h-.007c.01-.045.028-.082.039-.127.004-.016.006-.033.012-.05h.004c.924-3.633 3.64-5.737 7.613-5.737 2.654 0 5.298 1.207 6.635 4.084L8.092 16.077c-.004-.14-.012-.272-.012-.422\" mask=\"url(#spr-logo_d)\"/> </g> <path fill=\"#FFF\" d=\"M182.011 0c-4.13 0-7.13 1.24-9.51 3.73a.633.633 0 0 0-.422-.45L165.655.82c-.252-.064-.66.155-.683.658v39.647c0 .375.306.68.683.68h6.19a.682.682 0 0 0 .681-.68V26.641c2.108 2.692 5.211 4.273 9.485 4.273 7.847 0 14.285-6.732 14.285-15.457C196.296 6.732 189.858 0 182.011 0m-1.405 23.713c-4.623 0-8.076-3.334-8.08-8.248v-.016c.004-4.914 3.457-8.248 8.08-8.248 4.684 0 8.137 3.338 8.137 8.256s-3.453 8.256-8.137 8.256M260.833 25.821a4.975 4.975 0 1 1 9.951 0 4.97 4.97 0 0 1-4.975 4.977 4.973 4.973 0 0 1-4.976-4.977\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-logo");

/***/ }),
/* 247 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 71 28\" id=\"spr-notification\" > <path fill=\"#D70926\" fill-rule=\"nonzero\" d=\"M0 6.172V26.05a1.2 1.2 0 0 0 1.644 1.115L31.206 15.38a1.2 1.2 0 0 1 1.645 1.115v4.188a1.2 1.2 0 0 0 1.715 1.084L69.427 5.199a1.2 1.2 0 0 0-.703-2.27L34.24 8.394a1.2 1.2 0 0 1-1.388-1.185V2.125a1.2 1.2 0 0 0-1.358-1.19L1.042 4.983A1.2 1.2 0 0 0 0 6.172z\"/> </symbol>";
module.exports = sprite.add(image, "spr-notification");

/***/ }),
/* 248 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 42 42\" id=\"spr-ok\" > <g fill=\"none\" fill-rule=\"evenodd\"> <path fill-rule=\"nonzero\" stroke=\"#c7c7c7\" d=\"M37.06 1H4.94A3.941 3.941 0 0 0 1 4.942v32.116A3.941 3.941 0 0 0 4.94 41h32.12A3.941 3.941 0 0 0 41 37.058V4.942A3.941 3.941 0 0 0 37.06 1\"/> <path fill=\"#c7c7c7\" d=\"M21.366 12.367a2.766 2.766 0 0 1 2.77 2.758 2.766 2.766 0 0 1-2.77 2.756 2.767 2.767 0 0 1-2.768-2.756 2.767 2.767 0 0 1 2.768-2.758m0 9.417c3.69 0 6.688-2.987 6.688-6.66s-2.999-6.661-6.688-6.661c-3.687 0-6.687 2.988-6.687 6.662 0 3.672 3 6.659 6.687 6.659M24.072 27.217a12.552 12.552 0 0 0 3.883-1.601 1.947 1.947 0 0 0 .615-2.692 1.963 1.963 0 0 0-2.702-.613 8.52 8.52 0 0 1-9.004 0 1.961 1.961 0 0 0-2.7.613 1.947 1.947 0 0 0 .613 2.692 12.57 12.57 0 0 0 3.883 1.601l-3.738 3.724a1.947 1.947 0 0 0 0 2.76 1.961 1.961 0 0 0 2.771 0l3.673-3.66 3.675 3.66a1.964 1.964 0 0 0 2.77 0 1.945 1.945 0 0 0 0-2.76l-3.739-3.724z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-ok");

/***/ }),
/* 249 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 100 100\" id=\"spr-preloader\" class=\"lds-spinner\" preserveAspectRatio=\"xMidYMid\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><g transform=\"rotate(0 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.9s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(36 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.8s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(72 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.7s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(108 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.6s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(144 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.5s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(180 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.4s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(216 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.3s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(252 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.2s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(288 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"-0.1s\" repeatCount=\"indefinite\"/> </rect> </g><g transform=\"rotate(324 50 50)\"> <rect x=\"47\" y=\"41\" rx=\"9.4\" ry=\"8.2\" width=\"6\" height=\"6\"> <animate attributeName=\"opacity\" values=\"1;0\" keyTimes=\"0;1\" dur=\"1s\" begin=\"0s\" repeatCount=\"indefinite\"/> </rect> </g></symbol>";
module.exports = sprite.add(image, "spr-preloader");

/***/ }),
/* 250 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 10 10\" id=\"spr-red-cross\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <defs> <path id=\"spr-red-cross_a\" d=\"M8.068 6.21h4.003v2H8.068v3.861h-2V8.21H2.071v-2h3.997V2.071h2V6.21z\"/> </defs> <g transform=\"translate(-2 -2)\"> <mask id=\"spr-red-cross_b\" fill=\"#fff\"> <use xlink:href=\"#spr-red-cross_a\"/> </mask> <use transform=\"rotate(45 7.071 7.071)\" xlink:href=\"#spr-red-cross_a\" stroke=\"none\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-red-cross");

/***/ }),
/* 251 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 24 24\" id=\"spr-search\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"#7F7F7F\" stroke-width=\"2\"> <circle cx=\"9.5\" cy=\"9.5\" r=\"6.5\" stroke-linecap=\"round\"/> <path stroke-linecap=\"square\" d=\"M14 14.5l5.907 5.907\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-search");

/***/ }),
/* 252 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 259 241\" id=\"spr-stages-design\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <defs> <path id=\"spr-stages-design_a\" d=\"M0 3.535v151.197a3 3 0 0 0 2.628 2.977l71 8.87A3 3 0 0 0 77 163.602V15.244a3 3 0 0 0-2.512-2.96L3.488.575A3 3 0 0 0 0 3.535z\"/> <path id=\"spr-stages-design_c\" d=\"M49 11.796v2.524c-3.166 0-6.55-.544-10.277-1.58-3.565-.991-7.062-2.29-12.136-4.402C14.243 3.201 12.354 2.524 8.772 2.524c-4.375 0-6.439 2.892-6.439 10.534 0 26.61 20.84 56.881 35.143 50.577l.88 2.338C22.028 73.17 0 41.17 0 13.058 0 4.144 2.957 0 8.772 0c4.022 0 5.86.659 18.653 5.982 4.997 2.08 8.421 3.352 11.88 4.313 3.548.987 6.745 1.501 9.695 1.501z\"/> </defs> <g fill=\"none\" fill-rule=\"evenodd\"> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M147.413 95.173l95.443-31.505a5 5 0 0 1 6.567 4.748v110.197a5 5 0 0 1-2.5 4.33l-98.755 57.017a5 5 0 0 1-5.244-.15L2.256 147.48A5 5 0 0 1 0 143.301V33.944a5 5 0 0 1 7.163-4.508l136.52 65.497a5 5 0 0 0 3.73.24z\"/> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M182 42.377v151.196a3 3 0 0 0 2.628 2.977l71 8.87a3 3 0 0 0 3.372-2.977V54.085a3 3 0 0 0-2.512-2.96l-71-11.708a3 3 0 0 0-3.488 2.96z\"/> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M180.5 56.676v141.512a5 5 0 0 1-7.518 4.32l-119.5-69.67a5 5 0 0 1-2.482-4.32V5A5 5 0 0 1 57.985.41l119.5 51.676a5 5 0 0 1 3.015 4.59z\" opacity=\".6\"/> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M25 40.377v151.196a3 3 0 0 0 2.628 2.977l71 8.87a3 3 0 0 0 3.372-2.977V52.085a3 3 0 0 0-2.512-2.96l-71-11.708A3 3 0 0 0 25 40.377z\"/> <path fill=\"#FFF\" fill-rule=\"nonzero\" d=\"M26 36.377v151.196a3 3 0 0 0 2.628 2.977l71 8.87a3 3 0 0 0 3.372-2.977V48.085a3 3 0 0 0-2.512-2.96l-71-11.708A3 3 0 0 0 26 36.377z\"/> <g transform=\"translate(180 32.842)\"> <mask id=\"spr-stages-design_b\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-design_a\"/> </mask> <use fill=\"#FFF\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-design_a\"/> <path fill=\"#E5E8EE\" d=\"M36.442 183.688a6.978 6.978 0 0 1-2.05-4.95v-128l10-25 10 25v128a7 7 0 0 1-7 7h-6c-1.289 0-2.939-.684-4.95-2.05z\" mask=\"url(#spr-stages-design_b)\" transform=\"rotate(-171 44.392 105.738)\"/> </g> <path fill=\"#E5E8EE\" fill-rule=\"nonzero\" d=\"M180 36.377v151.196a3 3 0 0 1-2.628 2.977l-71 8.87a3 3 0 0 1-3.372-2.977V48.085a3 3 0 0 1 2.512-2.96l71-11.708a3 3 0 0 1 3.488 2.96z\"/> <path stroke=\"#B2BAC6\" stroke-width=\"2\" d=\"M47.57 62.14L103 68.5l77-11 60.34 10.29a5 5 0 0 1 4.16 4.93v100.509a5 5 0 0 1-5.712 4.949L178.5 169.5l-75.5 12-56.73-8.37a5 5 0 0 1-4.27-4.946V67.107a5 5 0 0 1 5.57-4.968z\" opacity=\".6\"/> <path stroke=\"#B2BAC6\" stroke-width=\"2\" d=\"M52.533 69.093L103 74.5l41-6.013V114.5l4.085-.5V67.916L180 63.5l54.342 9.29a5 5 0 0 1 4.158 4.928v88.524a5 5 0 0 1-5.701 4.95L178.5 163.5l-30.415 5L148 138l-4 .5v30.698l-41 6.302-51.707-7.387a5 5 0 0 1-4.293-4.95V74.065a5 5 0 0 1 5.533-4.971z\" opacity=\".6\"/> <g fill-rule=\"nonzero\"> <path fill=\"#232835\" d=\"M210.681 22.805l-41.374 194.651-30.323-6.445 41.374-194.652 30.323 6.446zm-17.562 8.072a2.83 2.83 0 0 0 3.344-2.18 2.83 2.83 0 0 0-2.169-3.35 2.83 2.83 0 0 0-3.344 2.179 2.83 2.83 0 0 0 2.169 3.35z\"/> <path fill=\"#2068BC\" d=\"M206.27 22.072l-41.375 194.65-30.323-6.444 41.375-194.652 30.322 6.446zm-17.563 8.072a2.83 2.83 0 0 0 3.345-2.18 2.83 2.83 0 0 0-2.17-3.35 2.83 2.83 0 0 0-3.344 2.179 2.83 2.83 0 0 0 2.17 3.35z\"/> <path fill=\"#232835\" d=\"M171.888 33.64l-.402 1.894 21.52 4.574.402-1.893-21.52-4.574zm-1.744 8.206l-.402 1.894 13.042 2.772.402-1.894-13.042-2.772zm-1.744 8.205l-.402 1.894 13.042 2.772.402-1.894-13.042-2.772zm-1.744 8.206l-.402 1.893 13.042 2.772.402-1.893-13.042-2.772zm-1.744 8.205l-.402 1.894 21.519 4.574.402-1.894-21.519-4.574zm-1.744 8.205l-.403 1.894 13.042 2.772.403-1.893-13.042-2.773zm-1.744 8.206l-.403 1.893 13.042 2.772.403-1.893-13.042-2.772zm-1.744 8.205l-.403 1.894 13.042 2.772.403-1.894-13.042-2.772zm-1.879 8.837l-.402 1.893 21.52 4.574.402-1.893-21.52-4.574zm-1.744 8.205l-.402 1.894 13.042 2.772.402-1.894-13.042-2.772zm-1.744 8.205l-.402 1.894 13.042 2.772.402-1.893-13.042-2.773zm-1.744 8.206l-.402 1.893 13.042 2.773.402-1.894-13.042-2.772zm-1.744 8.205l-.402 1.894 21.519 4.574.402-1.894-21.519-4.574zm-1.744 8.206l-.403 1.893 13.042 2.772.403-1.893-13.042-2.772zm-1.744 8.205l-.403 1.893 13.042 2.773.403-1.894-13.042-2.772zm-1.744 8.205l-.403 1.894 13.042 2.772.403-1.894-13.042-2.772zm-1.879 8.837l-.402 1.893 21.52 4.574.402-1.893-21.52-4.574zm-1.744 8.205l-.402 1.894 13.042 2.772.402-1.894-13.042-2.772zm-1.744 8.206l-.402 1.893 13.042 2.772.402-1.893-13.042-2.772zm-1.744 8.205l-.402 1.893 13.042 2.773.402-1.894-13.042-2.772zm-1.744 8.205l-.402 1.894 21.519 4.574.402-1.894-21.519-4.574z\"/> </g> <path fill=\"#C2CAD5\" d=\"M179.31 178.325l7.417 2.997 11.182-11.66 3.746-9.272-18.544-7.492-3.746 9.272zM239.116 67.672l-18.544-7.492 3.746-9.272 18.544 7.492z\"/> <path fill=\"#D70926\" fill-rule=\"nonzero\" d=\"M220.572 60.18l-41.207 101.99c1.124-2.782 4.191-3.7 6.51-2.763 2.317.936 3.333 2.426 3.699 4.19 1.025-1.203 3.441-1.844 5.76-.908 2.78 1.124 3.699 4.191 2.762 6.51l41.207-101.99-18.544-7.493-.187.464z\"/> <path fill=\"#232835\" d=\"M186.727 181.322l-7.455 7.773.037-10.77z\"/> <path fill=\"#D70926\" d=\"M242.862 58.4l-18.544-7.492 2.997-7.418a7 7 0 0 1 9.112-3.868l5.563 2.248a7 7 0 0 1 3.868 9.112l-2.996 7.418z\"/> <path fill=\"#9A1628\" fill-rule=\"nonzero\" d=\"M229.844 63.926l-40.27 99.672c1.489-1.016 3.441-1.845 5.76-.909 2.78 1.124 3.699 4.191 2.762 6.51l41.207-101.99-9.272-3.747-.187.464z\"/> <path fill=\"#9FA7B1\" d=\"M239.116 67.672l-9.272-3.746 3.746-9.272 9.272 3.746z\"/> <path fill=\"#9A1628\" d=\"M242.862 58.4l-9.272-3.746 5.619-13.908 2.781 1.124a7 7 0 0 1 3.868 9.112l-2.996 7.418z\"/> <path stroke=\"#BBE5EE\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M25.5 205.5l46 5.5\"/> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M34.851 203.442l6.641.58-1.85 5.862-6.642-.581z\"/> <g fill-rule=\"nonzero\" stroke=\"#D70926\" stroke-linecap=\"round\" stroke-linejoin=\"round\"> <path stroke-width=\"3\" d=\"M56 105c6.627 0 12-5.373 12-12s-5.373-12-12-12v24z\"/> <g stroke-width=\"2\"> <path d=\"M68 79.5v25.619M56.5 93h22.88\"/> </g> </g> <g fill-rule=\"nonzero\" stroke=\"#D70926\" stroke-linecap=\"round\" stroke-linejoin=\"round\"> <path stroke-width=\"3\" d=\"M56 156c6.627 0 12-5.373 12-12s-5.373-12-12-12v24z\"/> <g stroke-width=\"2\"> <path d=\"M68 130.5v25.619M56.5 144h22.88\"/> </g> </g> <circle cx=\"121\" cy=\"120\" r=\"13\" stroke=\"#D70926\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"3\"/> <g fill-rule=\"nonzero\" stroke=\"#D70926\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"> <path d=\"M109.265 109.265l22.14 22.14M109.751 132.113l22.245-22.245\"/> </g> <path stroke=\"#D70926\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M121.5 108V81M121.5 73v-3M121.5 61v-1\"/> <g transform=\"translate(6 80)\"> <mask id=\"spr-stages-design_d\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-design_c\"/> </mask> <use fill=\"#D70926\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-design_c\"/> <path fill=\"#FFF\" fill-rule=\"nonzero\" d=\"M20 16.377v151.196a3 3 0 0 0 2.628 2.977l71 8.87A3 3 0 0 0 97 176.443V28.085a3 3 0 0 0-2.512-2.96l-71-11.708A3 3 0 0 0 20 16.377z\" mask=\"url(#spr-stages-design_d)\"/> <path stroke=\"#B2BAC6\" stroke-width=\"2\" d=\"M41.57 32.14L97 38.5l77-11 60.34 10.29a5 5 0 0 1 4.16 4.93v50.509a5 5 0 0 1-5.712 4.949L172.5 89.5l-75.5 12-56.73-8.37A5 5 0 0 1 36 88.184V37.107a5 5 0 0 1 5.57-4.968z\" mask=\"url(#spr-stages-design_d)\" opacity=\".6\"/> </g> <g fill-rule=\"nonzero\" stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\"> <path d=\"M186.242 207.986c3.117-1.038 7.305-2.49 12.564-4.358\"/> <path stroke-width=\"2\" d=\"M179.09 204.19c5.6-.053 12.874-1.847 21.82-5.38\"/> </g> </g> </symbol>";
module.exports = sprite.add(image, "spr-stages-design");

/***/ }),
/* 253 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 261 239\" id=\"spr-stages-installation\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"> <defs> <path id=\"spr-stages-installation_a\" d=\"M0 0v8.5h7.5a3.5 3.5 0 0 1 0 7H0v79l15-3 8.5-6.5 3.424-53.316a37 37 0 0 0 .076-2.37V19.226a17 17 0 0 0-4.022-10.98L16 0H0z\"/> <path id=\"spr-stages-installation_c\" d=\"M0 0v8.5h7.5a3.5 3.5 0 0 1 0 7H0v79l15-3 8.5-6.5 3.424-53.316a37 37 0 0 0 .076-2.37V19.226a17 17 0 0 0-4.022-10.98L16 0H0z\"/> <path id=\"spr-stages-installation_e\" d=\"M17.158 1.943l-13.65 17.15a12 12 0 0 0-2.36 9.92L30 167.5c3.333 1 6.167 1.333 8.5 1 3.5-.5 6.5-2.5 6.5-10 0-3.313-.707-24.549-7-60.5-2.136-12.203-6.97-34.203-14.5-66 3-7 3.833-13.5 2.5-19.5-.922-4.148-2.242-7.58-3.96-10.294a3 3 0 0 0-4.882-.263z\"/> <path id=\"spr-stages-installation_g\" d=\"M20 81.5l2 4.5c-9.743 6.735-16.284 2.634-19.297-8.506C.508 69.376.102 57.254.946 40.534c.249-4.92.582-9.91 1.082-16.52C3.564 3.672 3.5 4.61 3.5.5h5c0 4.31.073 3.245-1.486 23.89-.497 6.573-.828 11.526-1.074 16.396-.819 16.212-.426 27.95 1.59 35.403C9.708 84.244 13.355 86.093 20 81.5z\"/> <path id=\"spr-stages-installation_i\" d=\"M17.158 1.943l-13.65 17.15a12 12 0 0 0-2.36 9.92L30 167.5c3.333 1 6.167 1.333 8.5 1 3.5-.5 6.5-2.5 6.5-10 0-3.313-.707-24.549-7-60.5-2.136-12.203-6.97-34.203-14.5-66 3-7 3.833-13.5 2.5-19.5-.922-4.148-2.242-7.58-3.96-10.294a3 3 0 0 0-4.882-.263z\"/> <rect id=\"spr-stages-installation_k\" width=\"29\" height=\"130\" rx=\"2\"/> <rect id=\"spr-stages-installation_m\" width=\"29\" height=\"130\" rx=\"2\"/> </defs> <g fill=\"none\" fill-rule=\"evenodd\"> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M231.5 86.676v92.915a5 5 0 0 1-4.194 4.934l-119.5 19.526a5 5 0 0 1-5.806-4.934V35a5 5 0 0 1 6.985-4.59l119.5 51.676a5 5 0 0 1 3.015 4.59z\" opacity=\".6\"/> <path fill=\"#BBE5EE\" fill-rule=\"nonzero\" d=\"M103.923 9.66v48.806a5 5 0 0 0 2.043 4.032l152.7 111.971a5 5 0 0 1-.456 8.362l-95.276 55.008a5 5 0 0 1-4.195.374L3.305 182.19A5 5 0 0 1 0 177.487v-113.6a5 5 0 0 1 2.5-4.33L96.423 5.33a5 5 0 0 1 7.5 4.33z\"/> <path fill=\"#FFF\" fill-rule=\"nonzero\" d=\"M143 47.535V20a2 2 0 0 1 2-2h27a2 2 0 0 1 2 2 2 2 0 0 1 2-2h25a2 2 0 0 1 2 2v27.535a4.017 4.017 0 0 1 1.646 1.819A4 4 0 0 1 207 53v65a4 4 0 0 1-4 4v24a2 2 0 0 1-2 2h-25a2 2 0 0 1-2-2 2 2 0 0 1-2 2h-27a2 2 0 0 1-2-2v-24.535a3.998 3.998 0 0 1-2-3.465V51c0-1.48.804-2.773 2-3.465z\"/> <g transform=\"translate(88 4)\"> <mask id=\"spr-stages-installation_b\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_a\"/> </mask> <use fill=\"#646C78\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_a\"/> <path fill=\"#232835\" fill-rule=\"nonzero\" d=\"M-1 27c14.667 1.333 26.333 5 35 11 8.667 6 11.667 29 9 69l-44 1.5V27z\" mask=\"url(#spr-stages-installation_b)\"/> </g> <g transform=\"matrix(-1 0 0 1 88 4)\"> <mask id=\"spr-stages-installation_d\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_c\"/> </mask> <use fill=\"#646C78\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_c\"/> <path fill=\"#232835\" fill-rule=\"nonzero\" d=\"M-1 27c14.667 1.333 26.333 5 35 11 8.667 6 11.667 29 9 69l-44 1.5V27z\" mask=\"url(#spr-stages-installation_d)\"/> </g> <circle cx=\"88\" cy=\"77\" r=\"11\" fill=\"#646C78\" fill-rule=\"nonzero\"/> <rect width=\"30\" height=\"8\" x=\"73\" y=\"47\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"4\"/> <rect width=\"8\" height=\"8\" x=\"73\" y=\"59\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"4\"/> <rect width=\"8\" height=\"8\" x=\"62\" y=\"20\" fill=\"#C2CAD5\" fill-rule=\"nonzero\" rx=\"4\"/> <rect width=\"8\" height=\"8\" x=\"95\" y=\"59\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"4\"/> <rect width=\"12\" height=\"12\" x=\"82\" y=\"71\" fill=\"#232835\" fill-rule=\"nonzero\" rx=\"4\"/> <path fill=\"#ABB4C1\" fill-rule=\"nonzero\" d=\"M80.03 1.943l2.787 4.643v2H64a2 2 0 0 1-.702-3.873l11.9-4.458a4 4 0 0 1 4.833 1.688zM92.786 1.943L90 6.586v2h18.817a2 2 0 0 0 .702-3.873L97.619.255a4 4 0 0 0-4.833 1.688z\"/> <path fill=\"#2068BC\" fill-rule=\"nonzero\" d=\"M98.05 19.086l-.6-.172A2 2 0 0 1 96 16.991v-1.3a2 2 0 0 1 1.047-1.758l8.926-4.835a2.854 2.854 0 0 1 3.527.652l.296.346A5 5 0 0 1 111 13.35v3.822a2 2 0 0 1-.586 1.414l-2.828 2.828a2 2 0 0 0-.586 1.414V39a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2V21.009a2 2 0 0 0-1.45-1.923zM77 34h10v6H77a3 3 0 0 1 0-6z\"/> <path fill=\"#C2CAD5\" fill-rule=\"nonzero\" d=\"M87 19.421V40a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V21.152a2 2 0 0 0-1.622-1.964l-9-1.73A2 2 0 0 0 87 19.42zM72 19v10a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V19a2 2 0 0 0-2-2h-9a2 2 0 0 0-2 2z\"/> <g transform=\"matrix(-1 0 0 1 81 64)\"> <mask id=\"spr-stages-installation_f\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_e\"/> </mask> <use fill=\"#2068BC\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_e\"/> <path fill=\"#232835\" fill-rule=\"nonzero\" d=\"M26.5 3.5L7.957 28.224a9 9 0 0 0-1.602 7.28l9.228 43.201a60 60 0 0 0 2.26 7.895c4.379 12.09 7.93 25.557 10.657 40.4 2.21 12.031 3.696 21.259 4.46 27.683a8 8 0 0 0 6.48 6.922L55 164.5 26.5 3.5z\" mask=\"url(#spr-stages-installation_f)\"/> </g> <path fill-rule=\"nonzero\" stroke=\"#F8E71C\" stroke-width=\"3\" d=\"M98.906 11.436c-36.797-2.678-59.63 6.989-68.5 29-13.5 33.5 15.354 64.196 47.5 83.5 44.41 26.667 134.428 60.92 94 90-28.5 20.5-15.5-60.34-15.5-82.5\"/> <g transform=\"translate(150 130)\"> <mask id=\"spr-stages-installation_h\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_g\"/> </mask> <use fill=\"#D70926\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_g\"/> <path fill=\"#9A1628\" fill-rule=\"nonzero\" d=\"M-56.694-110.354l.177 4.997c-26.528.94-43.08 10.213-50.164 27.791-11.34 28.14 8.289 57.497 46.468 80.423 5.161 3.099 11.14 6.435 19.018 10.657 4.099 2.196 18.913 10.02 21.408 11.349C3.749 37.398 17.879 45.867 28.554 54.598c19.36 15.835 22.5 30.403 4.916 42.777l-2.878-4.089C44.855 83.25 42.538 72.496 25.39 58.47 15.049 50.01 1.114 41.66-22.138 29.276c-2.482-1.322-17.303-9.15-21.418-11.355-7.944-4.257-13.987-7.629-19.231-10.778-17.831-10.707-32.523-23.516-41.659-37.18-10.758-16.09-13.539-32.856-6.873-49.397 7.907-19.62 26.284-29.916 54.625-30.92z\" mask=\"url(#spr-stages-installation_h)\"/> </g> <path fill=\"#D70926\" fill-rule=\"nonzero\" d=\"M82.22 8l.177 4.997c-26.528.939-43.08 10.213-50.164 27.791-11.34 28.14 8.289 57.496 46.468 80.422 5.161 3.1 11.14 6.436 19.018 10.657 4.099 2.197 18.913 10.02 21.408 11.35 23.536 12.535 37.666 21.003 48.341 29.735 19.36 15.835 22.115 30.674 4.532 43.048l-2-4.5c14.263-10.037 11.452-20.65-5.697-34.678-10.34-8.457-24.275-16.808-47.527-29.193-2.482-1.321-17.303-9.15-21.418-11.354-7.944-4.257-13.987-7.629-19.231-10.778-17.831-10.707-32.523-23.516-41.659-37.18-10.758-16.09-13.539-32.856-6.873-49.398C35.502 19.3 53.88 9.003 82.22 8z\"/> <g transform=\"translate(95 64)\"> <mask id=\"spr-stages-installation_j\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_i\"/> </mask> <use fill=\"#2068BC\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_i\"/> <path fill=\"#232835\" fill-rule=\"nonzero\" d=\"M26.5 3.5L7.957 28.224a9 9 0 0 0-1.602 7.28l9.228 43.201a60 60 0 0 0 2.26 7.895c4.379 12.09 7.93 25.557 10.657 40.4 2.21 12.031 3.696 21.259 4.46 27.683a8 8 0 0 0 6.48 6.922L55 164.5 26.5 3.5z\" mask=\"url(#spr-stages-installation_j)\"/> </g> <g transform=\"translate(139 14)\"> <rect width=\"29\" height=\"130\" x=\"4\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"2\"/> <g transform=\"translate(2)\"> <mask id=\"spr-stages-installation_l\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_k\"/> </mask> <use fill=\"#B2BAC6\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_k\"/> <rect width=\"66\" height=\"73\" x=\"-2\" y=\"31\" fill=\"#000\" fill-opacity=\".1\" fill-rule=\"nonzero\" mask=\"url(#spr-stages-installation_l)\" rx=\"4\"/> </g> <g transform=\"translate(33)\"> <mask id=\"spr-stages-installation_n\" fill=\"#fff\"> <use xlink:href=\"#spr-stages-installation_m\"/> </mask> <use fill=\"#B2BAC6\" fill-rule=\"nonzero\" xlink:href=\"#spr-stages-installation_m\"/> <rect width=\"66\" height=\"73\" x=\"-33\" y=\"31\" fill=\"#000\" fill-opacity=\".1\" fill-rule=\"nonzero\" mask=\"url(#spr-stages-installation_n)\" rx=\"4\"/> </g> <rect width=\"14\" height=\"14\" x=\"10\" y=\"5\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"7\"/> <rect width=\"14\" height=\"14\" x=\"10\" y=\"110\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"7\"/> <rect width=\"14\" height=\"14\" x=\"41\" y=\"5\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"7\"/> <rect width=\"14\" height=\"14\" x=\"41\" y=\"110\" fill=\"#646C78\" fill-rule=\"nonzero\" rx=\"7\"/> <rect width=\"64\" height=\"73\" y=\"29\" fill=\"#C2CAD5\" fill-rule=\"nonzero\" rx=\"4\"/> <g fill-rule=\"nonzero\"> <path fill=\"#9A1628\" d=\"M9 72h16v17a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2V72zM39 72h16v17a2 2 0 0 1-2 2H41a2 2 0 0 1-2-2V72z\"/> <path fill=\"#D70926\" d=\"M9 82h16v7a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2v-7zM39 82h16v7a2 2 0 0 1-2 2H41a2 2 0 0 1-2-2v-7z\"/> </g> <g fill-rule=\"nonzero\"> <path fill=\"#9A1628\" d=\"M4 68h56v-5a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v5z\"/> <path fill=\"#D70926\" d=\"M4 73h56v-5a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v5z\"/> </g> <path fill=\"#232835\" fill-rule=\"nonzero\" d=\"M47 11V8a1 1 0 0 1 2 0v3h3a1 1 0 0 1 0 2h-3v3a1 1 0 0 1-2 0v-3h-3a1 1 0 0 1 0-2h3zM16 11V8a1 1 0 0 1 2 0v3h3a1 1 0 0 1 0 2h-3v3a1 1 0 0 1-2 0v-3h-3a1 1 0 0 1 0-2h3zM16 116v-3a1 1 0 1 1 2 0v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3h-3a1 1 0 1 1 0-2h3zM47 116v-3a1 1 0 1 1 2 0v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3h-3a1 1 0 1 1 0-2h3z\"/> <rect width=\"21\" height=\"4\" x=\"7\" y=\"36\" fill=\"#D70926\" fill-rule=\"nonzero\" rx=\"2\"/> <rect width=\"21\" height=\"4\" x=\"7\" y=\"42\" fill=\"#D70926\" fill-rule=\"nonzero\" rx=\"2\"/> <rect width=\"15\" height=\"4\" x=\"7\" y=\"48\" fill=\"#D70926\" fill-rule=\"nonzero\" rx=\"2\"/> </g> <path fill-rule=\"nonzero\" stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M176 167c10.333 9.667 16.333 18.167 18 25.5 1.667 7.333.333 13.667-4 19\"/> <path fill-rule=\"nonzero\" stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M194 179c2.745 4.91 4.566 9.676 5.463 14.3.897 4.624.573 8.857-.971 12.7\"/> <path fill-rule=\"nonzero\" stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M16 71c1.333 7.667 6.167 17.333 14.5 29\"/> <path fill=\"#FFF\" fill-rule=\"nonzero\" d=\"M189.283 177.677l5.446 8.387-5.283 1.046-5.446-8.387zM21.064 80.13l3.63 5.59-5.758 2.15-3.63-5.59z\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-stages-installation");

/***/ }),
/* 254 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 257 230\" id=\"spr-stages-repairs\" > <g fill=\"none\" fill-rule=\"nonzero\"> <path fill=\"#BBE5EE\" d=\"M239.5 128.167v80.449a5 5 0 0 1-6.37 4.808l-105.5-30.067a5 5 0 0 1-3.63-4.809V9.874a5 5 0 0 1 8.72-3.34L237.71 123.49a7 7 0 0 1 1.79 4.676z\" opacity=\".6\"/> <path fill=\"#BBE5EE\" d=\"M149.923 116.432V79.368a5 5 0 0 1 8.811-3.236l97 114.224a5 5 0 0 1 1.189 3.237v29.484a5 5 0 0 1-6.346 4.815l-94.308-26.362a5 5 0 0 0-6.346 4.815v8.926a5 5 0 0 1-6.171 4.861L29.829 192.679A5 5 0 0 1 26 187.818V22a5 5 0 0 1 8.444-3.625l107.035 101.682a5 5 0 0 0 8.444-3.625z\"/> <path fill=\"#FFF\" d=\"M76.022 86.296l3.026 5.94-6.842 1.99-3.027-5.94zM104.642 55.366l1.311-.069a3.3 3.3 0 0 1 3.372 2.487l5.805 22.955 2.64-.138a2.479 2.479 0 0 1 .26 4.95l-2.64.139 4.946 94.377a3.3 3.3 0 0 1-.008.461l-2.941 33.485a3.3 3.3 0 0 1-3.115 3.006l-1.155.06-2.251.119a3.3 3.3 0 0 1-3.412-2.665l-6.425-32.993a3.3 3.3 0 0 1-.057-.458l-4.946-94.377-2.64.138a2.479 2.479 0 0 1-.259-4.95l2.64-.139 3.373-23.437a3.3 3.3 0 0 1 3.094-2.825l2.408-.126z\"/> <path stroke=\"#D70926\" stroke-width=\"4.4\" d=\"M109.752 197.846c1.126 8.015-25.621 15.274-39.978 3.394-12.513-10.355-8.392-24.407-10.843-57.435C55.502 97.6 52.77 72.363 36.46 78.548c-16.31 6.186 3.328 47.343 28.824 71.026 25.495 23.684 25.447 50.945 11.861 52.855-13.586 1.909-23.327-20.083-14.786-65.78 8.54-45.698-5.416-54.305-17.685-50.912-12.269 3.393 1.778 48.14 22.298 75.849 12.142 16.396 17.254 40.054 9.853 46.45-6.918 5.978-18.63 1.506-23.04-6.218\"/> <g transform=\"scale(-1 1) rotate(3 -52.851 -2178.566)\"> <rect width=\"3.293\" height=\"30.845\" x=\"13.171\" fill=\"#D8D8D8\" rx=\"1.646\"/> <rect width=\"5.488\" height=\"30.845\" x=\"12.073\" y=\"18.727\" fill=\"#D70926\" rx=\"2.2\"/> <path fill=\"#2068BC\" d=\"M15.366 38.556h1.313a3.3 3.3 0 0 1 3.237 2.66l4.596 23.228h2.644a2.479 2.479 0 1 1 0 4.957h-2.644v94.507a3.3 3.3 0 0 1-.032.46l-4.69 33.284a3.3 3.3 0 0 1-3.267 2.84h-3.411a3.3 3.3 0 0 1-3.268-2.84l-4.69-33.284a3.3 3.3 0 0 1-.032-.46V69.4H2.479a2.479 2.479 0 1 1 0-4.957h2.643l4.596-23.228a3.3 3.3 0 0 1 3.237-2.66h2.41z\"/> <rect width=\"14.268\" height=\"87.027\" x=\"7.683\" y=\"71.604\" fill=\"#232835\" rx=\"2.2\"/> <ellipse cx=\"17.012\" cy=\"79.866\" fill=\"#D70926\" rx=\"2.744\" ry=\"2.754\"/> <ellipse cx=\"17.012\" cy=\"90.882\" fill=\"#D70926\" rx=\"2.744\" ry=\"2.754\"/> <ellipse cx=\"17.012\" cy=\"101.898\" fill=\"#D70926\" rx=\"2.744\" ry=\"2.754\"/> </g> <g transform=\"scale(-1 1) rotate(22 -187.347 3.613)\"> <rect width=\"2.195\" height=\"30.845\" x=\"10.976\" fill=\"#D8D8D8\" rx=\"1.098\"/> <path fill=\"#232835\" d=\"M14.373 16.166l1.725 27.663 6.74 2.316a1.938 1.938 0 0 1 1.308 1.833c0 .833-.513 1.58-1.29 1.88l-4.459 1.716-2.247 86.307-4.077 1.107-4.076-1.107-2.248-86.307-4.458-1.716A2.014 2.014 0 0 1 0 47.978c0-.827.525-1.564 1.308-1.833l6.74-2.316 1.726-27.663a3.93 3.93 0 0 1 4.599 0z\"/> </g> <g transform=\"scale(-1 1) rotate(-8 2.628 1775.728)\"> <path fill=\"#232835\" d=\"M46.199 4.113h8.087a2.06 2.06 0 0 0 1.455-.602l2.916-2.91A2.06 2.06 0 0 1 60.112 0H74.72l3.608 4.113c6.577 0 12.227 4.665 13.458 11.112l4.062 21.28 4.638 4.113v45.76l-3.607 3.598.987 105.888a10.273 10.273 0 0 1-3.434 7.759l-13.17 11.757a10.32 10.32 0 0 1-6.872 2.62H26.096a10.32 10.32 0 0 1-6.872-2.62l-13.17-11.757a10.273 10.273 0 0 1-3.434-7.76l.987-105.887L0 86.377v-45.76l4.638-4.112L8.7 15.225C9.931 8.778 15.58 4.113 22.158 4.113L25.765 0h14.608a2.06 2.06 0 0 1 1.455.602l2.916 2.91c.386.385.91.601 1.455.601z\"/> <rect width=\"4\" height=\"48\" x=\"90.01\" y=\"139.861\" fill=\"#7F7F7F\" rx=\"2\"/> <rect width=\"7\" height=\"7\" x=\"87\" y=\"193\" fill=\"#7F7F7F\" rx=\"3.5\"/> <path fill=\"#D51B28\" d=\"M0 86.377v-45.76l15.974-14.746L20.96 4.165a13.83 13.83 0 0 1 1.198-.052L25.765 0h14.606c.546 0 1.07.217 1.457.602l2.915 2.909c.387.386.911.602 1.458.602h8.083c.547 0 1.07-.216 1.457-.602L58.656.602C59.043.217 59.567 0 60.114 0h14.605l3.12 3.558 5.125 22.313 17.52 14.747v45.76l-13.913 13.53-2.576 86.378L68.02 198.11H30.918l-15.974-11.825-2.577-86.378L0 86.378z\"/> <rect width=\"7\" height=\"4\" x=\"58.741\" y=\"18.702\" fill=\"#FFF\" rx=\"2\"/> <rect width=\"7\" height=\"4\" x=\"45.741\" y=\"18.702\" fill=\"#FFF\" rx=\"2\"/> <rect width=\"7\" height=\"4\" x=\"32.589\" y=\"18.873\" fill=\"#FFF\" rx=\"2\"/> <path fill=\"#646C78\" d=\"M13.398 31.877h73.173v61.698H13.398z\"/> <rect width=\"60.806\" height=\"45.245\" x=\"19.582\" y=\"40.104\" fill=\"#BBE5EE\" rx=\"2.06\"/> <rect width=\"2\" height=\"12\" x=\"58.832\" y=\"55.408\" fill=\"#646C78\" rx=\"1\"/> <rect width=\"4\" height=\"22\" x=\"52.832\" y=\"50.408\" fill=\"#646C78\" rx=\"2\"/> <rect width=\"2\" height=\"10\" x=\"47.832\" y=\"56.408\" fill=\"#646C78\" rx=\"1\"/> <rect width=\"4\" height=\"16\" x=\"40.832\" y=\"53.408\" fill=\"#646C78\" rx=\"2\"/> <path fill=\"#232835\" d=\"M22.176 106H78.63a2.06 2.06 0 0 1 2.057 2.172l-2.866 52.437a2.06 2.06 0 0 1-2.057 1.948H25.042a2.06 2.06 0 0 1-2.057-1.948l-2.866-52.437A2.06 2.06 0 0 1 22.176 106z\"/> <path fill=\"#7F7F7F\" d=\"M64.812 118.826v5.766c0 .456-.151.9-.431 1.26l-3.089 3.98c-.39.502-.992.797-1.63.797H43.137a2.063 2.063 0 0 1-1.632-.8l-3.075-3.978a2.054 2.054 0 0 1-.429-1.256v-5.771c0-.47.161-.926.457-1.292l3.047-3.767a2.063 2.063 0 0 1 1.604-.765H59.69c.621 0 1.21.28 1.601.762l3.06 3.77c.298.366.46.823.46 1.294z\"/> </g> <path stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M58.5 74.622c11.22 1.683 20.195 19.074 17.39 35.903\"/> <path stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M75 78c6.17 5.049 8.976 16.269 8.976 21.879\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-stages-repairs");

/***/ }),
/* 255 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 2666.66875 426.667\" id=\"spr-star-grey-5\" > <g transform=\"translate(53.33375 0)\"> <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(586.66825 0)\"> <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(1120.00275 0)\"> <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(1653.33725 0)\"> <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(2186.67175 0)\"> <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-star-grey-5");

/***/ }),
/* 256 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 426.667 426.667\" id=\"spr-star-grey\" > <polygon style=\"fill:#dfe0e0;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </symbol>";
module.exports = sprite.add(image, "spr-star-grey");

/***/ }),
/* 257 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 2666.66875 426.667\" id=\"spr-star-red-5\" > <g transform=\"translate(53.33375 0)\"> <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(586.66825 0)\"> <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(1120.00275 0)\"> <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(1653.33725 0)\"> <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> <g transform=\"translate(2186.67175 0)\"> <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-star-red-5");

/***/ }),
/* 258 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 426.667 426.667\" id=\"spr-star-red\" > <polygon style=\"fill:#d70926;\" points=\"213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91\r\n\t81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 \"/> </symbol>";
module.exports = sprite.add(image, "spr-star-red");

/***/ }),
/* 259 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 69 69\" id=\"spr-video\" > <path fill=\"#FFF\" fill-rule=\"nonzero\" d=\"M34.5 69C15.446 69 0 53.554 0 34.5 0 15.446 15.446 0 34.5 0 53.554 0 69 15.446 69 34.5 69 53.554 53.554 69 34.5 69zm14.963-31.952a2.4 2.4 0 0 0 0-4.293L25.938 20.992a2.4 2.4 0 0 0-3.473 2.147v23.524a2.4 2.4 0 0 0 3.473 2.147l23.525-11.762z\"/> </symbol>";
module.exports = sprite.add(image, "spr-video");

/***/ }),
/* 260 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 42 42\" id=\"spr-vk\" > <g fill=\"none\" fill-rule=\"evenodd\"> <path fill-rule=\"nonzero\" stroke=\"#c7c7c7\" d=\"M37.024 1H4.937A3.937 3.937 0 0 0 1 4.937v32.087a3.937 3.937 0 0 0 3.937 3.937h32.087a3.937 3.937 0 0 0 3.937-3.937V4.937A3.937 3.937 0 0 0 37.024 1\"/> <path fill=\"#c7c7c7\" d=\"M20.657 30.055h1.803s.544-.06.823-.36c.256-.275.248-.792.248-.792s-.036-2.42 1.087-2.776c1.107-.35 2.529 2.339 4.035 3.373 1.14.782 2.005.611 2.005.611l4.03-.056s2.107-.13 1.107-1.787c-.082-.136-.582-1.226-2.995-3.466-2.527-2.345-2.188-1.965.855-6.021 1.853-2.47 2.594-3.978 2.363-4.624-.22-.615-1.584-.453-1.584-.453l-4.536.029s-.337-.046-.586.103c-.244.146-.4.487-.4.487s-.719 1.91-1.676 3.536c-2.02 3.43-2.827 3.612-3.157 3.398-.769-.496-.577-1.993-.577-3.057 0-3.325.505-4.71-.981-5.07-.493-.118-.857-.197-2.118-.21-1.618-.016-2.988.005-3.764.386-.516.252-.914.815-.671.848.3.04.978.183 1.338.672.465.633.449 2.053.449 2.053s.267 3.913-.624 4.399c-.611.333-1.45-.347-3.25-3.46-.923-1.593-1.62-3.355-1.62-3.355s-.134-.33-.374-.506c-.29-.213-.697-.28-.697-.28l-4.31.027s-.647.018-.885.3c-.211.25-.017.768-.017.768s3.375 7.895 7.196 11.874c3.504 3.648 7.483 3.409 7.483 3.409\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-vk");

/***/ }),
/* 261 */
/***/ (function(module, exports, __webpack_require__) {


var sprite = __webpack_require__(1);
var image = "<symbol viewBox=\"0 0 40 40\" id=\"spr-zoom\" > <g fill=\"none\" fill-rule=\"evenodd\" stroke=\"#FFF\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"> <circle cx=\"16\" cy=\"16\" r=\"13\"/> <path d=\"M10.5 16.5h11.201M16 11v11.201M25 26l11.54 11.54\"/> </g> </symbol>";
module.exports = sprite.add(image, "spr-zoom");

/***/ }),
/* 262 */
/***/ (function(module, exports, __webpack_require__) {

var Sniffr = __webpack_require__(230);

/**
 * List of SVG attributes to fix url target in them
 * @type {string[]}
 */
var fixAttributes = [
  'clipPath',
  'colorProfile',
  'src',
  'cursor',
  'fill',
  'filter',
  'marker',
  'markerStart',
  'markerMid',
  'markerEnd',
  'mask',
  'stroke'
];

/**
 * Query to find'em
 * @type {string}
 */
var fixAttributesQuery = '[' + fixAttributes.join('],[') + ']';
/**
 * @type {RegExp}
 */
var URI_FUNC_REGEX = /^url\((.*)\)$/;

/**
 * Convert array-like to array
 * @param {Object} arrayLike
 * @returns {Array.<*>}
 */
function arrayFrom(arrayLike) {
  return Array.prototype.slice.call(arrayLike, 0);
}

/**
 * Handles forbidden symbols which cannot be directly used inside attributes with url(...) content.
 * Adds leading slash for the brackets
 * @param {string} url
 * @return {string} encoded url
 */
function encodeUrlForEmbedding(url) {
  return url.replace(/\(|\)/g, "\\$&");
}

/**
 * Replaces prefix in `url()` functions
 * @param {Element} svg
 * @param {string} currentUrlPrefix
 * @param {string} newUrlPrefix
 */
function baseUrlWorkAround(svg, currentUrlPrefix, newUrlPrefix) {
  var nodes = svg.querySelectorAll(fixAttributesQuery);

  if (!nodes) {
    return;
  }

  arrayFrom(nodes).forEach(function (node) {
    if (!node.attributes) {
      return;
    }

    arrayFrom(node.attributes).forEach(function (attribute) {
      var attributeName = attribute.localName.toLowerCase();

      if (fixAttributes.indexOf(attributeName) !== -1) {
        var match = URI_FUNC_REGEX.exec(node.getAttribute(attributeName));

        // Do not touch urls with unexpected prefix
        if (match && match[1].indexOf(currentUrlPrefix) === 0) {
          var referenceUrl = encodeUrlForEmbedding(newUrlPrefix + match[1].split(currentUrlPrefix)[1]);
          node.setAttribute(attributeName, 'url(' + referenceUrl + ')');
        }
      }
    });
  });
}

/**
 * Because of Firefox bug #353575 gradients and patterns don't work if they are within a symbol.
 * To workaround this we move the gradient definition outside the symbol element
 * @see https://bugzilla.mozilla.org/show_bug.cgi?id=353575
 * @param {Element} svg
 */
var FirefoxSymbolBugWorkaround = function (svg) {
  var defs = svg.querySelector('defs');

  var moveToDefsElems = svg.querySelectorAll('symbol linearGradient, symbol radialGradient, symbol pattern');
  for (var i = 0, len = moveToDefsElems.length; i < len; i++) {
    defs.appendChild(moveToDefsElems[i]);
  }
};

/**
 * Fix for browser (IE, maybe other too) which are throwing 'WrongDocumentError'
 * if you insert an element which is not part of the document
 * @see http://stackoverflow.com/questions/7981100/how-do-i-dynamically-insert-an-svg-image-into-html#7986519
 * @param {Element} svg
 */
function importSvg(svg) {
  try {
    if (document.importNode) {
      return document.importNode(svg, true);
    }
  } catch(e) {}

  return svg;
}

/**
 * @type {string}
 */
var DEFAULT_URI_PREFIX = '#';

/**
 * @type {string}
 */
var xLinkHref = 'xlink:href';
/**
 * @type {string}
 */
var xLinkNS = 'http://www.w3.org/1999/xlink';
/**
 * @type {string}
 */
var svgOpening = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="' + xLinkNS + '"';
/**
 * @type {string}
 */
var svgClosing = '</svg>';
/**
 * @type {string}
 */
var contentPlaceHolder = '{content}';

/**
 * Representation of SVG sprite
 * @constructor
 */
function Sprite() {
  var baseElement = document.getElementsByTagName('base')[0];
  var currentUrl = window.location.href.split('#')[0];
  var baseUrl = baseElement && baseElement.href;
  this.urlPrefix = baseUrl && baseUrl !== currentUrl ? currentUrl + DEFAULT_URI_PREFIX : DEFAULT_URI_PREFIX;

  var sniffr = new Sniffr();
  sniffr.sniff();
  this.browser = sniffr.browser;
  this.content = [];

  if (this.browser.name !== 'ie' && baseUrl) {
    window.addEventListener('spriteLoaderLocationUpdated', function (e) {
      var currentPrefix = this.urlPrefix;
      var newUrlPrefix = e.detail.newUrl.split(DEFAULT_URI_PREFIX)[0] + DEFAULT_URI_PREFIX;
      baseUrlWorkAround(this.svg, currentPrefix, newUrlPrefix);
      this.urlPrefix = newUrlPrefix;

      if (this.browser.name === 'firefox' || this.browser.name === 'edge' || this.browser.name === 'chrome' && this.browser.version[0] >= 49) {
        var nodes = arrayFrom(document.querySelectorAll('use[*|href]'));
        nodes.forEach(function (node) {
          var href = node.getAttribute(xLinkHref);
          if (href && href.indexOf(currentPrefix) === 0) {
            node.setAttributeNS(xLinkNS, xLinkHref, newUrlPrefix + href.split(DEFAULT_URI_PREFIX)[1]);
          }
        });
      }
    }.bind(this));
  }
}

Sprite.styles = ['position:absolute', 'width:0', 'height:0'];

Sprite.spriteTemplate = function(){ return svgOpening + ' style="'+ Sprite.styles.join(';') +'"><defs>' + contentPlaceHolder + '</defs>' + svgClosing; }
Sprite.symbolTemplate = function() { return svgOpening + '>' + contentPlaceHolder + svgClosing; }

/**
 * @type {Array<String>}
 */
Sprite.prototype.content = null;

/**
 * @param {String} content
 * @param {String} id
 */
Sprite.prototype.add = function (content, id) {
  if (this.svg) {
    this.appendSymbol(content);
  }

  this.content.push(content);

  return DEFAULT_URI_PREFIX + id;
};

/**
 *
 * @param content
 * @param template
 * @returns {Element}
 */
Sprite.prototype.wrapSVG = function (content, template) {
  var svgString = template.replace(contentPlaceHolder, content);

  var svg = new DOMParser().parseFromString(svgString, 'image/svg+xml').documentElement;
  var importedSvg = importSvg(svg);

  if (this.browser.name !== 'ie' && this.urlPrefix) {
    baseUrlWorkAround(importedSvg, DEFAULT_URI_PREFIX, this.urlPrefix);
  }

  return importedSvg;
};

Sprite.prototype.appendSymbol = function (content) {
  var symbol = this.wrapSVG(content, Sprite.symbolTemplate()).childNodes[0];

  this.svg.querySelector('defs').appendChild(symbol);
  if (this.browser.name === 'firefox') {
    FirefoxSymbolBugWorkaround(this.svg);
  }
};

/**
 * @returns {String}
 */
Sprite.prototype.toString = function () {
  var wrapper = document.createElement('div');
  wrapper.appendChild(this.render());
  return wrapper.innerHTML;
};

/**
 * @param {HTMLElement} [target]
 * @param {Boolean} [prepend=true]
 * @returns {HTMLElement} Rendered sprite node
 */
Sprite.prototype.render = function (target, prepend) {
  target = target || null;
  prepend = typeof prepend === 'boolean' ? prepend : true;

  var svg = this.wrapSVG(this.content.join(''), Sprite.spriteTemplate());

  if (this.browser.name === 'firefox') {
    FirefoxSymbolBugWorkaround(svg);
  }

  if (target) {
    if (prepend && target.childNodes[0]) {
      target.insertBefore(svg, target.childNodes[0]);
    } else {
      target.appendChild(svg);
    }
  }

  this.svg = svg;

  return svg;
};

module.exports = Sprite;


/***/ }),
/* 263 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(__dirname) {(function webpackUniversalModuleDefinition(root, factory) {
	if(true)
		module.exports = factory((function webpackLoadOptionalExternalModule() { try { return __webpack_require__(93); } catch(e) {} }()), __webpack_require__(90));
	else if(typeof define === 'function' && define.amd)
		define(["fs", "path"], factory);
	else if(typeof exports === 'object')
		exports["Twig"] = factory((function webpackLoadOptionalExternalModule() { try { return require("fs"); } catch(e) {} }()), require("path"));
	else
		root["Twig"] = factory(root["fs"], root["path"]);
})(this, function(__WEBPACK_EXTERNAL_MODULE_19__, __WEBPACK_EXTERNAL_MODULE_20__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	/**
	 * Twig.js
	 *
	 * @copyright 2011-2016 John Roepke and the Twig.js Contributors
	 * @license   Available under the BSD 2-Clause License
	 * @link      https://github.com/twigjs/twig.js
	 */

	var Twig = {
	    VERSION: '0.10.2'
	};

	__webpack_require__(1)(Twig);
	__webpack_require__(2)(Twig);
	__webpack_require__(3)(Twig);
	__webpack_require__(5)(Twig);
	__webpack_require__(6)(Twig);
	__webpack_require__(7)(Twig);
	__webpack_require__(17)(Twig);
	__webpack_require__(18)(Twig);
	__webpack_require__(21)(Twig);
	__webpack_require__(22)(Twig);
	__webpack_require__(23)(Twig);
	__webpack_require__(24)(Twig);
	__webpack_require__(25)(Twig);
	__webpack_require__(26)(Twig);
	__webpack_require__(27)(Twig);

	module.exports = Twig.exports;


/***/ },
/* 1 */
/***/ function(module, exports) {

	// ## twig.core.js
	//
	// This file handles template level tokenizing, compiling and parsing.
	module.exports = function (Twig) {
	    "use strict";

	    Twig.trace = false;
	    Twig.debug = false;

	    // Default caching to true for the improved performance it offers
	    Twig.cache = true;

	    Twig.noop = function() {};

	    Twig.placeholders = {
	        parent: "{{|PARENT|}}"
	    };

	    /**
	     * Fallback for Array.indexOf for IE8 et al
	     */
	    Twig.indexOf = function (arr, searchElement /*, fromIndex */ ) {
	        if (Array.prototype.hasOwnProperty("indexOf")) {
	            return arr.indexOf(searchElement);
	        }
	        if (arr === void 0 || arr === null) {
	            throw new TypeError();
	        }
	        var t = Object(arr);
	        var len = t.length >>> 0;
	        if (len === 0) {
	            return -1;
	        }
	        var n = 0;
	        if (arguments.length > 0) {
	            n = Number(arguments[1]);
	            if (n !== n) { // shortcut for verifying if it's NaN
	                n = 0;
	            } else if (n !== 0 && n !== Infinity && n !== -Infinity) {
	                n = (n > 0 || -1) * Math.floor(Math.abs(n));
	            }
	        }
	        if (n >= len) {
	            // console.log("indexOf not found1 ", JSON.stringify(searchElement), JSON.stringify(arr));
	            return -1;
	        }
	        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
	        for (; k < len; k++) {
	            if (k in t && t[k] === searchElement) {
	                return k;
	            }
	        }
	        if (arr == searchElement) {
	            return 0;
	        }
	        // console.log("indexOf not found2 ", JSON.stringify(searchElement), JSON.stringify(arr));

	        return -1;
	    }

	    Twig.forEach = function (arr, callback, thisArg) {
	        if (Array.prototype.forEach ) {
	            return arr.forEach(callback, thisArg);
	        }

	        var T, k;

	        if ( arr == null ) {
	          throw new TypeError( " this is null or not defined" );
	        }

	        // 1. Let O be the result of calling ToObject passing the |this| value as the argument.
	        var O = Object(arr);

	        // 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".
	        // 3. Let len be ToUint32(lenValue).
	        var len = O.length >>> 0; // Hack to convert O.length to a UInt32

	        // 4. If IsCallable(callback) is false, throw a TypeError exception.
	        // See: http://es5.github.com/#x9.11
	        if ( {}.toString.call(callback) != "[object Function]" ) {
	          throw new TypeError( callback + " is not a function" );
	        }

	        // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
	        if ( thisArg ) {
	          T = thisArg;
	        }

	        // 6. Let k be 0
	        k = 0;

	        // 7. Repeat, while k < len
	        while( k < len ) {

	          var kValue;

	          // a. Let Pk be ToString(k).
	          //   This is implicit for LHS operands of the in operator
	          // b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.
	          //   This step can be combined with c
	          // c. If kPresent is true, then
	          if ( k in O ) {

	            // i. Let kValue be the result of calling the Get internal method of O with argument Pk.
	            kValue = O[ k ];

	            // ii. Call the Call internal method of callback with T as the this value and
	            // argument list containing kValue, k, and O.
	            callback.call( T, kValue, k, O );
	          }
	          // d. Increase k by 1.
	          k++;
	        }
	        // 8. return undefined
	    };

	    Twig.merge = function(target, source, onlyChanged) {
	        Twig.forEach(Object.keys(source), function (key) {
	            if (onlyChanged && !(key in target)) {
	                return;
	            }

	            target[key] = source[key]
	        });

	        return target;
	    };

	    /**
	     * Exception thrown by twig.js.
	     */
	    Twig.Error = function(message, file) {
	       this.message = message;
	       this.name = "TwigException";
	       this.type = "TwigException";
	       this.file = file;
	    };

	    /**
	     * Get the string representation of a Twig error.
	     */
	    Twig.Error.prototype.toString = function() {
	        var output = this.name + ": " + this.message;

	        return output;
	    };

	    /**
	     * Wrapper for logging to the console.
	     */
	    Twig.log = {
	        trace: function() {if (Twig.trace && console) {console.log(Array.prototype.slice.call(arguments));}},
	        debug: function() {if (Twig.debug && console) {console.log(Array.prototype.slice.call(arguments));}}
	    };


	    if (typeof console !== "undefined") {
	        if (typeof console.error !== "undefined") {
	            Twig.log.error = function() {
	                console.error.apply(console, arguments);
	            }
	        } else if (typeof console.log !== "undefined") {
	            Twig.log.error = function() {
	                console.log.apply(console, arguments);
	            }
	        }
	    } else {
	        Twig.log.error = function(){};
	    }

	    /**
	     * Wrapper for child context objects in Twig.
	     *
	     * @param {Object} context Values to initialize the context with.
	     */
	    Twig.ChildContext = function(context) {
	        var ChildContext = function ChildContext() {};
	        ChildContext.prototype = context;
	        return new ChildContext();
	    };

	    /**
	     * Container for methods related to handling high level template tokens
	     *      (for example: {{ expression }}, {% logic %}, {# comment #}, raw data)
	     */
	    Twig.token = {};

	    /**
	     * Token types.
	     */
	    Twig.token.type = {
	        output:                 'output',
	        logic:                  'logic',
	        comment:                'comment',
	        raw:                    'raw',
	        output_whitespace_pre:  'output_whitespace_pre',
	        output_whitespace_post: 'output_whitespace_post',
	        output_whitespace_both: 'output_whitespace_both',
	        logic_whitespace_pre:   'logic_whitespace_pre',
	        logic_whitespace_post:  'logic_whitespace_post',
	        logic_whitespace_both:  'logic_whitespace_both'
	    };

	    /**
	     * Token syntax definitions.
	     */
	    Twig.token.definitions = [
	        {
	            type: Twig.token.type.raw,
	            open: '{% raw %}',
	            close: '{% endraw %}'
	        },
	        {
	            type: Twig.token.type.raw,
	            open: '{% verbatim %}',
	            close: '{% endverbatim %}'
	        },
	        // *Whitespace type tokens*
	        //
	        // These typically take the form `{{- expression -}}` or `{{- expression }}` or `{{ expression -}}`.
	        {
	            type: Twig.token.type.output_whitespace_pre,
	            open: '{{-',
	            close: '}}'
	        },
	        {
	            type: Twig.token.type.output_whitespace_post,
	            open: '{{',
	            close: '-}}'
	        },
	        {
	            type: Twig.token.type.output_whitespace_both,
	            open: '{{-',
	            close: '-}}'
	        },
	        {
	            type: Twig.token.type.logic_whitespace_pre,
	            open: '{%-',
	            close: '%}'
	        },
	        {
	            type: Twig.token.type.logic_whitespace_post,
	            open: '{%',
	            close: '-%}'
	        },
	        {
	            type: Twig.token.type.logic_whitespace_both,
	            open: '{%-',
	            close: '-%}'
	        },
	        // *Output type tokens*
	        //
	        // These typically take the form `{{ expression }}`.
	        {
	            type: Twig.token.type.output,
	            open: '{{',
	            close: '}}'
	        },
	        // *Logic type tokens*
	        //
	        // These typically take a form like `{% if expression %}` or `{% endif %}`
	        {
	            type: Twig.token.type.logic,
	            open: '{%',
	            close: '%}'
	        },
	        // *Comment type tokens*
	        //
	        // These take the form `{# anything #}`
	        {
	            type: Twig.token.type.comment,
	            open: '{#',
	            close: '#}'
	        }
	    ];


	    /**
	     * What characters start "strings" in token definitions. We need this to ignore token close
	     * strings inside an expression.
	     */
	    Twig.token.strings = ['"', "'"];

	    Twig.token.findStart = function (template) {
	        var output = {
	                position: null,
	                close_position: null,
	                def: null
	            },
	            i,
	            token_template,
	            first_key_position,
	            close_key_position;

	        for (i=0;i<Twig.token.definitions.length;i++) {
	            token_template = Twig.token.definitions[i];
	            first_key_position = template.indexOf(token_template.open);
	            close_key_position = template.indexOf(token_template.close);

	            Twig.log.trace("Twig.token.findStart: ", "Searching for ", token_template.open, " found at ", first_key_position);

	            //Special handling for mismatched tokens
	            if (first_key_position >= 0) {
	                //This token matches the template
	                if (token_template.open.length !== token_template.close.length) {
	                    //This token has mismatched closing and opening tags
	                    if (close_key_position < 0) {
	                        //This token's closing tag does not match the template
	                        continue;
	                    }
	                }
	            }
	            // Does this token occur before any other types?
	            if (first_key_position >= 0 && (output.position === null || first_key_position < output.position)) {
	                output.position = first_key_position;
	                output.def = token_template;
	                output.close_position = close_key_position;
	            } else if (first_key_position >= 0 && output.position !== null && first_key_position === output.position) {
	                /*This token exactly matches another token,
	                greedily match to check if this token has a greater specificity*/
	                if (token_template.open.length > output.def.open.length) {
	                    //This token's opening tag is more specific than the previous match
	                    output.position = first_key_position;
	                    output.def = token_template;
	                    output.close_position = close_key_position;
	                } else if (token_template.open.length === output.def.open.length) {
	                    if (token_template.close.length > output.def.close.length) {
	                        //This token's opening tag is as specific as the previous match,
	                        //but the closing tag has greater specificity
	                        if (close_key_position >= 0 && close_key_position < output.close_position) {
	                            //This token's closing tag exists in the template,
	                            //and it occurs sooner than the previous match
	                            output.position = first_key_position;
	                            output.def = token_template;
	                            output.close_position = close_key_position;
	                        }
	                    } else if (close_key_position >= 0 && close_key_position < output.close_position) {
	                        //This token's closing tag is not more specific than the previous match,
	                        //but it occurs sooner than the previous match
	                        output.position = first_key_position;
	                        output.def = token_template;
	                        output.close_position = close_key_position;
	                    }
	                }
	            }
	        }

	        delete output['close_position'];

	        return output;
	    };

	    Twig.token.findEnd = function (template, token_def, start) {
	        var end = null,
	            found = false,
	            offset = 0,

	            // String position variables
	            str_pos = null,
	            str_found = null,
	            pos = null,
	            end_offset = null,
	            this_str_pos = null,
	            end_str_pos = null,

	            // For loop variables
	            i,
	            l;

	        while (!found) {
	            str_pos = null;
	            str_found = null;
	            pos = template.indexOf(token_def.close, offset);

	            if (pos >= 0) {
	                end = pos;
	                found = true;
	            } else {
	                // throw an exception
	                throw new Twig.Error("Unable to find closing bracket '" + token_def.close +
	                                "'" + " opened near template position " + start);
	            }

	            // Ignore quotes within comments; just look for the next comment close sequence,
	            // regardless of what comes before it. https://github.com/justjohn/twig.js/issues/95
	            if (token_def.type === Twig.token.type.comment) {
	              break;
	            }
	            // Ignore quotes within raw tag
	            // Fixes #283
	            if (token_def.type === Twig.token.type.raw) {
	                break;
	            }

	            l = Twig.token.strings.length;
	            for (i = 0; i < l; i += 1) {
	                this_str_pos = template.indexOf(Twig.token.strings[i], offset);

	                if (this_str_pos > 0 && this_str_pos < pos &&
	                        (str_pos === null || this_str_pos < str_pos)) {
	                    str_pos = this_str_pos;
	                    str_found = Twig.token.strings[i];
	                }
	            }

	            // We found a string before the end of the token, now find the string's end and set the search offset to it
	            if (str_pos !== null) {
	                end_offset = str_pos + 1;
	                end = null;
	                found = false;
	                while (true) {
	                    end_str_pos = template.indexOf(str_found, end_offset);
	                    if (end_str_pos < 0) {
	                        throw "Unclosed string in template";
	                    }
	                    // Ignore escaped quotes
	                    if (template.substr(end_str_pos - 1, 1) !== "\\") {
	                        offset = end_str_pos + 1;
	                        break;
	                    } else {
	                        end_offset = end_str_pos + 1;
	                    }
	                }
	            }
	        }
	        return end;
	    };

	    /**
	     * Convert a template into high-level tokens.
	     */
	    Twig.tokenize = function (template) {
	        var tokens = [],
	            // An offset for reporting errors locations in the template.
	            error_offset = 0,

	            // The start and type of the first token found in the template.
	            found_token = null,
	            // The end position of the matched token.
	            end = null;

	        while (template.length > 0) {
	            // Find the first occurance of any token type in the template
	            found_token = Twig.token.findStart(template);

	            Twig.log.trace("Twig.tokenize: ", "Found token: ", found_token);

	            if (found_token.position !== null) {
	                // Add a raw type token for anything before the start of the token
	                if (found_token.position > 0) {
	                    tokens.push({
	                        type: Twig.token.type.raw,
	                        value: template.substring(0, found_token.position)
	                    });
	                }
	                template = template.substr(found_token.position + found_token.def.open.length);
	                error_offset += found_token.position + found_token.def.open.length;

	                // Find the end of the token
	                end = Twig.token.findEnd(template, found_token.def, error_offset);

	                Twig.log.trace("Twig.tokenize: ", "Token ends at ", end);

	                tokens.push({
	                    type:  found_token.def.type,
	                    value: template.substring(0, end).trim()
	                });

	                if (template.substr( end + found_token.def.close.length, 1 ) === "\n") {
	                    switch (found_token.def.type) {
	                        case "logic_whitespace_pre":
	                        case "logic_whitespace_post":
	                        case "logic_whitespace_both":
	                        case "logic":
	                            // Newlines directly after logic tokens are ignored
	                            end += 1;
	                            break;
	                    }
	                }

	                template = template.substr(end + found_token.def.close.length);

	                // Increment the position in the template
	                error_offset += end + found_token.def.close.length;

	            } else {
	                // No more tokens -> add the rest of the template as a raw-type token
	                tokens.push({
	                    type: Twig.token.type.raw,
	                    value: template
	                });
	                template = '';
	            }
	        }

	        return tokens;
	    };


	    Twig.compile = function (tokens) {
	        try {

	            // Output and intermediate stacks
	            var output = [],
	                stack = [],
	                // The tokens between open and close tags
	                intermediate_output = [],

	                token = null,
	                logic_token = null,
	                unclosed_token = null,
	                // Temporary previous token.
	                prev_token = null,
	                // Temporary previous output.
	                prev_output = null,
	                // Temporary previous intermediate output.
	                prev_intermediate_output = null,
	                // The previous token's template
	                prev_template = null,
	                // Token lookahead
	                next_token = null,
	                // The output token
	                tok_output = null,

	                // Logic Token values
	                type = null,
	                open = null,
	                next = null;

	            var compile_output = function(token) {
	                Twig.expression.compile.apply(this, [token]);
	                if (stack.length > 0) {
	                    intermediate_output.push(token);
	                } else {
	                    output.push(token);
	                }
	            };

	            var compile_logic = function(token) {
	                // Compile the logic token
	                logic_token = Twig.logic.compile.apply(this, [token]);

	                type = logic_token.type;
	                open = Twig.logic.handler[type].open;
	                next = Twig.logic.handler[type].next;

	                Twig.log.trace("Twig.compile: ", "Compiled logic token to ", logic_token,
	                                                 " next is: ", next, " open is : ", open);

	                // Not a standalone token, check logic stack to see if this is expected
	                if (open !== undefined && !open) {
	                    prev_token = stack.pop();
	                    prev_template = Twig.logic.handler[prev_token.type];

	                    if (Twig.indexOf(prev_template.next, type) < 0) {
	                        throw new Error(type + " not expected after a " + prev_token.type);
	                    }

	                    prev_token.output = prev_token.output || [];

	                    prev_token.output = prev_token.output.concat(intermediate_output);
	                    intermediate_output = [];

	                    tok_output = {
	                        type: Twig.token.type.logic,
	                        token: prev_token
	                    };
	                    if (stack.length > 0) {
	                        intermediate_output.push(tok_output);
	                    } else {
	                        output.push(tok_output);
	                    }
	                }

	                // This token requires additional tokens to complete the logic structure.
	                if (next !== undefined && next.length > 0) {
	                    Twig.log.trace("Twig.compile: ", "Pushing ", logic_token, " to logic stack.");

	                    if (stack.length > 0) {
	                        // Put any currently held output into the output list of the logic operator
	                        // currently at the head of the stack before we push a new one on.
	                        prev_token = stack.pop();
	                        prev_token.output = prev_token.output || [];
	                        prev_token.output = prev_token.output.concat(intermediate_output);
	                        stack.push(prev_token);
	                        intermediate_output = [];
	                    }

	                    // Push the new logic token onto the logic stack
	                    stack.push(logic_token);

	                } else if (open !== undefined && open) {
	                    tok_output = {
	                        type: Twig.token.type.logic,
	                        token: logic_token
	                    };
	                    // Standalone token (like {% set ... %}
	                    if (stack.length > 0) {
	                        intermediate_output.push(tok_output);
	                    } else {
	                        output.push(tok_output);
	                    }
	                }
	            };

	            while (tokens.length > 0) {
	                token = tokens.shift();
	                prev_output = output[output.length - 1];
	                prev_intermediate_output = intermediate_output[intermediate_output.length - 1];
	                next_token = tokens[0];
	                Twig.log.trace("Compiling token ", token);
	                switch (token.type) {
	                    case Twig.token.type.raw:
	                        if (stack.length > 0) {
	                            intermediate_output.push(token);
	                        } else {
	                            output.push(token);
	                        }
	                        break;

	                    case Twig.token.type.logic:
	                        compile_logic.call(this, token);
	                        break;

	                    // Do nothing, comments should be ignored
	                    case Twig.token.type.comment:
	                        break;

	                    case Twig.token.type.output:
	                        compile_output.call(this, token);
	                        break;

	                    //Kill whitespace ahead and behind this token
	                    case Twig.token.type.logic_whitespace_pre:
	                    case Twig.token.type.logic_whitespace_post:
	                    case Twig.token.type.logic_whitespace_both:
	                    case Twig.token.type.output_whitespace_pre:
	                    case Twig.token.type.output_whitespace_post:
	                    case Twig.token.type.output_whitespace_both:
	                        if (token.type !== Twig.token.type.output_whitespace_post && token.type !== Twig.token.type.logic_whitespace_post) {
	                            if (prev_output) {
	                                //If the previous output is raw, pop it off
	                                if (prev_output.type === Twig.token.type.raw) {
	                                    output.pop();

	                                    //If the previous output is not just whitespace, trim it
	                                    if (prev_output.value.match(/^\s*$/) === null) {
	                                        prev_output.value = prev_output.value.trim();
	                                        //Repush the previous output
	                                        output.push(prev_output);
	                                    }
	                                }
	                            }

	                            if (prev_intermediate_output) {
	                                //If the previous intermediate output is raw, pop it off
	                                if (prev_intermediate_output.type === Twig.token.type.raw) {
	                                    intermediate_output.pop();

	                                    //If the previous output is not just whitespace, trim it
	                                    if (prev_intermediate_output.value.match(/^\s*$/) === null) {
	                                        prev_intermediate_output.value = prev_intermediate_output.value.trim();
	                                        //Repush the previous intermediate output
	                                        intermediate_output.push(prev_intermediate_output);
	                                    }
	                                }
	                            }
	                        }

	                        //Compile this token
	                        switch (token.type) {
	                            case Twig.token.type.output_whitespace_pre:
	                            case Twig.token.type.output_whitespace_post:
	                            case Twig.token.type.output_whitespace_both:
	                                compile_output.call(this, token);
	                                break;
	                            case Twig.token.type.logic_whitespace_pre:
	                            case Twig.token.type.logic_whitespace_post:
	                            case Twig.token.type.logic_whitespace_both:
	                                compile_logic.call(this, token);
	                                break;
	                        }

	                        if (token.type !== Twig.token.type.output_whitespace_pre && token.type !== Twig.token.type.logic_whitespace_pre) {
	                            if (next_token) {
	                                //If the next token is raw, shift it out
	                                if (next_token.type === Twig.token.type.raw) {
	                                    tokens.shift();

	                                    //If the next token is not just whitespace, trim it
	                                    if (next_token.value.match(/^\s*$/) === null) {
	                                        next_token.value = next_token.value.trim();
	                                        //Unshift the next token
	                                        tokens.unshift(next_token);
	                                    }
	                                }
	                            }
	                        }

	                        break;
	                }

	                Twig.log.trace("Twig.compile: ", " Output: ", output,
	                                                 " Logic Stack: ", stack,
	                                                 " Pending Output: ", intermediate_output );
	            }

	            // Verify that there are no logic tokens left in the stack.
	            if (stack.length > 0) {
	                unclosed_token = stack.pop();
	                throw new Error("Unable to find an end tag for " + unclosed_token.type +
	                                ", expecting one of " + unclosed_token.next);
	            }
	            return output;
	        } catch (ex) {
	            if (this.options.rethrow) {
	                if (ex.type == 'TwigException' && !ex.file) {
	                    ex.file = this.id;
	                }

	                throw ex
	            }
	            else {
	                Twig.log.error("Error compiling twig template " + this.id + ": ");
	                if (ex.stack) {
	                    Twig.log.error(ex.stack);
	                } else {
	                    Twig.log.error(ex.toString());
	                }
	            }
	        }
	    };

	    /**
	     * Parse a compiled template.
	     *
	     * @param {Array} tokens The compiled tokens.
	     * @param {Object} context The render context.
	     *
	     * @return {string} The parsed template.
	     */
	    Twig.parse = function (tokens, context, allow_async) {
	        var that = this,
	            output = [],

	            // Store any error that might be thrown by the promise chain.
	            err = null,

	            // This will be set to is_async if template renders synchronously
	            is_async = true,
	            promise = null,

	            // Track logic chains
	            chain = true;


	        function handleException(ex) {
	            if (that.options.rethrow) {
	                if (typeof ex === 'string') {
	                    ex = new Twig.Error(ex)
	                }

	                if (ex.type == 'TwigException' && !ex.file) {
	                    ex.file = that.id;
	                }

	                throw ex;
	            }
	            else {
	                Twig.log.error("Error parsing twig template " + that.id + ": ");
	                if (ex.stack) {
	                    Twig.log.error(ex.stack);
	                } else {
	                    Twig.log.error(ex.toString());
	                }

	                if (Twig.debug) {
	                    return ex.toString();
	                }
	            }
	        }

	        promise = Twig.async.forEach(tokens, function parseToken(token) {
	            Twig.log.debug("Twig.parse: ", "Parsing token: ", token);

	            switch (token.type) {
	                case Twig.token.type.raw:
	                    output.push(Twig.filters.raw(token.value));
	                    break;

	                case Twig.token.type.logic:
	                    var logic_token = token.token;

	                    return Twig.logic.parseAsync.apply(that, [logic_token, context, chain])
	                    .then(function(logic) {
	                        if (logic.chain !== undefined) {
	                            chain = logic.chain;
	                        }
	                        if (logic.context !== undefined) {
	                            context = logic.context;
	                        }
	                        if (logic.output !== undefined) {
	                            output.push(logic.output);
	                        }
	                    });
	                    break;

	                case Twig.token.type.comment:
	                    // Do nothing, comments should be ignored
	                    break;

	                //Fall through whitespace to output
	                case Twig.token.type.output_whitespace_pre:
	                case Twig.token.type.output_whitespace_post:
	                case Twig.token.type.output_whitespace_both:
	                case Twig.token.type.output:
	                    Twig.log.debug("Twig.parse: ", "Output token: ", token.stack);
	                    // Parse the given expression in the given context
	                    return Twig.expression.parseAsync.apply(that, [token.stack, context])
	                    .then(function(o) {
	                        output.push(o);
	                    });
	            }
	        })
	        .then(function() {
	            output = Twig.output.apply(that, [output]);
	            is_async = false;
	            return output;
	        })
	        .catch(function(e) {
	            if (allow_async)
	                handleException(e);

	            err = e;
	        });

	        // If `allow_async` we will always return a promise since we do not
	        // know in advance if we are going to run asynchronously or not.
	        if (allow_async)
	            return promise;

	        // Handle errors here if we fail synchronously.
	        if (err !== null)
	            return handleException(err);

	        // If `allow_async` is not true we should not allow the user
	        // to use asynchronous functions or filters.
	        if (is_async)
	            throw new Twig.Error('You are using Twig.js in sync mode in combination with async extensions.');

	        return output;
	    };

	    /**
	     * Tokenize and compile a string template.
	     *
	     * @param {string} data The template.
	     *
	     * @return {Array} The compiled tokens.
	     */
	    Twig.prepare = function(data) {
	        var tokens, raw_tokens;

	        // Tokenize
	        Twig.log.debug("Twig.prepare: ", "Tokenizing ", data);
	        raw_tokens = Twig.tokenize.apply(this, [data]);

	        // Compile
	        Twig.log.debug("Twig.prepare: ", "Compiling ", raw_tokens);
	        tokens = Twig.compile.apply(this, [raw_tokens]);

	        Twig.log.debug("Twig.prepare: ", "Compiled ", tokens);

	        return tokens;
	    };

	    /**
	     * Join the output token's stack and escape it if needed
	     *
	     * @param {Array} Output token's stack
	     *
	     * @return {string|String} Autoescaped output
	     */
	    Twig.output = function(output) {
	        if (!this.options.autoescape) {
	            return output.join("");
	        }

	        var strategy = 'html';
	        if(typeof this.options.autoescape == 'string')
	            strategy = this.options.autoescape;

	        // [].map would be better but it's not supported by IE8-
	        var escaped_output = [];
	        Twig.forEach(output, function (str) {
	            if (str && (str.twig_markup !== true && str.twig_markup != strategy)) {
	                str = Twig.filters.escape(str, [ strategy ]);
	            }
	            escaped_output.push(str);
	        });
	        return Twig.Markup(escaped_output.join(""));
	    }

	    // Namespace for template storage and retrieval
	    Twig.Templates = {
	        /**
	         * Registered template loaders - use Twig.Templates.registerLoader to add supported loaders
	         * @type {Object}
	         */
	        loaders: {},

	        /**
	         * Registered template parsers - use Twig.Templates.registerParser to add supported parsers
	         * @type {Object}
	         */
	        parsers: {},

	        /**
	         * Cached / loaded templates
	         * @type {Object}
	         */
	        registry: {}
	    };

	    /**
	     * Is this id valid for a twig template?
	     *
	     * @param {string} id The ID to check.
	     *
	     * @throws {Twig.Error} If the ID is invalid or used.
	     * @return {boolean} True if the ID is valid.
	     */
	    Twig.validateId = function(id) {
	        if (id === "prototype") {
	            throw new Twig.Error(id + " is not a valid twig identifier");
	        } else if (Twig.cache && Twig.Templates.registry.hasOwnProperty(id)) {
	            throw new Twig.Error("There is already a template with the ID " + id);
	        }
	        return true;
	    }

	    /**
	     * Register a template loader
	     *
	     * @example
	     * Twig.extend(function(Twig) {
	     *    Twig.Templates.registerLoader('custom_loader', function(location, params, callback, error_callback) {
	     *        // ... load the template ...
	     *        params.data = loadedTemplateData;
	     *        // create and return the template
	     *        var template = new Twig.Template(params);
	     *        if (typeof callback === 'function') {
	     *            callback(template);
	     *        }
	     *        return template;
	     *    });
	     * });
	     *
	     * @param {String} method_name The method this loader is intended for (ajax, fs)
	     * @param {Function} func The function to execute when loading the template
	     * @param {Object|undefined} scope Optional scope parameter to bind func to
	     *
	     * @throws Twig.Error
	     *
	     * @return {void}
	     */
	    Twig.Templates.registerLoader = function(method_name, func, scope) {
	        if (typeof func !== 'function') {
	            throw new Twig.Error('Unable to add loader for ' + method_name + ': Invalid function reference given.');
	        }
	        if (scope) {
	            func = func.bind(scope);
	        }
	        this.loaders[method_name] = func;
	    };

	    /**
	     * Remove a registered loader
	     *
	     * @param {String} method_name The method name for the loader you wish to remove
	     *
	     * @return {void}
	     */
	    Twig.Templates.unRegisterLoader = function(method_name) {
	        if (this.isRegisteredLoader(method_name)) {
	            delete this.loaders[method_name];
	        }
	    };

	    /**
	     * See if a loader is registered by its method name
	     *
	     * @param {String} method_name The name of the loader you are looking for
	     *
	     * @return {boolean}
	     */
	    Twig.Templates.isRegisteredLoader = function(method_name) {
	        return this.loaders.hasOwnProperty(method_name);
	    };

	    /**
	     * Register a template parser
	     *
	     * @example
	     * Twig.extend(function(Twig) {
	     *    Twig.Templates.registerParser('custom_parser', function(params) {
	     *        // this template source can be accessed in params.data
	     *        var template = params.data
	     *
	     *        // ... custom process that modifies the template
	     *
	     *        // return the parsed template
	     *        return template;
	     *    });
	     * });
	     *
	     * @param {String} method_name The method this parser is intended for (twig, source)
	     * @param {Function} func The function to execute when parsing the template
	     * @param {Object|undefined} scope Optional scope parameter to bind func to
	     *
	     * @throws Twig.Error
	     *
	     * @return {void}
	     */
	    Twig.Templates.registerParser = function(method_name, func, scope) {
	        if (typeof func !== 'function') {
	            throw new Twig.Error('Unable to add parser for ' + method_name + ': Invalid function regerence given.');
	        }

	        if (scope) {
	            func = func.bind(scope);
	        }

	        this.parsers[method_name] = func;
	    };

	    /**
	     * Remove a registered parser
	     *
	     * @param {String} method_name The method name for the parser you wish to remove
	     *
	     * @return {void}
	     */
	    Twig.Templates.unRegisterParser = function(method_name) {
	        if (this.isRegisteredParser(method_name)) {
	            delete this.parsers[method_name];
	        }
	    };

	    /**
	     * See if a parser is registered by its method name
	     *
	     * @param {String} method_name The name of the parser you are looking for
	     *
	     * @return {boolean}
	     */
	    Twig.Templates.isRegisteredParser = function(method_name) {
	        return this.parsers.hasOwnProperty(method_name);
	    };

	    /**
	     * Save a template object to the store.
	     *
	     * @param {Twig.Template} template   The twig.js template to store.
	     */
	    Twig.Templates.save = function(template) {
	        if (template.id === undefined) {
	            throw new Twig.Error("Unable to save template with no id");
	        }
	        Twig.Templates.registry[template.id] = template;
	    };

	    /**
	     * Load a previously saved template from the store.
	     *
	     * @param {string} id   The ID of the template to load.
	     *
	     * @return {Twig.Template} A twig.js template stored with the provided ID.
	     */
	    Twig.Templates.load = function(id) {
	        if (!Twig.Templates.registry.hasOwnProperty(id)) {
	            return null;
	        }
	        return Twig.Templates.registry[id];
	    };

	    /**
	     * Load a template from a remote location using AJAX and saves in with the given ID.
	     *
	     * Available parameters:
	     *
	     *      async:       Should the HTTP request be performed asynchronously.
	     *                      Defaults to true.
	     *      method:      What method should be used to load the template
	     *                      (fs or ajax)
	     *      parser:      What method should be used to parse the template
	     *                      (twig or source)
	     *      precompiled: Has the template already been compiled.
	     *
	     * @param {string} location  The remote URL to load as a template.
	     * @param {Object} params The template parameters.
	     * @param {function} callback  A callback triggered when the template finishes loading.
	     * @param {function} error_callback  A callback triggered if an error occurs loading the template.
	     *
	     *
	     */
	    Twig.Templates.loadRemote = function(location, params, callback, error_callback) {
	        var loader;

	        // Default to async
	        if (params.async === undefined) {
	            params.async = true;
	        }

	        // Default to the URL so the template is cached.
	        if (params.id === undefined) {
	            params.id = location;
	        }

	        // Check for existing template
	        if (Twig.cache && Twig.Templates.registry.hasOwnProperty(params.id)) {
	            // A template is already saved with the given id.
	            if (typeof callback === 'function') {
	                callback(Twig.Templates.registry[params.id]);
	            }
	            // TODO: if async, return deferred promise
	            return Twig.Templates.registry[params.id];
	        }

	        //if the parser name hasn't been set, default it to twig
	        params.parser = params.parser || 'twig';

	        // Assume 'fs' if the loader is not defined
	        loader = this.loaders[params.method] || this.loaders.fs;
	        return loader.apply(this, arguments);
	    };

	    // Determine object type
	    function is(type, obj) {
	        var clas = Object.prototype.toString.call(obj).slice(8, -1);
	        return obj !== undefined && obj !== null && clas === type;
	    }

	    /**
	     * Create a new twig.js template.
	     *
	     * Parameters: {
	     *      data:   The template, either pre-compiled tokens or a string template
	     *      id:     The name of this template
	     *      blocks: Any pre-existing block from a child template
	     * }
	     *
	     * @param {Object} params The template parameters.
	     */
	    Twig.Template = function ( params ) {
	        var data = params.data,
	            id = params.id,
	            blocks = params.blocks,
	            macros = params.macros || {},
	            base = params.base,
	            path = params.path,
	            url = params.url,
	            name = params.name,
	            method = params.method,
	            // parser options
	            options = params.options;

	        // # What is stored in a Twig.Template
	        //
	        // The Twig Template hold several chucks of data.
	        //
	        //     {
	        //          id:     The token ID (if any)
	        //          tokens: The list of tokens that makes up this template.
	        //          blocks: The list of block this template contains.
	        //          base:   The base template (if any)
	        //            options:  {
	        //                Compiler/parser options
	        //
	        //                strict_variables: true/false
	        //                    Should missing variable/keys emit an error message. If false, they default to null.
	        //            }
	        //     }
	        //

	        this.id     = id;
	        this.method = method;
	        this.base   = base;
	        this.path   = path;
	        this.url    = url;
	        this.name   = name;
	        this.macros = macros;
	        this.options = options;

	        this.reset(blocks);

	        if (is('String', data)) {
	            this.tokens = Twig.prepare.apply(this, [data]);
	        } else {
	            this.tokens = data;
	        }

	        if (id !== undefined) {
	            Twig.Templates.save(this);
	        }
	    };

	    Twig.Template.prototype.reset = function(blocks) {
	        Twig.log.debug("Twig.Template.reset", "Reseting template " + this.id);
	        this.blocks = {};
	        this.importedBlocks = [];
	        this.originalBlockTokens = {};
	        this.child = {
	            blocks: blocks || {}
	        };
	        this.extend = null;
	    };

	    Twig.Template.prototype.render = function (context, params, allow_async) {
	        params = params || {};

	        var that = this,

	            // Store any error that might be thrown by the promise chain.
	            err = null,

	            // This will be set to is_async if template renders synchronously
	            is_async = true,
	            promise = null,

	            result,
	            url;

	        this.context = context || {};

	        // Clear any previous state
	        this.reset();
	        if (params.blocks) {
	            this.blocks = params.blocks;
	        }
	        if (params.macros) {
	            this.macros = params.macros;
	        }

	        var cb = function(output) {
	            // Does this template extend another
	            if (that.extend) {
	                var ext_template;

	                // check if the template is provided inline
	                if ( that.options.allowInlineIncludes ) {
	                    ext_template = Twig.Templates.load(that.extend);
	                    if ( ext_template ) {
	                        ext_template.options = that.options;
	                    }
	                }

	                // check for the template file via include
	                if (!ext_template) {
	                    url = Twig.path.parsePath(that, that.extend);

	                    ext_template = Twig.Templates.loadRemote(url, {
	                        method: that.getLoaderMethod(),
	                        base: that.base,
	                        async:  false,
	                        id:     url,
	                        options: that.options
	                    });
	                }

	                that.parent = ext_template;

	                return that.parent.renderAsync(that.context, {
	                    blocks: that.blocks
	                });
	            }

	            if (params.output == 'blocks') {
	                return that.blocks;
	            } else if (params.output == 'macros') {
	                return that.macros;
	            } else {
	                return output;
	            }
	        };

	        promise = Twig.parseAsync.apply(this, [this.tokens, this.context])
	        .then(cb)
	        .then(function(v) {
	            is_async = false;
	            result = v;
	            return v;
	        })
	        .catch(function(e) {
	            if (allow_async)
	                throw e;

	            err = e;
	        })

	        // If `allow_async` we will always return a promise since we do not
	        // know in advance if we are going to run asynchronously or not.
	        if (allow_async)
	            return promise;

	        // Handle errors here if we fail synchronously.
	        if (err !== null)
	            throw err;

	        // If `allow_async` is not true we should not allow the user
	        // to use asynchronous functions or filters.
	        if (is_async)
	            throw new Twig.Error('You are using Twig.js in sync mode in combination with async extensions.');

	        return result;
	    };

	    Twig.Template.prototype.importFile = function(file) {
	        var url, sub_template;
	        if (!this.url && this.options.allowInlineIncludes) {
	            file = this.path ? Twig.path.parsePath(this, file) : file;
	            sub_template = Twig.Templates.load(file);

	            if (!sub_template) {
	                sub_template = Twig.Templates.loadRemote(url, {
	                    id: file,
	                    method: this.getLoaderMethod(),
	                    async: false,
	                    path: file,
	                    options: this.options
	                });

	                if (!sub_template) {
	                    throw new Twig.Error("Unable to find the template " + file);
	                }
	            }

	            sub_template.options = this.options;

	            return sub_template;
	        }

	        url = Twig.path.parsePath(this, file);

	        // Load blocks from an external file
	        sub_template = Twig.Templates.loadRemote(url, {
	            method: this.getLoaderMethod(),
	            base: this.base,
	            async: false,
	            options: this.options,
	            id: url
	        });

	        return sub_template;
	    };

	    Twig.Template.prototype.importBlocks = function(file, override) {
	        var sub_template = this.importFile(file),
	            context = this.context,
	            that = this,
	            key;

	        override = override || false;

	        sub_template.render(context);

	        // Mixin blocks
	        Twig.forEach(Object.keys(sub_template.blocks), function(key) {
	            if (override || that.blocks[key] === undefined) {
	                that.blocks[key] = sub_template.blocks[key];
	                that.importedBlocks.push(key);
	            }
	        });
	    };

	    Twig.Template.prototype.importMacros = function(file) {
	        var url = Twig.path.parsePath(this, file);

	        // load remote template
	        var remoteTemplate = Twig.Templates.loadRemote(url, {
	            method: this.getLoaderMethod(),
	            async: false,
	            id: url
	        });

	        return remoteTemplate;
	    };

	    Twig.Template.prototype.getLoaderMethod = function() {
	        if (this.path) {
	            return 'fs';
	        }
	        if (this.url) {
	            return 'ajax';
	        }
	        return this.method || 'fs';
	    };

	    Twig.Template.prototype.compile = function(options) {
	        // compile the template into raw JS
	        return Twig.compiler.compile(this, options);
	    };

	    /**
	     * Create safe output
	     *
	     * @param {string} Content safe to output
	     *
	     * @return {String} Content wrapped into a String
	     */

	    Twig.Markup = function(content, strategy) {
	        if(typeof strategy == 'undefined') {
	            strategy = true;
	        }

	        if (typeof content === 'string' && content.length > 0) {
	            content = new String(content);
	            content.twig_markup = strategy;
	        }
	        return content;
	    };

	    return Twig;

	};


/***/ },
/* 2 */
/***/ function(module, exports) {

	// ## twig.compiler.js
	//
	// This file handles compiling templates into JS
	module.exports = function (Twig) {
	    /**
	     * Namespace for compilation.
	     */
	    Twig.compiler = {
	        module: {}
	    };

	    // Compile a Twig Template to output.
	    Twig.compiler.compile = function(template, options) {
	        // Get tokens
	        var tokens = JSON.stringify(template.tokens)
	            , id = template.id
	            , output;

	        if (options.module) {
	            if (Twig.compiler.module[options.module] === undefined) {
	                throw new Twig.Error("Unable to find module type " + options.module);
	            }
	            output = Twig.compiler.module[options.module](id, tokens, options.twig);
	        } else {
	            output = Twig.compiler.wrap(id, tokens);
	        }
	        return output;
	    };

	    Twig.compiler.module = {
	        amd: function(id, tokens, pathToTwig) {
	            return 'define(["' + pathToTwig + '"], function (Twig) {\n\tvar twig, templates;\ntwig = Twig.twig;\ntemplates = ' + Twig.compiler.wrap(id, tokens) + '\n\treturn templates;\n});';
	        }
	        , node: function(id, tokens) {
	            return 'var twig = require("twig").twig;\n'
	                + 'exports.template = ' + Twig.compiler.wrap(id, tokens)
	        }
	        , cjs2: function(id, tokens, pathToTwig) {
	            return 'module.declare([{ twig: "' + pathToTwig + '" }], function (require, exports, module) {\n'
	                        + '\tvar twig = require("twig").twig;\n'
	                        + '\texports.template = ' + Twig.compiler.wrap(id, tokens)
	                    + '\n});'
	        }
	    };

	    Twig.compiler.wrap = function(id, tokens) {
	        return 'twig({id:"'+id.replace('"', '\\"')+'", data:'+tokens+', precompiled: true});\n';
	    };

	    return Twig;
	};


/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	// ## twig.expression.js
	//
	// This file handles tokenizing, compiling and parsing expressions.
	module.exports = function (Twig) {
	    "use strict";

	    function parseParams(thisArg, params, context) {
	        if (params)
	            return Twig.expression.parseAsync.apply(thisArg, [params, context]);

	        return Twig.Promise.resolve(false);
	    }

	    /**
	     * Namespace for expression handling.
	     */
	    Twig.expression = { };

	    __webpack_require__(4)(Twig);

	    /**
	     * Reserved word that can't be used as variable names.
	     */
	    Twig.expression.reservedWords = [
	        "true", "false", "null", "TRUE", "FALSE", "NULL", "_context", "and", "b-and", "or", "b-or", "b-xor", "in", "not in", "if"
	    ];

	    /**
	     * The type of tokens used in expressions.
	     */
	    Twig.expression.type = {
	        comma:      'Twig.expression.type.comma',
	        operator: {
	            unary:  'Twig.expression.type.operator.unary',
	            binary: 'Twig.expression.type.operator.binary'
	        },
	        string:     'Twig.expression.type.string',
	        bool:       'Twig.expression.type.bool',
	        slice:      'Twig.expression.type.slice',
	        array: {
	            start:  'Twig.expression.type.array.start',
	            end:    'Twig.expression.type.array.end'
	        },
	        object: {
	            start:  'Twig.expression.type.object.start',
	            end:    'Twig.expression.type.object.end'
	        },
	        parameter: {
	            start:  'Twig.expression.type.parameter.start',
	            end:    'Twig.expression.type.parameter.end'
	        },
	        subexpression: {
	            start:  'Twig.expression.type.subexpression.start',
	            end:    'Twig.expression.type.subexpression.end'
	        },
	        key: {
	            period:   'Twig.expression.type.key.period',
	            brackets: 'Twig.expression.type.key.brackets'
	        },
	        filter:     'Twig.expression.type.filter',
	        _function:  'Twig.expression.type._function',
	        variable:   'Twig.expression.type.variable',
	        number:     'Twig.expression.type.number',
	        _null:     'Twig.expression.type.null',
	        context:    'Twig.expression.type.context',
	        test:       'Twig.expression.type.test'
	    };

	    Twig.expression.set = {
	        // What can follow an expression (in general)
	        operations: [
	            Twig.expression.type.filter,
	            Twig.expression.type.operator.unary,
	            Twig.expression.type.operator.binary,
	            Twig.expression.type.array.end,
	            Twig.expression.type.object.end,
	            Twig.expression.type.parameter.end,
	            Twig.expression.type.subexpression.end,
	            Twig.expression.type.comma,
	            Twig.expression.type.test
	        ],
	        expressions: [
	            Twig.expression.type._function,
	            Twig.expression.type.bool,
	            Twig.expression.type.string,
	            Twig.expression.type.variable,
	            Twig.expression.type.number,
	            Twig.expression.type._null,
	            Twig.expression.type.context,
	            Twig.expression.type.parameter.start,
	            Twig.expression.type.array.start,
	            Twig.expression.type.object.start,
	            Twig.expression.type.subexpression.start,
	            Twig.expression.type.operator.unary
	        ]
	    };

	    // Most expressions allow a '.' or '[' after them, so we provide a convenience set
	    Twig.expression.set.operations_extended = Twig.expression.set.operations.concat([
	                    Twig.expression.type.key.period,
	                    Twig.expression.type.key.brackets,
	                    Twig.expression.type.slice]);

	    // Some commonly used compile and parse functions.
	    Twig.expression.fn = {
	        compile: {
	            push: function(token, stack, output) {
	                output.push(token);
	            },
	            push_both: function(token, stack, output) {
	                output.push(token);
	                stack.push(token);
	            }
	        },
	        parse: {
	            push: function(token, stack, context) {
	                stack.push(token);
	            },
	            push_value: function(token, stack, context) {
	                stack.push(token.value);
	            }
	        }
	    };

	    // The regular expressions and compile/parse logic used to match tokens in expressions.
	    //
	    // Properties:
	    //
	    //      type:  The type of expression this matches
	    //
	    //      regex: One or more regular expressions that matche the format of the token.
	    //
	    //      next:  Valid tokens that can occur next in the expression.
	    //
	    // Functions:
	    //
	    //      compile: A function that compiles the raw regular expression match into a token.
	    //
	    //      parse:   A function that parses the compiled token into output.
	    //
	    Twig.expression.definitions = [
	        {
	            type: Twig.expression.type.test,
	            regex: /^is\s+(not)?\s*([a-zA-Z_][a-zA-Z0-9_]*(\s?as)?)/,
	            next: Twig.expression.set.operations.concat([Twig.expression.type.parameter.start]),
	            compile: function(token, stack, output) {
	                token.filter   = token.match[2];
	                token.modifier = token.match[1];
	                delete token.match;
	                delete token.value;
	                output.push(token);
	            },
	            parse: function(token, stack, context) {
	                var value = stack.pop();

	                return parseParams(this, token.params, context)
	                .then(function(params) {
	                    var result = Twig.test(token.filter, value, params);

	                    if (token.modifier == 'not') {
	                        stack.push(!result);
	                    } else {
	                        stack.push(result);
	                    }
	                });
	            }
	        },
	        {
	            type: Twig.expression.type.comma,
	            // Match a comma
	            regex: /^,/,
	            next: Twig.expression.set.expressions.concat([Twig.expression.type.array.end, Twig.expression.type.object.end]),
	            compile: function(token, stack, output) {
	                var i = stack.length - 1,
	                    stack_token;

	                delete token.match;
	                delete token.value;

	                // pop tokens off the stack until the start of the object
	                for(;i >= 0; i--) {
	                    stack_token = stack.pop();
	                    if (stack_token.type === Twig.expression.type.object.start
	                            || stack_token.type === Twig.expression.type.parameter.start
	                            || stack_token.type === Twig.expression.type.array.start) {
	                        stack.push(stack_token);
	                        break;
	                    }
	                    output.push(stack_token);
	                }
	                output.push(token);
	            }
	        },
	        {
	            /**
	             * Match a number (integer or decimal)
	             */
	            type: Twig.expression.type.number,
	            // match a number
	            regex: /^\-?\d+(\.\d+)?/,
	            next: Twig.expression.set.operations,
	            compile: function(token, stack, output) {
	                token.value = Number(token.value);
	                output.push(token);
	            },
	            parse: Twig.expression.fn.parse.push_value
	        },
	        {
	            type: Twig.expression.type.operator.binary,
	            // Match any of ?:, +, *, /, -, %, ~, <, <=, >, >=, !=, ==, **, ?, :, and, b-and, or, b-or, b-xor, in, not in
	            // and, or, in, not in can be followed by a space or parenthesis
	            regex: /(^\?\:|^(b\-and)|^(b\-or)|^(b\-xor)|^[\+\-~%\?]|^[\:](?!\d\])|^[!=]==?|^[!<>]=?|^\*\*?|^\/\/?|^(and)[\(|\s+]|^(or)[\(|\s+]|^(in)[\(|\s+]|^(not in)[\(|\s+]|^\.\.)/,
	            next: Twig.expression.set.expressions,
	            transform: function(match, tokens) {
	                switch(match[0]) {
	                    case 'and(':
	                    case 'or(':
	                    case 'in(':
	                    case 'not in(':
	                        //Strip off the ( if it exists
	                        tokens[tokens.length - 1].value = match[2];
	                        return match[0];
	                        break;
	                    default:
	                        return '';
	                }
	            },
	            compile: function(token, stack, output) {
	                delete token.match;

	                token.value = token.value.trim();
	                var value = token.value,
	                    operator = Twig.expression.operator.lookup(value, token);

	                Twig.log.trace("Twig.expression.compile: ", "Operator: ", operator, " from ", value);

	                while (stack.length > 0 &&
	                       (stack[stack.length-1].type == Twig.expression.type.operator.unary || stack[stack.length-1].type == Twig.expression.type.operator.binary) &&
	                            (
	                                (operator.associativity === Twig.expression.operator.leftToRight &&
	                                 operator.precidence    >= stack[stack.length-1].precidence) ||

	                                (operator.associativity === Twig.expression.operator.rightToLeft &&
	                                 operator.precidence    >  stack[stack.length-1].precidence)
	                            )
	                       ) {
	                     var temp = stack.pop();
	                     output.push(temp);
	                }

	                if (value === ":") {
	                    // Check if this is a ternary or object key being set
	                    if (stack[stack.length - 1] && stack[stack.length-1].value === "?") {
	                        // Continue as normal for a ternary
	                    } else {
	                        // This is not a ternary so we push the token to the output where it can be handled
	                        //   when the assocated object is closed.
	                        var key_token = output.pop();

	                        if (key_token.type === Twig.expression.type.string ||
	                                key_token.type === Twig.expression.type.variable) {
	                            token.key = key_token.value;
	                        } else if (key_token.type === Twig.expression.type.number) {
	                            // Convert integer keys into string keys
	                            token.key = key_token.value.toString();
	                        } else if (key_token.expression &&
	                            (key_token.type === Twig.expression.type.parameter.end ||
	                            key_token.type == Twig.expression.type.subexpression.end)) {
	                            token.params = key_token.params;
	                        } else {
	                            throw new Twig.Error("Unexpected value before ':' of " + key_token.type + " = " + key_token.value);
	                        }

	                        output.push(token);
	                        return;
	                    }
	                } else {
	                    stack.push(operator);
	                }
	            },
	            parse: function(token, stack, context) {
	                if (token.key) {
	                    // handle ternary ':' operator
	                    stack.push(token);
	                } else if (token.params) {
	                    // handle "{(expression):value}"
	                    return Twig.expression.parseAsync.apply(this, [token.params, context])
	                    .then(function(key) {
	                        token.key = key;
	                        stack.push(token);

	                        //If we're in a loop, we might need token.params later, especially in this form of "(expression):value"
	                        if (!context.loop) {
	                            delete(token.params);
	                        }
	                    });
	                } else {
	                    Twig.expression.operator.parse(token.value, stack);
	                }
	            }
	        },
	        {
	            type: Twig.expression.type.operator.unary,
	            // Match any of not
	            regex: /(^not\s+)/,
	            next: Twig.expression.set.expressions,
	            compile: function(token, stack, output) {
	                delete token.match;

	                token.value = token.value.trim();
	                var value = token.value,
	                    operator = Twig.expression.operator.lookup(value, token);

	                Twig.log.trace("Twig.expression.compile: ", "Operator: ", operator, " from ", value);

	                while (stack.length > 0 &&
	                       (stack[stack.length-1].type == Twig.expression.type.operator.unary || stack[stack.length-1].type == Twig.expression.type.operator.binary) &&
	                            (
	                                (operator.associativity === Twig.expression.operator.leftToRight &&
	                                 operator.precidence    >= stack[stack.length-1].precidence) ||

	                                (operator.associativity === Twig.expression.operator.rightToLeft &&
	                                 operator.precidence    >  stack[stack.length-1].precidence)
	                            )
	                       ) {
	                     var temp = stack.pop();
	                     output.push(temp);
	                }

	                stack.push(operator);
	            },
	            parse: function(token, stack, context) {
	                Twig.expression.operator.parse(token.value, stack);
	            }
	        },
	        {
	            /**
	             * Match a string. This is anything between a pair of single or double quotes.
	             */
	            type: Twig.expression.type.string,
	            // See: http://blog.stevenlevithan.com/archives/match-quoted-string
	            regex: /^(["'])(?:(?=(\\?))\2[\s\S])*?\1/,
	            next: Twig.expression.set.operations_extended,
	            compile: function(token, stack, output) {
	                var value = token.value;
	                delete token.match

	                // Remove the quotes from the string
	                if (value.substring(0, 1) === '"') {
	                    value = value.replace('\\"', '"');
	                } else {
	                    value = value.replace("\\'", "'");
	                }
	                token.value = value.substring(1, value.length-1).replace( /\\n/g, "\n" ).replace( /\\r/g, "\r" );
	                Twig.log.trace("Twig.expression.compile: ", "String value: ", token.value);
	                output.push(token);
	            },
	            parse: Twig.expression.fn.parse.push_value
	        },
	        {
	            /**
	             * Match a subexpression set start.
	             */
	            type: Twig.expression.type.subexpression.start,
	            regex: /^\(/,
	            next: Twig.expression.set.expressions.concat([Twig.expression.type.subexpression.end]),
	            compile: function(token, stack, output) {
	                token.value = '(';
	                output.push(token);
	                stack.push(token);
	            },
	            parse: Twig.expression.fn.parse.push
	        },
	        {
	            /**
	             * Match a subexpression set end.
	             */
	            type: Twig.expression.type.subexpression.end,
	            regex: /^\)/,
	            next: Twig.expression.set.operations_extended,
	            validate: function(match, tokens) {
	                // Iterate back through previous tokens to ensure we follow a subexpression start
	                var i = tokens.length - 1,
	                    found_subexpression_start = false,
	                    next_subexpression_start_invalid = false,
	                    unclosed_parameter_count = 0;

	                while(!found_subexpression_start && i >= 0) {
	                    var token = tokens[i];

	                    found_subexpression_start = token.type === Twig.expression.type.subexpression.start;

	                    // If we have previously found a subexpression end, then this subexpression start is the start of
	                    // that subexpression, not the subexpression we are searching for
	                    if (found_subexpression_start && next_subexpression_start_invalid) {
	                        next_subexpression_start_invalid = false;
	                        found_subexpression_start = false;
	                    }

	                    // Count parameter tokens to ensure we dont return truthy for a parameter opener
	                    if (token.type === Twig.expression.type.parameter.start) {
	                        unclosed_parameter_count++;
	                    } else if (token.type === Twig.expression.type.parameter.end) {
	                        unclosed_parameter_count--;
	                    } else if (token.type === Twig.expression.type.subexpression.end) {
	                        next_subexpression_start_invalid = true;
	                    }

	                    i--;
	                }

	                // If we found unclosed parameters, return false
	                // If we didnt find subexpression start, return false
	                // Otherwise return true

	                return (found_subexpression_start && (unclosed_parameter_count === 0));
	            },
	            compile: function(token, stack, output) {
	                // This is basically a copy of parameter end compilation
	                var stack_token,
	                    end_token = token;

	                stack_token = stack.pop();
	                while(stack.length > 0 && stack_token.type != Twig.expression.type.subexpression.start) {
	                    output.push(stack_token);
	                    stack_token = stack.pop();
	                }

	                // Move contents of parens into preceding filter
	                var param_stack = [];
	                while(token.type !== Twig.expression.type.subexpression.start) {
	                    // Add token to arguments stack
	                    param_stack.unshift(token);
	                    token = output.pop();
	                }

	                param_stack.unshift(token);

	                var is_expression = false;

	                //If the token at the top of the *stack* is a function token, pop it onto the output queue.
	                // Get the token preceding the parameters
	                stack_token = stack[stack.length-1];

	                if (stack_token === undefined ||
	                    (stack_token.type !== Twig.expression.type._function &&
	                    stack_token.type !== Twig.expression.type.filter &&
	                    stack_token.type !== Twig.expression.type.test &&
	                    stack_token.type !== Twig.expression.type.key.brackets)) {

	                    end_token.expression = true;

	                    // remove start and end token from stack
	                    param_stack.pop();
	                    param_stack.shift();

	                    end_token.params = param_stack;

	                    output.push(end_token);
	                } else {
	                    // This should never be hit
	                    end_token.expression = false;
	                    stack_token.params = param_stack;
	                }
	            },
	            parse: function(token, stack, context) {
	                var new_array = [],
	                    array_ended = false,
	                    value = null;

	                if (token.expression) {
	                    return Twig.expression.parseAsync.apply(this, [token.params, context])
	                    .then(function(value) {
	                        stack.push(value);
	                    });
	                } else {
	                    throw new Twig.Error("Unexpected subexpression end when token is not marked as an expression");
	                }
	            }
	        },
	        {
	            /**
	             * Match a parameter set start.
	             */
	            type: Twig.expression.type.parameter.start,
	            regex: /^\(/,
	            next: Twig.expression.set.expressions.concat([Twig.expression.type.parameter.end]),
	            validate: function(match, tokens) {
	                var last_token = tokens[tokens.length - 1];
	                // We can't use the regex to test if we follow a space because expression is trimmed
	                return last_token && (Twig.indexOf(Twig.expression.reservedWords, last_token.value.trim()) < 0);
	            },
	            compile: Twig.expression.fn.compile.push_both,
	            parse: Twig.expression.fn.parse.push
	        },
	        {
	            /**
	             * Match a parameter set end.
	             */
	            type: Twig.expression.type.parameter.end,
	            regex: /^\)/,
	            next: Twig.expression.set.operations_extended,
	            compile: function(token, stack, output) {
	                var stack_token,
	                    end_token = token;

	                stack_token = stack.pop();
	                while(stack.length > 0 && stack_token.type != Twig.expression.type.parameter.start) {
	                    output.push(stack_token);
	                    stack_token = stack.pop();
	                }

	                // Move contents of parens into preceding filter
	                var param_stack = [];
	                while(token.type !== Twig.expression.type.parameter.start) {
	                    // Add token to arguments stack
	                    param_stack.unshift(token);
	                    token = output.pop();
	                }
	                param_stack.unshift(token);

	                var is_expression = false;

	                // Get the token preceding the parameters
	                token = output[output.length-1];

	                if (token === undefined ||
	                    (token.type !== Twig.expression.type._function &&
	                    token.type !== Twig.expression.type.filter &&
	                    token.type !== Twig.expression.type.test &&
	                    token.type !== Twig.expression.type.key.brackets)) {

	                    end_token.expression = true;

	                    // remove start and end token from stack
	                    param_stack.pop();
	                    param_stack.shift();

	                    end_token.params = param_stack;

	                    output.push(end_token);

	                } else {
	                    end_token.expression = false;
	                    token.params = param_stack;
	                }
	            },
	            parse: function(token, stack, context) {
	                var new_array = [],
	                    array_ended = false,
	                    value = null;

	                if (token.expression) {
	                    return Twig.expression.parseAsync.apply(this, [token.params, context])
	                    .then(function(value) {
	                        stack.push(value);
	                    });
	                } else {

	                    while (stack.length > 0) {
	                        value = stack.pop();
	                        // Push values into the array until the start of the array
	                        if (value && value.type && value.type == Twig.expression.type.parameter.start) {
	                            array_ended = true;
	                            break;
	                        }
	                        new_array.unshift(value);
	                    }

	                    if (!array_ended) {
	                        throw new Twig.Error("Expected end of parameter set.");
	                    }

	                    stack.push(new_array);
	                }
	            }
	        },
	        {
	            type: Twig.expression.type.slice,
	            regex: /^\[(\d*\:\d*)\]/,
	            next: Twig.expression.set.operations_extended,
	            compile: function(token, stack, output) {
	                var sliceRange = token.match[1].split(':');

	                //sliceStart can be undefined when we pass parameters to the slice filter later
	                var sliceStart = (sliceRange[0]) ? parseInt(sliceRange[0]) : undefined;
	                var sliceEnd = (sliceRange[1]) ? parseInt(sliceRange[1]) : undefined;

	                token.value = 'slice';
	                token.params = [sliceStart, sliceEnd];

	                //sliceEnd can't be undefined as the slice filter doesn't check for this, but it does check the length
	                //of the params array, so just shorten it.
	                if (!sliceEnd) {
	                    token.params = [sliceStart];
	                }

	                output.push(token);
	            },
	            parse: function(token, stack, context) {
	                var input = stack.pop(),
	                    params = token.params;

	                stack.push(Twig.filter.apply(this, [token.value, input, params]));
	            }
	        },
	        {
	            /**
	             * Match an array start.
	             */
	            type: Twig.expression.type.array.start,
	            regex: /^\[/,
	            next: Twig.expression.set.expressions.concat([Twig.expression.type.array.end]),
	            compile: Twig.expression.fn.compile.push_both,
	            parse: Twig.expression.fn.parse.push
	        },
	        {
	            /**
	             * Match an array end.
	             */
	            type: Twig.expression.type.array.end,
	            regex: /^\]/,
	            next: Twig.expression.set.operations_extended,
	            compile: function(token, stack, output) {
	                var i = stack.length - 1,
	                    stack_token;
	                // pop tokens off the stack until the start of the object
	                for(;i >= 0; i--) {
	                    stack_token = stack.pop();
	                    if (stack_token.type === Twig.expression.type.array.start) {
	                        break;
	                    }
	                    output.push(stack_token);
	                }
	                output.push(token);
	            },
	            parse: function(token, stack, context) {
	                var new_array = [],
	                    array_ended = false,
	                    value = null;

	                while (stack.length > 0) {
	                    value = stack.pop();
	                    // Push values into the array until the start of the array
	                    if (value.type && value.type == Twig.expression.type.array.start) {
	                        array_ended = true;
	                        break;
	                    }
	                    new_array.unshift(value);
	                }
	                if (!array_ended) {
	                    throw new Twig.Error("Expected end of array.");
	                }

	                stack.push(new_array);
	            }
	        },
	        // Token that represents the start of a hash map '}'
	        //
	        // Hash maps take the form:
	        //    { "key": 'value', "another_key": item }
	        //
	        // Keys must be quoted (either single or double) and values can be any expression.
	        {
	            type: Twig.expression.type.object.start,
	            regex: /^\{/,
	            next: Twig.expression.set.expressions.concat([Twig.expression.type.object.end]),
	            compile: Twig.expression.fn.compile.push_both,
	            parse: Twig.expression.fn.parse.push
	        },

	        // Token that represents the end of a Hash Map '}'
	        //
	        // This is where the logic for building the internal
	        // representation of a hash map is defined.
	        {
	            type: Twig.expression.type.object.end,
	            regex: /^\}/,
	            next: Twig.expression.set.operations_extended,
	            compile: function(token, stack, output) {
	                var i = stack.length-1,
	                    stack_token;

	                // pop tokens off the stack until the start of the object
	                for(;i >= 0; i--) {
	                    stack_token = stack.pop();
	                    if (stack_token && stack_token.type === Twig.expression.type.object.start) {
	                        break;
	                    }
	                    output.push(stack_token);
	                }
	                output.push(token);
	            },
	            parse: function(end_token, stack, context) {
	                var new_object = {},
	                    object_ended = false,
	                    token = null,
	                    token_key = null,
	                    has_value = false,
	                    value = null;

	                while (stack.length > 0) {
	                    token = stack.pop();
	                    // Push values into the array until the start of the object
	                    if (token && token.type && token.type === Twig.expression.type.object.start) {
	                        object_ended = true;
	                        break;
	                    }
	                    if (token && token.type && (token.type === Twig.expression.type.operator.binary || token.type === Twig.expression.type.operator.unary) && token.key) {
	                        if (!has_value) {
	                            throw new Twig.Error("Missing value for key '" + token.key + "' in object definition.");
	                        }
	                        new_object[token.key] = value;

	                        // Preserve the order that elements are added to the map
	                        // This is necessary since JavaScript objects don't
	                        // guarantee the order of keys
	                        if (new_object._keys === undefined) new_object._keys = [];
	                        new_object._keys.unshift(token.key);

	                        // reset value check
	                        value = null;
	                        has_value = false;

	                    } else {
	                        has_value = true;
	                        value = token;
	                    }
	                }
	                if (!object_ended) {
	                    throw new Twig.Error("Unexpected end of object.");
	                }

	                stack.push(new_object);
	            }
	        },

	        // Token representing a filter
	        //
	        // Filters can follow any expression and take the form:
	        //    expression|filter(optional, args)
	        //
	        // Filter parsing is done in the Twig.filters namespace.
	        {
	            type: Twig.expression.type.filter,
	            // match a | then a letter or _, then any number of letters, numbers, _ or -
	            regex: /^\|\s?([a-zA-Z_][a-zA-Z0-9_\-]*)/,
	            next: Twig.expression.set.operations_extended.concat([
	                    Twig.expression.type.parameter.start]),
	            compile: function(token, stack, output) {
	                token.value = token.match[1];
	                output.push(token);
	            },
	            parse: function(token, stack, context) {
	                var that = this,
	                    input = stack.pop();

	                return parseParams(this, token.params, context)
	                .then(function(params) {
	                    return Twig.filter.apply(that, [token.value, input, params]);
	                })
	                .then(function(value) {
	                    stack.push(value);
	                });
	            }
	        },
	        {
	            type: Twig.expression.type._function,
	            // match any letter or _, then any number of letters, numbers, _ or - followed by (
	            regex: /^([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/,
	            next: Twig.expression.type.parameter.start,
	            validate: function(match, tokens) {
	                // Make sure this function is not a reserved word
	                return match[1] && (Twig.indexOf(Twig.expression.reservedWords, match[1]) < 0);
	            },
	            transform: function(match, tokens) {
	                return '(';
	            },
	            compile: function(token, stack, output) {
	                var fn = token.match[1];
	                token.fn = fn;
	                // cleanup token
	                delete token.match;
	                delete token.value;

	                output.push(token);
	            },
	            parse: function(token, stack, context) {

	                var that = this,
	                    fn = token.fn,
	                    value;

	                return parseParams(this, token.params, context)
	                .then(function(params) {
	                    if (Twig.functions[fn]) {
	                        // Get the function from the built-in functions
	                        value = Twig.functions[fn].apply(that, params);

	                    } else if (typeof context[fn] == 'function') {
	                        // Get the function from the user/context defined functions
	                        value = context[fn].apply(context, params);

	                    } else {
	                        throw new Twig.Error(fn + ' function does not exist and is not defined in the context');
	                    }

	                    return value;
	                })
	                .then(function(result) {
	                    stack.push(result);
	                });
	            }
	        },

	        // Token representing a variable.
	        //
	        // Variables can contain letters, numbers, underscores and
	        // dashes, but must start with a letter or underscore.
	        //
	        // Variables are retrieved from the render context and take
	        // the value of 'undefined' if the given variable doesn't
	        // exist in the context.
	        {
	            type: Twig.expression.type.variable,
	            // match any letter or _, then any number of letters, numbers, _ or -
	            regex: /^[a-zA-Z_][a-zA-Z0-9_]*/,
	            next: Twig.expression.set.operations_extended.concat([
	                    Twig.expression.type.parameter.start]),
	            compile: Twig.expression.fn.compile.push,
	            validate: function(match, tokens) {
	                return (Twig.indexOf(Twig.expression.reservedWords, match[0]) < 0);
	            },
	            parse: function(token, stack, context) {
	                // Get the variable from the context
	                return Twig.expression.resolveAsync.apply(this, [context[token.value], context])
	                .then(function(value) {
	                    stack.push(value);
	                });
	            }
	        },
	        {
	            type: Twig.expression.type.key.period,
	            regex: /^\.([a-zA-Z0-9_]+)/,
	            next: Twig.expression.set.operations_extended.concat([
	                    Twig.expression.type.parameter.start]),
	            compile: function(token, stack, output) {
	                token.key = token.match[1];
	                delete token.match;
	                delete token.value;

	                output.push(token);
	            },
	            parse: function(token, stack, context, next_token) {
	                var that = this,
	                    key = token.key,
	                    object = stack.pop(),
	                    value;

	                return parseParams(this, token.params, context)
	                .then(function(params) {
	                    if (object === null || object === undefined) {
	                        if (that.options.strict_variables) {
	                            throw new Twig.Error("Can't access a key " + key + " on an null or undefined object.");
	                        } else {
	                            value = undefined;
	                        }
	                    } else {
	                        var capitalize = function (value) {
	                            return value.substr(0, 1).toUpperCase() + value.substr(1);
	                        };

	                        // Get the variable from the context
	                        if (typeof object === 'object' && key in object) {
	                            value = object[key];
	                        } else if (object["get" + capitalize(key)] !== undefined) {
	                            value = object["get" + capitalize(key)];
	                        } else if (object["is" + capitalize(key)] !== undefined) {
	                            value = object["is" + capitalize(key)];
	                        } else {
	                            value = undefined;
	                        }
	                    }

	                    // When resolving an expression we need to pass next_token in case the expression is a function
	                    return Twig.expression.resolveAsync.apply(that, [value, context, params, next_token, object]);
	                })
	                .then(function(result) {
	                    stack.push(result);
	                });
	            }
	        },
	        {
	            type: Twig.expression.type.key.brackets,
	            regex: /^\[([^\]\:]*)\]/,
	            next: Twig.expression.set.operations_extended.concat([
	                    Twig.expression.type.parameter.start]),
	            compile: function(token, stack, output) {
	                var match = token.match[1];
	                delete token.value;
	                delete token.match;

	                // The expression stack for the key
	                token.stack = Twig.expression.compile({
	                    value: match
	                }).stack;

	                output.push(token);
	            },
	            parse: function(token, stack, context, next_token) {
	                // Evaluate key
	                var that = this,
	                    params = null,
	                    object,
	                    value;

	                return parseParams(this, token.params, context)
	                .then(function(parameters) {
	                    params = parameters;
	                    return Twig.expression.parseAsync.apply(that, [token.stack, context]);
	                })
	                .then(function(key) {
	                    object = stack.pop();

	                    if (object === null || object === undefined) {
	                        if (that.options.strict_variables) {
	                            throw new Twig.Error("Can't access a key " + key + " on an null or undefined object.");
	                        } else {
	                            return null;
	                        }
	                    }

	                    // Get the variable from the context
	                    if (typeof object === 'object' && key in object) {
	                        value = object[key];
	                    } else {
	                        value = null;
	                    }

	                    // When resolving an expression we need to pass next_token in case the expression is a function
	                    return Twig.expression.resolveAsync.apply(that, [value, object, params, next_token]);
	                })
	                .then(function(result) {
	                    stack.push(result);
	                });
	            }
	        },
	        {
	            /**
	             * Match a null value.
	             */
	            type: Twig.expression.type._null,
	            // match a number
	            regex: /^(null|NULL|none|NONE)/,
	            next: Twig.expression.set.operations,
	            compile: function(token, stack, output) {
	                delete token.match;
	                token.value = null;
	                output.push(token);
	            },
	            parse: Twig.expression.fn.parse.push_value
	        },
	        {
	            /**
	             * Match the context
	             */
	            type: Twig.expression.type.context,
	            regex: /^_context/,
	            next: Twig.expression.set.operations_extended.concat([
	                    Twig.expression.type.parameter.start]),
	            compile: Twig.expression.fn.compile.push,
	            parse: function(token, stack, context) {
	                stack.push(context);
	            }
	        },
	        {
	            /**
	             * Match a boolean
	             */
	            type: Twig.expression.type.bool,
	            regex: /^(true|TRUE|false|FALSE)/,
	            next: Twig.expression.set.operations,
	            compile: function(token, stack, output) {
	                token.value = (token.match[0].toLowerCase( ) === "true");
	                delete token.match;
	                output.push(token);
	            },
	            parse: Twig.expression.fn.parse.push_value
	        }
	    ];

	    /**
	     * Resolve a context value.
	     *
	     * If the value is a function, it is executed with a context parameter.
	     *
	     * @param {string} key The context object key.
	     * @param {Object} context The render context.
	     */
	    Twig.expression.resolveAsync = function(value, context, params, next_token, object) {
	        if (typeof value == 'function') {
	            var promise = Twig.Promise.resolve(params);

	            /*
	            If value is a function, it will have been impossible during the compile stage to determine that a following
	            set of parentheses were parameters for this function.

	            Those parentheses will have therefore been marked as an expression, with their own parameters, which really
	            belong to this function.

	            Those parameters will also need parsing in case they are actually an expression to pass as parameters.
	             */
	            if (next_token && next_token.type === Twig.expression.type.parameter.end) {
	                //When parsing these parameters, we need to get them all back, not just the last item on the stack.
	                var tokens_are_parameters = true;

	                promise = promise.then(function() {
	                    return next_token.params && Twig.expression.parseAsync.apply(this, [next_token.params, context, tokens_are_parameters]);
	                })
	                .then(function(p) {
	                    //Clean up the parentheses tokens on the next loop
	                    next_token.cleanup = true;

	                    return p;
	                });
	            }

	            return promise.then(function(params) {
	                return value.apply(object || context, params || []);
	            });
	        } else {
	            return Twig.Promise.resolve(value);
	        }
	    };

	    Twig.expression.resolve = function(value, context, params, next_token, object) {
	        var is_async = true,
	            result;

	        Twig.expression.resolveAsync.apply(this, [value, context, params, next_token, object])
	        .then(function(r) {
	            is_async = false;
	            result = r;
	        });

	        if (is_async)
	            throw new Twig.Error('You are using Twig.js in sync mode in combination with async extensions.');

	        return result;
	    }

	    /**
	     * Registry for logic handlers.
	     */
	    Twig.expression.handler = {};

	    /**
	     * Define a new expression type, available at Twig.logic.type.{type}
	     *
	     * @param {string} type The name of the new type.
	     */
	    Twig.expression.extendType = function (type) {
	        Twig.expression.type[type] = "Twig.expression.type." + type;
	    };

	    /**
	     * Extend the expression parsing functionality with a new definition.
	     *
	     * Token definitions follow this format:
	     *  {
	     *      type:     One of Twig.expression.type.[type], either pre-defined or added using
	     *                    Twig.expression.extendType
	     *
	     *      next:     Array of types from Twig.expression.type that can follow this token,
	     *
	     *      regex:    A regex or array of regex's that should match the token.
	     *
	     *      compile: function(token, stack, output) called when this token is being compiled.
	     *                   Should return an object with stack and output set.
	     *
	     *      parse:   function(token, stack, context) called when this token is being parsed.
	     *                   Should return an object with stack and context set.
	     *  }
	     *
	     * @param {Object} definition A token definition.
	     */
	    Twig.expression.extend = function (definition) {
	        if (!definition.type) {
	            throw new Twig.Error("Unable to extend logic definition. No type provided for " + definition);
	        }
	        Twig.expression.handler[definition.type] = definition;
	    };

	    // Extend with built-in expressions
	    while (Twig.expression.definitions.length > 0) {
	        Twig.expression.extend(Twig.expression.definitions.shift());
	    }

	    /**
	     * Break an expression into tokens defined in Twig.expression.definitions.
	     *
	     * @param {string} expression The string to tokenize.
	     *
	     * @return {Array} An array of tokens.
	     */
	    Twig.expression.tokenize = function (expression) {
	        var tokens = [],
	            // Keep an offset of the location in the expression for error messages.
	            exp_offset = 0,
	            // The valid next tokens of the previous token
	            next = null,
	            // Match information
	            type, regex, regex_array,
	            // The possible next token for the match
	            token_next,
	            // Has a match been found from the definitions
	            match_found, invalid_matches = [], match_function;

	        match_function = function () {
	            var match = Array.prototype.slice.apply(arguments),
	                string = match.pop(),
	                offset = match.pop();

	            Twig.log.trace("Twig.expression.tokenize",
	                           "Matched a ", type, " regular expression of ", match);

	            if (next && Twig.indexOf(next, type) < 0) {
	                invalid_matches.push(
	                    type + " cannot follow a " + tokens[tokens.length - 1].type +
	                           " at template:" + exp_offset + " near '" + match[0].substring(0, 20) +
	                           "...'"
	                );
	                // Not a match, don't change the expression
	                return match[0];
	            }

	            // Validate the token if a validation function is provided
	            if (Twig.expression.handler[type].validate &&
	                    !Twig.expression.handler[type].validate(match, tokens)) {
	                return match[0];
	            }

	            invalid_matches = [];

	            tokens.push({
	                type:  type,
	                value: match[0],
	                match: match
	            });

	            match_found = true;
	            next = token_next;
	            exp_offset += match[0].length;

	            // Does the token need to return output back to the expression string
	            // e.g. a function match of cycle( might return the '(' back to the expression
	            // This allows look-ahead to differentiate between token types (e.g. functions and variable names)
	            if (Twig.expression.handler[type].transform) {
	                return Twig.expression.handler[type].transform(match, tokens);
	            }
	            return '';
	        };

	        Twig.log.debug("Twig.expression.tokenize", "Tokenizing expression ", expression);

	        while (expression.length > 0) {
	            expression = expression.trim();
	            for (type in Twig.expression.handler) {
	                if (Twig.expression.handler.hasOwnProperty(type)) {
	                    token_next = Twig.expression.handler[type].next;
	                    regex = Twig.expression.handler[type].regex;
	                    Twig.log.trace("Checking type ", type, " on ", expression);
	                    if (regex instanceof Array) {
	                        regex_array = regex;
	                    } else {
	                        regex_array = [regex];
	                    }

	                    match_found = false;
	                    while (regex_array.length > 0) {
	                        regex = regex_array.pop();
	                        expression = expression.replace(regex, match_function);
	                    }
	                    // An expression token has been matched. Break the for loop and start trying to
	                    //  match the next template (if expression isn't empty.)
	                    if (match_found) {
	                        break;
	                    }
	                }
	            }
	            if (!match_found) {
	                if (invalid_matches.length > 0) {
	                    throw new Twig.Error(invalid_matches.join(" OR "));
	                } else {
	                    throw new Twig.Error("Unable to parse '" + expression + "' at template position" + exp_offset);
	                }
	            }
	        }

	        Twig.log.trace("Twig.expression.tokenize", "Tokenized to ", tokens);
	        return tokens;
	    };

	    /**
	     * Compile an expression token.
	     *
	     * @param {Object} raw_token The uncompiled token.
	     *
	     * @return {Object} The compiled token.
	     */
	    Twig.expression.compile = function (raw_token) {
	        var expression = raw_token.value,
	            // Tokenize expression
	            tokens = Twig.expression.tokenize(expression),
	            token = null,
	            output = [],
	            stack = [],
	            token_template = null;

	        Twig.log.trace("Twig.expression.compile: ", "Compiling ", expression);

	        // Push tokens into RPN stack using the Shunting-yard algorithm
	        // See http://en.wikipedia.org/wiki/Shunting_yard_algorithm

	        while (tokens.length > 0) {
	            token = tokens.shift();
	            token_template = Twig.expression.handler[token.type];

	            Twig.log.trace("Twig.expression.compile: ", "Compiling ", token);

	            // Compile the template
	            token_template.compile && token_template.compile(token, stack, output);

	            Twig.log.trace("Twig.expression.compile: ", "Stack is", stack);
	            Twig.log.trace("Twig.expression.compile: ", "Output is", output);
	        }

	        while(stack.length > 0) {
	            output.push(stack.pop());
	        }

	        Twig.log.trace("Twig.expression.compile: ", "Final output is", output);

	        raw_token.stack = output;
	        delete raw_token.value;

	        return raw_token;
	    };


	    /**
	     * Parse an RPN expression stack within a context.
	     *
	     * @param {Array} tokens An array of compiled expression tokens.
	     * @param {Object} context The render context to parse the tokens with.
	     *
	     * @return {Object} The result of parsing all the tokens. The result
	     *                  can be anything, String, Array, Object, etc... based on
	     *                  the given expression.
	     */
	    Twig.expression.parse = function (tokens, context, tokens_are_parameters, allow_async) {
	        var that = this;

	        // If the token isn't an array, make it one.
	        if (!(tokens instanceof Array)) {
	            tokens = [tokens];
	        }

	        // The output stack
	        var stack = [],
	            next_token,
	            output = null,
	            promise = null,
	            is_async = true,
	            token_template = null,
	            loop_token_fixups = [];

	        promise = Twig.async.forEach(tokens, function (token, index) {
	            //If the token is marked for cleanup, we don't need to parse it
	            if (token.cleanup) {
	                return;
	            }

	            var result = null;

	            //Determine the token that follows this one so that we can pass it to the parser
	            if (tokens.length > index + 1) {
	                next_token = tokens[index + 1];
	            }

	            token_template = Twig.expression.handler[token.type];

	            if (token_template.parse)
	                result = token_template.parse.apply(that, [token, stack, context, next_token]);

	            //Store any binary tokens for later if we are in a loop.
	            if (context.loop && token.type === Twig.expression.type.operator.binary) {
	                loop_token_fixups.push(token);
	            }

	            return result;
	        })
	        .then(function() {
	            //Check every fixup and remove "key" as long as they still have "params". This covers the use case where
	            //a ":" operator is used in a loop with a "(expression):" statement. We need to be able to evaluate the expression
	            Twig.forEach(loop_token_fixups, function (loop_token_fixup) {
	                if (loop_token_fixup.params && loop_token_fixup.key) {
	                    delete loop_token_fixup["key"];
	                }
	            });

	            //If parse has been called with a set of tokens that are parameters, we need to return the whole stack,
	            //wrapped in an Array.
	            if (tokens_are_parameters) {
	                var params = [];
	                while (stack.length > 0) {
	                    params.unshift(stack.pop());
	                }

	                stack.push(params);
	            }

	            if (allow_async)
	                return Twig.Promise.resolve(stack.pop());
	        })
	        .then(function(v) {
	            is_async = false;
	            return v;
	        });

	        if (allow_async)
	            return promise;

	        if (is_async)
	            throw new Twig.Error('You are using Twig.js in sync mode in combination with async extensions.');

	        // Pop the final value off the stack
	        return stack.pop();
	    };

	    return Twig;

	};


/***/ },
/* 4 */
/***/ function(module, exports) {

	// ## twig.expression.operator.js
	//
	// This file handles operator lookups and parsing.
	module.exports = function (Twig) {
	    "use strict";

	    /**
	     * Operator associativity constants.
	     */
	    Twig.expression.operator = {
	        leftToRight: 'leftToRight',
	        rightToLeft: 'rightToLeft'
	    };

	    var containment = function(a, b) {
	        if (b === undefined || b === null) {
	            return null;
	        } else if (b.indexOf !== undefined) {
	            // String
	            return a === b || a !== '' && b.indexOf(a) > -1;
	        } else {
	            var el;
	            for (el in b) {
	                if (b.hasOwnProperty(el) && b[el] === a) {
	                    return true;
	                }
	            }
	            return false;
	        }
	    };

	    /**
	     * Get the precidence and associativity of an operator. These follow the order that C/C++ use.
	     * See http://en.wikipedia.org/wiki/Operators_in_C_and_C++ for the table of values.
	     */
	    Twig.expression.operator.lookup = function (operator, token) {
	        switch (operator) {
	            case "..":
	                token.precidence = 20;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case ',':
	                token.precidence = 18;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            // Ternary
	            case '?:':
	            case '?':
	            case ':':
	                token.precidence = 16;
	                token.associativity = Twig.expression.operator.rightToLeft;
	                break;

	            case 'or':
	                token.precidence = 14;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case 'and':
	                token.precidence = 13;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case 'b-or':
	                token.precidence = 12;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case 'b-xor':
	                token.precidence = 11;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case 'b-and':
	                token.precidence = 10;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case '==':
	            case '!=':
	                token.precidence = 9;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case '<':
	            case '<=':
	            case '>':
	            case '>=':
	            case 'not in':
	            case 'in':
	                token.precidence = 8;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case '~': // String concatination
	            case '+':
	            case '-':
	                token.precidence = 6;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case '//':
	            case '**':
	            case '*':
	            case '/':
	            case '%':
	                token.precidence = 5;
	                token.associativity = Twig.expression.operator.leftToRight;
	                break;

	            case 'not':
	                token.precidence = 3;
	                token.associativity = Twig.expression.operator.rightToLeft;
	                break;

	            default:
	                throw new Twig.Error("Failed to lookup operator: " + operator + " is an unknown operator.");
	        }
	        token.operator = operator;
	        return token;
	    };

	    /**
	     * Handle operations on the RPN stack.
	     *
	     * Returns the updated stack.
	     */
	    Twig.expression.operator.parse = function (operator, stack) {
	        Twig.log.trace("Twig.expression.operator.parse: ", "Handling ", operator);
	        var a, b, c;

	        if (operator === '?') {
	            c = stack.pop();
	        }

	        b = stack.pop();
	        if (operator !== 'not') {
	            a = stack.pop();
	        }

	        if (operator !== 'in' && operator !== 'not in') {
	            if (a && Array.isArray(a)) {
	                a = a.length;
	            }

	            if (b && Array.isArray(b)) {
	                b = b.length;
	            }
	        }

	        switch (operator) {
	            case ':':
	                // Ignore
	                break;

	            case '?:':
	                if (Twig.lib.boolval(a)) {
	                    stack.push(a);
	                } else {
	                    stack.push(b);
	                }
	                break;
	            case '?':
	                if (a === undefined) {
	                    //An extended ternary.
	                    a = b;
	                    b = c;
	                    c = undefined;
	                }

	                if (Twig.lib.boolval(a)) {
	                    stack.push(b);
	                } else {
	                    stack.push(c);
	                }
	                break;

	            case '+':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(a + b);
	                break;

	            case '-':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(a - b);
	                break;

	            case '*':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(a * b);
	                break;

	            case '/':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(a / b);
	                break;

	            case '//':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(Math.floor(a / b));
	                break;

	            case '%':
	                b = parseFloat(b);
	                a = parseFloat(a);
	                stack.push(a % b);
	                break;

	            case '~':
	                stack.push( (a != null ? a.toString() : "")
	                          + (b != null ? b.toString() : "") );
	                break;

	            case 'not':
	            case '!':
	                stack.push(!Twig.lib.boolval(b));
	                break;

	            case '<':
	                stack.push(a < b);
	                break;

	            case '<=':
	                stack.push(a <= b);
	                break;

	            case '>':
	                stack.push(a > b);
	                break;

	            case '>=':
	                stack.push(a >= b);
	                break;

	            case '===':
	                stack.push(a === b);
	                break;

	            case '==':
	                stack.push(a == b);
	                break;

	            case '!==':
	                stack.push(a !== b);
	                break;

	            case '!=':
	                stack.push(a != b);
	                break;

	            case 'or':
	                stack.push(a || b);
	                break;

	            case 'b-or':
	                stack.push(a | b);
	                break;

	            case 'b-xor':
	                stack.push(a ^ b);
	                break;

	            case 'and':
	                stack.push(a && b);
	                break;

	            case 'b-and':
	                stack.push(a & b);
	                break;

	            case '**':
	                stack.push(Math.pow(a, b));
	                break;

	            case 'not in':
	                stack.push( !containment(a, b) );
	                break;

	            case 'in':
	                stack.push( containment(a, b) );
	                break;

	            case '..':
	                stack.push( Twig.functions.range(a, b) );
	                break;

	            default:
	                debugger;
	                throw new Twig.Error("Failed to parse operator: " + operator + " is an unknown operator.");
	        }
	    };

	    return Twig;

	};


/***/ },
/* 5 */
/***/ function(module, exports) {

	// ## twig.filters.js
	//
	// This file handles parsing filters.
	module.exports = function (Twig) {

	    // Determine object type
	    function is(type, obj) {
	        var clas = Object.prototype.toString.call(obj).slice(8, -1);
	        return obj !== undefined && obj !== null && clas === type;
	    }

	    Twig.filters = {
	        // String Filters
	        upper:  function(value) {
	            if ( typeof value !== "string" ) {
	               return value;
	            }

	            return value.toUpperCase();
	        },
	        lower: function(value) {
	            if ( typeof value !== "string" ) {
	               return value;
	            }

	            return value.toLowerCase();
	        },
	        capitalize: function(value) {
	            if ( typeof value !== "string" ) {
	                 return value;
	            }

	            return value.substr(0, 1).toUpperCase() + value.toLowerCase().substr(1);
	        },
	        title: function(value) {
	            if ( typeof value !== "string" ) {
	               return value;
	            }

	            return value.toLowerCase().replace( /(^|\s)([a-z])/g , function(m, p1, p2){
	                return p1 + p2.toUpperCase();
	            });
	        },
	        length: function(value) {
	            if (Twig.lib.is("Array", value) || typeof value === "string") {
	                return value.length;
	            } else if (Twig.lib.is("Object", value)) {
	                if (value._keys === undefined) {
	                    return Object.keys(value).length;
	                } else {
	                    return value._keys.length;
	                }
	            } else {
	                return 0;
	            }
	        },

	        // Array/Object Filters
	        reverse: function(value) {
	            if (is("Array", value)) {
	                return value.reverse();
	            } else if (is("String", value)) {
	                return value.split("").reverse().join("");
	            } else if (is("Object", value)) {
	                var keys = value._keys || Object.keys(value).reverse();
	                value._keys = keys;
	                return value;
	            }
	        },
	        sort: function(value) {
	            if (is("Array", value)) {
	                return value.sort();
	            } else if (is('Object', value)) {
	                // Sorting objects isn't obvious since the order of
	                // returned keys isn't guaranteed in JavaScript.
	                // Because of this we use a "hidden" key called _keys to
	                // store the keys in the order we want to return them.

	                delete value._keys;
	                var keys = Object.keys(value),
	                    sorted_keys = keys.sort(function(a, b) {
	                        var a1, a2;

	                        // if a and b are comparable, we're fine :-)
	                        if((value[a] > value[b]) == !(value[a] <= value[b])) {
	                            return value[a] > value[b] ? 1 :
				           value[a] < value[b] ? -1 :
					   0;
	                        }
	                        // if a and b can be parsed as numbers, we can compare
	                        // their numeric value
	                        else if(!isNaN(a1 = parseFloat(value[a])) &&
	                                !isNaN(b1 = parseFloat(value[b]))) {
	                            return a1 > b1 ? 1 :
				           a1 < b1 ? -1 :
					   0;
	                        }
	                        // if one of the values is a string, we convert the
	                        // other value to string as well
	                        else if(typeof value[a] == 'string') {
	                            return value[a] > value[b].toString() ? 1 :
	                                   value[a] < value[b].toString() ? -1 :
					   0;
	                        }
	                        else if(typeof value[b] == 'string') {
	                            return value[a].toString() > value[b] ? 1 :
	                                   value[a].toString() < value[b] ? -1 :
					   0;
	                        }
	                        // everything failed - return 'null' as sign, that
	                        // the values are not comparable
	                        else {
	                            return null;
	                        }
	                    });
	                value._keys = sorted_keys;
	                return value;
	            }
	        },
	        keys: function(value) {
	            if (value === undefined || value === null){
	                return;
	           }

	            var keyset = value._keys || Object.keys(value),
	                output = [];

	            Twig.forEach(keyset, function(key) {
	                if (key === "_keys") return; // Ignore the _keys property
	                if (value.hasOwnProperty(key)) {
	                    output.push(key);
	                }
	            });
	            return output;
	        },
	        url_encode: function(value) {
	            if (value === undefined || value === null){
	                return;
	            }

	            var result = encodeURIComponent(value);
	            result = result.replace("'", "%27");
	            return result;
	        },
	        join: function(value, params) {
	            if (value === undefined || value === null){
	                return;
	            }

	            var join_str = "",
	                output = [],
	                keyset = null;

	            if (params && params[0]) {
	                join_str = params[0];
	            }
	            if (is("Array", value)) {
	                output = value;
	            } else {
	                keyset = value._keys || Object.keys(value);
	                Twig.forEach(keyset, function(key) {
	                    if (key === "_keys") return; // Ignore the _keys property
	                    if (value.hasOwnProperty(key)) {
	                        output.push(value[key]);
	                    }
	                });
	            }
	            return output.join(join_str);
	        },
	        "default": function(value, params) {
	            if (params !== undefined && params.length > 1) {
	                throw new Twig.Error("default filter expects one argument");
	            }
	            if (value === undefined || value === null || value === '' ) {
	                if (params === undefined) {
	                    return '';
	                }

	                return params[0];
	            } else {
	                return value;
	            }
	        },
	        json_encode: function(value) {
	            if(value === undefined || value === null) {
	                return "null";
	            }
	            else if ((typeof value == 'object') && (is("Array", value))) {
	                output = [];

	                Twig.forEach(value, function(v) {
	                    output.push(Twig.filters.json_encode(v));
	                });

	                return "[" + output.join(",") + "]";
	            }
	            else if (typeof value == 'object') {
	                var keyset = value._keys || Object.keys(value),
	                output = [];

	                Twig.forEach(keyset, function(key) {
	                    output.push(JSON.stringify(key) + ":" + Twig.filters.json_encode(value[key]));
	                });

	                return "{" + output.join(",") + "}";
	            }
	            else {
	                return JSON.stringify(value);
	            }
	        },
	        merge: function(value, params) {
	            var obj = [],
	                arr_index = 0,
	                keyset = [];

	            // Check to see if all the objects being merged are arrays
	            if (!is("Array", value)) {
	                // Create obj as an Object
	                obj = { };
	            } else {
	                Twig.forEach(params, function(param) {
	                    if (!is("Array", param)) {
	                        obj = { };
	                    }
	                });
	            }
	            if (!is("Array", obj)) {
	                obj._keys = [];
	            }

	            if (is("Array", value)) {
	                Twig.forEach(value, function(val) {
	                    if (obj._keys) obj._keys.push(arr_index);
	                    obj[arr_index] = val;
	                    arr_index++;
	                });
	            } else {
	                keyset = value._keys || Object.keys(value);
	                Twig.forEach(keyset, function(key) {
	                    obj[key] = value[key];
	                    obj._keys.push(key);

	                    // Handle edge case where a number index in an object is greater than
	                    //   the array counter. In such a case, the array counter is increased
	                    //   one past the index.
	                    //
	                    // Example {{ ["a", "b"]|merge({"4":"value"}, ["c", "d"])
	                    // Without this, d would have an index of "4" and overwrite the value
	                    //   of "value"
	                    var int_key = parseInt(key, 10);
	                    if (!isNaN(int_key) && int_key >= arr_index) {
	                        arr_index = int_key + 1;
	                    }
	                });
	            }

	            // mixin the merge arrays
	            Twig.forEach(params, function(param) {
	                if (is("Array", param)) {
	                    Twig.forEach(param, function(val) {
	                        if (obj._keys) obj._keys.push(arr_index);
	                        obj[arr_index] = val;
	                        arr_index++;
	                    });
	                } else {
	                    keyset = param._keys || Object.keys(param);
	                    Twig.forEach(keyset, function(key) {
	                        if (!obj[key]) obj._keys.push(key);
	                        obj[key] = param[key];

	                        var int_key = parseInt(key, 10);
	                        if (!isNaN(int_key) && int_key >= arr_index) {
	                            arr_index = int_key + 1;
	                        }
	                    });
	                }
	            });
	            if (params.length === 0) {
	                throw new Twig.Error("Filter merge expects at least one parameter");
	            }

	            return obj;
	        },
	        date: function(value, params) {
	            var date = Twig.functions.date(value);
	            var format = params && params.length ? params[0] : 'F j, Y H:i';
	            return Twig.lib.date(format, date);
	        },

	        date_modify: function(value, params) {
	            if (value === undefined || value === null) {
	                return;
	            }
	            if (params === undefined || params.length !== 1) {
	                throw new Twig.Error("date_modify filter expects 1 argument");
	            }

	            var modifyText = params[0], time;

	            if (Twig.lib.is("Date", value)) {
	                time = Twig.lib.strtotime(modifyText, value.getTime() / 1000);
	            }
	            if (Twig.lib.is("String", value)) {
	                time = Twig.lib.strtotime(modifyText, Twig.lib.strtotime(value));
	            }
	            if (Twig.lib.is("Number", value)) {
	                time = Twig.lib.strtotime(modifyText, value);
	            }

	            return new Date(time * 1000);
	        },

	        replace: function(value, params) {
	            if (value === undefined||value === null){
	                return;
	            }

	            var pairs = params[0],
	                tag;
	            for (tag in pairs) {
	                if (pairs.hasOwnProperty(tag) && tag !== "_keys") {
	                    value = Twig.lib.replaceAll(value, tag, pairs[tag]);
	                }
	            }
	            return value;
	        },

	        format: function(value, params) {
	            if (value === undefined || value === null){
	                return;
	            }

	            return Twig.lib.vsprintf(value, params);
	        },

	        striptags: function(value) {
	            if (value === undefined || value === null){
	                return;
	            }

	            return Twig.lib.strip_tags(value);
	        },

	        escape: function(value, params) {
	            if (value === undefined|| value === null){
	                return;
	            }

	            var strategy = "html";
	            if(params && params.length && params[0] !== true)
	                strategy = params[0];

	            if(strategy == "html") {
	                var raw_value = value.toString().replace(/&/g, "&amp;")
	                            .replace(/</g, "&lt;")
	                            .replace(/>/g, "&gt;")
	                            .replace(/"/g, "&quot;")
	                            .replace(/'/g, "&#039;");
	                return Twig.Markup(raw_value, 'html');
	            } else if(strategy == "js") {
	                var raw_value = value.toString();
	                var result = "";

	                for(var i = 0; i < raw_value.length; i++) {
	                    if(raw_value[i].match(/^[a-zA-Z0-9,\._]$/))
	                        result += raw_value[i];
	                    else {
	                        var char_code = raw_value.charCodeAt(i);

	                        if(char_code < 0x80)
	                            result += "\\x" + char_code.toString(16).toUpperCase();
	                        else
	                            result += Twig.lib.sprintf("\\u%04s", char_code.toString(16).toUpperCase());
	                    }
	                }

	                return Twig.Markup(result, 'js');
	            } else if(strategy == "css") {
	                var raw_value = value.toString();
	                var result = "";

	                for(var i = 0; i < raw_value.length; i++) {
	                    if(raw_value[i].match(/^[a-zA-Z0-9]$/))
	                        result += raw_value[i];
	                    else {
	                        var char_code = raw_value.charCodeAt(i);
	                        result += "\\" + char_code.toString(16).toUpperCase() + " ";
	                    }
	                }

	                return Twig.Markup(result, 'css');
	            } else if(strategy == "url") {
	                var result = Twig.filters.url_encode(value);
	                return Twig.Markup(result, 'url');
	            } else if(strategy == "html_attr") {
	                var raw_value = value.toString();
	                var result = "";

	                for(var i = 0; i < raw_value.length; i++) {
	                    if(raw_value[i].match(/^[a-zA-Z0-9,\.\-_]$/))
	                        result += raw_value[i];
	                    else if(raw_value[i].match(/^[&<>"]$/))
	                        result += raw_value[i].replace(/&/g, "&amp;")
	                                .replace(/</g, "&lt;")
	                                .replace(/>/g, "&gt;")
	                                .replace(/"/g, "&quot;");
	                    else {
	                        var char_code = raw_value.charCodeAt(i);

	                        // The following replaces characters undefined in HTML with
	                        // the hex entity for the Unicode replacement character.
	                        if(char_code <= 0x1f && char_code != 0x09 && char_code != 0x0a && char_code != 0x0d)
	                            result += "&#xFFFD;";
	                        else if(char_code < 0x80)
	                            result += Twig.lib.sprintf("&#x%02s;", char_code.toString(16).toUpperCase());
	                        else
	                            result += Twig.lib.sprintf("&#x%04s;", char_code.toString(16).toUpperCase());
	                    }
	                }

	                return Twig.Markup(result, 'html_attr');
	            } else {
	                throw new Twig.Error("escape strategy unsupported");
	            }
	        },

	        /* Alias of escape */
	        "e": function(value, params) {
	            return Twig.filters.escape(value, params);
	        },

	        nl2br: function(value) {
	            if (value === undefined || value === null){
	                return;
	            }
	            var linebreak_tag = "BACKSLASH_n_replace",
	                br = "<br />" + linebreak_tag;

	            value = Twig.filters.escape(value)
	                        .replace(/\r\n/g, br)
	                        .replace(/\r/g, br)
	                        .replace(/\n/g, br);

	            value = Twig.lib.replaceAll(value, linebreak_tag, "\n");

	            return Twig.Markup(value);
	        },

	        /**
	         * Adapted from: http://phpjs.org/functions/number_format:481
	         */
	        number_format: function(value, params) {
	            var number = value,
	                decimals = (params && params[0]) ? params[0] : undefined,
	                dec      = (params && params[1] !== undefined) ? params[1] : ".",
	                sep      = (params && params[2] !== undefined) ? params[2] : ",";

	            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	            var n = !isFinite(+number) ? 0 : +number,
	                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	                s = '',
	                toFixedFix = function (n, prec) {
	                    var k = Math.pow(10, prec);
	                    return '' + Math.round(n * k) / k;
	                };
	            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	            if (s[0].length > 3) {
	                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	            }
	            if ((s[1] || '').length < prec) {
	                s[1] = s[1] || '';
	                s[1] += new Array(prec - s[1].length + 1).join('0');
	            }
	            return s.join(dec);
	        },

	        trim: function(value, params) {
	            if (value === undefined|| value === null){
	                return;
	            }

	            var str = Twig.filters.escape( '' + value ),
	                whitespace;
	            if ( params && params[0] ) {
	                whitespace = '' + params[0];
	            } else {
	                whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
	            }
	            for (var i = 0; i < str.length; i++) {
	                if (whitespace.indexOf(str.charAt(i)) === -1) {
	                    str = str.substring(i);
	                    break;
	                }
	            }
	            for (i = str.length - 1; i >= 0; i--) {
	                if (whitespace.indexOf(str.charAt(i)) === -1) {
	                    str = str.substring(0, i + 1);
	                    break;
	                }
	            }
	            return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
	        },

	        truncate: function (value, params) {
	            var length = 30,
	                preserve = false,
	                separator = '...';

	            value =  value + '';
	            if (params) {
	                if (params[0]) {
	                    length = params[0];
	                }
	                if (params[1]) {
	                    preserve = params[1];
	                }
	                if (params[2]) {
	                    separator = params[2];
	                }
	            }

	            if (value.length > length) {

	                if (preserve) {
	                    length = value.indexOf(' ', length);
	                    if (length === -1) {
	                        return value;
	                    }
	                }

	                value =  value.substr(0, length) + separator;
	            }

	            return value;
	        },

	        slice: function(value, params) {
	            if (value === undefined || value === null) {
	                return;
	            }
	            if (params === undefined || params.length < 1) {
	                throw new Twig.Error("slice filter expects at least 1 argument");
	            }

	            // default to start of string
	            var start = params[0] || 0;
	            // default to length of string
	            var length = params.length > 1 ? params[1] : value.length;
	            // handle negative start values
	            var startIndex = start >= 0 ? start : Math.max( value.length + start, 0 );

	            if (Twig.lib.is("Array", value)) {
	                var output = [];
	                for (var i = startIndex; i < startIndex + length && i < value.length; i++) {
	                    output.push(value[i]);
	                }
	                return output;
	            } else if (Twig.lib.is("String", value)) {
	                return value.substr(startIndex, length);
	            } else {
	                throw new Twig.Error("slice filter expects value to be an array or string");
	            }
	        },

	        abs: function(value) {
	            if (value === undefined || value === null) {
	                return;
	            }

	            return Math.abs(value);
	        },

	        first: function(value) {
	            if (is("Array", value)) {
	                return value[0];
	            } else if (is("Object", value)) {
	                if ('_keys' in value) {
	                    return value[value._keys[0]];
	                }
	            } else if ( typeof value === "string" ) {
	                return value.substr(0, 1);
	            }

	            return;
	        },

	        split: function(value, params) {
	            if (value === undefined || value === null) {
	                return;
	            }
	            if (params === undefined || params.length < 1 || params.length > 2) {
	                throw new Twig.Error("split filter expects 1 or 2 argument");
	            }
	            if (Twig.lib.is("String", value)) {
	                var delimiter = params[0],
	                    limit = params[1],
	                    split = value.split(delimiter);

	                if (limit === undefined) {

	                    return split;

	                } else if (limit < 0) {

	                    return value.split(delimiter, split.length + limit);

	                } else {

	                    var limitedSplit = [];

	                    if (delimiter == '') {
	                        // empty delimiter
	                        // "aabbcc"|split('', 2)
	                        //     -> ['aa', 'bb', 'cc']

	                        while(split.length > 0) {
	                            var temp = "";
	                            for (var i=0; i<limit && split.length > 0; i++) {
	                                temp += split.shift();
	                            }
	                            limitedSplit.push(temp);
	                        }

	                    } else {
	                        // non-empty delimiter
	                        // "one,two,three,four,five"|split(',', 3)
	                        //     -> ['one', 'two', 'three,four,five']

	                        for (var i=0; i<limit-1 && split.length > 0; i++) {
	                            limitedSplit.push(split.shift());
	                        }

	                        if (split.length > 0) {
	                            limitedSplit.push(split.join(delimiter));
	                        }
	                    }

	                    return limitedSplit;
	                }

	            } else {
	                throw new Twig.Error("split filter expects value to be a string");
	            }
	        },
	        last: function(value) {
	            if (Twig.lib.is('Object', value)) {
	                var keys;

	                if (value._keys === undefined) {
	                    keys = Object.keys(value);
	                } else {
	                    keys = value._keys;
	                }

	                return value[keys[keys.length - 1]];
	            }

	            // string|array
	            return value[value.length - 1];
	        },
	        raw: function(value) {
	            return Twig.Markup(value);
	        },
	        batch: function(items, params) {
	            var size = params.shift(),
	                fill = params.shift(),
	                result,
	                last,
	                missing;

	            if (!Twig.lib.is("Array", items)) {
	                throw new Twig.Error("batch filter expects items to be an array");
	            }

	            if (!Twig.lib.is("Number", size)) {
	                throw new Twig.Error("batch filter expects size to be a number");
	            }

	            size = Math.ceil(size);

	            result = Twig.lib.chunkArray(items, size);

	            if (fill && items.length % size != 0) {
	                last = result.pop();
	                missing = size - last.length;

	                while (missing--) {
	                    last.push(fill);
	                }

	                result.push(last);
	            }

	            return result;
	        },
	        round: function(value, params) {
	            params = params || [];

	            var precision = params.length > 0 ? params[0] : 0,
	                method = params.length > 1 ? params[1] : "common";

	            value = parseFloat(value);

	            if(precision && !Twig.lib.is("Number", precision)) {
	                throw new Twig.Error("round filter expects precision to be a number");
	            }

	            if (method === "common") {
	                return Twig.lib.round(value, precision);
	            }

	            if(!Twig.lib.is("Function", Math[method])) {
	                throw new Twig.Error("round filter expects method to be 'floor', 'ceil', or 'common'");
	            }

	            return Math[method](value * Math.pow(10, precision)) / Math.pow(10, precision);
	        }
	    };

	    Twig.filter = function(filter, value, params) {
	        if (!Twig.filters[filter]) {
	            throw "Unable to find filter " + filter;
	        }
	        return Twig.filters[filter].apply(this, [value, params]);
	    };

	    Twig.filter.extend = function(filter, definition) {
	        Twig.filters[filter] = definition;
	    };

	    return Twig;

	};


/***/ },
/* 6 */
/***/ function(module, exports) {

	// ## twig.functions.js
	//
	// This file handles parsing filters.
	module.exports = function (Twig) {
	    /**
	     * @constant
	     * @type {string}
	     */
	    var TEMPLATE_NOT_FOUND_MESSAGE = 'Template "{name}" is not defined.';

	    // Determine object type
	    function is(type, obj) {
	        var clas = Object.prototype.toString.call(obj).slice(8, -1);
	        return obj !== undefined && obj !== null && clas === type;
	    }

	    Twig.functions = {
	        //  attribute, block, constant, date, dump, parent, random,.

	        // Range function from http://phpjs.org/functions/range:499
	        // Used under an MIT License
	        range: function (low, high, step) {
	            // http://kevin.vanzonneveld.net
	            // +   original by: Waldo Malqui Silva
	            // *     example 1: range ( 0, 12 );
	            // *     returns 1: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
	            // *     example 2: range( 0, 100, 10 );
	            // *     returns 2: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
	            // *     example 3: range( 'a', 'i' );
	            // *     returns 3: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']
	            // *     example 4: range( 'c', 'a' );
	            // *     returns 4: ['c', 'b', 'a']
	            var matrix = [];
	            var inival, endval, plus;
	            var walker = step || 1;
	            var chars = false;

	            if (!isNaN(low) && !isNaN(high)) {
	                inival = parseInt(low, 10);
	                endval = parseInt(high, 10);
	            } else if (isNaN(low) && isNaN(high)) {
	                chars = true;
	                inival = low.charCodeAt(0);
	                endval = high.charCodeAt(0);
	            } else {
	                inival = (isNaN(low) ? 0 : low);
	                endval = (isNaN(high) ? 0 : high);
	            }

	            plus = ((inival > endval) ? false : true);
	            if (plus) {
	                while (inival <= endval) {
	                    matrix.push(((chars) ? String.fromCharCode(inival) : inival));
	                    inival += walker;
	                }
	            } else {
	                while (inival >= endval) {
	                    matrix.push(((chars) ? String.fromCharCode(inival) : inival));
	                    inival -= walker;
	                }
	            }

	            return matrix;
	        },
	        cycle: function(arr, i) {
	            var pos = i % arr.length;
	            return arr[pos];
	        },
	        dump: function() {
	            var EOL = '\n',
	                indentChar = '  ',
	                indentTimes = 0,
	                out = '',
	                args = Array.prototype.slice.call(arguments),
	                indent = function(times) {
	                    var ind  = '';
	                    while (times > 0) {
	                        times--;
	                        ind += indentChar;
	                    }
	                    return ind;
	                },
	                displayVar = function(variable) {
	                    out += indent(indentTimes);
	                    if (typeof(variable) === 'object') {
	                        dumpVar(variable);
	                    } else if (typeof(variable) === 'function') {
	                        out += 'function()' + EOL;
	                    } else if (typeof(variable) === 'string') {
	                        out += 'string(' + variable.length + ') "' + variable + '"' + EOL;
	                    } else if (typeof(variable) === 'number') {
	                        out += 'number(' + variable + ')' + EOL;
	                    } else if (typeof(variable) === 'boolean') {
	                        out += 'bool(' + variable + ')' + EOL;
	                    }
	                },
	                dumpVar = function(variable) {
	                    var i;
	                    if (variable === null) {
	                        out += 'NULL' + EOL;
	                    } else if (variable === undefined) {
	                        out += 'undefined' + EOL;
	                    } else if (typeof variable === 'object') {
	                        out += indent(indentTimes) + typeof(variable);
	                        indentTimes++;
	                        out += '(' + (function(obj) {
	                            var size = 0, key;
	                            for (key in obj) {
	                                if (obj.hasOwnProperty(key)) {
	                                    size++;
	                                }
	                            }
	                            return size;
	                        })(variable) + ') {' + EOL;
	                        for (i in variable) {
	                            out += indent(indentTimes) + '[' + i + ']=> ' + EOL;
	                            displayVar(variable[i]);
	                        }
	                        indentTimes--;
	                        out += indent(indentTimes) + '}' + EOL;
	                    } else {
	                        displayVar(variable);
	                    }
	                };

	            // handle no argument case by dumping the entire render context
	            if (args.length == 0) args.push(this.context);

	            Twig.forEach(args, function(variable) {
	                dumpVar(variable);
	            });

	            return out;
	        },
	        date: function(date, time) {
	            var dateObj;
	            if (date === undefined || date === null || date === "") {
	                dateObj = new Date();
	            } else if (Twig.lib.is("Date", date)) {
	                dateObj = date;
	            } else if (Twig.lib.is("String", date)) {
	                if (date.match(/^[0-9]+$/)) {
	                    dateObj = new Date(date * 1000);
	                }
	                else {
	                    dateObj = new Date(Twig.lib.strtotime(date) * 1000);
	                }
	            } else if (Twig.lib.is("Number", date)) {
	                // timestamp
	                dateObj = new Date(date * 1000);
	            } else {
	                throw new Twig.Error("Unable to parse date " + date);
	            }
	            return dateObj;
	        },
	        block: function(block) {
	            if (this.originalBlockTokens[block]) {
	                return Twig.logic.parse.apply(this, [this.originalBlockTokens[block], this.context]).output;
	            } else {
	                return this.blocks[block];
	            }
	        },
	        parent: function() {
	            // Add a placeholder
	            return Twig.placeholders.parent;
	        },
	        attribute: function(object, method, params) {
	            if (Twig.lib.is('Object', object)) {
	                if (object.hasOwnProperty(method)) {
	                    if (typeof object[method] === "function") {
	                        return object[method].apply(undefined, params);
	                    }
	                    else {
	                        return object[method];
	                    }
	                }
	            }
	            // Array will return element 0-index
	            return object[method] || undefined;
	        },
	        max: function(values) {
	            if(Twig.lib.is("Object", values)) {
	                delete values["_keys"];
	                return Twig.lib.max(values);
	            }

	            return Twig.lib.max.apply(null, arguments);
	        },
	        min: function(values) {
	            if(Twig.lib.is("Object", values)) {
	                delete values["_keys"];
	                return Twig.lib.min(values);
	            }

	            return Twig.lib.min.apply(null, arguments);
	        },
	        template_from_string: function(template) {
	            if (template === undefined) {
	                template = '';
	            }
	            return Twig.Templates.parsers.twig({
	                options: this.options,
	                data: template
	            });
	        },
	        random: function(value) {
	            var LIMIT_INT31 = 0x80000000;

	            function getRandomNumber(n) {
	                var random = Math.floor(Math.random() * LIMIT_INT31);
	                var limits = [0, n];
	                var min = Math.min.apply(null, limits),
	                    max = Math.max.apply(null, limits);
	                return min + Math.floor((max - min + 1) * random / LIMIT_INT31);
	            }

	            if(Twig.lib.is("Number", value)) {
	                return getRandomNumber(value);
	            }

	            if(Twig.lib.is("String", value)) {
	                return value.charAt(getRandomNumber(value.length-1));
	            }

	            if(Twig.lib.is("Array", value)) {
	                return value[getRandomNumber(value.length-1)];
	            }

	            if(Twig.lib.is("Object", value)) {
	                var keys = Object.keys(value);
	                return value[keys[getRandomNumber(keys.length-1)]];
	            }

	            return getRandomNumber(LIMIT_INT31-1);
	        },

	        /**
	         * Returns the content of a template without rendering it
	         * @param {string} name
	         * @param {boolean} [ignore_missing=false]
	         * @returns {string}
	         */
	        source: function(name, ignore_missing) {
	            var templateSource;
	            var templateFound = false;
	            var isNodeEnvironment = typeof module !== 'undefined' && typeof module.exports !== 'undefined' && typeof window === 'undefined';
	            var loader;
	            var path;

	            //if we are running in a node.js environment, set the loader to 'fs' and ensure the
	            // path is relative to the CWD of the running script
	            //else, set the loader to 'ajax' and set the path to the value of name
	            if (isNodeEnvironment) {
	                loader = 'fs';
	                path = __dirname + '/' + name;
	            } else {
	                loader = 'ajax';
	                path = name;
	            }

	            //build the params object
	            var params = {
	                id: name,
	                path: path,
	                method: loader,
	                parser: 'source',
	                async: false,
	                fetchTemplateSource: true
	            };

	            //default ignore_missing to false
	            if (typeof ignore_missing === 'undefined') {
	                ignore_missing = false;
	            }

	            //try to load the remote template
	            //
	            //on exception, log it
	            try {
	                templateSource = Twig.Templates.loadRemote(name, params);

	                //if the template is undefined or null, set the template to an empty string and do NOT flip the
	                // boolean indicating we found the template
	                //
	                //else, all is good! flip the boolean indicating we found the template
	                if (typeof templateSource === 'undefined' || templateSource === null) {
	                    templateSource = '';
	                } else {
	                    templateFound = true;
	                }
	            } catch (e) {
	                Twig.log.debug('Twig.functions.source: ', 'Problem loading template  ', e);
	            }

	            //if the template was NOT found AND we are not ignoring missing templates, return the same message
	            // that is returned by the PHP implementation of the twig source() function
	            //
	            //else, return the template source
	            if (!templateFound && !ignore_missing) {
	                return TEMPLATE_NOT_FOUND_MESSAGE.replace('{name}', name);
	            } else {
	                return templateSource;
	            }
	        }
	    };

	    Twig._function = function(_function, value, params) {
	        if (!Twig.functions[_function]) {
	            throw "Unable to find function " + _function;
	        }
	        return Twig.functions[_function](value, params);
	    };

	    Twig._function.extend = function(_function, definition) {
	        Twig.functions[_function] = definition;
	    };

	    return Twig;

	};


/***/ },
/* 7 */
/***/ function(module, exports, __webpack_require__) {

	// ## twig.lib.js
	//
	// This file contains 3rd party libraries used within twig.
	//
	// Copies of the licenses for the code included here can be found in the
	// LICENSES.md file.
	//

	module.exports = function(Twig) {

	    // Namespace for libraries
	    Twig.lib = { };

	    Twig.lib.sprintf = __webpack_require__(8);
	    Twig.lib.vsprintf = __webpack_require__(9);
	    Twig.lib.round = __webpack_require__(10);
	    Twig.lib.max = __webpack_require__(11);
	    Twig.lib.min = __webpack_require__(12);
	    Twig.lib.strip_tags = __webpack_require__(13);
	    Twig.lib.strtotime = __webpack_require__(14);
	    Twig.lib.date = __webpack_require__(15);
	    Twig.lib.boolval = __webpack_require__(16);

	    Twig.lib.is = function(type, obj) {
	        var clas = Object.prototype.toString.call(obj).slice(8, -1);
	        return obj !== undefined && obj !== null && clas === type;
	    };

	    // shallow-copy an object
	    Twig.lib.copy = function(src) {
	        var target = {},
	            key;
	        for (key in src)
	            target[key] = src[key];

	        return target;
	    };

	    Twig.lib.extend = function (src, add) {
	        var keys = Object.keys(add),
	            i;

	        i = keys.length;

	        while (i--) {
	            src[keys[i]] = add[keys[i]];
	        }

	        return src;
	    };

	    Twig.lib.replaceAll = function(string, search, replace) {
	        return string.split(search).join(replace);
	    };

	    // chunk an array (arr) into arrays of (size) items, returns an array of arrays, or an empty array on invalid input
	    Twig.lib.chunkArray = function (arr, size) {
	        var returnVal = [],
	            x = 0,
	            len = arr.length;

	        if (size < 1 || !Twig.lib.is("Array", arr)) {
	            return [];
	        }

	        while (x < len) {
	            returnVal.push(arr.slice(x, x += size));
	        }

	        return returnVal;
	    };

	    return Twig;
	};


/***/ },
/* 8 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function sprintf() {
	  //  discuss at: http://locutus.io/php/sprintf/
	  // original by: Ash Searle (http://hexmen.com/blog/)
	  // improved by: Michael White (http://getsprink.com)
	  // improved by: Jack
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: Dj
	  // improved by: Allidylls
	  //    input by: Paulo Freitas
	  //    input by: Brett Zamir (http://brett-zamir.me)
	  //   example 1: sprintf("%01.2f", 123.1)
	  //   returns 1: '123.10'
	  //   example 2: sprintf("[%10s]", 'monkey')
	  //   returns 2: '[    monkey]'
	  //   example 3: sprintf("[%'#10s]", 'monkey')
	  //   returns 3: '[####monkey]'
	  //   example 4: sprintf("%d", 123456789012345)
	  //   returns 4: '123456789012345'
	  //   example 5: sprintf('%-03s', 'E')
	  //   returns 5: 'E00'

	  var regex = /%%|%(\d+\$)?([-+'#0 ]*)(\*\d+\$|\*|\d+)?(?:\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
	  var a = arguments;
	  var i = 0;
	  var format = a[i++];

	  var _pad = function _pad(str, len, chr, leftJustify) {
	    if (!chr) {
	      chr = ' ';
	    }
	    var padding = str.length >= len ? '' : new Array(1 + len - str.length >>> 0).join(chr);
	    return leftJustify ? str + padding : padding + str;
	  };

	  var justify = function justify(value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
	    var diff = minWidth - value.length;
	    if (diff > 0) {
	      if (leftJustify || !zeroPad) {
	        value = _pad(value, minWidth, customPadChar, leftJustify);
	      } else {
	        value = [value.slice(0, prefix.length), _pad('', diff, '0', true), value.slice(prefix.length)].join('');
	      }
	    }
	    return value;
	  };

	  var _formatBaseX = function _formatBaseX(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
	    // Note: casts negative numbers to positive ones
	    var number = value >>> 0;
	    prefix = prefix && number && {
	      '2': '0b',
	      '8': '0',
	      '16': '0x'
	    }[base] || '';
	    value = prefix + _pad(number.toString(base), precision || 0, '0', false);
	    return justify(value, prefix, leftJustify, minWidth, zeroPad);
	  };

	  // _formatString()
	  var _formatString = function _formatString(value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
	    if (precision !== null && precision !== undefined) {
	      value = value.slice(0, precision);
	    }
	    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
	  };

	  // doFormat()
	  var doFormat = function doFormat(substring, valueIndex, flags, minWidth, precision, type) {
	    var number, prefix, method, textTransform, value;

	    if (substring === '%%') {
	      return '%';
	    }

	    // parse flags
	    var leftJustify = false;
	    var positivePrefix = '';
	    var zeroPad = false;
	    var prefixBaseX = false;
	    var customPadChar = ' ';
	    var flagsl = flags.length;
	    var j;
	    for (j = 0; j < flagsl; j++) {
	      switch (flags.charAt(j)) {
	        case ' ':
	          positivePrefix = ' ';
	          break;
	        case '+':
	          positivePrefix = '+';
	          break;
	        case '-':
	          leftJustify = true;
	          break;
	        case "'":
	          customPadChar = flags.charAt(j + 1);
	          break;
	        case '0':
	          zeroPad = true;
	          customPadChar = '0';
	          break;
	        case '#':
	          prefixBaseX = true;
	          break;
	      }
	    }

	    // parameters may be null, undefined, empty-string or real valued
	    // we want to ignore null, undefined and empty-string values
	    if (!minWidth) {
	      minWidth = 0;
	    } else if (minWidth === '*') {
	      minWidth = +a[i++];
	    } else if (minWidth.charAt(0) === '*') {
	      minWidth = +a[minWidth.slice(1, -1)];
	    } else {
	      minWidth = +minWidth;
	    }

	    // Note: undocumented perl feature:
	    if (minWidth < 0) {
	      minWidth = -minWidth;
	      leftJustify = true;
	    }

	    if (!isFinite(minWidth)) {
	      throw new Error('sprintf: (minimum-)width must be finite');
	    }

	    if (!precision) {
	      precision = 'fFeE'.indexOf(type) > -1 ? 6 : type === 'd' ? 0 : undefined;
	    } else if (precision === '*') {
	      precision = +a[i++];
	    } else if (precision.charAt(0) === '*') {
	      precision = +a[precision.slice(1, -1)];
	    } else {
	      precision = +precision;
	    }

	    // grab value using valueIndex if required?
	    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

	    switch (type) {
	      case 's':
	        return _formatString(value + '', leftJustify, minWidth, precision, zeroPad, customPadChar);
	      case 'c':
	        return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
	      case 'b':
	        return _formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
	      case 'o':
	        return _formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
	      case 'x':
	        return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
	      case 'X':
	        return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
	      case 'u':
	        return _formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
	      case 'i':
	      case 'd':
	        number = +value || 0;
	        // Plain Math.round doesn't just truncate
	        number = Math.round(number - number % 1);
	        prefix = number < 0 ? '-' : positivePrefix;
	        value = prefix + _pad(String(Math.abs(number)), precision, '0', false);
	        return justify(value, prefix, leftJustify, minWidth, zeroPad);
	      case 'e':
	      case 'E':
	      case 'f': // @todo: Should handle locales (as per setlocale)
	      case 'F':
	      case 'g':
	      case 'G':
	        number = +value;
	        prefix = number < 0 ? '-' : positivePrefix;
	        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
	        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
	        value = prefix + Math.abs(number)[method](precision);
	        return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
	      default:
	        return substring;
	    }
	  };

	  return format.replace(regex, doFormat);
	};
	//# sourceMappingURL=sprintf.js.map

/***/ },
/* 9 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function vsprintf(format, args) {
	  //  discuss at: http://locutus.io/php/vsprintf/
	  // original by: ejsanders
	  //   example 1: vsprintf('%04d-%02d-%02d', [1988, 8, 1])
	  //   returns 1: '1988-08-01'

	  var sprintf = __webpack_require__(8);

	  return sprintf.apply(this, [format].concat(args));
	};
	//# sourceMappingURL=vsprintf.js.map

/***/ },
/* 10 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function round(value, precision, mode) {
	  //  discuss at: http://locutus.io/php/round/
	  // original by: Philip Peterson
	  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
	  //  revised by: T.Wild
	  //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
	  //    input by: Greenseed
	  //    input by: meo
	  //    input by: William
	  //    input by: Josep Sanz (http://www.ws3.es/)
	  // bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //      note 1: Great work. Ideas for improvement:
	  //      note 1: - code more compliant with developer guidelines
	  //      note 1: - for implementing PHP constant arguments look at
	  //      note 1: the pathinfo() function, it offers the greatest
	  //      note 1: flexibility & compatibility possible
	  //   example 1: round(1241757, -3)
	  //   returns 1: 1242000
	  //   example 2: round(3.6)
	  //   returns 2: 4
	  //   example 3: round(2.835, 2)
	  //   returns 3: 2.84
	  //   example 4: round(1.1749999999999, 2)
	  //   returns 4: 1.17
	  //   example 5: round(58551.799999999996, 2)
	  //   returns 5: 58551.8

	  var m, f, isHalf, sgn; // helper variables
	  // making sure precision is integer
	  precision |= 0;
	  m = Math.pow(10, precision);
	  value *= m;
	  // sign of the number
	  sgn = value > 0 | -(value < 0);
	  isHalf = value % 1 === 0.5 * sgn;
	  f = Math.floor(value);

	  if (isHalf) {
	    switch (mode) {
	      case 'PHP_ROUND_HALF_DOWN':
	        // rounds .5 toward zero
	        value = f + (sgn < 0);
	        break;
	      case 'PHP_ROUND_HALF_EVEN':
	        // rouds .5 towards the next even integer
	        value = f + f % 2 * sgn;
	        break;
	      case 'PHP_ROUND_HALF_ODD':
	        // rounds .5 towards the next odd integer
	        value = f + !(f % 2);
	        break;
	      default:
	        // rounds .5 away from zero
	        value = f + (sgn > 0);
	    }
	  }

	  return (isHalf ? value : Math.round(value)) / m;
	};
	//# sourceMappingURL=round.js.map

/***/ },
/* 11 */
/***/ function(module, exports) {

	'use strict';

	var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

	module.exports = function max() {
	  //  discuss at: http://locutus.io/php/max/
	  // original by: Onno Marsman (https://twitter.com/onnomarsman)
	  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
	  // improved by: Jack
	  //      note 1: Long code cause we're aiming for maximum PHP compatibility
	  //   example 1: max(1, 3, 5, 6, 7)
	  //   returns 1: 7
	  //   example 2: max([2, 4, 5])
	  //   returns 2: 5
	  //   example 3: max(0, 'hello')
	  //   returns 3: 0
	  //   example 4: max('hello', 0)
	  //   returns 4: 'hello'
	  //   example 5: max(-1, 'hello')
	  //   returns 5: 'hello'
	  //   example 6: max([2, 4, 8], [2, 5, 7])
	  //   returns 6: [2, 5, 7]

	  var ar;
	  var retVal;
	  var i = 0;
	  var n = 0;
	  var argv = arguments;
	  var argc = argv.length;
	  var _obj2Array = function _obj2Array(obj) {
	    if (Object.prototype.toString.call(obj) === '[object Array]') {
	      return obj;
	    } else {
	      var ar = [];
	      for (var i in obj) {
	        if (obj.hasOwnProperty(i)) {
	          ar.push(obj[i]);
	        }
	      }
	      return ar;
	    }
	  };
	  var _compare = function _compare(current, next) {
	    var i = 0;
	    var n = 0;
	    var tmp = 0;
	    var nl = 0;
	    var cl = 0;

	    if (current === next) {
	      return 0;
	    } else if ((typeof current === 'undefined' ? 'undefined' : _typeof(current)) === 'object') {
	      if ((typeof next === 'undefined' ? 'undefined' : _typeof(next)) === 'object') {
	        current = _obj2Array(current);
	        next = _obj2Array(next);
	        cl = current.length;
	        nl = next.length;
	        if (nl > cl) {
	          return 1;
	        } else if (nl < cl) {
	          return -1;
	        }
	        for (i = 0, n = cl; i < n; ++i) {
	          tmp = _compare(current[i], next[i]);
	          if (tmp === 1) {
	            return 1;
	          } else if (tmp === -1) {
	            return -1;
	          }
	        }
	        return 0;
	      }
	      return -1;
	    } else if ((typeof next === 'undefined' ? 'undefined' : _typeof(next)) === 'object') {
	      return 1;
	    } else if (isNaN(next) && !isNaN(current)) {
	      if (current === 0) {
	        return 0;
	      }
	      return current < 0 ? 1 : -1;
	    } else if (isNaN(current) && !isNaN(next)) {
	      if (next === 0) {
	        return 0;
	      }
	      return next > 0 ? 1 : -1;
	    }

	    if (next === current) {
	      return 0;
	    }

	    return next > current ? 1 : -1;
	  };

	  if (argc === 0) {
	    throw new Error('At least one value should be passed to max()');
	  } else if (argc === 1) {
	    if (_typeof(argv[0]) === 'object') {
	      ar = _obj2Array(argv[0]);
	    } else {
	      throw new Error('Wrong parameter count for max()');
	    }
	    if (ar.length === 0) {
	      throw new Error('Array must contain at least one element for max()');
	    }
	  } else {
	    ar = argv;
	  }

	  retVal = ar[0];
	  for (i = 1, n = ar.length; i < n; ++i) {
	    if (_compare(retVal, ar[i]) === 1) {
	      retVal = ar[i];
	    }
	  }

	  return retVal;
	};
	//# sourceMappingURL=max.js.map

/***/ },
/* 12 */
/***/ function(module, exports) {

	'use strict';

	var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

	module.exports = function min() {
	  //  discuss at: http://locutus.io/php/min/
	  // original by: Onno Marsman (https://twitter.com/onnomarsman)
	  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
	  // improved by: Jack
	  //      note 1: Long code cause we're aiming for maximum PHP compatibility
	  //   example 1: min(1, 3, 5, 6, 7)
	  //   returns 1: 1
	  //   example 2: min([2, 4, 5])
	  //   returns 2: 2
	  //   example 3: min(0, 'hello')
	  //   returns 3: 0
	  //   example 4: min('hello', 0)
	  //   returns 4: 'hello'
	  //   example 5: min(-1, 'hello')
	  //   returns 5: -1
	  //   example 6: min([2, 4, 8], [2, 5, 7])
	  //   returns 6: [2, 4, 8]

	  var ar;
	  var retVal;
	  var i = 0;
	  var n = 0;
	  var argv = arguments;
	  var argc = argv.length;
	  var _obj2Array = function _obj2Array(obj) {
	    if (Object.prototype.toString.call(obj) === '[object Array]') {
	      return obj;
	    }
	    var ar = [];
	    for (var i in obj) {
	      if (obj.hasOwnProperty(i)) {
	        ar.push(obj[i]);
	      }
	    }
	    return ar;
	  };

	  var _compare = function _compare(current, next) {
	    var i = 0;
	    var n = 0;
	    var tmp = 0;
	    var nl = 0;
	    var cl = 0;

	    if (current === next) {
	      return 0;
	    } else if ((typeof current === 'undefined' ? 'undefined' : _typeof(current)) === 'object') {
	      if ((typeof next === 'undefined' ? 'undefined' : _typeof(next)) === 'object') {
	        current = _obj2Array(current);
	        next = _obj2Array(next);
	        cl = current.length;
	        nl = next.length;
	        if (nl > cl) {
	          return 1;
	        } else if (nl < cl) {
	          return -1;
	        }
	        for (i = 0, n = cl; i < n; ++i) {
	          tmp = _compare(current[i], next[i]);
	          if (tmp === 1) {
	            return 1;
	          } else if (tmp === -1) {
	            return -1;
	          }
	        }
	        return 0;
	      }
	      return -1;
	    } else if ((typeof next === 'undefined' ? 'undefined' : _typeof(next)) === 'object') {
	      return 1;
	    } else if (isNaN(next) && !isNaN(current)) {
	      if (current === 0) {
	        return 0;
	      }
	      return current < 0 ? 1 : -1;
	    } else if (isNaN(current) && !isNaN(next)) {
	      if (next === 0) {
	        return 0;
	      }
	      return next > 0 ? 1 : -1;
	    }

	    if (next === current) {
	      return 0;
	    }

	    return next > current ? 1 : -1;
	  };

	  if (argc === 0) {
	    throw new Error('At least one value should be passed to min()');
	  } else if (argc === 1) {
	    if (_typeof(argv[0]) === 'object') {
	      ar = _obj2Array(argv[0]);
	    } else {
	      throw new Error('Wrong parameter count for min()');
	    }

	    if (ar.length === 0) {
	      throw new Error('Array must contain at least one element for min()');
	    }
	  } else {
	    ar = argv;
	  }

	  retVal = ar[0];

	  for (i = 1, n = ar.length; i < n; ++i) {
	    if (_compare(retVal, ar[i]) === -1) {
	      retVal = ar[i];
	    }
	  }

	  return retVal;
	};
	//# sourceMappingURL=min.js.map

/***/ },
/* 13 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function strip_tags(input, allowed) {
	  // eslint-disable-line camelcase
	  //  discuss at: http://locutus.io/php/strip_tags/
	  // original by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: Luke Godfrey
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  //    input by: Pul
	  //    input by: Alex
	  //    input by: Marc Palau
	  //    input by: Brett Zamir (http://brett-zamir.me)
	  //    input by: Bobby Drake
	  //    input by: Evertjan Garretsen
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: Eric Nagel
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: Tomasz Wesolowski
	  //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
	  //   example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>')
	  //   returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
	  //   example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>')
	  //   returns 2: '<p>Kevin van Zonneveld</p>'
	  //   example 3: strip_tags("<a href='http://kvz.io'>Kevin van Zonneveld</a>", "<a>")
	  //   returns 3: "<a href='http://kvz.io'>Kevin van Zonneveld</a>"
	  //   example 4: strip_tags('1 < 5 5 > 1')
	  //   returns 4: '1 < 5 5 > 1'
	  //   example 5: strip_tags('1 <br/> 1')
	  //   returns 5: '1  1'
	  //   example 6: strip_tags('1 <br/> 1', '<br>')
	  //   returns 6: '1 <br/> 1'
	  //   example 7: strip_tags('1 <br/> 1', '<br><br/>')
	  //   returns 7: '1 <br/> 1'

	  // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	  allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

	  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
	  var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

	  return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	    return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	  });
	};
	//# sourceMappingURL=strip_tags.js.map

/***/ },
/* 14 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function strtotime(text, now) {
	  //  discuss at: http://locutus.io/php/strtotime/
	  // original by: Caio Ariede (http://caioariede.com)
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: Caio Ariede (http://caioariede.com)
	  // improved by: A. Matías Quezada (http://amatiasq.com)
	  // improved by: preuter
	  // improved by: Brett Zamir (http://brett-zamir.me)
	  // improved by: Mirko Faber
	  //    input by: David
	  // bugfixed by: Wagner B. Soares
	  // bugfixed by: Artur Tchernychev
	  // bugfixed by: Stephan Bösch-Plepelits (http://github.com/plepe)
	  //      note 1: Examples all have a fixed timestamp to prevent
	  //      note 1: tests to fail because of variable time(zones)
	  //   example 1: strtotime('+1 day', 1129633200)
	  //   returns 1: 1129719600
	  //   example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200)
	  //   returns 2: 1130425202
	  //   example 3: strtotime('last month', 1129633200)
	  //   returns 3: 1127041200
	  //   example 4: strtotime('2009-05-04 08:30:00 GMT')
	  //   returns 4: 1241425800
	  //   example 5: strtotime('2009-05-04 08:30:00+00')
	  //   returns 5: 1241425800
	  //   example 6: strtotime('2009-05-04 08:30:00+02:00')
	  //   returns 6: 1241418600
	  //   example 7: strtotime('2009-05-04T08:30:00Z')
	  //   returns 7: 1241425800

	  var parsed;
	  var match;
	  var today;
	  var year;
	  var date;
	  var days;
	  var ranges;
	  var len;
	  var times;
	  var regex;
	  var i;
	  var fail = false;

	  if (!text) {
	    return fail;
	  }

	  // Unecessary spaces
	  text = text.replace(/^\s+|\s+$/g, '').replace(/\s{2,}/g, ' ').replace(/[\t\r\n]/g, '').toLowerCase();

	  // in contrast to php, js Date.parse function interprets:
	  // dates given as yyyy-mm-dd as in timezone: UTC,
	  // dates with "." or "-" as MDY instead of DMY
	  // dates with two-digit years differently
	  // etc...etc...
	  // ...therefore we manually parse lots of common date formats
	  var pattern = new RegExp(['^(\\d{1,4})', '([\\-\\.\\/:])', '(\\d{1,2})', '([\\-\\.\\/:])', '(\\d{1,4})', '(?:\\s(\\d{1,2}):(\\d{2})?:?(\\d{2})?)?', '(?:\\s([A-Z]+)?)?$'].join(''));
	  match = text.match(pattern);

	  if (match && match[2] === match[4]) {
	    if (match[1] > 1901) {
	      switch (match[2]) {
	        case '-':
	          // YYYY-M-D
	          if (match[3] > 12 || match[5] > 31) {
	            return fail;
	          }

	          return new Date(match[1], parseInt(match[3], 10) - 1, match[5], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	        case '.':
	          // YYYY.M.D is not parsed by strtotime()
	          return fail;
	        case '/':
	          // YYYY/M/D
	          if (match[3] > 12 || match[5] > 31) {
	            return fail;
	          }

	          return new Date(match[1], parseInt(match[3], 10) - 1, match[5], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	      }
	    } else if (match[5] > 1901) {
	      switch (match[2]) {
	        case '-':
	          // D-M-YYYY
	          if (match[3] > 12 || match[1] > 31) {
	            return fail;
	          }

	          return new Date(match[5], parseInt(match[3], 10) - 1, match[1], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	        case '.':
	          // D.M.YYYY
	          if (match[3] > 12 || match[1] > 31) {
	            return fail;
	          }

	          return new Date(match[5], parseInt(match[3], 10) - 1, match[1], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	        case '/':
	          // M/D/YYYY
	          if (match[1] > 12 || match[3] > 31) {
	            return fail;
	          }

	          return new Date(match[5], parseInt(match[1], 10) - 1, match[3], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	      }
	    } else {
	      switch (match[2]) {
	        case '-':
	          // YY-M-D
	          if (match[3] > 12 || match[5] > 31 || match[1] < 70 && match[1] > 38) {
	            return fail;
	          }

	          year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];
	          return new Date(year, parseInt(match[3], 10) - 1, match[5], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	        case '.':
	          // D.M.YY or H.MM.SS
	          if (match[5] >= 70) {
	            // D.M.YY
	            if (match[3] > 12 || match[1] > 31) {
	              return fail;
	            }

	            return new Date(match[5], parseInt(match[3], 10) - 1, match[1], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	          }
	          if (match[5] < 60 && !match[6]) {
	            // H.MM.SS
	            if (match[1] > 23 || match[3] > 59) {
	              return fail;
	            }

	            today = new Date();
	            return new Date(today.getFullYear(), today.getMonth(), today.getDate(), match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000;
	          }

	          // invalid format, cannot be parsed
	          return fail;
	        case '/':
	          // M/D/YY
	          if (match[1] > 12 || match[3] > 31 || match[5] < 70 && match[5] > 38) {
	            return fail;
	          }

	          year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];
	          return new Date(year, parseInt(match[1], 10) - 1, match[3], match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
	        case ':':
	          // HH:MM:SS
	          if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
	            return fail;
	          }

	          today = new Date();
	          return new Date(today.getFullYear(), today.getMonth(), today.getDate(), match[1] || 0, match[3] || 0, match[5] || 0) / 1000;
	      }
	    }
	  }

	  // other formats and "now" should be parsed by Date.parse()
	  if (text === 'now') {
	    return now === null || isNaN(now) ? new Date().getTime() / 1000 | 0 : now | 0;
	  }
	  if (!isNaN(parsed = Date.parse(text))) {
	    return parsed / 1000 | 0;
	  }
	  // Browsers !== Chrome have problems parsing ISO 8601 date strings, as they do
	  // not accept lower case characters, space, or shortened time zones.
	  // Therefore, fix these problems and try again.
	  // Examples:
	  //   2015-04-15 20:33:59+02
	  //   2015-04-15 20:33:59z
	  //   2015-04-15t20:33:59+02:00
	  pattern = new RegExp(['^([0-9]{4}-[0-9]{2}-[0-9]{2})', '[ t]', '([0-9]{2}:[0-9]{2}:[0-9]{2}(\\.[0-9]+)?)', '([\\+-][0-9]{2}(:[0-9]{2})?|z)'].join(''));
	  match = text.match(pattern);
	  if (match) {
	    // @todo: time zone information
	    if (match[4] === 'z') {
	      match[4] = 'Z';
	    } else if (match[4].match(/^([+-][0-9]{2})$/)) {
	      match[4] = match[4] + ':00';
	    }

	    if (!isNaN(parsed = Date.parse(match[1] + 'T' + match[2] + match[4]))) {
	      return parsed / 1000 | 0;
	    }
	  }

	  date = now ? new Date(now * 1000) : new Date();
	  days = {
	    'sun': 0,
	    'mon': 1,
	    'tue': 2,
	    'wed': 3,
	    'thu': 4,
	    'fri': 5,
	    'sat': 6
	  };
	  ranges = {
	    'yea': 'FullYear',
	    'mon': 'Month',
	    'day': 'Date',
	    'hou': 'Hours',
	    'min': 'Minutes',
	    'sec': 'Seconds'
	  };

	  function lastNext(type, range, modifier) {
	    var diff;
	    var day = days[range];

	    if (typeof day !== 'undefined') {
	      diff = day - date.getDay();

	      if (diff === 0) {
	        diff = 7 * modifier;
	      } else if (diff > 0 && type === 'last') {
	        diff -= 7;
	      } else if (diff < 0 && type === 'next') {
	        diff += 7;
	      }

	      date.setDate(date.getDate() + diff);
	    }
	  }

	  function process(val) {
	    // @todo: Reconcile this with regex using \s, taking into account
	    // browser issues with split and regexes
	    var splt = val.split(' ');
	    var type = splt[0];
	    var range = splt[1].substring(0, 3);
	    var typeIsNumber = /\d+/.test(type);
	    var ago = splt[2] === 'ago';
	    var num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

	    if (typeIsNumber) {
	      num *= parseInt(type, 10);
	    }

	    if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
	      return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
	    }

	    if (range === 'wee') {
	      return date.setDate(date.getDate() + num * 7);
	    }

	    if (type === 'next' || type === 'last') {
	      lastNext(type, range, num);
	    } else if (!typeIsNumber) {
	      return false;
	    }

	    return true;
	  }

	  times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' + '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' + '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
	  regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

	  match = text.match(new RegExp(regex, 'gi'));
	  if (!match) {
	    return fail;
	  }

	  for (i = 0, len = match.length; i < len; i++) {
	    if (!process(match[i])) {
	      return fail;
	    }
	  }

	  return date.getTime() / 1000;
	};
	//# sourceMappingURL=strtotime.js.map

/***/ },
/* 15 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function date(format, timestamp) {
	  //  discuss at: http://locutus.io/php/date/
	  // original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
	  // original by: gettimeofday
	  //    parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
	  // improved by: Kevin van Zonneveld (http://kvz.io)
	  // improved by: MeEtc (http://yass.meetcweb.com)
	  // improved by: Brad Touesnard
	  // improved by: Tim Wiel
	  // improved by: Bryan Elliott
	  // improved by: David Randall
	  // improved by: Theriault (https://github.com/Theriault)
	  // improved by: Theriault (https://github.com/Theriault)
	  // improved by: Brett Zamir (http://brett-zamir.me)
	  // improved by: Theriault (https://github.com/Theriault)
	  // improved by: Thomas Beaucourt (http://www.webapp.fr)
	  // improved by: JT
	  // improved by: Theriault (https://github.com/Theriault)
	  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
	  // improved by: Theriault (https://github.com/Theriault)
	  //    input by: Brett Zamir (http://brett-zamir.me)
	  //    input by: majak
	  //    input by: Alex
	  //    input by: Martin
	  //    input by: Alex Wilson
	  //    input by: Haravikk
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: majak
	  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
	  // bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // bugfixed by: omid (http://locutus.io/php/380:380#comment_137122)
	  // bugfixed by: Chris (http://www.devotis.nl/)
	  //      note 1: Uses global: locutus to store the default timezone
	  //      note 1: Although the function potentially allows timezone info
	  //      note 1: (see notes), it currently does not set
	  //      note 1: per a timezone specified by date_default_timezone_set(). Implementers might use
	  //      note 1: $locutus.currentTimezoneOffset and
	  //      note 1: $locutus.currentTimezoneDST set by that function
	  //      note 1: in order to adjust the dates in this function
	  //      note 1: (or our other date functions!) accordingly
	  //   example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400)
	  //   returns 1: '07:09:40 m is month'
	  //   example 2: date('F j, Y, g:i a', 1062462400)
	  //   returns 2: 'September 2, 2003, 12:26 am'
	  //   example 3: date('Y W o', 1062462400)
	  //   returns 3: '2003 36 2003'
	  //   example 4: var $x = date('Y m d', (new Date()).getTime() / 1000)
	  //   example 4: $x = $x + ''
	  //   example 4: var $result = $x.length // 2009 01 09
	  //   returns 4: 10
	  //   example 5: date('W', 1104534000)
	  //   returns 5: '52'
	  //   example 6: date('B t', 1104534000)
	  //   returns 6: '999 31'
	  //   example 7: date('W U', 1293750000.82); // 2010-12-31
	  //   returns 7: '52 1293750000'
	  //   example 8: date('W', 1293836400); // 2011-01-01
	  //   returns 8: '52'
	  //   example 9: date('W Y-m-d', 1293974054); // 2011-01-02
	  //   returns 9: '52 2011-01-02'
	  //        test: skip-1 skip-2 skip-5

	  var jsdate, f;
	  // Keep this here (works, but for code commented-out below for file size reasons)
	  // var tal= [];
	  var txtWords = ['Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	  // trailing backslash -> (dropped)
	  // a backslash followed by any character (including backslash) -> the character
	  // empty string -> empty string
	  var formatChr = /\\?(.?)/gi;
	  var formatChrCb = function formatChrCb(t, s) {
	    return f[t] ? f[t]() : s;
	  };
	  var _pad = function _pad(n, c) {
	    n = String(n);
	    while (n.length < c) {
	      n = '0' + n;
	    }
	    return n;
	  };
	  f = {
	    // Day
	    d: function d() {
	      // Day of month w/leading 0; 01..31
	      return _pad(f.j(), 2);
	    },
	    D: function D() {
	      // Shorthand day name; Mon...Sun
	      return f.l().slice(0, 3);
	    },
	    j: function j() {
	      // Day of month; 1..31
	      return jsdate.getDate();
	    },
	    l: function l() {
	      // Full day name; Monday...Sunday
	      return txtWords[f.w()] + 'day';
	    },
	    N: function N() {
	      // ISO-8601 day of week; 1[Mon]..7[Sun]
	      return f.w() || 7;
	    },
	    S: function S() {
	      // Ordinal suffix for day of month; st, nd, rd, th
	      var j = f.j();
	      var i = j % 10;
	      if (i <= 3 && parseInt(j % 100 / 10, 10) === 1) {
	        i = 0;
	      }
	      return ['st', 'nd', 'rd'][i - 1] || 'th';
	    },
	    w: function w() {
	      // Day of week; 0[Sun]..6[Sat]
	      return jsdate.getDay();
	    },
	    z: function z() {
	      // Day of year; 0..365
	      var a = new Date(f.Y(), f.n() - 1, f.j());
	      var b = new Date(f.Y(), 0, 1);
	      return Math.round((a - b) / 864e5);
	    },

	    // Week
	    W: function W() {
	      // ISO-8601 week number
	      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
	      var b = new Date(a.getFullYear(), 0, 4);
	      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
	    },

	    // Month
	    F: function F() {
	      // Full month name; January...December
	      return txtWords[6 + f.n()];
	    },
	    m: function m() {
	      // Month w/leading 0; 01...12
	      return _pad(f.n(), 2);
	    },
	    M: function M() {
	      // Shorthand month name; Jan...Dec
	      return f.F().slice(0, 3);
	    },
	    n: function n() {
	      // Month; 1...12
	      return jsdate.getMonth() + 1;
	    },
	    t: function t() {
	      // Days in month; 28...31
	      return new Date(f.Y(), f.n(), 0).getDate();
	    },

	    // Year
	    L: function L() {
	      // Is leap year?; 0 or 1
	      var j = f.Y();
	      return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
	    },
	    o: function o() {
	      // ISO-8601 year
	      var n = f.n();
	      var W = f.W();
	      var Y = f.Y();
	      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
	    },
	    Y: function Y() {
	      // Full year; e.g. 1980...2010
	      return jsdate.getFullYear();
	    },
	    y: function y() {
	      // Last two digits of year; 00...99
	      return f.Y().toString().slice(-2);
	    },

	    // Time
	    a: function a() {
	      // am or pm
	      return jsdate.getHours() > 11 ? 'pm' : 'am';
	    },
	    A: function A() {
	      // AM or PM
	      return f.a().toUpperCase();
	    },
	    B: function B() {
	      // Swatch Internet time; 000..999
	      var H = jsdate.getUTCHours() * 36e2;
	      // Hours
	      var i = jsdate.getUTCMinutes() * 60;
	      // Minutes
	      // Seconds
	      var s = jsdate.getUTCSeconds();
	      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
	    },
	    g: function g() {
	      // 12-Hours; 1..12
	      return f.G() % 12 || 12;
	    },
	    G: function G() {
	      // 24-Hours; 0..23
	      return jsdate.getHours();
	    },
	    h: function h() {
	      // 12-Hours w/leading 0; 01..12
	      return _pad(f.g(), 2);
	    },
	    H: function H() {
	      // 24-Hours w/leading 0; 00..23
	      return _pad(f.G(), 2);
	    },
	    i: function i() {
	      // Minutes w/leading 0; 00..59
	      return _pad(jsdate.getMinutes(), 2);
	    },
	    s: function s() {
	      // Seconds w/leading 0; 00..59
	      return _pad(jsdate.getSeconds(), 2);
	    },
	    u: function u() {
	      // Microseconds; 000000-999000
	      return _pad(jsdate.getMilliseconds() * 1000, 6);
	    },

	    // Timezone
	    e: function e() {
	      // Timezone identifier; e.g. Atlantic/Azores, ...
	      // The following works, but requires inclusion of the very large
	      // timezone_abbreviations_list() function.
	      /*              return that.date_default_timezone_get();
	       */
	      var msg = 'Not supported (see source code of date() for timezone on how to add support)';
	      throw new Error(msg);
	    },
	    I: function I() {
	      // DST observed?; 0 or 1
	      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
	      // If they are not equal, then DST is observed.
	      var a = new Date(f.Y(), 0);
	      // Jan 1
	      var c = Date.UTC(f.Y(), 0);
	      // Jan 1 UTC
	      var b = new Date(f.Y(), 6);
	      // Jul 1
	      // Jul 1 UTC
	      var d = Date.UTC(f.Y(), 6);
	      return a - c !== b - d ? 1 : 0;
	    },
	    O: function O() {
	      // Difference to GMT in hour format; e.g. +0200
	      var tzo = jsdate.getTimezoneOffset();
	      var a = Math.abs(tzo);
	      return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
	    },
	    P: function P() {
	      // Difference to GMT w/colon; e.g. +02:00
	      var O = f.O();
	      return O.substr(0, 3) + ':' + O.substr(3, 2);
	    },
	    T: function T() {
	      // The following works, but requires inclusion of the very
	      // large timezone_abbreviations_list() function.
	      /*              var abbr, i, os, _default;
	      if (!tal.length) {
	        tal = that.timezone_abbreviations_list();
	      }
	      if ($locutus && $locutus.default_timezone) {
	        _default = $locutus.default_timezone;
	        for (abbr in tal) {
	          for (i = 0; i < tal[abbr].length; i++) {
	            if (tal[abbr][i].timezone_id === _default) {
	              return abbr.toUpperCase();
	            }
	          }
	        }
	      }
	      for (abbr in tal) {
	        for (i = 0; i < tal[abbr].length; i++) {
	          os = -jsdate.getTimezoneOffset() * 60;
	          if (tal[abbr][i].offset === os) {
	            return abbr.toUpperCase();
	          }
	        }
	      }
	      */
	      return 'UTC';
	    },
	    Z: function Z() {
	      // Timezone offset in seconds (-43200...50400)
	      return -jsdate.getTimezoneOffset() * 60;
	    },

	    // Full Date/Time
	    c: function c() {
	      // ISO-8601 date.
	      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
	    },
	    r: function r() {
	      // RFC 2822
	      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
	    },
	    U: function U() {
	      // Seconds since UNIX epoch
	      return jsdate / 1000 | 0;
	    }
	  };

	  var _date = function _date(format, timestamp) {
	    jsdate = timestamp === undefined ? new Date() // Not provided
	    : timestamp instanceof Date ? new Date(timestamp) // JS Date()
	    : new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
	    ;
	    return format.replace(formatChr, formatChrCb);
	  };

	  return _date(format, timestamp);
	};
	//# sourceMappingURL=date.js.map

/***/ },
/* 16 */
/***/ function(module, exports) {

	'use strict';

	module.exports = function boolval(mixedVar) {
	  // original by: Will Rowe
	  //   example 1: boolval(true)
	  //   returns 1: true
	  //   example 2: boolval(false)
	  //   returns 2: false
	  //   example 3: boolval(0)
	  //   returns 3: false
	  //   example 4: boolval(0.0)
	  //   returns 4: false
	  //   example 5: boolval('')
	  //   returns 5: false
	  //   example 6: boolval('0')
	  //   returns 6: false
	  //   example 7: boolval([])
	  //   returns 7: false
	  //   example 8: boolval('')
	  //   returns 8: false
	  //   example 9: boolval(null)
	  //   returns 9: false
	  //   example 10: boolval(undefined)
	  //   returns 10: false
	  //   example 11: boolval('true')
	  //   returns 11: true

	  if (mixedVar === false) {
	    return false;
	  }

	  if (mixedVar === 0 || mixedVar === 0.0) {
	    return false;
	  }

	  if (mixedVar === '' || mixedVar === '0') {
	    return false;
	  }

	  if (Array.isArray(mixedVar) && mixedVar.length === 0) {
	    return false;
	  }

	  if (mixedVar === null || mixedVar === undefined) {
	    return false;
	  }

	  return true;
	};
	//# sourceMappingURL=boolval.js.map

/***/ },
/* 17 */
/***/ function(module, exports) {

	module.exports = function(Twig) {
	    'use strict';

	    Twig.Templates.registerLoader('ajax', function(location, params, callback, error_callback) {
	        var template,
	            xmlhttp,
	            precompiled = params.precompiled,
	            parser = this.parsers[params.parser] || this.parser.twig;

	        if (typeof XMLHttpRequest === "undefined") {
	            throw new Twig.Error('Unsupported platform: Unable to do ajax requests ' +
	                                 'because there is no "XMLHTTPRequest" implementation');
	        }

	        xmlhttp = new XMLHttpRequest();
	        xmlhttp.onreadystatechange = function() {
	            var data = null;

	            if(xmlhttp.readyState === 4) {
	                if (xmlhttp.status === 200 || (window.cordova && xmlhttp.status == 0)) {
	                    Twig.log.debug("Got template ", xmlhttp.responseText);

	                    if (precompiled === true) {
	                        data = JSON.parse(xmlhttp.responseText);
	                    } else {
	                        data = xmlhttp.responseText;
	                    }

	                    params.url = location;
	                    params.data = data;

	                    template = parser.call(this, params);

	                    if (typeof callback === 'function') {
	                        callback(template);
	                    }
	                } else {
	                    if (typeof error_callback === 'function') {
	                        error_callback(xmlhttp);
	                    }
	                }
	            }
	        };
	        xmlhttp.open("GET", location, !!params.async);
	        xmlhttp.send();

	        if (params.async) {
	            // TODO: return deferred promise
	            return true;
	        } else {
	            return template;
	        }
	    });

	};


/***/ },
/* 18 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = function(Twig) {
	    'use strict';

	    var fs, path;

	    try {
	    	// require lib dependencies at runtime
	    	fs = __webpack_require__(19);
	    	path = __webpack_require__(20);
	    } catch (e) {
	    	// NOTE: this is in a try/catch to avoid errors cross platform
	    }

	    Twig.Templates.registerLoader('fs', function(location, params, callback, error_callback) {
	        var template,
	            data = null,
	            precompiled = params.precompiled,
	            parser = this.parsers[params.parser] || this.parser.twig;

	        if (!fs || !path) {
	            throw new Twig.Error('Unsupported platform: Unable to load from file ' +
	                                 'because there is no "fs" or "path" implementation');
	        }

	        var loadTemplateFn = function(err, data) {
	            if (err) {
	                if (typeof error_callback === 'function') {
	                    error_callback(err);
	                }
	                return;
	            }

	            if (precompiled === true) {
	                data = JSON.parse(data);
	            }

	            params.data = data;
	            params.path = params.path || location;

	            // template is in data
	            template = parser.call(this, params);

	            if (typeof callback === 'function') {
	                callback(template);
	            }
	        };
	        params.path = params.path || location;

	        if (params.async) {
	            fs.stat(params.path, function (err, stats) {
	                if (err || !stats.isFile()) {
	                    if (typeof error_callback === 'function') {
	                        error_callback(new Twig.Error('Unable to find template file ' + params.path));
	                    }
	                    return;
	                }
	                fs.readFile(params.path, 'utf8', loadTemplateFn);
	            });
	            // TODO: return deferred promise
	            return true;
	        } else {
	            try {
	                if (!fs.statSync(params.path).isFile()) {
	                    throw new Twig.Error('Unable to find template file ' + params.path);
	                }
	            } catch (err) {
	                throw new Twig.Error('Unable to find template file ' + params.path);
	            }
	            data = fs.readFileSync(params.path, 'utf8');
	            loadTemplateFn(undefined, data);
	            return template
	        }
	    });

	};


/***/ },
/* 19 */
/***/ function(module, exports) {

	module.exports = __webpack_require__(93);

/***/ },
/* 20 */
/***/ function(module, exports) {

	module.exports = __webpack_require__(90);

/***/ },
/* 21 */
/***/ function(module, exports) {

	// ## twig.logic.js
	//
	// This file handles tokenizing, compiling and parsing logic tokens. {% ... %}
	module.exports = function (Twig) {
	    "use strict";

	    /**
	     * Namespace for logic handling.
	     */
	    Twig.logic = {};

	    /**
	     * Logic token types.
	     */
	    Twig.logic.type = {
	        if_:       'Twig.logic.type.if',
	        endif:     'Twig.logic.type.endif',
	        for_:      'Twig.logic.type.for',
	        endfor:    'Twig.logic.type.endfor',
	        else_:     'Twig.logic.type.else',
	        elseif:    'Twig.logic.type.elseif',
	        set:       'Twig.logic.type.set',
	        setcapture:'Twig.logic.type.setcapture',
	        endset:    'Twig.logic.type.endset',
	        filter:    'Twig.logic.type.filter',
	        endfilter: 'Twig.logic.type.endfilter',
	        shortblock: 'Twig.logic.type.shortblock',
	        block:     'Twig.logic.type.block',
	        endblock:  'Twig.logic.type.endblock',
	        extends_:  'Twig.logic.type.extends',
	        use:       'Twig.logic.type.use',
	        include:   'Twig.logic.type.include',
	        spaceless: 'Twig.logic.type.spaceless',
	        endspaceless: 'Twig.logic.type.endspaceless',
	        macro:     'Twig.logic.type.macro',
	        endmacro:  'Twig.logic.type.endmacro',
	        import_:   'Twig.logic.type.import',
	        from:      'Twig.logic.type.from',
	        embed:     'Twig.logic.type.embed',
	        endembed:  'Twig.logic.type.endembed'
	    };


	    // Regular expressions for handling logic tokens.
	    //
	    // Properties:
	    //
	    //      type:  The type of expression this matches
	    //
	    //      regex: A regular expression that matches the format of the token
	    //
	    //      next:  What logic tokens (if any) pop this token off the logic stack. If empty, the
	    //             logic token is assumed to not require an end tag and isn't push onto the stack.
	    //
	    //      open:  Does this tag open a logic expression or is it standalone. For example,
	    //             {% endif %} cannot exist without an opening {% if ... %} tag, so open = false.
	    //
	    //  Functions:
	    //
	    //      compile: A function that handles compiling the token into an output token ready for
	    //               parsing with the parse function.
	    //
	    //      parse:   A function that parses the compiled token into output (HTML / whatever the
	    //               template represents).
	    Twig.logic.definitions = [
	        {
	            /**
	             * If type logic tokens.
	             *
	             *  Format: {% if expression %}
	             */
	            type: Twig.logic.type.if_,
	            regex: /^if\s+([\s\S]+)$/,
	            next: [
	                Twig.logic.type.else_,
	                Twig.logic.type.elseif,
	                Twig.logic.type.endif
	            ],
	            open: true,
	            compile: function (token) {
	                var expression = token.match[1];
	                // Compile the expression.
	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;
	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this;

	                return Twig.expression.parseAsync.apply(this, [token.stack, context])
	                .then(function(result) {
	                    chain = true;

	                    if (Twig.lib.boolval(result)) {
	                        chain = false;

	                        return Twig.parseAsync.apply(that, [token.output, context]);
	                    }

	                    return '';
	                })
	                .then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * Else if type logic tokens.
	             *
	             *  Format: {% elseif expression %}
	             */
	            type: Twig.logic.type.elseif,
	            regex: /^elseif\s+([^\s].*)$/,
	            next: [
	                Twig.logic.type.else_,
	                Twig.logic.type.elseif,
	                Twig.logic.type.endif
	            ],
	            open: false,
	            compile: function (token) {
	                var expression = token.match[1];
	                // Compile the expression.
	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;
	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this;

	                return Twig.expression.parseAsync.apply(this, [token.stack, context])
	                .then(function(result) {
	                    if (chain && Twig.lib.boolval(result)) {
	                        chain = false;

	                        return Twig.parseAsync.apply(that, [token.output, context]);
	                    }

	                    return '';
	                })
	                .then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    }
	                });
	            }
	        },
	        {
	            /**
	             * Else if type logic tokens.
	             *
	             *  Format: {% elseif expression %}
	             */
	            type: Twig.logic.type.else_,
	            regex: /^else$/,
	            next: [
	                Twig.logic.type.endif,
	                Twig.logic.type.endfor
	            ],
	            open: false,
	            parse: function (token, context, chain) {
	                var promise = Twig.Promise.resolve('');

	                if (chain) {
	                    promise = Twig.parseAsync.apply(this, [token.output, context]);
	                }

	                return promise.then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * End if type logic tokens.
	             *
	             *  Format: {% endif %}
	             */
	            type: Twig.logic.type.endif,
	            regex: /^endif$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * For type logic tokens.
	             *
	             *  Format: {% for expression %}
	             */
	            type: Twig.logic.type.for_,
	            regex: /^for\s+([a-zA-Z0-9_,\s]+)\s+in\s+([^\s].*?)(?:\s+if\s+([^\s].*))?$/,
	            next: [
	                Twig.logic.type.else_,
	                Twig.logic.type.endfor
	            ],
	            open: true,
	            compile: function (token) {
	                var key_value = token.match[1],
	                    expression = token.match[2],
	                    conditional = token.match[3],
	                    kv_split = null;

	                token.key_var = null;
	                token.value_var = null;

	                if (key_value.indexOf(",") >= 0) {
	                    kv_split = key_value.split(',');
	                    if (kv_split.length === 2) {
	                        token.key_var = kv_split[0].trim();
	                        token.value_var = kv_split[1].trim();
	                    } else {
	                        throw new Twig.Error("Invalid expression in for loop: " + key_value);
	                    }
	                } else {
	                    token.value_var = key_value;
	                }

	                // Valid expressions for a for loop
	                //   for item     in expression
	                //   for key,item in expression

	                // Compile the expression.
	                token.expression = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                // Compile the conditional (if available)
	                if (conditional) {
	                    token.conditional = Twig.expression.compile.apply(this, [{
	                        type:  Twig.expression.type.expression,
	                        value: conditional
	                    }]).stack;
	                }

	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, continue_chain) {
	                // Parse expression
	                var output = [],
	                    len,
	                    index = 0,
	                    keyset,
	                    that = this,
	                    conditional = token.conditional,
	                    buildLoop = function(index, len) {
	                        var isConditional = conditional !== undefined;
	                        return {
	                            index: index+1,
	                            index0: index,
	                            revindex: isConditional?undefined:len-index,
	                            revindex0: isConditional?undefined:len-index-1,
	                            first: (index === 0),
	                            last: isConditional?undefined:(index === len-1),
	                            length: isConditional?undefined:len,
	                            parent: context
	                        };
	                    },
	                    // run once for each iteration of the loop
	                    loop = function(key, value) {
	                        var inner_context = Twig.ChildContext(context);

	                        inner_context[token.value_var] = value;

	                        if (token.key_var) {
	                            inner_context[token.key_var] = key;
	                        }

	                        // Loop object
	                        inner_context.loop = buildLoop(index, len);

	                        var promise = conditional === undefined ?
	                            Twig.Promise.resolve(true) :
	                            Twig.expression.parseAsync.apply(that, [conditional, inner_context]);

	                        promise.then(function(condition) {
	                            if (!condition)
	                                return;

	                            return Twig.parseAsync.apply(that, [token.output, inner_context])
	                            .then(function(o) {
	                                output.push(o);
	                                index += 1;
	                            });
	                        })
	                        .then(function() {
	                            // Delete loop-related variables from the context
	                            delete inner_context['loop'];
	                            delete inner_context[token.value_var];
	                            delete inner_context[token.key_var];

	                            // Merge in values that exist in context but have changed
	                            // in inner_context.
	                            Twig.merge(context, inner_context, true);
	                        });
	                    };


	                return Twig.expression.parseAsync.apply(this, [token.expression, context])
	                .then(function(result) {
	                    if (Twig.lib.is('Array', result)) {
	                        len = result.length;
	                        Twig.async.forEach(result, function (value) {
	                            var key = index;

	                            return loop(key, value);
	                        });
	                    } else if (Twig.lib.is('Object', result)) {
	                        if (result._keys !== undefined) {
	                            keyset = result._keys;
	                        } else {
	                            keyset = Object.keys(result);
	                        }
	                        len = keyset.length;
	                        Twig.forEach(keyset, function(key) {
	                            // Ignore the _keys property, it's internal to twig.js
	                            if (key === "_keys") return;

	                            loop(key,  result[key]);
	                        });
	                    }

	                    // Only allow else statements if no output was generated
	                    continue_chain = (output.length === 0);

	                    return {
	                        chain: continue_chain,
	                        output: Twig.output.apply(that, [output])
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * End if type logic tokens.
	             *
	             *  Format: {% endif %}
	             */
	            type: Twig.logic.type.endfor,
	            regex: /^endfor$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * Set type logic tokens.
	             *
	             *  Format: {% set key = expression %}
	             */
	            type: Twig.logic.type.set,
	            regex: /^set\s+([a-zA-Z0-9_,\s]+)\s*=\s*([\s\S]+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var key = token.match[1].trim(),
	                    expression = token.match[2],
	                    // Compile the expression.
	                    expression_stack  = Twig.expression.compile.apply(this, [{
	                        type:  Twig.expression.type.expression,
	                        value: expression
	                    }]).stack;

	                token.key = key;
	                token.expression = expression_stack;

	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, continue_chain) {
	                var key = token.key;

	                return Twig.expression.parseAsync.apply(this, [token.expression, context])
	                .then(function(value) {
	                    if (value === context) {
	                        /*  If storing the context in a variable, it needs to be a clone of the current state of context.
	                            Otherwise we have a context with infinite recursion.
	                            Fixes #341
	                        */
	                        value = Twig.lib.copy(value);
	                    }

	                    context[key] = value;

	                    return {
	                        chain: continue_chain,
	                        context: context
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * Set capture type logic tokens.
	             *
	             *  Format: {% set key %}
	             */
	            type: Twig.logic.type.setcapture,
	            regex: /^set\s+([a-zA-Z0-9_,\s]+)$/,
	            next: [
	                Twig.logic.type.endset
	            ],
	            open: true,
	            compile: function (token) {
	                var key = token.match[1].trim();

	                token.key = key;

	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, continue_chain) {
	                var that = this,
	                    key = token.key;

	                return Twig.parseAsync.apply(this, [token.output, context])
	                .then(function(value) {
	                    // set on both the global and local context
	                    that.context[key] = value;
	                    context[key] = value;

	                    return {
	                        chain: continue_chain,
	                        context: context
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * End set type block logic tokens.
	             *
	             *  Format: {% endset %}
	             */
	            type: Twig.logic.type.endset,
	            regex: /^endset$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * Filter logic tokens.
	             *
	             *  Format: {% filter upper %} or {% filter lower|escape %}
	             */
	            type: Twig.logic.type.filter,
	            regex: /^filter\s+(.+)$/,
	            next: [
	                Twig.logic.type.endfilter
	            ],
	            open: true,
	            compile: function (token) {
	                var expression = "|" + token.match[1].trim();
	                // Compile the expression.
	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;
	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                return Twig.parseAsync.apply(this, [token.output, context])
	                .then(function(unfiltered) {
	                    var stack = [{
	                        type: Twig.expression.type.string,
	                        value: unfiltered
	                    }].concat(token.stack);

	                    return Twig.expression.parseAsync.apply(that, [stack, context]);
	                })
	                .then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    }
	                });
	            }
	        },
	        {
	            /**
	             * End filter logic tokens.
	             *
	             *  Format: {% endfilter %}
	             */
	            type: Twig.logic.type.endfilter,
	            regex: /^endfilter$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * Block logic tokens.
	             *
	             *  Format: {% block title %}
	             */
	            type: Twig.logic.type.block,
	            regex: /^block\s+([a-zA-Z0-9_]+)$/,
	            next: [
	                Twig.logic.type.endblock
	            ],
	            open: true,
	            compile: function (token) {
	                token.block = token.match[1].trim();
	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this,
	                    block_output,
	                    output,
	                    promise = Twig.Promise.resolve(),
	                    isImported = Twig.indexOf(this.importedBlocks, token.block) > -1,
	                    hasParent = this.blocks[token.block] && Twig.indexOf(this.blocks[token.block], Twig.placeholders.parent) > -1;

	                // Don't override previous blocks unless they're imported with "use"
	                // Loops should be exempted as well.
	                if (this.blocks[token.block] === undefined || isImported || hasParent || context.loop || token.overwrite) {
	                    if (token.expression) {
	                        promise = Twig.expression.parseAsync.apply(this, [token.output, context])
	                        .then(function(value) {
	                            return Twig.expression.parseAsync.apply(that, [{
	                                type: Twig.expression.type.string,
	                                value: value
	                            }, context]);
	                        });
	                    } else {
	                        promise = Twig.parseAsync.apply(this, [token.output, context])
	                        .then(function(value) {
	                            return Twig.expression.parseAsync.apply(that, [{
	                                type: Twig.expression.type.string,
	                                value: value
	                            }, context]);
	                        });
	                    }

	                    promise = promise.then(function(block_output) {
	                        if (isImported) {
	                            // once the block is overridden, remove it from the list of imported blocks
	                            that.importedBlocks.splice(that.importedBlocks.indexOf(token.block), 1);
	                        }

	                        if (hasParent) {
	                            that.blocks[token.block] = Twig.Markup(that.blocks[token.block].replace(Twig.placeholders.parent, block_output));
	                        } else {
	                            that.blocks[token.block] = block_output;
	                        }

	                        that.originalBlockTokens[token.block] = {
	                            type: token.type,
	                            block: token.block,
	                            output: token.output,
	                            overwrite: true
	                        };
	                    });
	                }

	                return promise.then(function() {
	                    // Check if a child block has been set from a template extending this one.
	                    if (that.child.blocks[token.block]) {
	                        output = that.child.blocks[token.block];
	                    } else {
	                        output = that.blocks[token.block];
	                    }

	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * Block shorthand logic tokens.
	             *
	             *  Format: {% block title expression %}
	             */
	            type: Twig.logic.type.shortblock,
	            regex: /^block\s+([a-zA-Z0-9_]+)\s+(.+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                token.expression = token.match[2].trim();

	                token.output = Twig.expression.compile({
	                    type: Twig.expression.type.expression,
	                    value: token.expression
	                }).stack;

	                token.block = token.match[1].trim();
	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                return Twig.logic.handler[Twig.logic.type.block].parse.apply(this, arguments);
	            }
	        },
	        {
	            /**
	             * End block logic tokens.
	             *
	             *  Format: {% endblock %}
	             */
	            type: Twig.logic.type.endblock,
	            regex: /^endblock(?:\s+([a-zA-Z0-9_]+))?$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * Block logic tokens.
	             *
	             *  Format: {% extends "template.twig" %}
	             */
	            type: Twig.logic.type.extends_,
	            regex: /^extends\s+(.+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var expression = token.match[1].trim();
	                delete token.match;

	                token.stack   = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                return token;
	            },
	            parse: function (token, context, chain) {
	                var template,
	                    that = this,
	                    innerContext = Twig.ChildContext(context);

	                // Resolve filename
	                return Twig.expression.parseAsync.apply(this, [token.stack, context])
	                .then(function(file) {
	                    // Set parent template
	                    that.extend = file;

	                    if (file instanceof Twig.Template) {
	                        template = file;
	                    } else {
	                        // Import file
	                        template = that.importFile(file);
	                    }

	                    // Render the template in case it puts anything in its context
	                    return template.renderAsync(innerContext);
	                })
	                .then(function() {
	                    // Extend the parent context with the extended context
	                    Twig.lib.extend(context, innerContext);

	                    return {
	                        chain: chain,
	                        output: ''
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * Block logic tokens.
	             *
	             *  Format: {% use "template.twig" %}
	             */
	            type: Twig.logic.type.use,
	            regex: /^use\s+(.+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var expression = token.match[1].trim();
	                delete token.match;

	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this;

	                // Resolve filename
	                return Twig.expression.parseAsync.apply(this, [token.stack, context])
	                .then(function(file) {
	                    // Import blocks
	                    that.importBlocks(file);

	                    return {
	                        chain: chain,
	                        output: ''
	                    };
	                });
	            }
	        },
	        {
	            /**
	             * Block logic tokens.
	             *
	             *  Format: {% includes "template.twig" [with {some: 'values'} only] %}
	             */
	            type: Twig.logic.type.include,
	            regex: /^include\s+(.+?)(?:\s|$)(ignore missing(?:\s|$))?(?:with\s+([\S\s]+?))?(?:\s|$)(only)?$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var match = token.match,
	                    expression = match[1].trim(),
	                    ignoreMissing = match[2] !== undefined,
	                    withContext = match[3],
	                    only = ((match[4] !== undefined) && match[4].length);

	                delete token.match;

	                token.only = only;
	                token.ignoreMissing = ignoreMissing;

	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                if (withContext !== undefined) {
	                    token.withStack = Twig.expression.compile.apply(this, [{
	                        type:  Twig.expression.type.expression,
	                        value: withContext.trim()
	                    }]).stack;
	                }

	                return token;
	            },
	            parse: function (token, context, chain) {
	                // Resolve filename
	                var innerContext = {},
	                    i,
	                    template,
	                    that = this,
	                    promise = Twig.Promise.resolve();

	                if (!token.only) {
	                    innerContext = Twig.ChildContext(context);
	                }

	                if (token.withStack !== undefined) {
	                    promise = Twig.expression.parseAsync.apply(this, [token.withStack, context])
	                    .then(function(withContext) {
	                        for (i in withContext) {
	                            if (withContext.hasOwnProperty(i))
	                                innerContext[i] = withContext[i];
	                        }
	                    });
	                }

	                return promise
	                .then(function() {
	                    return Twig.expression.parseAsync.apply(that, [token.stack, context]);
	                })
	                .then(function(file) {
	                    if (file instanceof Twig.Template) {
	                        template = file;
	                    } else {
	                        // Import file
	                        try {
	                            template = that.importFile(file);
	                        } catch (err) {
	                            if (token.ignoreMissing) {
	                                return '';
	                            }

	                            throw err;
	                        }
	                    }

	                    return template.renderAsync(innerContext);
	                })
	                .then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },
	        {
	            type: Twig.logic.type.spaceless,
	            regex: /^spaceless$/,
	            next: [
	                Twig.logic.type.endspaceless
	            ],
	            open: true,

	            // Parse the html and return it without any spaces between tags
	            parse: function (token, context, chain) {
	                // Parse the output without any filter
	                return Twig.parseAsync.apply(this, [token.output, context])
	                .then(function(unfiltered) {
	                    var // A regular expression to find closing and opening tags with spaces between them
	                        rBetweenTagSpaces = />\s+</g,
	                        // Replace all space between closing and opening html tags
	                        output = unfiltered.replace(rBetweenTagSpaces,'><').trim();
	                        // Rewrap output as a Twig.Markup
	                        output = Twig.Markup(output);
	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },

	        // Add the {% endspaceless %} token
	        {
	            type: Twig.logic.type.endspaceless,
	            regex: /^endspaceless$/,
	            next: [ ],
	            open: false
	        },
	        {
	            /**
	             * Macro logic tokens.
	             *
	             * Format: {% maro input(name, value, type, size) %}
	             *
	             */
	            type: Twig.logic.type.macro,
	            regex: /^macro\s+([a-zA-Z0-9_]+)\s*\(\s*((?:[a-zA-Z0-9_]+(?:,\s*)?)*)\s*\)$/,
	            next: [
	                Twig.logic.type.endmacro
	            ],
	            open: true,
	            compile: function (token) {
	                var macroName = token.match[1],
	                    parameters = token.match[2].split(/[\s,]+/);

	                //TODO: Clean up duplicate check
	                for (var i=0; i<parameters.length; i++) {
	                    for (var j=0; j<parameters.length; j++){
	                        if (parameters[i] === parameters[j] && i !== j) {
	                            throw new Twig.Error("Duplicate arguments for parameter: "+ parameters[i]);
	                        }
	                    }
	                }

	                token.macroName = macroName;
	                token.parameters = parameters;

	                delete token.match;
	                return token;
	            },
	            parse: function (token, context, chain) {
	                var template = this;
	                this.macros[token.macroName] = function() {
	                    // Pass global context and other macros
	                    var macroContext = {
	                        _self: template.macros
	                    }
	                    // Add parameters from context to macroContext
	                    for (var i=0; i<token.parameters.length; i++) {
	                        var prop = token.parameters[i];
	                        if(typeof arguments[i] !== 'undefined') {
	                            macroContext[prop] = arguments[i];
	                        } else {
	                            macroContext[prop] = undefined;
	                        }
	                    }

	                    // Render
	                    return Twig.parseAsync.apply(template, [token.output, macroContext]);
	                };

	                return {
	                    chain: chain,
	                    output: ''
	                };

	            }
	        },
	        {
	            /**
	             * End macro logic tokens.
	             *
	             * Format: {% endmacro %}
	             */
	             type: Twig.logic.type.endmacro,
	             regex: /^endmacro$/,
	             next: [ ],
	             open: false
	        },
	        {
	            /*
	            * import logic tokens.
	            *
	            * Format: {% import "template.twig" as form %}
	            */
	            type: Twig.logic.type.import_,
	            regex: /^import\s+(.+)\s+as\s+([a-zA-Z0-9_]+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var expression = token.match[1].trim(),
	                    contextName = token.match[2].trim();
	                delete token.match;

	                token.expression = expression;
	                token.contextName = contextName;

	                token.stack = Twig.expression.compile.apply(this, [{
	                    type: Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this,
	                    output = { chain: chain, output: '' };

	                if (token.expression === '_self') {
	                    context[token.contextName] = this.macros;
	                    return Twig.Promise.resolve(output);
	                }

	                return Twig.expression.parseAsync.apply(this, [token.stack, context])
	                .then(function(file) {
	                    return that.importFile(file || token.expression);
	                })
	                .then(function(template) {
	                    context[token.contextName] = template.renderAsync({}, {output: 'macros'});

	                    return output;
	                });
	            }
	        },
	        {
	            /*
	            * from logic tokens.
	            *
	            * Format: {% from "template.twig" import func as form %}
	            */
	            type: Twig.logic.type.from,
	            regex: /^from\s+(.+)\s+import\s+([a-zA-Z0-9_, ]+)$/,
	            next: [ ],
	            open: true,
	            compile: function (token) {
	                var expression = token.match[1].trim(),
	                    macroExpressions = token.match[2].trim().split(/\s*,\s*/),
	                    macroNames = {};

	                for (var i=0; i<macroExpressions.length; i++) {
	                    var res = macroExpressions[i];

	                    // match function as variable
	                    var macroMatch = res.match(/^([a-zA-Z0-9_]+)\s+as\s+([a-zA-Z0-9_]+)$/);
	                    if (macroMatch) {
	                        macroNames[macroMatch[1].trim()] = macroMatch[2].trim();
	                    }
	                    else if (res.match(/^([a-zA-Z0-9_]+)$/)) {
	                        macroNames[res] = res;
	                    }
	                    else {
	                        // ignore import
	                    }

	                }

	                delete token.match;

	                token.expression = expression;
	                token.macroNames = macroNames;

	                token.stack = Twig.expression.compile.apply(this, [{
	                    type: Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                return token;
	            },
	            parse: function (token, context, chain) {
	                var that = this,
	                    promise = Twig.Promise.resolve(this.macros);

	                if (token.expression !== "_self") {
	                    promise = Twig.expression.parseAsync.apply(this, [token.stack, context])
	                    .then(function(file) {
	                        return that.importFile(file || token.expression);
	                    })
	                    .then(function(template) {
	                        return template.renderAsync({}, {output: 'macros'});
	                    });
	                }

	                return promise
	                .then(function(macros) {
	                    for (var macroName in token.macroNames) {
	                        if (macros.hasOwnProperty(macroName)) {
	                            context[token.macroNames[macroName]] = macros[macroName];
	                        }
	                    }

	                    return {
	                        chain: chain,
	                        output: ''
	                    }
	                });
	            }
	        },
	        {
	            /**
	             * The embed tag combines the behaviour of include and extends.
	             * It allows you to include another template's contents, just like include does.
	             *
	             *  Format: {% embed "template.twig" [with {some: 'values'} only] %}
	             */
	            type: Twig.logic.type.embed,
	            regex: /^embed\s+(.+?)(?:\s|$)(ignore missing(?:\s|$))?(?:with\s+([\S\s]+?))?(?:\s|$)(only)?$/,
	            next: [
	                Twig.logic.type.endembed
	            ],
	            open: true,
	            compile: function (token) {
	                var match = token.match,
	                    expression = match[1].trim(),
	                    ignoreMissing = match[2] !== undefined,
	                    withContext = match[3],
	                    only = ((match[4] !== undefined) && match[4].length);

	                delete token.match;

	                token.only = only;
	                token.ignoreMissing = ignoreMissing;

	                token.stack = Twig.expression.compile.apply(this, [{
	                    type:  Twig.expression.type.expression,
	                    value: expression
	                }]).stack;

	                if (withContext !== undefined) {
	                    token.withStack = Twig.expression.compile.apply(this, [{
	                        type:  Twig.expression.type.expression,
	                        value: withContext.trim()
	                    }]).stack;
	                }

	                return token;
	            },
	            parse: function (token, context, chain) {
	                // Resolve filename
	                var innerContext = {},
	                    that = this,
	                    i,
	                    template,
	                    promise = Twig.Promise.resolve();

	                if (!token.only) {
	                    for (i in context) {
	                        if (context.hasOwnProperty(i))
	                            innerContext[i] = context[i];
	                    }
	                }

	                if (token.withStack !== undefined) {
	                    promise = Twig.expression.parseAsync.apply(this, [token.withStack, context])
	                    .then(function(withContext) {
	                        for (i in withContext) {
	                            if (withContext.hasOwnProperty(i))
	                                innerContext[i] = withContext[i];
	                        }
	                    });
	                }

	                return promise.then(function() {
	                    return Twig.expression.parseAsync.apply(that, [token.stack, innerContext]);
	                })
	                .then(function(file) {
	                    if (file instanceof Twig.Template) {
	                        template = file;
	                    } else {
	                        // Import file
	                        try {
	                            template = that.importFile(file);
	                        } catch (err) {
	                            if (token.ignoreMissing) {
	                                return '';
	                            }

	                            throw err;
	                        }
	                    }

	                    // reset previous blocks
	                    that.blocks = {};

	                    // parse tokens. output will be not used
	                    return Twig.parseAsync.apply(that, [token.output, innerContext])
	                    .then(function() {
	                        // render tempalte with blocks defined in embed block
	                        return template.renderAsync(innerContext, {'blocks':that.blocks});
	                    });
	                })
	                .then(function(output) {
	                    return {
	                        chain: chain,
	                        output: output
	                    };
	                });
	            }
	        },
	        /* Add the {% endembed %} token
	         *
	         */
	        {
	            type: Twig.logic.type.endembed,
	            regex: /^endembed$/,
	            next: [ ],
	            open: false
	        }

	    ];


	    /**
	     * Registry for logic handlers.
	     */
	    Twig.logic.handler = {};

	    /**
	     * Define a new token type, available at Twig.logic.type.{type}
	     */
	    Twig.logic.extendType = function (type, value) {
	        value = value || ("Twig.logic.type" + type);
	        Twig.logic.type[type] = value;
	    };

	    /**
	     * Extend the logic parsing functionality with a new token definition.
	     *
	     * // Define a new tag
	     * Twig.logic.extend({
	     *     type: Twig.logic.type.{type},
	     *     // The pattern to match for this token
	     *     regex: ...,
	     *     // What token types can follow this token, leave blank if any.
	     *     next: [ ... ]
	     *     // Create and return compiled version of the token
	     *     compile: function(token) { ... }
	     *     // Parse the compiled token with the context provided by the render call
	     *     //   and whether this token chain is complete.
	     *     parse: function(token, context, chain) { ... }
	     * });
	     *
	     * @param {Object} definition The new logic expression.
	     */
	    Twig.logic.extend = function (definition) {

	        if (!definition.type) {
	            throw new Twig.Error("Unable to extend logic definition. No type provided for " + definition);
	        } else {
	            Twig.logic.extendType(definition.type);
	        }
	        Twig.logic.handler[definition.type] = definition;
	    };

	    // Extend with built-in expressions
	    while (Twig.logic.definitions.length > 0) {
	        Twig.logic.extend(Twig.logic.definitions.shift());
	    }

	    /**
	     * Compile a logic token into an object ready for parsing.
	     *
	     * @param {Object} raw_token An uncompiled logic token.
	     *
	     * @return {Object} A compiled logic token, ready for parsing.
	     */
	    Twig.logic.compile = function (raw_token) {
	        var expression = raw_token.value.trim(),
	            token = Twig.logic.tokenize.apply(this, [expression]),
	            token_template = Twig.logic.handler[token.type];

	        // Check if the token needs compiling
	        if (token_template.compile) {
	            token = token_template.compile.apply(this, [token]);
	            Twig.log.trace("Twig.logic.compile: ", "Compiled logic token to ", token);
	        }

	        return token;
	    };

	    /**
	     * Tokenize logic expressions. This function matches token expressions against regular
	     * expressions provided in token definitions provided with Twig.logic.extend.
	     *
	     * @param {string} expression the logic token expression to tokenize
	     *                (i.e. what's between {% and %})
	     *
	     * @return {Object} The matched token with type set to the token type and match to the regex match.
	     */
	    Twig.logic.tokenize = function (expression) {
	        var token = {},
	            token_template_type = null,
	            token_type = null,
	            token_regex = null,
	            regex_array = null,
	            regex = null,
	            match = null;

	        // Ignore whitespace around expressions.
	        expression = expression.trim();

	        for (token_template_type in Twig.logic.handler) {
	            if (Twig.logic.handler.hasOwnProperty(token_template_type)) {
	                // Get the type and regex for this template type
	                token_type = Twig.logic.handler[token_template_type].type;
	                token_regex = Twig.logic.handler[token_template_type].regex;

	                // Handle multiple regular expressions per type.
	                regex_array = [];
	                if (token_regex instanceof Array) {
	                    regex_array = token_regex;
	                } else {
	                    regex_array.push(token_regex);
	                }

	                // Check regular expressions in the order they were specified in the definition.
	                while (regex_array.length > 0) {
	                    regex = regex_array.shift();
	                    match = regex.exec(expression.trim());
	                    if (match !== null) {
	                        token.type  = token_type;
	                        token.match = match;
	                        Twig.log.trace("Twig.logic.tokenize: ", "Matched a ", token_type, " regular expression of ", match);
	                        return token;
	                    }
	                }
	            }
	        }

	        // No regex matches
	        throw new Twig.Error("Unable to parse '" + expression.trim() + "'");
	    };

	    /**
	     * Parse a logic token within a given context.
	     *
	     * What are logic chains?
	     *      Logic chains represent a series of tokens that are connected,
	     *          for example:
	     *          {% if ... %} {% else %} {% endif %}
	     *
	     *      The chain parameter is used to signify if a chain is open of closed.
	     *      open:
	     *          More tokens in this chain should be parsed.
	     *      closed:
	     *          This token chain has completed parsing and any additional
	     *          tokens (else, elseif, etc...) should be ignored.
	     *
	     * @param {Object} token The compiled token.
	     * @param {Object} context The render context.
	     * @param {boolean} chain Is this an open logic chain. If false, that means a
	     *                        chain is closed and no further cases should be parsed.
	     */
	    Twig.logic.parse = function (token, context, chain, allow_async) {
	        var output = '',
	            promise,
	            is_async = true,
	            token_template;

	        context = context || { };

	        Twig.log.debug("Twig.logic.parse: ", "Parsing logic token ", token);

	        token_template = Twig.logic.handler[token.type];

	        if (token_template.parse) {
	            output = token_template.parse.apply(this, [token, context, chain]);
	        }

	        promise = Twig.isPromise(output) ? output : Twig.Promise.resolve(output);

	        promise.then(function(o) {
	            is_async = false;
	            output = o;
	        });

	        if (allow_async)
	            return promise || Twig.Promise.resolve(output);

	        if (is_async)
	            throw new Twig.Error('You are using Twig.js in sync mode in combination with async extensions.');

	        return output;
	    };

	    return Twig;

	};


/***/ },
/* 22 */
/***/ function(module, exports) {

	module.exports = function(Twig) {
	    'use strict';

	    Twig.Templates.registerParser('source', function(params) {
	        return params.data || '';
	    });
	};


/***/ },
/* 23 */
/***/ function(module, exports) {

	module.exports = function(Twig) {
	    'use strict';

	    Twig.Templates.registerParser('twig', function(params) {
	        return new Twig.Template(params);
	    });
	};


/***/ },
/* 24 */
/***/ function(module, exports, __webpack_require__) {

	// ## twig.path.js
	//
	// This file handles path parsing
	module.exports = function (Twig) {
	    "use strict";

	    /**
	     * Namespace for path handling.
	     */
	    Twig.path = {};

	    /**
	     * Generate the canonical version of a url based on the given base path and file path and in
	     * the previously registered namespaces.
	     *
	     * @param  {string} template The Twig Template
	     * @param  {string} file     The file path, may be relative and may contain namespaces.
	     *
	     * @return {string}          The canonical version of the path
	     */
	     Twig.path.parsePath = function(template, file) {
	        var namespaces = null,
	            file = file || "";

	        if (typeof template === 'object' && typeof template.options === 'object') {
	            namespaces = template.options.namespaces;
	        }

	        if (typeof namespaces === 'object' && (file.indexOf('::') > 0) || file.indexOf('@') >= 0){
	            for (var k in namespaces){
	                if (namespaces.hasOwnProperty(k)) {
	                    file = file.replace(k + '::', namespaces[k]);
	                    file = file.replace('@' + k, namespaces[k]);
	                }
	            }

	            return file;
	        }

	        return Twig.path.relativePath(template, file);
	    };

	    /**
	     * Generate the relative canonical version of a url based on the given base path and file path.
	     *
	     * @param {Twig.Template} template The Twig.Template.
	     * @param {string} file The file path, relative to the base path.
	     *
	     * @return {string} The canonical version of the path.
	     */
	    Twig.path.relativePath = function(template, file) {
	        var base,
	            base_path,
	            sep_chr = "/",
	            new_path = [],
	            file = file || "",
	            val;

	        if (template.url) {
	            if (typeof template.base !== 'undefined') {
	                base = template.base + ((template.base.charAt(template.base.length-1) === '/') ? '' : '/');
	            } else {
	                base = template.url;
	            }
	        } else if (template.path) {
	            // Get the system-specific path separator
	            var path = __webpack_require__(20),
	                sep = path.sep || sep_chr,
	                relative = new RegExp("^\\.{1,2}" + sep.replace("\\", "\\\\"));
	            file = file.replace(/\//g, sep);

	            if (template.base !== undefined && file.match(relative) == null) {
	                file = file.replace(template.base, '');
	                base = template.base + sep;
	            } else {
	                base = path.normalize(template.path);
	            }

	            base = base.replace(sep+sep, sep);
	            sep_chr = sep;
	        } else if ((template.name || template.id) && template.method && template.method !== 'fs' && template.method !== 'ajax') {
	            // Custom registered loader
	            base = template.base || template.name || template.id;
	        } else {
	            throw new Twig.Error("Cannot extend an inline template.");
	        }

	        base_path = base.split(sep_chr);

	        // Remove file from url
	        base_path.pop();
	        base_path = base_path.concat(file.split(sep_chr));

	        while (base_path.length > 0) {
	            val = base_path.shift();
	            if (val == ".") {
	                // Ignore
	            } else if (val == ".." && new_path.length > 0 && new_path[new_path.length-1] != "..") {
	                new_path.pop();
	            } else {
	                new_path.push(val);
	            }
	        }

	        return new_path.join(sep_chr);
	    };

	    return Twig;
	};


/***/ },
/* 25 */
/***/ function(module, exports) {

	// ## twig.tests.js
	//
	// This file handles expression tests. (is empty, is not defined, etc...)
	module.exports = function (Twig) {
	    "use strict";
	    Twig.tests = {
	        empty: function(value) {
	            if (value === null || value === undefined) return true;
	            // Handler numbers
	            if (typeof value === "number") return false; // numbers are never "empty"
	            // Handle strings and arrays
	            if (value.length && value.length > 0) return false;
	            // Handle objects
	            for (var key in value) {
	                if (value.hasOwnProperty(key)) return false;
	            }
	            return true;
	        },
	        odd: function(value) {
	            return value % 2 === 1;
	        },
	        even: function(value) {
	            return value % 2 === 0;
	        },
	        divisibleby: function(value, params) {
	            return value % params[0] === 0;
	        },
	        defined: function(value) {
	            return value !== undefined;
	        },
	        none: function(value) {
	            return value === null;
	        },
	        'null': function(value) {
	            return this.none(value); // Alias of none
	        },
	        'same as': function(value, params) {
	            return value === params[0];
	        },
	        sameas: function(value, params) {
	            console.warn('`sameas` is deprecated use `same as`');
	            return Twig.tests['same as'](value, params);
	        },
	        iterable: function(value) {
	            return value && (Twig.lib.is("Array", value) || Twig.lib.is("Object", value));
	        }
	        /*
	        constant ?
	         */
	    };

	    Twig.test = function(test, value, params) {
	        if (!Twig.tests[test]) {
	            throw "Test " + test + " is not defined.";
	        }
	        return Twig.tests[test](value, params);
	    };

	    Twig.test.extend = function(test, definition) {
	        Twig.tests[test] = definition;
	    };

	    return Twig;
	};


/***/ },
/* 26 */
/***/ function(module, exports) {

	// ## twig.async.js
	//
	// This file handles asynchronous tasks within twig.
	module.exports = function (Twig) {
	    "use strict";

	    Twig.parseAsync = function (tokens, context) {
	        return Twig.parse.apply(this, [tokens, context, true]);
	    }

	    Twig.expression.parseAsync = function (tokens, context, tokens_are_parameters) {
	        return Twig.expression.parse.apply(this, [tokens, context, tokens_are_parameters, true]);
	    }

	    Twig.logic.parseAsync = function (token, context, chain) {
	        return Twig.logic.parse.apply(this, [token, context, chain, true]);
	    }

	    Twig.Template.prototype.renderAsync = function (context, params) {
	        return this.render(context, params, true);
	    }

	    Twig.async = {};

	    /**
	     * Checks for `thenable` objects
	     */
	    Twig.isPromise = function(obj) {
	        return obj && (typeof obj.then == 'function');
	    }

	    /**
	     * An alternate implementation of a Promise that does not fully follow
	     * the spec, but instead works fully synchronous while still being
	     * thenable.
	     *
	     * These promises can be mixed with regular promises at which point
	     * the synchronous behaviour is lost.
	     */
	    Twig.Promise = function(executor) {
	        // State
	        var state = 'unknown';
	        var value = null;
	        var handlers = null;

	        function changeState(newState, v) {
	            state = newState;
	            value = v;
	            notify();
	        };
	        function onResolve(v) { changeState('resolve', v); }
	        function onReject(e) { changeState('reject', e); }

	        function notify() {
	            if (!handlers) return;

	            Twig.forEach(handlers, function(h) {
	                append(h.resolve, h.reject);
	            });
	            handlers = null;
	        }

	        function append(onResolved, onRejected) {
	            var h = {
	                resolve: onResolved,
	                reject: onRejected
	            };

	            // The promise has yet to be rejected or resolved.
	            if (state == 'unknown') {
	                handlers = handlers || [];
	                return handlers.push(h);
	            }

	            // The state has been changed to either resolve, or reject
	            // which means we should call the handler.
	            if (h[state])
	                h[state](value);
	        }

	        function run(fn, resolve, reject) {
	            var done = false;
	            try {
	                fn(function(v) {
	                    if (done) return;
	                    done = true;
	                    resolve(v);
	                }, function(e) {
	                    if (done) return;
	                    done = true;
	                    reject(e);
	                });
	            } catch(e) {
	                done = true;
	                reject(e);
	            }
	        }

	        function ready(result) {
	            try {
	                if (!Twig.isPromise(result)) {
	                    return onResolve(result);
	                }

	                run(result.then.bind(result), ready, onReject);
	            } catch (e) {
	                onReject(e);
	            }
	        }

	        run(executor, ready, onReject);

	        return {
	            then: function(onResolved, onRejected) {
	                var hasResolved = typeof onResolved == 'function';
	                var hasRejected = typeof onRejected == 'function';

	                return new Twig.Promise(function(resolve, reject) {
	                    append(function(result) {
	                        if (hasResolved) {
	                            try {
	                                resolve(onResolved(result));
	                            } catch (e) {
	                                reject(e);
	                            }
	                        } else {
	                            resolve(result);
	                        }
	                    }, function(err) {
	                        if (hasRejected) {
	                            try {
	                                resolve(onRejected(err));
	                            } catch (e) {
	                                reject(e);
	                            }
	                        } else {
	                            reject(err);
	                        }
	                    });
	                });
	            },
	            catch: function(onRejected) {
	                return this.then(null, onRejected);
	            }
	        };
	    }

	    Twig.Promise.resolve = function(value) {
	        return new Twig.Promise(function(resolve) {
	            resolve(value);
	        });
	    };

	    Twig.Promise.reject = function(e) {
	        return new Twig.Promise(function(resolve, reject) {
	            reject(e);
	        });
	    };

	    Twig.Promise.all = function(promises) {
	        var results = [];

	        return Twig.async.forEach(promises, function(p, index) {
	            if (!Twig.isPromise(p)) {
	                results[index] = p;
	                return;
	            }

	            return p.then(function(v) {
	                results[index] = v;
	            });
	        })
	        .then(function() {
	            return results;
	        });
	    };

	    /**
	    * Go over each item in a fashion compatible with Twig.forEach,
	    * allow the function to return a promise or call the third argument
	    * to signal it is finished.
	    *
	    * Each item in the array will be called sequentially.
	    */
	    Twig.async.forEach = function forEachAsync(arr, callback) {
	        var arg_index = 0;
	        var callbacks = {};
	        var promise = new Twig.Promise(function(resolve, reject) {
	            callbacks = {
	                resolve: resolve,
	                reject: reject
	            };
	        });

	        function fail(err) {
	            callbacks.reject(err);
	        }

	        function next(value) {
	            if (!Twig.isPromise(value))
	                return iterate();

	            value.then(next, fail);
	        }

	        function iterate() {
	            var index = arg_index++;

	            if (index == arr.length) {
	                callbacks.resolve();
	                return;
	            }

	            next(callback(arr[index], index));
	        }

	        iterate();

	        return promise;
	    };

	    return Twig;

	};


/***/ },
/* 27 */
/***/ function(module, exports) {

	// ## twig.exports.js
	//
	// This file provides extension points and other hooks into the twig functionality.

	module.exports = function (Twig) {
	    "use strict";
	    Twig.exports = {
	        VERSION: Twig.VERSION
	    };

	    /**
	     * Create and compile a twig.js template.
	     *
	     * @param {Object} param Paramteres for creating a Twig template.
	     *
	     * @return {Twig.Template} A Twig template ready for rendering.
	     */
	    Twig.exports.twig = function twig(params) {
	        'use strict';
	        var id = params.id,
	            options = {
	                strict_variables: params.strict_variables || false,
	                // TODO: turn autoscape on in the next major version
	                autoescape: params.autoescape != null && params.autoescape || false,
	                allowInlineIncludes: params.allowInlineIncludes || false,
	                rethrow: params.rethrow || false,
	                namespaces: params.namespaces
	            };

	        if (Twig.cache && id) {
	            Twig.validateId(id);
	        }

	        if (params.debug !== undefined) {
	            Twig.debug = params.debug;
	        }
	        if (params.trace !== undefined) {
	            Twig.trace = params.trace;
	        }

	        if (params.data !== undefined) {
	            return Twig.Templates.parsers.twig({
	                data: params.data,
	                path: params.hasOwnProperty('path') ? params.path : undefined,
	                module: params.module,
	                id:   id,
	                options: options
	            });

	        } else if (params.ref !== undefined) {
	            if (params.id !== undefined) {
	                throw new Twig.Error("Both ref and id cannot be set on a twig.js template.");
	            }
	            return Twig.Templates.load(params.ref);
	        
	        } else if (params.method !== undefined) {
	            if (!Twig.Templates.isRegisteredLoader(params.method)) {
	                throw new Twig.Error('Loader for "' + params.method + '" is not defined.');
	            }
	            return Twig.Templates.loadRemote(params.name || params.href || params.path || id || undefined, {
	                id: id,
	                method: params.method,
	                parser: params.parser || 'twig',
	                base: params.base,
	                module: params.module,
	                precompiled: params.precompiled,
	                async: params.async,
	                options: options

	            }, params.load, params.error);

	        } else if (params.href !== undefined) {
	            return Twig.Templates.loadRemote(params.href, {
	                id: id,
	                method: 'ajax',
	                parser: params.parser || 'twig',
	                base: params.base,
	                module: params.module,
	                precompiled: params.precompiled,
	                async: params.async,
	                options: options

	            }, params.load, params.error);

	        } else if (params.path !== undefined) {
	            return Twig.Templates.loadRemote(params.path, {
	                id: id,
	                method: 'fs',
	                parser: params.parser || 'twig',
	                base: params.base,
	                module: params.module,
	                precompiled: params.precompiled,
	                async: params.async,
	                options: options

	            }, params.load, params.error);
	        }
	    };

	    // Extend Twig with a new filter.
	    Twig.exports.extendFilter = function(filter, definition) {
	        Twig.filter.extend(filter, definition);
	    };

	    // Extend Twig with a new function.
	    Twig.exports.extendFunction = function(fn, definition) {
	        Twig._function.extend(fn, definition);
	    };

	    // Extend Twig with a new test.
	    Twig.exports.extendTest = function(test, definition) {
	        Twig.test.extend(test, definition);
	    };

	    // Extend Twig with a new definition.
	    Twig.exports.extendTag = function(definition) {
	        Twig.logic.extend(definition);
	    };

	    // Provide an environment for extending Twig core.
	    // Calls fn with the internal Twig object.
	    Twig.exports.extend = function(fn) {
	        fn(Twig);
	    };


	    /**
	     * Provide an extension for use with express 2.
	     *
	     * @param {string} markup The template markup.
	     * @param {array} options The express options.
	     *
	     * @return {string} The rendered template.
	     */
	    Twig.exports.compile = function(markup, options) {
	        var id = options.filename,
	            path = options.filename,
	            template;

	        // Try to load the template from the cache
	        template = new Twig.Template({
	            data: markup,
	            path: path,
	            id: id,
	            options: options.settings['twig options']
	        }); // Twig.Templates.load(id) ||

	        return function(context) {
	            return template.render(context);
	        };
	    };

	    /**
	     * Provide an extension for use with express 3.
	     *
	     * @param {string} path The location of the template file on disk.
	     * @param {Object|Function} The options or callback.
	     * @param {Function} fn callback.
	     * 
	     * @throws Twig.Error
	     */
	    Twig.exports.renderFile = function(path, options, fn) {
	        // handle callback in options
	        if (typeof options === 'function') {
	            fn = options;
	            options = {};
	        }

	        options = options || {};

	        var settings = options.settings || {};

	        var params = {
	            path: path,
	            base: settings.views,
	            load: function(template) {
	                // render and return template as a simple string, see https://github.com/twigjs/twig.js/pull/348 for more information
	                fn(null, '' + template.render(options));
	            }
	        };

	        // mixin any options provided to the express app.
	        var view_options = settings['twig options'];

	        if (view_options) {
	            for (var option in view_options) {
	                if (view_options.hasOwnProperty(option)) {
	                    params[option] = view_options[option];
	                }
	            }
	        }

	        Twig.exports.twig(params);
	    };

	    // Express 3 handler
	    Twig.exports.__express = Twig.exports.renderFile;

	    /**
	     * Shoud Twig.js cache templates.
	     * Disable during development to see changes to templates without
	     * reloading, and disable in production to improve performance.
	     *
	     * @param {boolean} cache
	     */
	    Twig.exports.cache = function(cache) {
	        Twig.cache = cache;
	    };

	    //We need to export the path module so we can effectively test it
	    Twig.exports.path = Twig.path;

	    //Export our filters.
	    //Resolves #307
	    Twig.exports.filters = Twig.filters;

	    return Twig;
	};


/***/ }
/******/ ])
});
;
/* WEBPACK VAR INJECTION */}.call(exports, "/"))

/***/ }),
/* 264 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(97);
module.exports = __webpack_require__(98);


/***/ })
],[264]);
//# sourceMappingURL=bundle.js.map