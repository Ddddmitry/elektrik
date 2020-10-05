<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
            <div class="events-page__lside js-filter-container">
                <div class="events-page__inners js-events-list-container" data-page="1">
<?endif;?>
                    <?if($arResult["ITEMS"]):?>
                        <?foreach ($arResult["ITEMS"] as $arItem):?>
                            <div class="events-page__inner">
                                <div class="events-page__container">
                                    <div class="events-page__image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                    <div class="events-page__description">
                                        <div class="events-page__top">
                                            <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>

                                            <?if($arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_NAME"]):?>
                                                <div class="btn btn--gray-logo" style="margin-left: 10px;">
                                                    <img src="<?=$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_PREVIEW_PICTURE"]["SRC"]?>"
                                                         alt="<?=$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_NAME"]?>">
                                                </div>
                                            <?endif;?>
                                            <div class="events-page__date main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                            <?if($arItem["PROPERTIES"]["LINK"]["VALUE"]):?>
                                            <div class="events-page__inner-link">
                                                <a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" class="btn btn--red-small" target="_blank"><?= $arResult["PROPERTIES"]["BUTTON"]["VALUE"] ?? "Ссылка на вебинар" ?></a>
                                            </div>
                                            <?endif;?>
                                        </div>
                                        <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="events-page__name link-red main__text main__text--md"><?=$arItem["NAME"]?></a>
                                        <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                        <?if($arItem["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                                        <div class="events-page__location"><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                    <?else:?>
                        <p>Не найдены мероприятия по выбранным параметрам</p>
                    <?endif;?>

<?if(!$arResult["IS_AJAX"]):?>
                </div>

                <div class="events-page__action" id="js-more-button">
                    <a class="btn btn--border-red js-events-more"><span>Показать еще</span></a>
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
        document.getElementById('js-more-button').style.display = "block";
        document.getElementById('js-more-button').disabled = false;
    </script>
<?endif;?>




