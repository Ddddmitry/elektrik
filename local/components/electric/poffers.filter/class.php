<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Events;

class PoffersFilter extends BaseComponent
{
    private $input;

    private $obHLDataClassSkills;

    public $requiredModuleList = ['iblock', 'highloadblock'];

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных фильтра
     *
     * @return mixed
     */
    protected function getFilterData()
    {
        $arFilterData = [];

        $arFilterData["TYPES"] = $this->getPoffersTypes();


        return $arFilterData;
    }

    protected function getPoffersTypes(){

        $arSelect = ['ID', 'PROPERTY_TYPE'];
        $arOrder = [
            "SORT" => "ASC",
            "DATE_CREATE" => "DESC",
        ];
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => IBLOCK_PARTNERS_OFFERS,
        ];
        $arTypesID = [];
        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arItem = $dbResult->fetch()) {
            $arTypesID[] = $arItem["PROPERTY_TYPE_VALUE"];
        }

        $arSelect = [
            'ID',
            'NAME',
            'CODE',
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_PARTNERS_OFFERS_TYPES,
            'ID' => array_unique($arTypesID)
        ];

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTypes = [];
        while ($arType = $dbResult->GetNext()) {
            $arTypes[$arType["ID"]] = $arType["NAME"];
        }

        return $arTypes;
    }

    public function executeComponent()
    {
        if ($this->isAjaxRequest()) {
            return false;
        }

        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["FILTER_DATA"] = $this->getFilterData();
            $this->arResult["SEF_FOLDER"] = $this->folder;
            $this->includeComponentTemplate();
        }
    }
}

