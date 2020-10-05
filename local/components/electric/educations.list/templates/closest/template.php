<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="education-list__headline main__headline main__headline--start">
    Ближайшее обучение
    <a href="<?=$arResult["SEF_FOLDER"]?>" class="btn btn--border-red">
        <span>Все обучения</span>
        <span class="gray"><?=$arResult["TOTAL_COUNT"]?></span>
    </a>
</div>
<div class="education-list__inners">
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
                <div class="education-list__title main__text main__text--md"><?=$arItem["NAME"]?></div>
                <div class="education-list__description main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                <div class="education-list__info">
                    <div class="education-list__info-image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                    <div class="education-list__info-rside">
                        <ul>
                            <li>
                                <div class="main__text main__text--xs">Дата начала:</div>
                                <div class="main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                            </li>
                            <li>
                                <div class="main__text main__text--xs">Продолжительность:</div>
                                <div class="main__text main__text--xs light"><?=$arItem["PROPERTIES"]["DURING"]["VALUE"]?></div>
                            </li>
                            <li>
                                <div class="main__text main__text--xs">Адрес проведения:</div>
                                <div class="main__text main__text--xs light"><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                            </li>
                        </ul>
                        <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="btn btn--red">Записаться</a>
                    </div>
                </div>
            </div>

        <?endforeach;?>
    <?else:?>
        <p>Не найдены программы обучения по выбранным параметрам</p>
    <?endif;?>
</div>
