<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\DataHelper;

class ApiBackofficeTickets extends AjaxComponent
{

    protected function parseRequest() { }

    protected function getTickets()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $arSelect = ["ID", "NAME", "IBLOCK_ID", "ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT", "PROPERTY_ANSWER_DATE", "PROPERTY_IS_CLOSED"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_TICKETS,
            "PROPERTY_USER" => $userID,
        ];
        $arTickets = [];
        $rsTickets = \CIBlockElement::GetList(["ACTIVE_FROM" => "DESC"], $arFilter, false, false, $arSelect);
        while ($arElement = $rsTickets->Fetch()) {
            $arTickets[] = [
                "title" => $arElement["NAME"],
                "number" => $arElement["ID"],
                "date" => DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]),
                "text" => $arElement["PREVIEW_TEXT"],
                "answer" => $arElement["DETAIL_TEXT"],
                "answerDate" => DataHelper::getFormattedDate($arElement["PROPERTY_ANSWER_DATE_VALUE"]),
                "isClosed" => $arElement["PROPERTY_IS_CLOSED_VALUE"] ? true : false,
            ];
        }

        return $arTickets;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result["TICKETS"] = $this->getTickets();

            $result["TICKET_AVAILABLE"] = true;

            foreach ($result["TICKETS"] as $arTicket) {

                if (!$arTicket["isClosed"]) {

                    $result["TICKET_AVAILABLE"] = false;

                    break;
                }
            }

        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
