<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//todo: Надо не выводить блоки с данными клиента, если они не нужны для показа
?>
<div class="cabinet__sorting">
    <div class="cabinet__sorting-item is-active js-cabinet__sorting-item">
        <div class="main__text main__text--sm" data-sort="all">Все</div>
    </div>
    <?
    //var_dump($arResult["PROFILE"]["ORDERS"]);die();
    ?>

    <?foreach ($arResult["PROFILE"]["STATUS"] as $index => $arStatus):?>
        <?if(in_array($index,LIST_STATUS_CONTRACTOR)):?>
            <div class="cabinet__sorting-item js-cabinet__sorting-item">
                <div class="main__text main__text--sm" data-sort="<?=$index?>"><?=$arStatus["VALUE"]?></div>
                <div class="number"><?=count($arResult["PROFILE"]["ORDERS"][$index])?></div>
            </div>
        <?endif;?>
    <?endforeach;?>

    <?/*foreach ($arResult["PROFILE"]["ORDERS"] as $index => $arStatus):?>
        <?//if(count($arStatus) > 0):?>
        <div class="cabinet__sorting-item js-cabinet__sorting-item">
            <div class="main__text main__text--sm" data-sort="<?=$arResult["PROFILE"]["STATUS"][$index]["XML_ID"]?>"><?=$arResult["PROFILE"]["STATUS"][$index]["VALUE"]?></div>
            <div class="number"><?=count($arStatus)?></div>
        </div>
        <?//endif;?>
    <?endforeach;*/?>
</div>

