

<div id="city_change" class="fancyBlock">
    <div class="fancybox-title">Выбор города</div>

    <form action="#" id="search_city" class="fullForm" data-form-search-city>
        <div class="js-form-error"></div>

        <div class="formGroup">
            <div class="formGroup-inner">
                <span class="svg-icon findIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-search"></use></svg></span>                <label class="search__label">
                    <span class="svg-icon findIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-search"></use></svg></span>                    Поиск города
                </label>
                <input type="hidden" name="search_city-id" id="search_city-id" class="js-popupSearchResult-inputHidden">
                <input value="" type="search" autocomplete="off"
                       name="search_city-search" id="search_city-search"
                       data-min-length="3" data-padding-right="16.5px" placeholder="Поиск города" class="js-popupSearchResult-input">
                <div class="popupSearchResult js-popupSearchResult"></div>
            </div>
        </div>

        <div class="impotantTowns js-impotantTowns">
            <? foreach ($arResult["PREFERRED_CITIES"] as $item) {?>
                <div class="impotantTowns__single">
                    <a href="#" class="impotantTowns__single-link js-impotantTowns__single-link" data-id="<?=$item["ID"]?>" data-path="<?=$item["PATH"]?>">
                    <?=$item["NAME"]?>
                    </a>
                </div>
            <?}?>

        </div>

        <div class="formGroup formGroup__bottom">
            <div class="formGroup-inner">
                <button type="submit" class="city-change-submit" name="city-change-submit" id="city-change-submit" disabled="disabled">
                    Подтвердить выбор
                </button>
                <div class="formGroup__action" data-fancybox-close="">
                    Отменить
                </div>
            </div>
        </div>

    </form>

</div>
