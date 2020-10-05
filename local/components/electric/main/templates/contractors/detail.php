<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetPageProperty('layout', 'pages/marketplace-detail');

$APPLICATION->IncludeComponent("electric:contractors.filter", "",[
    "AJAX" => false,
    "SEF_FOLDER" => $arParams['SEF_FOLDER'],
    "SHOW_FILTER" => false,
    "CODE" => "CONTRACTOR",
]);
$APPLICATION->IncludeComponent('electric:contractors.detail', '', [
    "SEF_FOLDER" => $arParams['SEF_FOLDER'],
    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
    "CACHE_TIME" => $arParams['CACHE_TIME'],
    "CODE" => $arResult['VARIABLES']['ELEMENT_CODE'],
]);
