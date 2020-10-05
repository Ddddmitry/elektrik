<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["IS_AUTHORIZED"]):?>
    <div class="marketDetail-reviews__comment-lock">
    Чтобы оставить комментарий, необходимо
    <a href="/register/">зарегистрироваться</a> или <a href="/auth/">войти</a>
    </div>
<?else:?>
    <?if($arResult["COMMENT"]):?>
        <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__answer-toggler">
            Отмена
        </a>
    <?endif;?>
    <form action="/api/comments.add/"
          class="marketDetail-reviews__comment-form articlesPage-comments__form"
          id="comment-<?=$arResult["COMMENT"]?>" data-form-comment>
        <input hidden name="comment-article" value="<?=$arResult["ARTICLE"]?>">
        <?if($arResult["COMMENT"]):?>
            <input hidden name="comment-comment" value="<?=$arResult["COMMENT"]?>">
        <?endif;?>
        <div class="formGroup required">
            <div class="formGroup-inner">
                <label for="comment-text-<?=$arResult["COMMENT"]?>">
                    Ваш комментарий
                </label>
                <textarea name="comment-text" id="comment-text-<?=$arResult["COMMENT"]?>" required
                          class="marketDetail-reviews__comment-textarea js-textarea-sm" maxlength="2048" placeholder="Ваш комментарий"></textarea>
            </div>
        </div>
        <div class="formGroup formGroup__bottom disabled">
            <div class="formGroup-inner">
                <button type="submit" name="comment-submit" disabled>
                    Отправить
                </button>
            </div>
        </div>
    </form>
<?endif;?>


