<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



$APPLICATION->IncludeComponent('electric:contractors.filter', '', [
    "AJAX" => true,
    "SEF_FOLDER" => PATH_MARKETPLACE,
    "SHOW_FILTER" => true,
    "PRESS_SUBMIT" => false,
    "CODE" => "MARKETPLACE",
    "SHOW_HINTS" => true
]);

$APPLICATION->IncludeComponent('electric:contractors.list', '', [
    'PAGE_SIZE' => 2,//CONTRACTORS_PAGE_SIZE,
]);

