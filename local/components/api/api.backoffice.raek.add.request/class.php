<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiBackofficeRaekAddRequest extends AjaxComponent
{
    private $formData;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);
        $this->formData = [
            "NAME" => $input['name'],
            "COMPANY" => $input['company'],
            "PHONE" => $input['phone'],
            "EMAIL" => $input['email'],
        ];
    }

    /**
     * Создание заявки на членство в РАЭК и отправка уведомления администратору
     */
    protected function addRequest()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arFilter = array(
            "IBLOCK_ID" => IBLOCK_RAEK_REQUESTS,
            "PROPERTY_USER" => $userID,
        );
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($dbResult->SelectedRowsCount()) throw new \Exception("request_already_exists");

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "NAME" => $this->formData["NAME"],
            "IBLOCK_ID" => IBLOCK_RAEK_REQUESTS,
        );

        if ($elementID = $obElement->Add($arFields)) {
            $arProperties = [
                "USER" => $userID,
                "NAME" => $this->formData["NAME"],
                "COMPANY" => $this->formData["COMPANY"],
                "PHONE" => $this->formData["PHONE"],
                "EMAIL" => $this->formData["EMAIL"],
            ];
            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            $arMailData = array(
                "NAME" => $this->formData["NAME"],
                "COMPANY" => $this->formData["COMPANY"],
                "PHONE" => $this->formData["PHONE"],
                "EMAIL" => $this->formData["EMAIL"],
            );
            Notification::sendNotification("ELECTRIC_RAEK_NEW_REQUEST", $arMailData);

            return $elementID;
        } else {
            if (!$userID) throw new \Exception("element_creation_error");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addRequest();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
