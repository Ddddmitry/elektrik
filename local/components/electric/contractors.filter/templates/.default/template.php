<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section class="hero new-request">
    <div class="hero__bg hero__bg--new-request" style="background-image: url('<?=$arResult["BANNER"][0]["SRC"]?>');"></div>
    <div class="hero__bg hero__bg--search" style="background-image: url('<?=$arResult["BANNER"][1]["SRC"]?>');"></div>
    <div class="container">
        <div class="hero__content">
            <div class="hero__title hero__title--new-request"><?=$arResult["BANNER"][0]["TITLE"]?> <a href="#city_change" data-fancybox="" data-src="#city_change">г. <?=$arResult["USER_CITY"]["NAME"]?></a></div>
            <div class="hero__title hero__title--search"><?=$arResult["BANNER"][1]["TITLE"]?>
                <a href="#city_change" data-fancybox="" data-src="#city_change">г. <?=$arResult["USER_CITY"]["NAME"]?></a>
            </div>
            <div class="hero__holder">
                <div class="hero__tabs">
                    <div class="hero__tab hero__tab--new-request is-active">Вызвать электрика</div>
                    <div class="hero__tab hero__tab--search">Найти самостоятельно</div>
                </div>
                <div class="hero__inners">
                    <div class="hero__inner hero__inner--new-request">
                        <form action="/order/" method="post">
                            <div class="hero__form">
                                <div class="hero__form-item hero__form-item--auto relative js-hero__form-item ">
                                    <input type="text" data-service-search name="q" id="contractors-order" placeholder="Напишите, что нужно сделать…" class="js-popupSearchResult-input field-input" autocomplete="off">
                                    <div class="popupSearchResult js-popupSearchResult"></div>
                                </div>
                                <div class="hero__form-item hero__form-item--action">
                                    <button class="btn btn--red">Вызвать электрика</button>
                                </div>
                            </div>
                        </form>
                        <?if($arResult["SHOW"]["HINTS"]):?>
                            <div class="hero__examples">
                            <div class="hero__examples-title main__text main__text--xs">
                                Например:
                            </div>
                            <div class="hero__examples-items">
                                <? foreach ($arResult["HINTS"] as $arHint) {?>
                                    <div class="hero__examples-item btn--filter js-substitution-button"
                                         data-input="contractors-order"
                                         data-value="<?=$arHint["value"]?>"><?=$arHint["value"]?></div>
                                <?}?>
                            </div>
                        </div>
                        <?endif;?>
                    </div>
                    <div class="hero__inner hero__inner--search  ">

                        <form action="<?if(!$arResult["AJAX_MODE"]):?> <?=$arResult["SEF_FOLDER"]?> <?else:?> /e/<?endif?>" data-filter-params="" data-sort-params="" data-contractors-filter="<?=($arResult["AJAX_MODE"])?"ajax":""?>">
                            <input type="hidden" name="sert">
                            <input type="hidden" name="free">
                            <div class="hero__form  formGroup_search search__form">
                                <div class="hero__form-item hero__form-item--auto formGroup ">
                                    <input type="text"
                                           placeholder="Услуга и специалист"
                                           name="contractors-search"
                                           id="contractors-service"
                                           class="js-popupSearchResult-input field-input"
                                           autocomplete="off"
                                           <?if($arResult["SELECTED_DATA"]["contractors-search"]):?> value="<?=$arResult["SELECTED_DATA"]["contractors-search"]?>" <?endif;?>
                                    >
                                    <input type="hidden" name="contractors-search-id" id="contractors-service-id" class="js-popupSearchResult-inputHidden">

                                    <div class="popupSearchResult js-popupSearchResult"></div>


                                </div>
                                <div class="hero__form-item hero__form-item--auto formGroup ">
                                    <input type="hidden" name="contractors-location-id" id="contractors-location-id" class="js-popupSearchResult-inputHidden"
                                        <?if($arResult["SELECTED_DATA"]["contractors-location-id"]):?> value="<?=$arResult["SELECTED_DATA"]["contractors-location-id"]?>" <?endif;?> >
                                    <input type="text"
                                           name="contractors-location"
                                           id="contractors-location"
                                           class="js-popupSearchResult-input field-input"
                                           autocomplete="off"
                                           placeholder="Метро, район или город"
                                           data-restricted="<?=$arResult["USER_CITY"]["ID"]?>">
                                    <div class="popupSearchResult js-popupSearchResult"></div>
                                </div>
                                <div class="hero__form-item hero__form-item--action">
                                    <button class="btn btn--red" type="submit" id="contractors-submit" name="contractors-submit">Поиск</button>
                                </div>
                            </div>
                            <?/*if($arResult["SHOW"]["HINTS"]):?>
                            <div class="hero__examples">
                                <div class="hero__examples-title main__text main__text--xs">
                                    Например:
                                </div>
                                <div class="hero__examples-items">
                                    <? foreach ($arResult["HINTS"] as $arHint) {?>
                                        <div class="hero__examples-item btn--filter js-substitution-button"
                                             data-input="contractors-service"
                                             data-value="<?=$arHint["value"]?>"><?=$arHint["value"]?></div>
                                    <?}?>
                                </div>
                            </div>
                            <?endif;*/?>
                        </form>
                        <?if($arResult["SHOW"]["FILTER"]):?>
                            <form action="#" class="" id="filter" data-contractors-filter-details>
                                <div class="hero__drops">

                                    <div class="hero__drop select-transparent">
                                        <select name="type" id="filter-service-person" data-placeholder="Исполнитель" class="js-chosen <?if($arResult["SHOW"]["PRESS_SUBMIT"]):?>js-only-submit<?endif;?>">
                                            <option value="">Тип исполнителя</option>
                                            <option value="individual" <?if($arResult["SELECTED_DATA"]["type"] == "individual"):?> selected <?endif;?> >Частное лицо</option>
                                            <option value="legal" <?if($arResult["SELECTED_DATA"]["type"] == "legal"):?> selected <?endif;?> >Компания</option>
                                        </select>
                                    </div>
                                    <div class="hero__drop select-transparent">
                                        <select name="skill" id="filter-qualification" data-placeholder="Квалификация" class="js-chosen <?if($arResult["SHOW"]["PRESS_SUBMIT"]):?>js-only-submit<?endif;?>">
                                            <option value="">Квалификация</option>
                                            <?foreach ($arResult["FILTER_DATA"]["SKILLS"] as $arSkill):?>
                                                <option value="<?=$arSkill["ID"]?>" <?if($arResult["SELECTED_DATA"]["skill"] == $arSkill["ID"]):?> selected <?endif;?> ><?=$arSkill["NAME"]?></option>
                                            <?endforeach;?>
                                        </select>
                                    </div>
                                    <div class="hero__drop select-transparent">
                                        <select name="room" id="filter-house" data-placeholder="Тип помещения" class="js-chosen <?if($arResult["SHOW"]["PRESS_SUBMIT"]):?>js-only-submit<?endif;?>">
                                            <option value="">Тип помещения</option>
                                            <?foreach ($arResult["FILTER_DATA"]["ROOMS"] as $arRoom):?>
                                                <option value="<?=$arRoom["ID"]?>" <?if($arResult["SELECTED_DATA"]["room"] == $arRoom["ID"]):?> selected <?endif;?> ><?=$arRoom["NAME"]?></option>
                                            <?endforeach;?>
                                        </select>
                                    </div>
                                    <button type="reset" class="button2  js-reset-form" style="display:none;">Сбросить</button>
                                </div>

                            </form>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



