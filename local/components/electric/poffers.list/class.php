<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Events;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;
use Electric\User;

class  PoffersList extends BaseComponent
{

    private $input;

    public $requiredModuleList = ['iblock', 'search'];

    private $page = 1;
    private $rowsPerPage = 5;
    private $isLastPage = false;
    private $cacheSting = "poffers_";
    private $foundArticlesTotalCount;
    private $totalArticlesCount, $totalPagesCount;

    private $search;
    private $arSearchResult = [];
    private $filterByType;

    private $addFilter;
    private $arIblock = [];


    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        if ($arParams['PAGE_SIZE']) {
            $this->rowsPerPage = $arParams['PAGE_SIZE'];
        }

        $this->arIblock = [
            "SERVICES" => $arParams['IBLOCK_ID'],
            "TYPES" => $arParams['TYPE_IBLOCK_ID'],
        ];

        return $arParams;
    }

    /**
     * Получение списка партнерских предложений
     *
     * @return array
     * @throws \Exception
     */
    protected function getPoffers()
    {

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PAGE_URL'];
        $arOrder = [
            "SORT" => "ASC",
            "DATE_CREATE" => "DESC",
        ];

        // Добавление фильтрации
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $this->arIblock["SERVICES"],
        ];
        if ($this->filterByType) {
            $arTypes = explode(",",$this->filterByType);
            $arFilter["PROPERTY_TYPE"] = $arTypes;
        }

        if($this->addFilter){
            $arFilter = array_merge($arFilter,$this->addFilter);
        }

        $arNavData = [
            "nPageSize" => $this->rowsPerPage,
            "iNumPage" => $this->page,
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavData, $arSelect);

        $this->foundArticlesTotalCount = $dbResult->NavRecordCount;
        $this->totalPagesCount = $dbResult->NavPageCount;

        if (!$this->totalPagesCount || $this->totalPagesCount == 1 || $arNavData["iNumPage"] == $this->totalPagesCount) {
            $this->isLastPage = true;
        };

        $arItems = [];
        $arTypesID = [];
        while ($arItem = $dbResult->fetch()) {

            $urlTemplate = $arItem["DETAIL_PAGE_URL"];
            $arItem["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($urlTemplate, $arItem);

            // Получение изображений
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PREVIEW_PICTURE'] = \CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
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

            $arItems[] = $arItem;
        }

        // Получение категорий сервисов
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


        if($this->arResult["IS_AUTHORIZED"]){
            $arPoffersOrderRequests = [];
            $allIDs = array_column($arItems,"ID");
            $dbResult = \CIBlockElement::GetList([],
                ["IBLOCK_ID"=>IBLOCK_PARTNERS_OFFERS_ORDERS,
                    "PROPERTY_USER" => $this->arResult['USER_ID'],
                    "PROPERTY_POFFER_ID" => $allIDs], false, false, ["ID","PROPERTY_POFFER_ID"]);
            while ($arPoffersOrderRequest = $dbResult->GetNext()) {
                $arPoffersOrderRequests[$arPoffersOrderRequest["PROPERTY_POFFER_ID_VALUE"]] = true;
            }

        }


        // Добавление дополнительных данных в массив сервисов
        foreach ($arItems as &$arItem) {
            $arItem["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arTypes[$arItem["PROPERTIES"]["TYPE"]["VALUE"]];
            $arItem["POFFER_ORDER_REQUEST"] = $arPoffersOrderRequests[$arItem["ID"]];
        }

        $arResult = [
            "ITEMS" => $arItems,
            "TYPES" => $arTypes,
        ];

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

        if ($this->input["filter"]) {
            $this->cacheSting .= "filter";
        }

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
                "PARAM2" => IBLOCK_ARTICLES,
            ]);
            while($arResult = $obSearch->GetNext()) {
                $this->arSearchResult[] = $arResult["ITEM_ID"];
            }
        }

        if ($isAjax) $this->cacheSting .= "_is_ajax";
        if ($isAjax) $this->restartBuffer();


        // todo: Включить кэширование, и показ кнопки для акции только для авторизованных пользователей.
        global $USER;
        $this->arResult["IS_AUTHORIZED"] = $USER->IsAuthorized();
        if($this->arResult["IS_AUTHORIZED"]){
            $obUser = new User();
            $this->arResult['USER_ID'] = $USER->GetId();
            $this->arResult['IS_SERTIFIED'] = $obUser->isCertified();
            $this->arResult['USER_DATA'] = $obUser->getClientData();
            $this->arResult['USER_TYPE'] = $obUser->getType();
        }

        //if ($this->StartResultCache(false, $this->cacheSting)) {

            // Получение предложений
            $arResult = $this->getPoffers();

            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["IS_FILTER"] = $this->input['filter'];
            $this->arResult["TOTAL_COUNT"] = $this->totalArticlesCount;
            $this->arResult["TOTAL_PAGES_COUNT"] = $this->totalPagesCount;
            $this->arResult["ITEMS"] = $arResult["ITEMS"];
            $this->arResult["TYPE"] = $arResult["TYPES"];
            $this->arResult["CUR_PAGE"] = $this->page;
            $this->arResult["IS_LAST_PAGE"] = $this->isLastPage;
            $this->arResult["SEF_FOLDER"] = $this->folder;
            $this->includeComponentTemplate();
        //}


        if ($isAjax) die();
    }
}
