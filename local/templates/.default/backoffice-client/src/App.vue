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
            <header class="header">
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
                        </div>
                    </div>
                </div>
            </header>

            <div class="backoffice">
                <div class="backoffice-inner typicalBlock typicalBlock_two">
                    <div class="personMenu" :class="{'personMenu_edit' : ($route.name == 'edit_data')}">
                        <div class="personMenu-inner">
                            <router-link v-for="(item, key) in personMenu" :to="{name:item.code}" class="personMenu__single">
                                {{item.title}}
                            </router-link>
                            <a href="#edit_password" data-fancybox data-src="#edit_password" class="personMenu__single">Сменить пароль</a>
                            <a href="/logout/" class="personMenu__single">Выйти</a>
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

    </div>
</template>

<script>
    import axios from 'axios';
    import $ from 'jquery'
    window.jQuery = $;
    require("@fancyapps/fancybox");

    //IE closest
    !function(e){var t=e.Element.prototype;"function"!=typeof t.matches&&(t.matches=t.msMatchesSelector||t.mozMatchesSelector||t.webkitMatchesSelector||function(e){for(var t=(this.document||this.ownerDocument).querySelectorAll(e),o=0;t[o]&&t[o]!==this;)++o;return Boolean(t[o])}),"function"!=typeof t.closest&&(t.closest=function(e){for(var t=this;t&&1===t.nodeType;){if(t.matches(e))return t;t=t.parentNode}return null})}(window);

    export default {
        name: 'backoffice',
        data () {
            return {
                user: {
                    notifications: {},
                },
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
                personMenu: [{
                    title: 'Анкета',
                    code: 'worksheet',
                }],
            }
        },
        methods: {

            toggleShowFunctionsMenu () {
                event.preventDefault();
                this.showFunctionsMenu = !this.showFunctionsMenu;
            },
            toggleShowNotifications () {
                event.preventDefault();
                this.showNotifications = !this.showNotifications;
            },
            backofficeMenuToggle () {
                $('.js-backofficeMenu-toggle').toggleClass('menuActive');
                $('.js-backofficeMenu').toggleClass('menuActive');
            },

        },
        beforeMount () {
            axios.get('/api/backoffice.user.client/').then((response) => {
                this.user = response.data.result.user;
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
    }

</script>

