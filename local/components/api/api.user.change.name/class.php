<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiUserChangeName extends AjaxComponent
{
    private $arFormData;

    protected function parseRequest()
    {
        $inputPost = $this->request->getPostList();

        $this->arFormData = [
            "NEW_NAME" => $inputPost["edfio-fio"],
        ];
    }

    /**
     * Изменение пароля пользователя
     */
    protected function changeUserName()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arFullName = explode(" ", $this->arFormData["NEW_NAME"]);
        if (count($arFullName) == 3) {
            $arFields = [
                "NAME" => $arFullName[1],
                "SECOND_NAME" => $arFullName[2],
                "LAST_NAME" => $arFullName[0],
            ];
        } elseif (count($arFullName) == 2) {
            $arFields = [
                "NAME" => $arFullName[1],
                "LAST_NAME" => $arFullName[0],
            ];
        } else {
            $arFields = [
                "NAME" => $this->arFormData["NEW_NAME"],
            ];
        }
        $obUser = new \CUser;
        if ($obUser->Update($userID, $arFields)) {

            return true;
        } else {
            throw new \Exception($obUser->LAST_ERROR);
        }


    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = ["SUCCESS" => $this->changeUserName()];
            $result["MESSAGE"] = [
                "TITLE" => "ФИО успешно изменёно",
            ];
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
