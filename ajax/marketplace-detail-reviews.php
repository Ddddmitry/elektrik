<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$userID = $request->getQuery("user");

$APPLICATION->IncludeComponent(
    "electric:contractors.reviews",
    "",
    array(
        "USER" => $userID,
        "PAGE_SIZE" => REVIEWS_PAGE_SIZE,
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => 3600,
    ),
    false
);
