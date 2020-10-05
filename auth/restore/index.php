<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Установите свой пароль');
?>

<?
$APPLICATION->IncludeComponent(
    "electric:restore",
    "",
    array(),
    false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
