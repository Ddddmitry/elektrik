$(document).ready(function () {

    $(".js-datapicker").datepicker({
        autoClose: true,
    });

    let imagesPreviewBack = function (input, placeToInsertImagePreview, customClass) {

        if (input.files) {
            let filesAmount = input.files.length;
            for (let i = 0; i < filesAmount; i++) {
                let reader = new FileReader();
                reader.onload = function (event) {

                    let single =  $('<img>');
                    let cls = customClass;
                    if(i > 0){
                        cls += " hide";
                    }
                    single.attr({'data-index': i,'class':cls});
                    single.attr({'src': event.target.result});
                    placeToInsertImagePreview.append(single);
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    /**
     * Переключалка таблов
     * */
    $("[data-tabs]").on("click","[data-tab]",function () {
        $("[data-tab-content]").removeClass("is-open");
        let curTab = $(this).data("tab");
        $("[data-tab-content='"+curTab+"']").addClass("is-open");
    });

    /**
     * Форма обновления профиля
     * */
    let $formProfileUpdate = $('[data-back-form-profile-update]');
    if ($formProfileUpdate.length) {
        $formProfileUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');
            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
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

                        if (data.success) {
                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }


    /**
     * Форма обновления ифно
     * */
    let $formInfoUpdate = $('[data-back-form-info-update]');
    if ($formInfoUpdate.length) {
        $formInfoUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = "";
            let action = $currentOrderForm.attr('action');
            let ob = {};
            ob.about = $currentOrderForm.find("[name='about']").val();
            ob.qualification = $currentOrderForm.find("[name='skill']").val();
            ob.languages = [];
            ob.experience = [];

            let langs = $currentOrderForm.find(".js-language-item");
            langs.each(function (index) {
                ob.languages.push({"language":{"ID":$(this).find("[name='language']").val()},"skill":{"ID":$(this).find("[name='skill']").val()}});
            });

            let exp = $currentOrderForm.find(".js-experience-item");
            exp.each(function (index) {
                let tmp = {
                    "monthStart":$(this).find("[name='monthStart']").val(),
                    "monthStartNumber":$(this).find("[name='monthStart']").val(),
                    "yearStart":$(this).find("[name='yearStart']").val(),
                    "monthEnd":$(this).find("[name='monthEnd']").val(),
                    "monthEndNumber":$(this).find("[name='monthEndNumber']").val(),
                    "yearEnd":$(this).find("[name='yearEnd']").val(),
                    "currentPlace":$(this).find("[name='currentPlace']").is(':checked'),
                    "place":$(this).find("[name='place']").val()
                };
                if($(this).find("[name='currentPlace']").is(':checked')){
                    tmp.monthEnd = "";
                    tmp.monthEndNumber = "";
                    tmp.yearEnd = "";
                }
                ob.experience.push(tmp);
            });


            fields = JSON.stringify(ob);

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {

                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }

    /**
     * Форма обновления услуг
     * */
    let $formServiceUpdate = $('[data-back-form-services-update]');
    if ($formServiceUpdate.length) {
        $formServiceUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');

            let ob = {};
            ob.services = [];
            ob.typePlace = [];
            ob.places = [];

            let services = $currentOrderForm.find("[data-service-single]");
            services.each(function (index) {
                let tmp = {"name": $(this).data("category")};
                tmp.items = [];
                $(this).find("[data-subservice-single]").each(function (index2) {
                    let that = $(this);
                    tmp.items.push({"id":that.data("service-item"),"price":that.find("[name='price']").val()});
                });
                ob.services.push(tmp);
            });


            let rooms = $currentOrderForm.find("[data-room]");
            rooms.each(function (index) {
                ob.typePlace.push({"id":$(this).find("input").val(),"name":$(this).find("input").data("name"),"checked":$(this).find("input").is(':checked')})
            });

            let places = $currentOrderForm.find(".js-city");
            places.each(function (index) {
                ob.places.push({"id":$(this).data("id"),"name":$(this).find('[data-name]').text(),"level":$(this).data("level")})
            });


            fields = JSON.stringify(ob);

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {

                        if (data.success) {
                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }


    /**
     * Форма обновления примеров работы
     * */
    let $formWorksUpdate = $('[data-back-form-works-update]');
    if ($formWorksUpdate.length) {
        $formWorksUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');
            let ob = {};
            ob.works = [];
            let works = $currentOrderForm.find("[data-work-single]");
            works.each(function (index) {
                let tmpOb = {};
                tmpOb.title = $(this).find("[name='title']").val();
                if(tmpOb.title.length >= 0){
                    tmpOb.description = $(this).find("[name='description']").val();
                    tmpOb.images = [];
                    $(this).find("img").each(function () {
                        if($(this).hasClass("old-work")){
                            tmpOb.images.push({
                                "detail_img": $(this).data("full"),
                                "is_base64": false
                            });
                        }else{
                            tmpOb.images.push({
                                "detail_img": $(this).attr("src"),
                                "is_base64": true
                            });
                        }
                    });
                    ob.works.push(tmpOb);
                }

            });

            fields = JSON.stringify(ob);

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {

                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }

    /**
     * Форма добавления заявки в РАЭК
     * */
    let $formAddRaek = $('[data-raek-add-request]');
    if ($formAddRaek.length) {
        $formAddRaek.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = "";
            let action = $currentOrderForm.attr('action');
            let ob = {};
            ob.name = $currentOrderForm.find('[name="name"]').val();
            ob.phone = $currentOrderForm.find('[name="phone"]').val();
            ob.email = $currentOrderForm.find('[name="email"]').val();

            fields = JSON.stringify(ob);

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {

                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }

    /**
     * Форма подтверждения верификации в РАЭК
     * */
    let $formAddVerificationRaek = $('[data-raek-add-verification]');
    if ($formAddVerificationRaek.length) {
        $formAddVerificationRaek.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = new FormData($currentOrderForm[0]);
            let action = $currentOrderForm.attr('action');
            /*let ob = {};
            ob.document = $currentOrderForm.find("img").attr("src");

            fields = JSON.stringify(ob);*/

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {
                            $('#confirm_sertificate_raek').html('<div class="fancybox-title">Членство РАЭК</div><p>Ваша заявка отправлена.</p>');
                        } else if (data.error) {
                            $('#confirm_sertificate_raek').html('<div class="fancybox-title">Заявка уже существует</div><p>Ваша заявка уже находится на рассмотрении.</p>');
                        }

                    },
                })
            }
        })
    }

    /**
     * Форма обновления сертификатов
     * */
    let $formSertificatesUpdate = $('[data-back-form-sertificates-update]');
    if ($formSertificatesUpdate.length) {
        $formSertificatesUpdate.on('submit', (e) => {
            e.preventDefault();
            let $currentOrderForm = $(e.target);
            let fields = "";
            let action = $currentOrderForm.attr('action');
            let ob = {};
            ob.sertificates = [];
            let sertificates = $currentOrderForm.find("[data-sertificate-single]");
            sertificates.each(function (index) {
                let tmpOb = {};
                tmpOb.title = $(this).find("[name='title']").val();
                tmpOb.date = $(this).find("[name='date']").val();

                $(this).find("img").each(function () {
                    tmpOb.detail_img = $(this).attr("src");
                    if($(this).hasClass("old-sert")){
                        tmpOb.is_base64 = false;
                    }else{
                        tmpOb.is_base64 = true;
                    }
                });
                ob.sertificates.push(tmpOb);


            });

            ob.education = [];
            let educations = $currentOrderForm.find("[data-education-single]");
            educations.each(function (index) {
                let tmpOb = {};
                tmpOb.place = $(this).find("[name='place']").val();
                tmpOb.type = $(this).find("[name='type']").val();
                if(tmpOb.place.length > 0)
                    ob.education.push(tmpOb);
            });

            ob.courses = [];
            let course = $currentOrderForm.find("[data-course-single]");
            course.each(function (index) {
                let tmpOb = {};
                tmpOb.name = $(this).find("[name='course']").val();
                if(tmpOb.name.length > 0)
                    ob.courses.push(tmpOb);
            });

            fields = JSON.stringify(ob);

            if (!action) {
                alert('Не указан обработчик запроса');
                return false;
            } else {
                //$currentOrderForm.find('[data-form-submit]').attr('disabled', true);
                $.ajax({
                    url: action,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: fields,
                    type: 'POST',
                    success: (data) => {
                        if (data.result) {

                            $currentOrderForm.find('[data-form-submit]').addClass("btn--green");
                            setTimeout(function () {
                                $currentOrderForm.find('[data-form-submit]').removeClass("btn--green");
                            }, 3000);
                        } else if (data.error) {

                        }
                        $currentOrderForm.find('[data-form-submit]').attr('disabled', false);
                    },
                })
            }
        })
    }

    $('.backofficeExperience__new').click(function(e){
        e.preventDefault();
        var labell = $('.custLabel').length;



        var bText = $(this).find('.backofficeExperience__new-inner').text();
        bText = bText.replace(/\s+/g, ' ').trim();

        if(bText == 'Добавить место работы'){
            let toAddXP = genterateExperience();
            $(this).closest('.cabinet__block').find('.backofficeExperience-list').append(toAddXP);
        }
        if(bText == 'Добавить язык'){
            let toAddLanguage = generateLangugaes();
            $(this).closest('.cabinet__block').find('.backofficeExperience-list').append(toAddLanguage);
        }
        if(bText == 'Добавить локацию'){
            $('[data-city-chooser]').show();
        }
        if(bText == 'Добавить примеры работ'){
            let work = generateWorks();
            $(this).closest('.cabinet__block').find('.backofficeWorks-list').prepend(work);
        }
        if(bText == 'Добавить сертификат'){
            let sert = generateSertificate();
            $(this).closest('.cabinet__block').find('.backofficeExperience-list').prepend(sert);
            $(this).closest('.cabinet__block').find('.backofficeExperience-list .js-new-sert .js-datapicker' ).datepicker({
                autoClose: true
            });

        }
        if(bText == 'Добавить место учебы'){
            let edu = generateEducation();
            $(this).closest('.cabinet__block').find('.backofficeExperience-list').prepend(edu);
        }
        if(bText == 'Добавить курсы'){
            let courses = generateCourse();
            $(this).closest('.cabinet__block').find('.backofficeExperience-list').prepend(courses);
        }

        $('.cabinet__form-item select').select2({
            minimumResultsForSearch: -1,
            width: 'resolve',
            dropdownCssClass: 's2customDropdown'
        });

        $('.multiselect--disabled select').on('select2:opening', function (e) {
            if($(this).parent().hasClass('multiselect--disabled')){
                e.preventDefault();
            } else {

            }
        });




    });

    $('.custLabel').click(function(){
        var elem = $(this).attr('for');
        if(!$('input[id="' + elem + '"]').is(':checked')){
            $(this).closest('.backofficeExperience-list__single').find('.backofficeExperience-list__medium:eq(1)').addClass('multiselect--disabled');
            $(this).closest('.backofficeExperience-list__single').find('.backofficeExperience-list__small:eq(1)').addClass('multiselect--disabled');
            $(this).closest('.backofficeExperience-list__single').find('select[name="monthEnd"]').prop("disabled",true);
            $(this).closest('.backofficeExperience-list__single').find('select[name="yearEnd"]').prop("disabled",true);
        } else {
            $(this).closest('.backofficeExperience-list__single').find('.backofficeExperience-list__medium').removeClass('multiselect--disabled');
            $(this).closest('.backofficeExperience-list__single').find('.backofficeExperience-list__medium:eq(1)').removeClass('multiselect--disabled');
            $(this).closest('.backofficeExperience-list__single').find('.backofficeExperience-list__small:eq(1)').removeClass('multiselect--disabled');
            $(this).closest('.backofficeExperience-list__single').find('select[name="monthEnd"]').prop("disabled",false);
            $(this).closest('.backofficeExperience-list__single').find('select[name="yearEnd"]').prop("disabled",false);
        }

        /*$('.cabinet__form-item select').select2({
            minimumResultsForSearch: -1,
            width: 'resolve',
            dropdownCssClass: 's2customDropdown'
        });

        $('.multiselect--disabled select').on('select2:opening', function (e) {
            if($(this).parent().hasClass('multiselect--disabled')){
                e.preventDefault();
            } else {

            }
        });*/

    });

    setInterval(function(){
        $('.backofficeExperience-list__delete').click(function(e){
            e.preventDefault();
            $(this).closest('.backofficeExperience-list__single').remove();
            $(this).closest('.backofficeExperience-list__place').remove();
        });
    },100);

    function generateLangugaes(){
        let items = "";
        let LANGS = JS_SELECTS.LANGUANGES;
        let LANGS_LEVEL = JS_SELECTS.LANGUANGES_LEVELS;
        items += '<div class="backofficeExperience-list__single js-language-item">';
        items += '<div class="backofficeExperience-list__large"><select name="language">';
            for(let i in LANGS){
                items += "<option value='" + LANGS[i].ID + "'>" + LANGS[i].NAME + "</option>";
            }
        items += '</select></div>';
        items += '<div class="backofficeExperience-list__extralarge"><select name="skill">';
            for(let i in LANGS_LEVEL){
                items += "<option value='" + LANGS_LEVEL[i].ID + "'>" + LANGS_LEVEL[i].NAME + "</option>";
            }
        items += '</select></div>';
        items += '</div>';
        items += '<a href="#" class="backofficeExperience-list__delete"><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>';
        return items;
    };

    function genterateExperience() {
        let items = "";
        let Months = JS_SELECTS.MONTH;
        let curYear = new Date().getFullYear();
        let randomString = Date.now();
        items += '<div class="backofficeExperience-list__single js-experience-item">';
        items += '<div class="backofficeExperience-list__text">С</div>';
        items += '<div class="backofficeExperience-list__medium"><select name="monthStart">';
            for(let i in Months){
                items += "<option value='" + i + "'>" + Months[i] + "</option>";
            }
        items += '</select></div>';
        items += '<div class="backofficeExperience-list__small"><select name="yearStart">';
            for(let i = curYear; i > 1949; i-- ){
                items += "<option value='" + i + "'>" + i + "</option>";
            }
        items += '</select></div>';
        items += '<span class="backofficeExperience-list__single-dash"></span>';
        items += '<div class="backofficeExperience-list__text">По</div>';
        items += '<div class="backofficeExperience-list__medium"><select name="monthEnd">';
        for(let i in Months){
            items += "<option value='" + i + "'>" + Months[i] + "</option>";
        }
        items += '</select></div>';
        items += '<div class="backofficeExperience-list__small"><select name="yearEnd">';
        for(let i = curYear; i > 1949; i-- ){
            items += "<option value='" + i + "'>" + i + "</option>";
        }
        items += '</select></div>';
        items += '<a href="#" class="backofficeExperience-list__delete"><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>';
        items += '<div class="backofficeExperience-list__currentPlace"><div class="slideToggler"><input type="checkbox" value="1" id="curPlace-'+randomString+'" name="currentPlace" checked><label for="curPlace-'+randomString+'" class="custLabel">Работаю сейчас</label></div></div>';
        items += '<div class="backofficeExperience-list__place"><input type="text" id="" name="place" placeholder="Название компании"></div>'
        items += '</div>';
        return items;
    }

    function generateWorks() {
        let items = "";
        items = '<div class="backofficeWorks-list__single js-backofficeWorks-list__single" data-work-single>\n' +
            '                                        <div class="backofficeWorks-list__img js-backofficeWorks-list__img">\n' +
            '                                            <div class="backofficeWorks-list__img-file">\n' +
            '                                                <div class="backofficeCustFile">\n' +
            '                                                    <label for="editWorks-img">\n' +
            '                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"\n' +
            '                                                             viewBox="0 0 24 24" class="svg-icon">\n' +
            '                                                            <g fill="none" fill-rule="evenodd" stroke-linecap="square"\n' +
            '                                                               stroke-linejoin="round" stroke-width="2">\n' +
            '                                                                <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"></path>\n' +
            '                                                            </g>\n' +
            '                                                        </svg>\n' +
            '                                                        <span class="backofficeCustFile-hiddenMd">Загрузить фото</span>\n' +
            '                                                        <span class="backofficeCustFile-visionMd">Фото</span></label>\n' +
            '                                                    <input type="file" name="editWorks-img" id="editWorks-img" data-edit-works-img\n' +
            '                                                           accept="image/jpg,image/jpeg,image/png,image/gif" multiple>\n' +
            '                                                </div>\n' +
            '                                                <div class="backofficeWorks-list__img-note">\n' +
            '                                                    Вы можете загрузить изображение в формате JPG, GIF, BMP или PNG.\n' +
            '                                                    Размер фото не&nbsp;больше 10 Мб.\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                        <div class="backofficeWorks-list__text">\n' +
            '                                            <div class="backofficeWorks-list__text-group"><input type="text"\n' +
            '                                                                                                 name="title"\n' +
            '                                                                                                 placeholder="Заголовок"\n' +
            '                                                                                                 class="backofficeWorks-list__text-input">\n' +
            '                                            </div>\n' +
            '                                            <div class="backofficeWorks-list__text-group"><textarea\n' +
            '                                                        name="description"\n' +
            '                                                        placeholder="Описание (не обязательно)"\n' +
            '                                                        class="backofficeWorks-list__text-textarea"></textarea></div>\n' +
            '                                        </div>\n' +
            '                                        <div class="backofficeWorks-list__delete">\n' +
            '                                            <a href="#" class="backofficeServices__delete" data-delete-work>\n' +
            '                                                <div class="backofficeServices__delete-line"></div>\n' +
            '                                                <div class="backofficeServices__delete-line"></div>\n' +
            '                                            </a>\n' +
            '                                        </div>\n' +
            '                                    </div>';
        return items;
    }

    function generateSertificate() {

        let item = '';

        item += '<div class="backofficeExperience-list__single backofficeExperience-list__single_start js-new-sert" data-sertificate-single>\n' +
            '                    <div class="backofficeExperience-list__img js-backofficeSert-list__img"></div>\n' +
            '                    <div class="backofficeExperience-list__fields">\n' +
            '                        <div class="backofficeCustFile">\n' +
            '                            <label for="editData-logo"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"></path></g></svg>Загрузить сертификат</label>\n' +
            '                            <input type="file" name="editData-logo" id="editData-logo" accept="image/jpg,image/jpeg,image/png,image/gif" data-edit-sert-img>\n' +
            '                        </div>\n' +
            '                        <input name="title" type="text" placeholder="Название сертификата">\n' +
            '                        <a href="#" class="backofficeExperience-list__delete" data-delete-sertificate><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>\n' +
            '                        <div class="backofficeExperience-list__space"></div>\n' +
            '                        <label for="editSertificates-date-0" class="backofficeExperience-list__label">Выдан</label>\n' +
            '                        <input id="editSertificates-date-0" name="date" type="text" class="backofficeExperience-list__medium_sert js-datapicker">\n' +
            '                    </div>\n' +
            '                </div>';

        return item;

    }

    function generateEducation(){
        let item = '';
        let types = JS_SELECTS.EDUCATIONS;
        item += '<div class="backofficeExperience-list__single" data-education-single>\n' +
            '                                    <input name="place" type="text" placeholder="Название места учёбы">                                    \n' +
            '                                    <a href="#" class="backofficeExperience-list__delete" data-delete-education><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>\n' +
            '                                    <div class="backofficeExperience-list__space"></div>\n' +
            '                                    <div class="backofficeExperience-list__large">\n' +
            '                                        <select name="type" id="">';
        for(let i in types){
            item += '<option value="'+types[i]+'">'+types[i]+'</option>';
        }
        item += '</select></div></div>';

        return item;
    }

    function generateCourse(){
        let item = '';
        item += '<div class="backofficeExperience-list__single" data-course-single>\n' +
                    '<input name="course" type="text" placeholder="Название курса">\n' +
                    '<a href="#" class="backofficeExperience-list__delete" data-delete-course><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>\n' +
                '</div>';

        return item;
    }

    $('.serviceFieldSearch select').select2({
        minimumResultsForSearch: 1,
        width: 'resolve',
        dropdownCssClass: 'servicesDropdown'
    });

    $('.serviceFieldSearch select').on('select2:select',function(e){
        var resultServiceSearch = $('.serviceFieldSearch select').val().split(':');

        var category = resultServiceSearch[0];
        var theService = resultServiceSearch[1];
        var theServiceID = resultServiceSearch[2];
        var categoryTitle = "";
        let isset = false;
        let kuda;

        var toAddService = '<div class="backofficeServices-sublist__single" data-subservice-single data-category="' + category + '" data-service-item="'+theServiceID+'">' +
            '<div class="backofficeServices-sublist__title backofficeServices-list__block">'+theService+'</div>' +
            '<div class="backofficeServices-sublist__price backofficeServices-list__block">' +
            '<div class="backofficeServices-sublist__text backofficeServices-list__block">от</div>' +
            '<input type="text" name="price" data-number class="backofficeServices-sublist__input">' +
            '<div class="backofficeServices-sublist__money">рублей</div>' +
            '</div>' +
            '<div class="backofficeServices-list__delete backofficeServices-list__block">' +
            '<a href="#" class="backofficeServices__delete">' +
            '<div class="backofficeServices__delete-line"></div>' +
            '<div class="backofficeServices__delete-line"></div>' +
            '</a>' +
            '</div>' +
            '</div>';

        $('.modal').fadeOut();

        $('.backofficeServices-list__title.backofficeServices-list__block').each(function(e){
            categoryTitle = $(this).text();
            categoryTitle = categoryTitle.replace(/\s+/g, ' ').trim();

            if(categoryTitle == category){
                isset = true;
                kuda = $(this).closest('.backofficeServices-list__single').find('.backofficeServices-sublist');
            }

        });

        if(isset){
            kuda.append(toAddService);
        }else{
            let categoryService = '<div class="backofficeServices-list__single" data-service-single data-category="'+category+'"><div class="backofficeServices-list__title backofficeServices-list__block">' + category + '</div>' +
                '                                <div class="backofficeServices-list__comment backofficeServices-list__block"><span>цена в рублях</span></div>' +
                '                                <div class="backofficeServices-list__delete backofficeServices-list__block"></div>' +
                '                                <div class="backofficeServices-sublist">';
            categoryService += toAddService;
            categoryService += '</div></div></div>';
            $('[data-service-list]').append(categoryService);
        }

        $("*[data-number]").inputmask("decimal",{
            showMaskOnHover: false,
            clearIncomplete: true,
            rightAlign: false,
        });
    });

    // Всплывающие подсказки при заполнении поля местоположения в форме поиска
    let $chooseCity = $('[data-choose-city]');
    if ($chooseCity.length) {
        $chooseCity.on('keyup', function (e) {
            e.preventDefault();

            debounce(function () {
                if (e.target.value.length > 2) {
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
                            var popup = $(e.target).closest('.js-popupSearchWrap').find('.js-popupSearchResult');
                            popup.html('');

                            if (data.result != undefined) {
                                for (var i = 0; i < data.result.length; i++) {
                                    popup.append('<div class="popupSearchResult__result popupSearchResult__block js-popupSearchResult__result_city" data-id="' + data.result[i]['id'] + '" data-level="' + data.result[i]['level'] + '" >' + data.result[i]['value'] + '</div>');
                                }
                                popup.slideDown(200);
                            }
                        }
                    });
                }
            },600)();
        });
    }
    // Клик на элемент в выпадающем списке с подсказками в заявке
    $(document).on('click', '.js-popupSearchResult__result_city', function () {
        var value = $(this).text();
        var id = $(this).attr('data-id');
        var level = $(this).attr('data-level');
        let city = '<a href="#" class="backofficeServices-cityList__single inhLink js-city" data-id="'+id+'" data-level="'+level+'">\n' +
            '                                    <div class="backofficeServices-cityList__name" data-name>'+ value +'</div> <span class="backofficeServices-cityList__delete" data-delete-city><span class="backofficeServices-cityList__delete-line"></span> <span class="backofficeServices-cityList__delete-line"></span></span>\n' +
            '                                </a>';

        $('[data-service-city-list]').append(city);
        $chooseCity.val("");
        $(this).blur();
        $('[data-city-chooser]').hide();
    });

    $(document).on('blur', '.js-popupSearchResult__result_city', function (e) {
        var that = $(this);
        setTimeout(function () {
            that.closest('.js-popupSearchWrap').find('.js-popupSearchResult').slideUp(200);
        }, 200);
    });

    $("[data-service-city-list]").on("click","[data-delete-city]",function (e) {
        e.preventDefault();
        $(e.target).closest('.js-city').remove();
    });

    $("[data-subservice-single]").on("click","[data-delete-service]",function (e) {
        e.preventDefault();
        $(e.target).closest('[data-subservice-single]').remove();
    });

    $("[data-works-list]").on("click","[data-delete-work]",function (e) {
        e.preventDefault();
        $(e.target).closest('[data-work-single]').remove();
    });

    $("[data-educations-list]").on("click","[data-delete-education]",function (e) {
        e.preventDefault();
        $(e.target).closest('[data-education-single]').remove();
    });


    $('[data-works-list]').on("change","[data-edit-works-img]",function(){
        let place = $(this).closest(".js-backofficeWorks-list__img");
        place.html("");
        imagesPreviewBack(this,place,'backofficeWorks-list__img-tag');
    });

    $('[data-sertificates-list]').on("change","[data-edit-sert-img]",function(){
        let place = $(this).closest('[data-sertificate-single]').find(".js-backofficeSert-list__img");
        place.html("");
        imagesPreviewBack(this,place,'backofficeExperience-list__img-tag');
    });



});
