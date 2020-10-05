<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Vendor;
use \Electric\Helpers\DataHelper;

class VendorsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arVendor;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных вендора
     *
     * @return array|bool
     */
    protected function getVendor()
    {
        global $USER;
        $this->curUserId = $USER->GetID();
        $obVendor = new Vendor();

        $arVendor = $obVendor->getVendorData();

        if ($arVendor) {

            if(!$arVendor["PREVIEW_PICTURE"])
                $arVendor["PREVIEW_PICTURE"]["SRC"] = "/local/templates/elektrik/images/contractor-empty.png";

            $arVendor["EVENTS"] = $obVendor->getEvents();
            $arVendor["ARTICLES"] = $obVendor->getArticles();
            $arVendor["EDUCATIONS"] = $obVendor->getEducations();
            $arVendor["SALES"] = $obVendor->getSales();

            $this->arVendor = $arVendor;

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
            if ($this->getVendor() !== false) {
                $this->arResult['PROFILE'] = $this->arVendor;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
