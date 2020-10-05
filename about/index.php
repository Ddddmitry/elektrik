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
                <h1 class="main__text main__text--mb main__text--md">Наша миссия и цель</h1>
                <div class="main__text main__text--sm">Портал Электрик.ру предоставляет неограниченные возможности для
                    найма специалистов и обмена опытом.
                    С его помощью можно организовать выполнение электромонтажных работ в кратчайшие сроки, на высоком
                    профессиональном уровне и с минимальными затратами.
                    <br><br>
                    Разместите задание и найдите исполнителя за несколько минут!
                    <br><br>
                    Чтобы реализовать электротехнический проект, уделите пристальное внимание таким факторам как:
                </div>
                <ul class="main__list">
                    <li class="main__text main__text--sm">
                        техническое решение
                    </li>
                    <li class="main__text main__text--sm">
                        качество оборудования и материалов
                    </li>
                    <li class="main__text main__text--sm">
                        квалификация исполнителей
                    </li>
                    <li class="main__text main__text--sm">
                        стоимость
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
