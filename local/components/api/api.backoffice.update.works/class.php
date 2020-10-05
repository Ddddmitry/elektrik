<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeUpdateWorks extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);
    }

    protected function updateServices()
    {
        $arFields = [];
        $arProperties = [];

        if (isset($this->input["works"])) {

            foreach ($this->input["works"] as $key => $arWorkData) {

                foreach ($arWorkData["images"] as $idx=>$image) {
                    if ($image["is_base64"]) {
                        $filePath = FileHelper::saveFile($image["detail_img"]);
                    } else {
                        $filePath = $image["detail_img"];
                    }

                    $arWork["VALUE"] = \CFile::MakeFileArray($filePath);
                    if($idx == 0)
                        $arFields["PREVIEW_PICTURE"] = $arWork["VALUE"];

                    $arWork["DESCRIPTION"] = $arWorkData["title"];
                    $arProperties["MORE_PHOTO"]["n" . $idx] = $arWork;
                }

                $arFields["NAME"] = $arWorkData["title"];
                $arFields["PREVIEW_TEXT"] = $arWorkData["description"];
                $arFields["DETAIL_TEXT"] = $arWorkData["description"];

                $arWorks[] = [
                    "FIELDS" => $arFields,
                    "PROPERTIES" => $arProperties
                ];
            }


        }

        $obContractor = new Contractor();
        try {
            $obContractor->updateWorks(null, $arWorks);
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
            $result = $this->updateServices();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
