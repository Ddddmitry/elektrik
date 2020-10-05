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

$elementId = $APPLICATION->IncludeComponent('electric:news.element', '', [
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "SEF_FOLDER" => $arParams["SEF_FOLDER"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "DETAIL_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["element"],
    "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
    "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
],$component);
?>



