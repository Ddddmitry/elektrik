<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Helpers\DataHelper;

class ServiceList extends BaseComponent
{
    private $input,$iblock;

    public $requiredModuleList = ['iblock', 'search'];

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        if(intval($arParams["IBLOCK_ID"]) <= 0 ){
            ShowError('Не выбран инфоблок');
            return false;
        }else{
            $this->iblock = $arParams['IBLOCK_ID'];
        }

        return $arParams;
    }


    /**
     * Получение списка сервисов

     * @return array
     * @throws \Exception
     */
    protected function getElements()
    {
        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT','DETAIL_TEXT', 'DETAIL_PICTURE'];
        $arOrder = [
            "SORT" => "ASC",
            "DATE_CREATE" => "DESC",
        ];

        // Добавление фильтрации
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $this->iblock,
        ];


        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $arResult = [];

        while ($arItem = $dbResult->fetch()) {

            // Получение изображений
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PREVIEW_PICTURE'] = \CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
            }
            if ($arItem['DETAIL_PICTURE']) {
                $arItem['DETAIL_PICTURE'] = \CFile::GetFileArray($arItem['DETAIL_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arItem['IBLOCK_ID'],
                $arItem['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arItem['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }



            $arResult[] = $arItem;
        }

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

        // Получение услуг
        $arResult = $this->getElements();

        if ($this->StartResultCache(false)) {
            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["ITEMS"] = $arResult;
            $this->arResult["SEF_FOLDER"] = $this->folder;
            if ($isAjax) $this->restartBuffer();
            $this->includeComponentTemplate();
            if ($isAjax) die();
        }
    }
}

