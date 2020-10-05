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