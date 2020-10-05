<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\User;
use \Electric\Article;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiCommentsAdd extends AjaxComponent
{
    protected $articleID, $commentID, $text;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->articleID = $input['comment-article'];
        $this->commentID = $input['comment-comment'];
        $this->text = $input['comment-text'];
    }


    /**
     * Добавление комментария
     *
     * @return mixed
     * @throws \Exception
     */
    protected function addComment()
    {
        \CModule::IncludeModule('iblock');

        global $USER;
        $authorID = $USER->GetID();

        $obElement = new \CIBlockElement;
        $arCommentFields = Array(
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "NAME" => "Комментарий",
            "PREVIEW_TEXT" => $this->text,
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
        );
        if ($commentID = $obElement->Add($arCommentFields)) {

            $arCommentProperties = array(
                "USER" => $authorID,
                "ARTICLE"   => $this->articleID,
                "COMMENT" => $this->commentID,
            );

            if ($this->commentID) {
                $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_COMMENT", "PROPERTY_TOP_COMMENT"];
                $arFilter = [
                    "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
                    "ID"        => $this->commentID,
                ];
                $rsComments = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
                $arComment  = $rsComments->Fetch();

                if ($arComment["PROPERTY_TOP_COMMENT_VALUE"]) {
                    $arCommentProperties["TOP_COMMENT"] = $arComment["PROPERTY_TOP_COMMENT_VALUE"];
                } else {
                    $arCommentProperties["TOP_COMMENT"] = $this->commentID;
                }
            }

            \CIBlockElement::SetPropertyValuesEx($commentID, false, $arCommentProperties);

            // Добавление уведомления
            User::addNotification([
                "TYPE" => "NEW_COMMENT",
                "AUTHOR" => $authorID,
                "ARTICLE" => $this->articleID,
            ]);
        } else {
            throw new \Exception($obElement->LAST_ERROR);
        }

        return $commentID;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->addComment();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
