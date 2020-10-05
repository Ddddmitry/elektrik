<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use http\Params;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class ReviewEditForm extends BaseComponent
{
    private $reviewID;

    public function onPrepareComponentParams($arParams)
    {
        $this->reviewID = $arParams["REVIEW"];

        return $arParams;
    }

    public function executeComponent()
    {
        \CModule::IncludeModule("iblock");

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'ID' => $this->reviewID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "PREVIEW_TEXT", "PROPERTY_MARK"]);
        if ($arReview = $dbResult->Fetch()) {
            $this->arResult = [
                "ID" => $this->reviewID,
                "TEXT" => $arReview["PREVIEW_TEXT"],
                "MARK" => $arReview["PROPERTY_MARK_VALUE"],
            ];
        } else {
            $this->arResult["REVIEW_NOT_FOUND"] = true;
        }

        $this->includeComponentTemplate();
    }
}
