<template>
    <div class="backofficeMain">
        <div class="backofficeMain-block">
            <div class="backofficeMain-block-inner">
                <div class="backofficeMain-block__top">
                    <div class="backoffice__title">
                        Предложить статью
                    </div>
                </div>
                <div class="articlesPage-list articleEditor">

                    <div v-if="!isArticleSent" class="articlesPage-list__single">
                        <div class="articlesPage-list__single-inner">
                            <input v-model="title" class="articlesPage-list__title-input" placeholder="Название статьи">


                            <div class="backofficeFormGroup__logo backofficeFormGroup__block backofficeFormGroup__field">
                                <div class="backofficeFormGroup__logo-img">
                                    <img v-if="image"
                                         :src="image"
                                         class="backofficeFormGroup__logo-tag js-logoMaster">
                                </div>
                                <div class="backofficeFormGroup__logo-file">
                                    <div class="backofficeCustFile">
                                        <label for="editData-logo">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon">
                                                <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                    <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"/>
                                                </g>
                                            </svg>
                                            Загрузить обложку статьи
                                        </label>
                                        <input type="file"
                                               name="editData-logo"
                                               id="editData-logo"
                                               accept="image/jpg,image/jpeg,image/png,image/gif"
                                               @change="logoChange(10485760, $event)">
                                        <div class="backofficeCustFile-response backofficeFormGroup__logo-note" v-if="errors.fileSize">
                                            Размер файла превышает 10Мб
                                        </div>
                                    </div>
                                    <div class="backofficeFormGroup__logo-note">
                                        Вы можете загрузить изображение в формате JPG, GIF, BMP или PNG. Размер фото не больше 10 Мб.
                                    </div>
                                </div>
                            </div>



                            <quill-editor :content="content"
                                          :options="editorOption"
                                          @change="onEditorChange($event)">
                            </quill-editor>
                            <div class="backofficeCustFile-response backofficeFormGroup__logo-note" v-if="isMaxLength">
                                Длина статьи превышает 20000 символов
                            </div>
                            <div class="articleEditor__submit-button">
                                <button type="submit" v-on:click="sendArticle()" class="button-article-edit" :disabled="isMaxLength">
                                    Отправить статью
                                </button>
                                <button type="submit" class="button-article-edit" data-fancybox="" data-src="#preview" v-if="content && title">
                                    Предпросмотр
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-else class="articlesPage-list__single">
                        Спасибо за размещение статьи!<br>
                        Статья появится на сайте после того, как пройдёт модерацию.
                    </div>

                </div>
            </div>
        </div>
        <div id="preview" class="fancyBlock">
            <div class="article-preview">
                <h1 class="articlesPage__title">
                    {{ title }}
                </h1>
                <div class="htmlArea" v-html="content"></div>
            </div>
        </div>
    </div>

</template>
<script>

    import axios from 'axios';

    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import { quillEditor } from 'vue-quill-editor';

    export default {
        components: {
            quillEditor,
        },
        name: 'articleEditor',
        data: function () {
            return {
                title: '',
                image: '',
                content: '',
                isMaxLength: false,
                isArticleSent: false,
                editorOption: {
                    placeholder: 'Текст статьи',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            ['blockquote'],
                            // [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'header': [2, 3, false] }],
                            [{ 'color': [] }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ],
                    }
                },
                errors: {
                    fileSize: false,
                }
            }
        },
        methods: {
            onEditorChange({ quill, html, text }) {
                this.content = html;
                console.log('length!');
                console.log(quill.getLength());
                if (quill.getLength() > 20000) {
                    this.isMaxLength = true;
                } else {
                    this.isMaxLength = false;
                }
            },
            logoChange (maxSize, event) {
                if (event.target.files[0].size > maxSize) {
                    this.errors.fileSize = true;
                } else {
                    let reader  = new FileReader();
                    let that = this;
                    reader.onloadend = function () {
                        that.image = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                    this.errors.fileSize = false;
                }
            },
            sendArticle() {
                axios({
                    method: 'post',
                    url: '/api/backoffice.articles.add/',
                    data: {
                        'title': this.title,
                        'image': this.image,
                        'content': this.content,
                    },
                }).then((response) => {
                    if (response.data.result) {
                        this.isArticleSent = true;
                    }
                });
            },
            previewArticle() {
                console.log(this.title);
                console.log(this.content);
            },
        },
        mounted () {

        },
    }
</script>

<style lang="scss">

    .articleEditor {

        &__submit-button {
            margin-top: 20px;
        }
    }

    .backofficeMain input:not([type=submit]):not([type=checkbox]):not([type=radio]).articlesPage-list__title-input {
        font-size: 28px;
        font-weight: 400;
        line-height: 0.94;
        padding: 0 16px;
        margin-bottom: 20px;
        border: 1px solid #e5e5e5;
        background: transparent;
    }

    .ql-toolbar.ql-snow {
        border: 1px solid #e5e5e5;
    }

    .ql-container.ql-snow {
        border: 1px solid #e5e5e5;
    }

    .ql-snow.ql-toolbar button, .ql-snow .ql-toolbar button {
        height: auto;
    }

    .ql-editor {
        font: normal 18px/1.67 "Gilroy", sans-serif;
        font-size: 16px;
        line-height: 1.4;
        color: #000;

        & p {
            margin-bottom: 10px;
        }

        & strong {
            font-weight: 500;
        }

        & em {
            font-style: italic;
        }

        & u {
            text-decoration: underline;
        }


    }
</style>
