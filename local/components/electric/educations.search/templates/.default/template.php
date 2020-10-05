<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>




<section class="education-hero" style="background-image: url('<?=$arResult["BANNER"][0]["SRC"]?>');">
    <div class="container">
        <div class="education-hero__content">
            <h1 class="education-hero__title main__text main__text--lg white"><?=$arResult["BANNER"][0]["TITLE"]?></h1>
            <form action="#" class="education-hero__form" id="search_articles" data-educations-search>
                <div class="education-hero__form-items formGroup formGroup_search required">
                    <div class="education-hero__form-item education-hero__form-item--field formGroup-inner" >
                        <label class="search__label">
                            <span class="svg-icon findIcon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-search"></use></svg></span>
                            Поиск курса или программы
                        </label>
                        <input
                                type="search"
                                name="search"
                                id="search_educations-search"
                                data-min-length="3"
                                autocomplete="off"
                                style="padding-left: 54px!important;padding-right: 54px!important;"
                                placeholder="Поиск курса или программы"
                                class="field-input field-input--search"
                                value="<?=$arResult["SEARCH_DATA"]["SEARCH_PHRASE"]?>"
                        >
                        <span class="svg-icon js-cleanIcon closeIcon" style="right: 15px;"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-close"></use></svg></span>
                    </div>
                    <div class="education-hero__form-item education-hero__form-item--action">
                        <button class="btn btn--red" type="submit" id="search_educations-submit" name="submit" disabled>Поиск</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>