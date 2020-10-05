<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<div class="cabinet__tabs-contents">
    <div class="cabinet__tabs-content is-open" data-tab-content="contacts" >
        <form autocomplete="off" method="POST" action="/api/backoffice.update.contacts/" data-back-form-profile-update enctype="multipart/form-data">
            <input type="hidden" name="TYPE" value="clients">
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
                        <div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">Номер телефона</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="PHONE" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?>"
                                       class="field-input field-input--light" data-phone>
                            </div>
                        </div>
                        <?/*<div class="cabinet__form-item">
                            <div class="cabinet__form-title main__text">E-mail</div>
                            <div class="cabinet__form-field">
                                <input type="text" name="EMAIL" id="" placeholder=""
                                       value="<?=$arResult["PROFILE"]["PROPERTIES"]["EMAIL"]["VALUE"]?>"
                                       class="field-input field-input--light">
                            </div>
                        </div>*/?>
                    </div>
                </div>
            </div>
            <div class="cabinet__action">
                <button class="btn btn--red" type="submit" data-form-submit>Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>



