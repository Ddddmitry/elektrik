<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use \Electric\Helpers\DataHelper;
use \Electric\Location;
use \Electric\Service;
use \Electric\User;

class ContractorsFilter extends BaseComponent
{
    public $input;

    private $ajaxMode;

    private $folder;

    private $obHLDataClassSkills;

    public $requiredModuleList = ['iblock', 'highloadblock', 'sale'];

    private $showFilter, $showHints, $pressSubmit;

    private $bannerCode;

    private $userCity;

    public function onPrepareComponentParams($arParams)
    {
        $this->ajaxMode = isset($arParams["AJAX"]) ? $arParams["AJAX"] : true;
        $this->folder = $arParams["SEF_FOLDER"] ? $arParams["SEF_FOLDER"] : "";
        $this->showHints = isset($arParams["SHOW_HINTS"]) ? $arParams["SHOW_HINTS"] : false;
        $this->showFilter = isset($arParams["SHOW_FILTER"]) ? $arParams["SHOW_FILTER"] : true;
        $this->pressSubmit = isset($arParams["PRESS_SUBMIT"]) ? $arParams["PRESS_SUBMIT"] : true;
        $this->bannerCode = isset($arParams["CODE"]) ? $arParams["CODE"] : "";

        return $arParams;
    }

    /**
     * Получение данных фильтра
     *
     * @return mixed
     */
    protected function getFilterData()
    {
        $arFilterData = array();

        $rsData = $this->obHLDataClassSkills::getList(
            array(
                "select" => array('*'),
                "filter" => array(),
                "order" => array(),
            )
        );
        $arSkills = [];
        while ($arItem = $rsData->Fetch()){
            $arSkills[] = array(
                "ID" => $arItem["ID"],
                "NAME" => $arItem["UF_NAME"],
            );
        }

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "CODE" => "ROOM",
        ];
        $arRooms = [];
        $rsPropertyEnum = \CIBlockPropertyEnum::GetList(["SORT"=>"ASC", "ID"=>"ASC"], $arFilter);
        while($arValue = $rsPropertyEnum->Fetch()) {
            $arRooms[] = [
                "ID" => $arValue["ID"],
                "NAME" => $arValue["VALUE"],
            ];
        }

        $arFilterData["SKILLS"] = $arSkills;
        $arFilterData["ROOMS"] = $arRooms;

        return $arFilterData;
    }

    private function getHLDataClasses() {
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_SKILLS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->obHLDataClassSkills = $obEntity->getDataClass();
    }

    public function executeComponent()
    {
        if ($this->isAjaxRequest()) {
            return false;
        }

        global $USER;

        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        // Обработка SEO-адресов "услуга + город"
        if ($this->input["filter"]) {
            $isSEOFilter = true;
            $arSEOFilter = explode("_", $this->input["filter"]);
            $obService = new Service();
            $arService = $obService->getServiceByCode($arSEOFilter[0]);
            if ($arService) {
                $arSEOService["contractors-search-id"] = $arService["ID"];
                $arSEOService["contractors-search"] = $arService["NAME"];
            } else {
                LocalRedirect(PATH_MARKETPLACE);
            }

        }

        $this->getHLDataClasses();

        // Получение города пользователя
        if ($USER->IsAuthorized()) {

            if ($isSEOFilter) {
                $arCity = Location::getCachedLocationByCode($arSEOFilter[1]);
                if (!$arCity) LocalRedirect(PATH_MARKETPLACE);
                if (strpos($arCity["UF_NAME"], "г ") === 0) {
                    $cityName = substr($arCity["UF_NAME"], 2);
                } else {
                    $cityName = $arCity["UF_NAME"];
                }
                $obUser = new User();
                $obUser->setUserCity(null, ["ID" => $arCity["UF_FIAS_ID"], "NAME" => $cityName]);
                $userCityID = $arCity["UF_FIAS_ID"];
                $userCityName = $cityName;
            } else {
                $arUser = \CUser::GetByID($USER->GetID())->Fetch();
                $userCityID = $arUser["UF_LOCATION"];
                $userCityName = $arUser["UF_LOCATION_NAME"];
            }
        } else {

            if ($isSEOFilter) {
                $arCity = Location::getCachedLocationByCode($arSEOFilter[1]);
                if (!$arCity) LocalRedirect(PATH_MARKETPLACE);
                if (strpos($arCity["UF_NAME"], "г ") === 0) {
                    $cityName = substr($arCity["UF_NAME"], 2);
                } else {
                    $cityName = $arCity["UF_NAME"];
                }
                $userCityID = $arCity["UF_FIAS_ID"];
                $userCityName = $cityName;
            } else {
                $userCityID = $_COOKIE["BITRIX_SM_USER_CITY"];
                $userCityName = $_COOKIE["BITRIX_SM_USER_CITY_NAME"];
            }
        }

        $userCity = [
            "ID" => $userCityID,
            "NAME" => $userCityName,
        ];

        // Если в городе пользователя нет исполнителей - вывести сообщение об этом и
        // предложить ближайшие города с исполнителями
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "PROPERTY_LOCATIONS" => $userCityID,
        ];
        $arSelect = [
            "ID",
            "NAME",
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if (!$dbResult->SelectedRowsCount()) {
            $this->arResult["NO_CONTRACTORS"] = true;

            $obUser = new User;
            $userCityID = $obUser->getUserCityID();

            $this->arResult["CLOSEST_CITIES"] = Location::getClosestCities($userCityID, 4);
        }

        // Получение списка услуг-подсказок для поиска
        $arHints = [];
        $arHintsSpec = [];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_SERVICES,
            "ACTIVE" => "Y",
            "!PROPERTY_IS_HINT" => false,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["NAME", "IBLOCK_ID"]);
        while ($arItem = $dbResult->Fetch()) {
            $arHints[] = [
                "value" => $arItem["NAME"],
            ];
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["AJAX_MODE"] = $this->ajaxMode;
            $this->arResult["SEF_FOLDER"] = $this->folder;
            if ($isSEOFilter) {
                $this->arResult["SELECTED_DATA"] = $arSEOService;
            } else {
                $this->arResult["SELECTED_DATA"] = $this->input->toArray();
            }
            $this->arResult["FILTER_DATA"] = $this->getFilterData();
            $this->arResult["BANNER"] = DataHelper::getBannerData($this->bannerCode);
            $this->arResult["USER_CITY"] = $userCity;
            $this->arResult["HINTS"] = $arHints;
            $this->arResult["HINTS_SPEC"] = $arHintsSpec;
            $this->arResult["SHOW"] = [
                "FILTER" => $this->showFilter,
                "HINTS" => $this->showHints,
                "PRESS_SUBMIT" => $this->pressSubmit,
            ];
            $this->includeComponentTemplate();
        }
    }
}

