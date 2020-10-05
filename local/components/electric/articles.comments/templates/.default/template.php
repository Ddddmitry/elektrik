<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?if(!$arResult["IS_AJAX"]):?>
<div class="marketDetail-reviews-list marketDetail-reviews-list_comments js-comments-list"
     data-article="<?=$arResult["COMMENTS_DATA"]["ARTICLE"]?>"
    data-page="1"
     <?if($arResult["MARK_AS_VIEWED"]):?>mark-as-viewed="<?=$arResult["COMMENTS_DATA"]["ARTICLE"]?>"<?endif;?>>
<?endif;?>

    <? foreach ($arResult["COMMENTS_DATA"]["ITEMS"] as $arItem) {?>

        <div class="marketDetail-reviews-list__single js-marketDetail-comment-single">
            <div class="marketDetail-reviews-list__top">
                <div class="marketDetail-reviews-list__author">
                    <?=$arItem["AUTHOR_NAME"]?>
                </div>
                <div class="marketDetail-reviews-list__date">
                    <?=$arItem["DATE"]?>
                </div>
            </div>
            <div class="marketDetail-reviews-list__text js-marketDetail-reviews-list__text">
                <?=$arItem["PREVIEW_TEXT"]?>
            </div>
            <?if($arItem["CAN_EDIT"]):?>
                <div class="marketDetail-reviews-list__form js-marketDetail-reviews-list__edit-form">
                    <?$APPLICATION->IncludeComponent('electric:form.comment.edit', '', ['COMMENT'=> $arItem["ID"],"TEXT"=>$arItem["PREVIEW_TEXT"]]);?>
                </div>
            <?endif;?>
            <div class="marketDetail-reviews-list__bottom js-marketDetail-reviews-list__answer-bottom">
                <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__answer-toggler">
                    Ответить
                </a>
                <?if($arItem["CAN_EDIT"]):?>
                    <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__edit-toggler">
                        Редактировать
                    </a>
                    <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__delete" data-id="<?=$arItem["ID"]?>">
                        Удалить
                    </a>
                <?endif;?>
                <div class="marketDetail-reviews-list__likes">
                    <div class="marketDetail-reviews-list__likes-single js-comment-like js-comment-like--like <?if($arItem["USER_LIKE"] == "LIKE"):?> active <?endif;?>" data-type="like" data-id="<?=$arItem["ID"]?>">
                        <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-like"></use></svg></span>
                        <span class="js-likes-count"><?= $arItem["LIKES"]["LIKE"] ?? "0"?></span>
                    </div>
                    <div class="marketDetail-reviews-list__likes-single js-comment-like js-comment-like--dislike <?if($arItem["USER_LIKE"] == "DISLIKE"):?> active <?endif;?>" data-type="dislike" data-id="<?=$arItem["ID"]?>">
                        <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-dislike"></use></svg></span>
                        <span class="js-likes-count"><?= $arItem["LIKES"]["DISLIKE"] ?? "0"?></span>
                    </div>
                </div>
            </div>

            <div class="marketDetail-reviews-list__form js-marketDetail-reviews-list__answer-form">
                <?$APPLICATION->IncludeComponent('electric:form.comment', '', ['ARTICLE'=> $arItem["PROPERTY_ARTICLE_VALUE"],"COMMENT"=>$arItem["ID"]]);?>
            </div>
            <?

            answersTreeList($arItem["ANSWERS"],1);

            ?>


        </div>
    <?}?>


<?if(!$arResult["IS_AJAX"]):?>
</div>

<div class="articlesPage-comments-bottom js-comments-more" id="js-comments-more-button">
    <a href="#" class="button">
        Показать ещё
    </a>
</div>
<?endif;?>

<?if($arResult["COMMENTS_DATA"]["NAV"]["IS_LAST_PAGE"]):?>
        <script>
            document.getElementById('js-comments-more-button').style.display = "none";
        </script>
<?else:?>
        <script>
            document.getElementById('js-comments-more-button').style.display = "block";
        </script>
<?endif;?>
