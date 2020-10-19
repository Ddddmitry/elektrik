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
use Electric\User;


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
        $phoneFrom = "";
        $obUser = new User();
        switch ($obUser->getType($userID)){
            case "client":
                $obClient = new Client();
                $arClient = $obClient->getClientData(["PROPERTY_USER" => $userID]);
                $phoneFrom = $arClient["PROPERTIES"]["PHONE"]["VALUE"];
                unset($arClient);
                unset($obClient);
                break;
            case "contractor":
                $obContractor = new Contractor();
                $arContractor = $obContractor->getContractorData(["PROPERTY_USER" => $userID]);
                $phoneFrom = $arContractor["PROPERTIES"]["PHONE"]["VALUE"];
                unset($arContractor);
                unset($obContractor);
                break;
        }

        $obContractor = new Contractor();
        $arContractor = $obContractor->getContractorData(["ID" => $this->input["contractor"]]);


        $phoneContact = str_replace(["+"," ","(",")","-"],"", $phoneFrom);
        $phoneOperator = str_replace(["+"," ","(",")","-"],"", $arContractor["PROPERTIES"]["PHONE"]["VALUE"]);
        

        if(!$phoneContact || !$phoneOperator)
            return false;

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
