<?php

namespace Electric;

use Electric\Component\PopularServices;
use Electric\Helpers\DataHelper;

/**
 * Class Article
 * Класс, содержащий методы для работы со статьями в компонентах
 *
 * @package Electric
 */
class Article
{

    private $currentUserID;

    public function __construct()
    {
        \CModule::IncludeModule('iblock');
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->currentUserID = $USER->GetID();
        }
    }

    /**
     * Получение количества комментариев к статье
     *
     * @param $articleID
     * @param bool $showNew - отдельно подсчитывать комментарии, не отмеченные как просмотренные
     *
     * @return mixed
     */
    public function getCommentsCount($articleID, $showNew = false)
    {
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES_COMMENTS,
            'ACTIVE' => 'Y',
            'PROPERTY_ARTICLE' => $articleID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID", "PROPERTY_VIEWED"]);
        $arCommentsCount["TOTAL"] = 0;
        if ($showNew) $arCommentsCount["NEW"] = 0;
        while ($arComment = $dbResult->Fetch()) {
            $arCommentsCount["TOTAL"] += 1;
            if ($showNew && !$arComment["PROPERTY_VIEWED_VALUE"]) {
                $arCommentsCount["NEW"] += 1;
            }
        }
        return $arCommentsCount;
    }

    public function getNewCommentsTotalCount() {

        if (!$this->currentUserID) throw new \Exception("authorization_required");

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $this->currentUserID,
        ];
        $arArticlesID = [];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        while ($arArticle = $dbResult->Fetch()) {
            $arArticlesID[] = $arArticle["ID"];
        }
        if (!$arArticlesID) return 0;

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES_COMMENTS,
            "ACTIVE" => "Y",
            "=PROPERTY_ARTICLE" => $arArticlesID,
            "PROPERTY_VIEWED" => false,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID", "PROPERTY_VIEWED"]);
        $unreadCommentsCount = $dbResult->SelectedRowsCount();

        return $unreadCommentsCount ? $unreadCommentsCount : 0;
    }

    /**
     * Получение списка ID связанных статей
     *
     * @param $arRelatedArticlesID
     * @param $urlTemplate
     *
     * @return array
     * @throws \Exception
     */
    public function getRelatedArticles($arRelatedArticlesID, $urlTemplate)
    {
        $arRelatedArticles = [];
        $arSelect = ["ID", "IBLOCK_ID", "NAME", "CODE", "ACTIVE_FROM", "PREVIEW_TEXT", "PROPERTY_TYPE", "PROPERTY_USER"];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES,
            'ACTIVE' => 'Y',
            'ID' => $arRelatedArticlesID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arArticle = $dbResult->Fetch()) {
            // Получение URL детальной страницы
            $arArticle["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($urlTemplate, $arArticle);
            // Получение даты
            $arArticle["DATE"] = DataHelper::getFormattedDate($arArticle["ACTIVE_FROM"]);

            $arRelatedArticles[] = $arArticle;
        }

        // Получение категорий статей
        $arSelect = ['ID', 'NAME'];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES_TYPES,
            'ID' => array_column($arRelatedArticles, "PROPERTY_TYPE_VALUE"),
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTypes = [];
        while ($arType = $dbResult->GetNext()) {
            $arTypes[$arType["ID"]] = $arType["NAME"];
        }

        // Получение авторов статей
        $arAuthors = [];
        $arFilter = [
            "ID" => array_column($arRelatedArticles, "PROPERTY_USER_VALUE"),
        ];
        $dbResult = \Bitrix\Main\UserTable::getList([
            "select" => Array("ID", "NAME", "LAST_NAME"),
            "filter" => $arFilter,
        ]);
        while ($arAuthor = $dbResult->fetch()) {
            $arAuthors[$arAuthor["ID"]] = $arAuthor;
        }

        // Добавление дополнительных данных в массив статей
        foreach ($arRelatedArticles as &$arArticle) {
            $arArticle["PROPERTY_TYPE_VALUE_NAME"] = $arTypes[$arArticle["PROPERTY_TYPE_VALUE"]];
            $arArticle["PROPERTY_USER_VALUE_NAME"] = $arAuthors[$arArticle["PROPERTY_USER_VALUE"]]["NAME"] . " " . $arAuthors[$arArticle["PROPERTY_USER_VALUE"]]["LAST_NAME"];
        }

        return $arRelatedArticles;
    }


    /**
     * Получение типов статей
     *
     * @param array $arTypesID - массив ID для получение только определённого набора типов статей
     * @param bool $withoutProposed - исключать из результата тип "Предложенные статьи"
     *
     * @return array
     */
    public function getArticlesTypes($arTypesID = [], $withoutProposed = true) {
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES_TYPES,
        ];
        if ($arTypesID) {
            $arFilter["ID"] = array_unique($arTypesID);
        }
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTypes = [];
        while ($arType = $dbResult->GetNext()) {
            if ($withoutProposed && $arType["CODE"] == ARTICLE_TYPE_PROPOSED_CODE) continue;
            $arTypes[$arType["ID"]] = $arType["NAME"];
        }

        return $arTypes;
    }

    /**
     * Получение списка тегов статей
     *
     * @return array
     */
    public function getArticlesTags() {
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_TAGS',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_ARTICLES,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTags = [];
        while ($arArticle = $dbResult->GetNext()) {
            if (!in_array($arArticle["PROPERTY_TAGS_VALUE"], $arTags)) {
                $arTags[] = $arArticle["PROPERTY_TAGS_VALUE"];
            }
        }

        return $arTags;
    }


    /**
     * Принадлежит ли статья текущему пользователю?
     *
     * @param $articleID
     *
     * @return bool
     */
    public function isBelongToCurrentUser($articleID) {
        if (!$articleID || !$this->currentUserID) return false;
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES,
            "=ID" => $articleID,
        ];
        $arArticle = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID", "PROPERTY_USER"])->Fetch();
        if ((int)$arArticle["PROPERTY_USER_VALUE"] ===  (int)$this->currentUserID) {

            return true;
        } else {

            return false;
        }
    }

    public static function getArticleAuthorID($articleID) {
        $dbResult = \CIBlockElement::GetList([], ['IBLOCK_ID' => IBLOCK_ARTICLES], false, false, ['ID', 'PROPERTY_USER']);
        if ($arArticle = $dbResult->GetNext()) {
            if ($arArticle["PROPERTY_USER_VALUE"]) {

                return $arArticle["PROPERTY_USER_VALUE"];
            }
        }

        return false;

    }

    public function getTotalCount(){
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ARTICLES,
            'ACTIVE' => 'Y',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        return $dbResult->SelectedRowsCount();
    }


}
