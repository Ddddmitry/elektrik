<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Партнёрские предложения');
?>
<?
$APPLICATION->IncludeComponent('electric:poffers.list', '', [
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/poffers/",
    "SEF_MODE" => "Y",
    "IBLOCK_ID" => IBLOCK_PARTNERS_OFFERS,
    "TYPE_IBLOCK_ID" => IBLOCK_PARTNERS_OFFERS_TYPES,
    "PAGE_SIZE" => "4"
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
