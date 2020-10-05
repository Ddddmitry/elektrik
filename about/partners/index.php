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
                <h1 class="main__text main__text--mb main__text--md">Партнеры портала</h1>
                <div class="main__text main__text--sm">Обоснованность технического решения, качество материалов и
                    квалификацию мастера проверить легко. Это объективные характеристики. С экономической составляющей дело
                    обстоит сложнее. Стоимость реализации проекта — ненормируемая величина. Нет свода правил, инструкций,
                    стандартов и иных нормативов, которые регламентируют ценообразование. Цена услуги устанавливается
                    исполнителем исключительно по собственному усмотрению, в соответствии с личными пожеланиями. В таких
                    условиях конкуренция — тот сдерживающий фактор, который заставляет исполнителей регулировать
                    ценообразование.
                </div>
                <div class="about__box">
                    <?$APPLICATION->IncludeComponent("electric:element.list", "about__partners", [
                        "IBLOCK_ID" => IBLOCK_PARTNERS,
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000",
                    ]);?>

                </div>
            </div>

            <div class="about__action">
                <a href="/about/connect/" class="btn btn--red">Стать партнером портала</a>
            </div>

        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
