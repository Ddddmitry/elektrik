<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'О проекте');
?>
    <div class="about__content">
    <div class="main__headline"><span>О проекте</span></div>
    <div class="about__holder">
        <div class="about__lside">
            <?$APPLICATION->IncludeComponent("bitrix:menu","simple",Array(
                    "ROOT_MENU_TYPE" => "about",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "top",
                    "USE_EXT" => "N",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => "",
                )
            );?>
        </div>
        <div class="about__rside">
            <?$APPLICATION->IncludeFile(SITE_DIR."include/help.php",Array(),Array("MODE"=>"html"));?>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
