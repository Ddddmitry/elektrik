<?php

namespace Electric;

use Bitrix\Highloadblock\HighloadBlockTable;
use \Bitrix\Main\Type\Date;
use Electric\Notification;
use Electric\Helpers\DataHelper;
use Electric\User;

/**
 * Class Client
 *
 * Класс, содержащий методы для работы с клиентами и связанными сущностями
 *
 * @package Electric
 */
class Client
{
    private $currentUserID;

    private $arHLDataClasses;

    public function __construct()
    {
        \CModule::IncludeModule("iblock");
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->currentUserID = $USER->GetID();
        }
    }

    public function getActualClient($userID, $arSelect = []) {
        $arSelectDefault = ["ID", "IBLOCK_ID", "NAME"];
        $arSelect = array_unique(array_merge($arSelectDefault, $arSelect));
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($arResult = $dbResult->Fetch()) {

            if ($arResult["PREVIEW_PICTURE"]) {
                if ($arFile = \CFile::GetFileArray($arResult["PREVIEW_PICTURE"])) {
                    $arResult["PREVIEW_PICTURE"] = $arFile;
                }
            }

            return $arResult;
        } else {

            return false;
        }
    }


    public function getActualClientCode($userID) {
        $arSelect = ["ID", "IBLOCK_ID", "CODE"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($arResult = $dbResult->Fetch()) {

            return $arResult["CODE"];
        } else {

            return false;
        }
    }


    /**
     * Получение данных об клиенте
     *
     * @param int $arFindBy
     * @param bool $showDraft
     *
     * @return array|bool
     */
    public function getClientData($arFindBy = null, $showDraft = false) {
        if (!$arFindBy) {
            if ($this->currentUserID) {
                $arFindBy = ["PROPERTY_USER" => $this->currentUserID];
            } else {
                return false;
            }
        }

        $arOrder = [];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CLIENTS,
        ];
        $arFilter = array_merge($arFilter, $arFindBy);
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
            'IBLOCK_ID',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
        ];
        if (!$showDraft) {
            $arFilter["ACTIVE"] = "Y";
        } else {
            $arOrder["TIMESTAMP_X"] = "DESC";
        }

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        if ($arClient = $dbResult->Fetch()) {

            // Получение изображения
            if ($arClient['PREVIEW_PICTURE']) {
                $arClient['PREVIEW_PICTURE'] = \CFile::GetFileArray($arClient['PREVIEW_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arClient['IBLOCK_ID'],
                $arClient['ID'],
                ["value_id" => "asc"]
            );
            while ($arProperty = $dbProperty->Fetch()) {
                switch ($arProperty["CODE"]) {
                    case "ADDRESS_COORDS":
                        $arCoords = explode(" ", $arProperty["VALUE"]);
                        $arClient['PROPERTIES'][$arProperty["CODE"]]["VALUE"] = implode(", ", array_reverse($arCoords));
                        break;
                    default:
                        if ($arProperty["MULTIPLE"] === "N") {
                            $arClient['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                        } else {
                            if ($arProperty["VALUE"]) {
                                $arClient['PROPERTIES'][$arProperty["CODE"]][] = $arProperty;
                            }
                        }
                        break;
                }
            }


            return $arClient;
        }

        return false;
    }


    /**
     * Получение данных об клиенте, хранящихся в его пользователе
     *
     * @param $clientID
     *
     * @return mixed
     */
    public function getUserData($clientID) {
        $arUser = \CUser::GetByID($clientID)->Fetch();
        //$arUserData["RATING"] = $arUser["UF_RATING"];
        $arUserData = $arUser;

        return $arUserData;
    }


    /**
     * Получение полного набора полей и свойств для создания копии элемента клиента
     *
     * @param int $clientID
     *
     * @return array|bool
     */
    private function getClientCopyArray($clientID) {
        $dbResult = \CIBlockElement::GetByID($clientID);
        if ($arFieldsOriginal = $dbResult->Fetch()) {

            $arFieldsCopy = [
                "ACTIVE" => "N",
                "IBLOCK_SECTION_ID" => $arFieldsOriginal["IBLOCK_SECTION_ID"],
                "IBLOCK_ID" => $arFieldsOriginal["IBLOCK_ID"],
                "NAME"  => $arFieldsOriginal["NAME"],
                "CODE"  => $arFieldsOriginal["CODE"],
                "PREVIEW_TEXT" => $arFieldsOriginal["PREVIEW_TEXT"],
                "PREVIEW_PICTURE" => \CFile::MakeFileArray($arFieldsOriginal["PREVIEW_PICTURE"])
            ];

            $arPropertiesCopy = [];
            $dbProperty = \CIBlockElement::getProperty(IBLOCK_CLIENTS, $arFieldsOriginal['ID']);
            while($arProperty = $dbProperty->Fetch()){
                if (in_array($arProperty["PROPERTY_TYPE"], array("S", "L", "F", "N"))) {
                    $value = ($arProperty["PROPERTY_TYPE"] == "F") ? \CFile::MakeFileArray($arProperty["VALUE"]) : $arProperty["VALUE"];
                    if ($arProperty["MULTIPLE"] == "N") {
                        $arPropertiesCopy[$arProperty["CODE"]] = $value;
                    } else {
                        $arPropertiesCopy[$arProperty["CODE"]][] = $value;
                    }
                }
            }

            $arResult = [
                "FIELDS" => $arFieldsCopy,
                "PROPERTIES" => $arPropertiesCopy,
            ];

            return $arResult;
        } else {

            return false;
        }
    }

    private function updateHLReferences($clientID, $arHLReferences) {

        foreach ($arHLReferences as $key => $arHLReferencesItem) {
            $rsData = $this->arHLDataClasses[$key]::getList(
                [
                    "select" => ["ID"],
                    "filter" => ["UF_CONTRACTOR" => $clientID],
                    "order" => [],
                ]
            );
            $arItems = $rsData->fetchAll();
            foreach ($arItems as $arItem) {
                $this->arHLDataClasses[$key]::delete($arItem["ID"]);
            }
            foreach ($arHLReferencesItem as $arRow) {
                $arRow["UF_CONTRACTOR"] = $clientID;
                $this->arHLDataClasses[$key]::add($arRow);
            }
        }
    }

    private function copyHLReferences($newClientID, $originalClientID, $arIgnore = []) {

        $arHLBlocks = ["JOBS", "LANGUAGES", "SERVICES", "EDUCATIONS", "COURSES"];

        foreach ($arHLBlocks as $hlBlock) {
            if (!in_array($hlBlock, $arIgnore)) {
                $rsData = $this->arHLDataClasses[$hlBlock]::getList(
                    [
                        "select" => ['*'],
                        "filter" => ["UF_CONTRACTOR" => $originalClientID],
                        "order" => [],
                    ]
                );
                $arItems = $rsData->fetchAll();
                foreach ($arItems as $arItem) {
                    unset($arItem["ID"]);
                    $arItem["UF_CONTRACTOR"] = $newClientID;
                    $this->arHLDataClasses[$hlBlock]::add($arItem);
                }
            }
        }
    }


    /**
     * Обновление клиента путём создания неактивной копии его элемента, включающей изменённые данные
     *
     * @param null  $userID
     * @param array $arFields
     * @param array $arProperties
     * @param array $arHLReferences
     *
     * @throws \Exception
     *
     * @return int - возвращает ID созданного или обновлённого черновика
     */
    public function updateClient($userID = null, $arFields = [], $arProperties = [], $arHLReferences = []) {
        $this->arHLDataClasses = self::getHLDataClasses(
            [HLBLOCK_JOBS, HLBLOCK_LANGUAGES, HLBLOCK_CONTRACTOR_TO_SERVICE, HLBLOCK_EDUCATIONS, HLBLOCK_COURSES]
        );

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }
        $clientID = $this->getClientID($userID);
        $obElement = new \CIBlockElement;

        // Обновление полей
        if (!$obElement->Update($clientID, $arFields)) {
            throw new \Exception($obElement->LAST_ERROR);
        };

        // Удаление временных файлов для полей
        if ($arFields["PREVIEW_PICTURE"]) {
            unlink($arFields["PREVIEW_PICTURE"]["tmp_name"]);
        }

        // Обновление свойств
        if ($arProperties) {
            foreach ($arProperties as &$arProperty) {
                if (!$arProperty) $arProperty = false;
            }

            \CIBlockElement::SetPropertyValuesEx($clientID, IBLOCK_CLIENTS, $arProperties);
        }

        // Удаление временных файлов для свойств
        foreach ($arProperties as $arProperty) {
            if ($tmpName = current($arProperty)["VALUE"]["tmp_name"]) {
                unlink($tmpName);
            }
        }



        // Отправка модератору уведомления об изменении анкеты клиента
        $arMailData = [
            "USER_ID" => $userID,
            "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CLIENTS."&type=users&ID=".$clientID,
        ];
        Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

        $resultID = $clientID;

        return $resultID;
    }

    /**
     * Получение ID актуальной версии анкеты клиента для пользователя
     *
     * @param int $userID
     *
     * @return int|bool
     */
    public function getClientID($userID) {
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $rsClients = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($arClient = $rsClients->Fetch()) {

            return $arClient["ID"];
        } else {

            return false;
        }
    }

    /**
     * Получение ID черновика анкеты клиента для пользователя, если он существует
     *
     * @param int $userID
     *
     * @return int|bool
     */
    public function getDraftID($userID) {
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "ACTIVE" => "N",
            "PROPERTY_USER" => $userID,
        ];
        $rsDrafts = \CIBlockElement::GetList(array(), $arFilter, false, false, ["ID"]);
        if ($arDraft = $rsDrafts->Fetch()) {

            return $arDraft["ID"];
        } else {

            return false;
        }
    }


    /**
     * Получение каоличества отзывов о пользователе
     *
     * @param integer $userID
     * @param \DateTime  $obDate - дата, с которой считаются отзывы
     *
     * @return integer
     */
    public function getReviewsCount($userID = null, $obDate = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userID,
        ];
        if ($obDate) {
            $date = (\Bitrix\Main\Type\DateTime::createFromPhp($obDate))->toString();
            $arFilter["ACTIVE_FROM"] = $date;
        }
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        $reviewsCount = $dbResult->SelectedRowsCount();

        return $reviewsCount;
    }

    /**
     * Получение полных данных отзывов о пользователе
     *
     * @param null $userID
     * @param bool $arNavData
     * @param null $filterMark - фильтрация по оценке
     *
     * @return array|bool
     */
    public function getReviews($userID = null, $arNavData = false, $filterMark = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arReviews = [];

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userID,
        ];
        if ($filterMark) {
            $arFilter["PROPERTY_MARK"] = $filterMark;
        }
        $arSelect = [
            'ID',
            'NAME',
            'ACTIVE_FROM',
            'IBLOCK_ID',
            'PREVIEW_TEXT',
            'PROPERTY_USER',
            'PROPERTY_AUTHOR',
            'PROPERTY_MARK',
            'PROPERTY_ANSWER',
            'PROPERTY_VIEWED'
        ];
        $dbResult = \CIBlockElement::GetList(["ACTIVE_FROM" => "DESC"], $arFilter, false, $arNavData, $arSelect);
        $arReviews["NAV"]["TOTAL_COUNT"] = $dbResult->NavRecordCount;
        if (($arNavData && $arNavData["iNumPage"] == $dbResult->NavPageCount) || !$dbResult->NavPageCount) {
            $arReviews["NAV"]["IS_LAST_PAGE"] = true;
        };

        while ($arReview = $dbResult->Fetch()) {
            $arReviews["ITEMS"][] = $arReview;
        }

        $arFilter = [
            "ID" => implode("|", array_unique(array_column($arReviews["ITEMS"], "PROPERTY_AUTHOR_VALUE"))),
        ];
        $dbResult = \CUser::GetList(($by="ID"), ($order="desc"), $arFilter);
        $arAuthors = [];
        while ($arAuthor = $dbResult->Fetch()) {
            $arAuthors[$arAuthor["ID"]] = $arAuthor;
        }

        foreach ($arReviews["ITEMS"] as &$arReview) {
            if ($arReview["PROPERTY_AUTHOR_VALUE"] == $this->currentUserID) {
                $arReview["CAN_EDIT"] = true;
            }
            $arReview["AUTHOR_NAME"] = $arAuthors[$arReview["PROPERTY_AUTHOR_VALUE"]]["NAME"] . " " . $arAuthors[$arReview["PROPERTY_AUTHOR_VALUE"]]["LAST_NAME"];
        }

        return $arReviews;
    }

    public function getRatingChanges($userID = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $ratingLastMonth = $this->getRating($userID, (new \DateTime())->modify('-1 month'));
        $ratingMonthBefore = $this->getRating($userID, (new \DateTime())->modify('-2 month'), (new \DateTime())->modify('-1 month'));

        $ratingChanges = [
            "LAST_MONTH" => $ratingLastMonth,
            "MONTH_CHANGE" => $ratingLastMonth - $ratingMonthBefore,
        ];

        return $ratingChanges;
    }

    /**
     * Получение числа новых отзывов для пользователя
     *
     * @param int $userID
     *
     * @return int
     */
    public function getNewReviewsCount($userID = null)
    {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'PROPERTY_VIEWED' => false,
            '=PROPERTY_USER' => $userID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        $unreadReviewsCount = $dbResult->SelectedRowsCount();

        return $unreadReviewsCount ? $unreadReviewsCount : 0;
    }


    /**
     * Получение распределения отзывов по оценкам
     *
     * @param $userID
     *
     * @return array
     */
    public function getReviewsSpread($userID) {
        $arReviews = [];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userID,
        ];
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_MARK',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arReview = $dbResult->Fetch()) {
            $arReviews[] = $arReview;
        }
        $arSpread = array_count_values(array_column($arReviews, "PROPERTY_MARK_VALUE"));
        foreach ($arSpread as &$markCount) {
            $markCount = $markCount . " " . DataHelper::getWordForm("reviews", $markCount);
        }

        return $arSpread;
    }


    /**
     * Подсчёт рейтинга (средней оценки отзывов) для пользователя
     *
     * @param integer $userID
     * @param \DateTime $obStartDate
     * @param \DateTime $obEndDate
     *
     * @return bool|float|int
     */
    public function getRating($userID = null, $obStartDate = null, $obEndDate = null) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userID,
        ];
        if ($obStartDate) {
            $startDate = (\Bitrix\Main\Type\DateTime::createFromPhp($obStartDate))->toString();;
            $arFilter[">DATE_ACTIVE_FROM"] = $startDate;
        }
        if ($obEndDate) {
            $endDate = (\Bitrix\Main\Type\DateTime::createFromPhp($obEndDate))->toString();;
            $arFilter["<DATE_ACTIVE_FROM"] = $endDate;
        }
        $arSelect = [
            'ID',
            'ACTIVE_FROM',
            'IBLOCK_ID',
            'PROPERTY_MARK',
        ];
        $arMarks = [];

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arReview = $dbResult->Fetch()) {
            if ($arReview["PROPERTY_MARK_VALUE"]) {
                $arMarks[] = $arReview["PROPERTY_MARK_VALUE"];
            }
        }

        if ($arMarks) {
            $rating = round(array_sum($arMarks)/count($arMarks), 1);
        } else {
            $rating = 0;
        }

        return $rating;
    }

    /**
     * Сохранение рейтинга в пользователе клиента
     *
     * @param $userID
     * @param $rating
     *
     * @return bool
     * @throws \Exception
     */
    public function saveRating($userID, $rating) {
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $obUser = new \CUser;
        $arFields = Array(
            "UF_RATING" => $rating,
        );
        if ($obUser->Update($userID, $arFields)) {

            return true;
        } else {
            throw new \Exception($obUser->LAST_ERROR);
        };
    }

    /**
     * Обновление ответа на отзыв
     *
     * @param $reviewID
     * @param $answerText
     *
     * @return bool
     */
    public function updateReviewAnswer($reviewID, $answerText) {
        \CIBlockElement::SetPropertyValuesEx($reviewID, false, ["ANSWER" => $answerText]);

        return true;
    }

    public static function getReviewCode($reviewID) {
        return \CIBlockElement::GetByID($reviewID)["CODE"];
    }


    public function createProfile ($arFields,$userID = null){
        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }
        $arUser = \CUser::GetByID($userID)->Fetch();
        $fullName = trim($arFields["LAST_NAME"]." ".$arFields["NAME"]);
        $obSection = new \CIBlockSection;
        $arClientSectionFields = Array(
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "NAME" => $fullName,
        );
        $clientSectionID = $obSection->Add($arClientSectionFields);
        if (!$clientSectionID) {
            throw new \Exception($obSection->LAST_ERROR);
        }

        $obElement = new \CIBlockElement;
        $arClientFields = Array(
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "NAME" => $fullName,
            "IBLOCK_SECTION_ID" => $clientSectionID,
        );
        if ($clientID = $obElement->Add($arClientFields)) {
            $arClientProperties = array(
                "USER" => $arUser["ID"],
                "EMAIL" => $arUser["EMAIL"],
                "PHONE" => $arUser["PERSONAL_PHONE"]
            );
            \CIBlockElement::SetPropertyValuesEx($clientID, false, $arClientProperties);
        }
        return $clientID;
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

        if (in_array(HLBLOCK_CONTRACTOR_TO_SERVICE, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CONTRACTOR_TO_SERVICE)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["SERVICES"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_JOBS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_JOBS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["JOBS"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_SKILLS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_SKILLS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["SKILLS"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_LANGUAGES, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_LANGUAGES)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["LANGUAGES"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_LANGUAGES_LIST, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_LANGUAGES_LIST)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["LANGUAGES_LIST"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_LANGUAGES_LEVELS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_LANGUAGES_LEVELS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["LANGUAGES_LEVELS"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_EDUCATIONS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_EDUCATIONS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["EDUCATIONS"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_COURSES, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_COURSES)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["COURSES"] = $obEntity->getDataClass();
        }

        return $arDataClasses;

    }
}
