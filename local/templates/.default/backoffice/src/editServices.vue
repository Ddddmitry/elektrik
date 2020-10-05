<template>
    <div class="backofficeEdit">
        <form action="" class="backofficeEdit-form" name="editServices" id="editServices">
            <div class="backofficeEdit-block">
                <div class="backofficeEdit-block-inner">
                    <div class="backofficeServices">
                        <div class="backofficeServices__add">
                            <a href="#" class="button button_md" @click.prevent="addServicesWindow()">
                                Добавить услуги
                            </a>
                        </div>

                        <div class="backofficeServices-list" v-if="startFields.services && startFields.services.length != 0">
                            <div class="backofficeServices-list__single" v-for="(item, key, index) in startFields.services" v-if="item">
                                <div class="backofficeServices-list__title backofficeServices-list__block">
                                    {{item.name}}:
                                </div>
                                <div class="backofficeServices-list__comment backofficeServices-list__block">
                                    <span v-if="key == 0">цена в рублях</span>
                                </div>
                                <div class="backofficeServices-list__delete backofficeServices-list__block"></div>
                                <div class="backofficeServices-sublist" v-if="item.items && item.items.length != 0">
                                    <div class="backofficeServices-sublist__single" v-for="(subItem, subKey) in item.items" v-if="subItem">
                                        <div v-if="subItem.delete" class="backofficeServices-sublist__deleteBlock">
                                            <span class="backofficeServices-sublist__deleteBlock-text">
                                                Услуга удалена
                                            </span>
                                            <a href="#" @click.prevent="returnService(key, subKey)" class="backofficeServices-sublist__deleteBlock-return">
                                                Отменить
                                            </a>
                                        </div>
                                        <div class="backofficeServices-sublist__title backofficeServices-list__block" v-if="!subItem.delete">
                                            {{subItem.name}}
                                        </div>
                                        <div class="backofficeServices-sublist__price backofficeServices-list__block" v-if="!subItem.delete" :class="{'backofficeFormGroup_empty':subItem.price==''}">
                                            <div class="backofficeServices-sublist__text backofficeServices-list__block">
                                                от
                                            </div>
                                            <input type="tel" v-model="subItem.price" class="backofficeServices-sublist__input" @keyup="priceEnter($event, key, subKey)">
                                            <div class="backofficeServices-sublist__money">
                                                рублей
                                            </div>
                                        </div>
                                        <div class="backofficeServices-list__delete backofficeServices-list__block" v-if="!subItem.delete">
                                            <a href="#" class="backofficeServices__delete" @click.prevent="deleteService(key, subKey)">
                                                <div class="backofficeServices__delete-line"></div>
                                                <div class="backofficeServices__delete-line"></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <div class="backofficeFormGroup__label-inner">
                                Локация оказания услуг
                            </div>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field">
                            <div class="backofficeFormGroup__error" v-if="showLocationError">Выберите хотя бы одну локацию</div>
                            <multiselect :options="allCity"
                                         :showNoOptions="false"
                                         v-model="searchLoc"
                                         placeholder="Название улицы, города или области"
                                         track-by="id"
                                         label="value"
                                         @select="selectCity"
                                         @search-change="searchLocation"
                                         v-if="showCitySearch" class="multiselect_loc">
                                <span slot="noResult">По данному запросу ничего не найдено</span>
                                <span slot="noOptions"></span>
                            </multiselect>
                            <a href="#" class="backofficeServices__delete backofficeServices__delete_loc" v-if="showCitySearch" @click.prevent="searchLoc = ''; showCitySearch = false">
                                <div class="backofficeServices__delete-line"></div>
                                <div class="backofficeServices__delete-line"></div>
                            </a>
                            <div v-else="" class="backofficeServices__addCity">
                                <a href="#" class="backofficeExperience__new" @click.prevent="showCitySearch = !showCitySearch">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                    <span class="backofficeExperience__new-inner">
                                        Добавить локацию
                                    </span>
                                </a>
                            </div>
                            <div class="clear"></div>
                            <div class="backofficeServices-cityList" v-if="startFields.places && startFields.places.length != 0">
                                <a href="#" class="backofficeServices-cityList__single inhLink"
                                   v-for="(place, key) in startFields.places" @click.prevent="deleteCity(key)">
                                    <div class="backofficeServices-cityList__name">
                                        {{place.name}}
                                    </div>
                                    <span class="backofficeServices-cityList__delete">
                                        <span class="backofficeServices-cityList__delete-line"></span>
                                        <span class="backofficeServices-cityList__delete-line"></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="backofficeEdit-hr">
                    <div class="backofficeFormGroup">
                        <div class="backofficeFormGroup__label backofficeFormGroup__block">
                            <div class="backofficeFormGroup__label-inner">
                                Типы помещений
                            </div>
                        </div>
                        <div class="backofficeFormGroup__block backofficeFormGroup__field backofficeFormGroup__block_flexCheckbox">
                            <div class="custCheckbox" v-for="(place, key) in startFields.typePlace">
                                <input type="checkbox"
                                       :id="'editServices-'+key"
                                       :name="'editServices-'+key"
                                       :value="key"
                                       :checked="place.checked"
                                       @change="changeChecked(key)">
                                <label :for="'editServices-'+key" class="custLabel">
                                    {{place.name}}
                                </label>
                            </div>
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
        <div id="add_service" class="fancyBlock">
            <div class="fancybox-title">Добавить услуги</div>
            <form action="" name="addServices" id="addServices" class="fullForm" @submit.prevent="addServices()">
                <div class="formGroup formGroup_search">
                    <div class="formGroup-inner">
                        <span class="svg-icon findIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-width="2">
                                    <circle cx="9.5" cy="9.5" r="6.5" stroke-linecap="round"/>
                                    <path stroke-linecap="square" d="M14 14.5l5.907 5.907"/>
                                </g>
                            </svg>
                        </span>
                        <label for="addServices-search" class="search__label">
                            <span class="svg-icon findIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <g fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-width="2">
                                        <circle cx="9.5" cy="9.5" r="6.5" stroke-linecap="round"/>
                                        <path stroke-linecap="square" d="M14 14.5l5.907 5.907"/>
                                    </g>
                                </svg>
                            </span>
                        </label>
                        <input type="search" name="addServices-search" id="addServices-search" v-model="searchString" placeholder="Введите название услуги">
                        <span class="svg-icon js-cleanIcon closeIcon" @click="searchString=''">
                            <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-close"></use></svg>
                        </span>
                    </div>
                </div>
                <div class="backofficeServices-list backofficeServices-list_add" v-show="allServices && allServices.length != 0 && searchString.length > 2">
                    <div class="backofficeServices-list__single js-backofficeServices-list__single" v-for="(item, key, index) in allServices">
                        <div class="backofficeServices-list__title backofficeServices-list__block">
                            {{item.name}}:
                        </div>
                        <div class="backofficeServices-list__comment backofficeServices-list__block">
                            <span v-if="index == 0">цена в рублях</span>
                        </div>
                        <div class="backofficeServices-sublist" v-if="item.items && item.items.length != 0">
                            <div class="backofficeServices-sublist__single js-backofficeServices-sublist__single"
                                 v-for="(subItem, subKey) in item.items" v-if="checkSearch(subItem, key, subKey) || subItem.added">
                                <div class="backofficeServices-sublist__title backofficeServices-list__block">
                                    <div class="custCheckbox">
                                        <input type="checkbox" v-model="subItem.added"
                                               :id="'addServices-'+key+'-'+subKey" :name="'addServices-'+key+'-'+subKey"
                                               @change="changeAddedServiceNumber(subItem.added)">
                                        <label :for="'addServices-'+key+'-'+subKey" class="custLabel">
                                            {{subItem.name}}
                                        </label>
                                    </div>
                                </div>
                                <div class="backofficeServices-sublist__price backofficeServices-list__block">
                                    <div class="backofficeServices-sublist__text backofficeServices-list__block" v-if="subItem.added">
                                        от
                                    </div>
                                    <input type="tel" v-model="subItem.price"
                                           class="backofficeServices-sublist__input"
                                           @keyup="priceEnterNewService($event, key, subKey)"
                                           v-if="subItem.added">
                                    <div class="backofficeServices-sublist__money"  v-if="subItem.added">
                                        рублей
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" v-if="addedServiceNumber > 0" @click.prevent="addServices()" style="margin-top: 30px;">
                    Добавить {{addedServiceNumber}}
                </button>
            </form>
        </div>
    </div>
