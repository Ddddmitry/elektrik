<?
use Electric\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Бекофис');

global $USER;
if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}else{
    $obUser = new User;
    $isDist = $obUser->isDistributor();
    if(!$isDist)
        LocalRedirect('/auth/');
}
?>

<div class="cabinet__content">
    <div class="cabinet__holder">
        <?$APPLICATION->IncludeComponent("bitrix:menu","back_dist",Array(
                "ROOT_MENU_TYPE" => "dist",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "dist",
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
            <?$APPLICATION->IncludeComponent('electric:backoffice.distributors', '', [
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000",
                "SEF_FOLDER" => "/backoffice/distributors/",
                "SEF_MODE" => "Y",
                "SEF_URL_TEMPLATES" => [
                    'index' => '/',
                    'events' => 'events/',
                    'events.add' => 'events/add/',
                    'events.edit' => 'events/edit/#ELEMENT_ID#/',
                    'educations' => 'educations/',
                    'educations.add' => 'educations/add/',
                    'educations.edit' => 'educations/edit/#ELEMENT_ID#/',
                    'articles' => 'articles/',
                    'articles.add' => 'articles/add/',
                    'statistic' => 'statistic/',
                    'sales' => 'sales/',
                    'sales.add' => 'sales/add/',
                    'sales.edit' => 'sales/edit/#ELEMENT_ID#/',
                    'help' => 'help/',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
