<template>
    <div class="backofficeMain">
        <div class="breadcrumbs breadcrumbs_backoffice" v-if="$route.name != 'worksheet'">
            <div class="breadcrumbs-inner">
                <div class="breadcrumbs-list">
                    <router-link :to="{name:'worksheet'}" class="breadcrumbs-list__single">
                        <span class="svg-icon ">
                            <svg>
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-arrow-down"></use>
                            </svg>
                        </span>
                        <span class="breadcrumbs-list__single-text">Выйти из редактирования профиля</span>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="backofficeEditMenu js-backofficeEditMenu" v-if="$route.name != 'worksheet'">
            <div class="backofficeEditMenu-inner js-backofficeEditMenu-inner">
                <div class="backofficeEditMenu__single js-backofficeEditMenu__single" v-for="(item, key) in editMenu">
                    <router-link :to="{name:item.code}" class="backofficeEditMenu__single-link inhLink js-backofficeEditMenu__single-link">
                        {{item.title}}
                    </router-link>
                </div>
                <div class="backofficeEditMenu__helper"></div>
            </div>
        </div>

        <router-view v-if="isDataLoaded" ref="componentData"></router-view>

        <div id="change_detected" class="fancyBlock">
            <div class="fancybox-title">Сохранить изменения?</div>
            <p>
                Вы изменили информацию. Возможно вы хотите сохранить данные?
            </p>
            <div class="fancybox-buttons">
                <a href="#" class="button3" @click.prevent="$refs.componentData.saveData()" v-if="validValues">
                    Сохранить изменения
                </a>
                <a href="#" class="button3" @click.prevent="goEditData()" v-else="">
                    Сохранить изменения
                </a>
                <a href="#" class="formGroup__action" @click.prevent="dontSave()">
                    Продолжить без сохранения
                </a>
            </div>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery';
    import axios from 'axios';

    export default {
        name: 'worksheet',
        data: function () {
            return {
                isDataLoaded: false,
                startValues: true,
                validValues: true,
                routeTo : 'worksheet',
                user: {
                    img: {},
                },
                editMenu: [{
                    code: 'edit_data',
                    title: 'Данные и контакты',
                },{
                    code: 'edit_info',
                    title: 'Информация',
                },{
                    code: 'edit_services',
                    title: 'Услуги и цены',
                },{
                    code: 'edit_works',
                    title: 'Примеры работ',
                },{
                    code: 'edit_sertificates',
                    title: 'Сертификаты',
                }],
            }
        },
        methods: {
            scrollToActive () {
                if ($('.js-backofficeEditMenu').length != 0) {
                    let position = $('.js-backofficeEditMenu__single-link.active').position().left - $('.js-backofficeEditMenu-inner').position().left;
                    let width = $('.js-backofficeEditMenu__single-link.active').outerWidth();
                    $('.js-backofficeEditMenu').scrollLeft(position - width/2);
                }
            },
            saveData (data, apiUrl, reload = false) {
                this.startValues = true;

                $.fancybox.close();
                axios({
                    method: 'post',
                    url: apiUrl,
                    data: data,
                }).then((response) => {
                    for (let prop in data) {
                        this.user[prop] = data[prop];
                    }
                    if (reload) this.loadData();

                    this.startValues = true;
                    this.$router.push({name: this.routeTo});
                });
            },
            dontSave () {
                this.startValues = true;
                $.fancybox.close();
                this.$router.push({name: this.routeTo});
            },
            goEditData () {
                $.fancybox.close();
                if ($('.backofficeFormGroup_empty').length != 0) {
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $('.backofficeFormGroup_empty').offset().top
                    }, 300);
                } else {
                    if ($('.backofficeFormGroup__error').length != 0) {
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $('.backofficeFormGroup__error').offset().top
                        }, 300);
                    }
                }
            },
            loadData () {
                axios.get('/api/backoffice.worksheet/').then((response) => {
                    this.user = response.data.result;
                    this.isDataLoaded = true;
                });
            }
        },
        beforeRouteUpdate (to, from, next) {
            if (from.name != 'worksheet') {
                // this.routeTo = to.name;
                if (this.startValues === false) {
                    $.fancybox.open({
                        src: '#change_detected',
                        touch: false,
                    });
                } else {
                    $(window).scrollTop(0);
                    next();
                }
            } else {
                $(window).scrollTop(0);
                next();
            }
        },
        created () {
            this.loadData();
        },
        mounted () {
            this.scrollToActive();
        },
        updated: function () {
            this.$nextTick(function () {
                this.scrollToActive();
            });
        },

    }
</script>
