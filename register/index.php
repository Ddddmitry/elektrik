<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Регистрация');
$APPLICATION->AddHeadString('<metaname="googlebot" content="noindex">',true);
?>

<?
$APPLICATION->IncludeComponent(
    "electric:register",
    "phone",
    array(),
    false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
