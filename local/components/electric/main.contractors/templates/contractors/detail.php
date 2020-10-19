<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


/*$APPLICATION->IncludeComponent("electric:contractors.filter", "",[
    "AJAX" => false,
    "SEF_FOLDER" => $arParams['SEF_FOLDER'],
    "SHOW_FILTER" => false,
    "CODE" => "CONTRACTOR",
]);*/
$APPLICATION->IncludeComponent('electric:contractors.detail', '', [
    "SEF_FOLDER" => $arParams['SEF_FOLDER'],
    "CACHE_TYPE" => "N",//$arParams['CACHE_TYPE'],
    "CACHE_TIME" => $arParams['CACHE_TIME'],
    "ID" => $arResult['VARIABLES']['ELEMENT_ID'],
    "CODE" => $arResult['VARIABLES']['ELEMENT_CODE'],
]);
