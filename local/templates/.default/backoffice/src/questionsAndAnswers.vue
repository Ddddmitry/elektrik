<template>
    <div class="backofficeMain backofficeMain_faq">
        <div class="backofficeMain-block">
            <div class="backofficeMain-block-inner">
                <div class="backoffice__title">
                    Ваши обращения
                </div>
                <div class="tpQuestions" v-if="questions.length">
                    <div class="tpQuestions__single js-tpQuestions-single" v-for="question in questions">
                        <div class="tpQuestions__title">
                            <span href="#" class="tpQuestions__title-inner js-tpQuestions-toggler">
                                Обращение №{{ question.number }} от {{ question.date }} <template v-if="question.isClosed">(закрыто)</template>
                            </span>
                        </div>
                        <div class="tpQuestions__text js-tpQuestions-text active">
                            <b>{{ question.title }}</b>
                        </div>
                        <div class="tpQuestions__text js-tpQuestions-text">
                            {{ question.text }}
                        </div>
                        <div class="tpQuestions__answer js-tpQuestions-answer" v-if="question.answer && question.answer != ''">
                            <div class="tpQuestions__answer-top">
                                <div class="tpQuestions__answer-author">
                                    Ответ поддержки
                                </div>
                                <div class="tpQuestions__answer-date">
                                    {{ question.answerDate }}
                                </div>
                            </div>
                            <div class="tpQuestions__answer-text">
                                {{ question.answer }}
                            </div>
                        </div>
                        <br>
                        <router-link :to="{name: 'questions_detail', params: {id: question.number}}" class="tpQuestions__detail-link">
                            Перейти
                        </router-link>
                    </div>
                </div>
                <div v-else>
                    <p>
                        Если вы хотите задать какой-либо вопрос, то заполните заявку и дождитесь ответа службы поддержки.
                        <br><br>
                    </p>
                </div>
                <a href="#do-question" data-fancybox data-src="#do-question" class="button3" v-on:click="openQuestionPopup()" v-if="ticketAvailable">
                    Задать вопрос
                </a>
                <div class="backofficeFaq">
                    <div class="backoffice__title">
                        Вопросы и ответы
                    </div>
                    <div class="backofficeFaq-list">
                        <div class="js-showhide2 backofficeFaq-list__single" v-for="item in faq">
                            <div class="backofficeFaq-list__title">
                                <a href="#" class="js-showhide2-toggler backofficeFaq-list__title-inner">
                                    {{item.title}}
                                </a>
                            </div>
                            <div class="js-showhide2-content backofficeFaq-list__content" v-html="item.text"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="do-question" class="fancyBlock">
            <div class="fancybox-title">Задать вопрос</div>
            <template v-if="!isTicketSent">
                <form id="question" name="question">
                    <input type="hidden" name="question-name" id="question-name" :value="$parent._data.user.name">

                    <div class="formGroup required">
                        <div class="formGroup-inner">
                            <label for="question-title">
                                Тема обращения
                            </label>
                            <input name="question-text" id="question-title" required v-model="title" placeholder="Тема обращения">
                        </div>
                    </div>

                    <div class="formGroup required">
                        <div class="formGroup-inner">
                            <label for="question-text">
                                Ваш вопрос
                            </label>
                            <textarea name="question-text" id="question-text" required v-model="userQuestion" placeholder="Ваш вопрос"></textarea>
                        </div>
                    </div>

                    <div class="formGroup">
                        <div class="backofficeExperience">
                            <a href="#" class="backofficeExperience__new" @click.prevent="addDocument()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                    <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                        <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                    </g>
                                </svg>
                                <span class="backofficeExperience__new-inner">Добавить вложение</span>
                            </a>

                            <div class="backofficeExperience-list" v-if="documents && documents.length != 0">
                                <div class="backofficeExperience-list__single backofficeExperience-list__single_start"
                                     v-for="(document, key) in documents">
                                    <div class="backofficeExperience-list__img">
                                        <a :href="document.detail_img"
                                           data-fancybox=""
                                           data-fancybox-img
                                           class="backofficeExperience-list__img-link"
                                           :data-caption="document.title"
                                           v-if="document.detail_img && checkDetailImgBase64(document.detail_img) == false">
                                            <img :src="document.preview_img"
                                                 :alt="document.title"
                                                 class="backofficeExperience-list__img-tag">
                                        </a>
                                        <img :src="document.detail_img"
                                             :alt="document.title"
                                             class="backofficeExperience-list__img-tag"
                                             v-if="document.detail_img && checkDetailImgBase64(document.detail_img)">
                                    </div>
                                    <div class="backofficeExperience-list__fields">
                                        <div class="backofficeCustFile" v-if="document.detail_img == ''">
                                            <label for="editData-logo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon">
                                                    <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                        <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"/>
                                                    </g>
                                                </svg>
                                                Загрузить файл
                                            </label>
                                            <input type="file"
                                                   name="editData-logo"
                                                   id="editData-logo"
                                                   accept="image/jpg,image/jpeg,image/png,image/gif"
                                                   @change="addDocumentImg(10485760, $event, key)">
                                            <div class="backofficeCustFile-response backofficeFormGroup__logo-note" v-if="errors.fileSize">
                                                Размер файла превышает 10Мб
                                            </div>

                                        </div>

                                        <a href="#" v-if="document.detail_img" style="left: auto;" class="backofficeExperience-list__delete" @click.prevent="deleteDocument(key)">
                                            <span class="backofficeExperience-list__delete-line"></span>
                                            <span class="backofficeExperience-list__delete-line"></span>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="formGroup formGroup__bottom" v-if="ticketAvailable">
                        <div class="formGroup-inner">
                            <button type="submit" name="question-submit" id="question-submit" disabled="disabled" v-on:click.prevent="sendTicket()">
                                Отправить вопрос
                            </button>
                        </div>
                    </div>

                </form>
            </template>
            <template v-else>
                Спасибо за обращение! Мы сообщим Вам, когда наш сотрудник ответит на Ваш вопрос.
            </template>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        name: 'questionsAndAnswers',
        data: function () {
            return {
                faq : [],
                title: '',
                userQuestion: '',
                isTicketSent: false,
                questions: [],
                title: '',
                documents: [],
                errors: {
                    fileSize: false,
                },
                ticketAvailable: false,
            }
        },
        methods: {
            openQuestionPopup() {
              this.isTicketSent = false;
              this.userQuestion = '';
              this.documents = [];
              this.title = '';
            },
            sendTicket() {
                let data = {
                    title: this.title,
                    text: this.userQuestion,
                    documents: this.documents,
                };
                axios({
                    method: 'post',
                    url: '/api/backoffice.tickets.add/',
                    data: data,
                }).then((response) => {
                    if (response.data.result) {
                        this.isTicketSent = true;
                        this.ticketAvailable = false;
                        this.questions.unshift(response.data.result.newTicket)
                    }
                });
            },


            deleteDocument: function (key) {
                this.documents.splice(key, 1);
            },
            addDocument: function () {
                let newDocument = {
                    preview_img: '',
                    detail_img: '',
                    title: '',
                };
                this.documents.unshift(newDocument);
            },
            checkDetailImgBase64 (url) {
                if (url.indexOf('base64') != -1) {
                    return true;
                } else {
                    return false;
                }
            },
            addDocumentImg (maxSize, event, key) {
                if (event.target.files[0].size > maxSize) {
                    this.errors.fileSize = true;
                } else {
                    let reader  = new FileReader();
                    let that = this;
                    reader.onloadend = function () {
                        that.documents[key].detail_img = reader.result;
                        that.documents[key].is_base64 = true;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                    this.errors.fileSize = false;
                }
            },


        },
        mounted: function () {
            axios.get('/api/backoffice.faq/').then((response) => {
                this.faq = response.data.result;
            });
            axios.get('/api/backoffice.tickets/').then((response) => {
                this.questions = response.data.result["TICKETS"];
                this.ticketAvailable = response.data.result["TICKET_AVAILABLE"];
            });
        },
    }
</script>
