<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Сервисы');
?>
<?
$APPLICATION->IncludeComponent('electric:service.list', '', [
    "CACHE_TYPE" => "N",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/service/",
    "IBLOCK_ID" => IBLOCK_SERVICE,
    "TYPE_IBLOCK_ID" => IBLOCK_SERVICE_TYPES,
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
