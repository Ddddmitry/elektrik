<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Авторизация');
$APPLICATION->AddHeadString('<metaname="googlebot" content="noindex">',true);
?>

<?
$APPLICATION->IncludeComponent(
    "electric:auth",
    "",
    array(),
    false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
