<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use \Electric\User;

class Services extends BaseComponent
{
    public $requiredModuleList = ['iblock'];

    private $filterPopular = false;

    private $title;

    public function onPrepareComponentParams($arParams)
    {
        $this->filterPopular = $arParams['POPULAR'] ? true : false;
        $this->title = $arParams['TITLE'];

        return $arParams;
    }

    /**
     * Получение списка категорий услуг и услуг
     * Услугам назначается ссылка вида "услуга_город", зависящая от города
     * текущего пользователя
     *
     * @return array $arItems
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
        ];
        if ($this->filterPopular) $arFilter["UF_IS_POPULAR"] = true;

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
            "PROPERTY_COUNT_ORDERS",
            "PROPERTY_COUNT_CONTRACTORS",
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_SERVICES,
            'IBLOCK_SECTION_ID' => array_column($arSections, 'ID'),
        ];
        if ($this->filterPopular) $arFilter["!PROPERTY_IS_POPULAR_VALUE"] = false;

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbResult->Fetch()) {
            // todo: Убрать рандомное количество заказов
            $arElement["PROPERTY_COUNT_ORDERS_VALUE"] = $arElement["PROPERTY_COUNT_ORDERS_VALUE"] ?? rand(2,10);
            $arElement["PROPERTY_COUNT_CONTRACTORS_VALUE"] = $arElement["PROPERTY_COUNT_CONTRACTORS_VALUE"] ?? rand(2,10);
            $arElement["LINK"] = PATH_SERVICES."filter/".$arElement["CODE"]."_".$userCityCode."/";
            $arSections[$arElement['IBLOCK_SECTION_ID']]['ITEMS'][] = $arElement;
        }

        return $arSections;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["ITEMS"] = $this->getServices();
            $this->arResult["TITLE"] = $this->title;
            $this->arResult["SHOW_ALL_BUTTON"] = $this->filterPopular ? true : false;
            $this->includeComponentTemplate();
        }
    }
}

?>
