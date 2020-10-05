<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Distributor;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeDistributorUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateDistributor()
    {
        $arFields = [];
        $arFields["NAME"] = $this->input["NAME"];
        unset($this->input["NAME"]);

        $previewPicture = $this->request->getFile("PREVIEW_PICTURE");
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/distibutors/";
        $file = file_get_contents($previewPicture["tmp_name"]);
        file_put_contents($uploadPath . $previewPicture["name"], $file);
        $arFields["PREVIEW_PICTURE"] = \CFile::MakeFileArray($uploadPath . $previewPicture["name"]);
        unset($this->input["PREVIEW_PICTURE"]);

        $arProperties = [];
        foreach ($this->input as $code=>$value){
            $arProperties[$code] = $value;
        }

        $obDistributor = new Distributor();
        try {
            $obDistributor->updateDistributor(null, $arFields, $arProperties);
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
            $result = $this->updateDistributor();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
