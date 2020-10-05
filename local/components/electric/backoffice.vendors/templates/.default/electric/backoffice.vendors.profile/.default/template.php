<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__top">
    <div class="main__text main__text--lg"><?=$arResult["PROFILE"]["NAME"]?></div>
</div>
<div class="cabinet__tabs cabinet__tabs--auto">
    <div class="cabinet__tabs-btns">
        <div class="cabinet__tabs-btn main__text is-open" data-open="tab-about">О компании</div>
        <div class="cabinet__tabs-btn main__text" data-open="tab-requisites">Реквизиты</div>
        <div class="cabinet__tabs-btn main__text" data-open="tab-contact">Контактное лицо</div>
    </div>
    <div class="cabinet__tabs-contents">
        <form autocomplete="off" method="POST" action="/api/backoffice.vendors.update/" data-form-vendor enctype="multipart/form-data">
            <div class="cabinet__tabs-content tab-about is-open">
                <div class="cabinet__box">
                    <div class="cabinet__block">
                        <div class="cabinet__form">
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Название компании</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="NAME" id="" placeholder="" value="<?=$arResult["PROFILE"]["NAME"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Логотип</div>
                                <div class="cabinet__form-field cabinet__form-field--inline">
                                    <div class="cabinet__form-photo"
                                         style="background-image: url('<?=$arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"]?>');" data-logo-block>
                                    </div>
                                    <div class="cabinet__form-rside">
                                        <label for="field-add-photo" class="field-add-photo">
                                            <input type="file" name="PREVIEW_PICTURE" id="field-add-photo">
                                            <span class="field-add-photo__title">Загрузить логотип</span>
                                        </label>
                                        <div class="main__text main__text--xs light">Вы можете загрузить изображение в формате
                                            JPG,
                                            GIF
                                            или PNG. Размер фото не больше 10 Мб
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabinet__action">
                    <button class="btn btn--red" data-form-submit>Сохранить изменения</button>
                </div>
            </div>
            <div class="cabinet__tabs-content tab-requisites">
                <div class="cabinet__box">
                    <div class="cabinet__block">
                        <div class="cabinet__form">
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">ИНН</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="INN" id="" placeholder="ИНН" value="<?=$arResult["PROFILE"]["PROPERTIES"]["INN"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">КПП</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="KPP" id="" placeholder="КПП" value="<?=$arResult["PROFILE"]["PROPERTIES"]["KPP"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">ОРГН/ОГРНИП</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="OGRN" id="" placeholder="ОРГН/ОГРНИП" value="<?=$arResult["PROFILE"]["PROPERTIES"]["OGRN"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Юридический адрес</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="UR_ADDRESS" id="" placeholder="Юридический адрес"
                                           value="<?=$arResult["PROFILE"]["PROPERTIES"]["UR_ADDRESS"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Почтовый адрес</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="MAIL_ADDRESS" id="" placeholder=""
                                           value="<?=$arResult["PROFILE"]["PROPERTIES"]["MAIL_ADDRESS"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabinet__action">
                    <button class="btn btn--red" data-form-submit>Сохранить изменения</button>
                </div>
            </div>
            <div class="cabinet__tabs-content tab-contact">
                <div class="cabinet__box">
                    <div class="cabinet__block">
                        <div class="cabinet__form">
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Фамилия Имя Отчество</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="FIO" id="" placeholder="" value="<?=$arResult["PROFILE"]["PROPERTIES"]["FIO"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Телефон</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="PHONE" id="" placeholder="Если есть" value="<?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?>"
                                           data-phone
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">E-mail</div>
                                <div class="cabinet__form-field">
                                    <input type="email" name="EMAIL" id="" placeholder="" value="<?=$arResult["PROFILE"]["PROPERTIES"]["EMAIL"]["VALUE"]?>"
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
                                    <input type="text" name="WHATSAPP" id="" placeholder="" data-phone
                                           value="<?=$arResult["PROFILE"]["PROPERTIES"]["WHATSAPP"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                            <div class="cabinet__form-item">
                                <div class="cabinet__form-title main__text">Viber</div>
                                <div class="cabinet__form-field">
                                    <input type="text" name="VIBER" id="" placeholder="" data-phone
                                           value="<?=$arResult["PROFILE"]["PROPERTIES"]["VIBER"]["VALUE"]?>"
                                           class="field-input field-input--light">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabinet__action">
                    <button class="btn btn--red" data-form-submit>Сохранить изменения</button>
                </div>
            </div>
        </form>
    </div>
</div>


