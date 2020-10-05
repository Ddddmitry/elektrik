<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Электрик.ру - Все виды электротехнических услуг");
$APPLICATION->SetPageProperty('title', 'Электрик.ру - Все виды электротехнических услуг');
?>

<?
$APPLICATION->IncludeComponent("electric:contractors.filter", "", [
    "AJAX" => false,
    "SEF_FOLDER" => PATH_MARKETPLACE,
    "SHOW_FILTER" => true,
    "SHOW_HINTS" => true,
    "PRESS_SUBMIT" => true,
    "CODE" => "MAIN",
]);
?>


<?
$APPLICATION->IncludeComponent("electric:services", "main-page-list", [
    "TITLE" => "Все виды электротехнических услуг",
    "POPULAR" => true,
]);
?>

<?$APPLICATION->IncludeComponent('electric:static.seo', '', []);?>

<?$APPLICATION->IncludeComponent('electric:articles.list', 'main-page-list', [
    'PAGE_SIZE' => 2,
    'CACHE_TYPE' => "N",
    'CACHE_TIME' => "36000",
    "SEF_FOLDER" => "/articles/",
    'FILTER' => ["!PROPERTY_SHOW_ON_MAIN" => false]
]);?>
<?$APPLICATION->IncludeComponent('electric:events.list', 'main-page-list', [
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/events/",
    "IBLOCK_ID" => IBLOCK_EVENTS,
    "TYPE_IBLOCK_ID" => IBLOCK_EVENTS_TYPES,
    "PAGE_SIZE" => "2"
]);?>
<?$APPLICATION->IncludeComponent('electric:news.list', 'main-page-list', [
    'PAGE_SIZE' => IBLOCK_BACKOFFICE_NEWS,
    'CACHE_TYPE' => "A",
    'CACHE_TIME' => "36000",
    "SEF_FOLDER" => "/news/",
    "PAGE_SIZE" => "4"
]);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
