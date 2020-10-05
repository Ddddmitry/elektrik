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
                <h1 class="main__text main__text--mb main__text--md">Наши преимущества</h1>
                <div class="about__inners">
                    <div class="about__inner">
                        <div class="main__text main__text--md">Честная конкуренция —
                            основа работы портала
                        </div>
                        <div class="main__text main__text--sm">Конкуренция — эффективный способ регулирования цен на услуги.
                            После размещения заказа, мы быстро оповестим исполнителей из указанного региона о вашем предложении.
                            Заинтересованные подрядчики откликнутся на заявку, и вам останется только выбрать подходящего по
                            цене или рейтингу мастера.
                            В среднем каждое задание получает от 3 предложений
                        </div>
                    </div>
                    <div class="about__inner">
                        <div class="main__text main__text--md">Поиск исполнителя больше
                            не отнимет много времени
                        </div>
                        <div class="main__text main__text--sm">На сайте вы найдете десятки предложений от частных мастеров и
                            профильных компаний. Все они разделены по категориям: проектирование, монтаж, ремонт, а также
                            комплексные электротехнические работы — найти исполнителей для проекта «под ключ» стало намного
                            легче!
                        </div>
                    </div>
                </div>
                <img src="<?=SITE_TEMPLATE_PATH?>/images/img-steps-lg-2.png" class="about__advantages" alt="">
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
