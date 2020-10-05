<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\News;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;
use Bitrix\Iblock;

class  OneNews extends BaseComponent
{

    public $requiredModuleList = ['iblock'];
    private $params;
    private $addFilter;

    public function onPrepareComponentParams($arParams)
    {
        $this->params = $arParams;
        $this->addFilter = $arParams["FILTER"];

        return $arParams;
    }

    /**
     * Получение новости
     *
     * @return array
     * @throws \Exception
     */
    protected function getOneNews()
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




        $rsNews = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $rsNews->SetUrlTemplates($this->params["DETAIL_URL"]);
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
        $arItem = $this->getOneNews();

        if ($this->StartResultCache(false, [$arItem["ID"]])) {
            $this->arResult = $arItem;
            $this->includeComponentTemplate();
        }

        return $arItem["ID"];
    }
}