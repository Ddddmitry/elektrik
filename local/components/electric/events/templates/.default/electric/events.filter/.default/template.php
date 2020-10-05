<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="events-page__rside js-events-filter events__filter-sticky">
    <button class="btn-filter">Фильтр</button>
    <div class="events-page__drop">
        <form action="" data-events-filter>
        <div class="events-page__rside-box">
            <div class="events-page__rside-title main__text">Дата обучения</div>
            <div class="events-page__rside-fieldset">
                <div class="events-page__rside-field events-page__rside-field--md">
                    <div class="events-page__rside-drop">
                        <select name="month" id="js-events-filter__month" class="js-select" >
                            <option value="">Месяц</option>
                            <?foreach ($arResult["FILTER_DATA"]["MONTHS"] as $index => $month):?>
                                <option value="<?=$index+1?>"><?=$month?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="events-page__rside-field events-page__rside-field--sm">
                    <div class="events-page__rside-drop">
                        <select name="year" id="js-events-filter__year" class="js-select" >
                            <option value="">Год</option>
                            <?foreach ($arResult["FILTER_DATA"]["YEARS"] as $year):?>
                                <option value="<?=$year?>"><?=$year?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="events-page__rside-box">
            <div class="events-page__rside-title main__text">Город</div>
            <div class="events-page__rside-drop">
                <select name="city" id="js-events-filter__city" class="js-select">
                    <option value="">Выберите город</option>
                    <?foreach ($arResult["FILTER_DATA"]["CITIES"] as $arCity):?>
                        <option value="<?=$arCity["ID"]?>"><?=$arCity["NAME"]?></option>
                    <?endforeach;?>
                </select>
            </div>
        </div>
        <div class="events-page__rside-box">
            <div class="events-page__rside-title main__text">Тип мероприятия</div>
            <div class="events-page__rside-items">
                <input type="hidden" name="type">
                <?foreach ($arResult["FILTER_DATA"]["TYPES"] as $id => $type):?>
                    <div class="events-page__rside-item">
                        <button class="btn btn--filter events-filter js-filter-type " data-type="<?=$id?>"><?=$type?></button>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </form>
    </div>
</div>