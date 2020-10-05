<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
<div class="news_list__content">
    <div class="news_list__holder">
        <div class="news_list__lside js-filter-container">
            <h1 class="main__headline">
                <span>Новости</span>
            </h1>
            <div class="news_list__inners js-news-list-container" data-page="1" data-action="<?=$arResult["SEF_FOLDER"]?>">
                <?endif;?>
                <?if($arResult["ITEMS"]):?>
                    <?foreach ($arResult["ITEMS"] as $arItem):?>
                        <div class="news_list__inner">
                            <div class="news_list__inner-container">

                                <div class="news_list__inner-holder">
                                    <div class="news_list__date main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                    <div class="news_list__inner-title main__text main__text--md">
                                        <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="news_list__link"><?=$arItem["NAME"]?></a>
                                    </div>
                                    <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                </div>
                            </div>
                        </div>


                    <?endforeach;?>
                <?else:?>
                    <p>Не найдены новости</p>
                <?endif;?>

                <?if(!$arResult["IS_AJAX"]):?>
            </div>

            <div class="news_list__action" id="js-more-button">
                <a class="btn btn--border-red js-news-more"><span>Показать еще</span></a>
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




