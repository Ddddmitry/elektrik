<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Helpers\DataHelper;

class ServiceList extends BaseComponent
{
    private $input;

    public $requiredModuleList = ['iblock', 'search'];
    private $cacheSting = "service_";
    private $filterByType;

    private $arIblock = [];

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        $this->arIblock = [
            "SERVICES" => $arParams['IBLOCK_ID'],
            "TYPES" => $arParams['TYPE_IBLOCK_ID'],
        ];

        return $arParams;
    }


    /**
     * Получение списка сервисов

     * @return array
     * @throws \Exception
     */
    protected function getServiceList()
    {
        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'DETAIL_TEXT', 'DETAIL_PICTURE'];
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
            $arFilter["PROPERTY_TYPE"] = $this->filterByType;
        }

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $arServices = [];
        $arTypesID = [];
        while ($arService = $dbResult->fetch()) {

            // Получение изображений
            if ($arService['PREVIEW_PICTURE']) {
                $arService['PREVIEW_PICTURE'] = \CFile::GetFileArray($arService['PREVIEW_PICTURE']);
            }
            if ($arService['DETAIL_PICTURE']) {
                $arService['DETAIL_PICTURE'] = \CFile::GetFileArray($arService['DETAIL_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arService['IBLOCK_ID'],
                $arService['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arService['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $arTypesID[] = $arService["PROPERTIES"]["TYPE"]["VALUE"];

            $arServices[] = $arService;
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

        // Добавление дополнительных данных в массив сервисов
        foreach ($arServices as &$arService) {
            $arService["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arTypes[$arService["PROPERTIES"]["TYPE"]["VALUE"]];
        }

        $arResult = [
            "SERVICES" => $arServices,
            "TYPES" => $arTypes,
        ];

        return $arResult;
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

        // Установки фильтрации
        if ($this->input["type"]) {
            $this->filterByType = $this->input["type"];
            $this->cacheSting .= $this->input["type"];
        }

        if ($isAjax) $this->cacheSting .= "_is_ajax";
        if ($isAjax) $this->restartBuffer();

        if ($this->StartResultCache(false, $this->cacheSting)) {
            // Получение услуг
            $arResult = $this->getServiceList();

            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["ITEMS"] = $arResult["SERVICES"];
            $this->arResult["TYPES"] = $arResult["TYPES"];
            $this->arResult["SEF_FOLDER"] = $this->folder;

            $this->includeComponentTemplate();

        }
        if ($isAjax) die();
    }
}

