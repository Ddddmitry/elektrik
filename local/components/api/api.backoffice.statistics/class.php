<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Contractor;

class ApiBackofficeStatistics extends AjaxComponent
{

    protected function parseRequest()
    {

    }

    protected function getStatistics()
    {
        global $USER;
        $userID = $USER->GetID();

        $obContractor = new Contractor();
        $reviewsCount = $obContractor->getReviewsCount();
        $reviewsCountByMonth = $obContractor->getReviewsCount(null, (new \DateTime())->modify('-1 month'));
        $rating = $obContractor->getRating();
        $ratingChanges = $obContractor->getRatingChanges();


        $watchWorksheet = $this->arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $userID]);
        $watchContacts = $this->arHLDataClasses["VIEWS_CONTACTS"]::getCount(["UF_USER" => $userID]);
        $date = (\Bitrix\Main\Type\DateTime::createFromPhp((new \DateTime())->modify('-1 month')))->toString();
        $watchWorksheetByMonth = $this->arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $userID, ">UF_DATE" => $date]);
        $watchContactsByMonth = $this->arHLDataClasses["VIEWS_CONTACTS"]::getCount(["UF_USER" => $userID, ">UF_DATE" => $date]);

        $arStatistics = [
            "all" => [
                "raitingNumber" => $rating,
                "reviewsNumber" => $reviewsCount,
                "watchWorksheet" => $watchWorksheet,
                "watchContacts" => $watchContacts,
            ],
            "month" => [
                "raitingNumber" => $ratingChanges["LAST_MONTH"],
                "reviewsNumber" => $reviewsCountByMonth,
                "watchWorksheet" => $watchWorksheetByMonth,
                "watchContacts" => $watchContactsByMonth,
            ],
        ];

        if ($ratingChanges["MONTH_CHANGE"] >= 0) {
            $arStatistics["month"]["raitingProgress"] = "+ " . number_format($ratingChanges["MONTH_CHANGE"], 1, ".", "");
        } else {
            $arStatistics["month"]["raitingProgress"] = "- " . number_format(abs($ratingChanges["MONTH_CHANGE"]), 1, ".", "");
        }

        return $arStatistics;
    }

    private function getHLDataClasses() {
        \CModule::IncludeModule('highloadblock');
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_DETAIL)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_DETAIL"] = $obEntity->getDataClass();
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_CONTACTS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_CONTACTS"] = $obEntity->getDataClass();
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        $this->getHLDataClasses();

        try {
            $result = $this->getStatistics();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
