<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class ChangeCityForm extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    public function executeComponent()
    {
        $arPreferredCities = [];
        foreach (PREFERRED_CITIES as $name => $code) {
            $arPreferredCities[] = [
                "ID" => $code,
                "NAME" => $name,
                "PATH" => $name,
            ];
        }

        if ($this->StartResultCache(false, [$this->page])) {
            $this->arResult["PREFERRED_CITIES"] = $arPreferredCities;
            $this->includeComponentTemplate();
        }

    }
}
