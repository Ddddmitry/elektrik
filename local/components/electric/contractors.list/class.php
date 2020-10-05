<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Contractor;
use \Electric\Helpers\DataHelper;
use Electric\Location;
use Electric\Service;

class ContractorsList extends BaseComponent
{
    private $input;

    private $userID;

    public $requiredModuleList = ['iblock', 'search', 'sale'];

    private $arSort = [
        "BY" => "price",
        "ORDER" => "asc",
    ];

    private $page = 1;
    private $rowsPerPage = 5;
    private $pagesCount;
    private $isLastPage = false;

    private $search;
    private $arSearchResult = [];
    private $isServicesFound = false;

    private $filterByLocation, $filterByService, $filterByType,
        $filterBySkill, $filterByRoom, $filterBySertified, $filterByFree;

    public function onPrepareComponentParams($arParams)
    {
        if ($arParams["PAGE_SIZE"]) {
            $this->rowsPerPage = $arParams['PAGE_SIZE'];
        }

        return $arParams;
    }

    /**
     * Получение списка исполнителей
     *
     * @return mixed
     */
    protected function getContractors()
    {
        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'PRICE'];
        $arOrder = [];
        $arRuntime = [];

        // Добавление динамического поля ID пользователя, которому
        // принадлежит исполнитель.
        $arRuntime["USER"] = array(
            'expression' => array(
                '(SELECT b_iblock_element_property.VALUE
                            FROM b_iblock_element_property
                            WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID='.PROPERTY_CONTRACTORS_USER.'
                            AND b_iblock_element_property.IBLOCK_ELEMENT_ID=%s)',
                'ID',
            ),
        );
        $arSelect[] = "USER";

        // Добвление динамического поля рейтинга
        $arRuntime["RATING"] = array(
            'expression' => array(
                '(SELECT b_uts_user.UF_RATING
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                'USER',
            ),
        );
        $arSelect[] = "RATING";

        // Добвление динамического поля рейтинга
        $arRuntime["SERTIFIED"] = array(
            'expression' => array(
                '(SELECT b_uts_user.UF_SERTIFIED
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                'USER',
            ),
        );
        $arSelect[] = "SERTIFIED";

        // Добвление динамического поля статус свободен
        $arRuntime["FREE"] = array(
            'expression' => array(
                '(SELECT b_uts_user.UF_FREE
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                'USER',
            ),
        );
        $arSelect[] = "FREE";

        // Добавление фильтрации
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_CONTRACTORS,
            'ACTIVE' => 'Y',
            '!PRICE' => false,
        ];

        // Фильтрация по результатам поиска
        if ($this->search) {
            if ($this->arSearchResult["CONTRACTORS"]) {
                $arFilter["ID"] = $this->arSearchResult["CONTRACTORS"];
            }
            elseif ($this->arSearchResult["SERVICES"]) {
                if (count($this->arSearchResult["SERVICES"]) == 1) {
                    $this->filterByService = $this->getService(current($this->arSearchResult["SERVICES"]));
                } else {
                    $this->isServicesFound = true;
                }
            }
            else {
                $arFilter["ID"] = false;
            }
        }

        // Фильтрация по выбранной услуге
        if ($this->filterByService) {
            $runtimePriceQuery = '(SELECT electric_contractor_to_service.UF_PRICE
                            FROM electric_contractor_to_service
                            WHERE electric_contractor_to_service.UF_CONTRACTOR=%s
                            AND electric_contractor_to_service.UF_SERVICE='.$this->filterByService["ID"].')';
        } else {
            // Фильтрация по массиву найденных услуг
            if (!$this->isServicesFound) {
                $runtimePriceQuery = '(SELECT MIN(electric_contractor_to_service.UF_PRICE)
                            FROM electric_contractor_to_service
                            WHERE electric_contractor_to_service.UF_CONTRACTOR=%s)';
            } else {
                $runtimePriceQuery = '(SELECT MIN(electric_contractor_to_service.UF_PRICE)
                            FROM electric_contractor_to_service
                            WHERE electric_contractor_to_service.UF_CONTRACTOR=%s
                            AND electric_contractor_to_service.UF_SERVICE in ('.implode(", ", $this->arSearchResult["SERVICES"]).'))';
            }
        }
        $arRuntime["PRICE"] = [
            'expression' => [
                $runtimePriceQuery,
                'ID',
            ],
        ];

        // Находим название минимальной по стоимости услуги
        $runtimeMinServiceQuery = '(
            SELECT b_iblock_element.NAME 
            FROM b_iblock_element 
            WHERE b_iblock_element.ID = (SELECT electric_contractor_to_service.UF_SERVICE
                            FROM electric_contractor_to_service
                            WHERE electric_contractor_to_service.UF_PRICE = ( SELECT MIN(electric_contractor_to_service.UF_PRICE) from electric_contractor_to_service WHERE electric_contractor_to_service.UF_CONTRACTOR=%s LIMIT 0,1 )
                            AND electric_contractor_to_service.UF_CONTRACTOR=%s                            
                            LIMIT 0,1)
                            )';
        $arRuntime["MIN_SERVICE"] = [
            'expression' => [
                $runtimeMinServiceQuery,
                'ID',
                'ID',
            ],
        ];
        $arSelect[] = "MIN_SERVICE";


        // Фильтрация по местоположению исполнителя
        // (город, выбранный пользователем + улица, выбранная в фильтре)
        if ($this->userID || $_COOKIE["BITRIX_SM_USER_CITY"]) {

            if ($this->userID) {
                $arUser = \CUser::GetByID($this->userID)->Fetch();
                $userCityID = $arUser["UF_LOCATION"];
            } else {
                $userCityID = $_COOKIE["BITRIX_SM_USER_CITY"];
            }

            if ($this->filterByLocation) {
                $arFilteredByLocation = $this->filterByMultipleProperty(PROPERTY_CONTRACTORS_LOCATIONS, $this->filterByLocation);
            } else {
                $arFilteredByLocation = $this->filterByMultipleProperty(PROPERTY_CONTRACTORS_LOCATIONS, $userCityID);
            }

            if ($arFilter["ID"]) {
                $arFilter["ID"] = array_intersect($arFilter["ID"], $arFilteredByLocation);
            } else {
                $arFilter["ID"] = $arFilteredByLocation;
            }

        }

        // Фильтрация по типу исполнителя (частное лицо или компания)
        if ($this->filterByType) {
            $arRuntime["TYPE"] = array(
                'expression' => array(
                    '(SELECT b_iblock_element_property.VALUE
                            FROM b_iblock_element_property
                            WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID='.PROPERTY_CONTRACTORS_TYPE.'
                            AND b_iblock_element_property.IBLOCK_ELEMENT_ID=%s)',
                    'ID',
                ),
            );
            $arSelect[] = "TYPE";
            $arFilter["TYPE"] = $this->filterByType;
        }

        // Фильтрация по квалификации
        if ($this->filterBySkill) {
            $arRuntime["SKILL"] = array(
                'expression' => array(
                    '(SELECT b_iblock_element_property.VALUE
                            FROM b_iblock_element_property
                            WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID='.PROPERTY_CONTRACTORS_SKILL.'
                            AND b_iblock_element_property.IBLOCK_ELEMENT_ID=%s)',
                    'ID',
                ),
            );
            $arFilter["SKILL"] = $this->filterBySkill;
        }

        // Фильтрация по типу помещения
        if ($this->filterByRoom) {
            if ($arFilteredID = $this->filterByMultipleProperty(PROPERTY_CONTRACTORS_ROOM, $this->filterByRoom)) {
                if ($arFilter["ID"]) {
                    $arFilter["ID"] = array_intersect($arFilter["ID"], $arFilteredID);
                } else {
                    $arFilter["ID"] = $arFilteredID;
                }
            }
        }

        // Фильтрация по сертификации
        if ($this->filterBySertified) {
            $arFilter["SERTIFIED"] = 1;
        }
        // Фильтрация по статусу
        if ($this->filterByFree) {
            $arFilter["FREE"] = 1;
        }

        // Добавление сортировки

        // Сортировка по цене
        if ($this->arSort["BY"] == "price") {
            $arOrder["PRICE"] = $this->arSort["ORDER"];
        }

        // Сортировка по рейтингу
        // (включает в себя предварительную сортировку по членству в РАЭК)
        if ($this->arSort["BY"] == "rating") {
            $arRuntime["RAEK"] = array(
                'expression' => array(
                    '(SELECT b_uts_user.UF_RAEK
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                    'USER',
                ),
            );
            $arOrder = array(
                "RAEK" => $this->arSort["ORDER"],
                "RATING" => $this->arSort["ORDER"],
            );
        }

        // Сортировка по времени реагирования
        // (включает в себя предварительную сортировку по членству в РАЭК)
        $arRuntime["TIME"] = array(
            'expression' => array(
                '(SELECT b_uts_user.UF_TIME
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                'USER',
            ),
        );
        $arSelect[] = "TIME";
        if ($this->arSort["BY"] == "time") {
            $arOrder = array(
                "TIME" => $this->arSort["ORDER"],
                "RATING" => $this->arSort["ORDER"],
            );
        }

        // Сортировка по рекомендованным исполнителям
        // (сортирует по рейтингу, но включает в себя предварительную сортировку
        // по свойству пользователя "Рекомендованный")
        if ($this->arSort["BY"] == "recommend") {
            $arRuntime["RECOMMENDED"] = array(
                'expression' => array(
                    '(SELECT b_uts_user.UF_RECOMMENDED
                            FROM b_uts_user
                            WHERE b_uts_user.VALUE_ID=%s)',
                    'USER',
                ),
            );
            $arSelect[] = "RECOMMENDED";

            $arOrder = array(
                "RECOMMENDED" => $this->arSort["ORDER"],
                "RATING" => $this->arSort["ORDER"],
            );
        }



        // Выборка
        $dbResult = \Bitrix\Iblock\ElementTable::getList(array(
            'select' => $arSelect,
            'filter' => $arFilter,
            'order' => $arOrder,
            'runtime' => $arRuntime,
            'count_total' => true,
            'limit' => $this->rowsPerPage,
            'offset' => $this->rowsPerPage * ($this->page - 1),
        ));
        $this->pagesCount = ceil($dbResult->getCount() / $this->rowsPerPage);

        $arContractors = array();
        $arUsersID = array();
        while ($arItem = $dbResult->fetch()) {

            // Получение URL детальной страницы
            $arItem["DETAIL_PAGE_LINK"] = PATH_MARKETPLACE . $arItem['ID'] . '/';

            // Получение изображения
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PREVIEW_PICTURE'] = \CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arItem['IBLOCK_ID'],
                $arItem['ID']
            );
            while ($arProperty = $dbProperty->Fetch()) {
                $arItem['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                if ($arProperty["CODE"] == "USER") {
                    $arUsersID[] = $arProperty["VALUE"];
                }
            }
            $arItem['TIME_TEXT'] = DataHelper::getWordForm("time",$arItem["TIME"]);
            $arContractors[] = $arItem;
        }

        if ($this->pagesCount == 1 || $this->page >= $this->pagesCount) {
            $this->isLastPage = true;
        };

        // Для поиска по услугам, если по запросу было найдено больше одной услуги:
        // получение списка найденных услуг для каждого из выбранных исполнителей
        if ($this->isServicesFound) {
            $arFoundServicesForContractors = $this->getFoundServicesForContractors($this->arSearchResult["SERVICES"], array_column($arContractors, "ID"));
        }

        $arContractorServices = $this->getServicesForContractors(array_column($arContractors, "ID"));


        // Получение отзывов для всех выбранных пользователей
        $arReviews = $this->getUsersReviews($arUsersID);

        // Добавление данных в массив исполнителей
        foreach ($arContractors as &$arContractor) {
            $contractorID = $arContractor["ID"];
            if ($this->filterByService) {
                $arContractor["SERVICE"]["NAME"] = $this->filterByService["NAME"];
            }
            if ($this->isServicesFound) {
                $arContractor["FOUND_SERVICES"] = array_column($arFoundServicesForContractors[$contractorID], "NAME");
            }

            $arContractor["SERVICES"] = $arContractorServices[$contractorID];

            $arContractor["REVIEWS"]["COUNT"] = count($arReviews[$arContractor["PROPERTIES"]["USER"]["VALUE"]]);
            $arContractor["REVIEWS"]["COUNT_TEXT"] = $arContractor["REVIEWS"]["COUNT"] . " " . DataHelper::getWordForm("reviews", $arContractor["REVIEWS"]["COUNT"]);

            foreach ($arReviews[$arContractor["PROPERTIES"]["USER"]["VALUE"]] as $arReview) {
                if ($arReview["PROPERTY_MARK_VALUE"] > 3) {
                    $arContractor["REVIEWS"]["LAST"] = $arReview;
                    break;
                }
            }
        }

        return $arContractors;
    }

    /**
     * Получение данных об отзывах для всех исполнителей
     *
     * @param $arUsersID
     *
     * @return array
     */
    private function getUsersReviews($arUsersID) {
        $arOrder = [
            "ACTIVE_FROM" => "DESC",
        ];
        $arSelect = [
            "ID",
            "ACTIVE_FROM",
            "NAME",
            "IBLOCK_ID",
            "PREVIEW_TEXT",
            "PROPERTY_USER",
            "PROPERTY_MARK",
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'PROPERTY_USER' => $arUsersID,
        ];
        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $arReviews = [];
        while ($arElement = $dbResult->Fetch()) {
            $arReviews[$arElement["PROPERTY_USER_VALUE"]][] = $arElement;
        }

        return $arReviews;
    }

    /**
     * Получение данных об услуге по ID
     *
     * @param $serviceID
     *
     * @return array|bool
     */
    private function getService($serviceID) {
        $rsService = \CIBlockElement::GetByID($serviceID);
        $arService = $rsService->Fetch();
        if ($arService) {
            return $arService;
        } else {
            return false;
        }
    }

    /**
     * Получение данных об услугах по массиву ID
     *
     * @param $arServicesID
     *
     * @return array
     */
    private function getServices($arServicesID = []) {
        $arSelect = [
            "ID",
            "NAME",
            "IBLOCK_ID",
        ];
        if(!empty($arServicesID)){
            $arFilter = [
                'ID' => $arServicesID,
            ];
        }
        $arServices = [];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arItem = $dbResult->Fetch()) {
            $arServices[$arItem["ID"]] = $arItem;
        }

        return $arServices;
    }

    /**
     * Получение списка (названий и цен) услуг, найденных для выбранных пользователей
     *
     * @param $arServicesID
     * @param $arContractorsID
     *
     * @return array
     */
    private function getFoundServicesForContractors($arServicesID, $arContractorsID) {
        $arDataClasses = Contractor::getHLDataClasses([HLBLOCK_CONTRACTOR_TO_SERVICE]);

        $arFoundServices = $this->getServices($arServicesID);

        $rsData = $arDataClasses["SERVICES"]::getList(
            [
                "select" => ['*'],
                "filter" => [
                    "UF_SERVICE" => $arServicesID,
                    "UF_CONTRACTOR" => $arContractorsID,
                ],
                "order" => [],
            ]
        );
        $arFoundServicesForContractors = [];
        while ($arItem = $rsData->Fetch()) {
            $arFoundServicesForContractors[$arItem["UF_CONTRACTOR"]][] = [
                "NAME" => $arFoundServices[$arItem["UF_SERVICE"]]["NAME"],
                "PRICE" => $arItem["UF_PRICE"],
            ];
        }

        return $arFoundServicesForContractors;
    }

    private function getServicesForContractors($arContractorsID) {
        $arDataClasses = Contractor::getHLDataClasses([HLBLOCK_CONTRACTOR_TO_SERVICE]);

        $arFoundServices = $this->getServices();

        $rsData = $arDataClasses["SERVICES"]::getList(
            [
                "select" => ['*'],
                "filter" => [
                    "UF_CONTRACTOR" => $arContractorsID,
                ],
                "order" => [],
            ]
        );
        $arFoundServicesForContractors = [];
        while ($arItem = $rsData->Fetch()) {
            $arFoundServicesForContractors[$arItem["UF_CONTRACTOR"]][] = [
                "NAME" => $arFoundServices[$arItem["UF_SERVICE"]]["NAME"],
                "PRICE" => $arItem["UF_PRICE"],
            ];
        }

        return $arFoundServicesForContractors;
    }

    /**
     * Получение заголовка результатов поиска/фильтрации
     *
     * @return string
     */
    private function getSearchTitle() {
        global $USER;

        $searchTitle = "";
        if ($this->filterByService) {
            $searchTitle .= $this->filterByService["NAME"];
        } else {
            $searchTitle = "Все специалисты";
        }

        if ($USER->IsAuthorized()) {
            $arUser = \CUser::GetByID($USER->GetID())->Fetch();
            $searchTitle .= ", г. " . $arUser["UF_LOCATION_NAME"];
        } else {

            $searchTitle .= ", г. " . $_COOKIE["BITRIX_SM_USER_CITY_NAME"];
        }

        if ($this->filterByLocation) {
            // $arLocation = \CSaleLocation::GetByID($this->filterByLocation);
            // searchTitle .= ", " . $arLocation["CITY_NAME"];
        }

        return $searchTitle;
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

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        global $USER;
        $this->userID = $USER->GetID();

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();
        $isAjax = $this->isAjaxRequest() ? true : false;



        if ($this->input["sort_by"]) {
            $this->arSort["BY"] = $this->input["sort_by"];
            $this->arSort["ORDER"] = $this->input["sort_order"];
        }

        if ($this->input["page"]) {
            $this->page = $this->input["page"];
        }

        // Установки поиска
        // Поиск осуществляется отдельно по исполнителям и услугам
        if ($this->input["contractors-search"]) {
            $this->search = $this->input["contractors-search"];

            $obSearch = new \CSearch;
            $obSearch->Search([
                "QUERY" => $this->search,
                "SITE_ID" => SITE_ID,
                "MODULE_ID" => "iblock",
                "PARAM2" => IBLOCK_CONTRACTORS,
            ]);
            while($arResult = $obSearch->GetNext())
            {
                $this->arSearchResult["CONTRACTORS"][] = $arResult["ITEM_ID"];
            }

            $obSearch->Search([
                "QUERY" => $this->search,
                "SITE_ID" => SITE_ID,
                "MODULE_ID" => "iblock",
                "PARAM2" => IBLOCK_SERVICES,
            ]);
            while($arResult = $obSearch->GetNext())
            {
                // Разделов не должно быть в результатах
                if (substr($arResult['ITEM_ID'], 0, 1) != 'S') {
                    $this->arSearchResult["SERVICES"][] = $arResult["ITEM_ID"];
                }
            }
        }
        $isSEOFilter = false;

        // Установки фильтрации
        if ($this->input["filter"]) {
            $isSEOFilter = true;
            $arSEOFilter = explode("_", $this->input["filter"]);
            $obService = new Service();
            $arService = $obService->getServiceByCode($arSEOFilter[0]);
            if ($arService) {
                $this->filterByService = $this->getService($arService["ID"]);
            } else {
                LocalRedirect(PATH_MARKETPLACE);
            }
            $arCity = Location::getCachedLocationByCode($arSEOFilter[1]);

            if ($arCity) {
                if (strpos($arCity["UF_NAME"], "г ") === 0) {
                    $cityName = substr($arCity["UF_NAME"], 2);
                } else {
                    $cityName = $arCity["UF_NAME"];
                }
                $_COOKIE["BITRIX_SM_USER_CITY"] = $arCity["UF_FIAS_ID"];
                $_COOKIE["BITRIX_SM_USER_CITY_NAME"] = $cityName;

                global $APPLICATION;
                $APPLICATION->set_cookie("USER_CITY", $arCity["UF_FIAS_ID"]);
                $APPLICATION->set_cookie("USER_CITY_NAME", $cityName);

            } else {
                LocalRedirect(PATH_MARKETPLACE);
            }




        } else {

            if ($this->input["service"]) {
                $this->filterByService = $this->getService($this->input["service"]);
            }

        }

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

        if ($this->input["contractors-location-id"]) {
            $this->filterByLocation = $this->input["contractors-location-id"];
        }
        if ($this->input["type"]) {
            switch ($this->input["type"]) {
                case "individual":
                    $this->filterByType = ENUM_VALUE_CONTRACTORS_TYPE_INDIVIDUAL;
                    break;
                case "legal":
                    $this->filterByType = ENUM_VALUE_CONTRACTORS_TYPE_LEGAL;
                    break;
            }
        }
        if ($this->input["skill"]) {
            $this->filterBySkill = $this->input["skill"];
        }
        if ($this->input["room"]) {
            $this->filterByRoom = $this->input["room"];
        }
        if ($this->input["sert"]) {
            $this->filterBySertified = $this->input["sert"];
        }
        if ($this->input["free"]) {
            $this->filterByFree = $this->input["free"];
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["ITEMS"] = $this->getContractors();
            $this->arResult["SORT"] = $this->arSort;
            $this->arResult["SEARCH_TITLE"] = $this->getSearchTitle();
            $this->arResult["IS_LAST_PAGE"] = $this->isLastPage;
            $this->arResult["USER_CITY"] = $userCity;
            if (!$this->arResult["ITEMS"]) {
                $this->arResult["NOT_FOUND"] = true;
            }
            if ($isAjax) $this->restartBuffer();
            $this->includeComponentTemplate();
            if ($isAjax) die();
        }
    }
}

