<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<li class="js-notify-block">
    <a href="#" class="link-notification <?if($arResult["NOTIFICATIONS"]["NEW"]):?>new<?endif;?>" data-notify-bell>
        <span class="link-notification-icon"></span>
        <?if($arResult["NOTIFICATIONS"]["NEW"]):?>
            <span class="link-notification-number new" data-notification-number><?=count($arResult["NOTIFICATIONS"]["NEW"])?></span>
        <?endif;?>
    </a>
    <div class="header-person-menu header-person-menu--notifications" data-notifies>
        <div class="header-person-menu-inner">
            <?if($arResult["NOTIFICATIONS"]["NEW"] || $arResult["NOTIFICATIONS"]["NEW_VIEWS"]):?>
                <div class="header-person-menu__type header-person-menu__type_new">
                    <div class="header-person-menu__type-title">
                        <span>новые</span>
                    </div>
                    <?foreach ($arResult["NOTIFICATIONS"]["NEW"] as $arNotify):?>
                        <div class="header-person-menu__single header-person-menu__single_notification">
                            <a href="<?=$arNotify["LINK"]?>"><?=$arNotify["TEXT"]?></a>
                        </div>
                    <?endforeach;?>

                    <?/*if($arResult["NOTIFICATIONS"]["NEW_VIEWS"]):?>
                        <div class="header-person-menu__single header-person-menu__single_notification">
                            <a href="#">
                                <?=$arResult["NOTIFICATIONS"]["NEW_VIEWS"]?>
                            </a>
                        </div>
                    <?endif;*/?>

                </div>
            <?endif;?>

            <?if($arResult["NOTIFICATIONS"]["VIEWED"]):?>
                <div class="header-person-menu__type">
                    <div class="header-person-menu__type-title">
                        <span>просмотренные</span>
                    </div>
                    <?foreach ($arResult["NOTIFICATIONS"]["VIEWED"] as $arNotify):?>
                        <div class="header-person-menu__single header-person-menu__single_notification">
                            <a href="<?=$arNotify["LINK"]?>"><?=$arNotify["TEXT"]?></a>
                        </div>
                    <?endforeach;?>
                </div>
            <?endif;?>

        </div>
    </div>
</li>
