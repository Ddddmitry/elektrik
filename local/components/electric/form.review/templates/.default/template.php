<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["IS_AUTHORIZED"] && $arResult["CAN_STAY_REVIEW"]):?>
    <div class="marketDetail-reviews__comment js-form-comment">

        <div class="marketDetail-reviews__comment-unlock">
            <form action="/api/reviews.add/" class="marketDetail-reviews__comment-form" id="review" data-form-review>
                <input hidden name="review-user" value="<?=$arResult["USER"]?>">
                <div class="js-form-error"></div>
                <div class="formGroup required formGroup_raiting">
                    <div class="formGroup-inner formGroup-inner_line">
                        <div class="formGroup-inner-title">
                            Оценка работы мастера:
                        </div>
                        <div class="raitRadio js-raitRadio">
                            <div id="reviewStars-input">
                                <input id="star-4" type="radio" name="review-mark" value="5"/>
                                <label title="gorgeous" for="star-4"></label>

                                <input id="star-3" type="radio" name="review-mark" value="4"/>
                                <label title="good" for="star-3"></label>

                                <input id="star-2" type="radio" name="review-mark" value="3"/>
                                <label title="regular" for="star-2"></label>

                                <input id="star-1" type="radio" name="review-mark" value="2"/>
                                <label title="poor" for="star-1"></label>

                                <input id="star-0" type="radio" name="review-mark" value="1"/>
                                <label title="bad" for="star-0"></label>
                            </div>

                            <!--<div class="raitRadio__radio">
                                <input type="radio" name="review-mark" value="1" class="js-raitRadio__radio-tag" required>
                                <input type="radio" name="review-mark" value="2" class="js-raitRadio__radio-tag">
                                <input type="radio" name="review-mark" value="3" class="js-raitRadio__radio-tag">
                                <input type="radio" name="review-mark" value="4" class="js-raitRadio__radio-tag">
                                <input type="radio" name="review-mark" value="5" checked class="js-raitRadio__radio-tag">
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="formGroup required">
                    <div class="formGroup-inner">
                        <label for="review-text">
                            Ваш отзыв
                        </label>
                        <textarea name="review-text" id="review-text" required
                                  class="marketDetail-reviews__comment-textarea js-textarea-sm" maxlength="2048" placeholder="Ваш отзыв"></textarea>
                    </div>
                </div>
                <div class="formGroup formGroup__bottom disabled">
                    <div class="formGroup-inner">
                        <button type="submit" name="review-submit" disabled>
                            Отправить
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
<?else:?>
    <div class="feedback__top">
        <?/*<div class="main__text">Чтобы оставить комментарий, необходимо <a href="/register/" class="main__text link">зарегистрироваться</a> или <a href="/auth/" class="main__text link">войти</a></div>*/?>
        <div class="main__text">Оставить комментарий может только клиент, который воспользовался услугами данного специалиста </div>
    </div>
<?endif;?>
