<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="education-list__content">
    <a href="<?=$arParams["SEF_FOLDER"]?>" class="btn-back">К списку мероприятий</a>
    <div class="event">
        <div class="event__top">
            <div class="event__holder">
                <div class="event__image" style="background-image: url('<?=$arResult["DETAIL_PICTURE"]["SRC"] ? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"]?>');">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d143721.3185750522!2d37.56225561974967!3d55.74728641316869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d4b0c9%3A0x3d44d6cc5757cf4c!2z0JzQvtGB0LrQstCwLCDQoNC-0YHRgdC40Y8!5e0!3m2!1sru!2sua!4v1580653449549!5m2!1sru!2sua"
                            frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                    <div id="yandex_map" data-address="Барнаул, Алтайский край, ул. Шевченко, 144А "></div>
                </div>
                <div class="event__info">
                    <div class="event__info-top-info">
                        <div class="btn btn--gray"><?=$arResult["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></div>

                        <?if($arResult["PROPERTIES"]["LINK"]["VALUE"]/* && $arResult["IS_AUTHORIZED"]*/):?>
                            <a class="btn btn--red-small" href="<?= $arResult["PROPERTIES"]["LINK"]["VALUE"] ?>" target="_blank"><span><?= $arResult["PROPERTIES"]["BUTTON"]["VALUE"] ?></span></a>

                        <?/*elseif($arResult["PROPERTIES"]["LINK"]["VALUE"] && !$arResult["IS_AUTHORIZED"]):?>
                            <div class="main__text">
                                <p>Чтобы увидеть ссылку на вебинар вы должны быть зарегистрированным пользователем. <a href="/auth/">Войти</a></p>
                            </div>
                        <?*/endif;?>

                        <?if($arResult["PROPERTIES"]["VENDOR"]["ELEMENT"]):?>
                            <div class="btn btn--gray-logo fright">
                                <img src="<?=$arResult["PROPERTIES"]["VENDOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                            </div>
                        <?elseif($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]):?>
                            <div class="btn btn--gray-logo fright">
                                <img src="<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                            </div>
                        <?endif;?>
                    </div>



                    <h1 class="events-page__title main__text main__text--lg"><?=$arResult["NAME"]?></h1>
                    <?if($arResult["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                        <div class="main__text">Адрес проведения</div>
                        <div class="main__text light"><?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"]?>
                            <!--<a href="#" class="main__text link js-open-map-event">На карте</a>-->
                        </div>
                    <?endif;?>
                    <div class="main__text">Дата</div>
                    <div class="main__text light"><?=$arResult["DATE"]?><?=" ".$arResult["TIME"]?></div>
                    <?if($arResult["PROPERTIES"]["DURING"]["VALUE"]):?>
                        <div class="main__text">Продолжительность</div>
                        <div class="main__text light"><?=$arResult["PROPERTIES"]["DURING"]["VALUE"]?></div>
                    <?endif;?>
                    <div class="event__image event__image--mob" style="background-image: url('<?=$arResult["DETAIL_PICTURE"]["SRC"] ? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                </div>
            </div>
            <div class="event__description">
                <div class="main__text main__text--md">О мероприятии:</div>
                <div class="main__text simpleTextPage">
                    <?=$arResult["DETAIL_TEXT"]?>
                </div>



            </div>
        </div>
        <?if (!$arResult["COMPLETED"] && !$arResult["PROPERTIES"]["LINK"]["VALUE"]):?>
            <div class="event__bottom">
            <?$APPLICATION->IncludeComponent(
                "electric:form.result.new",
                "zapis_meropriyatie",
                array(
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "N",
                    "CHAIN_ITEM_LINK" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "EDIT_URL" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                    "LIST_URL" => "",
                    "SEF_MODE" => "N",
                    "SUCCESS_URL" => "",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "VARIABLE_ALIASES" => [],
                    "WEB_FORM_ID" => 1
                )
            );?>
        </div>
        <?endif;?>
    </div>
</div>