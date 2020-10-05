<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Новости');
?>
<?
$APPLICATION->IncludeComponent('electric:news', '', [
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/news/",
    "SEF_MODE" => "Y",
    "SEF_URL_TEMPLATES" => [
        'list' => '/news/',
        'element' => '#ELEMENT_CODE#/'
    ],
    "IBLOCK_ID" => IBLOCK_BACKOFFICE_NEWS,
    "PAGE_SIZE" => "9"
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
