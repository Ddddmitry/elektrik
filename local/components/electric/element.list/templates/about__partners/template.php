<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["ITEMS"]):?>
<div class="about__partners">
    <? foreach ($arResult["ITEMS"] as $arItem):?>
        <div class="about__partner">
            <div class="about__partner-logo">
                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
            </div>
            <div class="about__partner-description">
                <div class="main__text"><?=$arItem["NAME"]?></div>
                <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
            </div>
        </div>
    <?endforeach;?>
</div>
<?endif;?>
