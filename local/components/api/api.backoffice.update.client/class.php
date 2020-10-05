<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeUpdateClient extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateContacts()
    {
        global $USER;

        $arFields = [];
        if ($this->input["img"]) {
            $fileTempPath = FileHelper::saveFile($this->input["img"]["src"]);
            $arFields["UF_CLIENT_IMAGE"] = \CFile::MakeFileArray($fileTempPath);
        }

        if ($this->input["status"]) {
            $arFields["UF_FREE"] = ($this->input["status"] == "true") ? "0" : "1";
        }

        if ($this->input["name"]) {
            $arName = explode(" ", $this->input["name"]);
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
        if ($this->input["phone"]) {
            $arFields["PERSONAL_PHONE"] = $this->input["phone"];
        }
        if ($this->input["email"]) {
            $arFields["EMAIL"] = $this->input["email"];
            $arFields["LOGIN"] = $this->input["email"];
        }

        $obUser = new \CUser();
        if (!$obUser->Update($USER->GetID(), $arFields)) {
            throw new \Exception($obUser->LAST_ERROR);
        };

        return true;
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
