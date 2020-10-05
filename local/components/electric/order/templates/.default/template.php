<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<form autocomplete="off" method="POST" action="/api/order.add/" data-form-order   enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>

    <div class="order__content js-slider-order-steps">
        <div class="order__step_wrapper" data-step1>
            <div class="order__header">
                <div class="order__headline">
                    <div class="main__headline main__headline--black">Новая заявка.</div>
                    <div class="main__headline main__headline--gray">Шаг 1 из 3</div>
                </div>
                <div class="order__steps">
                    <div class="order__step is-active"></div>
                    <div class="order__step"></div>
                    <div class="order__step"></div>
                </div>
                <div class="order__description main__text main__text--xs light">Заполните заявку максимально подробно, чтобы
                    подобрать подходящего специалиста в короткие сроки и по приемлемой стоимости. Если вы не знаете ответ на
                    какие-либо вопросы — оставьте поля пустыми
                </div>
            </div>
            <div class="order__body">
                <div class="order__box">
                    <div class="order__box-headline order__box-headline--inline">
                        <div class="main__text main__text--md red">Укажите адрес и категорию помещения</div>
                        <a href="#city_change" data-fancybox="" data-src="#city_change" class="main__text main__text--xs order__location"><?=$arResult["USER"]["CITY"]["NAME"]?></a>
                        <input type="hidden" name="restricted" value="<?=$arResult["USER"]["CITY"]["ID"]?>">
                        <input type="hidden" name="restricted-name" value="<?=$arResult["USER"]["CITY"]["NAME"]?>">
                    </div>
                    <div class="order__box-title order__box-title--mt main__text mb30">Адрес выезда специалиста</div>
                    <div class="order__form">
                        <div class="order__form-item order__form-item--sm js-popupSearchResult-input-wrap transLabel">
                            <label for="order-street">Улица</label>
                            <input type="hidden" name="order-location-id" id="order-location-id" class="js-popupSearchResult-inputHidden">
                            <input type="text"
                                   id="order-street"
                                   name="order-street"
                                   autocomplete="off"
                                   placeholder="Улица"
                                   class="field-input field-input--sm field-input--light js-popupSearchResult-input"
                                   data-order-street
                                   data-restricted="<?=$arResult["USER"]["CITY"]["ID"]?>">
                            <div class="popupSearchResult js-popupSearchResult"></div>
                        </div>
                        <div class="order__form-item order__form-item--sm transLabel">
                            <label for="">Номер дома</label>
                            <input type="text" name="order-house" placeholder="Номер дома"
                                   class="field-input field-input--sm field-input--light">
                        </div>
                        <div class="order__form-item order__form-item--sm transLabel">
                            <label for="">Квартира/Офис</label>
                            <input type="text" name="order-flat" placeholder="Квартира/Офис"
                                   class="field-input field-input--sm field-input--light">
                        </div>
                        <div class="order__form-item order__form-item--sm transLabel">
                            <label for="">Корп./Стр.</label>
                            <input type="text" name="order-korp" placeholder="Корп./Стр."
                                   class="field-input field-input--sm field-input--light">
                        </div>
                        <div class="order__form-item order__form-item--sm transLabel">
                            <label for="">Этаж</label>
                            <input type="text" name="order-floor" placeholder="Этаж" class="field-input field-input--sm field-input--light">
                        </div>
                        <div class="order__form-item order__form-item--sm transLabel">
                            <label for="">Код домофона</label>
                            <input type="text" name="order-intercom" placeholder="Код домофона" class="field-input field-input--sm field-input--light">
                        </div>
                    </div>
                </div>
                <div class="order__box">
                    <div class="order__box-title">Выберите категорию помещения</div>
                    <div class="order__box-items" data-room-wrap>
                        <input type="hidden" name="order-room" class="js-order-room">
                        <? foreach ($arResult["FIELDS"]["ROOM"]["VALUES"] as $arValue) {?>
                            <div class="order__box-item">
                                <button type="button" class="btn btn--filter" data-room data-room-id="<?=$arValue["ID"]?>"><?=$arValue["VALUE"]?></button>
                            </div>
                        <?}?>
                    </div>
                    <div class="order__box-action">
                        <button type="button" class="btn btn--red js-next-step" data-next-step>Продолжить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="order__step_wrapper" data-step2>
            <div class="order__header">
                <div class="order__headline">
                    <a href="#" class="btn-prev js-prev-step" data-prev-step></a>
                    <div class="main__headline main__headline--black">Новая заявка.</div>
                    <div class="main__headline main__headline--gray">Шаг 2 из 3</div>
                </div>
                <div class="order__steps">
                    <div class="order__step"></div>
                    <div class="order__step is-active"></div>
                    <div class="order__step"></div>
                </div>
                <div class="order__description main__text main__text--xs light">Заполните заявку максимально подробно, чтобы
                    подобрать подходящего специалиста в короткие сроки и по приемлемой стоимости. Если вы не знаете ответ на
                    какие-либо вопросы — оставьте поля пустыми
                </div>
            </div>
            <div class="order__body">
                <div class="order__box">
                    <div class="order__box-headline">
                        <div class="main__text main__text--md red mb15">Что нужно сделать?</div>
                    </div>
                    <div class="order__form">
                        <div class="order__form-item order__form-item--lg js-popupSearchResult-input-wrap transLabel">
                            <label for="">Выбор услуги</label>
                            <input type="hidden" name="order-service-id" id="order-service-id" class="js-popupSearchResult-inputHidden">
                            <input type="text"
                                   name="order-service-name"
                                   placeholder="Поиск услуги..."
                                   class="field-input field-input--search field-input--sm field-input--light js-popupSearchResult-input"
                                   value="<?= htmlentities($_GET["q"]) ?? "" ?>"
                                   data-order-service
                            >
                            <div class="popupSearchResult js-popupSearchResult"></div>
                        </div>
                    </div>
                    <div class="all-services__lists all-services__lists--order">
                        <?
                        foreach ($arResult["SERVICES"] as $arService) {?>
                            <div class="all-services__list">
                                <div class="all-services__title main__text medium"><?=$arService["NAME"]?></div>
                                <?if($arService['ITEMS']):?>
                                <ul class="all-services__items">
                                    <? foreach ($arService["ITEMS"] as $arItem) {?>
                                        <li class="all-services__item">
                                            <a href="#" class="all-services__item-link main__text main__text--sm link" data-order-service-link data-order-service-id="<?=$arItem["ID"]?>"><?=$arItem["NAME"]?></a>
                                        </li>
                                    <?}?>
                                </ul>
                                <?endif;?>
                            </div>
                        <?}?>

                    </div>
                    <div class="order__box-right">
                        <a href="#" class="main__text main__text--sm link gray" data-order-service-link >Я не знаю, что нужно сделать</a>
                    </div>
                </div>
                <div class="order__box">
                    <div class="order__box-title">Подробное описание работ</div>
                    <div class="order__form">
                        <div class="order__form-item order__form-item--lg">
                                    <textarea name="order-description" id="" cols="30" rows="10" class="field-textarea field-textarea--sm field-textarea--light"
                                              placeholder="Подробно опишите, что сломалось или требуется установить"></textarea>
                        </div>
                        <div class="order__form-item order__form-item--lg">
                            <label for="add-file" class="field-add-file">
                                <input type="file" id="add-file" name="order-file">
                                <span>Прикрепить фотографии</span>
                            </label>
                        </div>


                    </div>
                </div>
                <div class="order__box">
                    <div class="order__box-title">Дополнительная информация</div>
                    <div class="order__form order__form--sm">
                        <div class="order__form-item order__form-item--lg">
                            <label for="checkbox-1" class="field-checkbox">
                                <input type="checkbox" id="checkbox-1" name="order-my-spares" value="<?=$arResult["FIELDS"]["MY_SPARES"]["VALUES"]["ID"]?>">
                                <span class="field-checkbox__mark"></span>
                                <span class="field-checkbox__title">Есть свои материалы</span>
                            </label>
                        </div>
                        <div class="order__form-item order__form-item--lg">
                            <label for="checkbox-2" class="field-checkbox">
                                <input type="checkbox" id="checkbox-2" name="order-my-tools" value="<?=$arResult["FIELDS"]["MY_TOOLS"]["VALUES"]["ID"]?>">
                                <span class="field-checkbox__mark"></span>
                                <span class="field-checkbox__title">Есть необходимые инструменты</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="order__box">
                    <div class="order__box-title">В какое время вам удобно встретить специалиста?</div>
                    <div class="order__form order__form--sm">
                        <? foreach ($arResult["FIELDS"]["WHEN"]["VALUES"] as $index => $arValue) {?>
                            <div class="order__form-item order__form-item--lg">
                                <label for="radiobox-<?=$arValue["ID"]?>" class="field-radiobox">
                                    <input type="radio" id="radiobox-<?=$arValue["ID"]?>" name="order-when" data-to-time value="<?=$arValue["ID"]?>" <?if($index==0):?>checked="checked"<?endif;?>>
                                    <span class="field-radiobox__mark"></span>
                                    <span class="field-radiobox__title"><?=$arValue["VALUE"]?></span>
                                </label>
                            </div>
                        <?}?>
                    </div>
                    <div class="order__form disabled" data-to-time-block>
                        <div class="order__form-item order__form-item--sm">
                            <input type="text" name="order-date" placeholder="Дата визита"
                                   class="field-input field-input--datapicker field-input--sm field-input--light js-field-input--datapicker">
                        </div>
                        <div class="order__form-item order__form-item--sm">
                            <input type="text" name="order-time" placeholder="Время визита"
                                   class="field-input field-input--timepicker field-input--sm field-input--light js-field-input--timepicker">
                        </div>
                    </div>
                    <div class="order__box-action">
                        <button type="button" class="btn btn--red js-next-step" data-next-step>Продолжить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="order__step_wrapper" data-step3>
            <div class="order__header">
                <div class="order__headline">
                    <a href="#" class="btn-prev" data-prev-step></a>
                    <div class="main__headline main__headline--black">Новая заявка.</div>
                    <div class="main__headline main__headline--gray">Шаг 3 из 3</div>
                </div>
                <div class="order__steps">
                    <div class="order__step"></div>
                    <div class="order__step"></div>
                    <div class="order__step is-active"></div>
                </div>
                <div class="order__description main__text main__text--xs light">Заполните заявку максимально подробно, чтобы
                    подобрать подходящего специалиста в короткие сроки и по приемлемой стоимости. Если вы не знаете ответ на
                    какие-либо вопросы — оставьте поля пустыми
                </div>
            </div>
            <div class="order__body">
                <div class="order__box">
                    <div class="order__box-headline">
                        <div class="main__text main__text--md red">Как с вами связаться?</div>
                    </div>
                    <div class="order__box-title order__box-title--mt mb15">Контактная информация</div>
                    <div class="order__form">
                        <div class="order__form-item order__form-item--md transLabel">
                            <label for="">Ваши фамилия и имя</label>
                            <input type="text" name="order-name" placeholder="Ваши фамилия и имя"
                                   class="field-input field-input--sm field-input--light"
                                    value="<?=trim($arResult["USER"]["LAST_NAME"]." ".$arResult["USER"]["NAME"])?>">
                        </div>
                        <div class="order__form-item order__form-item--md transLabel">
                            <label for="">Номер телефона</label>
                            <input type="text" name="order-phone" placeholder="Номер телефона" data-phone
                                   class="field-input field-input--sm field-input--light" value="<?=$arResult["USER"]["PHONE"]?>"
                                   autocomplete="off"
                            >
                        </div>
                        <?if(!$arResult["USER"]["IS_AUTH"]):?>
                            <div class="order__form-item order__form-item--sm" >
                                <label for="checkbox-email" class="field-checkbox">
                                    <input type="checkbox" id="checkbox-email" name="order-register" value="Y" data-reg-checkbox checked="checked">
                                    <span class="field-checkbox__mark"></span>
                                    <span class="field-checkbox__title">Зарегистрироваться</span>
                                </label>
                            </div>
                            <?/*<div class="order__form-item order__form-item--sm"  data-register-field >
                                <input type="text" name="order-email" placeholder="Ваши Email"
                                       class="field-input field-input--sm field-input--light">
                            </div>*/?>
                            <div class="order__form-item order__form-item--sm" data-register-field >
                                <input type="password" name="order-password" placeholder="Пароль"
                                       class="field-input field-input--sm field-input--light">
                            </div>
                        <?endif;?>
                    </div>



                </div>
                <div class="order__box">
                    <div class="order__box-title">Когда вам можно позвонить?</div>
                    <div class="main__text main__text--mt main__text--xs light">Специалист свяжется с вами в удобное для вас
                        время для уточнения деталей заявки
                    </div>
                    <div class="order__form">
                        <div class="order__form-item order__form-item--sm" data-time-recall>
                            <select name="order-call-time" id="">
                                <? foreach ($arResult["FIELDS"]["CALL_TIME"]["VALUES"] as $index=>$arValue) {?>
                                    <option value="<?=$arValue["ID"]?>" <?if($index==0):?>selected<?endif;?>><?=$arValue["VALUE"]?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="order__form-item order__form-item--auto">
                            <label for="checkbox-3" class="field-checkbox">
                                <input type="checkbox" id="checkbox-3" name="order-call-anytime" data-anytime value="<?=$arResult["FIELDS"]["CALL_ANYTIME"]["VALUES"]["ID"]?>">
                                <span class="field-checkbox__mark"></span>
                                <span class="field-checkbox__title">В любое время</span>
                            </label>
                        </div>
                    </div>

                    <div class="order__box-action">
                        <button type="submit" class="btn btn--red" data-form-submit>Оформить заявку</button>
                        <div class="main__text main__text--xs light">Отправляя форму я соглашаюсь на обработку моих персональных
                            данных, а также с <a href="<?=PATH_TERMS?>" target="_blank" class="main__text link-xs">политикой конфиденциальности</a>
                        </div>
                    </div>
                    <div class="order__warning error red" data-message></div>
                </div>
            </div>
        </div>
    </div>
</form>