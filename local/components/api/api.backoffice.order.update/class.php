<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Order;
use \Electric\Helpers\FileHelper;


class ApiBackofficeOrderUpdate extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();
    }

    protected function updateOrder()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $arFields = [];
        $arFields["CONTRACTOR"] = $userID;
        $arFields["ORDER"] = $this->input["order"];
        $arFields["USER"] = $userID;
        $obOrder = new Order();
        switch ($this->input["action"]){
            case "applyOrder":
                try {
                    $arFields["STATUS"] = "inwork";
                    $obOrder->applyOrder($arFields);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
                break;
            case "stayReviewOrder":
                try {
                    $obOrder->stayReviewOrder($arFields);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
                break;
            case "completeOrder":
                try {
                    $arFields["STATUS"] = "completed";
                    $arFields["REVIEW"] = $this->input['review'];
                    $arFields["MARK"] = $this->input['mark'];
                    $obOrder->completeOrder($arFields);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
                break;
            case "cancelOrder":
                try {
                    $arFields["STATUS"] = "canceled";
                    $arFields["CANCEL_REASONS"] = $this->input['reason'];
                    $arFields["CANCEL_REASONS_DESCRIPTION"] = $this->input['reasonText'];
                    $obOrder->cancelOrder($arFields);
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
                break;
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
            $result = $this->updateOrder();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
