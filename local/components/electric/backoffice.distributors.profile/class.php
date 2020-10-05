<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use Electric\Distributor;
use \Electric\Helpers\DataHelper;

class DistributorsProfile extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arDistributor;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных исполнителя
     *
     * @return array|bool
     */
    protected function getDistributor()
    {
        global $USER;
        $this->curUserId = $USER->GetID();
        $obDistributor = new Distributor();

        $arDistributor = $obDistributor->getDistributorData();

        if ($arDistributor) {

            if(!$arDistributor["PREVIEW_PICTURE"])
                $arDistributor["PREVIEW_PICTURE"]["SRC"] = "/local/templates/elektrik/images/contractor-empty.png";

            $arDistributor["EVENTS"] = $obDistributor->getEvents();
            $arDistributor["EDUCATIONS"] = $obDistributor->getEducations();
            $arDistributor["SALES"] = $obDistributor->getSales();
            $arDistributor["ARTICLES"] = $obDistributor->getArticles();

            // todo: собрать статистику для дистрибьютора
            $arDistributor["STATISTICS"] = $obDistributor->getStatistics();

            $this->arDistributor = $arDistributor;

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
            if ($this->getDistributor() !== false) {
                $this->arResult['PROFILE'] = $this->arDistributor;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }
    }
}
