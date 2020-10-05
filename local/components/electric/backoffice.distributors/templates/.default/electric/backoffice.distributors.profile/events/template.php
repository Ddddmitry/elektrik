<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__top">
    <div class="main__text main__text--lg">Мероприятия <?=$arResult["PROFILE"]["NAME"]?></div>
    <a href="add/" class="btn btn--red">Добавить мероприятие</a>
</div>
<?if($arResult["PROFILE"]["EVENTS"]):?>
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
<div class="cabinet__events">
    <?if($arResult["PROFILE"]["EVENTS"]):?>
        <? foreach ($arResult["PROFILE"]["EVENTS"] as $arEvent) {?>
            <div class="cabinet__event js-cabinet__event js-all <?if($arEvent["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>js-is-archive cabinet__event_is_archive<?else:?>js-is-active<?endif;?>" data-event-<?=$arEvent["ID"]?> >
                <div class="cabinet__event-photo" style="background-image: url('<?=$arEvent["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                <div class="cabinet__event-description">
                    <div class="cabinet__event-top">
                        <div class="btn btn--gray btn--gray--xs"><?=$arEvent["PROPERTY_TYPE_NAME"]?></div>
                        <div class="text-archival">Архивное</div>
                    </div>
                    <div class="cabinet__event-title main__text main__text--md"><?=$arEvent["NAME"]?></div>
                    <div class="cabinet__event-text main__text main__text--sm"><?=$arEvent["PREVIEW_TEXT"]?></div>
                    <ul class="cabinet__event-items">
                        <?if($arEvent["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                            <li class="cabinet__event-item">
                                <div class="cabinet__event-item-title main__text main__text--xs">Адрес:</div>
                                <div class="cabinet__event-item-description main__text main__text--xs light"><?=$arEvent["PROPERTIES"]["ADDRESS"]["VALUE"]?>
                                </div>
                            </li>
                        <?endif;?>

                        <li class="cabinet__event-item">
                            <div class="cabinet__event-item-title main__text main__text--xs">Дата:</div>
                            <div class="cabinet__event-item-description main__text main__text--xs light"><?=$arEvent["DATE"]?></div>
                        </li>
                    </ul>
                    <div class="cabinet__event-actions">
                        <div class="cabinet__event-action">
                            <a href="<?=$arResult["SEF_FOLDER"]?>events/edit/<?=$arEvent["ID"]?>/" class="main__text main__text--sm link-red">Редактировать</a>
                        </div>
                        <div class="cabinet__event-action js-cabinet__event-action">
                            <?if($arEvent["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="N" data-event-id="<?=$arEvent["ID"]?>">Вернуть из архива</a>
                            <?else:?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="Y" data-event-id="<?=$arEvent["ID"]?>">Архивировать</a>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?}?>
    <?else:?>
        <p>У вас еще нет никаких мероприятий</p>
    <?endif;?>
</div>

