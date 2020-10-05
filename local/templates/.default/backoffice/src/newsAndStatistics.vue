<template>
    <div class="backofficeMain">
        <div class="backoffice__mobileTitle">
            Новости и статистика
        </div>
        <div class="backofficeNews">
            <div class="backofficeNews-inner">
                <div class="backoffice__title">
                    Новости
                </div>
                <div class="backofficeNews-list">
                    <div class="backofficeNews-list__single" v-for="item in news">
                        <div class="backofficeNews-list__single-date">
                            {{item.date}}
                        </div>
                        <div class="backofficeNews-list__single-title">
                            <router-link :to="{name: 'news_detail', params: {id: item.id}}" class="backofficeNews-list__single-title-link inhLink">
                                {{item.description}}
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="personStats">
            <div class="personStats-inner">
                <div class="backoffice__title">
                    Статистика
                </div>
                <div class="personStats-types js-tabs">
                    <div class="personStats-types-toggler js-tabs-buttons">
                        <div class="formSolo-workarea-toggler__single personStats-types-toggler__single js-tabs-buttons-single" v-for="(item, key) in statistics">
                            <a :href="'#'+item.code"
                               class="formSolo-workarea-toggler__single-inner
                                           personStats-types-toggler__single-inner
                                           button2
                                           button2_large
                                           js-tabs-buttons-single-inner"
                               :class="{'active' : key == 'all'}">
                                {{item.title}}
                            </a>
                        </div>
                    </div>
                    <div class="personStats-types-list js-slider-statistics js-tabs-content">
                        <div class="personStats-types-list__single js-tabs-content-single" :data-id="item.code" v-for="item in statistics">
                            <div class="personStats-types-list__block">
                                <div class="personStats-types-list__title">
                                    Рейтинг
                                </div>
                                <div class="personStats-types-list__number personStats-types-list__number_important">
                                    {{item.raitingNumber}}
                                    <span class="personStats-types-list__number-note" v-if="item.raitingProgress != null && item.raitingProgress != ''">
                                        {{item.raitingProgress}}
                                    </span>
                                </div>
                            </div>
                            <div class="personStats-types-list__block">
                                <div class="personStats-types-list__title">
                                    Отзывов:
                                </div>
                                <div class="personStats-types-list__number personStats-types-list__number_important">
                                    {{item.reviewsNumber}}
                                </div>
                            </div>
                            <div class="personStats-types-list__block">
                                <div class="personStats-types-list__title">
                                    Просмотров анкеты
                                </div>
                                <div class="personStats-types-list__number">
                                    {{numberSpace(item.watchWorksheet)}}
                                </div>
                            </div>
                            <div class="personStats-types-list__block">
                                <div class="personStats-types-list__title">
                                    Просмотров контактов
                                </div>
                                <div class="personStats-types-list__number">
                                    {{numberSpace(item.watchContacts)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery'
    import axios from 'axios';

    export default {
        name: 'newsAndStatics',
        data: function () {
            return {
                news: [],
                statistics: {
                    all:
                    {
                        title: 'За все время',
                        code: 'allStatistics',
                        raitingNumber: '',
                        raitingProgress: '',
                        reviewsNumber: '',
                        watchWorksheet: '',
                        watchContacts: '',
                    },
                    month:
                    {
                        title: 'За месяц',
                        code: 'monthStatistics',
                        raitingNumber: '',
                        raitingProgress: '',
                        reviewsNumber: '',
                        watchWorksheet: '',
                        watchContacts: '',
                    },
                },
            }
        },
        methods: {
            numberSpace (number) {
                return String(number).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            },
        },
        mounted: function () {
            $('.js-slider-statistics').slick({
                arrows: false,
                dots: false,
                infinite: false,
                swipe: false,
                rows: 0
            });

            axios.get('/api/backoffice.news.list/').then((response) => {
                this.news = response.data.result;
            });
            axios.get('/api/backoffice.statistics/').then((response) => {
                Object.assign(this.statistics.all, response.data.result.all);
                Object.assign(this.statistics.month, response.data.result.month);
                this.raitingProgress = response.data.result.ratingChange;

            });

        },

    }
</script>