<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="cabinet__top">
    <div class="main__text main__text--lg">Обучение <?=$arResult["PROFILE"]["NAME"]?></div>
    <a href="add/" class="btn btn--red">Добавить курс</a>
</div>
<?if($arResult["PROFILE"]["EDUCATIONS"]):?>
<div class="cabinet__sorting">
    <div class="btn-filter">Фильтр заказов</div>
    <div class="cabinet__sorting-drop">
        <div class="cabinet__sorting-items">
            <div class="cabinet__sorting-item is-active js-cabinet__sorting-item" data-show="js-all">
                <div class="main__text main__text--sm" >Все</div>
            </div>
            <div class="cabinet__sorting-item js-cabinet__sorting-item" data-show="js-is-active">
                <div class="main__text main__text--sm" data-is-active>Активные</div>
            </div>
            <div class="cabinet__sorting-item js-cabinet__sorting-item" data-show="js-is-archive">
                <div class="main__text main__text--sm" data-is-archive>Архивные</div>
            </div>
        </div>
    </div>
</div>
<?endif;?>
<div class="education-list__inners education-list__inners--mt">
    <?if($arResult["PROFILE"]["EDUCATIONS"]):?>
    <? foreach ($arResult["PROFILE"]["EDUCATIONS"] as $arEducation) {?>
        <div class="education-list__inner js-cabinet__education js-all <?if($arEducation["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>js-is-archive cabinet__education_is_archive<?else:?>js-is-active<?endif;?>" data-education-<?=$arEducation["ID"]?>>
            <div class="education-list__top">
                <div class="btn btn--gray"><?=$arEducation["PROPERTY_TYPE_NAME"]?></div>
                <div class="text-archival">Архивное</div>
            </div>
            <div class="education-list__title main__text main__text--md"><?=$arEducation["NAME"]?></div>
            <div class="education-list__description main__text main__text--sm"><?=$arEducation["PREVIEW_TEXT"]?></div>
            <div class="education-list__info">
                <div class="education-list__info-image" style="background-image: url('<?=$arEducation["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                <div class="education-list__info-rside">
                    <ul>
                        <li>
                            <div class="main__text main__text--xs">Дата начала:</div>
                            <div class="main__text main__text--xs light"><?=$arEducation["DATE"]?></div>
                        </li>
                        <li>
                            <div class="main__text main__text--xs">Продолжительность:</div>
                            <div class="main__text main__text--xs light"><?=$arEducation["PROPERTIES"]["DURING"]["VALUE"]?></div>
                        </li>
                        <li>
                            <div class="main__text main__text--xs">Адрес проведения:</div>
                            <div class="main__text main__text--xs light"><?=$arEducation["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                        </li>
                    </ul>
                    <div class="education-list__links">
                        <div class="education-list__link">
                            <a href="<?=$arResult["SEF_FOLDER"]?>educations/edit/<?=$arEducation["ID"]?>/" class="main__text main__text--sm link-red">Редактировать</a>
                        </div>
                        <div class="education-list__link js-cabinet__education-action">
                            <?if($arEducation["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="N" data-education-id="<?=$arEducation["ID"]?>">Вернуть из архива</a>
                            <?else:?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="Y" data-education-id="<?=$arEducation["ID"]?>">Архивировать</a>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?}?>
    <?else:?>
        <p>У вас еще нет никаких курсов</p>
    <?endif;?>

</div>



