<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?if($arResult["PROFILE"]["ORDERS"]):?>

    <div class="cabinet__sorting">
        <div class="cabinet__sorting-item is-active js-cabinet__sorting-item">
            <div class="main__text main__text--sm" data-sort="all">Все</div>
        </div>
        <? foreach ($arResult["PROFILE"]["STATUS"] as $arStatus) {?>
            <div class="cabinet__sorting-item js-cabinet__sorting-item">
                <div class="main__text main__text--sm" data-sort="<?=$arStatus["CODE"]?>"><?=$arStatus["NAME"]?></div>
            </div>
        <?}?>
    </div>

    <div class="cabinet__orders">
    <?foreach ($arResult["PROFILE"]["ORDERS"] as $arOrder):?>

        <?
            $addClass = "";
            if(in_array($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"],["canceled","canceled_complaint"]))
                $addClass .= "is-order-cancel";
        ?>
        <div class="cabinet__order <?=$addClass;?>" data-all data-<?=$arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]?>>
            <div class="cabinet__order-lside">
                <div class="cabinet__order-title main__text main__text--md"><?=$arOrder["NAME"]?></div>
                <div class="cabinet__order-description main__text main__text--sm">
                    <?=$arOrder["PREVIEW_TEXT"]?>
                    <?if($arOrder["PROPERTIES"]["MY_SPARES"]["VALUE"]):?>
                        <br>Есть свои запчасти
                    <?endif;?>
                    <?if($arOrder["PROPERTIES"]["MY_TOOLS"]["VALUE"]):?>
                        <br>Есть необходимый инструменты
                    <?endif;?>
                    <div class="main__text main__text--xs light"><?=$arOrder["PROPERTIES"]["ROOM"]["VALUE_ENUM"]?></div>
                    <div class="main__text main__text--sm">
                        <?if($arOrder["PROPERTIES"]["CITY"]["VALUE"]):?>
                            <?=$arOrder["PROPERTIES"]["CITY"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["RAYON"]["VALUE"]):?>
                            <?=$arOrder["PROPERTIES"]["RAYON"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["STREET"]["VALUE"]):?>
                            <?=$arOrder["PROPERTIES"]["STREET"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                            д. <?=$arOrder["PROPERTIES"]["HOUSE"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["KORP"]["VALUE"]):?>
                            корп. <?=$arOrder["PROPERTIES"]["KORP"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["FLAT"]["VALUE"]):?>
                            кв./офис <?=$arOrder["PROPERTIES"]["FLAT"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                            этаж <?=$arOrder["PROPERTIES"]["FLOOR"]["VALUE"]?>,
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                            домофон <?=$arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                        <?endif;?>
                    </div>
                    <div class="main__text main__text--xs light">Время выезда</div>
                    <div class="main__text main__text--sm"><?=$arOrder["PROPERTIES"]["DATE_TIME"]["DATE"]?> <?=$arOrder["PROPERTIES"]["DATE_TIME"]["TIME"]?></div>

                </div>
                <div class="cabinet__order-photos">
                    <?foreach ($arOrder["PROPERTIES"]["PHOTOS"]["VALUE"] as $arPhoto) {?>
                        <div class="cabinet__order-photo" >
                            <a href="<?=$arPhoto["FULL"]["SRC"]?>" data-fancybox="gallery-order-<?=$arOrder["ID"]?>" data-fancybox-img="">
                                <img src="<?= $arPhoto["PREVIEW"]["src"] ?>" alt="">
                            </a>
                        </div>
                    <?}?>
                </div>
            </div>
            <div class="cabinet__order-rside">
                <div class="cabinet__order-container">
                    <div class="cabinet__order-boxes">
                        <div class="cabinet__order-box">
                            <div class="main__text main__text--xs light">Заказ №<?=$arOrder["ID"]?></div>
                            <div class="main__text <?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"] == "new"):?>red<?endif;?>">
                                <?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"] == "canceled_complaint"):?><img src="<?=SITE_TEMPLATE_PATH?>/images/icon-flag.svg" alt=""><?endif;?>
                                <?=$arOrder["PROPERTIES"]["STATUS"]["VALUE_ENUM"]?>
                            </div>
                        </div>

                        <?switch ($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]){
                            case "new":
                                ?>
                                    <?/*<div class="cabinet__order-box">
                                        <div class="main__text main__text--xs light">Статистика</div>
                                        <div class="main__text">53 просмотра</div>
                                        <div class="main__text">3 отказа</div>
                                    </div>*/?>
                                    <div class="cabinet__order-box">
                                        <div class="main__text main__text--xs light">Размещен</div>
                                        <div class="main__text"><?=$arOrder["DATE"]?></div>
                                    </div>
                                <?
                                break;
                            case "inwork":
                                ?>
                                <div class="cabinet__order-box">
                                    <div class="main__text main__text--xs light">Исполнитель</div>
                                    <a href="<?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["NAME"]?></a>
                                </div>
                                <div class="cabinet__order-box">
                                    <div class="main__text main__text--xs light">Размещен</div>
                                    <div class="main__text"><?=$arOrder["DATE"]?></div>
                                </div>
                                <?
                                break;
                            case "completed":
                                ?>
                                    <div class="cabinet__order-box">
                                        <div class="main__text main__text--xs light">Исполнитель</div>
                                        <a href="<?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["NAME"]?></a>
                                    </div>
                                    <div class="cabinet__order-box">
                                        <div class="cabinet__order-top">
                                            <div class="cabinet__order-top-inner">
                                                <div class="main__text light">Ваша оценка</div>
                                            </div>
                                            <div class="cabinet__order-top-inner">
                                                <div class="cabinet__order-stars cabinet__order-stars--md">
                                                    <div class="cabinet__order-star">
                                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                    </div>
                                                    <div class="cabinet__order-star">
                                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                    </div>
                                                    <div class="cabinet__order-star">
                                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                    </div>
                                                    <div class="cabinet__order-star">
                                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                    </div>
                                                    <div class="cabinet__order-star">
                                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#" class="main__text main__text--sm link-dashed">Посмотреть отзыв</a>
                                    </div>
                                <?
                                break;
                            case "canceled":
                                ?>
                                <div class="cabinet__order-box">
                                    <div class="main__text main__text--xs light">Размещен</div>
                                    <div class="main__text"><?=$arOrder["DATE"]?></div>
                                </div>
                                <?
                                break;
                            case "canceled_complaint":
                                ?>
                                <div class="cabinet__order-box">
                                    <div class="main__text main__text--xs light">Исполнитель</div>
                                    <a href="<?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"]["NAME"]?></a>
                                </div>
                                <div class="cabinet__order-box">
                                    <div class="main__text main__text--xs light">Размещен</div>
                                    <div class="main__text"><?=$arOrder["DATE"]?></div>
                                </div>
                                <?
                                break;
                        }?>
                    </div>
                    <?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"] == "inwork"):?>
                        <a href="#" class="btn btn--gray-icon btn--gray-icon--xs">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-flag.svg" alt="">
                            <span>Сообщить о проблеме</span>
                        </a>
                    <?endif;?>
                    <?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"] == "new"):?>
                        <a href="#" class="btn btn--xs btn--gray-bg">Отменить заявку</a>
                    <?endif;?>


                </div>
            </div>
        </div>

    <?endforeach;?>
</div>
<?else:?>
<div class="cabinet__orders">
    <div class="not_found_text">
        <p>Вы ещё не разу не вызывали электрика!</p>
    </div>
</div>
<?endif;?>
