<template>
    <div class="backofficeMain">
        <div class="backofficeMain-block">
            <div class="backofficeMain-block-inner">
                <div class="backofficeMain-block__top">
                    <div class="backoffice__title">
                        Отзывы
                    </div>
                    <div class="backofficeMain-block__top-side backofficeMain-block__top-sort">
                        <span class="backofficeMain-block__top-sort-text">
                            Сортировка:
                        </span>
                        <multiselect v-model="sort"
                                     :options="sortList"
                                     placeholder="Тип сортировки"
                                     class=""
                                     track-by="name"
                                     label="name"
                                     :searchable="false">
                            <span slot="noResult">По данному запросу ничего не найдено</span>
                        </multiselect>
                    </div>
                </div>

                <div class="marketDetail-reviews-list">
                    <div class="marketDetail-reviews-list__single js-marketDetail-comment-single" v-bind:data-answer-form="review.id" v-for="(review, index) in reviews">

                        <div class="marketDetail-reviews-list__top">
                            <div class="marketDetail-reviews-list__author">
                                <span v-if="review.new" class="marketDetail-reviews-list__new">(новый)</span> {{ review.author }}
                            </div>
                            <div class="marketDetail-reviews-list__date">
                                {{ review.date }}
                            </div>
                        </div>
                        <div class="marketList__numbers-rait js-raitWr marketDetail-reviews-list__rait">
                            <span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2666.66875 426.667"
                                     width="96" height="16">
                                    <g transform="translate(53.33375 0)">
                                        <polygon style="fill:#dfe0e0;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                    </g>
                                    <g transform="translate(586.66825 0)">
                                        <polygon style="fill:#dfe0e0;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                    </g>
                                    <g transform="translate(1120.00275 0)">
                                        <polygon style="fill:#dfe0e0;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                    </g>
                                    <g transform="translate(1653.33725 0)">
                                        <polygon style="fill:#dfe0e0;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                    </g>
                                    <g transform="translate(2186.67175 0)">
                                        <polygon style="fill:#dfe0e0;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                    </g>
                                </svg>
                            </span>
                            <div class="marketList__numbers-rait-inner js-rait"
                                 :data-rait="review.raiting">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2666.66875 426.667"
                                         width="96" height="16">
                                        <g transform="translate(53.33375 0)">
                                            <polygon style="fill:#d70926;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
                                        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                        </g>
                                        <g transform="translate(586.66825 0)">
                                            <polygon style="fill:#d70926;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
                                        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                        </g>
                                        <g transform="translate(1120.00275 0)">
                                            <polygon style="fill:#d70926;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
                                        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                        </g>
                                        <g transform="translate(1653.33725 0)">
                                            <polygon style="fill:#d70926;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
                                        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                        </g>
                                        <g transform="translate(2186.67175 0)">
                                            <polygon style="fill:#d70926;" points="213.333,10.441 279.249,144.017 426.667,165.436 320,269.41 345.173,416.226 213.333,346.91
                                        81.485,416.226 106.667,269.41 0,165.436 147.409,144.017 "/>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="marketDetail-reviews-list__text">
                            {{ review.text }}
                        </div>
                        <div class="marketDetail-reviews-list__answer js-marketDetail-reviews-list__answer-bottom" v-if="review.answer && review.answer != ''">
                            <div class="marketDetail-reviews-list__answer-top">
                                <div class="marketDetail-reviews-list__author">
                                    {{ $parent._data.user.name }}
                                </div>
                            </div>
                            <div class="marketDetail-reviews-list__answer-text">
                                {{ review.answer }}
                            </div>
                            <a href="#" class="marketDetail-reviews-list__answer-edit js-marketDetail-reviews-list__answer-toggler">
                                <span class="svg-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-linejoin="round" stroke-width="2">
                                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                        </div>
                        <div class="marketDetail-reviews-list__bottom
                        js-marketDetail-reviews-list__answer-bottom"
                             v-else="">
                            <a href="#" class="js-marketDetail-reviews-list__answer-toggler">
                                Ответить
                            </a>
                        </div>
                        <div class="marketDetail-reviews-list__form js-marketDetail-reviews-list__answer-form">
                            <form action="#" class="marketDetail-reviews__comment-form articlesPage-comments__form" :id="'comment-'+review.id" v-on:submit.prevent="saveAnswer(review.id, index, review.newanswer)">
                                <input type="hidden" :name="'comment-'+review.id+'-answer'" :id="'comment-'+review.id+'-answer'">
                                <div class="formGroup required">
                                    <div class="formGroup-inner">
                                        <label :for="'comment-'+review.id+'-text'">
                                            Ответьте на отзыв
                                        </label>
                                        <textarea :name="'comment-'+review.id+'-text'" :id="'comment-'+review.id+'-text'"
                                                  class="marketDetail-reviews__comment-textarea js-textarea-sm"
                                                  maxlength="2048" required v-model="review.newanswer" placeholder="Ответьте на отзыв">
                                        </textarea>
                                        <a href="#" class="js-marketDetail-reviews-list__answer-toggler
                                                marketDetail-reviews-list__answer-cancel">
                                            <span class="marketDetail-reviews-list__answer-cancel-line"></span>
                                            <span class="marketDetail-reviews-list__answer-cancel-line"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="formGroup formGroup__bottom">
                                    <div class="formGroup-inner">
                                        <button type="submit" :name="'comment-'+review.id+'-submit'">
                                            Отправить
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    import $ from 'jquery';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect'

    //Определение автозаполнения webkit браузеров
    let isWebKit = !!window.webkitURL;
    function checkWebkitAutofill (that) {
        let webkitAutofill = false;
        if (isWebKit) {
            webkitAutofill = that.is(':-webkit-autofill');
        }
        return webkitAutofill;
    }

    //Закрашивание звезд рейтинга
    function starRaiting (that, number) {
        let rait = 0;
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

    //Смещение label при фокусе или если в поле есть текст
    function labelTransfer(that) {
        if (that.closest('.formGroup').length != 0
            && that.closest('.formGroup').find('label').length != 0
            && that.closest('.formGroup').find('.custCheckbox').length == 0
            && that.closest('.formGroup').find('.custRadio').length == 0) {
            if (that.val() != '' || that.is(":focus") || checkWebkitAutofill (that)) {
                that.closest('.formGroup').find('label').addClass('active');
                if (that.closest('.formGroup').find('.showPass').length != 0) {
                    that.closest('.formGroup').find('.showPass').addClass('active');
                }
                setTimeout(function () {
                    let labelWidth = that.closest('.formGroup').find('label').innerWidth();
                    if (that.closest('.formGroup').hasClass('formGroup_search') == false) {
                        that.css({'padding-right': labelWidth });
                    } else {
                        that.closest('.formGroup').find('.js-cleanIcon').addClass('active');
                    }
                },300);
            } else {
                let paddingRightStart = that.attr('data-padding-right');
                that.css({ 'padding-right': paddingRightStart });
                that.closest('.formGroup').find('label').removeClass('active');
                if (that.closest('.formGroup').find('.showPass').length != 0) {
                    that.closest('.formGroup').find('.showPass').removeClass('active');
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

    function propComparator(prop) {
        return function(a, b) {
            if (b[prop] <  a[prop]) {
                return -1;
            }
            if (b[prop] > a[prop]) {
                return 1;
            }
            return 0;
        }
    }

    export default {
        name: 'reviews',
        data: function () {
            return {
                updateUrl: '/api/backoffice.reviews.update.answer/',
                sortList: [
                    {code: 'dateNumber', name: 'По дате публикации'},
                    {code: 'raiting', name: 'По оценке'},
                ],
                sort: {
                    code: 'dateNumber',
                    name: 'По дате публикации'
                },
                raitingWidth: 96,
                reviews: [],
            }
        },
        components: {
            Multiselect
        },
        watch : {
            sort: {
                handler: function (newValue) {
                    this.reviews = this.reviews.sort(propComparator(this.sort.code));
                    setTimeout(function () {
                        $('.js-rait').each(function () {
                            starRaiting ($(this));
                        });
                        $('.formGroup.required textarea').each(function () {
                            requiredFull ($(this));
                            labelTransfer ($(this));
                        });
                    }, 100);
                },
                deep: true
            }
        },
        methods: {
            saveAnswer (reviewID, reviewIndex, answerText) {
                let data = {
                    reviewID: reviewID,
                    answerText: answerText,
                };
                axios({
                    method: 'post',
                    url: this.updateUrl,
                    data: data,
                }).then((response) => {
                    $('[data-answer-form=' + reviewID + ']').children('.js-marketDetail-reviews-list__answer-form').slideToggle(300);
                    $('[data-answer-form=' + reviewID + ']').children('.js-marketDetail-reviews-list__answer-bottom').slideToggle(300);
                    this.reviews[reviewIndex].answer = answerText;
                });
            },
        },
        mounted () {
            axios.get('/api/backoffice.reviews/').then((response) => {
                this.reviews = response.data.result;
                this.$parent._data.user.newReviews = 0;
                setTimeout(function () {
                    $('.js-rait').each(function () {
                        starRaiting ($(this));
                    });
                }, 100);
            });

            axios({
                method: 'post',
                url: '/api/reviews.update.viewed/',
            });

            $('.formGroup.required textarea').each(function () {
                requiredFull ($(this));
                // labelTransfer ($(this));
            });
        },
    }
</script>
