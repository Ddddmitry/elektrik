<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?if($arResult["ITEMS"]):?>
    <?$arFirst = array_shift($arResult["ITEMS"]);?>
    <section class="news">
        <div class="container">
            <div class="news__content">
                <div class="main__headline main__headline--start">Новости портала</div>
                <div class="news__inners">
                    <div class="news__inner">
                        <div class="main__text main__text--xs light"><?=$arFirst["DATE"]?></div>
                        <a href="<?=$arFirst["DETAIL_PAGE_LINK"]?>" class="main__text link"><?=$arFirst["NAME"]?></a>
                        <div class="news__text main__text main__text--sm gray"><?=$arFirst["PREVIEW_TEXT"]?></div>
                    </div>
                    <div class="news__inner">
                        <? foreach ($arResult["ITEMS"] as $arItem):?>
                            <div class="news__item">
                                <div class="main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="main__text link"><?=$arItem["NAME"]?></a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
                <div class="news__action">
                    <a href="<?=$arParams["SEF_FOLDER"]?>" class="btn btn--border-red">
                        <span>Все новости</span>
                        <span class="gray"><?=$arResult["TOTAL_COUNT"]?></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
<?endif;?>


