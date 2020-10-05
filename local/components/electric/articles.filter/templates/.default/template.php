<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>


<div class="articlesPage-helper-filters js-showhide articles__filter-sticky">
    <div class="articlesPage-helper__showFilters">
        <a href="#" class="articlesPage-helper__showFilters-button js-showhide-toggler inhLink">
            Фильтр
        </a>
    </div>
    <div class="articlesPage-helper-filters-inner js-showhide-content" data-articles-filter data-filter-params="">
        <a href="#" class="articlesPage-helper__showFilters-clean js-articles-filter-clear js-articles-filter-clear-block">
            Очистить все <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-cross"></use></svg></span>
        </a>
        <div class="articlesPage-helper-category articlesPage-helper__block">
            <div class="articlesPage-helper__title">
                Тип статьи
            </div>
            <div class="articlesPage-helper-category-list">
                <? foreach ($arResult["FILTER_DATA"]["TYPES"] as $typeId =>$typeName) {?>
                    <div class="articlesPage-helper-category-list__single js-articles-filter-type" data-type="<?=$typeId?>">
                        <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$typeId?>" class="button2 articlesPage-helper-category-list__single-button ">
                            <?=$typeName?>
                            <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-cross"></use></svg></span>
                        </a>
                    </div>
                <?}?>
            </div>
        </div>
        <div class="articlesPage__separator"></div>

        <?/*<div class="articlesPage-helper-themes articlesPage-helper__block">
            <div class="articlesPage-helper__title">
                Темы
            </div>
            <div class="articlesPage-helper-themes-list">
                <? foreach ($arResult["FILTER_DATA"]["TAGS"] as $TAG) {?>
                    <?if($TAG):?>
                    <div class="articlesPage-helper-themes-list__single js-articles-filter-tag" data-tag="<?=$TAG?>">
                        <a href="#" data-code="<?=$TAG?>" class="inhLink articlesPage-helper-themes-list__single-button">
                            <?=$TAG?>
                            <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-cross"></use></svg></span>
                        </a>
                    </div>
                    <?endif;?>
                <?}?>

            </div>
        </div>


        <div class="articlesPage-helper-themes articlesPage-helper-themes--clear articlesPage-helper__block js-articles-filter-clear-block">
            <div class="articlesPage__separator"></div>
            <div class="articlesPage-helper-themes-list">
                <div class="articlesPage-helper-themes-list__single active">
                    <a href="#" class="inhLink articlesPage-helper-themes-list__single-button js-articles-filter-clear">
                        Очистить всё
                        <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-cross"></use></svg></span>
                    </a>
                </div>
            </div>
        </div>*/?>

    </div>
</div>
