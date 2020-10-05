<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Contractor;
use \Electric\Article;
use \Electric\User;

class ApiBackofficeUser extends AjaxComponent
{

    protected function parseRequest()
    {

    }

    protected function getUserData()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $rsUser = \CUser::GetByID($userID);
        $arUser = $rsUser->Fetch();

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_CONTRACTORS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $arUser["ID"],
        ];
        $arSelect = [
            'ID',
            'NAME',
            'IBLOCK_ID',
            'PROPERTY_PHONE',
            'PROPERTY_EMAIL',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if (!$arContractor = $dbResult->Fetch()) {
            throw new \Exception("У пользователя нет активных исполнителей.");
        }

        $obContractor = new Contractor();
        $obArticle = new Article();

        $arUserData = [
            "name" => $arUser["NAME"] . ($arUser["LAST_NAME"] ? " " . $arUser["LAST_NAME"] : ""),
            "phone" => $arContractor["PROPERTY_PHONE_VALUE"],
            "email" => $arContractor["PROPERTY_EMAIL_VALUE"],
            "notifications" => [
                "mail" => true,
                "sms" => false,
            ],
            "newReviews" => $obContractor->getNewReviewsCount(),
            "newArticlesComm" => $obArticle->getNewCommentsTotalCount(),
        ];

        // Проверка сертификаций и наличия заявок на сертификации
        $obUser = new User();
        $isCertified = $arUser["UF_EMASTER"] ? true : false;
        $hasCertificationRequest = $obUser->hasCertificationRequest();
        $isRaekMember = $arUser["UF_RAEK"] ? true : false;
        $hasRaekRequest = $obUser->hasRaekRequest() || $obUser->hasRaekVerification();

        // Получение уведомлений
        $obUser = new User();
        $arNotifications = $obUser->getNotifications();

        $arResult = [
            "user" => $arUserData,
            "certified" => $isCertified,
            "certificationRequest" => $hasCertificationRequest,
            "raek" => $isRaekMember,
            "raekRequest" => $hasRaekRequest,
            "notifications" => $arNotifications,
        ];

        return $arResult;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getUserData();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
