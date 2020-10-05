<?php
namespace Api\Components;

use Electric\Component\AjaxComponent;
use Electric\Helpers\DataHelper;
use Electric\Helpers\FileHelper;
use Electric\Notification;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;


class ApiBackofficeTicketsAdd extends AjaxComponent
{
    private $title, $text, $documents;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->title = $input["title"];
        $this->text = $input["text"];
        $this->documents = $input["documents"];
    }

    /**
     * Создание обращения в поддержку
     */
    protected function addTicket()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "NAME" => $this->title ? $this->title : "Обращение",
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_TICKETS,
            "ACTIVE_FROM" => (new \Bitrix\Main\Type\DateTime)->toString(),
            "PREVIEW_TEXT" => $this->text,
        );

        if ($elementID = $obElement->Add($arFields)) {
            $arProperties = [
                "USER" => $userID,
            ];

            if (isset($this->documents)) {
                $arProperties["FILES"] = [];
                foreach ($this->documents as $arDocumentData) {
                    $filePath = FileHelper::saveFile($arDocumentData["detail_img"]);
                    $arDocument["VALUE"] = \CFile::MakeFileArray($filePath);
                    $arDocument["DESCRIPTION"] = $arDocumentData["title"];
                    $arProperties["FILES"][] = $arDocument;
                }
            }

            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            $arMailData = [
                "USER_ID" => $userID,
                "TICKET_ID" => $elementID,
                "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_BACKOFFICE_TICKETS."&type=content&ID=".$elementID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_TICKET", $arMailData);

            $arResult = [
                "id" => $elementID,
                "newTicket" => [
                    "number" => $elementID,
                    "date" => DataHelper::getFormattedDate($arFields["ACTIVE_FROM"]),
                    "text" => $arFields["PREVIEW_TEXT"],
                    "answer" => "",
                    "answerDate" => "",
                ],
            ];

            return $arResult;
        } else {

            throw new \Exception("element_creation_error");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addTicket();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