<?if($arResult["PROFILE"]["ORDERS"]):?>
<div class="cabinet__orders">
    <?foreach ($arResult["PROFILE"]["ORDERS"] as $index => $arStatus):?>
        <?foreach ($arStatus as $arOrder):?>
            <?//var_dump($arOrder);die();?>
            <div class="cabinet__order <?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="completed"):?>is-order-complete<?endif;?>" data-all data-<?=$index?> data-order="<?=$arOrder["ID"]?>">
                <div class="cabinet__order-lside">
                    <div class="cabinet__order-title main__text main__text--md"><?=$arOrder["NAME"]?></div>
                    <div class="cabinet__order-description main__text main__text--sm">
                        <?=$arOrder["PREVIEW_TEXT"]?>
                        <?if($arOrder["PROPERTIES"]["MY_SPARES"]["VALUE"]):?>
                            <br>Есть свои запчасти
                        <?endif;?>
                        <?if($arOrder["PROPERTIES"]["MY_TOOLS"]["VALUE"]):?>
                            <br>Есть необходимый инструменты
                        <?endif;?>
                    </div>
                    <div class="cabinet__order-photos">
                        <?foreach ($arOrder["PROPERTIES"]["PHOTOS"]["VALUE"] as $arPhoto) {?>
                            <div class="cabinet__order-photo" >
                                <a href="<?=$arPhoto["FULL"]["SRC"]?>" data-fancybox="gallery-order-<?=$arOrder["ID"]?>" data-fancybox-img="">
                                    <img src="<?= $arPhoto["PREVIEW"]["src"] ?>" alt="">
                                </a>
                            </div>
                        <?}?>
                    </div>
                </div>
                <div class="cabinet__order-rside">
                    <div class="cabinet__order-steps" data-order-item>

                        <div class="cabinet__order-step is-step-first" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="new"):?>block<?else:?>none<?endif;?>;" >
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div href="#" class="main__text main__text--sm"><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                        <div class="cabinet__order-stars">
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time is-clock main__text"><?=$arOrder["TIME"]?></div>
                                    </div>
                                </div>
                                <div class="cabinet__order-info">
                                    <div class="main__text main__text--xs light"><?=$arOrder["PROPERTIES"]["ROOM"]["VALUE_ENUM"]?></div>
                                    <div class="main__text main__text--sm">
                                        <?if($arOrder["PROPERTIES"]["CITY"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["CITY"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["RAYON"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["RAYON"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["STREET"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["STREET"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                                            д. <?=$arOrder["PROPERTIES"]["HOUSE"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["KORP"]["VALUE"]):?>
                                            корп. <?=$arOrder["PROPERTIES"]["KORP"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLAT"]["VALUE"]):?>
                                            кв./офис <?=$arOrder["PROPERTIES"]["FLAT"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                                            этаж <?=$arOrder["PROPERTIES"]["FLOOR"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                                            домофон <?=$arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                                        <?endif;?>
                                    </div>
                                    <div class="main__text main__text--xs light">Время выезда</div>
                                    <div class="main__text main__text--sm"><?=$arOrder["PROPERTIES"]["DATE_TIME"]["DATE"]?> <?=$arOrder["PROPERTIES"]["DATE_TIME"]["TIME"]?></div>
                                </div>
                                <div class="cabinet__order-warning">
                                    <div class="cabinet__order-warning-pay main__text main__text--xs blue">Оплата за услугу оговаривается со специалистом лично.</div>
                                    <a href="" class="main__text main__text--xs link">Юридическая ответственность</a>
                                </div>
                                <button class="cabinet__order-action btn btn--xs btn--red is-open-show-contacts">Показать контакты</button>

                            </div>
                            <div class="cabinet__order-contacts">
                                <?/*<a href="tel:+79202767347" class="main__text main__text--md"><?=$arOrder["PROPERTIES"]["CONTACT_PHONE"]["VALUE"]?></a>*/?>

                                <button class="btn btn--xs btn--red" data-make-call>Связаться с клиентом</button>
                                <div class="modal" id="successMakeCallModal">
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
                                <div class="main__text main__text--xs light">
                                    <?if($arOrder["PROPERTIES"]["CALL_ANYTIME"]["VALUE"]):?>
                                        Звонить в любое время
                                    <?else:?>
                                        Звонить с <?=$arOrder["PROPERTIES"]["CALL_TIME"]["VALUE_ENUM"]?>
                                    <?endif;?>
                                </div>
                                <div class="cabinet__order-contacts-actions">
                                    <button class="btn btn--xs btn--red is-btn-apply">Принять заказ</button>
                                    <button class="btn btn--xs btn--gray-bg is-btn-renouncement">Отказаться</button>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-in-progress" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="inwork"):?>block<?else:?>none<?endif;?>;">
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div href="#" class="main__text main__text--sm "><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                        <div class="cabinet__order-stars">
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time main__text"><?=$arOrder["PROPERTIES"]["STATUS"]["VALUE_ENUM"]?></div>
                                    </div>
                                </div>
                                <div class="cabinet__order-info">
                                    <div class="main__text main__text--xs light"><?=$arOrder["PROPERTIES"]["ROOM"]["VALUE_ENUM"]?></div>
                                    <div class="main__text main__text--sm"> <?if($arOrder["PROPERTIES"]["CITY"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["CITY"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["RAYON"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["RAYON"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["STREET"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["STREET"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                                            д. <?=$arOrder["PROPERTIES"]["HOUSE"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["KORP"]["VALUE"]):?>
                                            корп. <?=$arOrder["PROPERTIES"]["KORP"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLAT"]["VALUE"]):?>
                                            кв./офис <?=$arOrder["PROPERTIES"]["FLAT"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                                            этаж <?=$arOrder["PROPERTIES"]["FLOOR"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                                            домофон <?=$arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                                        <?endif;?></div>
                                    <div class="main__text main__text--xs light">Время выезда</div>
                                    <div class="main__text main__text--sm"><?=$arOrder["PROPERTIES"]["DATE_TIME"]["DATE"]?> <?=$arOrder["PROPERTIES"]["DATE_TIME"]["TIME"]?></div>
                                    <div class="main__text main__text--xs light">Телефон</div>
                                    <div class="main__text main__text--sm"><?=$arOrder["PROPERTIES"]["CONTACT_PHONE"]["VALUE"]?></div>
                                    <div class="main__text main__text--xs light">
                                        <?if($arOrder["PROPERTIES"]["CALL_ANYTIME"]["VALUE"]):?>
                                            Звонить в любое время
                                        <?else:?>
                                            Звонить с <?=$arOrder["PROPERTIES"]["CALL_TIME"]["VALUE_ENUM"]?>
                                        <?endif;?>
                                    </div>
                                </div>
                                <button class="cabinet__order-action btn btn--xs btn--red is-close-order">Завершить заказ</button>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-review" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="stay_review"):?>block<?else:?>none<?endif;?>;">
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div class="main__text main__text--sm "><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time main__text">Оставьте отзыв</div>
                                    </div>
                                </div>
                                <div class="cabinet__order-form">
                                    <div class="cabinet__order-form-item">
                                        <div class="cabinet__order-form-item">
															<textarea name="" id="" cols="30" rows="10"
                                                                      class="field-textarea field-textarea--md field-textarea--light"
                                                                      placeholder="Ваш отзыв о работе" data-review></textarea>
                                        </div>
                                        <div class="cabinet__order-form-item cabinet__order-form-item--rating">
                                            <div class="main__text">Ваша оценка</div>
                                            <div class="cabinet__order-rating">
                                                <div class="cabinet__order-rating-star">
                                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                </div>
                                                <div class="cabinet__order-rating-star">
                                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                </div>
                                                <div class="cabinet__order-rating-star">
                                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                </div>
                                                <div class="cabinet__order-rating-star">
                                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                </div>
                                                <div class="cabinet__order-rating-star">
                                                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cabinet__order-form-item">
                                            <label for="check-add" class="field-checkbox">
                                                <input type="checkbox" name="" id="check-add">
                                                <span class="field-checkbox__mark"></span>
                                                <span class="field-checkbox__title">Добавить работу в портфолио</span>
                                            </label>
                                        </div>
                                        <div class="cabinet__order-form-item cabinet__order-form-item--action">
                                            <button class="btn btn--red btn--xs is-btn-complete">Оставить отзыв о работе</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-complete" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="completed"):?>block<?else:?>none<?endif;?>;">
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div  class="main__text main__text--sm "><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time main__text">Завершен</div>
                                    </div>
                                </div>
                                <div class="cabinet__order-top cabinet__order-top--mt">
                                    <div class="cabinet__order-top-inner">
                                        <div class="main__text light">Ваша оценка</div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-stars">
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                            <div class="cabinet__order-star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cabinet__order-info">
                                    <a href="#" class="main__text main__text--sm link-dashed">Посмотреть отзыв</a>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-cancel" >
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div class="main__text">Причина отказа</div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time is-clock main__text"><?=$arOrder["TIME"]?></div>
                                    </div>
                                </div>
                                <div class="cabinet__order-form">
                                    <div class="cabinet__order-form-item">
                                        <label for="radio-1" class="field-radiobox">
                                            <input type="radio" name="radio-renouncement" id="radio-1" checked="checked" value="<?=ENUM_VALUE_ORDERS_REASONS_NO_CLIENT?>" data-reason>
                                            <span class="field-radiobox__mark"></span>
                                            <span class="field-radiobox__title">Клиент не отвечает</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__order-form-item">
                                        <label for="radio-2" class="field-radiobox">
                                            <input type="radio" name="radio-renouncement" id="radio-2" value="<?=ENUM_VALUE_ORDERS_REASONS_NO_CONDITION?>" data-reason>
                                            <span class="field-radiobox__mark"></span>
                                            <span class="field-radiobox__title">Условия не подошли</span>
                                        </label>
                                    </div>
                                    <div class="cabinet__order-form-item">
															<textarea name="" id="" cols="30" rows="10"
                                                                      class="field-textarea field-textarea--md field-textarea--light"
                                                                      placeholder="Опишите причину отказа" data-reason-description></textarea>
                                    </div>
                                    <div class="cabinet__order-form-item cabinet__order-form-item--actions">
                                        <button class="btn btn--gray-bg btn--xs is-btn-cancel">Отказаться</button>
                                        <a href="" class="main__text main__text--xs link is-btn-back">Отменить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-renouncement" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="canceled"):?>block<?else:?>none<?endif;?>;">
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div class="main__text main__text--sm light"><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time main__text light">Отказ</div>
                                    </div>
                                </div>
                                <div class="cabinet__order-info">
                                    <div class="main__text main__text--sm light">
                                        <?if($arOrder["PROPERTIES"]["CITY"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["CITY"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["RAYON"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["RAYON"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["STREET"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["STREET"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                                            д. <?=$arOrder["PROPERTIES"]["HOUSE"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["KORP"]["VALUE"]):?>
                                            корп. <?=$arOrder["PROPERTIES"]["KORP"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLAT"]["VALUE"]):?>
                                            кв./офис <?=$arOrder["PROPERTIES"]["FLAT"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                                            этаж <?=$arOrder["PROPERTIES"]["FLOOR"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                                            домофон <?=$arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cabinet__order-step is-step-skipped" style="display:<?if($arOrder["PROPERTIES"]["STATUS"]["VALUE_XML_ID"]=="skipped"):?>block<?else:?>none<?endif;?>;">
                            <div class="cabinet__order-step-container">
                                <div class="cabinet__order-top">
                                    <div class="cabinet__order-top-inner">
                                        <div class="main__text main__text--sm light"><?=$arOrder['PROPERTIES']["CONTACT_INFO"]["VALUE"]?></div>
                                    </div>
                                    <div class="cabinet__order-top-inner">
                                        <div class="cabinet__order-time main__text light">Пропущена</div>
                                    </div>
                                </div>
                                <div class="cabinet__order-info">
                                    <div class="main__text main__text--sm light">
                                        <?if($arOrder["PROPERTIES"]["CITY"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["CITY"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["RAYON"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["RAYON"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["STREET"]["VALUE"]):?>
                                            <?=$arOrder["PROPERTIES"]["STREET"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["HOUSE"]["VALUE"]):?>
                                            д. <?=$arOrder["PROPERTIES"]["HOUSE"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["KORP"]["VALUE"]):?>
                                            корп. <?=$arOrder["PROPERTIES"]["KORP"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLAT"]["VALUE"]):?>
                                            кв./офис <?=$arOrder["PROPERTIES"]["FLAT"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["FLOOR"]["VALUE"]):?>
                                            этаж <?=$arOrder["PROPERTIES"]["FLOOR"]["VALUE"]?>,
                                        <?endif;?>
                                        <?if($arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]):?>
                                            домофон <?=$arOrder["PROPERTIES"]["INTERCOM"]["VALUE"]?>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?endforeach;?>
    <?endforeach;?>
</div>
<?else:?>
<div class="cabinet__orders">
    <p class="no_orders">Заказы не найдены</p>
</div>
<?endif;?>
