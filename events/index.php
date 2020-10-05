<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Мероприятия');
?>
<?
$APPLICATION->IncludeComponent('electric:events', '', [
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/events/",
    "SEF_MODE" => "Y",
    "SEF_URL_TEMPLATES" => [
        'list' => '/events/',
        'element' => '#ELEMENT_CODE#/'
    ],
    "IBLOCK_ID" => IBLOCK_EVENTS,
    "TYPE_IBLOCK_ID" => IBLOCK_EVENTS_TYPES,
    "PAGE_SIZE" => "5"
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
