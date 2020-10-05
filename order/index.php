<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Новая заявка');
?>

<?
$APPLICATION->IncludeComponent('electric:order', '', [
    "IBLOCK_ID" => IBLOCK_ORDERS,
]);
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
