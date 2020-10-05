<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a href="<?=$arResult["SEF_FOLDER"]?>events/" class="btn-back">Вернуться назад</a>
<div class="cabinet__top">
    <div class="main__text main__text--lg">Создание мероприятия</div>
</div>
<form autocomplete="off" method="POST" action="/api/backoffice.event.add/" data-back-form-event-add enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>
    <div class="cabinet__box">
        <div class="cabinet__block">
            <div class="cabinet__form">
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Тип мероприятия</div>
                    <div class="cabinet__form-field">
                        <select name="TYPE" id="">
                            <? foreach ($arResult["FIELDS"]["TYPE"]["VALUES"] as $arField) {?>
                                <option value="<?=$arField["ID"]?>"><?=$arField["NAME"]?></option>
                            <?}?>
                        </select>
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Название</div>
                    <div class="cabinet__form-field">
                        <input type="text" name="NAME" placeholder=""  autocomplete="off" class="field-input field-input--light" required="required">
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Описание</div>
                    <div class="cabinet__form-field">
                                                <textarea name="PREVIEW_TEXT" id="" cols="30" rows="10"
                                                          class="field-textarea field-textarea--lg field-textarea--light" required="required"></textarea>
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Изображение</div>
                    <div class="cabinet__form-field cabinet__form-field--inline">
                        <div class="cabinet__form-photo cabinet__form-photo--empty cabinet__form-photo--lg" data-logo-block
                             style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-camera.svg');"></div>
                        <div class="cabinet__form-rside">
                            <label for="field-add-photo" class="field-add-photo">
                                <input type="file" name="PREVIEW_PICTURE" id="field-add-photo">
                                <span class="field-add-photo__title">Загрузить афишу</span>
                            </label>
                            <div class="main__text main__text--xs light">Вы можете загрузить изображение в формате
                                JPG, GIF или PNG. Размер фото не больше 10 Мб
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Адрес</div>
                    <div class="cabinet__form-field cabinet__form-field--rlt">
                        <input type="text" name="ADDRESS" placeholder="" autocomplete="off" class="field-input field-input--light">
                        <a href="#" class="main__text main__text--sm link">Указать на карте</a>
                    </div>
                </div>
                <div class="cabinet__form-item">
                    <div class="cabinet__form-title main__text">Дата</div>
                    <div class="cabinet__form-field">
                        <input type="text" name="ACTIVE_FROM" placeholder="Выберите дату" autocomplete="off"
                               class="field-input field-input--datapicker field-input--light js-field-input--datapicker">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cabinet__actions">
        <button class="btn btn--red" data-form-submit>Создать мероприятие</button>
        <a href="<?=$arResult["SEF_FOLDER"]?>events/" class="main__text link">Отменить</a>
    </div>
</form>