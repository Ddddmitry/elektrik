<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\DataHelper;

class ApiNotificationsUpdateViewed extends AjaxComponent
{

    protected function parseRequest() {
        $input = $this->request->getPostList()->toArray();

        $this->articleID = $input['articleID'];
    }


    /**
     * Установка свойства "Просмотрен" для всех новых комментариев Уведомлений текущего пользователя,
     * запись в свойсвто пользователя последнего просмотренного количества просмотров его анкеты.
     *
     * @return boolean
     * @throws \Exception
     */
    protected function updateNotifications()
    {
        global $USER;
        $userID = $USER->GetID();


        $arHLDataClasses = DataHelper::getHLDataClasses([HLBLOCK_NOTIFICATIONS, HLBLOCK_VIEWS_DETAIL]);

        $rsData = $arHLDataClasses["NOTIFICATIONS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_USER" => $userID, "UF_VIEWED" => false],
                "order" => ["ID" => "DESC"],
            ]
        );
        while ($arItem = $rsData->Fetch()) {
            $arHLDataClasses["NOTIFICATIONS"]::update($arItem["ID"], ["UF_VIEWED" => true]);
        }

        // Запись в свойсвто пользователя последнего просмотренного количества просмотров его анкеты
        $viewsDetail = $arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $userID]);
        $obUser = new \CUser();
        $obUser->Update($userID, ["UF_LAST_VIEWS" => $viewsDetail]);

        return true;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        try {
            $result = $this->updateNotifications();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
