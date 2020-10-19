<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
?>
<?if(!$IS_AUTH && !$IS_REG):?>
<?if(!$IS_MAIN && !$IS_MARKETPLACE && !$IS_ARTICLES && !$IS_EDUCATION):?>
        </div>
    </section>
<?endif;?>

<footer class="footer">
    <div class="footer__top">
        <div class="container">
            <div class="footer__content">
                <div class="footer__inners">
                    <div class="footer__inner">
                        <a href="/" class="footer__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg" alt="Электрик.ру">
                        </a>
                        <a href="tel:<?=PHONE_TEL?>" class="main__text footer-phone" onclick="ym(56492371, 'reachGoal', 'click-phone'); return true;"><?=PHONE?></a>
                        <div class="main__text main__text--xs gray-light">звонок бесплатный</div>
                        <a href="mailto:support@elektrik.ru" class="footer__email footer-email main__text" onclick="ym(56492371,'reachGoal','click-email');">support@elektrik.ru</a>
                        <ul class="footer__social">
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-vk.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-instagram.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-ok.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-fb.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-yb.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-tl.svg');"></a>
                            </li>
                            <li>
                                <a href="#" style="background-image: url('<?=SITE_TEMPLATE_PATH?>/images/icon-wa.svg');"></a>
                            </li>
                        </ul>
                    </div>
                    <?$APPLICATION->IncludeComponent("bitrix:menu","footer__inner",Array(
                            "ROOT_MENU_TYPE" => "bottom",
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "top",
                            "USE_EXT" => "N",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => "",
                            "TITLE" => "Электрик.ру"
                        )
                    );?>

                    <?$APPLICATION->IncludeComponent("bitrix:menu","footer__inner",Array(
                            "ROOT_MENU_TYPE" => "bottom2",
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "top",
                            "USE_EXT" => "N",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => "",
                            "TITLE" => "Сервисы"
                        )
                    );?>

                    <?$APPLICATION->IncludeComponent("bitrix:menu","footer__inner",Array(
                            "ROOT_MENU_TYPE" => "bottom3",
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "top",
                            "USE_EXT" => "N",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_CACHE_GET_VARS" => "",
                            "TITLE" => "О проекте"
                        )
                    );?>

                </div>
            </div>
        </div>
        <div class="js-scroll scroll-up">
            <a href="#top"></a>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="footer__content">
                <a href="/terms/" class="main__text main__text--xs white">Политика конфиденциальности</a>
                <div class="main__text main__text--xs white"><?=date("Y")?> Электрик.ру</div>
            </div>
        </div>
    </div>
</footer>

    </main>

<?$APPLICATION->IncludeComponent('electric:form.change.city', '', []);?>
<?else:?><? // Страница авторизации ?>
            </div>
        </div>
    </div>
</div>
<?endif;?>
<?
checkSEO();
if ($GLOBALS["SEO"]) {
    if (empty($GLOBALS["SEO"]["TITLE"]) === false) {
        $APPLICATION->SetPageProperty("title", $GLOBALS["SEO"]["TITLE"]);
    }
    if (empty($GLOBALS["SEO"]["DESCRIPTION"]) === false) {
        $APPLICATION->SetPageProperty("description", $GLOBALS["SEO"]["DESCRIPTION"]);
    }
    if (empty($GLOBALS["SEO"]["KEYWORDS"]) === false) {
        $APPLICATION->SetPageProperty("keywords", $GLOBALS["SEO"]["KEYWORDS"]);
    }
}
?>




<?$APPLICATION->IncludeFile(SITE_DIR."include/footer_scripts.php",Array(),Array("MODE"=>"html"));?>

</body>

</html>