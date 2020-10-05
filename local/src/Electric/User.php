<?php
namespace Electric;

use Bitrix\Highloadblock\HighloadBlockTable;
use \Electric\Contractor;
use \Electric\Helpers\DataHelper;

/**
 * Class User
 *
 * Класс, содержащий методы для работы с пользователями
 *
 * @package Electric
 */
class User
{
    private $currentUserID;

    public function __construct($userID = null)
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->currentUserID = $USER->GetID();
        }
    }


    /**
     * Принадлежит ли пользователь к группе "Исполнители"?
     *
     * @param int $userID
     *
     * @return bool
     */
    public function isContractor($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if ($arUserGroups = \CUser::GetUserGroup($userID)) {
            if (in_array(USER_GROUP_CONTRACTORS, $arUserGroups)) {
                return true;
            }
        };

        return false;
    }

    /**
     * Принадлежит ли пользователь к группе "Клиенты"?
     *
     * @param int $userID
     *
     * @return bool
     */
    public function isClient($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if ($arUserGroups = \CUser::GetUserGroup($userID)) {
            if (in_array(USER_GROUP_CLIENTS, $arUserGroups)) {
                return true;
            }
        };

        return false;
    }

    /**
     * Принадлежит ли пользователь к группе "Дистрибьютор"?
     *
     * @param int $userID
     *
     * @return bool
     */
    public function isDistributor($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if ($arUserGroups = \CUser::GetUserGroup($userID)) {
            if (in_array(USER_GROUP_DISTRIBUTORS, $arUserGroups)) {
                return true;
            }
        };

        return false;
    }

    /**
     * Принадлежит ли пользователь к группе "Вендоры"?
     *
     * @param int $userID
     *
     * @return bool
     */
    public function isVendor($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if ($arUserGroups = \CUser::GetUserGroup($userID)) {
            if (in_array(USER_GROUP_VENDORS, $arUserGroups)) {
                return true;
            }
        };

        return false;
    }

    /**
     * Выясняем кто пользователь
     *
     * @param int $userID
     *
     * @return bool
     */
    public function getType($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if ($arUserGroups = \CUser::GetUserGroup($userID)) {
            if (in_array(USER_GROUP_CLIENTS, $arUserGroups)) {
                return "client";
            }elseif(in_array(USER_GROUP_CONTRACTORS, $arUserGroups)) {
                return "contractor";
            }elseif (in_array(USER_GROUP_DISTRIBUTORS, $arUserGroups)) {
                return "distributor";
            }elseif (in_array(USER_GROUP_VENDORS, $arUserGroups)) {
                return "vendor";
            }
        };

        return false;
    }

    /**
     * Прошёл ли пользователь сертификацию?
     *
     * @param int $userID
     *
     * @return bool
     */
    public function isCertified($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arUser = \CUser::GetByID($userID)->Fetch();
        if ($arUser["UF_SERTIFIED"]) {

            return true;
        }

        return false;
    }

    /**
     * Получение Email пользователя
     *
     * @param int $userID
     *
     * @return bool
     */
    public function getEmail($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arUser = \CUser::GetByID($userID)->Fetch();

        return $arUser["EMAIL"];
    }


    /**
     * Получение данных клиента
     *
     * @param int $userID
     *
     * @return array $arClient
     */
    public function getClientData($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arUser = \CUser::GetByID($userID)->Fetch();
        $arClient = [
            "ID" => $arUser["ID"],
            "NAME" => $arUser["LAST_NAME"] . " " . $arUser["NAME"] . " " . $arUser["SECOND_NAME"],
            "NAME_SHORT" => $arUser["NAME"] . " " . $arUser["LAST_NAME"],
            "EMAIL" => $arUser["EMAIL"],
            "PHONE" => $arUser["PERSONAL_PHONE"],
            "PICTURE" => \CFile::GetFileArray($arUser["UF_CLIENT_IMAGE"])["SRC"],
        ];

        return $arClient;
    }


    /**
     * Заполнение сведений о городе (код ФИАС и название) пользователя
     *
     * @param int $userID
     * @param array $arLocation
     *
     * @return bool
     * @throws \Exception
     */
    public function setUserCity($userID = null, $arLocation = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        if (!$arLocation["ID"]) {
            throw new \Exception("no_location_specified");
        }

        $obUser = new \CUser;
        $arFields = Array(
            "UF_LOCATION" => $arLocation["ID"],
            "UF_LOCATION_NAME" => $arLocation["NAME"],
        );
        if ($obUser->Update($userID, $arFields)) {

            return true;
        } else {
            throw new \Exception($obUser->LAST_ERROR);
        };
    }


    /**
     * Получение кода ФИАС города пользователя.
     * Если пользователь авторизован, значение берется из свойства пользователя,
     * если нет - из куки.
     *
     * @param int $userID
     *
     * @return mixed
     */
    public function getUserCityID($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            }
        }

        $userCityID = false;

        if ($userID || $_COOKIE["BITRIX_SM_USER_CITY"]) {
            if ($userID) {
                $arUser = \CUser::GetByID($userID)->Fetch();
                $userCityID = $arUser["UF_LOCATION"];
            } else {
                $userCityID = $_COOKIE["BITRIX_SM_USER_CITY"];
            }
        }

        return $userCityID;
    }


    /**
     * Получение названия города пользователя.
     * Если пользователь авторизован, значение берется из свойства пользователя,
     * если нет - из куки.
     *
     * @param int $userID
     *
     * @return mixed
     */
    public function getUserCityName($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            }
        }

        $userCityName = false;

        if ($userID || $_COOKIE["BITRIX_SM_USER_CITY"]) {
            if ($userID) {
                $arUser = \CUser::GetByID($userID)->Fetch();
                $userCityName = $arUser["UF_LOCATION_NAME"];
            } else {
                $userCityName = $_COOKIE["BITRIX_SM_USER_CITY_NAME"];
            }
        }

        return $userCityName;
    }


    /**
     * Добавление уведомления
     *
     * @param $arNotification
     *
     * @return bool
     */
    public static function addNotification($arNotification) {

        $arFields = [
            "UF_TYPE" => $arNotification["TYPE"],
            "UF_VIEWED" => false,
        ];

        switch ($arNotification["TYPE"]) {
            case "NEW_REVIEW":
                $arFields["UF_USER"] = $arNotification["USER"];
                $obContractor = new Contractor();
                $contractorCode = $obContractor->getActualContractorCode($arNotification["USER"]);
                $arAuthor = \CUser::GetByID($arNotification["AUTHOR"])->Fetch();
                $arFields["UF_CONTENT"] = $arAuthor["NAME"] . " " . $arAuthor["LAST_NAME"];
                $arFields["UF_LINK"] = PATH_MARKETPLACE . $contractorCode . "/";
                break;
            case "NEW_COMMENT":
                $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', "PROPERTY_USER"];
                $arArticle = \CIBlockElement::GetList([], ["IBLOCK_ID" => IBLOCK_ARTICLES, "=ID" => $arNotification["ARTICLE"]], false, false, $arSelect)->Fetch();
                $articleAuthorID = $arArticle["PROPERTY_USER_VALUE"];
                // Оповещение не создаётся, если автор комментария и автор статьи - один пользователь
                if ($arNotification["AUTHOR"] == $articleAuthorID) {

                    return false;
                }
                $arFields["UF_USER"] = $articleAuthorID;
                $arAuthor = \CUser::GetByID($arNotification["AUTHOR"])->Fetch();
                $arFields["UF_CONTENT"] = $arAuthor["NAME"] . " " . $arAuthor["LAST_NAME"];
                $arFields["UF_LINK"] = PATH_ARTICLES . $arArticle["CODE"] . "/";

                break;
            case "PROFILE_MODERATION":
                $arFields["UF_USER"] = $arNotification["USER"];
                $arFields["UF_LINK"] = PATH_MARKETPLACE . $arNotification["CODE"] . "/";

                break;
        }

        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_NOTIFICATIONS]);
        $arHLDataClasses["NOTIFICATIONS"]::add($arFields);


        return true;
    }


    public function getNotifications() {
        global $USER;

        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_NOTIFICATIONS, HLBLOCK_VIEWS_DETAIL]);

        $userID = $USER->GetID();

        $rsData = $arHLDataClasses["NOTIFICATIONS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_USER" => $userID],
                "order" => ["ID" => "DESC"],
            ]
        );
        $arNotifications = [];
        $count = 0;
        while ($arItem = $rsData->Fetch()) {

            if ($count++ == MAX_NOTIFICATIONS_SHOW) break;

            switch ($arItem["UF_TYPE"]) {
                case "NEW_REVIEW":
                    $text = "<b>Новый отзыв</b> от клиента " . $arItem["UF_CONTENT"];
                    break;
                case "NEW_COMMENT":
                    $text = $arItem["UF_CONTENT"] . " оставил <b>комментарий</b> к вашей статье";
                    break;
                case "PROFILE_MODERATION":
                    $text = "Изменения вашего профиля прошли модерацию";
                    break;
            }

            $arNotification = [
                "TEXT" => $text,
                "LINK" => $arItem["UF_LINK"]
            ];

            if ($arItem["UF_VIEWED"]) {
                $arNotifications["VIEWED"][] = $arNotification;
            } else {
                $arNotifications["NEW"][] = $arNotification;
            }

        }

        // Получение числа новых просмотров
        $viewsDetail = $arHLDataClasses["VIEWS_DETAIL"]::getCount(["UF_USER" => $userID]);
        $userLastViews = \CUser::GetByID($userID)->Fetch()["UF_LAST_VIEWS"];
        if ($userLastViews) {
            $newViews = $viewsDetail - $userLastViews;
        } else {
            $newViews = $viewsDetail;
        };

        if ($newViews) {
            $arNotifications["NEW_VIEWS"] = $newViews . " " . DataHelper::getWordForm("views", $newViews);
        }

        if (!$arNotifications["NEW_VIEWS"] && !$arNotifications["NEW"] && !$arNotifications["VIEWED"]) {
            $arNotifications = [
                "VIEWED" => [
                    ["TEXT" => "Вы успешно <b>зарегистрировались</b>"],
                ],
            ];
        }

        return $arNotifications;

    }


    /**
     * Определение, есть ли у текущего пользователя заявки на сертификацию
     *
     * @return boolean
     */
    public function hasCertificationRequest() {
        $arFilter = ["IBLOCK_ID" => IBLOCK_EMASTER_REQUESTS, "=PROPERTY_USER" => $this->currentUserID];
        $obRequests = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID']);
        if ($obRequests->SelectedRowsCount()) {

            return true;
        } else {

            return false;
        }
    }


    /**
     * Определение, есть ли у текущего пользователя отправленное подтверждение членства в РАЭК
     *
     * @return boolean
     */
    public function hasRaekVerification() {
        $arFilter = ["IBLOCK_ID" => IBLOCK_RAEK_VERIFICATIONS, "=PROPERTY_USER" => $this->currentUserID];
        $obRequests = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID']);
        if ($obRequests->SelectedRowsCount()) {

            return true;
        } else {

            return false;
        }
    }


    /**
     * Определение, есть ли у текущего пользователя заявки на сертификацию РАЭК
     *
     * @return boolean
     */
    public function hasRaekRequest() {
        $arFilter = ["IBLOCK_ID" => IBLOCK_RAEK_REQUESTS, "=PROPERTY_USER" => $this->currentUserID];
        $obRequests = \CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'IBLOCK_ID']);
        if ($obRequests->SelectedRowsCount()) {

            return true;
        } else {

            return false;
        }
    }


    /**
     * Подключение классов для работы с сущностями D7
     *
     * @param array $arList - массив ID нужных HL-блоков
     *
     * @return array
     */
    public static function getHLDataClasses($arList) {
        \CModule::IncludeModule("highloadblock");

        $arDataClasses = [];

        if (in_array(HLBLOCK_NOTIFICATIONS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_NOTIFICATIONS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["NOTIFICATIONS"] = $obEntity->getDataClass();
        }
        if (in_array(HLBLOCK_VIEWS_DETAIL, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_VIEWS_DETAIL)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["VIEWS_DETAIL"] = $obEntity->getDataClass();
        }
        if (in_array(HLBLOCK_CONTRACTOR_POINTS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CONTRACTOR_POINTS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["CONTRACTOR_POINTS"] = $obEntity->getDataClass();
        }
        if (in_array(HLBLOCK_CONTRACTOR_POINTS_HISTORY, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CONTRACTOR_POINTS_HISTORY)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["CONTRACTOR_POINTS_HISTORY"] = $obEntity->getDataClass();
        }

        return $arDataClasses;

    }



}
