<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Educations;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;

class  EducationsList extends BaseComponent
{

    private $input;

    public $requiredModuleList = ['iblock', 'search'];

    private $page = 1;
    private $rowsPerPage = 5;
    private $isLastPage = false;
    private $cacheSting = "education_";
    private $foundArticlesTotalCount;
    private $totalItemsCount, $totalPagesCount;

    private $search;
    private $arSearchResult = [];
    private $filterByType;
    private $filterByMonth;
    private $filterByYear;
    private $filterByCity;
    private $filterByTheme;
    private $filterByAuthor;
    private $filterByFormat;

    private $addFilter;
    private $arIblock = [];
    private $pagerTemplate;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        if ($arParams['PAGE_SIZE']) {
            $this->rowsPerPage = $arParams['PAGE_SIZE'];
        }

        $this->arIblock = [
            "SERVICES" => $arParams['IBLOCK_ID'],
            "TYPES" => $arParams['TYPE_IBLOCK_ID'],
            "THEMES" => $arParams['THEMES_IBLOCK_ID'],
        ];

        $this->pagerTemplate = $arParams["PAGER_TEMPLATE"] ? $arParams["PAGER_TEMPLATE"] : "electric";

        $this->addFilter = $arParams["FILTER"];
        return $arParams;
    }

    /**
     * Получение списка обучалок
     *
     * @return array
     * @throws \Exception
     */
    protected function getEducations()
    {

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE','DETAIL_PAGE_URL'];
        $arOrder = [
            "SORT" => "ASC",
            "DATE_CREATE" => "DESC",
        ];

        // Добавление фильтрации
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $this->arIblock["SERVICES"],
            "PROPERTY_IS_ARCHIVE" => false
        ];
        if ($this->filterByType) {
            $arFilter["PROPERTY_TYPE"] = $this->filterByType;
        }
        if ($this->filterByTheme) {
            $arFilter["PROPERTY_THEME"] = $this->filterByTheme;
        }
        if ($this->filterByFormat) {
            $arFilter["PROPERTY_FORMAT"] = $this->filterByFormat;
        }
        if ($this->filterByAuthor) {
            $arFilter["PROPERTY_DISTRIBUTOR"] = $this->filterByAuthor;
        }
        if ($this->filterByCity) {
            $arFilteredByLocation = $this->filterByMultipleProperty(PROPERTY_EDUCATIONS_LOCATIONS, $this->filterByCity);
            if ($arFilter["ID"]) {
                $arFilter["ID"] = array_intersect($arFilter["ID"], $arFilteredByLocation);
            } else {
                $arFilter["ID"] = $arFilteredByLocation;
            }
        }

        if ($this->filterByMonth && $this->filterByYear) {
            //$arFilter[">=DATE_ACTIVE_FROM"] = $this->filterByMonth;
            $arFilter[">=DATE_ACTIVE_FROM"] = "01.".$this->filterByMonth.".".$this->filterByYear." 00:00:00";
            $arFilter["<=DATE_ACTIVE_FROM"] = "31.".$this->filterByMonth.".".$this->filterByYear." 23:59:59";
        }elseif($this->filterByMonth && !$this->filterByYear){
            $arFilter[">=DATE_ACTIVE_FROM"] = "01.".$this->filterByMonth.".".date("Y")." 00:00:00";
            $arFilter["<=DATE_ACTIVE_FROM"] = "31.".$this->filterByMonth.".".date("Y")." 23:59:59";
        }elseif(!$this->filterByMonth && $this->filterByYear){
            $arFilter[">=DATE_ACTIVE_FROM"] = "01.1.".$this->filterByYear." 00:00:00";
            $arFilter["<=DATE_ACTIVE_FROM"] = "31.12.".$this->filterByYear." 23:59:59";
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
        $this->totalPagesCount = $pagesCount;
        if (!$pagesCount || $pagesCount == 1 || $arNavData["iNumPage"] == $pagesCount) {
            $this->isLastPage = true;
        };

        $arItems = [];
        $arTypesID = [];
        while ($arItem = $dbResult->fetch()) {

            $arItem["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arItem['DETAIL_PAGE_URL'], $arItem, false, 'E');

            // Получение изображений
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PREVIEW_PICTURE'] = \CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
            }
            if ($arItem['DETAIL_PICTURE']) {
                $arItem['DETAIL_PICTURE'] = \CFile::GetFileArray($arItem['DETAIL_PICTURE']);
            }

            $arItem["DATE"] = DataHelper::getFormattedDate($arItem["ACTIVE_FROM"]);
            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arItem['IBLOCK_ID'],
                $arItem['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arItem['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $arTypesID[] = $arItem["PROPERTIES"]["TYPE"]["VALUE"];
            $arThemesID[] = $arItem["PROPERTIES"]["THEMES"]["VALUE"];
            $arVendorsID[] = $arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE"];

            $arItems[] = $arItem;
        }

        // Получение категорий
        $arSelect = [
            'ID',
            'NAME',
        ];
        $arFilter = [
            'IBLOCK_ID' => $this->arIblock["TYPES"]
        ];
        if ($arTypesID) {
            $arFilter["ID"] = array_unique($arTypesID);
        }
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTypes = [];
        while ($arType = $dbResult->GetNext()) {
            $arTypes[$arType["ID"]] = $arType["NAME"];
        }
        // Добавление дополнительных данных в массив сервисов
        foreach ($arItems as &$arItem) {
            $arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arTypes[$arItem["PROPERTIES"]["TYPE"]["VALUE"]];
        }

        // Получение тем
        $arSelect = [
            'ID',
            'NAME',
        ];
        $arFilter = [
            'IBLOCK_ID' => $this->arIblock["THEMES"]
        ];
        if ($arThemesID) {
            $arFilter["ID"] = array_unique($arThemesID);
        }
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arThemes = [];
        while ($arTheme = $dbResult->GetNext()) {
            $arThemes[$arTheme["ID"]] = $arTheme["NAME"];
        }

        // Добавление дополнительных данных в массив сервисов
        foreach ($arItems as &$arItem) {
            $arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arThemes[$arItem["PROPERTIES"]["TYPE"]["VALUE"]];
        }


        // Получение авторов
        $arSelect = ['ID','NAME','PREVIEW_PICTURE'];
        $arFilter = ['IBLOCK_ID' => IBLOCK_VENDORS,];
        if ($arVendorsID) {
            $arFilter["ID"] = array_unique($arVendorsID);
        }
        $arVendors = [];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arVendor = $dbResult->GetNext()) {
            if ($arVendor['PREVIEW_PICTURE']) {
                $arVendor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arVendor['PREVIEW_PICTURE']);
            }
            $arVendors[$arVendor["ID"]] = $arVendor;
        }
        $arFilter = ['IBLOCK_ID' => IBLOCK_DISTRIBUTORS];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arVendor = $dbResult->GetNext()) {
            if ($arVendor['PREVIEW_PICTURE']) {
                $arVendor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arVendor['PREVIEW_PICTURE']);
            }
            $arVendors[$arVendor["ID"]] = $arVendor;
        }

        // Добавление дополнительных данных в массив сервисов
        foreach ($arItems as &$arItem) {
            $arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_NAME"] = $arVendors[$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE"]]["NAME"];
            $arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE_PREVIEW_PICTURE"] = $arVendors[$arItem["PROPERTIES"]["DISTRIBUTOR"]["VALUE"]]["PREVIEW_PICTURE"];
        }



        $arResult = [
            "ITEMS" => $arItems,
            "TYPES" => $arTypes,
            "THEMES" => $arThemes,
            "DISTRIBUTOR" => $arVendors,
        ];


        $obEducation = new Educations();
        $this->totalItemsCount = $obEducation->getTotalCount();

       /* $NAV_STRING = $dbResult->GetPageNavStringEx($navComponentObject, '', $this->pagerTemplate, 'N');
        echo $NAV_STRING;*/


        return $arResult;
    }

    protected function setFilters(){
        if ($this->input["page"]) {
            $this->page = $this->input["page"];
            $this->cacheSting .= $this->page;
        }

        // Установки фильтрации
        if(isset($this->input["type"])){
            $this->setNoindex();
            if ($this->input["type"]) {
                $this->filterByType = $this->input["type"];
                $this->cacheSting .= $this->input["type"];
            }
        }

        if ($this->input["month"]) {
            $this->filterByMonth = $this->input["month"];
            $this->cacheSting .= $this->input["month"];
        }
        if ($this->input["year"]) {
            $this->filterByYear = $this->input["year"];
            $this->cacheSting .= $this->input["year"];
        }
        if ($this->input["city"]) {
            $this->filterByCity = $this->input["city"];
            $this->cacheSting .= $this->input["city"];
        }
        if ($this->input["theme"]) {
            $this->filterByTheme = $this->input["theme"];
            $this->cacheSting .= $this->input["theme"];
        }
        if ($this->input["author"]) {
            $this->filterByAuthor = $this->input["author"];
            $this->cacheSting .= $this->input["author"];
        }
        if ($this->input["format"]) {
            $this->filterByFormat = $this->input["format"];
            $this->cacheSting .= $this->input["format"];
        }

        if ($this->input["filter"]) {
            $this->cacheSting .= "filter";
        }

    }

    private function filterByMultipleProperty($propertyID, $filterValue) {
        global $DB;

        $query = "
            SELECT DISTINCT
                `iblock_element_property`.`IBLOCK_ELEMENT_ID` AS `IBLOCK_ELEMENT_ID`
            FROM `b_iblock_element_property` `iblock_element_property` 
            
            WHERE 
                `iblock_element_property`.`IBLOCK_PROPERTY_ID` = ".$propertyID."
                AND
                `iblock_element_property`.`VALUE` = '".$filterValue."'
            ";


        $rsQueryResult = $DB->Query($query, false, "");
        $arQueryResult = [];
        while ($arRow = $rsQueryResult->fetch()) {
            $arQueryResult[] = $arRow["IBLOCK_ELEMENT_ID"];
        }

        return $arQueryResult;
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

        $this->setFilters();

        // Установки поиска
        if ($this->input["search"]) {
            $this->search = $this->input["search"];
            $obSearch = new \CSearch;
            $obSearch->Search([
                "QUERY" => $this->search,
                "SITE_ID" => SITE_ID,
                "MODULE_ID" => "iblock",
                "PARAM2" => IBLOCK_EDUCATIONS,
            ]);
            while($arResult = $obSearch->GetNext()) {
                $this->arSearchResult[] = $arResult["ITEM_ID"];
            }
        }

        // Информация о поиске и его результатах
        if ($this->search) {
            $arSearchData = [
                "IS_SEARCH" => true,
                "PHRASE" => $this->search,
                "COUNT" => $this->foundArticlesTotalCount . " " . DataHelper::getWordForm("articles", $this->foundArticlesTotalCount),
            ];
            $this->cacheSting .= "_is_search";
        } else {
            $arSearchData = [
                "IS_SEARCH" => false
            ];
        }

        if ($isAjax) $this->cacheSting .= "_is_ajax";
        if ($isAjax) $this->restartBuffer();

        if ($this->StartResultCache(false, $this->cacheSting)) {
            // Получение мероприятий
            $arResult = $this->getEducations();

            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["IS_FILTER"] = $this->input['filter'];
            $this->arResult["TOTAL_COUNT"] = $this->totalItemsCount;
            $this->arResult["TOTAL_PAGES_COUNT"] = $this->totalPagesCount;
            $this->arResult["ITEMS"] = $arResult["ITEMS"];
            $this->arResult["TYPE"] = $arResult["TYPES"];
            $this->arResult["THEMES"] = $arResult["THEMES"];
            $this->arResult["DISTRIBUTOR"] = $arResult["DISTRIBUTOR"];
            $this->arResult["SEARCH"] = $arSearchData;
            $this->arResult["CUR_PAGE"] = $this->page;
            $this->arResult["IS_LAST_PAGE"] = $this->isLastPage;
            $this->arResult["SEF_FOLDER"] = $this->folder;
            $this->includeComponentTemplate();
        }

        if ($isAjax) die();
    }
}
