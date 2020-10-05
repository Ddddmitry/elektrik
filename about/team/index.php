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
                <h1 class="main__text main__text--md">Создатели портала</h1>
                <div class="main__text main__text--sm">Портал <a href="/" class="main__text main__text--sm link-red-xs">elektrik.ru</a>
                    принадлежит РАЭК (Российская ассоциация
                    электротехнических
                    компаний) — первому в России союзу независимых электротехнических компаний-дистрибьюторов, занимающих
                    лидирующие позиции, каждый в своем регионе.
                </div>
                <div class="about__box">
                    <div class="main__text main__text--md">Цели и задачи РАЭК</div>
                    <ul class="main__list">
                        <li>
                            <div class="main__text main__text--sm">развитие электротехнической отрасли России;</div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">внедрение стандарта ETIM в качестве единого стандарта для
                                рынка электротехники;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">улучшение стандартов качества и безопасности
                                электротехнической продукции;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">формирование культуры продвижения и потребления современного
                                электрооборудования;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">противодействие недобросовестной конкуренции и монополизации
                                рынка;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">усиление взаимодействия между поставщиками и
                                дистрибьюторами;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">появление российского голоса на мировом рынке электротехники
                                и участие в формировании международных стандартов;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">обмен опытом в реализации бизнес процессов, внедрения новых
                                проектов, технологий и ноу-хау;
                            </div>
                        </li>
                        <li>
                            <div class="main__text main__text--sm">получение новых возможностей в организации обучения
                                сотрудников.
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="about__box">
                    <div class="main__text main__text--md">Члены ассоциации РАЭК</div>
                    <div class="about__description-inline">
                        <div class="main__text main__text--sm">В ассоциацию входят 16 крупнейших дистрибьюторов электротехнической
                            продукции, занимающих лидирующие позиции в своем регионе.
                        </div>
                        <a href="#" class="btn btn--red">Присоединиться к РАЭК</a>
                    </div>
                    <div class="about__logos">
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l1.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l2.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l3.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l4.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l5.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l6.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l7.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l8.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l9.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l10.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l11.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l12.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l13.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l14.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l15.png" alt="">
                        </div>
                        <div class="about__logo">
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/l16.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
