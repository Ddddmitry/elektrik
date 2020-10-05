<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="search search_">
    <div class="search-inner " style="height: 346px">
        <div class="search__back">
            <img src="<?=$arResult["BANNER"][0]["SRC"]?>" alt="Баннер3" class="search__back-img">

        </div>
        <h1 class="search__title">
            <?=$arResult["BANNER"]["TITLE"]?>
        </h1>
        <div class="search__action">
            <form action="#" class="search__form" id="search_articles" data-articles-search>
                <div class="formGroup formGroup_search required">
                    <div class="formGroup-inner">
                        <span class="svg-icon findIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-search"></use></svg></span>
                        <label class="search__label">
                            <span class="svg-icon findIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-search"></use></svg></span>
                            Поиск статьи
                        </label>
                        <input value="<?=$arResult["SEARCH_DATA"]["SEARCH_PHRASE"]?>" type="search"
                               name="search" id="search_articles-search"
                               data-min-length="3" data-padding-right="16.5px"
                               maxlength="255"
                               required
                               style="padding-right: 16.5px;" placeholder="Поиск статьи"
                        >
                        <span class="svg-icon js-cleanIcon closeIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-close"></use></svg></span>
                    </div>
                </div>
                <div class="formGroup formGroup_submit">
                    <div class="formGroup-inner">
                        <button type="submit" id="search_articles-submit" name="submit" disabled>
                            Поиск
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
