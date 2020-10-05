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
<div class="events-page__content">
    <h1 class="main__headline">
        <span>Мероприятия</span>
    </h1>
    <div class="events-page__holder">

<?endif;?>
<?
$APPLICATION->IncludeComponent('electric:events.list', '', [
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "SEF_FOLDER" => $arParams["SEF_FOLDER"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "TYPE_IBLOCK_ID" => $arParams["TYPE_IBLOCK_ID"],
    "PAGE_SIZE" => $arParams["PAGE_SIZE"]
],$component);
?>
<?if(!$arResult["IS_AJAX"]):?>
    <?
    $APPLICATION->IncludeComponent('electric:events.filter', '', [
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
    ],$component);
    ?>
    </div>
</div>

    <?
    $APPLICATION->IncludeComponent('electric:events.list', 'old_events', [
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "TYPE_IBLOCK_ID" => $arParams["TYPE_IBLOCK_ID"],
        "PAGE_SIZE" => 8,
        "FILTER" => ["ACTIVE" => "N"]
    ],$component);
    ?>






<?endif;?>