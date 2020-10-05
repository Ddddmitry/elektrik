<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class MenuMain extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $menuFirst = new \CMenu("main1");
        $menuFirst->Init($APPLICATION->GetCurDir(), true);
        $arMenuFirst = [];
        foreach ($menuFirst->arMenu as $arItem) {
            $arMenuFirst[] = [
                "name" => $arItem[0],
                "link" => $arItem[1],
            ];
        }

        $menuSecond = new \CMenu("main2");
        $menuSecond->Init($APPLICATION->GetCurDir(), true);
        $arMenuSecond = [];
        foreach ($menuSecond->arMenu as $arItem) {
            $arMenuSecond[] = [
                "name" => $arItem[0],
                "link" => $arItem[1],
            ];
        }

        $menuMob = new \CMenu("mainmob");
        $menuMob->Init($APPLICATION->GetCurDir(), true);
        $arMenuMob = [];
        foreach ($menuMob->arMenu as $arItem) {
            $arMenuMob[] = [
                "name" => $arItem[0],
                "link" => $arItem[1],
            ];
        }


        $this->arResult = array(
            "MENU_FIRST" => $arMenuFirst,
            "MENU_SECOND" => $arMenuSecond,
            "MENU_MOB" => $arMenuMob,
            "IS_AUTHORIZED" => false
        );

        global $USER;
        if ($USER->IsAuthorized()) {
            $obUser = new User();
            $this->arResult["IS_AUTHORIZED"] = true;
            $this->arResult["TYPE"] = $obUser->getType();
            $this->arResult["USER"] = array(
                "ID" => $USER->GetID(),
                "NAME" => $USER->GetFirstName() . " " . $USER->GetLastName(),
            );
        }

        $this->includeComponentTemplate();
    }
}
