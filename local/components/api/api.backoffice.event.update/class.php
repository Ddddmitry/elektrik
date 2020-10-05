<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Distributor;
use Electric\Events;
use Electric\Helpers\FileHelper;


class ApiBackofficeEventUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {

        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateEvent()
    {

        $obDistributor =  new Distributor();
        if(!$obDistributor->isMy($this->input["eventID"]))
            return false;

        $obEvent = new Events();
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



        try {
            $obEvent->updateEvent($this->input["eventID"], $arFields, $arProperties);
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
            $result = $this->updateEvent();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
