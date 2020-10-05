<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог специалистов");

$APPLICATION->IncludeComponent(
    "electric:main",
    "contractors",
    array(
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => PATH_MARKETPLACE,
        "SEF_URL_TEMPLATES" => array(
            "list" => "index.php",
            "detail" => "#ELEMENT_CODE#/",
        ),
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000",
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
