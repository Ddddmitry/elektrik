<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

?>
<?if(!$arResult["IS_AJAX"]):?>
<?$APPLICATION->IncludeComponent('electric:educations.search', '', ["CODE"=> "EDUCATION"]);?>
<section class="education-list">
    <div class="container">
        <div class="education-list__content">
            <div class="education-list__holder">
<?endif;?>
            <?
            $APPLICATION->IncludeComponent('electric:educations.list', '', [
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "SEF_FOLDER" => $arParams["SEF_FOLDER"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "TYPE_IBLOCK_ID" => $arParams["TYPE_IBLOCK_ID"],
                "THEME_IBLOCK_ID" => $arParams["THEME_IBLOCK_ID"],
                "PAGE_SIZE" => $arParams["PAGE_SIZE"]
            ],$component);
            ?>
<?if(!$arResult["IS_AJAX"]):?>
        <?
        $APPLICATION->IncludeComponent('electric:educations.filter', '', [
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "SEF_FOLDER" => $arParams["SEF_FOLDER"],
        ],$component);
        ?>

            </div>
        </div>
    </div>
</section>
<?endif;?>



