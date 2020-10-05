<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeUpdateInfo extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);

        //$this->input = $this->request->getPostList()->toArray();
    }

    protected function updateInfo()
    {
        $arFields = [];
        $arProperties = [];
        $arHLReferences = [];

        if (isset($this->input["about"])) {
            $arFields["PREVIEW_TEXT"] = $this->input["about"];
        }

        if (isset($this->input["experience"])) {
            foreach ($this->input["experience"] as $arJobData) {
                $arJob = [
                    "UF_NAME" => $arJobData["place"],
                    "UF_NOW" => $arJobData["currentPlace"],
                ];
                $obStartDate = new \DateTime();
                $obStartDate->setDate($arJobData["yearStart"], $arJobData["monthStartNumber"] + 1, 1);
                $arJob["UF_START_DATE"] = $obStartDate->format("d.m.Y");
                if (!$arJobData["currentPlace"]) {
                    $obEndDate = new \DateTime();
                    $obEndDate->setDate($arJobData["yearEnd"], $arJobData["monthEndNumber"] + 1, 1);
                    $arJob["UF_END_DATE"] = $obEndDate->format("d.m.Y");
                }

                $arHLReferences["JOBS"][] = $arJob;
            }

        }

        if (isset($this->input["qualification"])) {
            $arProperties["SKILL"] = $this->input["qualification"];
        }

        if (isset($this->input["languages"])) {
            $arHLReferences["LANGUAGES"] = [];
            foreach ($this->input["languages"] as $arLanguageData) {
                if ($arLanguageData["language"]["ID"] && $arLanguageData["skill"]["ID"]) {
                    $arHLReferences["LANGUAGES"][] = [
                        "UF_LANGUAGE" => $arLanguageData["language"]["ID"],
                        "UF_LEVEL" => $arLanguageData["skill"]["ID"],
                    ];
                }
            }
        }

        $obContractor = new Contractor();
        try {
            $obContractor->updateContractor(null, $arFields, $arProperties, $arHLReferences);
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
            $result = $this->updateInfo();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
