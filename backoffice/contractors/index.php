<?
use Electric\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Бекофис');

global $USER;
if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}else{
    $obUser = new User;
    $isContractor = $obUser->isContractor();
    if(!$isContractor)
        LocalRedirect('/auth/');
}
?>

<div class="cabinet__content">
    <div class="cabinet__holder">
        <?$APPLICATION->IncludeComponent("bitrix:menu","back_contractor",Array(
                "ROOT_MENU_TYPE" => "contractor",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "contractor",
                "USE_EXT" => "N",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "36000",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => "",
            )
        );?>
        <div class="cabinet__container">
            <?$APPLICATION->IncludeComponent('electric:backoffice.contractors', '', [
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "36000",
                "SEF_FOLDER" => "/backoffice/contractors/",
                "SEF_MODE" => "Y",
                "SEF_URL_TEMPLATES" => [
                    'index' => '/',
                    'articles' => 'articles/',
                    'articles.add' => 'articles/add/',
                    'statistic' => 'statistic/',
                    'orders' => 'orders/',
                    'sales' => 'sales/',
                    'reviews' => 'reviews/',
                    'points' => 'points/',
                    'help' => 'help/',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