</template>
<script>

    import $ from 'jquery';
    import axios from 'axios';
    import Multiselect from 'vue-multiselect'

    let handle;

    function hideSection () {
        setTimeout(function () {
            $('.js-backofficeServices-list__single').each(function () {
                if ($(this).find('.js-backofficeServices-sublist__single').length == 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }, 100);
    }

    export default {
        name: 'editServices',
        data: function () {
            return {
                apiUrl: '/api/backoffice.update.services/',
                startFields: {
                    services: this.$parent._data.user.services ? JSON.parse(JSON.stringify(this.$parent._data.user.services)) : [],
                    typePlace: this.$parent._data.user.typePlace ? JSON.parse(JSON.stringify(this.$parent._data.user.typePlace)) : [],
                    places: this.$parent._data.user.places ? JSON.parse(JSON.stringify(this.$parent._data.user.places)) : [],
                },
                allServices: {},
                allCity: [],
                searchString: '',
                addedServiceNumber: 0,
                showCitySearch: false,
                searchLoc: '',
                showLocationError: false,
                validFields: {
                    services: true,
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
            changeChecked (key) {
                this.startFields.typePlace[key].checked = !this.startFields.typePlace[key].checked;
            },
            selectCity (selectedOption, id) {
                this.startFields.places.unshift({
                    id: selectedOption['id'],
                    name: selectedOption['value'],
                    level: selectedOption['level'],
                });
                this.showLocationError = false;
            },
            deleteCity (key) {
                this.startFields.places.splice(key, 1);
            },
            searchLocation (searchQuery, id) {

                this.searchLoc = searchQuery;
                axios({
                    method: 'post',
                    url: '/api/contractors.location.suggest/',
                    data: {
                        searchPhrase: searchQuery
                    },
                }).then((response) => {
                    if (response.data.result) {
                        this.allCity = response.data.result;
                    } else {
                        this.allCity = [];
                    }
                });

                // clearTimeout(handle);
                // handle = setTimeout(function () {
                //
                // }, 500);
            },
            addServices () {
                for (let prop in this.allServices) {
                    for (let subProp in this.allServices[prop].items) {
                        if (this.allServices[prop].items[subProp].added) {
                            if (this.startFields.services[prop] == undefined) {
                                this.$set(this.startFields.services, prop, JSON.parse(JSON.stringify(this.allServices[prop])));
                                this.startFields.services[prop].items = {};
                                this.$set(this.startFields.services[prop].items, subProp, {
                                    name: this.allServices[prop].items[subProp].name,
                                    price: this.allServices[prop].items[subProp].price,
                                    delete: false,
                                });
                            } else {
                                this.$set(
                                    this.startFields.services[prop].items,
                                    subProp,
                                    {
                                        name: this.allServices[prop].items[subProp].name,
                                        price: this.allServices[prop].items[subProp].price,
                                        delete: false,
                                    }
                                );
                            }
                        }

                    }
                }
                $.fancybox.close();
                hideSection ();
                this.addedServiceNumber = 0;
                this.checkValidArray('services', 0, 'price');
            },
            changeAddedServiceNumber (added) {
                if (added) {
                    this.addedServiceNumber = this.addedServiceNumber + 1;
                } else {
                    this.addedServiceNumber = this.addedServiceNumber - 1;
                }
            },
            returnService (key, subKey) {
                this.startFields.services[key].items[subKey].delete = false;
                this.checkValidArray('services', 0, 'price');
            },
            deleteService (key, subKey) {
                this.startFields.services[key].items[subKey].delete = true;
                this.checkValidArray('services', 0, 'price');
            },
            priceEnter (event, key, subKey) {
                let val = event.target.value.replace(/[^\d]/g, '');
                this.startFields.services[key].items[subKey].price = val.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
                this.checkValidArray('services', 0, 'price');
            },
            checkValidArray: function (prop, key, subProp) {
                let contine = true;
                for (let keyFor in this.startFields[prop]) {
                    if (contine) {
                        for (let keyFor2 in this.startFields[prop][keyFor].items) {
                            if (this.startFields[prop][keyFor].items[keyFor2]['delete'] == false) {
                                if (this.startFields[prop][keyFor].items[keyFor2][subProp] == "") {
                                    this.validFields[prop] = false;
                                    contine = false;
                                    break;
                                } else {
                                    this.validFields[prop] = true;
                                }
                            }
                        }
                    } else {
                        break;
                    }
                }
            },
            priceEnterNewService (event, key, subKey) {
                let val = event.target.value.replace(/[^\d]/g, '');
                this.allServices[key].items[subKey].price = val.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            },
            checkSearch (item, key, subKey) {
                if (this.searchString == '') {
                    return false;
                } else {
                    if (item.name.toUpperCase().indexOf(this.searchString.toUpperCase()) != -1) {
                        if (this.startFields.services[key] == undefined) {
                            return true;
                        } else {
                            if (this.startFields.services[key].items[subKey] == undefined) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }
            },
            saveData () {

                if (!this.startFields['places'].length) {
                    this.showLocationError = true;

                    return false;
                }

                for (let prop in this.startFields['services']) {
                    for (let subItem in this.startFields['services'][prop]['items']) {
                        if (this.startFields['services'][prop]['items'][subItem] && this.startFields['services'][prop]['items'][subItem].delete) {
                            delete this.startFields['services'][prop]['items'][subItem];
                        }
                    }
                }

                let data = JSON.parse(JSON.stringify(this.startFields));
                for (let prop in data) {
                    if (JSON.stringify(data[prop]) === JSON.stringify(this.$parent._data.user[prop])) {
                        delete data[prop];
                    }
                }

                this.$parent.saveData(data, this.apiUrl);
            },
            addServicesWindow () {
                $.fancybox.open({
                    src  : '#add_service',
                    touch: false,
                });
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
            searchString: function () {
                hideSection ();
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
            axios.get(
                '/api/backoffice.helper.lists/', {params: {lists: ['SERVICES']}}
            ).then((response) => {
                this.allServices = response.data.result['SERVICES'];
            });
        },
    }
</script>
