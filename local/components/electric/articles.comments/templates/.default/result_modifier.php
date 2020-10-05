<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

function answersTreeList($answers, $depth){?>
    <?global $APPLICATION;?>
    <? foreach ($answers as $arAnswer) {?>
        <div class="marketDetail-reviews-list__answer js-marketDetail-comment-single">
        <div class="marketDetail-reviews-list__answer-top">
            <div class="marketDetail-reviews-list__author">
                <?=$arAnswer["AUTHOR_NAME"]?>
            </div>
            <div class="marketDetail-reviews-list__date">
                <?=$arAnswer["DATE"]?>
            </div>
        </div>
        <div class="marketDetail-reviews-list__answer-text js-marketDetail-reviews-list__text">
            <?=$arAnswer["PREVIEW_TEXT"]?>
        </div>
        <?if($arAnswer["CAN_EDIT"]):?>
            <div class="marketDetail-reviews-list__form js-marketDetail-reviews-list__edit-form">
                <?$APPLICATION->IncludeComponent('electric:form.comment.edit', '', ['COMMENT'=> $arAnswer["ID"],"TEXT"=>$arAnswer["PREVIEW_TEXT"]]);?>
            </div>
        <?endif;?>
        <div class="marketDetail-reviews-list__answer-bottom js-marketDetail-reviews-list__answer-bottom">
            <?if($depth < (ARTICLE_COMMENTS_MAX_DEPTH-1)):?>
                <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__answer-toggler">
                    Ответить
                </a>
            <?endif;?>
            <?if($arAnswer["CAN_EDIT"]):?>
                <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__edit-toggler">
                    Редактировать
                </a>
                <a href="#" class="marketDetail-reviews-list__bottom-button js-marketDetail-reviews-list__delete" data-id="<?=$arAnswer["ID"]?>">
                    Удалить
                </a>
            <?endif;?>
            <div class="marketDetail-reviews-list__likes">
                <div class="marketDetail-reviews-list__likes-single js-comment-like js-comment-like--like <?if($arAnswer["USER_LIKE"] == "LIKE"):?> active <?endif;?>" data-type="like" data-id="<?=$arAnswer["ID"]?>">
                    <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-like"></use></svg></span>
                    <span class="js-likes-count"><?= $arAnswer["LIKES"]["LIKE"] ?? "0"?></span>
                </div>
                <div class="marketDetail-reviews-list__likes-single js-comment-like js-comment-like--dislike <?if($arAnswer["USER_LIKE"] == "DISLIKE"):?> active <?endif;?>" data-type="dislike" data-id="<?=$arAnswer["ID"]?>">
                    <span class="svg-icon "><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-dislike"></use></svg></span>
                    <span class="js-likes-count"><?= $arAnswer["LIKES"]["DISLIKE"] ?? "0"?></span>
                </div>
            </div>
        </div>

        <?if($depth < (ARTICLE_COMMENTS_MAX_DEPTH-1)):?>
            <div class="marketDetail-reviews-list__answer-form js-marketDetail-reviews-list__answer-form">
                <?$APPLICATION->IncludeComponent('electric:form.comment', '', ['ARTICLE'=> $arAnswer["PROPERTY_ARTICLE_VALUE"],"COMMENT"=>$arAnswer["ID"]]);?>
            </div>
        <?endif;?>
        <?
        if($arAnswer["ANSWERS"]):
            answersTreeList($arAnswer["ANSWERS"],$depth+1);
        endif;
        ?>

    </div>
    <?}?>
<?}