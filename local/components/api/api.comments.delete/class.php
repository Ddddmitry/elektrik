<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\User;
use \Electric\Article;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiCommentsDelete extends AjaxComponent
{
    protected $commentID, $text;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->commentID = $input['id'];
    }


    /**
     * Удаление комментария, удаление всех вложенных комментариев
     *
     * @return mixed
     * @throws \Exception
     */
    protected function deleteComments()
    {
        \CModule::IncludeModule('iblock');

        if (!$this->commentID) throw new \Exception("comment_undefined");

        global $USER;
        $userID = $USER->GetID();

        $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_USER", "PROPERTY_ARTICLE"];
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

        $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_COMMENT", "PROPERTY_TOP_COMMENT", "PREVIEW_TEXT"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "PROPERTY_ARTICLE" => $arComment["PROPERTY_ARTICLE_VALUE"],
        ];
        $rsAllComments = \CIBlockElement::GetList($arOrder = ["ACTIVE_FROM" => "ASC"], $arFilter, false, false, $arSelect);
        while ($arComment = $rsAllComments->Fetch()) {
            if ($arComment["PROPERTY_COMMENT_VALUE"]) {
                $arAllComments[] = $arComment;
            }
        }

        $arDeleteComments = [$this->commentID];
        foreach ($arAllComments as $arComment) {
            if (in_array($arComment["PROPERTY_COMMENT_VALUE"], $arDeleteComments)) {
                $arDeleteComments[] = $arComment["ID"];
            }
        }

        $obElement = new \CIBlockElement;
        foreach ($arDeleteComments as $commentID) {
            $obElement->Delete($commentID);
        }

        return $arDeleteComments;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {

            return false;
        }

        try {
            $result = $this->deleteComments();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
