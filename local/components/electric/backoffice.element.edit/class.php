<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Distributor;
use \Electric\Helpers\DataHelper;

class DistributorsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arFields, $iblockID, $elementID, $arElement;

    private $folder;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        $this->iblockID = $arParams['IBLOCK_ID'];
        $this->elementID = $arParams['ELEMENT_ID'];

        return $arParams;
    }

    /**
     * Получение полей инфоблока
     *
     * @return array|bool
     */
    protected function getFields()
    {
        /**Проверили принадлежность элемента к пользователю*/
        $obDistibutor = new Distributor();
        if(!$obDistibutor->isMy($this->elementID))
            return false;
            //throw new \Exception("Element not found");


        /**Собрали элемент*/
        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT','DETAIL_TEXT', 'DETAIL_PICTURE','DETAIL_PAGE_URL'];
        $arFilter = [
            "ID" => $this->elementID,
            "IBLOCK_ID" => $this->iblockID,
            "ACTIVE" => "Y",
        ];
        $rsElements = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arElement = $rsElements->Fetch()) {
            // Получение изображений
            if ($arElement['PREVIEW_PICTURE']) {
                $arElement['PREVIEW_PICTURE'] = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
            }
            $arElement["DATE"] = DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arElement['IBLOCK_ID'],
                $arElement['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if($arProperty['PROPERTY_TYPE'] == "E"){
                    // Получение категорий сервисов
                    $arSel = ['ID','NAME'];
                    $arFil = ['IBLOCK_ID' => $arProperty["LINK_IBLOCK_ID"],'ID' => $arElement["PROPERTIES"][$arProperty["CODE"]]["VALUE"]];

                    $dbResult = \CIBlockElement::GetList([], $arFil, false, false, $arSel);
                    while ($arType = $dbResult->GetNext()) {
                        $arElement["PROPERTIES"][$arProperty["CODE"]]["VALUE_NAME"] = $arType["NAME"];
                    }
                }
                if($arProperty["PROPERTY_TYPE"] == "L"){

                    $propList = \CIBlockProperty::GetPropertyEnum($arProperty["ID"], ["SORT"=>"ASC"], []);
                    while($propListEnum = $propList->GetNext()){
                        $arProperty["VALUES"][] = ["ID" => $propListEnum["ID"], "NAME" => $propListEnum["VALUE"]];
                    }
                }
                $arElement['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $this->arElement = $arElement;
        }



        /**Собрали свойства инфоблока*/
        $res = \CIBlock::GetProperties($this->iblockID, [], []);
        while($arProperty = $res->Fetch()){
            if($arProperty["PROPERTY_TYPE"] == "E"){
                $arSelect = ['ID', 'NAME', 'IBLOCK_ID'];
                $arOrder = ["NAME" => "ASC"];
                $arFilter = ["IBLOCK_ID" => $arProperty["LINK_IBLOCK_ID"], "ACTIVE" => "Y"];
                $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
                while ($arItem = $dbResult->fetch()) {
                    $arProperty["VALUES"][] = ["ID" => $arItem["ID"], "NAME" => $arItem["NAME"]];
                }
            }
            if($arProperty["PROPERTY_TYPE"] == "L"){

                $propList = \CIBlockProperty::GetPropertyEnum($arProperty["ID"], ["SORT"=>"ASC"], []);
                while($propListEnum = $propList->GetNext()){
                    $arProperty["VALUES"][] = ["ID" => $propListEnum["ID"], "NAME" => $propListEnum["VALUE"]];
                }
            }
            $this->arFields[$arProperty["CODE"]] = $arProperty;
        }
        if(!empty($this->arFields)){
            return true;
        }

        return false;
    }



    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        if ($this->StartResultCache()) {
            if ($this->getFields() !== false) {
                $this->arResult['FIELDS'] = $this->arFields;
                $this->arResult['ELEMENT'] = $this->arElement;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
