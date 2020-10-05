<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["MENU_MOB"]):?>
<div class="sidebar__inner sidebar__inner--mob">
    <div class="sidebar__actions">
        <?if(!$arResult["IS_AUTHORIZED"]):?>
            <? foreach ($arResult["MENU_MOB"] as $index=>$arItem) {?>
                <div class="sidebar__action <?if($index == 0):?>sidebar__action--lg<?else:?>sidebar__action--auto<?endif;?>">
                    <a href="<?=$arItem["link"]?>" class="<?if($index == 0):?>btn btn--menu<?else:?>main__link<?endif;?>"><span><?=$arItem["name"]?></span></a>
                </div>
            <?}?>
        <?else:?>
            <div class="sidebar__action sidebar__action--auto">
                <a href="/backoffice/" class="main__link"><span><?=$arResult["USER"]["NAME"]?></span></a>
            </div>
            <div class="sidebar__action sidebar__action--auto">
                <a href="/logout/" class="main__link"><span>Выйти</span></a>
            </div>
        <?endif;?>
    </div>
</div>
<?endif;?>
<?if($arResult["MENU_FIRST"]):?>
    <div class="sidebar__inner sidebar__inner--brd">
        <ul>
        <? foreach ($arResult["MENU_FIRST"] as $arItem) {?>
            <li>
                <a href="<?=$arItem["link"]?>" class="main__text sidebar-link"><?=$arItem["name"]?></a>
            </li>
        <?}?>
        </ul>
    </div>
<?endif;?>
<?if($arResult["MENU_SECOND"]):?>
    <div class="sidebar__inner">
        <ul>
            <? foreach ($arResult["MENU_SECOND"] as $arItem) {?>
                <li>
                    <a href="<?=$arItem["link"]?>" class="main__text sidebar-link"><?=$arItem["name"]?></a>
                </li>
            <?}?>
        </ul>
    </div>
<?endif;?>
