<?php
global $is404;
global $IS_MAIN;
global $IS_MARKETPLACE;
global $IS_AUTH;
$IS_MAIN = $APPLICATION->GetCurPage(false) === '/';
$IS_MARKETPLACE = $APPLICATION->GetCurPage(false) === '/marketplace/';
$IS_AUTH = $APPLICATION->GetCurPage(false) === '/auth/';
$is404 = (defined("ERROR_404") && ERROR_404 === "Y");