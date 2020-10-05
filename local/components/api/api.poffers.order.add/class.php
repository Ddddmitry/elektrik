<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use Electric\Distributor;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;

class ApiPoffersOrderAdd extends AjaxComponent
{
    private $formData;
    private $input;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();
    }

    /**
     * Создание новой акции акции
     *
     */
    protected function addPofferOrder()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $dbResult = \CIBlockElement::GetList([], ["IBLOCK_ID"=>IBLOCK_PARTNERS_OFFERS_ORDERS,"PROPERTY_USER" => $userID, "PROPERTY_POFFER_ID" => $this->input["pofferId"]], false, false, ["ID"]);
        if ($dbResult->SelectedRowsCount()) throw new \Exception("poffer_order_already_exists");

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => \ConvertTimeStamp(time(), "FULL"),
            "NAME" => $this->input["name"],
            "CODE" => \Cutil::translit($this->input["name"], "ru"),
            "IBLOCK_ID" => IBLOCK_PARTNERS_OFFERS_ORDERS,

        );

        if ($elementID = $obElement->Add($arFields)) {

            $arProperties = [
                "USER" => $userID,
                "PHONE" => $this->input["phone"],
                "EMAIL" => $this->input["email"],
                "POFFER_ID" => $this->input["pofferId"]
            ];

            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            /*$arMailData = [
                "USER_ID" => $userID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_POFFER_ORDER_ADD", $arMailData);*/

            return $elementID;
        } else {
            if (!$userID) throw new \Exception("element_creation_error");
            else throw new \Exception($obElement->LAST_ERROR);
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addPofferOrder();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
