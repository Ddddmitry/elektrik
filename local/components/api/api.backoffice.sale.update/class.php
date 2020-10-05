<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Distributor;
use Electric\Educations;
use Electric\Events;
use Electric\Helpers\FileHelper;
use Electric\Sales;


class ApiBackofficeSaleUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {

        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateSale()
    {

        if(!check_bitrix_sessid())
            throw new \Exception("Session error");

        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $obSale = new Sales();
        /**Обновление акции*/
        $arFields = [];
        $arFields["NAME"] = $this->input["NAME"];
        $arImage = FileHelper::saveFileFromForm($this->request->getFile("PREVIEW_PICTURE"),"events");
        $arFields["PREVIEW_PICTURE"] = $arImage;
        $arFields["PREVIEW_TEXT"] = $this->input["PREVIEW_TEXT"];

        $arProperties = [];
        $arProperties["USER"] = $userID;


        try {
            $obSale->updateSale($this->input["saleID"], $arFields, $arProperties);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        return true;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->updateSale();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
