<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiReviewsDelete extends AjaxComponent
{
    protected $reviewID;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->reviewID = $input['id'];
    }

    /**
     * Удаление отзыва
     */
    protected function deleteReview()
    {
        \CModule::IncludeModule('iblock');

        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'ID' => $this->reviewID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "PROPERTY_AUTHOR"]);
        if ($arReview = $dbResult->Fetch()) {
            if ($arReview["PROPERTY_AUTHOR_VALUE"] != $userID) {
                throw new \Exception("access_denied");
            }

            if (\CIBlockElement::Delete($this->reviewID)) {

                return $this->reviewID;
            } else {

                throw new \Exception("element_delete_error");
            }

        } else {
            throw new \Exception('review_not_found');
        }

    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = [
                "ID" => $this->deleteReview(),
                "MESSAGE" => "<div class=\"marketDetail-reviews__comment-lock\">Ваш отзыв удалён!</div>",
            ];
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
