<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use \Electric\Contractor;
use \Electric\Helpers\DataHelper;

class ContractorsDetail extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $contractorCode;
    protected $contractorID;
    protected $arContractor;

    private $folder;

    private $obHLDataClassServices;

    public function onPrepareComponentParams($arParams)
    {
        $this->contractorID = $arParams['ID'];
        $this->contractorCode = $arParams['CODE'];
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных исполнителя
     *
     * @return array|bool
     */
    protected function getContractor()
    {
        if (empty($this->contractorCode) && empty($this->contractorID)) {
            return false;
        }

        $obContractor = new Contractor();

        $arContractor = $obContractor->getContractorData(["ID" => $this->contractorID,"CODE" => $this->contractorCode]);

        if ($arContractor) {
            $contractorUserID = $arContractor["PROPERTIES"]["USER"]["VALUE"];

            $arContractor["LANGUAGES"] = $obContractor->getLanguages($arContractor["ID"]);
            $arContractor["EDUCATIONS"] = $obContractor->getEductaions($arContractor["ID"]);
            $arContractor["COURSES"] = $obContractor->getCourses($arContractor["ID"]);
            $arContractor["JOBS"] = $obContractor->getJobs($arContractor["ID"]);
            $arContractor["WORKS"] = $obContractor->getWorks($contractorUserID);


            if($arEventsIds = array_column($arContractor["PROPERTIES"]["EVENTS"],"VALUE"))
                $arContractor["EVENTS"] = $obContractor->getEvents($arEventsIds);
            if($arStudiesIds = array_column($arContractor["PROPERTIES"]["EDUCATIONS"],"VALUE"))
                $arContractor["STUDIES"] = $obContractor->getStudies($arStudiesIds);



            foreach ($arContractor["JOBS"] as &$arJob) {
                if ($arJob["START_DATE"]) $arJob["START_DATE"] = strtolower(DataHelper::getMonth((int)$arJob["START_DATE"]->format("m") - 1)) . " " . $arJob["START_DATE"]->format("Y");
                if ($arJob["END_DATE"]) $arJob["END_DATE"] = strtolower(DataHelper::getMonth((int)$arJob["END_DATE"]->format("m") - 1)) . " " . $arJob["END_DATE"]->format("Y");
            }

            // Получение количества отзывов
            $arContractor["REVIEWS_COUNT"] = $obContractor->getReviewsCount($contractorUserID);
            $arContractor["ORDERS_COUNT"] = $obContractor->getOrdersCount($contractorUserID);
            $arContractor["ORDERS_COUNT"] = $obContractor->getOrdersCount($contractorUserID);

            $this->arContractor = $arContractor;

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

        $this->arResult['SESSION_ID'] = bitrix_sessid();
        global $USER;
        $this->arResult['IS_AUTH'] = $USER->IsAuthorized();

        if ($this->StartResultCache()) {
            if ($this->getContractor() !== false) {
                $this->arResult['PROFILE'] = $this->arContractor;
                $this->arResult['SEF_FOLDER'] = $this->folder;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }

        // Получение значений SEO для элемента
        global $APPLICATION;
        $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(IBLOCK_CONTRACTORS, $this->arResult['PROFILE']["ID"]);
        $arElementSEO = $ipropElementValues->getValues();

        if ($arElementSEO["ELEMENT_META_TITLE"]) {
            $APPLICATION->SetPageProperty('title', $arElementSEO["ELEMENT_META_TITLE"]);
        } else {
            $APPLICATION->SetPageProperty('title', $this->arResult['PROFILE']['NAME']);
        }

        if ($arElementSEO["ELEMENT_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["ELEMENT_META_KEYWORDS"];
        } elseif ($arElementSEO["SECTION_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["SECTION_META_KEYWORDS"];
        }
        if ($arElementSEO["ELEMENT_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["ELEMENT_META_DESCRIPTION"];
        } elseif ($arElementSEO["SECTION_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["SECTION_META_DESCRIPTION"];
        }
        if ($sSEOKeywords) {
            $APPLICATION->SetPageProperty('keywords', $sSEOKeywords);
        }
        if ($sSEODescription) {
            $APPLICATION->SetPageProperty('description', $sSEODescription);
        }
    }
}
