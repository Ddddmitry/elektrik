<div class="about__container">
    <h1 class="main__text main__text--md main__text--mb">Нужна помощь?</h1>
    <div class="main__text main__text--sm">Свяжитесь с нами по телефону службы поддержки или оставьте
        сообщение в форме обратной связи
    </div>
    <a href="tel:<?=PHONE_TEL?>" onclick="ym(56492371, 'reachGoal', 'click-phone'); return true;" class="about__phone main__text main__text--lg"><?=PHONE?></a>
    <div class="main__text main__text--xs light">звонок бесплатный</div>
    <div class="about__box">
        <?$APPLICATION->IncludeComponent(
            "electric:form.result.new",
            "need_help",
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
                "WEB_FORM_ID" => 3
            )
        );?>

    </div>

</div>