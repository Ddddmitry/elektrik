<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
    <div class="partners-services__content">
        <div class="partners-services__holder">
            <div class="partners-services__lside js-filter-container">
                <h1 class="main__headline">
                    <span>Партнерские предложения</span>
                </h1>
                <div class="partners-services__inners js-poffers-list-container" data-page="1">
<?endif;?>
                <?if($arResult["ITEMS"]):?>
                    <?foreach ($arResult["ITEMS"] as $arItem):?>
                        <div class="partners-services__inner">
                            <div class="partners-services__inner-container">
                                <a href="#arend_site_<?=$arItem["ID"]?>" class="partners-services__inner-image" data-fancybox=""
                                   data-src="#arend_site_<?=$arItem["ID"]?>" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></a>
                                <div class="partners-services__inner-holder">
                                    <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>
                                    <div class="partners-services__inner-title main__text main__text--md">
                                        <a href="#arend_site_<?=$arItem["ID"]?>"
                                           data-fancybox=""
                                           data-src="#arend_site_<?=$arItem["ID"]?>"><?=$arItem["NAME"]?></a>

                                    </div>
                                    <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                </div>
                                <?/*if($arResult["IS_AUTHORIZED"]):?>
                                    <?if($arItem["PROPERTIES"]["POINTS"]["VALUE"]):?>
                                        <div class="partners-services__inner-holder">
                                            <div class="main__text">Оплатите баллами</div>
                                            <div class="main__text main__text--md red">от <?=$arItem["PROPERTIES"]["POINTS"]["VALUE"]?> баллов</div>
                                        </div>
                                    <?endif;?>
                                <?endif;*/?>
                            </div>
                        </div>
                        <div id="arend_site_<?=$arItem["ID"]?>" class="fancyBlock">
                            <div class="fancybox-title"><?=$arItem["NAME"]?></div>
                            <?if($arItem["PREVIEW_PICTURE"]):?>
                                <p>
                                    <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                                </p>
                            <?endif;?>
                            <?=$arItem["PREVIEW_TEXT"]?>

                            <?if($arResult["IS_AUTHORIZED"] && $arResult["USER_TYPE"] == "distributor"):?>

                                <?if($arItem["PROPERTIES"]["LINK"]["VALUE"] && $arItem["PROPERTIES"]["BUTTON"]["VALUE"]):?>
                                    <br>
                                    <br>
                                    <p>
                                        <a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="button3" target="_blank">
                                            <?=$arItem["PROPERTIES"]["BUTTON"]["VALUE"]?>
                                        </a>
                                    </p>
                                <?elseif($arItem["PROPERTIES"]["PROMOCODE"]["VALUE"]):?>
                                    <br>
                                    <br>
                                    <p>
                                        Чтобы воспользоваться предложением используйте промокод: <span class="poffer__promocode"><?=$arItem["PROPERTIES"]["PROMOCODE"]["VALUE"]?></span>
                                    </p>
                                <?elseif ($arItem["PROPERTIES"]["SHOW_SKIDKA_BTN"]["VALUE"]):?>
                                    <br>
                                    <br>

                                    <p>
                                        <?if(!$arItem["POFFER_ORDER_REQUEST"]):?>
                                        <a href="#" class="button3"
                                           data-get-poffer
                                           data-poffer-id = "<?=$arItem["ID"]?>"
                                           data-contractor-name = "<?=$arResult["USER_DATA"]["NAME_SHORT"]?>"
                                           data-contractor-phone = "<?=$arResult["USER_DATA"]["PHONE"]?>"
                                           data-contractor-email = "<?=$arResult["USER_DATA"]["EMAIL"]?>"
                                        >Оставить заявку</a>
                                        <?else:?>
                                            Ваша заявка прията, с вами свяжутся!
                                        <?endif;?>
                                    </p>
                                <?endif;?>

                            <?endif;?>

                            <?/*if($arResult["IS_AUTHORIZED"]):?>
                                <?if($arItem["PROPERTIES"]["LINK"]["VALUE"] && $arItem["PROPERTIES"]["BUTTON"]["VALUE"]):?>
                                    <br>
                                    <br>
                                    <p>
                                        <a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="button3" target="_blank">
                                            <?=$arItem["PROPERTIES"]["BUTTON"]["VALUE"]?>
                                        </a>
                                    </p>
                                <?endif;?>
                            <?else:?>
                                <div class="poffer_notice">
                                    Чтобы воспользоватся предложением вы должны быть зарегистрированным пользователем. <a href="/auth/">Войти</a>
                                </div>
                            <?endif;*/?>
                        </div>


                    <?endforeach;?>
                <?else:?>
                    <p>Не найдены предложения по выбранным параметрам</p>
                <?endif;?>

<?if(!$arResult["IS_AJAX"]):?>
                </div>

                <div class="partners-services__action" id="js-more-button">
                    <a class="btn btn--border-red js-poffers-more"><span>Показать еще</span></a>
                </div>
            </div>
            <div class="partners-services__rside">

                <?
                $APPLICATION->IncludeComponent('electric:poffers.filter', '', [
                    "CACHE_TYPE" => "N",//$arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "SEF_FOLDER" => $arParams["SEF_FOLDER"],
                ],$component);
                ?>
                <div class="partners-services__rside-box">
                    <div class="partners-services__rside-holder">
                        <div class="partners-services__rside-btn btn btn--white-icon btn--white-icon--xs">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/logo-e.svg" alt="">
                            <span>Сертифицирован</span>
                        </div>
                        <div class="main__text">Для авторизованных
							электриков:
                        </div>
                        <ul class="main__list">
                            <li class="main__text main__text--sm">каталог предложений от парнеров для зарегистрированных исполнителей</li>
                            <li class="main__text main__text--sm">выгода до 30% от обычной стоимости услуг</li>
                            <li class="main__text main__text--sm">все необходимые услуги в одном месте</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>

<?if($arResult["IS_LAST_PAGE"]):?>
    <script>
        document.getElementById('js-more-button').style.display = "none";
        document.getElementById('js-more-button').disabled = true;
    </script>
<?else:?>
    <script>
        document.getElementById('js-more-button').style.display = "flex";
        document.getElementById('js-more-button').disabled = false;
    </script>
<?endif;?>




