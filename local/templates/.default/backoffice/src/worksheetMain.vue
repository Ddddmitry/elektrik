<template>
    <div class="backofficeMain-inner">
        <div class="backofficeMain-block masterNotification"
             v-if="$store.getters.getSertificate == false">
            <div class="backofficeMain-block-inner masterNotification-inner">
                <span class="masterNotification__text">
                    Ваш профиль не подтвержден
                </span>
                <a href="#make_sertificate_electric" data-fancybox="" data-src="#make_sertificate_electric" class="button button_md">
                    Пройти сертификацию
                </a>
            </div>
        </div>
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
                        <img v-if="this.$parent._data.user.img.src" :src="this.$parent._data.user.img.src" :alt="name" class="masterData-img-tag">
                        <img v-else src="/local/templates/.default/img/contractor-empty.png" class="masterData-img-tag">
                    </div>
                    <div class="masterData-info">
                        <div class="masterData-info__single">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                <template v-if="contractorType != 'legal'">ФИО</template>
                                <template v-else>Название</template>
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.name}}
                            </div>
                        </div>
                        <div class="masterData-info__single">
                            <div class="masterData-info__single-title masterData-info__single-block">
                                Адрес
                            </div>
                            <div class="masterData-info__single-value masterData-info__single-block">
                                {{this.$parent._data.user.adress}}
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
        <div class="backofficeMain-block masterInfo">
            <div class="backofficeMain-block-inner masterInfo-inner">
                <router-link :to="{name:'edit_info'}" class="backofficeMain-block__edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                        <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                        </g>
                    </svg>
                </router-link>
                <div class="backoffice__title backoffice__title_typetwo">
                    Информация
                </div>
                <div class="masterInfo-content">
                    <div class="masterInfo-content__single" v-if="this.$parent._data.user.about && this.$parent._data.user.about != ''">
                        <div class="masterInfo-content__single-block masterInfo-content__single-title">
                            О себе
                        </div>
                        <div class="masterInfo-content__single-block masterInfo-content__single-value">
                            {{this.$parent._data.user.about}}
                        </div>
                    </div>
                    <div v-if="contractorType != 'legal'" class="masterInfo-content__single" v-if="this.$parent._data.user.experience && this.$parent._data.user.experience.length != 0">
                        <div class="masterInfo-content__single-block masterInfo-content__single-title">
                            Опыт работы
                        </div>
                        <div class="masterInfo-content__single-block masterInfo-content__single-value">
                            <div class="masterInfo-content__single-experience" v-for="item in this.$parent._data.user.experience">
                                <div class="masterInfo-content__single-experience-date masterInfo-content__single-experience-block">
                                    {{item.monthStart}} {{item.yearStart}} —
                                    <span v-if="item.currentPlace">по настоящее время</span>
                                    <span v-else="">{{item.monthEnd}} {{item.yearEnd}}</span>
                                </div>
                                <div class="masterInfo-content__single-experience-place masterInfo-content__single-experience-block">
                                    {{item.place}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="contractorType != 'legal'" class="masterInfo-content__single" v-if="this.$parent._data.user.qualification && this.$parent._data.user.qualification != ''">
                        <div class="masterInfo-content__single-block masterInfo-content__single-title">
                            Квалификация
                        </div>
                        <div class="masterInfo-content__single-block masterInfo-content__single-value">
                            {{this.$parent._data.user.qualification['NAME']}}
                        </div>
                    </div>
                    <div v-if="contractorType != 'legal'" class="masterInfo-content__single" v-if="this.$parent._data.user.languages && this.$parent._data.user.languages.length != 0">
                        <div class="masterInfo-content__single-block masterInfo-content__single-title">
                            Языки
                        </div>
                        <div class="masterInfo-content__single-block masterInfo-content__single-value">
                            <div class="masterInfo-content__single-language" v-for="item in this.$parent._data.user.languages">
                                {{item.language['NAME']}} — {{item.skill['NAME']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="backofficeMain-block masterService">
            <div class="backofficeMain-block-inner masterService-inner">
                <router-link :to="{name:'edit_services'}" class="backofficeMain-block__edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                        <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                        </g>
                    </svg>
                </router-link>
                <div class="backoffice__title backoffice__title_typetwo">
                    Услуги
                </div>
                <div class="marketDetail-about-serviceList marketDetail-about-serviceList_backoffice">

                    <div class="marketDetail-about-serviceList__single" v-if="!this.$parent._data.user.services.length">
                        Заполните услуги, чтобы Ваш профиль появился в каталоге исполнителей.
                    </div>

                    <div class="marketDetail-about-serviceList__single" v-if="this.$parent._data.user.services && this.$parent._data.user.services.length != 0"
                         v-for="service in this.$parent._data.user.services" v-if="service">
                        <div class="marketDetail-about-serviceList__title" v-if="service.items.length">
                            {{service.name}}:
                        </div>
                        <div class="marketDetail-about-serviceList__sublist">
                            <div class="marketDetail-about-serviceList__sublist-single"
                                 v-for="subService in service.items" v-if="subService">
                                <div class="marketDetail-about-serviceList__sublist-block_text
                                marketDetail-about-serviceList__sublist-block">
                                    <span class="marketDetail-about-serviceList__sublist-text">
                                        {{subService.name}}
                                    </span>
                                    <div class="marketDetail-about-serviceList__sublist-text-line"></div>
                                </div>
                                <div class="marketDetail-about-serviceList__sublist-block_value
                                marketDetail-about-serviceList__sublist-block">
                                    от {{subService.price}} ₽
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="backofficeMain-block">
        <div class="backofficeMain-block-inner">
          <router-link :to="{name:'edit_services'}" class="backofficeMain-block__edit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
              <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
              </g>
            </svg>
          </router-link>
          <div class="backoffice__title backoffice__title_typetwo">
            Оказание услуг:
          </div>
          <div class="masterEducation__single masterEducation__single_speial">
            <div class="masterEducation__single-block masterEducation__single-title">
              Локация
            </div>
            <div class="masterEducation__single-block masterEducation__single-value">
                <div class="backofficeServices-cityList" v-if="!this.$parent._data.user.places.length">
                    Заполните локации оказания услуг, чтобы Ваш профиль появился в каталоге исполнителей.
                </div>
                <div class="backofficeServices-cityList"
                   v-if="this.$parent._data.user.places && this.$parent._data.user.places.length != 0">
                      <span class="backofficeServices-cityList__single inhLink"
                            v-for="(place, key) in this.$parent._data.user.places">
                          <div class="backofficeServices-cityList__name">
                              {{place.name}}
                          </div>
                      </span>
                </div>
            </div>
          </div>
          <div class="masterEducation__single">
            <div class="masterEducation__single-block masterEducation__single-title" style="vertical-align: top;">
              Типы помещений
            </div>
            <div class="masterEducation__single-block masterEducation__single-value">
              <div class="typePlace-list">
                <div class="typePlace-list__single" v-for="(place, key) in this.$parent._data.user.typePlace">
                  <span class="typePlace-list__ok" v-if="place.checked"></span>
                  <span class="typePlace-list__not" v-else="">
                                      <span class="typePlace-list__not-line"></span>
                                      <span class="typePlace-list__not-line"></span>
                                  </span>
                  <span class="typePlace-list__name">
                                      {{place.name}}
                                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="backofficeMain-block masterWorks">
            <div class="backofficeMain-block-inner masterWorks-inner">
                <router-link :to="{name:'edit_works'}" class="backofficeMain-block__edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                        <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                        </g>
                    </svg>
                </router-link>
                <div class="backoffice__title backoffice__title_typetwo">
                    Примеры работ
                </div>
                <div class="masterWorks-content" v-if="this.$parent._data.user.works && this.$parent._data.user.works.length != 0">
                    <div class="masterWorks-content__single" v-for="work in this.$parent._data.user.works">
                        <a :href="work.detail_img"
                           class="masterWorks-content__single-inner"
                           data-fancybox="gallery-works"
                           data-fancybox-img
                           :data-caption="work.description">
                            <img :src="work.preview_img"
                                 :alt="work.title"
                                 class="masterWorks-content__single-img">
                        </a>
                    </div>
                </div>
                <div v-else="" class="backofficeMain-block__note">
                    Вы ещё не добавили ни одного примера работ
                </div>
            </div>
        </div>
        <div class="backofficeMain-block masterEducation">
            <div class="backofficeMain-block-inner masterEducation-inner">
                <router-link :to="{name:'edit_sertificates'}" class="backofficeMain-block__edit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon">
                        <g fill="none" fill-rule="evenodd" stroke-linejoin="round" stroke-width="2">
                            <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"/>
                            <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"/>
                            <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"/>
                        </g>
                    </svg>
                </router-link>
                <div class="backoffice__title backoffice__title_typetwo">
                    <template v-if="contractorType != 'legal'">Образование и сертификация</template>
                    <template v-else>Cертификация</template>

                </div>
                <div class="masterEducation-main">
                    <div class="masterEducation__single">
                        <div class="masterEducation__single-block masterEducation__single-title">
                            Сертификат Электрик.ру
                        </div>

                        <div class="masterEducation__single-block masterEducation__single-value"
                             v-if="$store.getters.getSertificate">
                            Да
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value"
                             v-else-if="$store.getters.getCertificationRequest">
                            Заявка отправлена
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value"
                             v-else="">
                            <a href="#make_sertificate_electric" data-fancybox="" data-src="#make_sertificate_electric" class="button button_md">
                                Пройти сертификацию
                            </a>
                        </div>
                    </div>
                    <div class="masterEducation__single">
                        <div class="masterEducation__single-block masterEducation__single-title">
                            Членство РАЭК
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value" v-if="$store.getters.getRaek">
                            Да
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value" v-else-if="$store.getters.getRaekRequest">
                            Заявка отправлена
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value" v-else="">
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
                        </div>
                    </div>
                </div>
                <div class="masterEducation-helper">
                    <div class="masterEducation__single">
                        <div class="masterEducation__single-block masterEducation__single-title" style="vertical-align: top;">
                            Сертификаты
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value">
                            <div class="masterEducation-sertificate" v-if="this.$parent._data.user.sertificates && this.$parent._data.user.sertificates.length != 0">
                                <div class="masterEducation-sertificate__single" v-for="sertificate in this.$parent._data.user.sertificates">
                                    <a :href="sertificate.detail_img"
                                       data-fancybox="gallery-sertificate"
                                       data-fancybox-img
                                       class="masterEducation-sertificate__inner"
                                       :data-caption="sertificate.title">
                                        <img :src="sertificate.preview_img"
                                             :alt="sertificate.title"
                                             class="masterEducation-sertificate__img">
                                    </a>
                                </div>
                            </div>
                            <div v-else="" class="backofficeMain-block__note">
                                Вы ещё не добавили ни одного сертификата
                            </div>
                        </div>
                    </div>
                    <div v-if="contractorType != 'legal'" class="masterEducation__single" v-if="this.$parent._data.user.education && this.$parent._data.user.education.length != 0">
                        <div class="masterEducation__single-block masterEducation__single-title">
                            Образование
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value">
                            <div class="masterEducation-education">
                                <div class="masterEducation-education__single" v-for="item in this.$parent._data.user.education">
                                    {{item.place}} — {{item.type}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="contractorType != 'legal'" class="masterEducation__single" v-if="this.$parent._data.user.courses && this.$parent._data.user.courses.length != 0">
                        <div class="masterEducation__single-block masterEducation__single-title">
                            Курсы
                        </div>
                        <div class="masterEducation__single-block masterEducation__single-value">
                            <div class="masterEducation-education">
                                <div class="masterEducation-education__single" v-for="item in this.$parent._data.user.courses">
                                    {{item.name}}
                                </div>
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
                contractorType: this.$parent._data.user.type,
            }
        },
        methods: {

        },
    }
</script>
