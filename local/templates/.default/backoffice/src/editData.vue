<template>
    <div class="backofficeEdit">
        <form class="backofficeEdit-form" name="editData" id="editData">
            <div class="backofficeEdit-block">
                <div class="backofficeEdit-block-inner">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <div class="backofficeFormGroup__label-inner">
                                Ваше фото
                            </div>
                        </div>
                        <div class="backofficeFormGroup__logo backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeFormGroup__logo-img">
                                <img v-if="startFields.img.src"
                                    :src="startFields.img.src"
                                    :alt="startFields.name"
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
                                        Загрузить другое фото
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
                    </div>
                    <div class="backofficeFormGroup" :class="{'backofficeFormGroup_empty':!validFields.name}">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editData-fio" class="backofficeFormGroup__label-inner">
                                <template v-if="contractorType != 'legal'">Фамилия Имя Отчество</template>
                                <template v-else>Название компании</template>
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <input type="text" name="editData-fio" id="editData-fio" :value="startFields.name"  @keyup="checkValidEmpty('name', $event)">
                        </div>
                    </div>
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup" :class="{'backofficeFormGroup_empty':!validFields.adress}">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editData-adress" class="backofficeFormGroup__label-inner">
                                Адрес
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <input type="text" name="editData-adress" id="editData-adress" :value="startFields.adress" @keyup="checkValidEmpty('adress', $event)">
                        </div>
                    </div>
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editData-phone" class="backofficeFormGroup__label-inner">
                                Номер телефона
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <input type="tel"
                                   :value="startFields.phone"
                                   placeholder="+7 (999) 999-99-99"
                                   name="editData-phone"
                                   id="editData-phone"
                                   @keyup="checkValidPhone($event)">
                            <div class="backofficeFormGroup__error" v-if="!validFields.phone">
                                Ошибочный номер телефона
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editData-email" class="backofficeFormGroup__label-inner">
                                E-mail
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <input type="email" name="editData-email" id="editData-email"
                                   :value="startFields.email"
                                   @keyup="checkValidEmail($event)">
                            <div class="backofficeFormGroup__error" v-if="!validFields.email">
                                Ошибочный адрес электронной почты
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <label for="editData-site" class="backofficeFormGroup__label-inner">
                                Сайт
                            </label>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <input type="text"
                                   name="editData-site"
                                   id="editData-site"
                                   placeholder="Если есть"
                                   v-model="startFields.site">
                        </div>
                    </div>
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

    var Inputmask = require('inputmask');

    export default {
        name: 'editData',
        data: function () {
            return {
                apiUrl: '/api/backoffice.update.contacts/',
                contractorType: this.$parent._data.user.type,
                startFields: {
                    img: this.$parent._data.user.img ? JSON.parse(JSON.stringify(this.$parent._data.user.img)) : {src:''},
                    name: this.$parent._data.user.name ? this.$parent._data.user.name : '',
                    adress: this.$parent._data.user.adress ? this.$parent._data.user.adress : '',
                    phone: this.$parent._data.user.phone ? this.$parent._data.user.phone : '',
                    email: this.$parent._data.user.email ? this.$parent._data.user.email : '',
                    site: this.$parent._data.user.site ? this.$parent._data.user.site : '',
                },
                validFields: {
                    phone: true,
                    email: true,
                    adress: true,
                    name: true,
                },
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
            checkValidEmail (event) {
                this.startFields.email = event.target.value;
                let pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
                if (this.startFields.email.search(pattern) == 0) {
                    this.validFields.email = true;
                } else {
                    this.validFields.email = false;
                }
            },
            checkValidPhone (event) {
                this.startFields.phone = event.target.value;
                if (this.startFields.phone.indexOf('_') == -1) {
                    if (this.startFields.phone == '') {
                        this.validFields.phone = false;
                    } else {
                        this.validFields.phone = true;
                    }
                } else {
                    this.validFields.phone = false;
                }
            },
            checkValidEmpty (prop, event) {
                this.startFields[prop] = event.target.value;
                if (this.startFields[prop] == '') {
                    this.validFields[prop] = false;
                } else {
                    this.validFields[prop] = true;
                }
            },
            logoChange (maxSize, event) {
                if (event.target.files[0].size > maxSize) {
                    this.errors.fileSize = true;
                } else {
                    let reader  = new FileReader();
                    let that = this;
                    reader.onloadend = function () {
                        that.startFields.img.src = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                    this.errors.fileSize = false;
                }
            },
            saveData () {
                let data = JSON.parse(JSON.stringify(this.startFields));
                for (let prop in data) {
                    if (JSON.stringify(data[prop]) === JSON.stringify(this.$parent._data.user[prop])) {
                        delete data[prop];
                    }
                }
                this.$parent.saveData(data, this.apiUrl);
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
        mounted() {
            Inputmask({mask: '+7 (999) 999-99-99',
                showMaskOnHover: false,}).mask('.backofficeFormGroup [type=tel]');
        },
    }
</script>
