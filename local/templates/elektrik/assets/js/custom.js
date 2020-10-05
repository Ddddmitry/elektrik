function debounce(f, t) {
    return function (args) {
        let previousCall = this.lastCall;
        this.lastCall = Date.now();
        if (previousCall && ((this.lastCall - previousCall) <= t)) {
            clearTimeout(this.lastCallTimer);
        }
        this.lastCallTimer = setTimeout(() => f(args), t);
    }
}

$(window).on('load', function () {
    forResize();
});

document.addEventListener("DOMContentLoaded", function(event) {

    loadSvg();

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

    let $formFilter = $('[data-contractors-filter]');
    let $formFilterDetails = $('[data-contractors-filter-details]');
    let $contractorsList = $('.js-marketplace-list');

    let $contractorsSearchService = $('input[name=contractors-search]');
    let $serviceSearchService = $('[data-service-search]');
    let $contractorsSearchLocation = $('input[name=contractors-location]');
    let $marketplaceDetail = $('.js-contractor-detail-viewed');
    let $showContactsButton = $('.js-marketDetail-about-helper-contactsButton');
    let $snowContactsButtonMobile = $('.js-marketDetail-about-helper-contactsButton_mobile');

    let $formEventsFilter = $('[data-events-filter]');
    let $formPoffersFilter = $('[data-poffers-filter]');
    let $eventsList = $('.js-events-list-container');
    let $poffersList = $('.js-poffers-list-container');
    let $newsList = $('.js-news-list-container');

    let $formEducationsFilter = $('[data-educations-filter]');
    let $educationsList = $('.js-educations-list-container');

    let $orderStreetLocation = $('[data-order-street]');
    let $orderService = $('[data-order-service]');

    let $formOrder = $('[data-form-order]');
    let $formDistributor = $('[data-form-distributor]');

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
                    $contractorsList.data('page', 1);
                    $('.js-contractors-filter-clean').addClass('show');
                }
            });
        } else {
            window.location = action + "?" + fields;
        }
    }
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
    function applyEventsFilter($formEventsFilter){
        var action = $formEventsFilter.attr('action') ? $formEventsFilter.attr('action') : window.location.href;
        var fields = $formEventsFilter.serializeArray();
        fields = $.param(fields);
        console.log(fields);
        //fields += "&" + $formFilterDetails.serialize();
        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $formEventsFilter.attr('data-filter-params', fields);
                $eventsList.html(data);
                $eventsList.data('page', 1);
            }
        });

    }
    function applyPoffersFilter($formPoffersFilter){
        var action = $formPoffersFilter.attr('action') ? $formPoffersFilter.attr('action') : window.location.href;
        var fields = $formPoffersFilter.serializeArray();
        fields = $.param(fields);
        console.log(fields);
        //fields += "&" + $formFilterDetails.serialize();
        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $formPoffersFilter.attr('data-filter-params', fields);
                $poffersList.html(data);
                $poffersList.data('page', 1);
            }
        });

    }
    function applyEducationsFilter($formEducationsFilter){
        var action = $formEducationsFilter.attr('action') ? $formEducationsFilter.attr('action') : window.location.href;
        var fields = $formEducationsFilter.serializeArray();
        fields = $.param(fields);
        //fields += "&clear_cache=Y";
        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $formEducationsFilter.attr('data-filter-params', fields);
                $educationsList.html(data);
                $educationsList.data('page', 1);
            }
        });
    }

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

            }
        });
    });
    $('.js-events-more').on('click', function (e) {
        e.preventDefault();
        var nextPage = $eventsList.data('page') + 1;
        var action = $formEventsFilter.attr('action');
        var fields = $formEventsFilter.attr('data-filter-params');
        fields = fields + (fields ? '&' : '') + $formEventsFilter.attr('data-sort-params');
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage + '&filter=Y';

        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $eventsList.data('page', nextPage);
                $eventsList.append(data);
            }
        });
    });

    $('.js-news-more').on('click', function (e) {
        e.preventDefault();
        var nextPage = $newsList.data('page') + 1;
        var action = $newsList.data('action');
        var fields = "";
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage + '&filter=Y';

        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $newsList.data('page', nextPage);
                $newsList.append(data);
            }
        });
    });
    $('.js-poffers-more').on('click', function (e) {
        e.preventDefault();
        var nextPage = $poffersList.data('page') + 1;
        var action = $formPoffersFilter.attr('action');
        var fields = $formPoffersFilter.attr('data-filter-params');
        fields = fields + (fields ? '&' : '') + $formPoffersFilter.attr('data-sort-params');
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage + '&filter=Y';

        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $poffersList.data('page', nextPage);
                $poffersList.append(data);
            }
        });
    });
    $('.js-educations-more').on('click', function (e) {
        e.preventDefault();
        var nextPage = $educationsList.data('page') + 1;
        var action = $formEducationsFilter.attr('action');
        var fields = $formEducationsFilter.attr('data-filter-params');
        fields = fields + (fields ? '&' : '') + $formEducationsFilter.attr('data-sort-params');
        fields = fields + (fields ? '&' : '') + 'page=' + nextPage + '&filter=Y';

        $.ajax({
            url: action,
            data: fields,
            type: 'GET',
            success: function success(data) {
                $educationsList.data('page', nextPage);
                $educationsList.append(data);
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
                        if(data.result['SHOW_SMS_FIELD']) {
                            let code = data.result["SMS_CODE"] || "";
                            $formRestore.html("");
                            $formRestore.append('<input type="hidden" name="SIGNED_DATA" value="'+data.result["SIGNED_DATA"]+'" />');
                            $formRestore.append('<input type="hidden" name="code_submit_button" value="Y" />');
                            $formRestore.append('<input type="hidden" name="phone" value="'+data.result["PHONE"]+'" />');
                            $formRestore.append('<div class="formGroup required"><div class="formGroup-inner"><label for="<?=sms-code">Код из смс</label><input type="text" value="'+code+'" id="sms-code" name="SMS_CODE" required placeholder="Код из смс"  autocomplete="NoAutocomplete"><div class="formGroup__error js-errorInput"></div></div></div>');
                            $formRestore.append('<div class="formGroup formGroup__bottom"><div class="formGroup-inner"><button type="submit" name="code_submit_button2" value="Y">Подтвердить</button></div></div>');

                        }else if(data.result){
                            $formRestore.html("");
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
                            window.location.href = data.link;
                            //window.location.reload();
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
                            if(data.result['SHOW_SMS_FIELD']){
                                let code = data.result["SMS_CODE"] || "";
                                $(".js-tabs-buttons").hide();
                                $currentFormRegister.html("");
                                $currentFormRegister.append('<input type="hidden" name="SIGNED_DATA" value="'+data.result["SIGNED_DATA"]+'" />');
                                $currentFormRegister.append('<input type="hidden" name="code_submit_button" value="Y" />');
                                $currentFormRegister.append('<input type="hidden" name="registration-type" value="'+data.result["TYPE"]+'" />');
                                $currentFormRegister.append('<input type="hidden" name="registration-name" value="'+data.result["NAME"]+'" />');
                                $currentFormRegister.append('<div class="formGroup required"><div class="formGroup-inner"><label for="<?=sms-code">Код из смс</label><input type="text" value="'+code+'" id="sms-code" name="SMS_CODE" required placeholder="Код из смс"  autocomplete="NoAutocomplete"><div class="formGroup__error js-errorInput"></div></div></div>');
                                $currentFormRegister.append('<div class="formGroup formGroup__bottom"><div class="formGroup-inner"><button type="submit" name="code_submit_button2" value="Y">Подтвердить</button></div></div>');

                            }
                            else if (data.result['USER_TYPE'] === 'contractor') {
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

    //let showMore = document.querySelector(".js-show-more");
    let showMore = document.getElementById("js-show-more");

    if (showMore) {
        $(".js-filter-container").on("click",".js-show-more",function(e){
            e.preventDefault();
            let curThis = this;
            let curPage = curThis.getAttribute("data-curpage");
            let target = curThis.getAttribute("data-target");
            let totalPages = curThis.getAttribute("data-total-pages");
            let url = window.location.href;
            if(curPage < totalPages){
                let needPage = ++curPage;
                if(target != undefined){
                    $.ajax({
                        url: url,
                        cache: false,
                        data: {page: needPage},
                        success: function (html) {
                            $(target).append(html);
                            if(needPage == totalPages) {
                                curThis.remove();
                            }else{
                                curThis.setAttribute("data-curpage",needPage);
                            }
                        }
                    });
                }
            }
        });

    }


    $('.js-filter-type').click(function () {
        if($(this).hasClass('is-active')){
            $(this).removeClass('is-active');
            $("input[name='type']").val("");
        }
        else{
            $('.js-filter-type').removeClass("is-active");
            $(this).addClass('is-active');
            $("input[name='type']").val($(this).attr("data-type"));
        }

        if($formEventsFilter.length)
            applyEventsFilter($formEventsFilter);
        if($formEducationsFilter.length)
            applyEducationsFilter($formEducationsFilter);

        return false;

    });
    $('.js-filter-poffers-type').click(function () {

        let type = [];
        $(this).toggleClass("is-active");
        $('.js-filter-poffers-type').each(function (e) {
            if($(this).hasClass("is-active")){
                type.push($(this).data('type'));
            }
        });
        $(this).closest("[data-poffers-filter]").find("[data-input-type]").val(type.join(","));

        if($formPoffersFilter.length)
            applyPoffersFilter($formPoffersFilter);

        return false;

    });


    $('.js-filter-theme').click(function () {

        if($(this).hasClass('is-active')){
            $(this).removeClass('is-active');
            $("input[name='theme']").val("");
        }
        else{
            $('.js-filter-theme').removeClass("is-active");
            $(this).addClass('is-active');
            $("input[name='theme']").val($(this).attr("data-theme"));
        }
        applyEducationsFilter($formEducationsFilter);
        return false;

    });
    $('.js-filter-format').click(function () {

        if($(this).hasClass('is-active')){
            $(this).removeClass('is-active');
            $("input[name='format']").val("");
        }
        else{
            $('.js-filter-format').removeClass("is-active");
            $(this).addClass('is-active');
            $("input[name='format']").val($(this).attr("data-format"));
        }
        applyEducationsFilter($formEducationsFilter);
        return false;

    });





    $("*[data-phone]").inputmask({
        mask: '+7 (999) 999-99-99',
        showMaskOnHover: false,
        clearIncomplete: true
    });

    $("*[data-number]").inputmask("decimal",{
        showMaskOnHover: false,
        clearIncomplete: true,
        rightAlign: false,
    });

    // Всплывающие подсказки при заполнении поля выбора местоположения пользователя
    let $inputSearchCity = $('#search_city-search');
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
                        document.location.reload(true);
                        //window.location = window.location.href.split("?")[0];
                    } else if (data.error) {
                        var errorBlock = $('.js-form-error');
                        errorBlock.html(data.error);
                    }
                    $formSearchCity.find('#city-change-submit').attr('disabled', false);
                }
            });
        });
    }

    // Всплывающие подсказки при заполнении поля "Услуга и специалист" в форме поиска
    if ($contractorsSearchService.length) {
        $contractorsSearchService.on('keyup', function (e) {
            e.preventDefault();
            debounce(function () {
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
            },600)();
        });
    }

    if ($serviceSearchService.length) {
        $serviceSearchService.on('keyup', function (e) {
            e.preventDefault();
            debounce(function () {
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
                        var popup = $(e.target).closest('.js-hero__form-item').find('.js-popupSearchResult');
                        popup.html('');
                        if (data.result.CATEGORIES) {
                            for (var i = 0; i < data.result.CATEGORIES.length; i++) {
                                if (data.result.CATEGORIES[i].ITEMS.length != 0) {
                                    popup.append('<div class="popupSearchResult__title popupSearchResult__block js-popupSearchResult__title">' + data.result.CATEGORIES[i].TITLE + '</div>');
                                }
                                for (var i2 = 0; i2 < data.result.CATEGORIES[i].ITEMS.length; i2++) {
                                    popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result_service" data-result="' + data.result.CATEGORIES[i].ITEMS[i2].ID + '">' + data.result.CATEGORIES[i].ITEMS[i2].NAME + '</div>');
                                }
                            }
                        }
                        popup.slideDown(200);
                    }
                });
            },600)();
        });
    }



    // Всплывающие подсказки при заполнении поля местоположения в форме поиска
    if ($contractorsSearchLocation.length) {
        $contractorsSearchLocation.on('keyup', function (e) {
            e.preventDefault();

            debounce(function () {
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
                            var popup = $(e.target).closest('.formGroup').find('.js-popupSearchResult:first');
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
            },600)();
        });
    }

    // Всплывающие подсказки при заполнении улицы в форме заявки
    if ($orderStreetLocation.length) {
        $orderStreetLocation.on('keyup', function (e) {
            e.preventDefault();

            debounce(function () {
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
                        url: '/api/order.location.suggest/',
                        data: JSON.stringify(data),
                        type: 'POST',
                        contentType: 'application/json',
                        success: function success(data) {
                            var popup = $(e.target).closest(".js-popupSearchResult-input-wrap").find('.js-popupSearchResult');
                            popup.html('');
                            if (data.result != undefined) {
                                for (var i = 0; i < data.result.length; i++) {
                                    popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result_order" data-result="' + data.result[i]['id'] + '">' + data.result[i]['street_with_type'] + '</div>');
                                }
                                popup.slideDown(200);
                            }
                        }
                    });
                }
            },600)();
        });
    }

    // Всплывающие подсказки при заполнении поля "Услуга" в форме заявки
    if ($orderService.length) {
        $orderService.on('keyup', function (e) {
            e.preventDefault();
            debounce(function () {
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
                        var popup = $(e.target).closest('.js-popupSearchResult-input-wrap').find('.js-popupSearchResult');
                        popup.html('');
                        if (data.result.CATEGORIES) {
                            for (var i = 0; i < data.result.CATEGORIES.length; i++) {
                                if (data.result.CATEGORIES[i].ITEMS.length != 0) {
                                    popup.append('<div class="popupSearchResult__title popupSearchResult__block js-popupSearchResult__title">' + data.result.CATEGORIES[i].TITLE + '</div>');
                                }
                                for (var i2 = 0; i2 < data.result.CATEGORIES[i].ITEMS.length; i2++) {
                                    popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result_order" data-result="' + data.result.CATEGORIES[i].ITEMS[i2].ID + '">' + data.result.CATEGORIES[i].ITEMS[i2].NAME + '</div>');
                                }
                            }
                        }
                        popup.slideDown(200);
                    }
                });
            },600)();
        });
    }

    // Клик по ссылке услуги которую закаываем в заявке
    $("[data-order-service-link]").click(function (e) {
        e.preventDefault();
        let serviceId = $(this).data("order-service-id");
        let serviceText = $(this).text();
        if($('[data-order-service]').val().length > 0){
            $('[data-order-service]').val($('[data-order-service]').val() + ', ' + serviceText);
        }else{
            $('[data-order-service]').val(serviceText);
        }

        $('[data-order-service]').closest(".js-popupSearchResult-input-wrap").find(".js-popupSearchResult-inputHidden").val(serviceId);
    });

    if ($formOrder.length) {
        $formOrder.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            //let fields = $currentOrderForm.serialize();
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                $currentOrderForm.find('[data-form-alert]').hide().html();
                $currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.error) {
                            $currentOrderForm.find('[data-message]').html(data.error);
                            $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                            $currentOrderForm.find('[data-message]').show();
                            $currentOrderForm.find('.slick-list').height($currentOrderForm.find('.slick-current').height());

                        } else if (data.success) {
                            window.location.href = "/order/success/"+data.result.order+'/';
                        }

                    },
                })
            }

        })

    }



    $(document).on('click', '.js-popupSearchResult__result', function () {
        var text = $(this).text();
        var value = $(this).attr('data-result');
        var inputText = $(this).closest('.formGroup').find('.js-popupSearchResult-input');
        var inputValue = $(this).closest('.formGroup').find('.js-popupSearchResult-inputHidden');
        inputText.val(text);
        inputValue.val(value);
        $(this).blur();
    });



    // Клик на элемент в выпадающем списке с подсказками в заявке
    $(document).on('click', '.js-popupSearchResult__result_order', function () {
        var text = $(this).text();
        var value = $(this).attr('data-result');
        var inputText = $(this).closest(".js-popupSearchResult-input-wrap").find('.js-popupSearchResult-input');
        var inputValue = $(this).closest(".js-popupSearchResult-input-wrap").find('.js-popupSearchResult-inputHidden');
        inputText.val(text);
        inputValue.val(value);
        $(this).blur();
    });

    $(document).on('click', '.js-popupSearchResult__result_service', function () {
        var text = $(this).text();
        var value = $(this).attr('data-result');
        var inputText = $(this).closest(".js-hero__form-item").find('.js-popupSearchResult-input');
        inputText.val(text);
        $(this).blur();
    });

    $(document).on('blur', '.js-popupSearchResult__result_service', function (e) {
        var that = $(this);
        setTimeout(function () {
            that.closest('.js-hero__form-item').find('.js-popupSearchResult').slideUp(200);
        }, 200);
    });

    $(document).on('blur', '.js-popupSearchResult-input', function (e) {
        var that = $(this);
        setTimeout(function () {
            that.closest('.formGroup').find('.js-popupSearchResult').slideUp(200);
        }, 200);
    });

    $('.js-popupSearchResult-input-wrap').on('blur', '.js-popupSearchResult-input', function (e) {
        var that = $(this);
        setTimeout(function () {
            that.parents(".js-popupSearchResult-input-wrap").find('.js-popupSearchResult').slideUp(200);
        }, 200);
    });

    $(document).on('focus', '.js-popupSearchResult-input', function () {
        var popup = $(this).closest('.formGroup').find('.js-popupSearchResult');
        if (popup.html() != '') {
            popup.slideDown(200);
        }
    });

    $('.js-popupSearchResult-input-wrap').on('focus', '.js-popupSearchResult-input', function (e) {
        var popup = $(this).parents(".js-popupSearchResult-input-wrap").find('.js-popupSearchResult');
        if (popup.html() != '') {
            popup.slideDown(200);
        }
    });



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
        if(!$(this).hasClass("js-only-submit")){
            applyFilter($formFilter, $formFilterDetails);
        }
        $formFilterDetails.find('.js-reset-form').show();
    });

    $formFilterDetails.on('click','.js-reset-form',function (e) {
        e.preventDefault();
        $formFilterDetails.find('.js-chosen').val(null).trigger('change');
        $(this).hide();
    });

    // Применение фильтра мероприятий
    $formEventsFilter.find('.js-select').on('change', function (e) {
        applyEventsFilter($formEventsFilter);
    });

    // Применение фильтра обучалок
    $formEducationsFilter.find('.js-select').on('change', function (e) {
        applyEducationsFilter($formEducationsFilter);
    });

    // Применение фильтра после изменения чекбокса "Сертифицированные"
    $('.js-checkbox-sert').on('change', function (e) {
        ($formFilter.find("input[name='sert']").val() == "1") ? $formFilter.find("input[name='sert']").val("") : $formFilter.find("input[name='sert']").val("1");
        applyFilter($formFilter, $formFilterDetails);
    });
    // Применение фильтра после изменения чекбокса "Свободен"
    $('.js-checkbox-free').on('change', function (e) {
        ($formFilter.find("input[name='free']").val() == "1") ? $formFilter.find("input[name='free']").val("") : $formFilter.find("input[name='free']").val("1");
        applyFilter($formFilter, $formFilterDetails);
    });

    // Сортировка
    $('.js-sort').on('click', function (e) {
        e.preventDefault();
        var action = $formFilter.attr('action');
        var fields = $formFilter.attr('data-filter-params');
        var sortButton = $(this);
        if (sortButton.hasClass('is-active')) {
            if (sortButton.data('order') == 'asc') {
                sortButton.data('order', 'desc');
                sortButton.addClass('desc');
            } else {
                sortButton.data('order', 'asc');
                sortButton.removeClass('desc');
            }
        } else {
            $('.js-sort').removeClass('is-active');
            sortButton.addClass('is-active');
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

    $(document).on('click', '.js-header-city__quest-toggler', function (e) {
        e.preventDefault();
        cityQuestToggle();
    });

    //Показать скрыть блок с вопросом "Ваш город"
    function cityQuestToggle() {
        $('.js-header-city__quest-content').toggleClass('active');
    }

    $('.formGroup [type=tel]').inputmask({
        mask: '+7 (999) 999-99-99',
        showMaskOnHover: false,
        clearIncomplete: true
    });

    if ($.fn.slick) {
        $('.js-slider-order-steps').slick({
            arrows: false,
            dots: false,
            rows: 0,
            adaptiveHeight: true,
            swipe: false,
            infinite: false,
            responsive: [{
                breakpoint: 767,
                settings: {
                    swipe: false,
                    adaptiveHeight: true
                }
            }]
        });
        $('[data-next-step]').click(function(){
            $(this).parents(".js-slider-order-steps").slick('slickNext');
            var body = $("html, body");
            body.stop().animate({scrollTop:0}, 500, 'swing', function() {});
        });
        $('[data-prev-step]').click(function(){
            $(this).parents(".js-slider-order-steps").slick('slickPrev');
            var body = $("html, body");
            body.stop().animate({scrollTop:0}, 500, 'swing', function() {});
        });


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

    hashWork();

    $(".js-field-input--datapicker").datepicker({
        minDate: new Date(),
        autoClose: true
    });

    $(".js-field-input--timepicker").inputmask({'alias':'datetime', 'inputFormat':'HH:MM','placeholder':'12:00'});


});

function filterEvents () {
    applyEventsFilter($formEventsFilter);
}

//Действия при изменении размера окна или перевороте экрана телефона
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

//Переключение табов
$(document).on('click', '.js-tabs-buttons-single-inner', function (e) {
    e.preventDefault();
    if ($(this).hasClass('js-set-cookie')) {
        setCookie('social-user-type', $(this).attr('href'));
    }
    toggleTab($(this));
});

function loadSvg () {
    // SVG sprites load
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/local/templates/elektrik/images/sprite.svg', true);
    xhr.overrideMimeType('image/svg+xml');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var container = document.createElement('div');
            container.className = 'invisible-container';
            container.style.display = "none";
            container.appendChild(xhr.responseXML.documentElement);

            function waitForBody() {
                if (document.body) {
                    document.body.appendChild(container);
                } else {
                    setTimeout(waitForBody, 16)
                }
            }

            waitForBody();
        };
    }
    xhr.send('');
}

