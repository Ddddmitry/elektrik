<?
use Electric\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Бекофис');

global $USER;
if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}else{
    $obUser = new User;
    $isClient = $obUser->isClient();
    if(!$isClient)
        LocalRedirect('/auth/');
}
?>

<div class="cabinet__content">
    <div class="cabinet__holder">
        <?$APPLICATION->IncludeComponent("bitrix:menu","back_client",Array(
                "ROOT_MENU_TYPE" => "client",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "client",
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
            <?$APPLICATION->IncludeComponent('electric:backoffice.clients', '', [
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "36000",
                "SEF_FOLDER" => "/backoffice/clients/",
                "SEF_MODE" => "Y",
                "SEF_URL_TEMPLATES" => [
                    'index' => '/',
                    'articles' => 'articles/',
                    'statistic' => 'statistic/',
                    'orders' => 'orders/',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
<?if($_REQUEST["soc-reg"]="Y"){?>
    <script>
        setCookie('social-register', "Y");
    </script>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
