<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Услуги');

$APPLICATION->IncludeComponent("electric:services", "", [
    "POPULAR" => false,
    "TITLE" => "Услуги"
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
