<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Electric\Request;

use \Bitrix\Main\Loader;
?>

<?
Loader::includeModule("iblock");


echo "<pre>";

$phone = "79158878008";
$UID_DIST = "7f975a56c761db6506eca0b37ce6ec87";
$phone_new = md5($phone.$UID_DIST);
$rest = new Request("","","https://electrograd.pro/local/interface/electricru/");
$arData = [
    "action" => "user.check",
    "phone" => $phone_new,
];
var_dump($arData);
$rest->execute($arData);
$arResult = $rest->getResult();
$status = $rest->getStatusCode();
var_dump($arResult);
var_dump($status);
echo "</pre>";

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>




