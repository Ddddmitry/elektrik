<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section class="all-services">
    <div class="container">
        <div class="all-services__content">
            <h1 class="main__headline main__headline--center main__headline--red"><?=$arParams["TITLE"]?></h1>
            <div class="all-services__lists">
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                    <div class="all-services__list">
                        <div class="all-services__title main__text main__text--md medium"><?=$arItem["NAME"]?></div>
                        <ul class="all-services__items">
                            <?foreach ($arItem["ITEMS"] as $arElement):?>
                                <li class="all-services__item">
                                    <a href="<?=$arElement["LINK"]?>" class="all-services__item-link main__text link"><?=$arElement["NAME"]?></a>
                                    <div class="all-services__item-info">
                                        <?if((int)$arElement["PROPERTY_COUNT_CONTRACTORS_VALUE"] > 0):?>
                                            <div class="main__text main__text--xs gray">
                                                Исполнителей: <span><?=(int)$arElement["PROPERTY_COUNT_CONTRACTORS_VALUE"]?></span>
                                            </div>
                                        <?endif;?>
                                        <?if((int)$arElement["PROPERTY_COUNT_ORDERS_VALUE"] > 0):?>
                                            <div class="main__text main__text--xs gray">
                                                Заказов: <span><?=(int)$arElement["PROPERTY_COUNT_ORDERS_VALUE"]?></span>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </li>
                            <?endforeach;?>
                        </ul>
                    </div>
                <?endforeach;?>

            </div>
            <?if($arResult["SHOW_ALL_BUTTON"]):?>
                <div class="all-services__action">
                    <a href="/services/" class="btn btn--border-red"><span>Все услуги</span></a>
                </div>
            <?endif;?>
        </div>
    </div>
</section>
