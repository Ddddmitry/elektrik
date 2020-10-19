<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"]):
    $image = $arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"];
else:
    $image = "/local/templates/.default/img/contractor-empty.png";
endif;
?>
<div class="hideOnMobile">
    <div class="card__content">
        <a href="<?=$arResult["SEF_FOLDER"]?>" class="btn-back">К списку исполнителей</a>
        <div class="card__holder" data-contractor = "<?=$arResult["PROFILE"]["ID"]?>">
            <div class="card__lside js-contractor-detail-viewed" data-contractor-user="<?=$arResult["PROFILE"]["PROPERTIES"]["USER"]["VALUE"]?>" data-user-session="<?=$arResult["SESSION_ID"]?>" >
                <div class="card__container">
                    <div class="card__container-top">

                        <div class="card__container-photo" style="background-image: url(<?=$image?>);">
                            <div class="card__container-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d143721.3185750522!2d37.56225561974967!3d55.74728641316869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d4b0c9%3A0x3d44d6cc5757cf4c!2z0JzQvtGB0LrQstCwLCDQoNC-0YHRgdC40Y8!5e0!3m2!1sru!2sua!4v1580653449549!5m2!1sru!2sua"
                                        frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                            </div>
                        </div>
                        <div class="card__container-description">
                            <div class="card__container-headline">
                                <h1 class="card__container-name main__text main__text--lg"><?=$arResult["PROFILE"]["NAME"]?></h1>
                                <?if(intval($arResult["PROFILE"]["SERTIFIED"]) > 0):?>
                                    <div class="card__container-certified">
                                        <div class="btn btn--white-icon btn--white-icon--xs">
                                            <img src="/local/templates/elektrik/images/logo-e.svg" alt="">
                                            <span>Сертифицирован</span>
                                        </div>
                                    </div>
                                <?endif;?>

                            </div>
                            <div class="card__container-info">
                                <?if(intval($arResult["PROFILE"]["FREE"]) <= 0):?>
                                    <div class="card__container-status is-busy">Занят</div>
                                <?else:?>
                                    <div class="card__container-status is-free">Свободен</div>
                                <?endif;?>

                                <?if(intval($arResult["PROFILE"]["TIME"]) > 0):?>
                                    <div class="card__container-time is-fast main__text main__text--xs light">Среднее время ответа: <?=$arResult["PROFILE"]["TIME"]?> <?=$arResult["PROFILE"]["TIME_WORD"]?></div>
                                <?endif;?>
                            </div>
                            <div class="card__container-items">
                                <?if($arResult["PROFILE"]["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                                    <div class="card__container-item">
                                    <div class="card__container-lside">
                                        <div class="main__text main__text--xs">Адрес:</div>
                                    </div>
                                    <div class="card__container-rside">
                                        <div class="main__text main__text--xs light"><?=$arResult["PROFILE"]["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                                        <?/*<a href="" class="main__text main__text--xs link js-open-map-card">На карте</a>*/?>
                                    </div>
                                </div>
                                <?endif;?>
                                <?if($arResult["PROFILE"]["PROPERTIES"]["SKILL"]["VALUE"]):?>
                                <div class="card__container-item">
                                    <div class="card__container-lside">
                                        <div class="main__text main__text--xs">Квалификация:</div>
                                    </div>
                                    <div class="card__container-rside">
                                        <div class="card__container-level main__text main__text--xs light"><?=$arResult["PROFILE"]["PROPERTIES"]["SKILL"]["VALUE_NAME"]?></div>
                                        <!--<div class="card__container-question"></div>-->
                                    </div>
                                </div>
                                <?endif;?>
                                <?if($arResult["PROFILE"]["EDUCATIONS"]):?>
                                    <div class="card__container-item">
                                        <div class="card__container-lside">
                                            <div class="main__text main__text--xs">Образование:</div>
                                        </div>
                                        <div class="card__container-rside">
                                            <div class="main__text main__text--xs light">
                                                <?foreach ($arResult["PROFILE"]["EDUCATION"] as $arEducation):?>
                                                    <?=$arEducation["NAME"];?>,<br>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                <?endif;?>
                                <?if($arResult["PROFILE"]["LANGUAGES"]):?>
                                    <div class="card__container-item">
                                    <div class="card__container-lside">
                                        <div class="main__text main__text--xs">Языки:</div>
                                    </div>
                                    <div class="card__container-rside">
                                        <div class="main__text main__text--xs light">
                                            <?$arLanguages=[];?>
                                            <?foreach ($arResult["PROFILE"]["LANGUAGES"] as $arLang):?>
                                                <?
                                                $arLanguages[] = $arLang["LANGUAGE"]["NAME"]." - ".$arLang["LEVEL"]["NAME"];
                                                ?>
                                            <?endforeach;?>
                                            <?= implode(",<br>",$arLanguages);?>
                                        </div>
                                    </div>
                                </div>
                                <?endif;?>
                                <?if($arResult["PROFILE"]["JOBS"]):?>
                                    <div class="card__container-item">
                                        <div class="card__container-lside">
                                            <div class="main__text main__text--xs">Опыт работы:</div>
                                        </div>
                                        <div class="card__container-rside">
                                            <div class="main__text main__text--xs light">
                                                <?foreach ($arResult["PROFILE"]["JOBS"] as $arJob):?>
                                                    <?=$arJob["NAME"];?>
                                                    (<?=$arJob["START_DATE"]?>
                                                    <?if($arJob["IS_NOW"]):?>
                                                        — по настроящее время
                                                    <?else:?>
                                                        — <?=$arJob["END_DATE"]?>
                                                    <?endif;?>),
                                                    <br>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="card__body">
                        <?if($arResult["PROFILE"]["PREVIEW_TEXT"]):?>
                            <div class="card__body-box">
                                <div class="card__body-headline main__text main__text--md">Об исполнителе:</div>
                                <div class="card__body-description main__text"><?=$arResult["PROFILE"]["PREVIEW_TEXT"]?></div>
                            </div>
                        <?endif;?>
                        <?if($arResult["PROFILE"]["SERVICES"]):?>
                            <?$index = 0;?>
                            <?foreach ($arResult["PROFILE"]["SERVICES"] as $arService):?>
                                <div class="card__body-box" data-service-item id="services">
                                    <?if($index == 0):?>
                                        <div class="card__body-headline main__text main__text--md">Услуги:</div>
                                        <?$index++;?>
                                    <?endif;?>
                                    <div class="card__body-title"><?=$arService["NAME"]?>:</div>
                                    <?if($arService["SERVICES"]):?>
                                        <ul class="card__body-items">
                                            <?foreach ($arService["SERVICES"] as $index => $arItem):?>
                                                <li class="card__body-item <?if($index > 2):?>is-hide<?endif;?>">
                                                    <div class="card__body-item-string">
                                                        <div class="card__body-item-title main__text main__text--xs"><?=$arItem["NAME"]?></div>
                                                        <div class="card__body-item-line"></div>
                                                        <div class="card__body-item-price main__text main__text--xs light">от <?=$arItem["PRICE"]?> ₽</div>
                                                    </div>
                                                </li>
                                            <?endforeach;?>
                                        </ul>
                                    <?endif;?>
                                    <?if($index > 2):?><a href="#" class="btn-more js-open-more">Ещё</a><?endif;?>
                                </div>
                            <?endforeach;?>

                        <?endif;?>
                    </div>
                </div>


                <?if($arResult["PROFILE"]["STUDIES"]):?>
                <div class="card__box">
                    <div class="card__box-title main__text main__text--md">Прошел обучение:</div>
                    <ul class="card__box-items">
                        <?foreach ($arResult["PROFILE"]["STUDIES"]  as $arStudy):?>
                            <li class="card__box-item">
                                <a href="<?=$arStudy["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arStudy["NAME"]?></a>
                                <div class="btn btn--gray btn--gray--xs"><?=$arStudy["PROPERTY_TYPE_NAME"]?></div>
                                <?if($arStudy["AUTHOR_PICTURE"]):?>
                                <div class="card__box-logo btn btn--gray-logo btn--gray-logo--xs">
                                    <img src="<?=$arStudy["AUTHOR_PICTURE"]["SRC"]?>" alt="">
                                </div>
                                <?endif;?>
                            </li>
                        <?endforeach;?>
                    </ul>
                    <!--<a href="#" class="btn-more">Ещё</a>-->
                </div>
                <?endif;?>


                <?if($arResult["PROFILE"]["EVENTS"]):?>
                <div class="card__box">
                    <div class="card__box-title main__text main__text--md">Посетил мероприятия:</div>
                    <ul class="card__box-items">
                        <?foreach ($arResult["PROFILE"]["EVENTS"]  as $arEvent):?>
                            <li class="card__box-item">
                                <a href="<?=$arEvent["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arEvent["NAME"]?></a>
                                <div class="btn btn--gray btn--gray--xs"><?=$arEvent["PROPERTY_TYPE_NAME"]?></div>
                                <div class="card__box-date main__text main__text--xs light"><?=$arEvent["DATE"]?></div>
                            </li>
                        <?endforeach;?>
                    </ul>
                </div>
                <?endif;?>
            </div>
            <div class="card__rside" >
                <button class="card__rside-open btn btn--red">Показать контакты</button>
                <div class="card__rside-form" >

                    <div class="main__text gray-light">Свяжитесь с электриком</div>
                    <?/*<a href="tel:+<?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?>" class="card__rside-phone main__text main__text--md"><?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?></a>*/?>
                    <?if($arResult["IS_AUTH"]):?>
                    <button class="btn btn--xs btn--red" data-make-call>Связаться</button>
                    <?else:?>
                        <div class="main__text gray-light">Что связаться с электриком, вам необходимо <a href="/auth/">авторизоваться</a> или
                            <a href="/register/">зарегистрироваться</a>.</div>
                    <?endif;?>
                    <?/*<div class="main__text main__text--xs">или отправьте запрос, чтобы электрик сам с вами связался</div>
                    <textarea name="" id="" cols="30" rows="10"
                              class="field-textarea field-textarea--sm field-textarea--white"
                              placeholder="Опишите задачу"></textarea>
                    <button class="btn btn--xs btn--red">Отправить запрос</button>*/?>
                </div>
                <div class="card__rside-box">
                    <div class="card__rside-items">
                        <div class="card__rside-item">
                            <div class="main__text">Рейтинг</div>
                            <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["RATING"]?></div>
                        </div>
                        <div class="card__rside-item">
                            <div class="main__text">Отзывов</div>
                            <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["REVIEWS_COUNT"]?></div>
                        </div>
                        <?
                        // todo: Количество заказов кэшируется, надо это исправить, чтобы показывать актуальную информацию
                        ?>
                        <div class="card__rside-item">
                            <div class="main__text">Заказов</div>
                            <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["ORDERS_COUNT"]?></div>
                        </div>
                    </div>
                </div>
                <?if($arResult["PROFILE"]["PROPERTIES"]["DOCUMENTS"]):?>
                    <div class="card__rside-box">
                    <div class="main__text main__text--md">Документы
                        и сертификаты:
                    </div>
                    <div class="card__rside-documents">
                        <? foreach ($arResult["PROFILE"]["PROPERTIES"]["DOCUMENTS"] as $arDocument) {?>
                            <div class="card__rside-document">
                                <a href="<?=$arDocument["SRC"]?>"
                                   data-fancybox="gallery-docs"
                                   data-fancybox-img
                                   >
                                    <img src="<?=$arDocument["SRC"]?>" alt="">
                                </a>

                            </div>
                        <?}?>
                    </div>
                </div>
                <?endif;?>
            </div>
        </div>
    </div>

    <?$countWorks = count($arResult["PROFILE"]["WORKS"]);?>
    <?if($countWorks > 0):?>
        <section class="work-examples js-work-examples">
        <div class="container">
            <div class="work-examples__content">
                <div class="main__text main__text--md">Примеры работ: <span class="light"><?=$countWorks?></span></div>
                <div class="work-examples__inners js-work-examples__inners_list">
                    <? foreach ($arResult["PROFILE"]["WORKS"] as $index=>$arWork) {?>
                        <?
                        $prev = $index-1;
                        $next = $index+1;
                        $last = array_key_last($arResult["PROFILE"]["WORKS"]);
                        if($prev < 0) $prev = $last;
                        if($next > $last) $next = 0;
                        ?>
                        <div class="work-examples__inner js-work-examples__inner <?if($index > 2):?>hide<?endif?>">
                            <div class="work-examples__container" data-work-id="<?=$arWork["ID"]?>">
                                <div class="work-examples__photo" style="background-image: url('<?=$arWork["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                <div class="work-examples__info">

                                    <?if(!empty($arWork["PROPERTIES"]["VIDEOS"]["VALUES"])):?>
                                        <div class="work-examples__video"></div>
                                    <?endif;?>
                                    <div class="work-examples__number main__text main__text--md white">+<?=count($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"])?></div>
                                </div>
                            </div>
                            <div class="work-examples__title main__text"><?=$arWork["NAME"]?></div>
                            <div class="work-examples__description main__text main__text--xs light"><?=$arWork["PREVIEW_TEXT"]?></div>
                        </div>

                        <div class="popup popup--gallery popup--gallery-<?=$arWork["ID"]?>">
                            <div class="popup__holder">
                                <div class="popup__overlay"></div>
                                <div class="popup__container">
                                    <div class="popup__close"></div>

                                    <div class="popup__gallery">
                                        <div class="popup__gallery-slider">
                                            <div class="swiper-container gallery-top gallery-top-<?=$arWork["ID"]?>">
                                                <div class="swiper-wrapper">
                                                    <? foreach ($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $arPhoto) {?>
                                                        <div class="swiper-slide">
                                                            <div class="popup__gallery-item" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"]?>');"></div>
                                                        </div>
                                                    <?}?>
                                                    <? foreach ($arWork["PROPERTIES"]["VIDEOS"]["VALUES"] as $link) {?>
                                                    <div class="swiper-slide">
                                                        <iframe class="popup__gallery-item popup__gallery-item--video"
                                                                src="<?=$link?>" frameborder="0"
                                                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                allowfullscreen></iframe>
                                                    </div>
                                                    <?}?>
                                                </div>
                                                <div class="swiper-button-next swiper-button-next-<?=$arWork["ID"]?>"></div>
                                                <div class="swiper-button-prev swiper-button-prev-<?=$arWork["ID"]?>"></div>
                                            </div>
                                            <div class="swiper-container gallery-thumbs gallery-thumbs-<?=$arWork["ID"]?>">
                                                <div class="swiper-wrapper">
                                                    <? foreach ($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $arPhoto) {?>
                                                        <div class="swiper-slide">
                                                            <div class="popup__gallery-item" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"]?>');"></div>
                                                        </div>
                                                    <?}?>
                                                    <? foreach ($arWork["PROPERTIES"]["VIDEOS"]["VALUES"] as $link) {?>
                                                        <div class="swiper-slide">
                                                            <iframe class="popup__gallery-item popup__gallery-item--video"
                                                                    src="<?=$link?>" frameborder="0"
                                                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen></iframe>
                                                        </div>
                                                    <?}?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="popup__gallery-description">
                                            <div class="main__text main__text--md"><?=$arWork["NAME"]?></div>
                                            <div class="main__text main__text--sm"><?=$arWork["DETAIL_TEXT"]?></div>
                                            <?if($countWorks > 1):?>
                                            <div class="popup__gallery-navigation" data-gallery-nav>
                                                <a href="#" class="popup__gallery-prev main__text main__text--sm link" data-work-id="<?=$arResult["PROFILE"]["WORKS"][$prev]["ID"]?>">Предыдущая работа</a>
                                                <a href="#" class="popup__gallery-next main__text main__text--sm link" data-work-id="<?=$arResult["PROFILE"]["WORKS"][$next]["ID"]?>">Следующая работа</a>
                                            </div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?}?>
                </div>

                <?if($countWorks > 3):?>
                    <div class="work-examples__action">
                        <button type="button" class="btn btn--border-red js-marketDetail-works-more">
                            <span>Показать еще</span>
                        </button>
                    </div>
                <?endif;?>

            </div>



        </div>
    </section>
    <?endif;?>

    <?
    $APPLICATION->IncludeComponent('electric:contractors.reviews', '', [
        "USER" =>$arResult["PROFILE"]["PROPERTIES"]["USER"]["VALUE"],
        "PAGE_SIZE" => REVIEWS_PAGE_SIZE,
    ]);
    ?>
</div>

<div class="hideOnPc cardoo">
    <section class="card">
        <div class="container">
            <div class="card__content">
                <a href="<?=$arResult["SEF_FOLDER"]?>" class="btn-back">К списку исполнителей</a>
                <div class="card__holder" data-contractorm = "<?=$arResult["PROFILE"]["ID"]?>">
                    <div class="card__lside noPad">
                        <div class="card__container">
                            <div class="card__container-top">
                                <div class="card__container-photo" style="background-image: url(<?=$image?>);">
                                    <div class="card__container-map">
                                        <iframe src="https://yandex.ua/map-widget/v1/-/CKqLUHPf" frameborder="1"
                                                allowfullscreen="true" style="position:relative;"></iframe>
                                    </div>
                                </div>
                                <div class="card__container-description">
                                    <div class="card__container-headline">
                                        <div class="card__container-name main__text main__text--lg"><?=$arResult["PROFILE"]["NAME"]?></div>
                                        <?if(intval($arResult["PROFILE"]["FREE"]) <= 0):?>
                                            <div class="card__container-status is-busy">Занят</div>
                                        <?else:?>
                                            <div class="card__container-status is-free">Свободен</div>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="card__container-top bluringEff">
                                <div class="card__container-info">
                                    <?if(intval($arResult["PROFILE"]["SERTIFIED"]) > 0):?>
                                        <div class="card__container-certified">
                                            <div class="btn btn--white-icon btn--white-icon--xs">
                                                <img src="/local/templates/elektrik/images/logo-e.svg" alt="">
                                                <span>Сертифицирован</span>
                                            </div>
                                        </div>
                                    <?endif;?>
                                    <?if(intval($arResult["PROFILE"]["TIME"]) > 0):?>
                                        <div class="card__container-time is-fast main__text main__text--xs light">Среднее время ответа: <?=$arResult["PROFILE"]["TIME"]?> <?=$arResult["PROFILE"]["TIME_WORD"]?></div>
                                    <?endif;?>
                                </div>
                            </div>
                            <div class="card__container-top">
                                <div class="card__container-tabs">
                                    <div class="cabinet__tabs">
                                        <div class="cabinet__tabs-btns" data-tabs="">
                                            <div class="cabinet__tabs-btn main__text is-open" data-tab="card-info">Информация</div>
                                            <div class="cabinet__tabs-btn main__text" data-tab="card-service">Услуги</div>
                                            <div class="cabinet__tabs-btn main__text" data-tab="card-reviews">Отзывы</div>
                                            <div class="cabinet__tabs-btn main__text" data-tab="card-works">Примеры работ</div>
                                        </div>
                                    </div>
                                    <div class="card__container-items-w">


                                        <div class="card__rside">
                                            <button class="card__rside-open btn btn--red">Показать контакты</button>
                                            <div class="card__rside-form" >

                                                <div class="main__text gray-light">Свяжитесь с электриком</div>
                                                <?/*<a href="tel:+<?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?>" class="card__rside-phone main__text main__text--md"><?=$arResult["PROFILE"]["PROPERTIES"]["PHONE"]["VALUE"]?></a>*/?>
                                                <?if($arResult["IS_AUTH"]):?>
                                                    <button class="btn btn--xs btn--red" data-make-call>Связаться</button>
                                                <?else:?>
                                                    <div class="main__text gray-light">Что связаться с электриком, вам необходимо <a href="/auth/">авторизоваться</a> или
                                                        <a href="/register/">зарегистрироваться</a>.</div>
                                                <?endif;?>

                                            </div>


                                        </div>

                                        <?if($arResult["PROFILE"]["PROPERTIES"]["ADDRESS"]["VALUE"]):?>
                                            <div class="card__container-item startHide is-open" data-tab-content="card-info">
                                                <div class="card__container-lside">
                                                    <div class="main__text main__text--xs">Адрес:</div>
                                                </div>
                                                <div class="card__container-rside">
                                                    <div class="main__text main__text--xs light"><?=$arResult["PROFILE"]["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
                                                    <?/*<a href="" class="main__text main__text--xs link js-open-map-card">На карте</a>*/?>
                                                </div>
                                            </div>
                                        <?endif;?>
                                        <?if($arResult["PROFILE"]["PROPERTIES"]["SKILL"]["VALUE"]):?>
                                            <div class="card__container-item startHide is-open" data-tab-content="card-info">
                                                <div class="card__container-lside">
                                                    <div class="main__text main__text--xs">Квалификация:</div>
                                                </div>
                                                <div class="card__container-rside saveflex">
                                                    <div class="card__container-level main__text main__text--xs light"><?=$arResult["PROFILE"]["PROPERTIES"]["SKILL"]["VALUE_NAME"]?></div>
                                                    <!--<div class="card__container-question"></div>-->
                                                </div>
                                            </div>
                                        <?endif;?>
                                        <?if($arResult["PROFILE"]["LANGUAGES"]):?>
                                            <div class="card__container-item startHide is-open" data-tab-content="card-info">
                                                <div class="card__container-lside">
                                                    <div class="main__text main__text--xs">Языки:</div>
                                                </div>
                                                <div class="card__container-rside">
                                                    <div class="main__text main__text--xs light">
                                                        <?$arLanguages=[];?>
                                                        <?foreach ($arResult["PROFILE"]["LANGUAGES"] as $arLang):?>
                                                            <?
                                                            $arLanguages[] = $arLang["LANGUAGE"]["NAME"]." - ".$arLang["LEVEL"]["NAME"];
                                                            ?>
                                                        <?endforeach;?>
                                                        <?= implode(",<br>",$arLanguages);?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?endif;?>

                                        <div class="card__rside startHide is-open" data-tab-content="card-info">
                                            <div class="card__rside-box">
                                                <div class="card__rside-items">
                                                    <div class="card__rside-item">
                                                        <div class="main__text">Рейтинг</div>
                                                        <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["RATING"]?></div>
                                                    </div>
                                                    <div class="card__rside-item">
                                                        <div class="main__text">Отзывов</div>
                                                        <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["REVIEWS_COUNT"]?></div>
                                                    </div>
                                                    <div class="card__rside-item">
                                                        <div class="main__text">Заказов</div>
                                                        <div class="main__headline main__headline--red"><?=$arResult["PROFILE"]["ORDERS_COUNT"]?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="card__body startHide is-open" data-tab-content="card-info">
                                <div class="card__body-box">
                                    <div class="card__body-headline main__text main__text--md">Об исполнителе:</div>
                                    <div class="card__body-description main__text"><?=$arResult["PROFILE"]["PREVIEW_TEXT"]?></div>
                                </div>
                            </div>
                            <?if($arResult["PROFILE"]["PROPERTIES"]["DOCUMENTS"]):?>
                            <div class="card__rside doclets startHide is-open" data-tab-content="card-info">
                                <div class="card__rside-box">
                                    <div class="main__text main__text--md">Документы
                                        и сертификаты:
                                    </div>
                                    <div class="card__rside-documents">
                                        <? foreach ($arResult["PROFILE"]["PROPERTIES"]["DOCUMENTS"] as $arDocument) {?>
                                            <div class="card__rside-document">
                                                <a href="<?=$arDocument["SRC"]?>"
                                                   data-fancybox="gallery-docs"
                                                   data-fancybox-img
                                                >
                                                    <img src="<?=$arDocument["SRC"]?>" alt="">
                                                </a>

                                            </div>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
                            <?endif;?>


                        </div>
                        <?if($arResult["PROFILE"]["STUDIES"]):?>
                        <div class="card__box startHide is-open" data-tab-content="card-info">

                                <div class="card__box-title main__text main__text--md">Прошел обучение:</div>
                                <ul class="card__box-items newViewKval">
                                    <?foreach ($arResult["PROFILE"]["STUDIES"]  as $arStudy):?>
                                        <li class="card__box-item">
                                            <a href="<?=$arStudy["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arStudy["NAME"]?></a>
                                            <div class="btn btn--gray btn--gray--xs"><?=$arStudy["PROPERTY_TYPE_NAME"]?></div>
                                            <?if($arStudy["AUTHOR_PICTURE"]):?>
                                                <div class="card__box-logo btn btn--gray-logo btn--gray-logo--xs">
                                                    <img src="<?=$arStudy["AUTHOR_PICTURE"]["SRC"]?>" alt="">
                                                </div>
                                            <?endif;?>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                                <!--<a href="#" class="btn-more">Ещё</a>-->

                        </div>
                        <?endif;?>
                        <?if($arResult["PROFILE"]["EVENTS"]):?>
                            <div class="card__box startHide is-open" data-tab-content="card-info">
                                <div class="card__box-title main__text main__text--md">Посетил мероприятия:</div>
                                <ul class="card__box-items newViewKval">
                                    <?foreach ($arResult["PROFILE"]["EVENTS"]  as $arEvent):?>
                                        <li class="card__box-item">
                                            <a href="<?=$arEvent["DETAIL_PAGE_LINK"]?>" class="main__text main__text--sm link"><?=$arEvent["NAME"]?></a>
                                            <div class="btn btn--gray btn--gray--xs"><?=$arEvent["PROPERTY_TYPE_NAME"]?></div>
                                            <div class="card__box-date main__text main__text--xs light"><?=$arEvent["DATE"]?></div>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            </div>
                        <?endif;?>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="work-services" id="card-service" data-tab-content="card-service">
        <div class="card__container">
            <div class="card__body">
                <?if($arResult["PROFILE"]["SERVICES"]):?>
                    <?$index = 0;?>
                    <?foreach ($arResult["PROFILE"]["SERVICES"] as $arService):?>
                        <div class="card__body-box">
                            <?if($index == 0):?>
                                <div class="card__body-headline main__text main__text--md">Услуги:</div>
                                <?$index++;?>
                            <?endif;?>
                            <div class="card__body-title"><?=$arService["NAME"]?>:</div>
                            <?if($arService["SERVICES"]):?>
                                <ul class="card__body-items">
                                    <?foreach ($arService["SERVICES"] as $index => $arItem):?>
                                        <li class="card__body-item <?if($index > 2):?>is-hide<?endif;?>">
                                            <div class="card__body-item-string">
                                                <div class="card__body-item-title main__text main__text--xs"><?=$arItem["NAME"]?></div>
                                                <div class="card__body-item-line"></div>
                                                <div class="card__body-item-price main__text main__text--xs light">от <?=$arItem["PRICE"]?> ₽</div>
                                            </div>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            <?endif;?>
                            <?if($index > 2):?><a href="#" class="btn-more js-open-more">Ещё</a><?endif;?>
                        </div>
                    <?endforeach;?>
                <?endif;?>
            </div>
        </div>
    </section>

    <?$countWorks = count($arResult["PROFILE"]["WORKS"]);?>
    <?if($countWorks > 0):?>
        <section class="work-examples  js-work-examples" id="card-works" data-tab-content="card-works">
            <div class="container">
                <div class="work-examples__content">
                    <div class="main__text main__text--md">Примеры работ: <span class="light"><?=$countWorks?></span></div>
                    <div class="work-examples__inners js-work-examples__inners_list">
                        <? foreach ($arResult["PROFILE"]["WORKS"] as $index=>$arWork) {?>
                            <?
                            $prev = $index-1;
                            $next = $index+1;
                            $last = array_key_last($arResult["PROFILE"]["WORKS"]);
                            if($prev < 0) $prev = $last;
                            if($next > $last) $next = 0;
                            ?>
                            <div class="work-examples__inner js-work-examples__inner <?if($index > 2):?>hide<?endif?>">
                                <div class="work-examples__container" data-work-id="<?=$arWork["ID"]?>">
                                    <div class="work-examples__photo" style="background-image: url('<?=$arWork["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                                    <div class="work-examples__info">

                                        <?if(!empty($arWork["PROPERTIES"]["VIDEOS"]["VALUES"])):?>
                                            <div class="work-examples__video"></div>
                                        <?endif;?>
                                        <div class="work-examples__number main__text main__text--md white">+<?=count($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"])?></div>
                                    </div>
                                </div>
                                <div class="work-examples__title main__text"><?=$arWork["NAME"]?></div>
                                <div class="work-examples__description main__text main__text--xs light"><?=$arWork["PREVIEW_TEXT"]?></div>
                            </div>

                            <div class="popup popup--gallery popup--gallery-<?=$arWork["ID"]?>">
                                <div class="popup__holder">
                                    <div class="popup__overlay"></div>
                                    <div class="popup__container">
                                        <div class="popup__close"></div>

                                        <div class="popup__gallery">
                                            <div class="popup__gallery-slider">
                                                <div class="swiper-container gallery-top gallery-top-m-<?=$arWork["ID"]?>">
                                                    <div class="swiper-wrapper">
                                                        <? foreach ($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $arPhoto) {?>
                                                            <div class="swiper-slide">
                                                                <div class="popup__gallery-item" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"]?>');"></div>
                                                            </div>
                                                        <?}?>
                                                        <? foreach ($arWork["PROPERTIES"]["VIDEOS"]["VALUES"] as $link) {?>
                                                            <div class="swiper-slide">
                                                                <iframe class="popup__gallery-item popup__gallery-item--video"
                                                                        src="<?=$link?>" frameborder="0"
                                                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                        allowfullscreen></iframe>
                                                            </div>
                                                        <?}?>
                                                    </div>
                                                    <div class="swiper-button-next swiper-button-next-<?=$arWork["ID"]?>"></div>
                                                    <div class="swiper-button-prev swiper-button-prev-<?=$arWork["ID"]?>"></div>
                                                </div>
                                                <div class="swiper-container gallery-thumbs gallery-thumbs-m-<?=$arWork["ID"]?>">
                                                    <div class="swiper-wrapper">
                                                        <? foreach ($arWork["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $arPhoto) {?>
                                                            <div class="swiper-slide">
                                                                <div class="popup__gallery-item" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"]?>');"></div>
                                                            </div>
                                                        <?}?>
                                                        <? foreach ($arWork["PROPERTIES"]["VIDEOS"]["VALUES"] as $link) {?>
                                                            <div class="swiper-slide">
                                                                <iframe class="popup__gallery-item popup__gallery-item--video"
                                                                        src="<?=$link?>" frameborder="0"
                                                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                        allowfullscreen></iframe>
                                                            </div>
                                                        <?}?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="popup__gallery-description">
                                                <div class="main__text main__text--md"><?=$arWork["NAME"]?></div>
                                                <div class="main__text main__text--sm"><?=$arWork["DETAIL_TEXT"]?></div>
                                                <?if($countWorks > 1):?>
                                                    <div class="popup__gallery-navigation" data-gallery-nav>
                                                        <a href="#" class="popup__gallery-prev main__text main__text--sm link" data-work-id="<?=$arResult["PROFILE"]["WORKS"][$prev]["ID"]?>">Предыдущая работа</a>
                                                        <a href="#" class="popup__gallery-next main__text main__text--sm link" data-work-id="<?=$arResult["PROFILE"]["WORKS"][$next]["ID"]?>">Следующая работа</a>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                    </div>

                    <?if($countWorks > 3):?>
                        <div class="work-examples__action">
                            <button type="button" class="btn btn--border-red js-marketDetail-works-more">
                                <span>Показать еще</span>
                            </button>
                        </div>
                    <?endif;?>

                </div>



            </div>
        </section>
    <?endif;?>

    <?
    $APPLICATION->IncludeComponent('electric:contractors.reviews', 'mobile', [
        "USER" =>$arResult["PROFILE"]["PROPERTIES"]["USER"]["VALUE"],
        "PAGE_SIZE" => REVIEWS_PAGE_SIZE,
    ]);
    ?>
</div>
<div class="modal" id="successMakeCallModal" style="display: none;">
    <div class="modal-body">
        <div class="modal-title">
            Вам скоро перезвонят
            <div class="closeModal">X</div>
        </div>
        <div class="modal-content">
            Ваш запрос успешно отправлен, в течение нескольких секунд вам позвонят и вы будете соединены с абонентом!
        </div>
    </div>
</div>
