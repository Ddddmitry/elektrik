<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiUserEmaster extends AjaxComponent
{
    private $arDocumentsMultiple = [], $arDocumentsSingle = [];

    protected function parseRequest()
    {
        $input = $this->request->getFileList();
        $arInputFilesMultiple = $input["sertificateElectric-pasport"];
        $arInputFilesSingle = $input["sertificateElectric-pasport-alone"];

        if ($arInputFilesMultiple) {
            foreach ($arInputFilesMultiple["size"] as $key => $fileSize) {
                if ($fileSize) {
                    $this->arDocumentsMultiple[] = [
                        "name" => $arInputFilesMultiple["name"][$key],
                        "type" => $arInputFilesMultiple["type"][$key],
                        "tmp_name" => $arInputFilesMultiple["tmp_name"][$key],
                    ];
                }
            }
        }

        if ($arInputFilesSingle) {
            foreach ($arInputFilesSingle["size"] as $key => $fileSize) {
                if ($fileSize) {
                    $this->arDocumentsSingle[] = [
                        "name" => $arInputFilesSingle["name"][$key],
                        "type" => $arInputFilesSingle["type"][$key],
                        "tmp_name" => $arInputFilesSingle["tmp_name"][$key],
                    ];
                }
            }
        }
    }

    /**
     * Создание заявки на сертификацию Электрик.ру
     */
    protected function addRequest()
    {
        if (!$this->arDocumentsMultiple && !$this->arDocumentsSingle) throw new \Exception("no_files_uploaded");

        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arFilter = array(
            "IBLOCK_ID" => IBLOCK_EMASTER_REQUESTS,
            "PROPERTY_USER" => $userID,
        );
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($dbResult->SelectedRowsCount()) throw new \Exception("request_already_exists");

        $arDocuments = array_merge($this->arDocumentsMultiple, $this->arDocumentsSingle);
        $arDocumentsProperty = [];
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/";
        foreach ($arDocuments as $key => $arDocument) {
            $file = file_get_contents($arDocument["tmp_name"]);
            file_put_contents($uploadPath . $arDocument["name"], $file);
            $arDocumentsProperty["n" . $key] = \CFile::MakeFileArray($uploadPath . $arDocument["name"]);
        }

        $obElement = new \CIBlockElement;
        $arFields = [
            "NAME" => "Заявка на сертификацию",
            "IBLOCK_ID" => IBLOCK_EMASTER_REQUESTS,
        ];
        if ($elementID = $obElement->Add($arFields)) {
            $arProperties = [
                "USER" => $userID,
                "DOCUMENTS" => $arDocumentsProperty,
            ];
            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            foreach ($arDocumentsProperty as $arDocumentProperty) {
                unlink($arDocumentProperty["tmp_name"]);
            }

            $arMailData = []; // @todo уточняется содержание уведомления
            Notification::sendNotification("ELECTRIC_EMASTER_NEW_REQUEST", $arMailData);

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
