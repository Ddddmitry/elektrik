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
            <div class="about__container">
                <h1 class="main__text main__text--md main__text--mb">Регистрация для партнеров</h1>
                <div class="main__text main__text--sm">Если вы хотите воспользоваться возможностями портала для своих
                    целей, но не являетесь исполнителем или компанией, то оставьте заявку на этой странице и администрация
                    портала свяжется с вами
                </div>
                <div class="about__box">
                    <?$APPLICATION->IncludeComponent(
                        "electric:form.result.new",
                        "register_partner",
                        array(
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "N",
                            "CHAIN_ITEM_LINK" => "",
                            "CHAIN_ITEM_TEXT" => "",
                            "EDIT_URL" => "",
                            "IGNORE_CUSTOM_TEMPLATE" => "Y",
                            "LIST_URL" => "",
                            "SEF_MODE" => "N",
                            "SUCCESS_URL" => "",
                            "USE_EXTENDED_ERRORS" => "Y",
                            "VARIABLE_ALIASES" => [],
                            "WEB_FORM_ID" => 2
                        )
                    );?>
                </div>
            </div>


        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
