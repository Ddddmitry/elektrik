<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;
use Electric\Location;
use Bitrix\Sale\Location\GeoIp;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class CitySuggest extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        global $USER;

        \CModule::IncludeModule("sale");

        $obLocation = new Location();

        if ($USER->IsAuthorized()) {
            $this->arResult["IS_AUTHORIZED"] = true;

            $userID = $USER->GetID();
            $arUser = \CUser::GetByID($userID)->Fetch();
            if (!$arUser["UF_LOCATION"]) {

                $arSuggestedCity = $obLocation->getSuggestedCity();
                $this->arResult["CITY_NAME"] = $arSuggestedCity["NAME"];
                $this->arResult["SUGGESTED_CITY_NAME"] = $arSuggestedCity["NAME"];
                $obUser = new User();
                $arLocation = [
                    "ID" => $arSuggestedCity["ID"],
                    "NAME" => $arSuggestedCity["NAME"],
                ];
                if ($this->arParams["SET_LOCATION"]) $obUser->setUserCity(null, $arLocation);

            } else {
                $this->arResult["CITY_NAME"] = $arUser["UF_LOCATION_NAME"];
            }
        } else {
            $this->arResult["IS_AUTHORIZED"] = false;

            $userCityCookie = $_COOKIE["BITRIX_SM_USER_CITY"];
            $userCityNameCookie = $_COOKIE["BITRIX_SM_USER_CITY_NAME"];
            if ($userCityCookie) {
                $this->arResult["CITY_NAME"] = $userCityNameCookie;

            } else {
                $arSuggestedCity = $obLocation->getSuggestedCity();
                $this->arResult["CITY_NAME"] = $arSuggestedCity["NAME"];
                $this->arResult["SUGGESTED_CITY_NAME"] = $arSuggestedCity["NAME"];

                $APPLICATION->set_cookie("USER_CITY", $arSuggestedCity["ID"]);
                $APPLICATION->set_cookie("USER_CITY_NAME", $arSuggestedCity["NAME"]);
            }

        }

        $this->includeComponentTemplate();
    }
}
