<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="breadcrumbs breadcrumbs_padding">
    <div class="breadcrumbs-inner typicalBlock typicalBlock_two">
        <div class="breadcrumbs-list">
            <a href="<?=$arResult["BACK_URL"]?>" class="breadcrumbs-list__single">
                <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-arrow-down"></use></svg></span>
                <span class="breadcrumbs-list__single-text">К списку статей</span>
            </a>
        </div>
    </div>
</div>

<div class="articlesPage articlesPage_detail">
    <div class="articlesPage-inner typicalBlock typicalBlock_two">

        <div class="articlesPage-main">
            <h1 class="articlesPage__title">
                <?=$arResult['ARTICLE']["NAME"];?>
            </h1>
            <div class="htmlArea">
                <?=$arResult['ARTICLE']["DETAIL_TEXT"];?>
            </div>
            <div class="articlesPage-helper articlesPage-helper_detail articlesPage-helper_mobile">
                <div class="articlesPage-helper-articles articlesPage-helper__block">
                    <div class="articlesPage-helper__title">
                        Похожие статьи
                    </div>
                    <div class="articlesPage-helper-articles-list js-articlesPage-helper-articles">
                        <?foreach ($arResult["ARTICLE"]["RELATED_ARTICLES"] as $arRelArticle):?>
                            <div class="articlesPage-helper-articles-list__single">
                                <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arRelArticle["PROPERTY_TYPE_VALUE"]?>" class="button2">
                                    <?=$arRelArticle["PROPERTY_TYPE_VALUE_NAME"]?>
                                </a>
                                <div class="articlesPage-helper-articles-list__title">
                                    <a href="<?=$arRelArticle["DETAIL_PAGE_LINK"]?>" class="inhLink">
                                        <?=$arRelArticle["NAME"]?>
                                    </a>
                                </div>
                                <?if($arRelArticle["PREVIEW_TEXT"]):?>
                                    <div class="articlesPage-helper-articles-list__description">
                                        <?if(strlen($arRelArticle["PREVIEW_TEXT"]) > 85):?>
                                            <?
                                            $words = explode(" ",$arRelArticle["PREVIEW_TEXT"]);
                                            for($i=0; $i<RELATED_ARTICLE_PREVIEW_TEXT_LIMIT; $i++):
                                                echo $words[$i]." ";
                                            endfor;
                                            echo "...";
                                            ?>
                                        <?else:?>
                                            <?=$arRelArticle["PREVIEW_TEXT"]?>
                                        <?endif;?>
                                    </div>
                                <?endif;?>

                                <div class="articlesPage-helper-articles-list__author">
                                    <?=$arRelArticle["PROPERTY_USER_VALUE_NAME"]?>
                                </div>
                                <div class="articlesPage-helper-articles-list__date">
                                    <?=$arRelArticle["DATE"]?>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>

            <?if($arResult["ARTICLE"]["BANNER"]):?>
                <div class="articlesPage-list__banner">
                    <?if($arResult["ARTICLE"]["BANNER"]["LINK"]):?>
                        <a target="_blank" href="<?=$arResult["ARTICLE"]["BANNER"]["LINK"]?>">
                    <?endif;?>
                            <img src="<?=$arResult["ARTICLE"]["BANNER"]["SRC"]?>" alt="">
                    <?if($arResult["ARTICLE"]["BANNER"]["LINK"]):?>
                        </a>
                    <?endif;?>
                </div>
            <?endif;?>

            <div class="articlesPage-comments" id="comments">

                <div class="articlesPage-comments-top">
                    <div class="marketDetail__title">
                        Комментарии
                        <span class="marketDetail__title-accent">
                            <?=$arResult["ARTICLE"]["COMMENTS"]["COUNT"]?>
                        </span>
                    </div>
                    <?
                    $APPLICATION->IncludeComponent('electric:form.comment', '', ['ARTICLE'=> $arResult['ARTICLE']["ID"],]);
                    ?>

                </div>
                <?$APPLICATION->IncludeComponent('electric:articles.comments', '', ['ARTICLE'=> $arResult['ARTICLE']["ID"],'PAGE_SIZE'=>ARTICLE_COMMENTS_PAGE_SIZE]);?>

            </div>

        </div>


        <div class="articlesPage-helper articlesPage-helper_detail">
            <div class="articlesPage-helper-category articlesPage-helper-category_detail articlesPage-helper__block">
                <div class="articlesPage-helper__title">
                    Тип статьи
                </div>
                <div class="articlesPage-helper-category-list">
                    <div class="articlesPage-helper-category-list__single">
                        <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arResult['ARTICLE']["PROPERTIES"]["TYPE"]["VALUE"]?>"
                           class="button2 articlesPage-helper-category-list__single-button">
                            <?=$arResult['ARTICLE']["PROPERTIES"]["TYPE"]["VALUE_NAME"]?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="articlesPage__separator"></div>
            <div class="articlesPage-helper-themes articlesPage-helper-themes_detail articlesPage-helper__block">
                <div class="articlesPage-helper__title">
                    Темы
                </div>
                <div class="articlesPage-helper-themes-list">
                    <? foreach ($arResult['ARTICLE']["PROPERTIES"]["TAGS"]["VALUE"] as $TAG) {?>
                        <div class="articlesPage-helper-themes-list__single">
                            <span class="articlesPage-helper-themes-list__single-button">
                                <?=$TAG?>
                            </span>
                        </div>
                    <?}?>
                </div>
            </div>
            <div class="articlesPage__separator"></div>
            <div class="articlesPage-helper-data articlesPage-helper__block">
                <div class="articlesPage-helper-data__author">
                    <?=$arResult['ARTICLE']["PROPERTIES"]["USER"]["VALUE_NAME"]?>
                </div>
                <div class="articlesPage-helper-data__date">
                    <?=$arResult['ARTICLE']["DATE"]?>
                </div>
                <div class="clear articlesPage-helper-data__clear"></div>
                <div class="articlesPage-helper-data__comment">
                    <a href="#comments" class="inhLink">
                        <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-comment"></use></svg></span>
                        <?=$arResult["ARTICLE"]["COMMENTS"]["COUNT"]?> <span class="articlesPage-helper-data__comment-text"><?=$arResult["ARTICLE"]["COMMENTS"]["COUNT_WORD"]?></span>
                    </a>
                </div>
            </div>
            <div class="clear"></div>

            <?if($arResult["ARTICLE"]["RELATED_ARTICLES"]):?>
                <div class="articlesPage-helper-articles articlesPage-helper__block">
                    <div class="articlesPage-helper__title">
                        Похожие статьи
                    </div>
                    <div class="articlesPage-helper-articles-list">
                        <? foreach ($arResult["ARTICLE"]["RELATED_ARTICLES"] as $arItem) {?>
                            <div class="articlesPage-helper-articles-list__single">
                                <a href="<?=$arResult["SEF_FOLDER"]?>?type=<?=$arItem["PROPERTY_TYPE_VALUE"]?>" class="button2">
                                    <?=$arItem["PROPERTY_TYPE_VALUE_NAME"]?>
                                </a>
                                <div class="articlesPage-helper-articles-list__title">
                                    <a href="<?=$arItem["DETAIL_PAGE_LINK"]?>"><?=$arItem["NAME"]?></a>
                                </div>
                                <div class="articlesPage-helper-articles-list__description">
                                    <?if(strlen($arItem["PREVIEW_TEXT"]) > 85):?>
                                        <?
                                        $words = explode(" ",$arItem["PREVIEW_TEXT"]);
                                        for($i=0; $i<RELATED_ARTICLE_PREVIEW_TEXT_LIMIT; $i++):
                                            echo $words[$i]." ";
                                        endfor;
                                        echo "...";
                                        ?>
                                    <?else:?>
                                        <?=$arItem["PREVIEW_TEXT"]?>
                                    <?endif;?>
                                </div>
                                <div class="articlesPage-helper-articles-list__author">
                                    <?=$arItem["PROPERTY_USER_VALUE_NAME"]?>
                                </div>
                                <div class="articlesPage-helper-articles-list__date">
                                    <?=$arItem["DATE"]?>
                                </div>
                            </div>
                        <?}?>

                    </div>
                </div>
            <?endif;?>

        </div>
    </div>
</div>
