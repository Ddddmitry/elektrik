<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//var_dump($arResult);
?>
<div class="order__content">
    <div class="order__header">
        <div class="order__headline">
            <div class="main__headline main__headline--black">Отлично! Заявка добавлена</div>
        </div>
        <div class="order__steps is-done">
            <div class="order__step"></div>
            <div class="order__step"></div>
            <div class="order__step"></div>
        </div>
        <div class="order__description main__text main__text--xs light">Как только ваша заявка пройдет проверку
            модераторами, сотни специалистов увидят её в каталоге заказов
        </div>
        <div class="order__warning">
            <div class="main__text main__text--xs blue">Оплата за услугу оговаривается со специалистом лично.
                Будьте внимательны и не вносите предоплату до выполнения работы.</div>
        </div>
    </div>
    <div class="order__success">
        <div class="main__text">Информация о вашей заявке:</div>
        <div class="order__success-inners">
            <div class="order__success-inner">
                <div class="order__success-title">
                    <div class="main__text main__text--sm light">Адрес</div>
                </div>
                <div class="order__success-description">
                    <div class="main__text main__text--sm">
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["CITY"]["VALUE"]):?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["CITY"]["VALUE"]?>
                        <?endif;?>
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["STREET"]["VALUE"]):?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["STREET"]["VALUE"]?>
                        <?endif;?>
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["HOUSE"]["VALUE"]?>
                        <?endif;?>
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["KORP"]["VALUE"]):?>
                            корп. <?=$arResult["ELEMENT"]["PROPERTIES"]["KORP"]["VALUE"]?>
                        <?endif;?>
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["FLAT"]["VALUE"]):?>
                            кв./оф. <?=$arResult["ELEMENT"]["PROPERTIES"]["FLAT"]["VALUE"]?>
                        <?endif;?>
                        (
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["FLOOR"]["VALUE"]?> этаж,
                        <?endif;?>
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                            код домофона <?=$arResult["ELEMENT"]["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                        <?endif;?>
                        )

                    </div>
                    <?if($arResult["ELEMENT"]["PROPERTIES"]["ROOM"]["VALUE_ENUM"]):?>
                        <div class="main__text main__text--xs light"><?=$arResult["ELEMENT"]["PROPERTIES"]["ROOM"]["VALUE_ENUM"]?></div>
                    <?endif;?>
                </div>
            </div>
            <div class="order__success-inner">
                <div class="order__success-title">
                    <div class="main__text main__text--sm light">Услуга</div>
                </div>
                <div class="order__success-description">
                    <div class="main__text main__text--sm"><?=$arResult["ELEMENT"]["NAME"]?></div>
                    <div class="main__text main__text--xs light"><?=$arResult["ELEMENT"]["PREVIEW_TEXT"]?></div>
                    <?if(count($arResult["ELEMENT"]["PROPERTIES"]["PHOTOS"]) > 0):?>
                        <div class="order__success-photos">
                            <? foreach ($arResult["ELEMENT"]["PROPERTIES"]["PHOTOS"] as $arPhoto) {?>
                                <div class="order__success-photo" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"]?>');"></div>
                            <?}?>
                        </div>
                    <?endif;?>
                </div>
            </div>
            <div class="order__success-inner">
                <div class="order__success-title">
                    <div class="main__text main__text--sm light">Дополнительная
                        информация</div>
                </div>
                <div class="order__success-description">
                    <?if($arResult["ELEMENT"]["PROPERTIES"]["MY_SPARES"]["VALUE"]):?>
                        <div class="main__text main__text--sm">Есть свои запчасти</div>
                    <?endif;?>
                    <?if($arResult["ELEMENT"]["PROPERTIES"]["MY_TOOLS"]["VALUE"]):?>
                        <div class="main__text main__text--sm">Есть свой инструмент</div>
                    <?endif;?>
                    <div class="main__text main__text--sm">Время выезда:
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["WHEN"]["VALUE_XML_ID"] == "fast"):?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["WHEN"]["VALUE_ENUM"]?>
                        <?else:?>
                            <?=$arResult["ELEMENT"]["PROPERTIES"]["DATE_TIME"]["DATE"]?> <?=$arResult["ELEMENT"]["PROPERTIES"]["DATE_TIME"]["TIME"]?>
                        <?endif;?>
                    </div>
                </div>
            </div>
            <div class="order__success-inner">
                <div class="order__success-title">
                    <div class="main__text main__text--sm light">Контактная
                        информация</div>
                </div>
                <div class="order__success-description">
                    <div class="main__text main__text--sm"><?=$arResult["ELEMENT"]["PROPERTIES"]["CONTACT_INFO"]["VALUE"]?></div>
                    <div class="main__text main__text--sm"><?=$arResult["ELEMENT"]["PROPERTIES"]["CONTACT_PHONE"]["VALUE"]?></div>
                    <div class="main__text main__text--xs light">
                        <?if($arResult["ELEMENT"]["PROPERTIES"]["CALL_ANYTIME"]["VALUE"]):?>
                            Звонить в любое время
                        <?else:?>
                            Звонить с <?=$arResult["ELEMENT"]["PROPERTIES"]["CALL_TIME"]["VALUE_ENUM"]?>
                        <?endif;?>
                    </div>
                </div>
            </div>
            <div class="order__success-inner order__success-inner--actions">
                <a href="/order/" class="btn btn--red">Добавить еще одну заявку</a>
                <?if($arResult["IS_AUTH"]):?>
                    <a href="#" class="main__text main__text--sm link">Редактировать заявку в личном кабинете</a>
                <?endif;?>
            </div>
        </div>
    </div>
</div>