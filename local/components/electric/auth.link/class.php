<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class UserAuthLink extends BaseComponent
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
            $this->arResult = array(
                "IS_AUTHORIZED" => true,
                "IS_CONTRACTOR" => $obUser->isContractor(),
                "TYPE" => $obUser->getType(),
                "USER" => array(
                    "ID" => $USER->GetID(),
                    "NAME" => $USER->GetFirstName() . " " . $USER->GetLastName(),
                ),
            );
        } else {
            $this->arResult = array(
                "IS_AUTHORIZED" => false,
            );
        }

        $this->includeComponentTemplate();
    }
}
