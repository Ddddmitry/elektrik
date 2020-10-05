<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Article;

class ApiArticlesUpdateViewed extends AjaxComponent
{

    private $articleID;

    protected function parseRequest() {
        $input = $this->request->getPostList()->toArray();

        $this->articleID = $input['articleID'];
    }

    /**
     * Установка свойства "Просмотрен автором" для всех новых комментариев к статье с полученным ID
     *
     * @return array Массив ID отмеченных комментариев
     * @throws \Exception
     */
    protected function updateReviews()
    {
        \CModule::IncludeModule('iblock');

        global $USER;
        $userID = $USER->GetID();

        if (!$userID) throw new \Exception("authorization_required");
        $obArticle = new Article();
        if (!$obArticle->isBelongToCurrentUser($this->articleID)) throw new \Exception("access_denied");

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "ACTIVE" => "Y",
            "PROPERTY_ARTICLE" => $this->articleID,
            "PROPERTY_VIEWED" => false,
        ];
        $arNewCommentsID = [];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        while ($arComment = $dbResult->Fetch()) {
            $arNewCommentsID[] = $arComment["ID"];
        }

        foreach ($arNewCommentsID as $commentID) {
            \CIBlockElement::SetPropertyValuesEx($commentID, false, ["VIEWED" => ENUM_VALUE_COMMENTS_VIEWED]);
        }

        return $arNewCommentsID;
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
