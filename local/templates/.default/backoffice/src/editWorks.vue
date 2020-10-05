<template>
    <div class="backofficeEdit">
        <form action="" class="backofficeEdit-form" name="editWorks" id="editWorks">
            <div class="backofficeEdit-block">
                <div class="backofficeEdit-block-inner">
                    <div class="backofficeWorks">
                        <div class="backofficeWorks__add" @click.prevent="addWork()">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                    <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                        <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                    </g>
                                </svg>
                                <span class="backofficeExperience__new-inner">
                                    Добавить пример работы
                                </span>
                            </a>
                        </div>
                        <div class="backofficeWorks-list" v-if="startFields.works && startFields.works.length != 0">
                            <div class="backofficeWorks-list__single" v-for="(item, key, index) in startFields.works">
                                <div class="backofficeWorks-list__img">
                                    <a :href="item.detail_img"
                                       data-fancybox="gallery-works"
                                       data-fancybox-img
                                       :data-caption="item.description"
                                       class="backofficeWorks-list__img-link"
                                       v-if="(item.preview_img && item.preview_img != '') && (item.detail_img && item.detail_img != '')">
                                        <img :src="item.preview_img"
                                             :alt="item.title"
                                             class="backofficeWorks-list__img-tag">
                                    </a>
                                    <img :src="item.detail_img"
                                         :alt="item.title"
                                         class="backofficeWorks-list__img-tag"
                                         v-if="(item.preview_img == undefined || item.preview_img == '') && (item.detail_img && item.detail_img != '')">
                                    <div class="backofficeWorks-list__img-file"
                                         v-if="(item.detail_img == undefined || item.detail_img == '') && ((item.preview_img == undefined || item.preview_img == ''))">
                                        <div class="backofficeCustFile">
                                            <label for="editWorks-img">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon">
                                                    <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                        <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"/>
                                                    </g>
                                                </svg>
                                                <span class="backofficeCustFile-hiddenMd">Загрузить фото</span>
                                                <span class="backofficeCustFile-visionMd">Фото</span>
                                            </label>
                                            <input type="file"
                                                   name="editWorks-img"
                                                   id="editWorks-img"
                                                   accept="image/jpg,image/jpeg,image/png,image/gif"
                                                   @change="addWorkImg(10485760, $event, key)">
                                            <div class="backofficeCustFile-response backofficeFormGroup__logo-note" v-if="errors.fileSize">
                                                Размер файла превышает 10Мб
                                            </div>
                                        </div>
                                        <div class="backofficeWorks-list__img-note">
                                            Вы можете загрузить изображение в формате JPG, GIF, BMP или PNG. Размер фото не больше 10 Мб.
                                        </div>
                                    </div>
                                </div>
                                <div class="backofficeWorks-list__text">
                                    <div class="backofficeWorks-list__text-group">
                                        <input type="text"
                                               v-model="item.title"
                                               :id="'editWorks-title-'+key"
                                               :name="'editWorks-title-'+key"
                                               class="backofficeWorks-list__text-input"
                                               placeholder="Заголовок">
                                    </div>
                                    <div class="backofficeWorks-list__text-group">
                                        <textarea v-model="item.description"
                                                  :id="'editWorks-description-'+key"
                                                  :name="'editWorks-description-'+key"
                                                  class="backofficeWorks-list__text-textarea"
                                                  placeholder="Описание (не обязательно)"></textarea>
                                    </div>
                                </div>
                                <div class="backofficeWorks-list__delete">
                                    <a href="#" class="backofficeServices__delete" @click.prevent="deleteWork(key)">
                                        <div class="backofficeServices__delete-line"></div>
                                        <div class="backofficeServices__delete-line"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="backofficeEdit-bottom">
                <button type="submit" v-on:click.prevent="saveData()">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        name: 'editWorks',
        data: function () {
            return {
                apiUrl: '/api/backoffice.update.works/',
                startFields: {
                    works: this.$parent._data.user.works ? JSON.parse(JSON.stringify(this.$parent._data.user.works)) : [],
                },
                errors: {
                    fileSize: false,
                },
            }
        },
        methods: {
            deleteWork: function (key) {
                this.startFields.works.splice(key, 1);
            },
            addWork: function () {
                let newWork = {
                    preview_img: '',
                    detail_img: '',
                    title: '',
                    description: '',
                };
                this.startFields.works.unshift(newWork);
            },
            addWorkImg (maxSize, event, key) {
                if (event.target.files[0].size > maxSize) {
                    this.errors.fileSize = true;
                } else {
                    let reader  = new FileReader();
                    let that = this;
                    reader.onloadend = function () {
                        that.startFields.works[key].detail_img = reader.result;
                        that.startFields.works[key].is_base64 = true;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                    this.errors.fileSize = false;
                }
            },
            saveData () {
                this.$parent.saveData(this.startFields, this.apiUrl, true);
            }
        },
        watch: {
            startFields: {
                handler: function (newValue) {
                    this.$parent.startValues = true;
                    for (let prop in this.startFields) {
                        if (JSON.stringify(this.startFields[prop]) !== JSON.stringify(this.$parent._data.user[prop])) {
                            this.$parent.startValues = false;
                            break;
                        }
                    }
                },
                deep: true
            },
        },
        mounted () {

        },
    }
</script>
