<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__sales">
    <?if($arResult["SALES"]):?>
        <?foreach ($arResult["SALES"] as $arSale) {?>
            <div class="cabinet__sale">
                <div class="cabinet__sale-photo" style="background-image: url('<?=$arSale["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                <div class="cabinet__sale-description">
                    <div class="main__text main__text--md">

                        <a href="#arend_site_<?=$arSale["ID"]?>"
                           data-fancybox=""
                           data-src="#arend_site_<?=$arSale["ID"]?>"><?=$arSale["NAME"]?></a>
                    </div>
                    <div class="main__text main__text--sm"><?=$arSale["PREVIEW_TEXT"]?></div>
                </div>
            </div>
            <div id="arend_site_<?=$arSale["ID"]?>" class="fancyBlock">
                <div class="fancybox-title"><?=$arSale["NAME"]?></div>
                <?if($arSale["PREVIEW_PICTURE"]):?>
                    <p>
                        <img src="<?=$arSale["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                    </p>
                <?endif;?>
                <p><?=$arSale["PREVIEW_TEXT"]?></p>
            </div>
        <?}?>
    <?else:?>
        <p>В данный момент акций от партнёров нет. Загляните позже</p>
    <?endif;?>
</div>
