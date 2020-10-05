<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?if(!$arResult["IS_AJAX"]):?>
<section class="feedback feedback_backoffice " id="reviews">
    <div class="container">
        <div class="feedback__content">
            <div class="feedback__holder">
                <div class="feedback__lside">
                    <div class="feedback__inners js-reviews-list" data-page="1" data-filter-mark="" data-user="<?=$arResult["REVIEWS_DATA"]["CONTRACTOR"]["USER"]?>">
<?endif;?>
                        <?if($arResult['REVIEWS_DATA']["ITEMS"]):?>
                        <? foreach ($arResult['REVIEWS_DATA']["ITEMS"] as $arItem) {?>

                            <div class="feedback__inner" data-review="<?=$arItem["ID"]?>" >
                                <div class="feedback__info">
                                    <div class="main__text main__text-name-author"><?=$arItem["AUTHOR_NAME"]?></div>
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
                                    <div class="feedback__answer js-feedback__answer" >
                                        <div class="main__text main__text-name-author"><?=$arResult["REVIEWS_DATA"]["CONTRACTOR"]["NAME"]?></div>
                                        <div class="main__text"><?=$arItem["PROPERTY_ANSWER_VALUE"]?></div>
                                        <a href="#" class="marketDetail-reviews-list__answer-edit js-marketDetail-reviews-list__answer-edit">
                                            <span class="svg-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <g fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-linejoin="round" stroke-width="2">
                                                        <path stroke-linecap="round" d="M19.435 3l2.121 2.121L8.121 18.556 6 16.435z"></path>
                                                        <path stroke-linecap="square" d="M5.85 20.828l-2.524.379.402-2.5"></path>
                                                        <path stroke-linecap="round" d="M16.967 5.967l1.69 1.69"></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                <?else:?>
                                    <div class="marketDetail-reviews-list__bottom js-marketDetail-reviews-list__answer-bottom">
                                        <a href="#" class="js-marketDetail-reviews-list__answer-toggler">
                                            Ответить
                                        </a>
                                    </div>

                                <?endif;?>
                                    <div class="marketDetail-reviews-list__form js-marketDetail-reviews-list__answer-form">
                                        <form action="#" data-review-form="<?=$arItem["ID"]?>" id="comment-<?=$arItem["ID"]?>"
                                              class="marketDetail-reviews__comment-form articlesPage-comments__form">
                                            <input type="hidden" name="comment-<?=$arItem["ID"]?>-answer" id="comment-<?=$arItem["ID"]?>-answer">
                                            <input type="hidden" name="reviewID" value="<?=$arItem["ID"]?>">
                                            <input type="hidden" name="owner" value="<?=$arResult["REVIEWS_DATA"]["CONTRACTOR"]["NAME"]?>">
                                            <div class="formGroup required notValid">
                                                <div class="formGroup-inner">
                                                    <label for="comment-<?=$arItem["ID"]?>-text" class="">Ответьте на отзыв</label>
                                                    <textarea name="answerText" id="comment-<?=$arItem["ID"]?>-text"
                                                              maxlength="2048" required="required"
                                                              placeholder="Ответьте на отзыв"
                                                              class="marketDetail-reviews__comment-textarea js-textarea-sm notValid"
                                                              data-placeholder="Ответьте на отзыв"><?=$arItem["PROPERTY_ANSWER_VALUE"]?></textarea>
                                                    <a href="#" class="js-marketDetail-reviews-list__answer-cancel marketDetail-reviews-list__answer-cancel">
                                                        <span class="marketDetail-reviews-list__answer-cancel-line"></span>
                                                        <span class="marketDetail-reviews-list__answer-cancel-line"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="formGroup formGroup__bottom disabled">
                                                <div class="formGroup-inner">
                                                    <button type="submit" data-submit-<?=$arItem["ID"]?> data-form-submit name="comment-<?=$arItem["ID"]?>-submit" disabled="disabled">
                                                        Отправить
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                            </div>
                        <?}?>
                        <?else:?>
                        <p class="no_reviews">Отзывов пока не оставляли.</p>
                        <?endif;?>

<?if(!$arResult["IS_AJAX"]):?>
                    </div>

                    <div id="js-reviews-more-button" class="feedback__action js-reviews-more-button">
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
            document.getElementById('js-reviews-more-button').style.display = "none";
        </script>
    <?else:?>
        <script>
            document.getElementById('js-reviews-more-button').style.display = "block";
        </script>
    <?endif;?>
<?//endif;?>
