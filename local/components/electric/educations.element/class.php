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
    private $params;
    private $addFilter;

    private $arSeo = [];

    public function onPrepareComponentParams($arParams)
    {
        $this->params = $arParams;
        $this->addFilter = $arParams["FILTER"];

        return $arParams;
    }

    /**
     * Получение обучалки
     *
     * @return array
     * @throws \Exception
     */
    protected function getEducation()
    {

        //Handle case when ELEMENT_CODE used
        if($this->params["ELEMENT_ID"] <= 0)
            $this->params["ELEMENT_ID"] = \CIBlockFindTools::GetElementID(
                $this->params["ELEMENT_ID"],
                $this->params["ELEMENT_CODE"],
                $this->params["STRICT_SECTION_CHECK"]? $this->params["SECTION_ID"]: false,
                $this->params["STRICT_SECTION_CHECK"]? $this->params["~SECTION_CODE"]: false,
                []
            );

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'DETAIL_TEXT', 'DETAIL_PICTURE','DETAIL_PAGE_URL'];
        $arFilter = [
            "ID" => $this->params["ELEMENT_ID"],
            "IBLOCK_ID" => $this->params["IBLOCK_ID"],
            "ACTIVE" => "Y",
        ];


        $rsItem = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $rsItem->SetUrlTemplates($this->params["DETAIL_URL"]);
        $arReturn = [];
        if ($arElement = $rsItem->Fetch()) {
            // Получение изображений
            if ($arElement['PREVIEW_PICTURE']) {
                $arElement['PREVIEW_PICTURE'] = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
            }
            if ($arElement['DETAIL_PICTURE']) {
                $arElement['DETAIL_PICTURE'] = \CFile::GetFileArray($arElement['DETAIL_PICTURE']);
            }

            $arElement["DATE"] = DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]);
            if(time() > strtotime($arElement["ACTIVE_FROM"]))
                $arElement["COMPLETED"] = true;

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arElement['IBLOCK_ID'],
                $arElement['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arElement['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            // Получение типа
            $arSelect = [
                'ID',
                'NAME',
            ];
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_EDUCATIONS_TYPES,
                'ID' => $arElement["PROPERTIES"]["TYPE"]["VALUE"]
            ];

            $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            while ($arType = $dbResult->GetNext()) {
                $arElement["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arType["NAME"];
            }


            // Получение автора
            if($arElement["PROPERTIES"]["DISTRIBUTOR"]["VALUE"]):
                $arSelect = ['ID','NAME','PREVIEW_PICTURE','PROPERTY_PHONE','PROPERTY_EMAIL'];
                $arFilter = ['IBLOCK_ID' => IBLOCK_VENDORS, 'ID' => $arElement["PROPERTIES"]["DISTRIBUTOR"]["VALUE"]];
                $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
                while ($arVendor = $dbResult->GetNext()) {
                    if ($arVendor['PREVIEW_PICTURE']) {
                        $arVendor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arVendor['PREVIEW_PICTURE']);
                    }
                    $arElement["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"] = $arVendor;
                }
                $arFilter = ['IBLOCK_ID' => IBLOCK_DISTRIBUTORS, 'ID' => $arElement["PROPERTIES"]["DISTRIBUTOR"]["VALUE"]];
                $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
                while ($arVendor = $dbResult->GetNext()) {
                    if ($arVendor['PREVIEW_PICTURE']) {
                        $arVendor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arVendor['PREVIEW_PICTURE']);
                    }
                    $arElement["PROPERTIES"]["DISTRIBUTOR"]["ELEMENT"] = $arVendor;
                }
            endif;

            $arReturn = $arElement;
        }

        $ipropValues = new Iblock\InheritedProperty\ElementValues($arReturn["IBLOCK_ID"], $arReturn["ID"]);
        $this->arSeo = $ipropValues->getValues();

        return $arReturn;
    }

    /**
     * Устанавливаем SEO параметры страницы
     */
    protected function setSeo(){
        // Получение значений SEO для элемента
        global $APPLICATION;

        $arElementSEO = $this->arSeo;

        if ($arElementSEO["ELEMENT_META_TITLE"]) {
            $APPLICATION->SetPageProperty('title', $arElementSEO["ELEMENT_META_TITLE"]);
        } else {
            $APPLICATION->SetPageProperty('title', $this->arResult['ARTICLE']['NAME']);
        }

        if ($arElementSEO["ELEMENT_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["ELEMENT_META_KEYWORDS"];
        } elseif ($arElementSEO["SECTION_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["SECTION_META_KEYWORDS"];
        }
        if ($arElementSEO["ELEMENT_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["ELEMENT_META_DESCRIPTION"];
        } elseif ($arElementSEO["SECTION_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["SECTION_META_DESCRIPTION"];
        }
        if ($sSEOKeywords) {
            $APPLICATION->SetPageProperty('keywords', $sSEOKeywords);
        }
        if ($sSEODescription) {
            $APPLICATION->SetPageProperty('description', $sSEODescription);
        }
    }


    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');
            return false;
        }

        // Получение мероприятий
        $arItem = $this->getEducation();
        global $USER;
        $arItem["IS_AUTHORIZED"] = $USER->IsAuthorized();
        //todo: Сделать чтобы проверка на авторизацию не зависела от кэширования
        // сейчас кэширование выключено
        //if ($this->StartResultCache(false, [$arItem["ID"]])) {
            $this->arResult = $arItem;
            $this->includeComponentTemplate();
        //}

        $this->setSeo();

        return $arItem["ID"];
    }
}