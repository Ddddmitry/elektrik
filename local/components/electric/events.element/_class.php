<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Events;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;
use Bitrix\Iblock;

class  Event extends BaseComponent
{



    public $requiredModuleList = ['iblock'];
    private $arParams;
    private $arResult;


    public function onPrepareComponentParams($arParams)
    {
        $this->arParams = $arParams;
        return $arParams;
    }

    /**
     * Получение мероприятия
     *
     * @return array
     * @throws \Exception
     */
    protected function getEvent()
    {

        //Handle case when ELEMENT_CODE used
        if($this->arParams["ELEMENT_ID"] <= 0)
            $this->arParams["ELEMENT_ID"] = \CIBlockFindTools::GetElementID(
                $this->arParams["ELEMENT_ID"],
                $this->arParams["~ELEMENT_CODE"],
                $this->arParams["STRICT_SECTION_CHECK"]? $this->arParams["SECTION_ID"]: false,
                $this->arParams["STRICT_SECTION_CHECK"]? $this->arParams["~SECTION_CODE"]: false,
                []
            );

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'DETAIL_TEXT', 'DETAIL_PICTURE','DETAIL_PAGE_URL'];
        $arFilter = [
            "ID" => $this->arParams["ELEMENT_ID"],
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "ACTIVE" => "Y",
        ];


        $rsNews = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $rsNews->SetUrlTemplates($this->arParams["DETAIL_URL"]);
        $arReturn = [];
        if ($arElement = $rsNews->Fetch()) {
            // Получение изображений
            if ($arElement['PREVIEW_PICTURE']) {
                $arElement['PREVIEW_PICTURE'] = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
            }
            if ($arElement['DETAIL_PICTURE']) {
                $arElement['DETAIL_PICTURE'] = \CFile::GetFileArray($arElement['DETAIL_PICTURE']);
            }

            $arElement["DATE"] = DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]);
            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arElement['IBLOCK_ID'],
                $arElement['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arElement['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $arReturn = $arElement;
        }
        return $arReturn;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');
            return false;
        }

        // Получение мероприятий
        $arResult = $this->getEvent();

        if ($this->StartResultCache(false, [$arResult["ID"]])) {
            $this->arResult = $arResult;
            $this->includeComponentTemplate();
        }
    }
}