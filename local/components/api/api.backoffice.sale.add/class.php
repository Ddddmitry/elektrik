<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use Electric\Distributor;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;

class ApiBackofficeSaleAdd extends AjaxComponent
{
    private $formData;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();
        $this->formData = [
            "NAME" => $input['NAME'],
            "PREVIEW_TEXT" => $input['PREVIEW_TEXT'],
            "PREVIEW_PICTURE" => $this->request->getFile("PREVIEW_PICTURE"),
        ];
    }

    /**
     * Создание новой акции акции
     *
     */
    protected function addSale()
    {
        if(!check_bitrix_sessid())
            throw new \Exception("Session error");

        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");
        $arImage = FileHelper::saveFileFromForm($this->formData["PREVIEW_PICTURE"]);

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => \ConvertTimeStamp(time(), "FULL"),
            "NAME" => $this->formData["NAME"],
            "CODE" => \Cutil::translit($this->formData["NAME"], "ru"),
            "IBLOCK_ID" => IBLOCK_SALES,
            "PREVIEW_TEXT" => $this->formData["PREVIEW_TEXT"],
            "PREVIEW_PICTURE" => $arImage,
        );

        if ($elementID = $obElement->Add($arFields)) {

            $arProperties = [
                "USER" => $userID,
            ];

            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            /*$arMailData = [
                "USER_ID" => $userID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_ARTICLE", $arMailData);*/

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
            $result = $this->addSale();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
