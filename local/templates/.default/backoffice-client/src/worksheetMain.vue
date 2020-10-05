<template>
    <div class="backofficeMain-inner">

        <div class="backofficeMain-block masterData">
            <div class="backofficeMain-block-inner  masterData-inner">
                <router-link :to="{name:'edit_data'}" class="backofficeMain-block__edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                        <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                        </g>
                    </svg>
                </router-link>
                <div class="backoffice__title backoffice__title_typetwo">
                    Данные и контакты
                </div>
                <div class="masterData-content">
                    <div class="masterData-img">
                        <img :src="this.$parent._data.user.img.src" :alt="name" class="masterData-img-tag">
                    </div>
                    <div class="masterData-info">
                        <div class="masterData-info__single">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                ФИО
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.name}}
                            </div>
                        </div>
                        <div class="masterData-info__single">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                Номер телефона
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.phone}}
                            </div>
                        </div>
                        <div class="masterData-info__single">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                E-mail
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.email}}
                            </div>
                        </div>
                        <div class="masterData-info__single" v-if="this.$parent._data.user.site && this.$parent._data.user.site != ''">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                Сайт
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.site}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        name: 'worksheetMain',
        data: function () {
            return {

            }
        },
    }
</script>
