<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__box cabinet__box--single cabinet__box--brb">
    <div class="cabinet__statistics">
        <div class="main__text main__text--lg">Заказы</div>
        <div class="cabinet__statistics-items">
            <div class="cabinet__statistics-item">
                <div class="main__text">Всего</div>
                <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["ORDERS_COUNT"]?></div>
            </div>
            <div class="cabinet__statistics-item">
                <div class="main__text">Выполнено</div>
                <div class="main__headline"><?=count($arResult["PROFILE"]["ORDERS"]["completed"])?></div>
            </div>
            <div class="cabinet__statistics-item">
                <div class="main__text">Отклонено</div>
                <div class="main__headline"><?=count($arResult["PROFILE"]["ORDERS"]["canceled"])?></div>
            </div>
        </div>
    </div>
</div>

<div class="cabinet__box cabinet__box--single cabinet__box--brb">
    <div class="cabinet__statistics">
        <div class="main__text main__text--lg">Просмотры анкеты</div>
        <div class="cabinet__statistics-items">
            <div class="cabinet__statistics-item">
                <div class="main__text">За всё время</div>
                <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["STATISTICS"]["all"]["watchWorksheet"]?></div>
            </div>
            <div class="cabinet__statistics-item">
                <div class="main__text">За месяц</div>
                <div class="main__headline"><?=$arResult["PROFILE"]["STATISTICS"]["month"]["watchWorksheet"]?></div>
            </div>
            <div class="cabinet__statistics-item">
                <div class="main__text">За неделю</div>
                <div class="main__headline"><?=$arResult["PROFILE"]["STATISTICS"]["week"]["watchWorksheet"]?></div>
            </div>
        </div>
    </div>
</div>

<div class="cabinet__box cabinet__box--single cabinet__box--brb">
    <div class="cabinet__statistics">
        <div class="main__text main__text--lg">Рейтинг и отзывы</div>
        <div class="cabinet__statistics-items">
            <div class="cabinet__statistics-item">
                <div class="main__text">Иготовый рейтинг</div>
                <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["RATING"]?></div>
            </div>
            <div class="cabinet__statistics-item">
                <div class="main__text">Отзывов</div>
                <div class="main__headline"><?=$arResult["PROFILE"]["REVIEWS_COUNT"]?></div>
            </div>
        </div>
    </div>
</div>
