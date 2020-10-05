<?php

namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Notification;
use \Electric\Helpers\FileHelper;

class ApiBackofficeRaekAddVerification extends AjaxComponent
{
    private $formData;

    protected function parseRequest()
    {
        //$input = json_decode($this->request->getInput(), true);

        $this->formData = [
            "DOCUMENT"    => $this->request->getFile("document"),
        ];
    }

    /**
     * Создание подтверждения членства в РАЭК и отправка уведомления администратору
     */
    protected function addVerification()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arFilter = array(
            "IBLOCK_ID"     => IBLOCK_RAEK_VERIFICATIONS,
            "PROPERTY_USER" => $userID,
        );
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($dbResult->SelectedRowsCount()) throw new \Exception("verification_already_exists");
        if (!$this->formData["DOCUMENT"]) throw new \Exception("document_file_is_required");

        $fileTempPath = FileHelper::saveFileFromForm($this->formData["DOCUMENT"]);
        
        $obElement = new \CIBlockElement;
        $arFields  = Array(
            "NAME"      => "Подтверждение",
            "IBLOCK_ID" => IBLOCK_RAEK_VERIFICATIONS,
            "PREVIEW_PICTURE" => \CFile::MakeFileArray($fileTempPath["tmp_name"]),
        );

        if ($elementID = $obElement->Add($arFields)) {
            unlink($fileTempPath);

            $arProperties = [
                "USER" => $userID,
            ];
            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            $arMailData = []; // @todo уточняется содержание уведомления
            Notification::sendNotification("ELECTRIC_RAEK_NEW_VERIFICATION", $arMailData);

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
            $result = $this->addVerification();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
