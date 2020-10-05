<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use \Electric\Contractor;
use \Electric\Helpers\DataHelper;

class ContractorReviews extends BaseComponent
{
    public $requiredModuleList = ['iblock'];

    private $userID;

    private $filteredByMark = null;

    private $page = 1;
    private $rowsPerPage = 5;

    public function onPrepareComponentParams($arParams)
    {
        $this->userID = $arParams['USER'];
        if(!$this->userID){
            global $USER;
            $this->userID = $USER->GetId();
        }

        $this->rowsPerPage = $arParams['PAGE_SIZE'];

        return $arParams;
    }

    /**
     * Получение данных отзывов
     *
     * @return mixed
     */
    protected function getReviewsData()
    {

        $obContractor = new Contractor();

        $arNavData = [
            "nPageSize" => $this->rowsPerPage,
            "iNumPage" => $this->page,
        ];

        $arReviewsData = $obContractor->getReviews($this->userID, $arNavData, $this->filteredByMark);
        foreach ($arReviewsData["ITEMS"] as &$arReview) {
            $arReview["ACTIVE_FROM"] = DataHelper::getFormattedDate($arReview["ACTIVE_FROM"]);
            $arReview["NEWANSWER"] = $arReview["PROPERTY_ANSWER_VALUE"];
            $arReview["NEW"] = $arReview["PROPERTY_VIEWED_ENUM_ID"] ? false : true;
        }

        $arReviewsData["MARKS_SPREAD"] = $obContractor->getReviewsSpread($this->userID);

        $arFilter = Array(
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $this->userID,
        );
        $rsContractors = \CIBlockElement::GetList([], $arFilter, false, false, array("ID", "NAME", "PROPERTY_RATING"));
        if ($arContractor = $rsContractors->Fetch()) {
            $arContractor["USER"] = $this->userID;
            $arContractor["RATING"] = $obContractor->getRating($this->userID);
            $arReviewsData["CONTRACTOR"] = $arContractor;
        }

        return $arReviewsData;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();
        $isAjax = $this->isAjaxRequest() ? true : false;

        if ($this->input["mark"]) {
            $this->filteredByMark = $this->input["mark"];
        }

        if ($this->input["page"]) {
            $this->page = $this->input["page"];
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["REVIEWS_DATA"] = $this->getReviewsData();
            if ($isAjax) $this->restartBuffer();
            $this->includeComponentTemplate();
            if ($isAjax) die();
        }
    }
}

