<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\User;
use \Electric\Article;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiCommentsEdit extends AjaxComponent
{
    protected $commentID, $text;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->commentID = $input['comment'];
        $this->text = $input['comment-text'];
    }


    /**
     * Изменение комментария
     *
     * @return mixed
     * @throws \Exception
     */
    protected function updateComment()
    {
        \CModule::IncludeModule('iblock');

        if (!$this->commentID) throw new \Exception("comment_undefined");

        global $USER;
        $userID = $USER->GetID();

        $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "=ID" => $this->commentID,
        ];
        $rsComments = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arComment  = $rsComments->Fetch()) {
            if ($arComment["PROPERTY_USER_VALUE"] != $userID) {
                throw new \Exception("access_denied");
            };
        } else {
            throw new \Exception("comment_not_found");
        };


        $obElement = new \CIBlockElement;
        $arCommentFields = Array(
            "PREVIEW_TEXT" => $this->text,
        );
        if ($obElement->Update($this->commentID, $arCommentFields)) {

            return true;
        } else {
            throw new \Exception($obElement->LAST_ERROR, "element_creation_error");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->updateComment();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
