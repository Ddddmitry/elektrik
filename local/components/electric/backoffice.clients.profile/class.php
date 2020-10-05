<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Client;
use \Electric\Component\BaseComponent;
use Electric\Contractor;
use Electric\Order;
use Electric\Vendor;
use \Electric\Helpers\DataHelper;

class ClientsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arClient;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных клиента
     *
     * @return array|bool
     */
    protected function getClient()
    {
        global $USER;
        $this->curUserId = $USER->GetID();
        $obClient = new Client();

        $arClient = $obClient->getClientData();

        if ($arClient) {

            if(!$arClient["PREVIEW_PICTURE"])
                $arClient["PREVIEW_PICTURE"]["SRC"] = "/local/templates/elektrik/images/contractor-empty.png";

            $obOrder = new Order();
            $arOrders = $obOrder->getClientsOrders();
            $arClient["ORDERS"] = $arOrders["ORDERS"];
            $arClient["STATUS"] = $arOrders["STATUS"];
            $obContractor = new Contractor();
            foreach ($arClient["ORDERS"] as &$arOrder) {

                if($arOrder["PROPERTIES"]["STATUS"]["VALUE"] == ENUM_VALUE_ORDERS_STATUS_INWORK
                    || $arOrder["PROPERTIES"]["STATUS"]["VALUE"] == ENUM_VALUE_ORDERS_STATUS_COMPLETED
                    || $arOrder["PROPERTIES"]["STATUS"]["VALUE"] == ENUM_VALUE_ORDERS_STATUS_CANCELED_COMPLAINT){
                    $arContractor = $obContractor->getActualContractor($arOrder["PROPERTIES"]["USER_CONTRACTOR"]["VALUE"],["DETAIL_PAGE_URL"]);
                    $arOrder["PROPERTIES"]["USER_CONTRACTOR"]["DATA"] = $arContractor;
                }
            }

            unset($obContractor);

            $this->arClient = $arClient;

            return true;
        }

        return false;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        if ($this->StartResultCache()) {
            if ($this->getClient() !== false) {
                $this->arResult['PROFILE'] = $this->arClient;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
