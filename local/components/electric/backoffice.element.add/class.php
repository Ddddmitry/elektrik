<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Distributor;
use \Electric\Helpers\DataHelper;

class DistributorsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arFields, $iblockID;

    private $folder;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        $this->iblockID = $arParams['IBLOCK_ID'];

        return $arParams;
    }

    /**
     * Получение полей инфоблока
     *
     * @return array|bool
     */
    protected function getFields()
    {
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
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
