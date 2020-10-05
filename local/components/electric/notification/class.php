<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class Notification extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $obUser = new User();
            if ($obUser->isContractor() && !$obUser->isCertified() && !$obUser->hasCertificationRequest()) {
                $this->includeComponentTemplate();
            }
        }
    }
}
