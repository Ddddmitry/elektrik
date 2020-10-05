<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Notification;

class ApiReviewsAdd extends AjaxComponent
{
    protected $mark, $text, $userID;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->mark = $input['review-mark'];
        $this->text = $input['review-text'];
        $this->userID = $input['review-user'];
    }

    /**
     * Добавление отзыва
     */
    protected function addReview()
    {
        \CModule::IncludeModule('iblock');

        global $USER;
        $authorID = $USER->GetID();

        $obElement = new \CIBlockElement;
        $arReviewFields = Array(
            "IBLOCK_ID" => IBLOCK_REVIEWS,
            "NAME" => "Отзыв",
            "PREVIEW_TEXT" => $this->text,
            "ACTIVE" => "N",
            "ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
        );
        if ($reviewID = $obElement->Add($arReviewFields)) {
            $arReviewProperties = array(
                "USER"   => $this->userID,
                "AUTHOR" => $authorID,
                "MARK" => $this->mark,
            );
            \CIBlockElement::SetPropertyValuesEx($reviewID, false, $arReviewProperties);

            // Отправка почтового уведомления
            $arMailData = [
                "REVIEW_ID" => $reviewID,
                "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_REVIEWS."&type=reviews&ID=".$reviewID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_REVIEW_ADMIN", $arMailData);
        } else {

            throw new \Exception($obElement->LAST_ERROR);
        }

        return $reviewID;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = [
                "ID" => $this->addReview(),
                "MESSAGE" => "<div class=\"marketDetail-reviews__comment-lock\">Ваш отзыв будет опубликован после проверки модератором!</div>",
            ];
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
