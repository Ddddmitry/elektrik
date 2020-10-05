<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Helpers\DataHelper;

class FormBanner extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $this->bannerCode = isset($arParams["CODE"]) ? $arParams["CODE"] : "";

        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->isAjaxRequest()) {
            return false;
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["BANNERS"] = DataHelper::getFormBannerData($this->bannerCode);

            $this->includeComponentTemplate();
        }
    }
}

