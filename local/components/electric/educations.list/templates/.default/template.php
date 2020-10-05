<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
    <div class="events-page__content">
        <div class="events-page__holder">
            <div class="events-page__lside">
                <div class="main__headline">
                    <span>Мероприятия</span>
                </div>
                <div class="events-page__inners js-events-list-container">
<?endif;?>

                        <?foreach ($arResult["ITEMS"] as $arItem):?>
                            <div class="events-page__inner">
                                <div class="events-page__container">
                                    <div class="events-page__image" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                    <div class="events-page__description">
                                        <div class="events-page__top">
                                            <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTIES"]["TYPE"]["VALUE"]?>" class="btn btn--gray"><?=$arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></a>
                                            <div class="events-page__date main__text main__text--xs light"><?=$arItem["DATE"]?></div>
                                        </div>
                                        <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>" class="events-page__name link-red main__text main__text--md"><?=$arItem["NAME"]?></a>
                                        <div class="main__text main__text--sm"><?=$arItem["PREVIEW_TEXT"]?></div>
                                        <div class="events-page__location"><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>

<?if(!$arResult["IS_AJAX"]):?>
                </div>
                <div class="events-page__action">
                    <a
                        href="<?=$arResult["CURRENT_PAGE"]?>"
                        class="btn btn--border-red js-show-more"
                        data-total-pages="<?=$arResult["TOTAL_PAGES_COUNT"]?>"
                        data-target=".js-events-list-container"
                    ><span>Показать еще</span></a>
                </div>
            </div>
            <div class="events-page__rside">
                <div class="events-page__rside-box">
                    <div class="events-page__rside-title main__text">Дата обучения</div>
                    <div class="events-page__rside-fieldset">
                        <div class="events-page__rside-field events-page__rside-field--md">
                            <div class="events-page__rside-drop">
                                <select name="" id="">
                                    <option value="">Месяц</option>
                                    <option value="">Месяц 2</option>
                                    <option value="">Месяц 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="events-page__rside-field events-page__rside-field--sm">
                            <div class="events-page__rside-drop">
                                <select name="" id="">
                                    <option value="">Год</option>
                                    <option value="">1990</option>
                                    <option value="">2000</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="events-page__rside-box">
                    <div class="events-page__rside-title main__text">Город</div>
                    <div class="events-page__rside-drop">
                        <select name="" id="">
                            <option value="">Выберите город</option>
                            <option value="">Выберите город 2</option>
                            <option value="">Выберите город 3</option>
                        </select>
                    </div>
                </div>
                <div class="events-page__rside-box">
                    <div class="events-page__rside-title main__text">Тип мероприятия</div>
                    <div class="events-page__rside-items">
                        <div class="events-page__rside-item">
                            <button class="btn btn--filter events-filter">Курс</button>
                        </div>
                        <div class="events-page__rside-item">
                            <button class="btn btn--filter events-filter">Мастер-класс</button>
                        </div>
                        <div class="events-page__rside-item">
                            <button class="btn btn--filter events-filter">Университет</button>
                        </div>
                        <div class="events-page__rside-item">
                            <button class="btn btn--filter events-filter">Презентация</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>
