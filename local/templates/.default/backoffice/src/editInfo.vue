<template>
    <div class="backofficeEdit backofficeEdit_info">
        <form action="" class="backofficeEdit-form" name="editInfo" id="editInfo">
            <div class="backofficeEdit-block">
                <div class="backofficeEdit-block-inner">
                    <div class="backofficeFormGroup" :class="{'backofficeFormGroup_empty':!validFields.about}">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editInfo-about" class="backofficeFormGroup__label-inner">
                                О себе
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <textarea name="editInfo-about" id="editInfo-about" v-model="startFields.about"  @keyup="checkValidEmpty('about', $event)"></textarea>
                        </div>
                    </div>

                    <template v-if="contractorType != 'legal'">
                        <hr class="backofficeEdit-hr">
                        <div class="backofficeFormGroup">
                            <div class="backofficeFormGroup__label backofficeFormGroup__block">
                                <div class="backofficeFormGroup__label-inner">
                                    Опыт работы
                                </div>
                            </div>
                            <div class="backofficeFormGroup__block backofficeFormGroup__field">
                                <div class="backofficeExperience">
                                    <a href="#" class="backofficeExperience__new" @click.prevent="addExperience()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                            <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                            </g>
                                        </svg>
                                        <span class="backofficeExperience__new-inner">
                                            Добавить место работы
                                        </span>
                                    </a>
                                    <div class="backofficeExperience-list" v-if="startFields.experience && startFields.experience.length != 0">
                                        <div class="backofficeExperience-list__single" v-for="(item, key) in startFields.experience">
                                            <div class="backofficeExperience-list__text">
                                                С
                                            </div>
                                            <multiselect v-model="item.monthStart"
                                                         :options="months"
                                                         placeholder="Месяц"
                                                         class="backofficeExperience-list__medium">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <multiselect v-model="item.yearStart"
                                                         :options="years"
                                                         placeholder="Год"
                                                         class="backofficeExperience-list__small">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <span class="backofficeExperience-list__single-dash"></span>
                                            <div class="backofficeExperience-list__text">
                                                По
                                            </div>
                                            <multiselect :options="months" v-if="item.currentPlace"
                                                         placeholder="Месяц" disabled
                                                         class="backofficeExperience-list__medium">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <multiselect v-model="item.monthEnd"
                                                         :options="months"
                                                         placeholder="Месяц" v-else=""
                                                         class="backofficeExperience-list__medium">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <multiselect :options="years" v-if="item.currentPlace"
                                                         placeholder="Год" disabled
                                                         class="backofficeExperience-list__small">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <multiselect v-model="item.yearEnd"
                                                         :options="years"
                                                         placeholder="Год" v-else=""
                                                         class="backofficeExperience-list__small">
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <a href="#" class="backofficeExperience-list__delete" @click.prevent="deleteExperience(key)">
                                                <span class="backofficeExperience-list__delete-line"></span>
                                                <span class="backofficeExperience-list__delete-line"></span>
                                            </a>
                                            <div class="backofficeExperience-list__currentPlace">
                                                <div class="slideToggler">
                                                    <input type="checkbox" value="1"
                                                           :id="'editInfo-currentPlace-'+key"
                                                           :name="'editInfo-currentPlace-'+key"
                                                           v-model="item.currentPlace"
                                                           @change="changeCurrentPlace(key)">
                                                    <label :for="'editInfo-currentPlace-'+key" class="custLabel">
                                                        Работаю сейчас
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="backofficeExperience-list__place">
                                                <input type="text"
                                                       :id="'editInfo-place-'+key"
                                                       :name="'editInfo-place-'+key"
                                                       placeholder="Название компании"
                                                       v-model="item.place" @keyup="checkValidArray('experience', key, 'place')">
                                                <div class="backofficeFormGroup__error" v-if="startFields.experience[key].place == ''">
                                                    Заполните место работы
                                                </div>
                                            </div>
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
                                    Квалификация
                                </div>
                            </div>
                            <div class="backofficeFormGroup__block backofficeFormGroup__field">
                                <multiselect v-model="startFields.qualification"
                                             :options="qualifications"
                                             placeholder="Выберите вашу квалификацию"
                                             track-by="NAME"
                                             label="NAME"
                                >
                                    <span slot="noResult">По данному запросу ничего не найдено</span>
                                </multiselect>
                            </div>
                        </div>
                    </template>

                    <template v-if="contractorType != 'legal'">
                        <hr class="backofficeEdit-hr">
                        <div class="backofficeFormGroup">
                            <div class="backofficeFormGroup__label backofficeFormGroup__block">
                                <div class="backofficeFormGroup__label-inner">
                                    Языки
                                </div>
                            </div>
                            <div class="backofficeFormGroup__block backofficeFormGroup__field">
                                <div class="backofficeExperience">
                                    <a href="#" class="backofficeExperience__new" @click.prevent="addLanguage()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 24 24">
                                            <g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2">
                                                <path d="M3.5 11.5h17.202M11.75 3.5v17.202"/>
                                            </g>
                                        </svg>
                                        <span class="backofficeExperience__new-inner">
                                            Добавить язык
                                        </span>
                                    </a>
                                    <div class="backofficeExperience-list" v-if="startFields.languages && startFields.languages.length != 0">
                                        <div class="backofficeExperience-list__single" v-for="(item, key) in startFields.languages">
                                            <multiselect v-model="item.language"
                                                         :options="languagesList"
                                                         placeholder="Язык"
                                                         class="backofficeExperience-list__large"
                                                         track-by="NAME"
                                                         label="NAME" @input="checkValidArray('languages', key, 'language')"
                                            >
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <div class="backofficeFormGroup__error" v-if="startFields.languages[key].language == ''">
                                                Заполните язык
                                            </div>
                                            <multiselect v-model="item.skill"
                                                         :options="languageSkillsList"
                                                         placeholder="Уровень владения"
                                                         class="backofficeExperience-list__extralarge"
                                                         track-by="NAME"
                                                         label="NAME"
                                            >
                                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                            </multiselect>
                                            <a href="#" class="backofficeExperience-list__delete" @click.prevent="deleteLanguage(key)">
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
    import $ from 'jquery';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect'

    let date = new Date();
    let currentYear = date.getFullYear();
    let currentMonth = date.getMonth();

    export default {
        name: 'editInfo',
        data: function () {
            return {
                apiUrl: '/api/backoffice.update.info/',
                contractorType: this.$parent._data.user.type,
                startFields: {
                    about: this.$parent._data.user.about ? this.$parent._data.user.about : '',
                    experience: this.$parent._data.user.experience ? JSON.parse(JSON.stringify(this.$parent._data.user.experience)) : [],
                    qualification: this.$parent._data.user.qualification ? this.$parent._data.user.qualification : '',
                    languages: this.$parent._data.user.languages ? JSON.parse(JSON.stringify(this.$parent._data.user.languages)) : [],
                },
                months: [
                    'Январь',
                    'Февраль',
                    'Март',
                    'Апрель',
                    'Май',
                    'Июнь',
                    'Июль',
                    'Август',
                    'Сентябрь',
                    'Октябрь',
                    'Ноябрь',
                    'Декабрь',
                ],
                startYear: 1940,
                years: [],
                qualifications: [],
                languagesList: [],
                languageSkillsList: [],
                validFields: {
                    about: true,
                    experience: true,
                    languages: true,
                },
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
            changeCurrentPlace: function (key) {
                for (let item in this.startFields.experience) {
                    if (item != key) {
                        this.startFields.experience[item].currentPlace = false;
                    }
                }
                if (this.startFields.experience[key].monthEnd == '') {
                    this.startFields.experience[key].monthEnd = this.months[currentMonth];
                }
                if (this.startFields.experience[key].yearEnd == '') {
                    this.startFields.experience[key].yearEnd = currentYear;
                }
            },
            deleteExperience: function (key) {
                this.startFields.experience.splice(key, 1);
            },
            addExperience: function () {
                this.validFields.experience = false;
                let newExperience = {
                    monthStart: this.months[currentMonth],
                    yearStart: currentYear,
                    monthEnd:  this.months[currentMonth],
                    yearEnd: currentYear,
                    currentPlace: false,
                    place: '',
                };
                this.startFields.experience.unshift(newExperience);
            },
            deleteLanguage: function (key) {
                this.startFields.languages.splice(key, 1);
            },
            addLanguage: function () {
                this.validFields.languages = false;
                let newLanguage = {
                    language: '',
                    skill: '',
                };
                this.startFields.languages.unshift(newLanguage);
            },
            checkValidEmpty (prop, event) {
                this.startFields[prop] = event.target.value;
                if (this.startFields[prop] == '') {
                    this.validFields[prop] = false;
                } else {
                    this.validFields[prop] = true;
                }
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
            saveData () {
                this.$parent.startValues = true;

                let experienceCleared = [];
                this.startFields.experience.forEach(function(element) {
                    if (element.place) {
                        experienceCleared.push(element);
                    }
                });
                this.startFields.experience = experienceCleared;

                let months = this.months;
                this.startFields.experience.forEach(function(element) {
                    element.monthStartNumber = Number(Object.keys(months).find(key => months[key] === element.monthStart));
                    element.monthEndNumber = Number(Object.keys(months).find(key => months[key] === element.monthEnd));
                });

                let languagesCleared = [];
                this.startFields.languages.forEach(function(element) {
                    if (element.language && element.skill) {
                        languagesCleared.push(element);
                    }
                });
                this.startFields.languages = languagesCleared;

                let data = JSON.parse(JSON.stringify(this.startFields));
                for (let prop in data) {
                    if (JSON.stringify(data[prop]) === JSON.stringify(this.$parent._data.user[prop])) {
                        delete data[prop];
                    }
                }
                this.$parent.saveData(data, this.apiUrl);
            }
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
        components: {
            Multiselect
        },
        mounted () {
            for (let i = this.startYear; i <= currentYear; i++) {
                this.years.unshift(i);
            }

            axios.get(
                '/api/backoffice.helper.lists/', {params: {lists: ['SKILLS', 'LANGUAGES_LIST', 'LANGUAGES_LEVELS']}}
            ).then((response) => {
                this.qualifications = response.data.result['SKILLS'];
                this.languagesList = response.data.result['LANGUAGES_LIST'];
                this.languageSkillsList = response.data.result['LANGUAGES_LEVELS'];
            });

        },
    }
</script>
