<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;
use Bitrix\Iblock;
use Electric\User;

class  OrderElement extends BaseComponent
{

    public $requiredModuleList = ['iblock'];
    private $params;
    private $input;
    private $userId, $userHash;
    private $isReg;

    public function onPrepareComponentParams($arParams)
    {
        $this->params = $arParams;

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        $userID = $this->input["user-id"];
        $userHash = $this->input["hash"];

        return $arParams;
    }

    /**
     * Получение списка категорий услуг и услуг
     * Услугам назначается ссылка вида "услуга_город", зависящая от города
     * текущего пользователя
     *
     * @return array $arSections
     */
    protected function getServices()
    {
        $obUser = new User();
        $userCityCode = \CUtil::translit($obUser->getUserCityName(), "ru", ["replace_space" => "-","replace_other" => "-"]);

        $arOrder = [
            'SORT' => 'ASC',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_SERVICES,
            'UF_IS_POPULAR' => true
        ];

        $arSelect = [
            'ID',
            'NAME',
        ];
        $dbResult = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect, ['nPageSize' => 3]);
        $arSections = [];
        while($arSection = $dbResult->GetNext()) {
            $arSections[$arSection["ID"]] = $arSection;
        }

        $arOrder = [
            'SORT' => 'ASC',
        ];
        $arSelect = [
            "ID",
            "NAME",
            "CODE",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_SERVICES,
            'IBLOCK_SECTION_ID' => array_column($arSections, 'ID'),
            '!PROPERTY_IS_POPULAR_VALUE' => false,
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbResult->Fetch()) {
            $arSections[$arElement['IBLOCK_SECTION_ID']]['ITEMS'][] = $arElement;
        }

        return $arSections;
    }

    /**
     * Получение списка свойств инфоблока, для формирования полей
     *
     * @return array $arResult
     */
    protected function getFields(){
        $arResult = [];
        $dbProperty = \CIBlock::GetProperties(
            IBLOCK_ORDERS,
            [],
            []
        );
        while($arProperty = $dbProperty->Fetch()){
            if($arProperty["PROPERTY_TYPE"] == "L"){
                $propList = \CIBlockProperty::GetPropertyEnum($arProperty["ID"], ["SORT"=>"ASC"], []);
                while($propListEnum = $propList->GetNext()){
                    $arProperty["VALUES"][] = $propListEnum;
                    $last = $propListEnum;
                }
                if(count($arProperty["VALUES"]) == 1){
                    $arProperty["VALUES"] = $last;
                }

            }

            $arResult[$arProperty["CODE"]] = $arProperty;


        }
        //var_dump($arResult);

        return $arResult;
    }

    /**
     * Получение инфо о городе пользователя
     * @return array $userCity
     */
    protected function getUser(){
        global $USER;
        $arReturn = [];
        // Получение города пользователя
        if ($USER->IsAuthorized()) {
            $arUser = \CUser::GetByID($USER->GetID())->Fetch();
            $userCityID = $arUser["UF_LOCATION"];
            $userCityName = $arUser["UF_LOCATION_NAME"];
            $arReturn = [
                "IS_AUTH" => true,
                "NAME" => $arUser["NAME"],
                "LAST_NAME" => $arUser["LAST_NAME"],
                "PHONE" => $arUser["PERSONAL_PHONE"]
            ];
        }
        else {
            $userCityID = $_COOKIE["BITRIX_SM_USER_CITY"];
            $userCityName = $_COOKIE["BITRIX_SM_USER_CITY_NAME"];
        }
        $userCity = [
            "ID" => $userCityID,
            "NAME" => $userCityName,
        ];
        $arReturn["CITY"] =  $userCity;
        return $arReturn;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');
            return false;
        }

        $this->arResult["SERVICES"] = $this->getServices();
        $this->arResult["USER"] = $this->getUser();
        $this->arResult["FIELDS"] = $this->getFields();

        $this->includeComponentTemplate();

    }
}