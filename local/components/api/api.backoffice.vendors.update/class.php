<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Vendor;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;

class ApiBackofficeVendorUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateVendor()
    {
        $arFields = [];
        $arFields["NAME"] = $this->input["NAME"];
        unset($this->input["NAME"]);

        $arImage = FileHelper::saveFileFromForm($this->request->getFile("PREVIEW_PICTURE"),"events");
        $arFields["PREVIEW_PICTURE"] = $arImage;

        unset($this->input["PREVIEW_PICTURE"]);

        $arProperties = [];
        foreach ($this->input as $code=>$value){
            $arProperties[$code] = $value;
        }

        $obVendor = new Vendor();
        try {
            $obVendor->updateVendor(null, $arFields, $arProperties);
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
            $result = $this->updateVendor();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
