<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Contractor;
use Electric\Helpers\DataHelper;

class ApiBackofficeReviews extends AjaxComponent
{

    protected function parseRequest()
    {

    }

    protected function getReviews()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            throw new \Exception('Пользователь не авторизован.');
        }

        $obContractor = new Contractor();
        $arReviews = $obContractor->getReviews();

        $arReviewsData = [];
        foreach ($arReviews["ITEMS"] as $arReview) {
            $obDateTime = new \DateTime($arReview["ACTIVE_FROM"]);
            $arReviewsData[] = [
                "id" => $arReview["ID"],
                "author" => $arReview["AUTHOR_NAME"],
                "date" => DataHelper::getFormattedDate($arReview["ACTIVE_FROM"]),
                "dateNumber" => $obDateTime->getTimestamp(),
                "raiting" => $arReview["PROPERTY_MARK_VALUE"],
                "text" => $arReview["PREVIEW_TEXT"],
                "answer" => $arReview["PROPERTY_ANSWER_VALUE"],
                "newanswer" => $arReview["PROPERTY_ANSWER_VALUE"],
                "new" => $arReview["PROPERTY_VIEWED_ENUM_ID"] ? false : true,
            ];
        }

        return $arReviewsData;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getReviews();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
