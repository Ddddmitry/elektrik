<?php
namespace Api\Components;

use Electric\Client;
use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;
use \Electric\Location;

class ApiBackofficeUpdateContacts extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        //$this->input = json_decode($this->request->getInput(), true);
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateContacts()
    {
        $arFields = [];
        $arProperties = [];
        if ($this->request->getFile("PREVIEW_PICTURE")) {
            $arImage = FileHelper::saveFileFromForm($this->request->getFile("PREVIEW_PICTURE"),$this->input["TYPE"]);
            $arFields["PREVIEW_PICTURE"] = $arImage;
        }

        if ($this->input["NAME"]) {
            $arFields["NAME"] = $this->input["NAME"];
        }
        if ($this->input["ADDRESS"]) {
            $coords = Location::getCoordsByAddress($this->input["ADDRESS"]);
            $arProperties["ADDRESS"] = $this->input["ADDRESS"];
            $arProperties["ADDRESS_COORDS"] = $coords;
        }
        if ($this->input["PHONE"]) {
            $arProperties["PHONE"] = $this->input["PHONE"];
        }
        if ($this->input["EMAIL"]) {
            $arProperties["EMAIL"] = $this->input["EMAIL"];
        }
        if ($this->input["SITE"]) {
            $arProperties["SITE"] = $this->input["SITE"];
        }
        if ($this->input["TELEGRAM"]) {
            $arProperties["TELEGRAM"] = $this->input["TELEGRAM"];
        }
        if ($this->input["WHATSAPP"]) {
            $arProperties["WHATSAPP"] = $this->input["WHATSAPP"];
        }
        if ($this->input["VIBER"]) {
            $arProperties["VIBER"] = $this->input["VIBER"];
        }

        if($this->input["TYPE"]){
            if($this->input["TYPE"] == "clients"){
                $obClient = new Client();
                try {
                    $obClient->updateClient(null, $arFields, $arProperties);
                    $this->updateUser();
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
            if($this->input["TYPE"] == "contractors"){
                $obContractor = new Contractor();
                try {
                    $obContractor->updateContractor(null, $arFields, $arProperties);
                    $this->updateUser();
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
        }else{
            throw new \Exception("Incorrect type of user");
        }



        return true;
    }
    protected function updateUser(){

        global $USER;

        if ($this->request->getFile("PREVIEW_PICTURE")) {
            $arImage = FileHelper::saveFileFromForm($this->request->getFile("PREVIEW_PICTURE"),$this->input["TYPE"]);
            $arFields["UF_CLIENT_IMAGE"] = $arImage;
        }
        if ($this->input["NAME"]) {
            $arName = explode(" ", $this->input["NAME"]);
            switch (count($arName)) {
                case 3:
                    $arFields["LAST_NAME"] = $arName[0];
                    $arFields["NAME"] = $arName[1];
                    $arFields["SECOND_NAME"] = $arName[2];
                    break;
                case 2:
                    $arFields["LAST_NAME"] = $arName[0];
                    $arFields["NAME"] = $arName[1];
                    $arFields["SECOND_NAME"] = "";
                    break;
                default:
                    $arFields["LAST_NAME"] = "";
                    $arFields["NAME"] = $this->input["name"];
                    $arFields["SECOND_NAME"] = "";
                    break;

            }
        }
        if ($this->input["PHONE"]) {
            $arFields["PERSONAL_PHONE"] = $this->input["PHONE"];
            $arFields["LOGIN"] = $this->input["PHONE"];
        }
        if ($this->input["EMAIL"]) {
            $arFields["EMAIL"] = $this->input["EMAIL"];

        }

        $obUser = new \CUser();
        if (!$obUser->Update($USER->GetID(), $arFields)) {
            throw new \Exception($obUser->LAST_ERROR);
        };

    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->updateContacts();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