function setCookie(name, value, options = {}) {

    options = {
        path: '/',
    };

    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}

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

//Смещение label при фокусе или если в поле есть текст
function labelTransfer2(that, focus) {
    if (that.closest('.order__form-item').length != 0
        && that.closest('.order__form-item').find('label').length != 0
        && that.closest('.order__form-item').find('.custCheckbox').length == 0
        && that.closest('.order__form-item').find('.custRadio').length == 0) {

        if (that.val().length > 0 || checkWebkitAutofill(that) || focus) {
            if (that.attr('data-placeholder') != undefined) {
                that.attr({'placeholder': ''});
            }
            that.closest('.order__form-item').find('label').addClass('active');
            if (that.closest('.order__form-item').find('.showPass').length != 0) {
                that.closest('.order__form-item').find('.showPass').addClass('active');
                if (that.closest('.order__form-item').find('.passwordField').length != 0) {
                    that.closest('.order__form-item').find('.passwordField').addClass('activeLabel');
                }
            }
            setTimeout(function () {
                // let labelWidth = that.closest('.order__form-item').find('label').innerWidth();
                if (that.closest('.order__form-item').hasClass('order__form-item_search') == false) {
                    // that.css({'padding-right': labelWidth });
                } else {
                    that.closest('.order__form-item').find('.js-cleanIcon').addClass('active');
                }
            }, 300);
        } else {
            // let paddingRightStart = that.attr('data-padding-right');
            // that.css({ 'padding-right': paddingRightStart });
            if (that.attr('data-placeholder') != undefined) {
                that.attr({'placeholder': that.attr('data-placeholder')});
            }
            that.closest('.order__form-item').find('label').removeClass('active');
            if (that.closest('.order__form-item').find('.showPass').length != 0) {
                that.closest('.order__form-item').find('.showPass').removeClass('active');
                if (that.closest('.order__form-item').find('.passwordField').length != 0) {
                    that.closest('.order__form-item').find('.passwordField').removeClass('activeLabel');
                }
            }
            if (that.closest('.order__form-item').hasClass('order__form-item_search')) {
                that.closest('.order__form-item').find('.js-cleanIcon').removeClass('active');
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
                //errorBlock.text('Пароль недостаточно надежен');
                errorBlock.text('Пароль должен состоять из латинских строчных, заглавных букв и цифр');
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

    $(document).on('click', '.js-marketDetail-works-more', function (e) {
        e.preventDefault();
        $('.js-work-examples__inner').removeClass('hide');
        $(this).remove();
    });

    $("[data-service-item]").on("click","[data-show-more]",function (e) {
        e.preventDefault();

    });


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

    let imagesPreviewBG = function (input, placeToInsertImagePreview) {

        if (input.files) {
            let filesAmount = input.files.length;
            for (let i = 0; i < filesAmount; i++) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    placeToInsertImagePreview.css("background-image",`url(${event.target.result})`);
                    placeToInsertImagePreview.removeClass("cabinet__form-photo--empty");
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

    $('.field-add-photo').on("change","#field-add-photo",function(){
        imagesPreviewBG(this,$("[data-logo-block]"));
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

    $('.transLabel input,textarea').focus(function (event) {
        labelTransfer2($(this), true);
    });
    $('.transLabel input,textarea').blur(function (event) {
        labelTransfer2($(this));
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

    /****************Articles*************/
    var $articlesSearch = $('[data-articles-search]');
    var $articlesFilter = $('[data-articles-filter]');
    var $articlesList = $('.js-articles-list');
    var $articlesClear = $('.js-articles-filter-clear');
    var $commentsList = $('.js-comments-list');

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
    /*$('.js-articles-more').on('click', function (e) {
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
    });*/

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
        /*$(window).scroll(function () {

            if ($('#js-more-button').is(':visible')) {
                if ($(window).scrollTop() + window.innerHeight * 1.6 > $('.js-articles-more').offset().top && focusButton == false) {


                }
            }
        });*/

        $('.js-articles-more').on('click', function (e) {
            e.preventDefault();
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
            }
        });
    });

    /* Подгрузка отзывов на детальной странице исполнителя */
    $('.js-reviews-more-button a').on('click', function (e) {
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
                //fillAllRatingsAjax();
            }
        });
    });

    /* Выбор типа помещения при оформлении заявки */
    $('.order__box-item').on("click","[data-room]",function (e) {
        let rooms = [];
        $(this).toggleClass("is-active");
        $('[data-room]').each(function (e) {
            if($(this).hasClass("is-active")){
                let roomId = $(this).data('room-id');
                rooms.push(roomId);
            }
        });
        $(this).closest("[data-room-wrap]").find(".js-order-room").val(rooms.join(","));
    });


    $('.backofficeServices__add .button').click(function(){
        $('#addServiceModal').fadeIn();
    });

    $('.closeModal').click(function(){
        $('.modal').fadeOut();
    });

    $(document).keyup(function(e) {
        if (e.key === "27") {
            $('.modal').fadeOut();
        }
    });


});
