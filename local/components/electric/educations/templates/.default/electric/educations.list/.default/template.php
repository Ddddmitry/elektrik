<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?if(!$arResult["IS_AJAX"]):?>
            <div class="education-list__lside js-filter-container">
                <div class="education-list__inners js-educations-list-container" data-page="1">

<?endif;?>
                    <?if($arResult["ITEMS"]):?>
                    <?foreach ($arResult["ITEMS"] as $arItem):?>

                            <div class="education-list__inner">
                                <div class="education-list__top">
                                    <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>
                                    <?if($arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_NAME"]):?>
                                        <div class="btn btn--gray-logo">
                                            <img src="<?=$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_PREVIEW_PICTURE"]["SRC"]?>"
                                                 alt="<?=$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_NAME"]?>">
                                        </div>
                                    <?endif;?>
                                </div>
                                <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="education-list__title main__text main__text--md"><?=$arItem["NAME"]?></a>
                                <div class="education-list__description main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                <div class="education-list__info">
                                    <div class="education-list__info-image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                    <div class="education-list__info-rside">
                                        <ul>
                                            <?if(!$arItem["PROPERTIES"]["HIDE_DATE"]["VALUE"]):?>
                                            <li>
                                                <div class="main__text main__text--xs">Дата начала:</div>
                                                <div class="main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                            </li>
                                            <?endif;?>
                                            <?if($arItem["PROPERTIES"]["DURING"]["VALUE"]):?>
                                                <li>
                                                    <div class="main__text main__text--xs">Продолжительность:</div>
                                                    <div class="main__text main__text--xs light"><?=$arItem["PROPERTIES"]["DURING"]["VALUE"]?></div>
                                                </li>
                                            <?endif;?>
                                            <?if($arItem["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                                                <li>
                                                    <div class="main__text main__text--xs">Адрес проведения:</div>
                                                    <div class="main__text main__text--xs light"><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                                                </li>
                                            <?endif;?>
                                        </ul>
                                        <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="btn btn--red">
                                            <?if($arItem["PROPERTIES"]["INCLUDE_COURSE"]["VALUE_XML_ID"] == "Y"):?>
                                                Пройти курс
                                            <?else:?>
                                                Записаться
                                            <?endif;?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        <?endforeach;?>
                    <?else:?>
                        <p>Не найдены программы обучения по выбранным параметрам</p>
                    <?endif;?>

<?if(!$arResult["IS_AJAX"]):?>
                </div>

                <div class="education-list__action" id="js-more-button">
                    <button type="button" class="btn btn--border-red js-educations-more"><span>Показать еще</span></button>
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




