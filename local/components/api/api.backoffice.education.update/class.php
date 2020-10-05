<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Distributor;
use Electric\Educations;
use Electric\Events;
use Electric\Helpers\FileHelper;


class ApiBackofficeEducationUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {

        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateEducation()
    {

        $obDistributor =  new Distributor();
        if(!$obDistributor->isMy($this->input["eventID"]))
            return false;

        $obEducation = new Educations();
        /**Обновление мероприятия*/
        $arFields = [];
        $arFields["NAME"] = $this->input["NAME"];
        $arImage = FileHelper::saveFileFromForm($this->request->getFile("PREVIEW_PICTURE"),"events");
        $arFields["PREVIEW_PICTURE"] = $arImage;
        $arFields["ACTIVE_FROM"] = $this->input["ACTIVE_FROM"];
        $arFields["PREVIEW_TEXT"] = $this->input["PREVIEW_TEXT"];

        $arProperties = [];
        $arProperties["ADDRESS"] = $this->input["ADDRESS"];
        $arProperties["TYPE"] = $this->input["TYPE"];
        $arProperties["THEME"] = $this->input["THEME"];
        $arProperties["FORMAT"] = $this->input["FORMAT"];


        try {
            $obEducation->updateEducation($this->input["educationID"], $arFields, $arProperties);
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
            $result = $this->updateEducation();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
