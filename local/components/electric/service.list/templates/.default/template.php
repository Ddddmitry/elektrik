<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
<div class="articlesPage articlesPage_detail">
    <div class="articlesPage-inner ">
        <div class="articlesPage-main articlesPage-main--content">

            <div class="articlesPage-helper-category-list" data-service-filter filter-params="">

    <div class="articlesPage-helper-category-list__single">
        <a href="" class="button2 articlesPage-helper-category-list__single-button js-service-filter-type" data-type="">
            Все
        </a>
    </div>
    <? foreach ($arResult["TYPES"] as $index=>$type) {?>
        <div class="articlesPage-helper-category-list__single">
            <a href="?type=<?=$index?>" class="button2 articlesPage-helper-category-list__single-button js-service-filter-type" data-type="<?=$index?>">
                <?=$type?>
            </a>
        </div>
    <?}?>

</div>

<div class="articles-list articles-list_serv js-service-list">

<?endif;?>
    <?foreach ($arResult["ITEMS"] as $arItem):?>
        <div class="articles-list__single">
            <a href="#arend_site_<?=$arItem["ID"]?>"
               data-fancybox=""
               data-src="#arend_site_<?=$arItem["ID"]?>"
               class="articles-list__single-inner"
               <?if($arItem["PREVIEW_PICTURE"]):?>
                style="background-image: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);"
               <?endif;?>
            >
            </a>
            <div class="articles-list__single-text">
                <div class="articles-list__single-category">
                    <?if($arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]):?>
                        <span class="button2">
                            <?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?>
                        </span>
                    <?endif;?>
                </div>
                <a href="#arend_site_<?=$arItem["ID"]?>"
                   data-fancybox=""
                   data-src="#arend_site_<?=$arItem["ID"]?>"
                   class="articles-list__single-title inhLink">
                    <?=$arItem["NAME"]?>
                </a>
            </div>
            <div id="arend_site_<?=$arItem["ID"]?>" class="fancyBlock">
                <div class="fancybox-title"><?=$arItem["NAME"]?></div>
                <?if($arItem["DETAIL_PICTURE"]):?>
                    <p>
                        <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="">
                    </p>
                <?endif;?>
                <?=$arItem["DETAIL_TEXT"]?>
                <?if($arItem["PROPERTIES"]["LINK"]["VALUE"] && $arItem["PROPERTIES"]["BUTTON"]["VALUE"]):?>
                    <br>
                    <p>
                        <a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="button3" target="_blank">
                        <?=$arItem["PROPERTIES"]["BUTTON"]["VALUE"]?>
                        </a>
                    </p>
                <?endif;?>
            </div>
        </div>
    <?endforeach;?>


<?if(!$arResult["IS_AJAX"]):?>
</div>
</div>
</div>
</div>
<?endif;?>

