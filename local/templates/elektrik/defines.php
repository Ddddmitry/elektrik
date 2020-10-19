<?
global $is404;
global $USER;
global $IS_MAIN;
global $IS_MARKETPLACE;
global $IS_ARTICLES;
global $IS_EDUCATION;
global $IS_AUTH;
global $IS_REG;

$IS_MAIN = $APPLICATION->GetCurPage(false) === '/';
$IS_MARKETPLACE = $APPLICATION->GetCurPage(false) === PATH_MARKETPLACE;
$IS_ARTICLES = $APPLICATION->GetCurPage(false) === '/articles/';
$IS_EDUCATION = $APPLICATION->GetCurPage(false) === '/education/';
$IS_AUTH = CSite::InDir('/auth/');
$IS_REG = $APPLICATION->GetCurPage(false) === '/register/';
$is404 = (defined("ERROR_404") && ERROR_404 === "Y");
?>