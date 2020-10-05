<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="education-list__rside js-educations-filter edu-right-sticky">
    <button class="btn-filter">Фильтр</button>
    <div class="education-list__drop ">
        <form action="" data-educations-filter>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Дата обучения</div>
                <div class="education-list__rside-fieldset">
                    <div class="education-list__rside-field education-list__rside-field--md">
                        <div class="education-list__rside-drop">
                            <select name="month"  class="js-select" >
                                <option value="">Месяц</option>
                                <?foreach ($arResult["FILTER_DATA"]["MONTHS"] as $index => $month):?>
                                    <option value="<?=$index+1?>"><?=$month?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="education-list__rside-field education-list__rside-field--sm">
                        <div class="education-list__rside-drop">
                            <select name="year"  class="js-select" >
                                <option value="">Год</option>
                                <?foreach ($arResult["FILTER_DATA"]["YEARS"] as $year):?>
                                    <option value="<?=$year?>"><?=$year?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Город</div>
                <div class="education-list__rside-drop">
                    <select name="city" class="js-select">
                        <option value="">Выберите город</option>
                        <?foreach ($arResult["FILTER_DATA"]["CITIES"] as $arCity):?>
                            <option value="<?=$arCity["ID"]?>"><?=$arCity["NAME"]?></option>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Тип обучения</div>
                <div class="education-list__rside-items">
                    <input type="hidden" name="type">
                    <?foreach ($arResult["FILTER_DATA"]["TYPES"] as $id => $type):?>
                        <div class="education-list__rside-item">
                            <button class="btn btn--filter education-filter js-filter-type " data-type="<?=$id?>"><?=$type?></button>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Тема</div>
                <div class="education-list__rside-items">
                    <input type="hidden" name="theme">
                    <?foreach ($arResult["FILTER_DATA"]["THEMES"] as $id => $theme):?>
                        <div class="education-list__rside-item">
                            <button class="btn btn--filter education-filter js-filter-theme" data-theme="<?=$id?>"><?=$theme?></button>
                        </div>
                    <?endforeach;?>


                </div>
            </div>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Автор программы</div>
                <div class="education-list__rside-drop">
                    <select name="author" class="js-select">
                        <option value="">Выбрать вендора</option>
                        <?foreach ($arResult["FILTER_DATA"]["VENDORS"] as $key=>$vendor):?>
                            <option value="<?=$key?>"><?=$vendor?></option>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
            <div class="education-list__rside-box">
                <div class="education-list__rside-title main__text">Формат обучения</div>
                <div class="education-list__rside-items">
                    <input type="hidden" name="format">
                    <?foreach ($arResult["FILTER_DATA"]["FORMATS"] as $id => $format):?>
                        <div class="education-list__rside-item">
                            <button type="button" class="btn btn--filter education-filter js-filter-format" data-format="<?=$id?>"><?=$format?></button>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </form>
    </div>
</div>


