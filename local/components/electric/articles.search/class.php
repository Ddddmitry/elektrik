<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Helpers\DataHelper;

class ArticlesSearch extends BaseComponent
{
    private $input;

    private $folder;

    private $searchPhrase;

    public $requiredModuleList = [];

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];
        $this->bannerCode = isset($arParams["CODE"]) ? $arParams["CODE"] : "";

        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        if ($this->isAjaxRequest()) {
            return false;
        }

        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        if ($this->input["search"]) {
            $this->searchPhrase = $this->input["search"];
        }

        if ($this->StartResultCache(false)) {
            $this->arResult["SEARCH_DATA"] = [
                "SEARCH_PHRASE" => $this->searchPhrase,
            ];
            $this->arResult["SEF_FOLDER"] = $this->folder;
            $this->arResult["BANNER"] = DataHelper::getBannerData($this->bannerCode);

            // SEO
            $seoTitle = $APPLICATION->GetTitle();
            if ($seoTitle) $this->arResult["BANNER"]["TITLE"] = $seoTitle;

            $this->includeComponentTemplate();
        }
    }
}
