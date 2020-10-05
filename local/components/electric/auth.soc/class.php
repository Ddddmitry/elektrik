<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class AuthSoc extends BaseComponent
{

    private $arIcons = [
        "facebook" => "fb",
        "vkontakte" => "vk",
        "odnoklassniki" => "ok",
    ];

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        \CModule::IncludeModule("socialservices");
        $arResult["AUTH_SERVICES"] = false;

        $oAuthManager = new \CSocServAuthManager();
        $arAuthServices = $oAuthManager->GetActiveAuthServices($arResult);

        \CUtil::InitJSCore(["window"]);
        $GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/socialservices/ss.js");

        $arAuthData = [];
        foreach($arAuthServices as $arService) {

            //$arService["ONCLICK"] = str_replace("2Fauth%","2Fregister%",$arService["ONCLICK"]);
            $arAuthData[] = [
                "ONCLICK" => str_replace("/authorize?", "/authorize?user-type=444&", $arService["ONCLICK"]),
                "ICON" => $this->arIcons[$arService["ICON"]],
            ];
        }

        $this->arResult["AUTH_DATA"] = $arAuthData;

        $this->includeComponentTemplate();
    }
}
