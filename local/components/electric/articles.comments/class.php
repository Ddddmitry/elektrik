<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\ArticleComment;
use \Electric\Component\BaseComponent;
use \Electric\Article;
use Electric\Helpers\DataHelper;

class ArticleComments extends BaseComponent
{
    public $requiredModuleList = ['iblock'];

    private $currentUserID;

    private $articleID;

    private $page = 1;
    private $rowsPerPage = 5;

    public function onPrepareComponentParams($arParams)
    {
        $this->articleID = $arParams['ARTICLE'];
        $this->rowsPerPage = $arParams['PAGE_SIZE'];

        return $arParams;
    }


    /**
     * Получение данных комментариев к статье
     *
     * @return array
     * @throws \Exception
     */
    protected function getCommentsData()
    {
        $arComments = [];
        $arNavData = [
            "nPageSize" => $this->rowsPerPage,
            "iNumPage" => $this->page,
        ];

        $arOrder = ["ACTIVE_FROM" => "ASC"];
        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PROPERTY_USER", "PROPERTY_ARTICLE", "PROPERTY_COMMENT", "ACTIVE_FROM"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "PROPERTY_ARTICLE" => $this->articleID,
            "PROPERTY_COMMENT" => false,
        ];
        $rsComments = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavData, $arSelect);
        $pagesCount = $rsComments->NavPageCount;
        if (!$pagesCount || $pagesCount == 1 || $arNavData["iNumPage"] == $pagesCount) {
            $arCommentsNav["IS_LAST_PAGE"] = true;
        };
        while ($arComment = $rsComments->Fetch()) {
            // Получение даты
            $arComment["DATE"] = DataHelper::getFormattedDate($arComment["ACTIVE_FROM"]);

            $arComments[$arComment["ID"]] = $arComment;
        }

        // Получение комментариев-ответов
        $arAnswers = [];
        $arOrder = ["ACTIVE_FROM" => "DESC"];
        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PROPERTY_USER", "PROPERTY_ARTICLE", "PROPERTY_COMMENT", "PROPERTY_TOP_COMMENT", "ACTIVE_FROM"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "PROPERTY_ARTICLE" => $this->articleID,
            "PROPERTY_TOP_COMMENT" => array_column($arComments, "ID"),
        ];
        $rsAnswers = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arAnswer = $rsAnswers->Fetch()) {
            // Получение даты
            $arAnswer["DATE"] = DataHelper::getFormattedDate($arAnswer["ACTIVE_FROM"]);

            $arAnswers[$arAnswer["ID"]] = $arAnswer;
        }

        $arAllCommentsID = array_merge(array_column($arComments, "ID"), array_column($arAnswers, "ID"));

        // Получение авторов выбранных комментариев
        $arAuthors = [];
        $arFilter = [
            "ID" => array_unique(array_merge(array_column($arComments, "PROPERTY_USER_VALUE"), array_column($arAnswers, "PROPERTY_USER_VALUE"))),
        ];
        $dbResult = \Bitrix\Main\UserTable::getList([
            "select" => Array("ID", "NAME", "LAST_NAME"),
            "filter" => $arFilter,
        ]);
        while ($arAuthor = $dbResult->fetch()) {
            $arAuthors[$arAuthor["ID"]] = $arAuthor;
        }

        $obArticleComment = new ArticleComment();

        // Получение количества лайков и дизлайков
        $arLikesCount = $obArticleComment->getLikesCount($arAllCommentsID);

        // Получение лайков и дизлайков, поставленных пользователем
        if ($this->currentUserID) {
            $arUserLikes = $obArticleComment->getUserLikes($arAllCommentsID, $this->currentUserID);
        }

        // Добавление данных в массив комментариев верхнего уровня
        foreach ($arComments as &$arComment) {
            $arComment["AUTHOR_NAME"] = $arAuthors[$arComment["PROPERTY_USER_VALUE"]]["NAME"] . " " . $arAuthors[$arComment["PROPERTY_USER_VALUE"]]["LAST_NAME"];
            $arComment["LIKES"] = $arLikesCount[$arComment["ID"]];
            if ($arUserLikes[$arComment["ID"]]) {
                $arComment["USER_LIKE"] = $arUserLikes[$arComment["ID"]];
            }
            if ($arComment["PROPERTY_USER_VALUE"] == $this->currentUserID) {
                $arComment["CAN_EDIT"] = true;
            }
        }

        // Добавление данных в массив комментариев-ответов
        foreach ($arAnswers as &$arAnswer) {
            $arAnswer["AUTHOR_NAME"] = $arAuthors[$arAnswer["PROPERTY_USER_VALUE"]]["NAME"] . " " . $arAuthors[$arAnswer["PROPERTY_USER_VALUE"]]["LAST_NAME"];
            $arAnswer["LIKES"] = $arLikesCount[$arAnswer["ID"]];
            if ($arUserLikes[$arAnswer["ID"]]) {
                $arAnswer["USER_LIKE"] = $arUserLikes[$arAnswer["ID"]];
            }
            if ($arAnswer["PROPERTY_USER_VALUE"] == $this->currentUserID) {
                $arAnswer["CAN_EDIT"] = true;
            }
        }

        // Составление иерархического массива комментариев
        foreach ($arAnswers as $key => &$arAnswer) {
            if ($arAnswer["PROPERTY_COMMENT_VALUE"] != $arAnswer["PROPERTY_TOP_COMMENT_VALUE"]) {
                if (in_array($arAnswer["PROPERTY_COMMENT_VALUE"], array_column($arAnswers, "ID"))) {
                    $arAnswers[$arAnswer["PROPERTY_COMMENT_VALUE"]]["ANSWERS"][] = $arAnswer;
                }
                unset($arAnswers[$key]);
            }
        }

        foreach ($arAnswers as $key => $arAnswerItem) {
            $arComments[$arAnswer["PROPERTY_COMMENT_VALUE"]]["ANSWERS"][] = $arAnswers[$key];
        }

        $arCommentsData = [
            "ITEMS" => $arComments,
            "NAV" => $arCommentsNav,
        ];

        return $arCommentsData;
    }


    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();
        $isAjax = $this->isAjaxRequest() ? true : false;

        global $USER;
        $this->currentUserID = $USER->GetID();

        if ($this->input["page"]) {
            $this->page = $this->input["page"];
        }

        if ($this->StartResultCache(false)) {
            $this->arResult["IS_AJAX"] = $isAjax;
            $this->arResult["COMMENTS_DATA"] = $this->getCommentsData();
            $this->arResult["COMMENTS_DATA"]["ARTICLE"] = $this->articleID;
            $obArticle = new Article();
            $this->arResult["MARK_AS_VIEWED"] = $obArticle->isBelongToCurrentUser($this->articleID);

            if ($isAjax) $this->restartBuffer();

            $this->includeComponentTemplate();
            if ($isAjax) die();
        }
    }
}

