<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiReviewsEdit extends AjaxComponent
{
    protected $mark, $text, $reviewID;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->reviewID = $input['review-id'];
        $this->mark = $input['review-mark'];
        $this->text = $input['review-text'];
    }

    /**
     * Обновление отзыва
     */
    protected function editReview()
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

            $obElement = new \CIBlockElement;
            $arReviewFields = Array(
                "PREVIEW_TEXT" => $this->text,
                "ACTIVE" => "N",
            );
            if ($obElement->Update($this->reviewID, $arReviewFields)) {

                if ($this->mark) {
                    $arReviewProperties = array(
                        "MARK" => $this->mark,
                    );
                    \CIBlockElement::SetPropertyValuesEx($this->reviewID, false, $arReviewProperties);
                }

                $arMailData = [
                    "REVIEW_ID" => $this->reviewID,
                    "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_REVIEWS."&type=reviews&ID=".$this->reviewID,
                ];
                Notification::sendNotification("ELECTRIC_NEW_REVIEW_ADMIN", $arMailData);

                return true;
            } else {

                throw new \Exception($obElement->LAST_ERROR);
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
                "ID" => $this->editReview(),
                "MESSAGE" => "<div class=\"marketDetail-reviews__comment-lock\">Ваш отзыв будет обновлён после проверки модератором!</div>",
            ];
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
