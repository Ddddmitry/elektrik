<template>
    <div class="backofficeEdit backofficeEdit_info">
        <form action="" class="backofficeEdit-form" name="editSertificates" id="editSertificates">
            <div class="backofficeEdit-block">
                <div class="backofficeEdit-block-inner">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block backofficeFormGroup__label_big">
                            <label class="backofficeFormGroup__label-inner">
                                Сертификат Электрик.ру
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeFormGroup__field-inner">
                                <div class="backofficeFormGroup__field-text" v-if="$store.getters.getSertificate">
                                    Да
                                </div>
                                <div class="backofficeFormGroup__field-text" v-else-if="$store.getters.getCertificationRequest">
                                    Заявка отправлена
                                </div>
                                <a href="#make_sertificate_electric"
                                   data-fancybox=""
                                   data-src="#make_sertificate_electric"
                                   class="button button_md" v-else="">
                                    Пройти сертификацию
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label class="backofficeFormGroup__label-inner">
                                Членство РАЭК
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeFormGroup__field-inner">
                                <div class="backofficeFormGroup__field-text" v-if="$store.getters.getRaek">
                                    Да
                                </div>
                                <div class="backofficeFormGroup__field-text" v-else-if="$store.getters.getRaekRequest">
                                    Заявка отправлена
                                </div>
                                <template v-else>
                                    <a href="#make_sertificate_raek"
                                       data-fancybox=""
                                       data-src="#make_sertificate_raek"
                                       class="button button_md">
                                        Оставить заявку
                                    </a>
                                    <a href="#confirm_sertificate_raek"
                                       data-fancybox=""
                                       data-src="#confirm_sertificate_raek"
                                       class="masterEducation__single-value-link">
                                        Подтвердить членство
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <div class="backofficeFormGroup__label-inner">
                                Сертификаты
                            </div>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeExperience">
                                <a href="#" class="backofficeExperience__new" @click.prevent="addSertificate()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                        <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                            <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                        </g>
                                    </svg>
                                    <span class="backofficeExperience__new-inner">
                                        Добавить сертификат
                                    </span>
                                </a>

                                <div class="backofficeExperience-list" v-if="startFields.sertificates && startFields.sertificates.length != 0">
                                    <div class="backofficeExperience-list__single backofficeExperience-list__single_start"
                                         v-for="(sertificate, key) in startFields.sertificates">
                                        <div class="backofficeExperience-list__img">
                                            <a :href="sertificate.detail_img"
                                               data-fancybox=""
                                               data-fancybox-img
                                               class="backofficeExperience-list__img-link"
                                               :data-caption="sertificate.title"
                                               v-if="sertificate.detail_img && checkDetailImgBase64(sertificate.detail_img) == false">
                                                <img :src="sertificate.preview_img"
                                                     :alt="sertificate.title"
                                                     class="backofficeExperience-list__img-tag">
                                            </a>
                                            <img :src="sertificate.detail_img"
                                                 :alt="sertificate.title"
                                                 class="backofficeExperience-list__img-tag"
                                                 v-if="sertificate.detail_img && checkDetailImgBase64(sertificate.detail_img)">
                                        </div>
                                        <div class="backofficeExperience-list__fields">
                                            <div class="backofficeCustFile" v-if="sertificate.detail_img == ''">
                                                <label for="editData-logo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="svg-icon">
                                                        <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                            <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"/>
                                                        </g>
                                                    </svg>
                                                    Загрузить сертификат
                                                </label>
                                                <input type="file"
                                                       name="editData-logo"
                                                       id="editData-logo"
                                                       accept="image/jpg,image/jpeg,image/png,image/gif"
                                                       @change="addSertificateImg(10485760, $event, key)">
                                                <div class="backofficeCustFile-response backofficeFormGroup__logo-note" v-if="errors.fileSize">
                                                    Размер файла превышает 10Мб
                                                </div>
                                            </div>
                                            <input :id="'editSertificates-title-'+key"
                                                   :name="'editSertificates-title-'+key"
                                                   type="text" v-model="sertificate.title"
                                                   placeholder="Название сертификата">
                                            <a href="#" class="backofficeExperience-list__delete" @click.prevent="deleteSertificate(key)">
                                                <span class="backofficeExperience-list__delete-line"></span>
                                                <span class="backofficeExperience-list__delete-line"></span>
                                            </a>
                                            <div class="backofficeExperience-list__space"></div>
                                            <label :for="'editSertificates-date-'+key" class="backofficeExperience-list__label">
                                                Выдан
                                            </label>
                                            <input :id="'editSertificates-date-'+key"
                                                   :name="'editSertificates-date-'+key"
                                                   type="date"
                                                   v-model="sertificate.date"
                                                   class="backofficeExperience-list__medium_sert">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="contractorType != 'legal'">
                        <hr class="backofficeEdit-hr">
                        <div class="backofficeFormGroup">
                            <div class="backofficeFormGroup__label backofficeFormGroup__block">
                                <div class="backofficeFormGroup__label-inner">
                                    Образование
                                </div>
                            </div>
                            <div class="backofficeFormGroup__block backofficeFormGroup__field">
                                <div class="backofficeExperience">
                                    <a href="#" class="backofficeExperience__new" @click.prevent="addEducation()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                            <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                            </g>
                                        </svg>
                                        <span class="backofficeExperience__new-inner">
                                            Добавить место учебы
                                        </span>
                                    </a>
                                    <div class="backofficeExperience-list" v-if="startFields.education && startFields.education.length != 0">
                                        <div class="backofficeExperience-list__single" v-for="(item, key) in startFields.education">
                                            <input :id="'editSertificates-educationTitle-'+key"
                                                   :name="'editSertificates-educationTitle-'+key"
                                                   type="text" v-model="item.place"
                                                   placeholder="Название места учёбы" @keyup="checkValidArray('education', key, 'place')">
                                            <div class="backofficeFormGroup__error" v-if="startFields.education[key].place == ''">
                                                Заполните место учёбы
                                            </div>
                                            <a href="#" class="backofficeExperience-list__delete" @click.prevent="deleteEducation(key)">
                                                <span class="backofficeExperience-list__delete-line"></span>
                                                <span class="backofficeExperience-list__delete-line"></span>
                                            </a>
                                            <div class="backofficeExperience-list__space"></div>
                                            <multiselect v-model="item.type"
                                                         :options="typeEducationList"
                                                         placeholder="Квалификация"
                                                         class="backofficeExperience-list__large">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="contractorType != 'legal'">
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <div class="backofficeFormGroup__label-inner">
                                Курсы
                            </div>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeExperience">
                                <a href="#" class="backofficeExperience__new" @click.prevent="addCourse()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                        <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                            <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                        </g>
                                    </svg>
                                    <span class="backofficeExperience__new-inner">
                                        Добавить курсы
                                    </span>
                                </a>

                                <div class="backofficeExperience-list" v-if="startFields.courses && startFields.courses.length != 0">
                                    <div class="backofficeExperience-list__single" v-for="(item, key) in startFields.courses">
                                        <input :id="'editSertificates-coursesTitle-'+key"
                                               :name="'editSertificates-coursesTitle-'+key"
                                               type="text" v-model="item.name"
                                               placeholder="Название курса" @keyup="checkValidArray('courses', key, 'name')">
                                        <div class="backofficeFormGroup__error" v-if="startFields.courses[key].name == ''">
                                            Заполните курс
                                        </div>
                                        <a href="#" class="backofficeExperience-list__delete" @click.prevent="deleteCourse(key)">
                                            <span class="backofficeExperience-list__delete-line"></span>
                                            <span class="backofficeExperience-list__delete-line"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </template>
                </div>
            </div>
            <div class="backofficeEdit-bottom">
                <button type="submit" :disabled="isDisabled()" v-on:click.prevent="saveData()">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</template>
