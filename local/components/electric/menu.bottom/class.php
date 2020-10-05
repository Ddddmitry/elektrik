<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class MenuBottom extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $menuBottom = new \CMenu("bottom");
        $menuBottom->Init($APPLICATION->GetCurDir(), true);
        $arMenuBottom = [];
        foreach ($menuBottom->arMenu as $arItem) {
            $arMenuBottom[] = [
                "name" => $arItem[0],
                "link" => $arItem[1],
            ];
        }

        $this->arResult = array(
            "MENU_BOTTOM" => $arMenuBottom,
        );

        $this->includeComponentTemplate();
    }
}
