<template>
    <div class="backofficeMain backofficeMain_faq">
        <div class="backofficeMain-block">
            <div class="backofficeMain-block-inner">
                <div class="backoffice__title">
                    Обращение №{{ questionsDetail.id }} от {{ questionsDetail.date }}  <template v-if="questionsDetail.isClosed">(закрыто)</template>
                </div>
                <div class="tpQuestions">

                    <div class="tpQuestions__single js-tpQuestions-single">

                        <div class="tpQuestions__title">
                            {{ questionsDetail.title }}
                        </div>

                        <div class="tpQuestions__text js-tpQuestions-text active">
                            {{ questionsDetail.text }}
                        </div>

                        <div class="masterWorks-content" v-if="questionsDetail.files && questionsDetail.files != 0">
                            <div class="masterWorks-content__single" v-for="src in questionsDetail.files">
                                <a :href="src"
                                   class="masterWorks-content__single-inner"
                                   data-fancybox="gallery-works"
                                   data-fancybox-img
                                   >
                                    <img :src="src"
                                         class="masterWorks-content__single-img">
                                </a>
                            </div>
                        </div>

                        <div class="tpQuestions__answer active js-tpQuestions-answer" v-if="questionsDetail.answer && questionsDetail.answer != ''">
                            <div class="tpQuestions__answer-top">
                                <div class="tpQuestions__answer-author">
                                    Ответ поддержки
                                </div>
                                <div class="tpQuestions__answer-date">
                                    {{ questionsDetail.answerDate }}
                                </div>
                            </div>
                            <div class="tpQuestions__answer-text">
                                {{ questionsDetail.answer }}
                            </div>
                        </div>

                        <div class="tpQuestions__answer active js-tpQuestions-answer" v-if="questionsDetail.authorAnswer && questionsDetail.authorAnswer != ''">
                            <div class="tpQuestions__answer-top">
                                <div class="tpQuestions__answer-author">
                                    Ваш ответ
                                </div>
                            </div>
                            <div class="tpQuestions__answer-text">
                                {{ questionsDetail.authorAnswer }}
                            </div>
                        </div>

                        <template v-if="!questionsDetail.authorAnswer && questionsDetail.answer">
                            <br>
                            <a href="#do-question" data-fancybox data-src="#do-question" class="button3" v-on:click="openQuestionPopup()">
                                Ответить
                            </a>
                        </template>

                    </div>

                </div>

            </div>
        </div>

        <div id="do-question" class="fancyBlock" v-if="(!questionsDetail.authorAnswer || !questionsDetail.answer) && showAnswerButton" v-if="!isTicketSent">
            <div class="fancybox-title">Ответить</div>
            <template>
                <form id="question" name="question">
                    <div class="formGroup required">
                        <div class="formGroup-inner">
                            <label for="question-text">
                                Ваш ответ
                            </label>
                            <textarea name="question-text" id="question-text" required v-model="authorAnswer" placeholder="Ваш вопрос"></textarea>
                        </div>
                    </div>

                    <div class="formGroup formGroup__bottom">
                        <div class="formGroup-inner">
                            <button type="submit" name="question-submit" id="question-submit" disabled="disabled" v-on:click.prevent="sendTicket()">
                                Отправить
                            </button>
                        </div>
                    </div>
                </form>
            </template>
            <template v-else>
                Спасибо за ответ!
            </template>
        </div>

    </div>

</template>
<script>
    import axios from 'axios';

    export default {
        name: 'questionsDetail',
        data: function () {
            return {
                questionsDetail: {},
                isTicketSent: false,
                authorAnswer: '',
                showAnswerButton: false,
            }
        },
        methods: {
            back () {
                this.$router.push({name: 'questions_and_answers'});
            },
            openQuestionPopup() {
                this.isTicketSent = false;
                this.authorAnswer = '';
            },
            sendTicket() {
                let data = {
                    text: this.authorAnswer,
                    ticket: this.questionsDetail.id,
                    documents: this.documents,
                };
                axios({
                    method: 'post',
                    url: '/api/backoffice.tickets.answer.add/',
                    data: data,
                }).then((response) => {
                    if (response.data.result) {
                        this.isTicketSent = true;
                        this.questionsDetail.authorAnswer = this.authorAnswer;
                    }
                });
            },


        },
        mounted: function () {
            axios.get('/api/backoffice.tickets.detail/', {params: {id: this.$route.params.id}}).then((response) => {
                this.questionsDetail = response.data.result;
                this.showAnswerButton = true;
            });
        },

    }
</script>
<style scoped>
    .breadcrumbs_padding {
        padding-top: 0px;
        padding-bottom: 30px;
    }
    .breadcrumbs-inner {
        padding-left: 0px;
    }
    .htmlArea {
        border-bottom-width: 0px;
    }
</style>
