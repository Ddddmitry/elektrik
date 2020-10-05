<?php

$arLoadHandlers = array(
    "iblock",
    "highloadblock",
    "checkSeo",
    "user",
    "SignedSoapClient"
);

if (!empty($arLoadHandlers)) {
    foreach ($arLoadHandlers as $arLoadHandler) {
        if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/handlers/".$arLoadHandler.".php")) {
            require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/handlers/".$arLoadHandler.".php");
        }
    }
}
