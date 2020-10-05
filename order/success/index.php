<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Успешое добавление заявки');
?>

<?

$APPLICATION->IncludeComponent('electric:order.element', '', [
    "ELEMENT_ID" => $_REQUEST["ID"],
]);
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
