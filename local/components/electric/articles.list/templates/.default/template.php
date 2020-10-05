<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
<div class="articlesPage-main">

    <div class="articlesPage-list js-articles-list" data-page="1">
<?endif;?>
        <?if(!$arResult["IS_AJAX"] && $arResult["SEARCH"]["IS_SEARCH"]):?>
            <div class="articlesPage-count">Найдено <?=$arResult["SEARCH"]["COUNT"]?> по запросу: «<?=$arResult["SEARCH"]["PHRASE"]?>»</div>
        <?endif;?>
        <?if($arResult["IS_AJAX"] && !$arResult["ITEMS"]):?>
            <div class="articlesPage-count">Не найдены статьи по выбранным параметрам</div>
        <?endif;?>
        <? foreach ($arResult["ITEMS"] as $index=>$arItem) {?>

            <?/*if(!$arResult["IS_AJAX"] && $index == 1):?>
                <div class="articlesPage-list__banner">
                    <?$APPLICATION->IncludeComponent('electric:advertising.banner', '', ["TYPE"=> "ARTICLES","CACHE_TYPE"=> "N","NOINDEX"=> "Y","CACHE_TIME"=> "3600"]);?>
                </div>
            <?endif;*/?>

            <div class="articlesPage-list__single" >
                <div class="articlesPage-list__single-inner">

                    <div class="articlesPage-list__navigation">
                        <?if($arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]):?>
                            <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>"
                                class="articlesPage-list__type button2">
                                <?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?>
                            </a>
                        <?endif;?>
                        <?if($arItem["PROPERTIES"]["TAGS"]):?>
                            <div class="articlesPage-list-themes">
                                <? foreach ($arItem["PROPERTIES"]["TAGS"]["VALUE"] as $tag) {?>
                                    <div class="articlesPage-list-themes__single">
                                        <?=$tag?>
                                    </div>
                                <?}?>
                            </div>
                        <?endif;?>

                    </div>

                    <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="articlesPage-list__title">
                        <?=$arItem["NAME"]?>
                    </a>
                    <?if($arItem["PREVIEW_TEXT"]):?>
                        <div class="articlesPage-list__description">
                        <?=$arItem["PREVIEW_TEXT"]?>
                    </div>
                    <?endif;?>
                    <?if($arItem["PREVIEW_PICTURE"]):?>
                        <div class="articlesPage-list__img">
                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"
                        class="articlesPage-list__img-tag">
                    </div>
                    <?endif;?>
                    <div class="articlesPage-list__info">
                        <div class="articlesPage-list__author">
                            <?=$arItem["PROPERTIES"]["USER"]["VALUE_NAME"]?>
                        </div>
                        <div class="articlesPage-list__date">
                            <?=$arItem["DATE"]?>
                        </div>
                    </div>
                </div>
            </div>
        <?}?>



<?if(!$arResult["IS_AJAX"]):?>
    </div>
    <?if(!$arResult["IS_LAST_PAGE"]):?>

        <div class="articlesPage__button" id="js-more-button">
            <a href="#" class="btn btn--border-red js-articles-more">
                <span>Показать ещё</span>
            </a>
        </div>
    <?endif;?>
</div>
<?endif;?>


<?if($arResult["IS_AJAX"]):?>
    <?if($arResult["IS_LAST_PAGE"]):?>

            <script>
                document.getElementById('js-more-button').style.display = "none";
            </script>

    <?else:?>

            <script>
                document.getElementById('js-more-button').style.display = "block";
            </script>

    <?endif;?>
<?endif;?>
