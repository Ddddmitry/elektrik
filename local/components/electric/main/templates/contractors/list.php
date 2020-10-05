<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$APPLICATION->IncludeComponent('electric:contractors.filter', '', [
    "AJAX" => true,
    "SEF_FOLDER" => PATH_MARKETPLACE,
    "SHOW_FILTER" => true,
    "CODE" => "MARKETPLACE",
]);
$APPLICATION->IncludeComponent('electric:contractors.list', '', [
    'PAGE_SIZE' => CONTRACTORS_PAGE_SIZE,
]);

