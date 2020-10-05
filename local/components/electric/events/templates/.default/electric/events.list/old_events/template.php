<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["ITEMS"]):?>
    <section class="events">
        <div class="events__content">
            <div class="main__headline main__headline--start">
                Прошедшие мероприятия
            </div>
            <div class="events__inners">


                    <?foreach ($arResult["ITEMS"] as $arItem):?>
                        <div class="events__inner">
                            <div class="events__container">
                                <div class="events__image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                <div class="events__description">
                                    <div class="events__top">
                                        <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>
                                        <div class="events__date main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                    </div>
                                    <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="events__name link-red main__text main__text--md"><?=$arItem["NAME"]?></a>
                                    <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                </div>
                            </div>
                        </div>
                    <?endforeach;?>

            </div>

        </div>
    </section>
<?endif;?>