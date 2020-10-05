<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="types">
    <div class="types-inner typicalBlock typicalBlock_two p40">
        <?if($arResult["TITLE"]):?>
            <h1 class="types__title main__headline main__headline--center main__headline--red">
                <?=$arResult["TITLE"]?>
            </h1>
        <?endif;?>
        <div class="types__list">
            <? foreach ($arResult["ITEMS"] as $arSection) {?>
                <div class="types__list-single">
                    <div class="types__list-title">
                        <?=$arSection["NAME"]?>
                    </div>
                    <div class="types__list-subTypes">
                        <? foreach ($arSection["ITEMS"] as $arItem) {?>
                        <div class="types__list-subTypes-single">
                            <a href="<?=$arItem["LINK"]?>">
                            <?=$arItem["NAME"]?>
                            </a>
                        </div>
                        <?}?>
                    </div>
                </div>
            <?}?>

        </div>
        <?if($arResult["SHOW_ALL_BUTTON"]):?>
            <div class="types__bottom">
                <a href="/services/" class="types__bottom-button button">
                    Все услуги
                    <span class="svg-icon {{icon.class}}"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-arrow-down"></use></svg></span>
                </a>
            </div>
        <?endif;?>
    </div>
</div>
