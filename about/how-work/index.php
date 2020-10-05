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
                <div class="about__header">
                    <h1 class="main__text main__text--md">Как работает портал</h1>
                    <img src="<?=SITE_TEMPLATE_PATH?>/images/logo-black-min.svg" class="about__electrick" alt="">
                    <div class="about__tabs">
                        <div class="about__tab main__text is-open" data-open="tab-customer">Для заказчика</div>
                        <div class="about__tab main__text" data-open="tab-employee">Для исполнителя</div>
                    </div>
                </div>
                <div class="about__tabs-contents">
                    <div class="about__tabs-content is-open tab-customer">
                        <div class="about__steps">
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">01</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-1.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <a href="#" class="main__text link-red main__text--md">Создаете <br>
                                    заявку
                                </a>
                                <div class="main__text main__text--sm">с кратким описанием и желаемой датой работ. Потратите не
                                    более 2
                                    минут
                                </div>
                            </div>
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">02</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-2.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <div class="main__text red main__text--md">Получаете <br>
                                    предложения
                                </div>
                                <div class="main__text main__text--sm">от специалистов портала по SMS или в личном кабинете</div>
                            </div>
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">03</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-3.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <div class="main__text red main__text--md">Выбираете <br>
                                    подходящее
                                </div>
                                <div class="main__text main__text--sm">на основе отзывов, стоимости, навыков специалиста и другим
                                    характеристикам
                                </div>
                            </div>
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">04</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-4.png" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <div class="main__text red main__text--md">Подтверждаете <br>
                                    заявку
                                </div>
                                <div class="main__text main__text--sm">а также все условия работ. После чего ждёте выезда
                                    специалиста в
                                    удобное время
                                </div>
                            </div>
                        </div>
                        <div class="about__tabs-bottom">
                            <div class="main__text main__text--md">Поиск исполнителя за 2 минуты!</div>
                            <div class="main__text">Обоснованность технического решения, качество материалов и квалификацию
                                мастера проверить легко. Это объективные характеристики. С экономической составляющей дело обстоит
                                сложнее. Стоимость реализации проекта — ненормируемая величина. Нет свода правил, инструкций,
                                стандартов и иных нормативов, которые регламентируют ценообразование. Цена услуги устанавливается
                                исполнителем исключительно по собственному усмотрению, в соответствии с личными пожеланиями. В
                                таких условиях конкуренция — тот сдерживающий фактор, который заставляет исполнителей регулировать
                                ценообразование. <br><br>
                                Портал Электрик.ру создает условия для честной конкурентной борьбы между исполнителями, чтобы
                                заказчики получили доступ к наиболее выгодным предложениям.
                            </div>
                        </div>
                    </div>
                    <div class="about__tabs-content tab-employee">
                        <div class="about__steps">
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">01</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-1-1.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <a href="#" class="main__text link-red main__text--md">Регистрируетесь <br>
                                    на портале
                                </a>
                                <div class="main__text main__text--sm">регистрация специалиста занимает не более 5 минут и
                                    открывает доступ к заказам
                                </div>
                            </div>
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">02</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-2-1.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <div class="main__text red main__text--md">Заполняете анкету <br>
                                    и перечень услуг
                                </div>
                                <div class="main__text main__text--sm">укажите вашу квалификацию, услуги и стоимость работ, чтобы
                                    пользователи могли вас найти
                                </div>
                            </div>
                            <div class="about__step">
                                <div class="about__step-container">
                                    <div class="about__step-holder">
                                        <div class="about__step-number">03</div>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/images/img-step-3-1.svg" alt="" class="about__step-img">
                                    </div>
                                </div>
                                <div class="main__text red main__text--md">Получаете заказы <br>
                                    от пользователей
                                </div>
                                <div class="main__text main__text--sm">вам станут доступны заказы от частных клиентов и больших
                                    компаний
                                </div>
                            </div>
                        </div>
                        <div class="about__tabs-bottom">
                            <div class="main__text main__text--md">Поиск исполнителя за 2 минуты!</div>
                            <div class="main__text">Частные мастера и профильные компании предлагают более 100 видов услуг — от
                                проектирования до ремонта электропроводки и оборудования на самых выгодных условиях. <br><br>
                                Интерфейс портала максимально простой и удобный. Система поиска и фильтрации помогает найти
                                исполнителя быстро и с минимальными усилиями. Сортируйте предложения по виду работ, стоимости
                                услуг, рейтингу и квалификации исполнителя. Мы поможем организовать выполнение проекта с
                                наименьшими затратами и в кратчайшие сроки!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
