<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AJAX"]):?>
<section class="feedback" id="card-reviews" data-tab-content="card-reviews">

    <div class="container">
        <div class="feedback__content">
            <div class="main__text main__text--md">Отзывы: <span class="light"><?=$arResult["REVIEWS_DATA"]["NAV"]["TOTAL_COUNT"]?></span></div>
            <div class="feedback__holder">
                <div class="feedback__lside">
                    <?$APPLICATION->IncludeComponent('electric:form.review', '', ["USER" => $arResult["REVIEWS_DATA"]["CONTRACTOR"]["USER"]]);?>

                    <div class="feedback__inners js-reviews-list" data-page="1" data-filter-mark="" data-user="<?=$arResult["REVIEWS_DATA"]["CONTRACTOR"]["USER"]?>">
<?endif;?>

                        <? foreach ($arResult['REVIEWS_DATA']["ITEMS"] as $arItem) {?>

                            <div class="feedback__inner">
                                <div class="feedback__info">
                                    <div class="main__text"><?=$arItem["AUTHOR_NAME"]?></div>
                                    <div class="main__text main__text--xs light"><?=$arItem["ACTIVE_FROM"]?></div>
                                </div>
                                <div class="feedback__stars">
                                    <?for($j=1;$j<=5;$j++):?>
                                        <?if($j<=$arItem["PROPERTY_MARK_VALUE"]){?>
                                            <div class="feedback__star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-red.svg" alt="">
                                            </div>
                                        <?}else{?>
                                            <div class="feedback__star">
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/icon-star-gray.svg" alt="">
                                            </div>
                                        <?}?>
                                    <?endfor;?>

                                </div>
                                <div class="feedback__comment"><?=$arItem["PREVIEW_TEXT"];?></div>
                                <?if($arItem["PROPERTY_ANSWER_VALUE"]):?>
                                <div class="feedback__answer">
                                    <div class="main__text"><?=$arResult["REVIEWS_DATA"]["CONTRACTOR"]["NAME"]?></div>
                                    <div class="main__text"><?=$arItem["PROPERTY_ANSWER_VALUE"]?></div>
                                </div>
                                <?endif;?>
                            </div>
                        <?}?>

<?if(!$arResult["IS_AJAX"]):?>
                    </div>

                    <div id="js-reviews-more-button-mobile" class="feedback__action js-reviews-more-button">
                        <a href="#" class="btn btn--border-red ">
                            <span>Показать еще</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<?endif;?>

<?//if($arResult["IS_AJAX"]):?>
    <?if($arResult["REVIEWS_DATA"]["NAV"]["IS_LAST_PAGE"]):?>
        <script>
            document.getElementById('js-reviews-more-button-mobile').style.display = "none";
        </script>
    <?else:?>
        <script>
            document.getElementById('js-reviews-more-button-mobile').style.display = "block";
        </script>
    <?endif;?>
<?//endif;?>
