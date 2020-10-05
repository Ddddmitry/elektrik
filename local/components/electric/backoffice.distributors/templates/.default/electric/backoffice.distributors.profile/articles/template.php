<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__top">
    <div class="main__text main__text--lg">Статьи <?=$arResult["PROFILE"]["NAME"]?></div>
    <a href="add/" class="btn btn--red">Предложить статью</a>
</div>
<div class="articlesPage-list">
    <?if($arResult["PROFILE"]["ARTICLES"]):?>
        <? foreach ($arResult["PROFILE"]["ARTICLES"] as $arArticle) {?>
            <div class="articlesPage-list__single">
                <div class="articlesPage-list__single-inner">
                    <div class="articlesPage-list__navigation"><!---->
                        <a href="<?=PATH_ARTICLES?>?type=503" class="articlesPage-list__type button2"><?=$arArticle["CATEGORY"]["NAME"]?></a>
                        <div class="articlesPage-list-themes"></div>
                    </div>
                    <a href="<?=$arArticle["DETAIL_PAGE_URL"]?>" class="articlesPage-list__title inhLink"><?=$arArticle["NAME"]?></a>
                    <div class="articlesPage-list__img"><img src="<?=$arArticle["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arArticle["NAME"]?>" class="articlesPage-list__img-tag"></div>
                    <div class="articlesPage-list__info">
                        <div class="articlesPage-helper-data__comment">
                            <a class="inhLink">
                                <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" fill-rule="evenodd" stroke="#7F7F7F" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17v3l-3.5-3H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2z"></path></svg></span>
                                <span>
                                    <?if($arArticle["COMMENTS_NUMBER"] > 0):?>
                                        <?=$arArticle["COMMENTS_NUMBER"]?>
                                    <?else:?>
                                        нет
                                    <?endif;?>
                                </span>
                                <span class="articlesPage-helper-data__comment-text"><?=$arArticle["COMMENTS_NUMBER_TEXT"]?></span>
                            </a>
                        </div>
                        <?if($arArticle["NEW_COMMENTS_NUMBER"]):?>
                            <div class="articlesPage-helper-data__comment">
                                <a href="<?=$arArticle["DETAIL_PAGE_URL"]?>#comments" class="articlesPage-helper-data__comment-new">
                                    <?=$arArticle["NEW_COMMENTS_NUMBER"]?> <?=$arArticle["NEW_COMMENTS_NUMBER_TEXT"]?>
                                </a>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>



        <?}?>
    <?else:?>
        <p>У вас еще статей</p>
    <?endif;?>
</div>
