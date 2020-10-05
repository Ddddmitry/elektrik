<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Article;

class ArticlesFilter extends BaseComponent
{
    private $input;

    private $obHLDataClassSkills;

    public $requiredModuleList = ['iblock', 'highloadblock'];

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных фильтра
     *
     * @return mixed
     */
    protected function getFilterData()
    {
        $arFilterData = [];
        $obArticle = new Article();
        $arFilterData["TYPES"] = $obArticle->getArticlesTypes();
        $arFilterData["TAGS"] = $obArticle->getArticlesTags();

        return $arFilterData;
    }

    private function getHLDataClasses() {
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_SKILLS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->obHLDataClassSkills = $obEntity->getDataClass();
    }

    public function executeComponent()
    {
        if ($this->isAjaxRequest()) {
            return false;
        }

        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        $this->getHLDataClasses();

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["FILTER_DATA"] = $this->getFilterData();
            $this->arResult["SEF_FOLDER"] = $this->folder;
            $this->includeComponentTemplate();
        }
    }
}

