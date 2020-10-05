<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["SUGGESTED_CITY_NAME"]):?>

    <div class="header-city-quest js-header-city__quest-content active typicalBlock typicalBlock_one">
        <div class="header-city-quest-inner">
            <div class="header-city-quest__title">
                Ваш город <?=$arResult["SUGGESTED_CITY_NAME"]?>?
            </div>
            <div class="header-city-quest__buttons">
                <a href="#" class="button button_md js-header-city__quest-toggler">
                    Да
                </a>
                <br class="header-city-quest__buttons-br">
                <a href="#city_change" data-fancybox data-src="#city_change" class="formGroup__action">
                    Нет, выбрать другой
                </a>
            </div>
        </div>
    </div>
<?endif;?>
