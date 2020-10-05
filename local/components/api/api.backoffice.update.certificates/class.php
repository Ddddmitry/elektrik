<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeUpdateCertificates extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);
    }

    protected function updateCertificates()
    {
        $arFields = [];
        $arProperties = [];
        $arHLReferences = [];

        if (isset($this->input["sertificates"])) {
            foreach ($this->input["sertificates"] as $arCertificateData) {
                if ($arCertificateData["is_base64"]) {
                    $filePath = FileHelper::saveFile($arCertificateData["detail_img"]);
                    //$arCertificate["TEMP_FILE"] = $filePath;
                } else {
                    $filePath = $arCertificateData["detail_img"];
                }
                $arCertificate["VALUE"] = \CFile::MakeFileArray($filePath);
                $arCertificate["DESCRIPTION"] = $arCertificateData["title"] . "||" . $arCertificateData["date"];
                $arProperties["DOCUMENTS"][] = $arCertificate;
            }

            if (!$arProperties["DOCUMENTS"]) {
                $arProperties["DOCUMENTS"] = ["n0" => [
                    "VALUE" => ["del" => "Y"],
                ]];
            }
        }
        if (isset($this->input["education"])) {
            $arHLReferences["EDUCATIONS"] = [];
            foreach ($this->input["education"] as $arEducationData) {
                $arEducation = [
                    "UF_NAME" => $arEducationData["place"],
                    "UF_STATUS" => $arEducationData["type"],
                ];
                $arHLReferences["EDUCATIONS"][] = $arEducation;
            }
        }
        if (isset($this->input["courses"])) {
            $arHLReferences["COURSES"] = [];
            foreach ($this->input["courses"] as $arCourseData) {
                $arCourse = [
                    "UF_NAME" => $arCourseData["name"],
                ];
                $arHLReferences["COURSES"][] = $arCourse;
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
            $result = $this->updateCertificates();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
