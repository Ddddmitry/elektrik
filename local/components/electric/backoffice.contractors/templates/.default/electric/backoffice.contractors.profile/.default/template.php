<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="cabinet__tabs">
    <div class="cabinet__tabs-btns" data-tabs>
        <div class="cabinet__tabs-btn main__text is-open" data-tab="contacts">Данные и контакты</div>
        <div class="cabinet__tabs-btn main__text" data-tab="info">Информация</div>
        <div class="cabinet__tabs-btn main__text " data-tab="service-price">Услуги и цены</div>
        <div class="cabinet__tabs-btn main__text" data-tab="works">Примеры работ</div>
        <div class="cabinet__tabs-btn main__text" data-tab="sertificates">Сертификаты</div>
    </div>
</div>
<div class="cabinet__tabs-contents backofficeMain">
    <div class="cabinet__tabs-content is-open" data-tab-content="contacts" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.contacts/" data-back-form-profile-update enctype="multipart/form-data">
            <input type="hidden" name="TYPE" value="contractors">
            <div class="cabinet__box">
                <div class="cabinet__block">
                    <div class="cabinet__form">
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Ваше фото</div>
                            <div class="cabinet__form-field cabinet__form-field--inline">
                                <div class="cabinet__form-photo" data-logo-block
                                     style="background-image: url(<?=$arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"]?>);"></div>
                                <div class="cabinet__form-rside">
                                    <label for="field-add-photo" class="field-add-photo">
                                        <input type="file" name="PREVIEW_PICTURE" id="field-add-photo">
                                        <span class="field-add-photo__title">Загрузить другое фото</span>
                                    </label>
                                    <div class="main__text main__text--xs light">Вы можете загрузить изображение в формате JPG,
                                        GIF
                                        или PNG. Размер фото не больше 10 Мб
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Фамилия Имя Отчество</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="NAME" id="" placeholder="" value="<?=$arResult["PROFILE"]["NAME"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabinet__block">
                    <div class="cabinet__form">
                        <?/*<div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Адрес</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="ADDRESS" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["ADDRESS"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>*/?>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Сайт</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="SITE" id="" placeholder="Если есть"
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["SITE"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Номер телефона</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="PHONE" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?>"
                                       class="field-input field-input--light" data-phone>
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">E-mail</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="EMAIL" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["EMAIL"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Telegram</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="TELEGRAM" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["TELEGRAM"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">WhatsApp</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="WHATSAPP" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["WHATSAPP"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Viber</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="VIBER" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["VIBER"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>
                        <!--<div class="cabinet__form-item" disabled="disabled">
                            <div class="cabinet__form-title main__text">Присылать уведомления</div>
                            <div class="cabinet__form-field">
                                <div class="cabinet__form-checkboxes">
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-portal" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-portal" checked>
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">На портале</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-email" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-email">
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">По электронной почте</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-sms" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-sms">
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">По SMS</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-tlg" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-tlg">
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">В Telegram</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-wa" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-wa">
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">В WhatsApp</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__form-checkbox">
                                        <label for="check-vb" class="field-checkbox">
                                            <input type="checkbox" name="" id="check-vb">
                                            <span class="field-checkbox__mark"></span>
                                            <span class="field-checkbox__title">В Viber</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
                <div class="cabinet__action-message">При изменении данных проверка происходит в течении 24 часов</div>
            </div>
        </form>
    </div>
    <div class="cabinet__tabs-content" data-tab-content="info" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.info/" data-back-form-info-update enctype="multipart/form-data">
            <div class="cabinet__box">
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">О себе</div>
                        <div class="cabinet__form-field">
                            <textarea type="text" name="about" placeholder="" class="field-textarea field-input--light"><?=strip_tags($arResult["PROFILE"]["PREVIEW_TEXT"])?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Опыт работы</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">Добавить место работы</span>
                            </a>
                            <div class="backofficeExperience-list">
                                <?if($arResult["PROFILE"]["EXPERIENCE"]):?>
                                    <? foreach ($arResult["PROFILE"]["EXPERIENCE"] as $idx=>$arExperience) {?>
                                        <div class="backofficeExperience-list__single js-experience-item">
                                            <div class="backofficeExperience-list__text">С</div>
                                            <div class="backofficeExperience-list__medium">
                                                <select name="monthStart">
                                                    <?foreach ($arResult['PROFILE']['LIST_MONTH'] as $index=> $month):?>
                                                        <option value="<?=$index?>" <?if(($arExperience["START_DATE"]->format("n")-1) == $index):?>selected<?endif;?> ><?=$month?></option>
                                                    <?endforeach;?>
                                                </select>
                                            </div>
                                            <div class="backofficeExperience-list__small">
                                                <select name="yearStart">
                                                    <?for($i=date("Y");$i>1949;$i--):?>
                                                        <option value="<?=$i?>" <?if(($arExperience["START_DATE"]->format("Y")) == $i):?>selected<?endif;?>><?=$i?></option>
                                                    <?endfor;?>
                                                </select>
                                            </div> <span class="backofficeExperience-list__single-dash"></span>
                                            <div class="backofficeExperience-list__text">
                                                По
                                            </div>
                                            <div class="backofficeExperience-list__medium <?if($arExperience["IS_NOW"]):?>multiselect--disabled<?endif;?>">
                                                <select name="monthEnd" <?if($arExperience["IS_NOW"]):?>disabled="disabled"<?endif;?>>
                                                    <?foreach ($arResult['PROFILE']['LIST_MONTH'] as $index=> $month):?>
                                                        <option value="<?=$index?>" <?if($arExperience["END_DATE"] && ($arExperience["END_DATE"]->format("n")-1) == $index):?>selected<?endif;?>><?=$month?></option>
                                                    <?endforeach;?>
                                                </select>
                                            </div>
                                            <div class="backofficeExperience-list__small <?if($arExperience["IS_NOW"]):?>multiselect--disabled<?endif;?>">
                                                <select name="yearEnd" <?if($arExperience["IS_NOW"]):?>disabled="disabled"<?endif;?>>
                                                    <?for($i=date("Y");$i>1949;$i--):?>
                                                        <option value="<?=$i?>" <?if($arExperience["END_DATE"] && ($arExperience["END_DATE"]->format("Y")) == $i):?>selected<?endif;?>><?=$i?></option>
                                                    <?endfor;?>
                                                </select>
                                            </div>
                                            <a href="#" class="backofficeExperience-list__delete"><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                            <div class="backofficeExperience-list__currentPlace">
                                                <div class="slideToggler">
                                                    <input type="checkbox" value="1" id="editInfo-currentPlace-<?=$idx?>" name="currentPlace" <?if($arExperience["IS_NOW"]):?>checked<?endif;?>>
                                                    <label for="editInfo-currentPlace-<?=$idx?>" class="custLabel">Работаю сейчас</label>
                                                </div>
                                            </div>
                                            <div class="backofficeExperience-list__place">
                                                <input type="text" id="" name="place" placeholder="Название компании" value="<?=$arExperience["NAME"]?>">
                                            </div>
                                        </div>
                                    <?}?>
                                <?else:?>
                                    <div class="backofficeExperience-list__single js-experience-item">
                                        <div class="backofficeExperience-list__text">С</div>
                                        <div class="backofficeExperience-list__medium">
                                            <select name="monthStart">
                                                <?foreach ($arResult['PROFILE']['LIST_MONTH'] as $index=> $month):?>
                                                    <option value="<?=$index?>"><?=$month?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                        <div class="backofficeExperience-list__small">
                                            <select name="yearStart">
                                                <?for($i=date("Y");$i>1949;$i--):?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                <?endfor;?>
                                            </select>
                                        </div> <span class="backofficeExperience-list__single-dash"></span>
                                        <div class="backofficeExperience-list__text">
                                            По
                                        </div>
                                        <div class="backofficeExperience-list__medium">
                                            <select name="monthEnd">
                                                <?foreach ($arResult['PROFILE']['LIST_MONTH'] as $index=> $month):?>
                                                    <option value="<?=$index?>"><?=$month?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                        <div class="backofficeExperience-list__small">
                                            <select name="yearEnd">
                                                <?for($i=date("Y");$i>1949;$i--):?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                <?endfor;?>
                                            </select>
                                        </div>
                                        <a href="#" class="backofficeExperience-list__delete"><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                        <div class="backofficeExperience-list__currentPlace">
                                            <div class="slideToggler">
                                                <input type="checkbox" value="1" id="editInfo-currentPlace-0" name="currentPlace" checked>
                                                <label for="editInfo-currentPlace-0" class="custLabel">Работаю сейчас</label>
                                            </div>
                                        </div>
                                        <div class="backofficeExperience-list__place">
                                            <input type="text" id="" name="place" placeholder="Название компании">
                                        </div>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Квалификация</div>
                        <div class="cabinet__form-field">
                            <select name="qualification" id="">
                                <?foreach ($arResult["PROFILE"]["LIST_SKILLS"] as $arSkill):?>
                                    <option value="<?=$arSkill["ID"]?>" <?if($arSkill["ID"] == $arResult["PROFILE"]["PROPERTIES"]["SKILL"]["VALUE"]):?>selected="selected"<?endif;?>><?=$arSkill["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Языки</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">Добавить язык</span>
                            </a>
                            <div class="backofficeExperience-list">
                                <? foreach ($arResult["PROFILE"]["LANGUAGES"] as $arLanguage) {?>
                                    <div class="backofficeExperience-list__single js-language-item">
                                        <div class="backofficeExperience-list__large">
                                            <select name="language">
                                                <?foreach ($arResult["PROFILE"]["LIST_LANGUAGES_NAMES"] as $arLang):?>
                                                    <option value="<?=$arLang["ID"]?>"
                                                            <?if($arLanguage["LANGUAGE"]["ID"] == $arLang["ID"]):?>selected="selected"<?endif;?>
                                                    ><?=$arLang["NAME"]?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                        <div class="backofficeExperience-list__extralarge">
                                            <select name="skill">
                                                <?foreach ($arResult["PROFILE"]["LIST_LANGUAGES_LEVELS"] as $arLangLvl):?>
                                                    <option value="<?=$arLangLvl["ID"]?>"
                                                            <?if($arLanguage["LEVEL"]["ID"] == $arLangLvl["ID"]):?>selected="selected"<?endif;?>
                                                    ><?=$arLangLvl["NAME"]?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                        <a href="#" class="backofficeExperience-list__delete"><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                    </div>
                                <?}?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
                <div class="cabinet__action-message">При изменении данных проверка происходит в течении 24 часов</div>
            </div>
        </form>
    </div>
    <div class="cabinet__tabs-content" data-tab-content="service-price" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.services/" data-back-form-services-update enctype="multipart/form-data">
            <div class="cabinet__box">
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="backofficeServices__add">
                        <a href="#" class="button button_md">
                            Добавить услуги
                        </a>
                    </div>
                    <div class="backofficeServices-list" data-service-list>
                        <?foreach ($arResult["PROFILE"]["SERVICES"] as $arService):?>
                            <div class="backofficeServices-list__single" data-service-single data-category="<?=$arService["NAME"]?>">
                                <div class="backofficeServices-list__title backofficeServices-list__block">
                                    <?=$arService["NAME"]?>
                                </div>
                                <div class="backofficeServices-list__comment backofficeServices-list__block"><span>цена в рублях</span></div>
                                <div class="backofficeServices-list__delete backofficeServices-list__block"></div>
                                <div class="backofficeServices-sublist">
                                    <?foreach ($arService["SERVICES"] as $id=>$arSubService):?>
                                        <div class="backofficeServices-sublist__single" data-subservice-single data-service-item="<?=$arSubService['ID']?>">
                                            <!---->
                                            <div class="backofficeServices-sublist__title backofficeServices-list__block">
                                                <?=$arSubService["NAME"]?>
                                            </div>
                                            <div class="backofficeServices-sublist__price backofficeServices-list__block">
                                                <div class="backofficeServices-sublist__text backofficeServices-list__block">от</div>
                                                <input type="text" name="price" class="backofficeServices-sublist__input" data-number value="<?=$arSubService["PRICE"]?>">
                                                <div class="backofficeServices-sublist__money">рублей</div>
                                            </div>
                                            <div class="backofficeServices-list__delete backofficeServices-list__block">
                                                <a class="backofficeServices__delete" data-delete-service>
                                                    <div class="backofficeServices__delete-line"></div>
                                                    <div class="backofficeServices__delete-line"></div>
                                                </a>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endforeach;?>

                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Локация оказания услуг</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">
															Добавить локацию
														</span>
                            </a>
                            <div class="backofficeServices-cityList" data-service-city-list >
                            <?foreach ($arResult['PROFILE']['PLACES'] as $arPlace):?>

                                <a  class="backofficeServices-cityList__single inhLink js-city" data-id="<?=$arPlace["ID"]?>" data-level="<?=$arPlace["LEVEL"]?>">
                                    <div class="backofficeServices-cityList__name" data-name>
                                        <?=$arPlace["NAME"]?>
                                    </div> <span class="backofficeServices-cityList__delete" data-delete-city><span class="backofficeServices-cityList__delete-line"></span> <span class="backofficeServices-cityList__delete-line"></span></span>
                                </a>

                            <?endforeach;?>
                            </div>
                            <div class="backofficeExperience-list__place js-popupSearchWrap" data-city-chooser style="display:none;">
                                <input type="text"
                                       autocomplete="off"
                                       placeholder="Укажите город"
                                       data-choose-city
                                >
                                <div class="popupSearchResult js-popupSearchResult"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Типы помещений</div>
                        <div class="cabinet__form-field">
                            <div class="backofficeFormGroup__block backofficeFormGroup__field backofficeFormGroup__block_flexCheckbox">
                                <?foreach ($arResult['PROFILE']['ROOMS'] as $index=>$arRoom):?>
                                    <div class="custCheckbox" data-room>
                                        <input type="checkbox" id="editServices-<?=$index?>" name="editServices-<?=$index?>" data-name="<?=$arRoom["NAME"]?>" value="<?=$index?>" <?if($arRoom["CHECKED"]):?>checked<?endif;?>>
                                        <label for="editServices-<?=$index?>" class="custLabel">
                                            <?=$arRoom["NAME"]?>
                                        </label>
                                    </div>
                                <?endforeach;?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
                <div class="cabinet__action-message">При изменении данных проверка происходит в течении 24 часов</div>
            </div>
        </form>

        <div class="modal" id="addServiceModal">
            <div class="modal-body">
                <div class="modal-title">
                    Добавить услугу
                    <div class="closeModal">X</div>
                </div>

                <div class="modal-content">
                    <div class="serviceFieldSearch">
                        <select name="searchService">
                            <?foreach ($arResult["PROFILE"]["LIST_SERVICES"] as $arListService):?>
                                <optgroup label="<?=$arListService["name"]?>">
                                    <?foreach ($arListService["items"] as $id=>$arService):?>
                                        <option value="<?=$arListService["name"]?>:<?=$arService["name"]?>:<?=$id?>"><?=$arService["name"]?></option>
                                    <?endforeach;?>
                                </optgroup>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div class="resultSearchField"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="cabinet__tabs-content" data-tab-content="works" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.works/" data-back-form-works-update enctype="multipart/form-data">
            <div class="cabinet__box">
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item-t">
                        <a href="#" class="backofficeExperience__new">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                            <span class="backofficeExperience__new-inner">Добавить примеры работ</span>
                        </a>
                        <div class="backofficeWorks-list" data-works-list>

                                <?if($arResult["PROFILE"]["WORKS"]):?>

                                        <?foreach ($arResult["PROFILE"]["WORKS"] as $index=>$arWork):?>
                                            <div class="backofficeWorks-list__single js-backofficeWorks-list__single" data-work-single>
                                                <div class="backofficeWorks-list__img">
                                                    <?foreach ($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $arMorePhoto):?>
                                                        <a href="<?=$arMorePhoto["FULL"]["SRC"]?>" data-fancybox="gallery-works-<?=$index?>" data-fancybox-img="" class="backofficeWorks-list__img-link">
                                                            <img src="<?=$arMorePhoto["PREVIEW"]["src"]?>" data-full="<?=$arMorePhoto["FULL"]["SRC"]?>" alt="" class="old-work backofficeWorks-list__img-tag">
                                                        </a>
                                                    <?endforeach;?>
                                                </div>
                                                <div class="backofficeWorks-list__text">
                                                    <div class="backofficeWorks-list__text-group">
                                                        <input type="text"
                                                               name="title"
                                                               placeholder="Заголовок"
                                                               class="backofficeWorks-list__text-input"
                                                               value="<?=$arWork["NAME"]?>"
                                                        >
                                                    </div>
                                                    <div class="backofficeWorks-list__text-group">
                                                        <textarea name="description"
                                                                  placeholder="Описание (не обязательно)"
                                                                  class="backofficeWorks-list__text-textarea"><?=$arWork["DETAIL_TEXT"]?></textarea>
                                                    </div>
                                                </div>
                                                <div class="backofficeWorks-list__delete">
                                                    <a href="#" class="backofficeServices__delete" data-delete-work>
                                                        <div class="backofficeServices__delete-line"></div>
                                                        <div class="backofficeServices__delete-line"></div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?endforeach;?>

                                <?else:?>
                                    <div class="backofficeWorks-list__single js-backofficeWorks-list__single" data-work-single>
                                        <div class="backofficeWorks-list__img js-backofficeWorks-list__img">
                                            <div class="backofficeWorks-list__img-file">
                                                <div class="backofficeCustFile">
                                                    <label for="editWorks-img">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                             viewBox="0 0 24 24" class="svg-icon">
                                                            <g fill="none" fill-rule="evenodd" stroke-linecap="square"
                                                               stroke-linejoin="round" stroke-width="2">
                                                                <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"></path>
                                                            </g>
                                                        </svg>
                                                        <span class="backofficeCustFile-hiddenMd">Загрузить фото</span>
                                                        <span class="backofficeCustFile-visionMd">Фото</span></label>
                                                    <input type="file" name="editWorks-img" id="editWorks-img" data-edit-works-img
                                                           accept="image/jpg,image/jpeg,image/png,image/gif" multiple>
                                                </div>
                                                <div class="backofficeWorks-list__img-note">
                                                    Вы можете загрузить изображение в формате JPG, GIF, BMP или PNG.
                                                    Размер фото не&nbsp;больше 10 Мб.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="backofficeWorks-list__text">
                                            <div class="backofficeWorks-list__text-group"><input type="text"
                                                                                                 name="title"
                                                                                                 placeholder="Заголовок"
                                                                                                 class="backofficeWorks-list__text-input">
                                            </div>
                                            <div class="backofficeWorks-list__text-group"><textarea
                                                        name="description"
                                                        placeholder="Описание (не обязательно)"
                                                        class="backofficeWorks-list__text-textarea"></textarea></div>
                                        </div>
                                        <div class="backofficeWorks-list__delete">
                                            <a href="#" class="backofficeServices__delete" data-delete-work>
                                                <div class="backofficeServices__delete-line"></div>
                                                <div class="backofficeServices__delete-line"></div>
                                            </a>
                                        </div>
                                    </div>
                                <?endif;?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
                <div class="cabinet__action-message">При изменении данных проверка происходит в течении 24 часов</div>
            </div>
        </form>
    </div>
    <div class="cabinet__tabs-content" data-tab-content="sertificates" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.certificates/" data-back-form-sertificates-update enctype="multipart/form-data">
            <div class="cabinet__box">
            <div class="cabinet__block">

                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Сертификат Электрик.ру</div>
                        <div class="cabinet__form-field pito">
                            <?if($arResult['PROFILE']['IS_CERTIFIED']):?>
                                <div class="backofficeFormGroup__field-text">
                                    Вы сертифицированы
                                </div>
                            <?elseif($arResult['PROFILE']['HAS_CERTIFIED_REQUEST']):?>
                                <div class="backofficeFormGroup__field-text">
                                    Заявка отправлена
                                </div>
                            <?else:?>
                                <a href="#make_sertificate_electric" data-fancybox="" data-src="#make_sertificate_electric" class="button button_md">
                                    Пройти сертификацию
                                </a>
                                <div id="make_sertificate_electric" class="fancyBlock">
                                    <div class="popup-content">
                                        <div class="fancybox-title">Сертификация Электрик.ру</div>
                                        <p>
                                            Чтоб появиться в каталоге специалистов, Вам нужно пройти сертификацию Электрик.ру.
                                        </p>
                                        <p>
                                            Загрузите документы, подтверждающие вашу личность и дождитесь звонка по&nbsp;указанному в&nbsp;контактах номеру.
                                            <br>
                                            <br>
                                        </p>
                                        <form action="/api/user.emaster/" name="sertificateElectric" id="sertificateElectric" class="fullForm js-custFile-img-form" data-form-emaster="" novalidate="">
                                            <div class="formGroup required notValid">
                                                <div class="formGroup-inner">
                                                    <div class="pasportList js-custFile-img-list">
                                                        <div class="pasportList__single js-custFile-img-list-single">
                                                            <div class="pasportList__single-delete js-custFile-img-list-single-delete">
                                                                <div class="pasportList__single-delete-line"></div>
                                                                <div class="pasportList__single-delete-line"></div>
                                                            </div>
                                                            <img src="" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="backofficeCustFile js-custFile-img js-custFile-img--limit">
                                                    <label for="sertificateElectric-pasport" class="custLabel">
                                                        <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-download"></use></svg></span>                        Загрузить сканы паспорта
                                                    </label>
                                                    <input type="file" value="" name="sertificateElectric-pasport[]" id="sertificateElectric-pasport" accept="image/jpg,image/jpeg,image/png,image/gif" multiple="" data-limit="4" required="" class="notValid">
                                                    <div class="custFile-note">
                                                        Нужно приложить сканы всех страниц паспорта в хорошем качестве
                                                    </div>
                                                </div>
                                                <div class="backofficeCustFile backofficeCustFile_alone js-custFile-img-alone">
                                                    <label for="sertificateElectric-pasport-alone" class="custLabel">
                                                        <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-add"></use></svg></span>                        Добавить скан
                                                    </label>
                                                    <div class="js-custFile-img-alone-list">
                                                        <input type="file" value="" name="sertificateElectric-pasport-alone[]" id="sertificateElectric-pasport-alone" accept="image/jpg,image/jpeg,image/png,image/gif" required="" class="notValid">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="formGroup required disabled">
                                                <div class="formGroup-inner">
                                                    <button type="submit" name="sertificateElectric-submit" disabled="disabled">
                                                        Подтвердить данные
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <?/*<div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Членство РАЭК</div>
                        <div class="cabinet__form-field pito">
                            <div class="backofficeFormGroup__field-inner">
                                <?if($arResult['PROFILE']['IS_RAEK_MEMBER']):?>
                                    <div class="backofficeFormGroup__field-text">
                                        Вы являетесь членом РАЭК
                                    </div>
                                <?elseif($arResult['PROFILE']['HAS_RAEK_REQUEST']):?>
                                    <div class="backofficeFormGroup__field-text">
                                        Заявка отправлена
                                    </div>
                                <?else:?>
                                    <a href="#make_sertificate_raek" data-fancybox="" data-src="#make_sertificate_raek" class="button button_md">
                                        Оставить заявку
                                    </a>
                                    <a href="#confirm_sertificate_raek" data-fancybox="" data-src="#confirm_sertificate_raek" class="masterEducation__single-value-link">
                                        Подтвердить членство
                                    </a>
                                    <div id="make_sertificate_raek" class="fancyBlock">
                                        <div class="fancyBlock-img"><img
                                                    src="/local/templates/.default/img/trash/soloForm3.jpg" alt=""></div>
                                        <div class="fancyBlock-inner">
                                            <div class="fancybox-title">Членство РАЭК</div>
                                            <p>Отправьте свои контактные данные и специалист свяжется с вами.</p>
                                            <?//НЕ удалять эту пустую форму.* По какой-то причине вырезается тег form?>
                                            <form action=""></form>
                                            <?//****************?>
                                            <form action="/api/backoffice.raek.add.request/" class="fullForm fullForm_two" data-raek-add-request >
                                                <div class="formGroup required ">
                                                    <div class="formGroup-inner"><label for="sertificateRaek-name"
                                                                                        class="active">ФИО</label> <input
                                                                type="text" id="sertificateRaek-name"
                                                                name="name" maxlength="512"
                                                                required="required" placeholder="ФИО" data-placeholder="ФИО"
                                                                value="<?=$arResult['PROFILE']['NAME']?>"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="formGroup required ">
                                                    <div class="formGroup-inner"><label for="sertificateRaek-phone"
                                                                                        class="active">Телефон</label>
                                                        <input type="tel" id="sertificateRaek-phone"
                                                               name="phone" maxlength="512"
                                                               required="required" placeholder="Телефон" data-placeholder="Телефон"
                                                               data-im-insert="true" value="<?=$arResult['PROFILE']["PROPERTIES"]["PHONE"]["VALUE"]?>"></div>
                                                </div>
                                                <div class="formGroup required ">
                                                    <div class="formGroup-inner"><label for="sertificateRaek-email"
                                                                                        class="active">E-mail</label> <input
                                                                type="email" id="sertificateRaek-email"
                                                                name="email" maxlength="512"
                                                                required="required" placeholder="E-mail"
                                                                data-placeholder="E-mail" value="<?=$arResult['PROFILE']["PROPERTIES"]["EMAIL"]["VALUE"]?>"></div>
                                                </div>
                                                <div class="formGroup formGroup__bottom">
                                                    <div class="formGroup-inner">
                                                        <button type="submit" name="sertificateRaek-submit">
                                                            Отправить заявку
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="confirm_sertificate_raek" class="fancyBlock confirmRaekFancyBlock">
                                        <div class="fancybox-title">Членство РАЭК</div>
                                        <p>
                                            Если вы уже прошли сертификацию РАЭК и имеете свидетельство,
                                            то загрузите его здесь и&nbsp;дождитесь когда наши специалисты вам
                                            перезвонят для подтверждения.
                                            <br> <br></p>
                                        <form action="/api/backoffice.raek.add.verification/" name="sertificateRaekConfirm" id="sertificateRaekConfirm" class="fullForm" data-raek-add-verification>
                                            <div class="formGroup required">
                                                <div class="formGroup-inner">
                                                    <div class="backofficeCustFile js-custFile"><label
                                                                for="sertificateRaekConfirm-file" class="custLabel">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" class="svg-icon">
                                                                <g fill="none" fill-rule="evenodd"
                                                                   stroke-linecap="square" stroke-linejoin="round"
                                                                   stroke-width="2">
                                                                    <path d="M3.5 20.5h17.202M12 3.549v11.39M15.485 12.364l-3.535 3.535-3.536-3.535"></path>
                                                                </g>
                                                            </svg>
                                                            Загрузить свидетельство о регистрации
                                                        </label> <input type="file" value=""
                                                                        name="document"
                                                                        id="sertificateRaekConfirm-file"
                                                                        required="required" data-input-file >
                                                        <div class="custFile-list js-custFile-list">
                                                            <div class="custFile-list-single js-custFile-list-single"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="formGroup ">
                                                <div class="formGroup-inner">
                                                    <button type="submit" name="sertificateElectric-submit">
                                                        Подтвердить данные
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?endif;?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>*/?>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Сертификаты</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">
															Добавить сертификат
														</span>
                            </a>
                            <div class="backofficeExperience-list" data-sertificates-list>
                                <? foreach ($arResult['PROFILE']["PROPERTIES"]["DOCUMENTS"] as $arDocument) {?>
                                    <div class="backofficeExperience-list__single backofficeExperience-list__single_start" data-sertificate-single>
                                        <div class="backofficeExperience-list__img">
                                            <a href="<?=$arDocument["SRC"]?>" data-fancybox="" data-fancybox-img="" data-caption="" class="backofficeExperience-list__img-link">
                                                <img src="<?=$arDocument["SRC"]?>" alt="<?=$arDocument["DESCRIPTION"]?>" class="backofficeExperience-list__img-tag old-sert">
                                            </a>
                                        </div>
                                        <div class="backofficeExperience-list__fields">
                                            <input name="title" type="text" placeholder="Название сертификата" value="<?=$arDocument["DESCRIPTION"]?>">
                                            <a href="#" class="backofficeExperience-list__delete" data-delete-sertificate><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                            <div class="backofficeExperience-list__space"></div>
                                            <label for="editSertificates-date-0" class="backofficeExperience-list__label">
                                                Выдан
                                            </label>
                                            <input id="editSertificates-date-0" name="date" type="text" class="backofficeExperience-list__medium_sert js-datapicker" value="<?=$arDocument["DATE"]?>">
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Образование</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">Добавить место учебы</span>
                            </a>
                            <div class="backofficeExperience-list" data-educations-list>
                                <? foreach ($arResult['PROFILE']['EDUCATIONS'] as $arEducation) {?>
                                    <div class="backofficeExperience-list__single" data-education-single>
                                        <input name="place" type="text" placeholder="Название места учёбы" value="<?=$arEducation["PLACE"]?>">
                                        <a href="#" class="backofficeExperience-list__delete" data-delete-education><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                        <div class="backofficeExperience-list__space"></div>
                                        <div class="backofficeExperience-list__large">
                                            <select name="type" id="">
                                                <?foreach ($arResult["PROFILE"]["LIST_EDUCATION_TYPE"] as $item):?>
                                                    <option value="<?= $item ?>" <?if($arEducation['TYPE'] == $item):?>selected<?endif;?>><?=$item?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                <?}?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cabinet__block">
                <div class="cabinet__form">
                    <div class="cabinet__form-item">
                        <div class="cabinet__form-title main__text">Курсы</div>
                        <div class="cabinet__form-field">
                            <a href="#" class="backofficeExperience__new">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-icon"><g fill="none" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="round" stroke-width="2"><path d="M3.5 11.5h17.202M11.75 3.5v17.202"></path></g></svg>
                                <span class="backofficeExperience__new-inner">Добавить курсы</span>
                            </a>
                            <div class="backofficeExperience-list" data-course-list>
                                <? foreach ($arResult['PROFILE']['COURSES'] as $arCourse) {?>
                                    <div class="backofficeExperience-list__single" data-course-single>
                                        <input name="course" type="text" placeholder="Название курса" value="<?=$arCourse["NAME"]?>">
                                        <a href="#" class="backofficeExperience-list__delete" data-delete-course><span class="backofficeExperience-list__delete-line"></span> <span class="backofficeExperience-list__delete-line"></span></a>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
                <div class="cabinet__action-message">При изменении данных проверка происходит в течении 24 часов</div>
            </div>
        </form>
    </div>
</div>



<script>
    var JS_SELECTS = {
        "LANGUANGES" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_LANGUAGES_NAMES"], false, true)?>,
        "LANGUANGES_LEVELS" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_LANGUAGES_LEVELS"], false, true)?>,
        "SKILLS" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_SKILLS"], false, true)?>,
        "MONTH" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_MONTH"], false, true)?>,
        "SERVICES" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_SERVICES"], false, true)?>,
        "EDUCATIONS" : <?=CUtil::PhpToJSObject($arResult["PROFILE"]["LIST_EDUCATION_TYPE"], false, true)?>,
    }
</script>

