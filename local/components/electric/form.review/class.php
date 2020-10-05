<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class ReviewForm extends BaseComponent
{
    private $contractorUserID;
    private $clientUserID;

    public function onPrepareComponentParams($arParams)
    {
        $this->contractorUserID = $arParams["USER"];

        return $arParams;
    }

    public function checkClientToContractor(){

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ORDERS,
            "PROPERTY_USER" => $this->clientUserID,
            "PROPERTY_USER_CONTRACTOR" => $this->contractorUserID,
            "PROPERTY_STATUS" => ENUM_VALUE_ORDERS_STATUS_COMPLETED
        ];

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        return boolval($dbResult->SelectedRowsCount());
    }

    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->arResult["IS_AUTHORIZED"] = true;
            $this->clientUserID = $USER->GetID();
            $this->arResult["CAN_STAY_REVIEW"] = $this->checkClientToContractor();
        };

        $this->arResult["USER"] = $this->contractorUserID;

        $this->includeComponentTemplate();
    }
}
