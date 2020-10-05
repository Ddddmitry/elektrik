<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Статьи");
$APPLICATION->SetTitle("Статьи");

$APPLICATION->IncludeComponent(
    "electric:main",
    "articles",
    array(
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/articles/",
        "SEF_URL_TEMPLATES" => array(
            "list" => "index.php",
            "detail" => "#CODE#/",
        ),
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000",
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
