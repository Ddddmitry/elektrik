<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class ChangePasswordForm extends BaseComponent
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
