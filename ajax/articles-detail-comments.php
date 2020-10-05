<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$articleID = $request->getQuery("article");

$APPLICATION->IncludeComponent(
    "electric:articles.comments",
    "",
    array(
        "ARTICLE" => $articleID,
        "PAGE_SIZE" => ARTICLE_COMMENTS_PAGE_SIZE,
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => 3600,
    ),
    false
);
