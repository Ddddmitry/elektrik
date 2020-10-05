<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\User;

class ApiBackofficeUserClient extends AjaxComponent
{

    protected function parseRequest() { }

    protected function getUserData()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $obUser = new User();
        $arUser = $obUser->getClientData($userID);

        $arUserData = [
            "name" => $arUser["NAME_SHORT"],
            "phone" => $arUser["PHONE"],
            "email" => $arUser["EMAIL"],
        ];

        $arResult = [
            "user" => $arUserData,
        ];

        return $arResult;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getUserData();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
