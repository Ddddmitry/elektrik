<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetPageProperty('layout', 'pages/articles-detail');

$APPLICATION->IncludeComponent('electric:articles.detail', '', [
    "CODE" => $arResult['VARIABLES']['CODE'],
    'CACHE_TYPE' => $arParams["CACHE_TYPE"],
    'CACHE_TIME' => $arParams["CACHE_TIME"],
    'SEF_FOLDER' => $arParams["SEF_FOLDER"],
    'SEF_URL_TEMPLATES' => $arParams["SEF_URL_TEMPLATES"],
]);
