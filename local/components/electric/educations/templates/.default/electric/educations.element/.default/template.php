<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="education-list__content">
    <a href="<?=$arParams["SEF_FOLDER"]?>" class="btn-back">К списку программ обучения</a>
    <div class="education-list__holder">
        <div class="education-list__lside">
            <div class="education">
                <div class="education__top">
                    <h1 class="education__title main__text main__text--lg"><?=$arResult["NAME"]?></h1>
                    <?if($arResult["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                    <div class="education__address">
                        <div class="main__text">Адрес проведения</div>
                        <div class="main__text"><?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"]?>
                                <a href="#" class="main__text link js-open-map">На карте</a>
                        </div>
                    </div>
                    <?endif;?>
                    <div class="education__container">
                        <div class="education__image" style="background-image: url('<?=$arResult["DETAIL_PICTURE"]["SRC"] ? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                        <div class="education__map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d143721.3185750522!2d37.56225561974967!3d55.74728641316869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d4b0c9%3A0x3d44d6cc5757cf4c!2z0JzQvtGB0LrQstCwLCDQoNC-0YHRgdC40Y8!5e0!3m2!1sru!2sua!4v1580653449549!5m2!1sru!2sua"
                                    frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                    </div>
                    <?if($arResult["DETAIL_TEXT"]):?>
                        <div class="education__description">
                            <div class="main__text main__text--md">О программе:</div>
                            <div class="main__text"><?=$arResult["DETAIL_TEXT"]?></div>
                        </div>
                    <?endif;?>
                    
                    <?if($arResult["PROPERTIES"]["URL_COURSE"]["VALUE"] && $arResult["IS_AUTHORIZED"]):?>
                        <div class="education__description">
                            <div class="main__text">
                                <p>
                                    Пройти курс вы можете по следующей <a class="courseLink"  data-src="<?=$arResult["PROPERTIES"]["URL_COURSE"]["VALUE"]?>" href="javascript:;">ссылке</a>
                                </p>
                            </div>
                        </div>
                    <?elseif($arResult["PROPERTIES"]["URL_COURSE"]["VALUE"] && !$arResult["IS_AUTHORIZED"]):?>
                        <div class="education__description">
                            <div class="main__text main__text_notice">
                                Чтобы пройти курс вы должны быть зарегистрированным пользователем. <a href="/auth/">Войти</a>
                            </div>
                        </div>
                    <?endif;?>

                    <div class="education-list__mob">
                        <div class="education-list__rside-holder">
                            <div class="education-list__rside-title main__text">Тип обучения</div>
                            <div class="education-list__rside-items">
                                <div class="education-list__rside-item">
                                    <div class="btn btn--gray"><?=$arResult["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></div>
                                </div>
                            </div>
                        </div>
                        <div class="education-list__rside-holder">
                            <div class="education-list__rside-title main__text">Дата начала</div>
                            <div class="education-list__rside-text main__text"><?=$arResult["DATE"]?></div>
                        </div>
                        <?if($arResult["PROPERTIES"]["DURING"]["VALUE"]):?>
                            <div class="education-list__rside-holder">
                                <div class="education-list__rside-title main__text">Длительность</div>
                                <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DURING"]["VALUE"]?></div>
                            </div>
                        <?endif;?>
                        <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]):?>
                            <div class="education-list__rside-holder">
                                <div class="education-list__rside-title main__text">Автор программы</div>
                                <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["NAME"]?></div>
                                <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_PHONE_VALUE"]):?>
                                    <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_PHONE_VALUE"]?></div>
                                <?endif;?>
                                <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]):?>
                                    <a href="mailto:<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]?>"
                                       class="education-list__rside-text education-list__rside-email main__text link"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]?></a>
                                <?endif;?>
                                <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]):?>
                                    <div class="btn btn--gray-logo-lg"><img src="<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["NAME"]?>"></div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    </div>
                </div>
                <?if(!$arResult["COMPLETED"]):?>
                <div class="education__bottom">
                    <?$APPLICATION->IncludeComponent(
                        "electric:form.result.new",
                        "zapis_education",
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
                            "WEB_FORM_ID" => 4
                        )
                    );?>
                </div>
                <?endif;?>
            </div>
            <?$APPLICATION->IncludeComponent('electric:educations.list', 'closest', [
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "36000",
                "SEF_FOLDER" => "/education/",
                "IBLOCK_ID" => IBLOCK_EDUCATIONS,
                "TYPE_IBLOCK_ID" => IBLOCK_EDUCATIONS_TYPES,
                "PAGE_SIZE" => "4",
                "FILTER" => ["!ID" => $arResult["ID"]]
            ],$component);?>


        </div>
        <div class="education-list__rside education-list__rside--mob">
            <div class="education-list__rside-holder">
                <div class="education-list__rside-title main__text">Тип обучения</div>
                <div class="education-list__rside-items">
                    <div class="education-list__rside-item">
                        <div class="btn btn--gray"><?=$arResult["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></div>
                    </div>
                </div>
            </div>
            <div class="education-list__rside-holder">
                <div class="education-list__rside-title main__text">Дата начала</div>
                <div class="education-list__rside-text main__text"><?=$arResult["DATE"]?></div>
            </div>
            <?if($arResult["PROPERTIES"]["DURING"]["VALUE"]):?>
            <div class="education-list__rside-holder">
                <div class="education-list__rside-title main__text">Длительность</div>
                <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DURING"]["VALUE"]?></div>
            </div>
            <?endif;?>
            <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]):?>
                <div class="education-list__rside-holder">
                    <div class="education-list__rside-title main__text">Автор программы</div>
                    <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["NAME"]?></div>
                    <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_PHONE_VALUE"]):?>
                        <div class="education-list__rside-text main__text"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_PHONE_VALUE"]?></div>
                    <?endif;?>
                    <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]):?>
                    <a href="mailto:<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]?>"
                       class="education-list__rside-text education-list__rside-email main__text link"><?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PROPERTY_EMAIL_VALUE"]?></a>
                    <?endif;?>
                    <?if($arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]):?>
                        <div class="btn btn--gray-logo-lg"><img src="<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arResult["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"]["NAME"]?>"></div>
                    <?endif;?>
                </div>
            <?endif;?>
        </div>
    </div>
</div>