<script>

    import Multiselect from 'vue-multiselect'

    export default {
        name: 'editSertificates',
        data: function () {
            return {
                apiUrl: '/api/backoffice.update.certificates/',
                contractorType: this.$parent._data.user.type,
                startFields: {
                    sertificate: this.$parent._data.user.sertificate,
                    raek: this.$parent._data.user.raek,
                    sertificates: this.$parent._data.user.sertificates ? JSON.parse(JSON.stringify(this.$parent._data.user.sertificates)) : [],
                    education: this.$parent._data.user.education ? JSON.parse(JSON.stringify(this.$parent._data.user.education)) : [],
                    courses: this.$parent._data.user.courses ? JSON.parse(JSON.stringify(this.$parent._data.user.courses)) : [],
                },
                validFields: {
                    education: true,
                    courses: true,
                },
                typeEducationList: [],
                errors: {
                    fileSize: false,
                }
            }
        },
        methods: {
            isDisabled () {
                let valid = true;
                for (let prop in this.validFields) {
                    if (this.validFields[prop] == false) {
                        valid = false;
                        break;
                    }
                }
                if (this.$parent.startValues || valid == false) {
                    return true;
                }
            },
            deleteCourse: function (key) {
                this.startFields.courses.splice(key, 1);
            },
            addCourse: function () {
                this.validFields.courses = false;
                let newCourse = {name: ''};
                this.startFields.courses.unshift(newCourse);
            },
            deleteEducation: function (key) {
                this.startFields.education.splice(key, 1);
            },
            addEducation: function () {
                this.validFields.education = false;
                let newEducation = {
                    place: '',
                    type: '',
                };
                this.startFields.education.unshift(newEducation);
            },
            checkValidArray: function (prop, key, subProp) {
                for (let i = 0; i < this.startFields[prop].length; i++) {
                    if (this.startFields[prop][i][subProp] == "") {
                        this.validFields[prop] = false;
                        break;
                    } else {
                        this.validFields[prop] = true;
                    }
                }
            },
            deleteSertificate: function (key) {
                this.startFields.sertificates.splice(key, 1);
            },
            addSertificate: function () {
                let newSertificate = {
                    preview_img: '',
                    detail_img: '',
                    title: '',
                    date: '',
                };
                this.startFields.sertificates.unshift(newSertificate);
            },
            checkDetailImgBase64 (url) {
                if (url.indexOf('base64') != -1) {
                    return true;
                } else {
                    return false;
                }
            },
            addSertificateImg (maxSize, event, key) {
                if (event.target.files[0].size > maxSize) {
                    this.errors.fileSize = true;
                } else {
                    let reader  = new FileReader();
                    let that = this;
                    reader.onloadend = function () {
                        that.startFields.sertificates[key].detail_img = reader.result;
                        that.startFields.sertificates[key].is_base64 = true;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                    this.errors.fileSize = false;
                }
            },
            saveData () {

                let educationCleared = [];
                this.startFields.education.forEach(function(element) {
                    if (element.place) {
                        educationCleared.push(element);
                    }
                });
                this.startFields.education = educationCleared;

                let coursesCleared = [];
                this.startFields.courses.forEach(function(element) {
                    if (element.name) {
                        coursesCleared.push(element);
                    }
                });
                this.startFields.courses = coursesCleared;

                let data = JSON.parse(JSON.stringify(this.startFields));
                for (let prop in data) {
                    if (JSON.stringify(data[prop]) === JSON.stringify(this.$parent._data.user[prop])) {
                        delete data[prop];
                    }
                }
                this.$parent.saveData(data, this.apiUrl, true);
            }
        },
        components: {
            Multiselect
        },
        watch : {
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
            validFields: {
                handler: function (newValue) {
                    this.$parent.validValues = true;
                    for (let prop in this.validFields) {
                        if (this.validFields[prop] == false) {
                            this.$parent.validValues = false;
                            break;
                        }
                    }
                },
                deep: true
            },
        },
        mounted () {
            this.typeEducationList = [
                'Бакалавр',
                'Магистр',
            ];
        },
    }
</script>
