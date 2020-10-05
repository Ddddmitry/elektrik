<?php

namespace Electric;

use Electric\Component\PopularServices;
use Electric\Helpers\DataHelper;

/**
 * Class News
 * Класс, содержащий методы для работы с новостями
 *
 * @package Electric
 */
class News
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
            'IBLOCK_ID' => IBLOCK_BACKOFFICE_NEWS,
            'ACTIVE' => 'Y',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        return $dbResult->SelectedRowsCount();
    }


}
