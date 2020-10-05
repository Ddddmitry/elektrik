<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\User;

class ApiBackofficeWorksheetClient extends AjaxComponent
{
    protected function parseRequest() { }

    protected function getWorksheet()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            throw new \Exception('authorization_required');
        }

        $obUser = new User();
        $arClient = $obUser->getClientData();

        $arWorksheetData = [
            "name" => $arClient["NAME"],
            "phone" => $arClient["PHONE"],
            "email" => $arClient["EMAIL"],
            "img" => [
                "src" => $arClient["PICTURE"],
            ],
        ];

        return $arWorksheetData;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        try {
            $result = $this->getWorksheet();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
