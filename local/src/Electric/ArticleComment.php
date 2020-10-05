<?php
namespace Electric;

/**
 * Class ArticleComment
 *
 * Класс, содержащий методы для работы с комментариями к статьям
 *
 * @package Electric
 */
class ArticleComment
{
    private $currentUserID;

    private $obHLDataClassLikes;

    public function __construct()
    {
        \CModule::IncludeModule('iblock');
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->currentUserID = $USER->GetID();
        }
    }

    /**
     * Получение количества лайков и дизлайков комментария
     *
     * @param $commentsID
     *
     * @return array
     */
    public function getLikesCount($commentsID)
    {
        $this->getHLDataClasses();

        $arLikes = [];
        $arSelect = ["UF_COMMENT", "UF_TYPE"];
        $arFilter = [
            "UF_COMMENT" => $commentsID,
        ];
        $rsData = $this->obHLDataClassLikes::getList(
            array(
                "select" => $arSelect,
                "filter" => $arFilter,
                "order" => [],
            )
        );
        while ($arLike = $rsData->fetch()) {
            $arLikes[$arLike["UF_COMMENT"]][$arLike["UF_TYPE"]] += 1;
        }

        return $arLikes;
    }

    /**
     * Получение лайков и дизайлаков пользователя для массива комментариев
     *
     * @param $commentsID
     * @param $userID
     *
     * @return array
     */
    public function getUserLikes($commentsID, $userID)
    {
        $this->getHLDataClasses();

        $arUserLikes = [];
        $arSelect = ["UF_COMMENT", "UF_TYPE"];
        $arFilter = [
            "UF_COMMENT" => $commentsID,
            "UF_USER" => $userID,
        ];
        $rsData = $this->obHLDataClassLikes::getList(
            array(
                "select" => $arSelect,
                "filter" => $arFilter,
                "order" => [],
            )
        );
        while ($arLike = $rsData->fetch()) {
            $arUserLikes[$arLike["UF_COMMENT"]] = $arLike["UF_TYPE"];
        }

        return $arUserLikes;
    }

    /**
     * Подключение классов для работы с сущностями D7
     *
     */
    private function getHLDataClasses() {
        \CModule::IncludeModule('highloadblock');
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_LIKES)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->obHLDataClassLikes = $obEntity->getDataClass();
    }

}
