<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->IncludeComponent('electric:articles.search', '', ["CODE"=> "ARTICLES"]);
?>

<div class="articlesPage">

        <div class="articlesPage-inner typicalBlock typicalBlock_two">
                <?$APPLICATION->IncludeComponent('electric:articles.list', '', [
                    'PAGE_SIZE' => ARTICLES_PAGE_SIZE,
                    'CACHE_TYPE' => $arParams["CACHE_TYPE"],
                    'CACHE_TIME' => $arParams["CACHE_TIME"],
                    'SEF_FOLDER' => $arParams["SEF_FOLDER"],
                    'SEF_URL_TEMPLATES' => $arParams["SEF_URL_TEMPLATES"],
                ]);?>
            <div class="articlesPage-helper">
                <?$APPLICATION->IncludeComponent('electric:articles.propose.button', '', []);?>
                <?$APPLICATION->IncludeComponent('electric:articles.filter', '', ["CODE"=> "ARTICLES"]);?>
            </div>
        </div>
</div>

