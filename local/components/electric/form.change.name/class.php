<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class ChangeNameForm extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->includeComponentTemplate();
        }
    }
}
