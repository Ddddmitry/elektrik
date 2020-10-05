<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Обучение');
$APPLICATION->SetTitle("Обучение");
?>
<?
$APPLICATION->IncludeComponent('electric:educations', '', [
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000",
    "SEF_FOLDER" => "/education/",
    "SEF_MODE" => "Y",
    "SEF_URL_TEMPLATES" => [
        'list' => '/education/',
        'element' => '#ELEMENT_CODE#/'
    ],
    "IBLOCK_ID" => IBLOCK_EDUCATIONS,
    "TYPE_IBLOCK_ID" => IBLOCK_EDUCATIONS_TYPES,
    "THEME_IBLOCK_ID" => IBLOCK_EDUCATIONS_THEMES,
    "PAGE_SIZE" => "5"
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
