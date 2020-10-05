<?php
namespace Electric\Component;

use \Electric\Component\BaseComponent;
use \Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class Notifications extends BaseComponent
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
            $arNotifications = $obUser->getNotifications();

            $this->arResult = array(
                "IS_AUTHORIZED" => true,
                "NOTIFICATIONS" => $arNotifications,
                "USER" => array(
                    "ID" => $USER->GetID(),
                    "NAME" => $USER->GetFirstName() . " " . $USER->GetLastName(),
                ),
            );

            $this->includeComponentTemplate();
        }

    }
}
