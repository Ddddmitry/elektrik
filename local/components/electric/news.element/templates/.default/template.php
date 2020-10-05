<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//var_dump($arResult);
?>
<div class="education-list__content">
    <a href="<?=$arParams["SEF_FOLDER"]?>" class="btn-back">К списку мероприятий</a>
    <div class="event">
        <div class="event__top">
            <div class="event__holder">
                <div class="event__image" style="background-image: url('<?=$arResult["DETAIL_PICTURE"]["SRC"] ? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"]?>');">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d143721.3185750522!2d37.56225561974967!3d55.74728641316869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d4b0c9%3A0x3d44d6cc5757cf4c!2z0JzQvtGB0LrQstCwLCDQoNC-0YHRgdC40Y8!5e0!3m2!1sru!2sua!4v1580653449549!5m2!1sru!2sua"
                            frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                    <div id="yandex_map" data-address="Барнаул, Алтайский край, ул. Шевченко, 144А "></div>
                </div>
                <div class="event__info">
                    <div class="btn btn--gray"><?=$arResult["PROPERTIES"]["TYPE"]["VALUE_NAME"]?></div>
                    <div class="events-page__title main__text main__text--lg"><?=$arResult["NAME"]?></div>
                    <div class="main__text">Адрес проведения</div>
                    <div class="main__text"><?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"]?> <a href="#" class="main__text link js-open-map-event">На
                            карте</a></div>
                    <div class="main__text">Дата</div>
                    <div class="main__text"><?=$arResult["DATE"]?></div>
                </div>
            </div>
            <div class="event__description">
                <div class="main__text main__text--md">О мероприятии:</div>
                <div class="main__text">
                    <?=$arResult["DETAIL_TEXT"]?>
                </div>
            </div>
        </div>
        <div class="event__bottom">
            <div class="event__bottom-form">
                <div class="main__text main__text--md red">Записаться на обучение</div>
                <form action="" class="event__form">
                    <div class="event__form-title main__text">Контактная информация</div>
                    <div class="event__form-items">
                        <div class="event__form-item event__form-item--sm">
                            <input type="text" name="" id="" placeholder="ФИО" class="field-input field-input--white">
                        </div>
                        <div class="event__form-item event__form-item--sm">
                            <input type="text" name="" id="" placeholder="Номер телефона"
                                   class="field-input field-input--white">
                        </div>
                        <div class="event__form-item event__form-item--sm">
                            <button class="btn btn--red js-event-button">Записаться</button>
                        </div>
                    </div>
                    <div class="event__form-bottom main__text main__text--xs light">Отправляя форму я соглашаюсь на
                        обработку моих персональных данных, а также с <a href="#" class="main__text link-xs">политикой конфиденциальности</a>
                    </div>
                </form>
            </div>
            <div class="event__bottom-success">
                <div class="event__bottom-middle">
                    <img src="images/logo-check-form.png" class="event__bottom-logo" alt="">
                    <div class="event__bottom-description">
                        <div class="main__text main__text--md">Отлично!</div>
                        <div class="main__text">В ближайшее время мы обработаем ваши данные
                            и специалист центра обучения свяжется в вами
                        </div>
                        <a href="#" class="main__text link js-event-more">Отправить еще одну заявку</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>