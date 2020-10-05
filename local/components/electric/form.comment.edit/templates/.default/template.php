<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__edit-toggler">
    Отмена
</a>
<form action="/api/comments.edit/" class="marketDetail-reviews__comment-form articlesPage-comments__form" id="comment-<?=$arResult["COMMENT"]?>" data-form-comment-edit>
    <?if($arResult["COMMENT"]):?>
        <input hidden name="comment" value="<?=$arResult["COMMENT"]?>">
    <?endif;?>
    <div class="formGroup required">
        <div class="formGroup-inner">
            <textarea name="comment-text" id="comment-text-<?=$arResult["COMMENT"]?>" required
                      class="marketDetail-reviews__comment-textarea js-textarea-sm" maxlength="2048" placeholder="Редактировать комментарий"><?=$arResult["TEXT"]?></textarea>
        </div>
    </div>
    <div class="formGroup formGroup__bottom disabled">
        <div class="formGroup-inner">
            <button type="submit" name="comment-submit" disabled>
                Сохранить
            </button>
        </div>
    </div>
</form>


