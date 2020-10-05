<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class StaticSeo extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        \CModule::IncludeModule("iblock");

        $currentPage = $APPLICATION->GetCurPage(false);


        $arFilter = [
            "IBLOCK_ID" => IBLOCK_SEO_CONTENT,
            "PROPERTY_PAGE" => $currentPage,
            "ACTIVE" => "Y",
        ];
        $arSelect = [
            "ID",
            "DETAIL_TEXT",
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arContent = $dbResult->Fetch()) {
            $this->arResult["CONTENT"] = $arContent["DETAIL_TEXT"];

            $this->includeComponentTemplate();
        }
    }
}
