<template>
    <div class="app">
        <div class="backofficeMenu js-backofficeMenu">
            <div class="backofficeMenu__vyal js-backofficeMenu-toggle"></div>
            <div class="backofficeMenu-inner">
                <a href="#" class="backofficeMenu__close js-backofficeMenu-toggle">
                    <span class="backofficeMenu__close-line"></span>
                    <span class="backofficeMenu__close-line"></span>
                </a>
                <div class="backofficeMenu-person">
                    <div class="backofficeMenu-person__single" v-for="item in personMenu">
                        <router-link @click.native="backofficeMenuToggle()" :to="{name:item.code}" class="backofficeMenu-person__single-link">
                            {{item.title}}
                        </router-link>
                    </div>
                </div>
                <div class="backofficeMenu__separator"></div>
                <div class="backofficeMenu-functions">
                    <div class="backofficeMenu-functions__single" v-for="item in functionsMenu"
                         :class="{'backofficeMenu-functions__single_logout' : (item.code == 'logout')}">
                        <a :href="'#'+item.code"
                           data-fancybox
                           :data-src="'#'+item.code"
                           v-if="item.code == 'edit_alerts' || item.code == 'edit_password'"
                           class="backofficeMenu-functions__single-link">
                            {{item.title}}
                        </a>
                        <a :href="'/'+item.code+'/'"
                           v-else-if="item.code == 'logout'" class="backofficeMenu-functions__single-link">
                            {{item.title}}
                        </a>
                        <router-link v-else="" @click.native="backofficeMenuToggle()" :to="{name:item.code}" class="backofficeMenu-functions__single-link">
                            {{item.title}}
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-inner js-app-inner js-menuUnder">
            <div class="header-helper2 js-header-helper"></div>
            <header class="header js-header">
                <div class="header__top header__top_backoffice">
                    <div class="typicalBlock typicalBlock_two header-topInner">
                        <div class="header-topInner__block header-topInner__block_title">
                            Бэкофис Электрик.ру
                        </div>
                        <a href="#" class="header-topInner__burger js-backofficeMenu-toggle">
                            <span class="header-topInner__burger-line"></span>
                            <span class="header-topInner__burger-line"></span>
                            <span class="header-topInner__burger-line"></span>
                        </a>
                        <div class="header-topInner__block">
                            <a href="/" class="header-topInner__logo">
                                <img src="/local/templates/.default/img/logo.png"
                                     srcset="/local/templates/.default/img/logo@2x.png 2x,
                                                         /local/templates/.default/img/logo@3x.png 3x"
                                     class="header-topInner__logo-img" alt="электрик.ру">
                            </a>
                        </div>
                        <div class="header-topInner__block header-person">
                            <div class="header-person__functions header-person__block js-header-person__functions" :class="{'selected' : showFunctionsMenu}">
                                <a href="#"
                                   class="header-person__functions-name"
                                   @click.prevent="toggleShowFunctionsMenu()">
                                    <span class="header-person__functions-name-inner">{{user.name}}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="svg-icon">
                                        <path fill="none" fill-rule="nonzero" stroke-width="2" d="M13.314 5.657l-5.657 5.657L2 5.657"/>
                                    </svg>
                                </a>
                                <div class="header-person-menu" :class="{'active' : showFunctionsMenu}">
                                    <div class="header-person-menu-inner">
                                        <div class="header-person-menu__single" v-for="item in functionsMenu"
                                             :class="{'header-person-menu__single_logout' : (item.code == 'logout')}">
                                            <a :href="'#'+item.code"
                                               data-fancybox
                                               :data-src="'#'+item.code"
                                               v-if="item.code == 'edit_alerts' || item.code == 'edit_password'">
                                                {{item.title}}
                                            </a>
                                            <a :href="'/'+item.code+'/'"
                                               v-else-if="item.code == 'logout'">
                                                {{item.title}}
                                            </a>
                                            <router-link v-else="" @click.native="toggleShowFunctionsMenu()" :to="{name:item.code}" class="backofficeMenu-functions__single-link">
                                                {{item.title}}
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="header-person__alert header-person__block js-header-person__alert"
                                 :class="{'selected' : showNotifications, 'new' : notifications['NEW'] || notifications['NEW_VIEWS']}">
                                <a href="#"
                                   class="js-backoffice-alert-toggler header-person__alert-bell"
                                   @click.prevent="toggleShowNotifications()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                                        <g fill="none" fill-rule="evenodd" stroke="" stroke-width="2">
                                            <path d="M7.186 10.698l.967-3.88a5 5 0 1 1 9.703 2.418l-.967 3.881M18.59 18.695a3 3 0 0 1-2.185-3.637l.484-1.94"/>
                                            <path d="M18.59 18.695L3.065 14.824a3 3 0 0 0 3.637-2.185l.484-1.94M8.161 19.186s.627 1.187 1.699 1.455c1.072.267 2.182-.487 2.182-.487"/>
                                        </g>
                                    </svg>
                                </a>
                                <div class="header-person-menu" :class="{'active' : showNotifications}">
                                    <div class="header-person-menu-inner">

                                        <div class="header-person-menu__type header-person-menu__type_new" v-if="notifications['NEW'] || notifications['NEW_VIEWS']">
                                            <div class="header-person-menu__type-title">
                                                <span>
                                                    новые
                                                </span>
                                            </div>
                                            <div class="header-person-menu__single header-person-menu__single_notification" v-for="notification in notifications['NEW']">
                                                <a :href="notification['LINK']" v-if="notification['LINK']" v-html="notification['TEXT']"></a>
                                                <span v-else v-html="notification['TEXT']"></span>
                                            </div>
                                            <div class="header-person-menu__single header-person-menu__single_notification" v-if="notifications['NEW_VIEWS']">
                                                <span v-html="notifications['NEW_VIEWS']"></span>
                                            </div>
                                        </div>

                                        <div class="header-person-menu__type">
                                            <div class="header-person-menu__type-title">
                                                <span>
                                                    просмотренные
                                                </span>
                                            </div>
                                            <div class="header-person-menu__single header-person-menu__single_notification" v-for="notification in notifications['VIEWED']">
                                                <a :href="notification['LINK']" v-if="notification['LINK']" v-html="notification['TEXT']"></a>
                                                <span v-else v-html="notification['TEXT']"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="notification js-notification" v-if="$store.getters.getSertificate == false && $route.name == 'news_and_statistics'">
                <div class="notification-inner typicalBlock typicalBlock_two">
                    <div class="notification__text">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 71 28" class="svg-icon">
                            <path fill="#D70926" fill-rule="nonzero" d="M0 6.172V26.05a1.2 1.2 0 0 0 1.644 1.115L31.206 15.38a1.2 1.2 0 0 1 1.645 1.115v4.188a1.2 1.2 0 0 0 1.715 1.084L69.427 5.199a1.2 1.2 0 0 0-.703-2.27L34.24 8.394a1.2 1.2 0 0 1-1.388-1.185V2.125a1.2 1.2 0 0 0-1.358-1.19L1.042 4.983A1.2 1.2 0 0 0 0 6.172z"/>
                        </svg>
                        <div class="notification__text-inner">
                            Пройдите сертификацию Электрик.ру
                        </div>
                    </div>
                    <div class="notification__button">
                        <a href="#make_sertificate_electric" data-fancybox="" data-src="#make_sertificate_electric" class="button button_white button_md">
                            Пройти сертификацию
                        </a>
                    </div>
                    <div class="notification__close js-notification-close">
                        <a href="#" class="notification__close-inner">
                            <span class="notification__close-inner-line"></span>
                            <span class="notification__close-inner-line"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="backoffice">
                <div class="backoffice-inner typicalBlock typicalBlock_two">
                    <div class="personMenu" :class="{'personMenu_edit' : ($route.name == 'edit_data'
                    || $route.name == 'edit_info' || $route.name == 'edit_services'
                    || $route.name == 'edit_works' || $route.name == 'edit_sertificates')}" v-if="$route.name != 'news_detail'">
                        <div class="personMenu-inner">
                            <router-link v-for="(item, key) in personMenu" :to="{name:item.code}" class="personMenu__single">
                                {{item.title}}
                                <span v-if="item.code == 'reviews' && user.newReviews  && user.newReviews != 0" class="personMenu__number">
                                    {{user.newReviews}}
                                </span>
                                <span v-if="item.code == 'articles' && user.newArticlesComm  && user.newArticlesComm != 0" class="personMenu__number">
                                    {{user.newArticlesComm}}
                                </span>
                            </router-link>
                        </div>
                    </div>
                    <router-view></router-view>
                </div>
            </div>
        </div>
        <footer class="footer footer_backoffice js-footer js-menuUnder">
            <div class="footer-top">
                <div class="footer-top-inner typicalBlock typicalBlock_two">
                    <div class="footer-menu">
                        <div class="footer-menu__single">
                            <a href="/about/" class="footer-menu__single-inner">
                                О проекте
                            </a>
                        </div>
                        <div class="footer-menu__single">
                            <a href="/articles/" class="footer-menu__single-inner">
                                Статьи
                            </a>
                        </div>
                        <div class="footer-menu__single">
                            <a href="/services/" class="footer-menu__single-inner">
                                Услуги
                            </a>
                        </div>
                        <div class="footer-menu__single">
                            <a href="/service/" class="footer-menu__single-inner">
                                Сервисы
                            </a>
                        </div>
                        <div class="footer-menu__single">
                            <a href="/training/" class="footer-menu__single-inner">
                                Обучение
                            </a>
                        </div>
                        <div class="footer-menu__single">
                            <a href="/events/" class="footer-menu__single-inner">
                                Мероприятия
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-inner typicalBlock typicalBlock_two">
                    <div class="footer-bottom__single">
                        <a href="/terms/">
                            Политика обработки персональных данных
                        </a>
                    </div>
                    <div class="footer-bottom__single">
                        2019 Электрик.ру
                    </div>
                </div>
            </div>
        </footer>
        <!--
        <div id="edit_alerts" class="fancyBlock">
            <div class="fancybox-title">Настройки уведомлений</div>
            <form id="edalerts" name="edalerts">
                <div class="formGroup">
                    <div class="formGroup-inner">
                        <div class="slideToggler">
                            <input type="checkbox" value="1" id="edalerts-alertMail" name="edalerts-alertMail" v-model="user.notifications.mail">
                            <label for="edalerts-alertMail" class="custLabel">
                                Получать уведомления на почту
                            </label>
                        </div>
                    </div>
                </div>
                <div class="formGroup">
                    <div class="formGroup-inner">
                        <div class="slideToggler">
                            <input type="checkbox" value="1" id="edalerts-alertSms" name="edalerts-alertMail"  v-model="user.notifications.sms">
                            <label for="edalerts-alertSms" class="custLabel">
                                Получать уведомления по смс
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        -->
        <div id="make_sertificate_raek" class="fancyBlock">
            <div class="fancyBlock-img" v-if="!isRaekRequestSent">
                <img src="/local/templates/.default/img/trash/soloForm3.jpg" alt="">
            </div>
            <div class="fancyBlock-inner">
                <div class="fancybox-title">Членство РАЭК</div>
                <template v-if="!isRaekRequestSent">
                    <p>Отправьте свои контактные данные и специалист свяжется с вами.</p>
                    <form action="" name="sertificateRaek" id="sertificateRaek" class="fullForm fullForm_two">
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <label for="sertificateRaek-name">ФИО</label>
                                <input type="text" v-model="raekRequest.name"
                                       id="sertificateRaek-name"
                                       name="sertificateRaek-name" maxlength="512" required placeholder="ФИО">
                            </div>
                        </div>
                        <!--
                        <div class="formGroup">
                            <div class="formGroup-inner">
                                <label for="sertificateRaek-company">Компания</label>
                                <input type="text" v-model="raekRequest.company" id="sertificateRaek-company"
                                       name="sertificateRaek-company" maxlength="512" placeholder="Компания">
                            </div>
                        </div>
                        -->
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <label for="sertificateRaek-phone">Телефон</label>
                                <input type="tel" v-model="raekRequest.phone" id="sertificateRaek-phone"
                                       name="sertificateRaek-phone" maxlength="512" required placeholder="Телефон">
                            </div>
                        </div>
                        <div class="formGroup required">
                            <div class="formGroup-inner">
                                <label for="sertificateRaek-email">E-mail</label>
                                <input type="email" v-model="raekRequest.email" id="sertificateRaek-email"
                                       name="sertificateRaek-email" maxlength="512" required placeholder="E-mail">
                            </div>
                        </div>
                        <div class="formGroup formGroup__bottom">
                            <div class="formGroup-inner">
                                <button type="submit" name="sertificateRaek-submit" v-on:click.prevent="sendRaekRequest()">
                                    Отправить заявку
                                </button>
                            </div>
                        </div>
                    </form>
                </template>
                <template v-else>
                    {{ raekRequestMessage }}
                </template>
            </div>

        </div>
        <div id="confirm_sertificate_raek" class="fancyBlock">
            <div class="fancybox-title">Членство РАЭК</div>
            <template v-if="!isRaekVerificationSent">
                <p>
                    Если вы уже прошли сертификацию РАЭК и имеете свидетельство,
                    то загрузите его здесь и дождитесь когда наши специалисты вам перезвонят для подтверждения.
                    <br>
                    <br>
                </p>
                <form action="" name="sertificateRaekConfirm" id="sertificateRaekConfirm" class="fullForm">
                    <div class="formGroup required">
                        <div class="formGroup-inner">
                            <div class="backofficeCustFile js-custFile">
                                <label for="sertificateRaekConfirm-file" class="custLabel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon">
                                        <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                            <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"/>
                                        </g>
                                    </svg>
                                    Загрузить свидетельство о регистрации
                                </label>
                                <input type="file"
                                       value=""
                                       name="sertificateRaekConfirm-file"
                                       id="sertificateRaekConfirm-file"
                                       @change="changeRaekVerification($event)"
                                       required>
                                <div class="custFile-list js-custFile-list">
                                    <div class="custFile-list-single js-custFile-list-single"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="formGroup">
                        <div class="formGroup-inner">
                            <button type="submit" name="sertificateElectric-submit" v-on:click.prevent="sendRaekVerification()">
                                Подтвердить данные
                            </button>
                        </div>
                    </div>
                </form>
            </template>
            <template v-else>
                {{ raekVerificationMessage }}
            </template>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import $ from 'jquery'
    window.jQuery = $;
    require("@fancyapps/fancybox");
    import 'slick-carousel';

    //IE closest
    !function(e){var t=e.Element.prototype;"function"!=typeof t.matches&&(t.matches=t.msMatchesSelector||t.mozMatchesSelector||t.webkitMatchesSelector||function(e){for(var t=(this.document||this.ownerDocument).querySelectorAll(e),o=0;t[o]&&t[o]!==this;)++o;return Boolean(t[o])}),"function"!=typeof t.closest&&(t.closest=function(e){for(var t=this;t&&1===t.nodeType;){if(t.matches(e))return t;t=t.parentNode}return null})}(window);

    export default {
        name: 'backoffice',
        data () {
            return {
                user: {
                    notifications: {},
                },
                raekRequest: {
                    name: '',
                    company: '',
                    phone: '',
                    email: '',
                },
                raekVerification: '',
                isRaekRequestSent: false,
                isRaekVerificationSent: false,
                raekRequestMessage: 'Ваша заявка отправлена.',
                raekVerificationMessage: 'Ваша заявка отправлена.',
                showFunctionsMenu: false,
                functionsMenu: [{
                    title: 'Редактировать анкету',
                    code: 'edit_data',
                },{
                    title: 'Сменить пароль',
                    code: 'edit_password',
                },{
                    title: 'Выйти',
                    code: 'logout',
                },],
                showNotifications: false,
                notifications: {
                    new: [{
                        type: 'review',
                        reviewAuthor: 'natalia_petrova',
                        id: 1,
                    },{
                        type: 'watch',
                        watchNumber: 26,
                    },],
                    old: [{
                        type: 'comment',
                        commentAuthor: 'Сергей Рукавишников',
                        id: 2,
                    },{
                        type: 'watch',
                        watchNumber: 128,
                    },{
                        type: 'review',
                        reviewAuthor: 'alexei_ivanov',
                        id: 3,
                    },{
                        type: 'review',
                        reviewAuthor: 'khvorskiy_evgeny',
                        id: 4,
                    },{
                        type: 'notification',
                        notificationText: 'Ваш профиль прошел модерацию',
                    },],
                },
                personMenu: [{
                    code: 'news_and_statistics',
                    title: 'Новости и статистика',
                },{
                    code: 'worksheet',
                    title: 'Анкета',
                },{
                    code: 'reviews',
                    title: 'Отзывы',
                },{
                    code: 'articles',
                    title: 'Мои статьи',
                },{
                    code: 'questions_and_answers',
                    title: 'Вопросы и ответы',
                },],
            }
        },
        methods: {
            changeRaekVerification (event) {
                let reader  = new FileReader();
                let that = this;
                reader.onloadend = function () {
                    that.raekVerification = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            },
            toggleShowFunctionsMenu () {
                event.preventDefault();
                this.showFunctionsMenu = !this.showFunctionsMenu;
            },
            toggleShowNotifications () {
                event.preventDefault();
                this.showNotifications = !this.showNotifications;
                if (this.notifications["NEW"] || this.notifications["NEW_VIEWS"]) {
                    axios({
                        method: 'post',
                        url: '/api/notifications.update.viewed/',
                        data: {},
                    });
                }
            },
            backofficeMenuToggle () {
                $('.js-backofficeMenu-toggle').toggleClass('menuActive');
                $('.js-backofficeMenu').toggleClass('menuActive');
            },
            sendRaekRequest () {
                axios({
                    method: 'post',
                    url: '/api/backoffice.raek.add.request/',
                    data: this.raekRequest,
                }).then((response) => {
                    if (response.data.result) {
                        this.isRaekRequestSent = true;
                        this.$store.commit('setRaekRequest', true);
                    } else if (response.data.error === 'request_already_exists') {
                        this.raekRequestMessage = "Заявка уже существует.";
                        this.isRaekRequestSent = true;
                    }
                });
            },
            sendRaekVerification () {
                axios({
                    method: 'post',
                    url: '/api/backoffice.raek.add.verification/',
                    data: {
                        document: this.raekVerification,
                    },
                }).then((response) => {
                    if (response.data.result) {
                        this.isRaekVerificationSent = true;
                        this.$store.commit('setRaekRequest', true);
                    } else if (response.data.error === 'verification_already_exists') {
                        this.raekVerificationMessage = "Заявка уже существует.";
                        this.isRaekVerificationSent = true;
                    }
                });
            },
        },
        beforeMount () {
            axios.get('/api/backoffice.user/').then((response) => {
                this.user = response.data.result.user;
                this.notifications = response.data.result.notifications;
                this.$store.commit('setCertificate', response.data.result.certified);
                this.$store.commit('setCertificationRequest', response.data.result.certificationRequest);
                this.$store.commit('setRaek', response.data.result.raek);
                this.$store.commit('setRaekRequest', response.data.result.raekRequest);
                this.raekRequest.name = this.user.name;
                this.raekRequest.phone = this.user.phone;
                this.raekRequest.email = this.user.email;
            });
        },
        mounted: function () {
            let that = this;
            document.addEventListener('click', function(event){
                if (event.target.closest('.js-header-person__functions') == null) {
                    if (that.showFunctionsMenu) {
                        that.showFunctionsMenu = false;
                    }
                }
                if (event.target.closest('.js-header-person__alert') == null) {
                    if (that.showNotifications) {
                        that.showNotifications = false;
                    }
                }
            });
        },
        components: {

        },
    }

</script>

