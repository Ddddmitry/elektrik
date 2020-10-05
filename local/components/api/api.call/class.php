<?php
namespace Api\Components;

use Electric\Client;
use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Contractor;
use Electric\Order;
use \Electric\Helpers\FileHelper;
use Electric\Comagic;


class ApiCall extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function makeCall()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $obOrder = new Order();
        $arOrder = $obOrder->getOrderById($this->input["order"]);
        $obContractor = new Contractor();
        $arContractor = $obContractor->getContractorData();

        $phoneContact = str_replace(["+"," ","(",")","-"],"", $arOrder["PROPERTIES"]["CONTACT_PHONE"]["VALUE"]);
        $phoneOperator = str_replace(["+"," ","(",")","-"],"", $arContractor["PROPERTIES"]["PHONE"]["VALUE"]);
        $comagic = new \Electric\Comagic();
        $result = $comagic->makeCall($this->input["contact"],$phoneContact,$phoneOperator);
        if($result->call_session_id){
            return true;
        }
        return false;
    }

    protected function makeCallProfile()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $obClient = new Client();
        $arClient = $obClient->getClientData();
        $obContractor = new Contractor();
        $arContractor = $obContractor->getContractorData(["ID" => $this->input["contractor"]]);


        $phoneContact = str_replace(["+"," ","(",")","-"],"", $arClient["PROPERTIES"]["PHONE"]["VALUE"]);
        $phoneOperator = str_replace(["+"," ","(",")","-"],"", $arContractor["PROPERTIES"]["PHONE"]["VALUE"]);
        
        $comagic = new \Electric\Comagic();
        $result = $comagic->makeCall($this->input["contact"],$phoneContact,$phoneOperator);
        if($result->call_session_id){
            return true;
        }
        return false;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            if($this->input['action'] == "makeCall")
                $result = $this->makeCall();
            if($this->input['action'] == "makeCallProfile")
                $result = $this->makeCallProfile();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
