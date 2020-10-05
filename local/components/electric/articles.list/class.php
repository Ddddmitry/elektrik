<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Article;
use Electric\Helpers\DataHelper;

class  Articles extends BaseComponent
{
    private $input;

    public $requiredModuleList = ['iblock', 'search'];

    private $page = 1;
    private $rowsPerPage = 5;
    private $isLastPage = false;

    private $foundArticlesTotalCount;
    private $totalArticlesCount;

    private $search;
    private $arSearchResult = [];

    private $filterByType, $filterByTag, $addFilter;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        if ($arParams['PAGE_SIZE']) {
            $this->rowsPerPage = $arParams['PAGE_SIZE'];
        }
        $this->addFilter = $arParams["FILTER"];
        return $arParams;
    }


    /**
     * Получение списка статей
     *
     * @return array
     * @throws \Exception
     */
    protected function getArticles()
    {
        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PAGE_URL'];
        $arOrder = [
            "ACTIVE_FROM" => "DESC",
        ];

        // Получение элемента категории "Предложенные статьи" по символьному коду
        $arTypeProposed = \CIBlockElement::GetList([], ["IBLOCK_ID" => IBLOCK_ARTICLES_TYPES, "=CODE" => ARTICLE_TYPE_PROPOSED_CODE], false, false, ["ID"])->Fetch();

        // Добавление фильтрации
        $arFilter = [
            "!PROPERTY_TYPE" => $arTypeProposed["ID"],
            "IBLOCK_ID" => IBLOCK_ARTICLES
        ];
        if ($this->filterByType) {
            $arFilter["PROPERTY_TYPE"] = $this->filterByType;
        }
        if ($this->filterByTag) {
            $arFilter["PROPERTY_TAGS"] = $this->filterByTag;
        }
        if($this->addFilter){
            $arFilter = array_merge($arFilter,$this->addFilter);
        }

        // Фильтрация по результатам поиска
        if ($this->search) {
            $arFilter["ID"] = $this->arSearchResult ? $this->arSearchResult : false;
        }

        $arNavData = [
            "nPageSize" => $this->rowsPerPage,
            "iNumPage" => $this->page,
        ];


        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavData, $arSelect);

        $this->foundArticlesTotalCount = $dbResult->NavRecordCount;
        $pagesCount = $dbResult->NavPageCount;
        if (!$pagesCount || $pagesCount == 1 || $arNavData["iNumPage"] == $pagesCount) {
            $this->isLastPage = true;
        };

        $arArticles = [];
        $arTypesID = [];
        $arUsersID = [];
        while ($arArticle = $dbResult->fetch()) {
            // Получение URL детальной страницы
            //$urlTemplate = $this->arParams["SEF_FOLDER"] . $this->arParams["SEF_URL_TEMPLATES"]["detail"];
            $urlTemplate = $arArticle["DETAIL_PAGE_URL"];
            $arArticle["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($urlTemplate, $arArticle);

            // Получение изображения
            if ($arArticle['PREVIEW_PICTURE']) {
                $arArticle['PREVIEW_PICTURE'] = \CFile::GetFileArray($arArticle['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arArticle["DATE"] = DataHelper::getFormattedDate($arArticle["ACTIVE_FROM"]);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arArticle['IBLOCK_ID'],
                $arArticle['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "TAGS") {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = $arProperty["VALUE"];
                } else {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }

            $arTypesID[] = $arArticle["PROPERTIES"]["TYPE"]["VALUE"];
            $arUsersID[] = $arArticle["PROPERTIES"]["USER"]["VALUE"];

            $arArticles[] = $arArticle;
        }

        $obArticle = new Article();

        $this->totalArticlesCount = $obArticle->getTotalCount();

        // Получение категорий статей
        $arTypes = $obArticle->getArticlesTypes($arTypesID);

        // Получение авторов статей
        $arAuthors = [];
        $arFilter = [
            "ID" => array_unique($arUsersID),
        ];
        $dbResult = \Bitrix\Main\UserTable::getList([
            "select" => Array("ID", "NAME", "LAST_NAME"),
            "filter" => $arFilter,
        ]);
        while ($arAuthor = $dbResult->fetch()) {
            $arAuthors[$arAuthor["ID"]] = $arAuthor;
        }

        // Добавление дополнительных данных в массив статей
        foreach ($arArticles as &$arArticle) {
            $arArticle["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arTypes[$arArticle["PROPERTIES"]["TYPE"]["VALUE"]];
            $arAuthorID = $arArticle["PROPERTIES"]["USER"]["VALUE"];
            $arArticle["PROPERTIES"]["USER"]["VALUE_NAME"] = $arAuthors[$arAuthorID]["NAME"] . " " . $arAuthors[$arAuthorID]["LAST_NAME"];
        }

        return $arArticles;
    }

    /**
     * Устанавливаем Meta параметры страницы
     */
    protected function setNoindex(){
        global $APPLICATION;
        $APPLICATION->AddHeadString('<metaname="googlebot" content="noindex">',true);

    }


    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();
        $isAjax = $this->isAjaxRequest() ? true : false;

        if ($this->input["page"]) {
            $this->page = $this->input["page"];
        }

        // Установки поиска
        if ($this->input["search"]) {
            $this->search = $this->input["search"];
            $obSearch = new \CSearch;
            $obSearch->Search([
                "QUERY" => $this->search,
                "SITE_ID" => SITE_ID,
                "MODULE_ID" => "iblock",
                "PARAM2" => IBLOCK_ARTICLES,
            ]);
            while($arResult = $obSearch->GetNext()) {
                $this->arSearchResult[] = $arResult["ITEM_ID"];
            }
        }

        // Установки фильтрации
        if(isset($this->input["type"])){
            $this->setNoindex();
            if ($this->input["type"]) {
                $this->filterByType = $this->input["type"];
            }
        }

        if ($this->input["tag"]) {
            $this->filterByTag = $this->input["tag"];
        }

        // Получение статей
        $arArticles = $this->getArticles();

        // Информация о поиске и его результатах
        if ($this->search) {
            $arSearchData = [
                "IS_SEARCH" => true,
                "PHRASE" => $this->search,
                "COUNT" => $this->foundArticlesTotalCount . " " . DataHelper::getWordForm("articles", $this->foundArticlesTotalCount),
            ];
        } else {
            $arSearchData = [
                "IS_SEARCH" => false
            ];
        }



        if ($this->StartResultCache(false, [$this->page])) {
            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["TOTAL_COUNT"] = $this->totalArticlesCount;
            $this->arResult["ITEMS"] = $arArticles;
            $this->arResult["SEARCH"] = $arSearchData;
            $this->arResult["IS_LAST_PAGE"] = $this->isLastPage;
            $this->arResult["SEF_FOLDER"] = $this->folder;
            if ($isAjax) $this->restartBuffer();
            $this->includeComponentTemplate();
            if ($isAjax) die();
        }
    }
}

