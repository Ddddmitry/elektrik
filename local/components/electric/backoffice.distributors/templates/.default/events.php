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

<?
$APPLICATION->IncludeComponent('electric:backoffice.distributors.profile', 'events', [
    "SEF_FOLDER" => $arParams['SEF_FOLDER'],
    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
    "CACHE_TIME" => $arParams['CACHE_TIME']
],$component);
?>

