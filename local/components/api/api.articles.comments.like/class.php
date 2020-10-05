<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiArticlesCommentsLike extends AjaxComponent
{
    private $type, $commentID;

    private $obHLDataClassLikes;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->type = strtoupper($input['type']);
        $this->commentID = $input['comment'];
    }


    /**
     * Добавление, изменение или удаление оценки комментария
     *
     * @return array
     * @throws \Exception
     */
    protected function updateLike()
    {
        global $USER;
        $userID = $USER->GetID();

        if (!$userID) {
            throw new \Exception("authorization_required");
        }

        // Удаление существующей оценки
        $arFilter = [
            "UF_USER" => $userID,
            "UF_COMMENT" => $this->commentID,
        ];
        $rsData = $this->obHLDataClassLikes::getList(
            array(
                "select" => ['*'],
                "filter" => $arFilter,
                "order" => [],
            )
        );
        if ($arPrevLike = $rsData->fetch()) {
            $this->obHLDataClassLikes::delete($arPrevLike["ID"]);
            if ($arPrevLike["UF_TYPE"] == $this->type) {
                $arResult = $this->countLikes();
                $arResult["IS_REMOVED"] = true;

                return $arResult;
            }
        };

        // Добавление оценки
        $arFields = [
            "UF_USER" => $userID,
            "UF_COMMENT" => $this->commentID,
            "UF_TYPE" => strtoupper($this->type),
        ];
        $obResult = $this->obHLDataClassLikes::add($arFields);

        // Отправка уведомления о том, что комментарий набрал определённое
        // количество отрицательных оценок и нуждается в модерации
        $arResult = $this->countLikes();
        if ($obResult->isSuccess() && $arResult["DISLIKE"] == COMMENTS_DISLIKES_THRESHOLD) {
            $arMailData = [
                "COMMENT_ID" => $userID,
                "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_ARTICLES_COMMENTS."&type=articles&ID=".$this->commentID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_COMMENT", $arMailData);
        }

        return $arResult;
    }


    /**
     * Общий подсчёт оценок
     *
     * @return array
     */
    private function countLikes() {
        $arLikesCount = [
            "LIKE" => 0,
            "DISLIKE" => 0,
        ];
        $arFilter = [
            "UF_COMMENT" => $this->commentID,
        ];
        $rsData = $this->obHLDataClassLikes::getList(
            array(
                "select" => ['*'],
                "filter" => $arFilter,
                "order" => [],
            )
        );
        while ($arLike = $rsData->Fetch()) {
            $arLikesCount[$arLike["UF_TYPE"]] += 1;
        }

        return $arLikesCount;
    }


    private function getHLDataClasses() {
        \CModule::IncludeModule('highloadblock');
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_LIKES)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->obHLDataClassLikes = $obEntity->getDataClass();
    }


    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        $this->getHLDataClasses();

        try {
            $result = $this->updateLike();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
