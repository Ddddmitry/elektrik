<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiReviewsUpdateViewed extends AjaxComponent
{

    protected function parseRequest() { }

    /**
     * Установка свойства "Просмотрен исполнителем" для всех отзывов текущего пользователя
     *
     * @return array Массив ID отмеченных отзывов
     * @throws \Exception
     */
    protected function updateReviews()
    {
        \CModule::IncludeModule('iblock');

        global $USER;
        $userID = $USER->GetID();

        if (!$userID) {
            throw new \Exception("authorization_required");
        }

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_REVIEWS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
            "PROPERTY_VIEWED" => false,
        ];
        $arNewReviewsID = [];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        while ($arReview = $dbResult->Fetch()) {
            $arNewReviewsID[] = $arReview["ID"];
        }

        foreach ($arNewReviewsID as $reviewID) {
            \CIBlockElement::SetPropertyValuesEx($reviewID, false, ["VIEWED" => ENUM_VALUE_REVIEWS_VIEWED]);
        }

        return $arNewReviewsID;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        try {
            $result = $this->updateReviews();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
