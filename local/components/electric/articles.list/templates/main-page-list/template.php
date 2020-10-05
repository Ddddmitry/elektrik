<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["ITEMS"]):?>
<section class="last-news">
    <div class="container">
        <div class="last-news__content">
            <div class="main__headline main__headline--start">
                Последние статьи
                <a href="<?=$arParams["SEF_FOLDER"]?>" class="btn btn--border-red">
                    <span>Все статьи</span>
                    <span class="gray"><?=$arResult["TOTAL_COUNT"]?></span>
                </a>
            </div>

            <div class="last-news__inners">
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                    <div class="last-news__inner">
                        <div class="last-news__container">
                            <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="last-news__image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></a>
                            <div class="last-news__holder">
                                <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>
                                <div class="last-news__link">
                                    <div class="main__text main__text--md"><a href="<?=$arItem["DETAIL_PAGE_LINK"]?>"><?=$arItem["NAME"]?></a></div>
                                </div>
                                <div class="main__text main__text--sm gray"><?=$arItem["PREVIEW_TEXT"]?></div>
                                <div class="last-news__info">
                                    <div class="main__text main__text--xs"><?=$arItem["PROPERTIES"]["USER"]["VALUE_NAME"]?></div>
                                    <div class="main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>

        </div>
    </div>
</section>
<?endif;?>

