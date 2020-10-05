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


class ApiBackofficeSaleUpdateArchive extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {

        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateSale()
    {

        $obDistributor =  new Distributor();
        if(!$obDistributor->isMy($this->input["eventID"]))
            return false;

        $obSale = new Sales();

        /**Добавление или удаление из архива*/
        $isArchive = false;
        if($this->input["archive"] == "Y")
            $isArchive = true;
        try {
            $obSale->updateArchive($this->input["saleID"],$isArchive);
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
