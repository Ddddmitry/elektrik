<?php

namespace Electric;

use Bitrix\Highloadblock\HighloadBlockTable;
use \Bitrix\Main\Type\Date;
use Electric\Notification;
use Electric\Helpers\DataHelper;
use Electric\User;
use Bitrix\Main\PhoneNumber\ShortNumberFormatter;
use Bitrix\Main\PhoneNumber\Parser;
use Bitrix\Main\PhoneNumber\Format;
use Electric\Request;
use Electric\RequestSoap;


/**
 * Class Contractor
 *
 * Класс, содержащий методы для работы с исполнителями и связанными сущностями
 *
 * @package Electric
 */
class Contractor
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

    public function getActualContractor($userID, $arSelect = []) {
        $arSelectDefault = ["ID", "IBLOCK_ID", "NAME"];
        $arSelect = array_unique(array_merge($arSelectDefault, $arSelect));
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($arResult = $dbResult->Fetch()) {

            if ($arResult["DETAIL_PAGE_URL"]) {
                $arResult["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arResult["DETAIL_PAGE_URL"], $arResult);
            }

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


    public function getActualContractorCode($userID) {
        $arSelect = ["ID", "IBLOCK_ID", "CODE"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
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
     * Получение данных об исполнителе
     *
     * @param int $arFindBy
     * @param bool $showDraft
     *
     * @return array|bool
     */
    public function getContractorData($arFindBy = null, $showDraft = false) {
        if (!$arFindBy) {
            if ($this->currentUserID) {
                $arFindBy = ["PROPERTY_USER" => $this->currentUserID];
            } else {
                return false;
            }
        }

        $arOrder = [];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
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

        if ($arContractor = $dbResult->Fetch()) {

            // Получение изображения
            if ($arContractor['PREVIEW_PICTURE']) {
                $arContractor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arContractor['PREVIEW_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arContractor['IBLOCK_ID'],
                $arContractor['ID'],
                ["value_id" => "asc"]
            );
            while ($arProperty = $dbProperty->Fetch()) {
                switch ($arProperty["CODE"]) {
                    case "DOCUMENTS":
                        if ($arFile = \CFile::GetFileArray($arProperty["VALUE"])) {
                            $arDescription = explode("||", $arFile["DESCRIPTION"]);
                            $arFile["DESCRIPTION"] = $arDescription[0];
                            $arFile["DATE"] = $arDescription[1];
                            //$arFile["DATE_DISPLAY"] = (new Date($arDescription[1], "Y-m-d"))->toString();
                            $arContractor["PROPERTIES"]["DOCUMENTS"][] = $arFile;
                        }
                        break;
                    case "EXAMPLES":
                        if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                            $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                            $arContractor["PROPERTIES"]["EXAMPLES"][] = [
                                "FULL" => $arPicture,
                                "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 365, 'height' => 900], BX_RESIZE_IMAGE_PROPORTIONAL, true),
                                "TITLE" => $arDescription[0],
                                "DESCRIPTION" => $arDescription[1],
                            ];
                        }
                        break;
                    case "ROOM":
                        $arContractor['PROPERTIES'][$arProperty["CODE"]][] = [
                            "VALUE" => $arProperty["VALUE"],
                            "VALUE_ENUM" => $arProperty["VALUE_ENUM"],
                        ];
                        break;
                    case "ADDRESS_COORDS":
                        $arCoords = explode(" ", $arProperty["VALUE"]);
                        $arContractor['PROPERTIES'][$arProperty["CODE"]]["VALUE"] = implode(", ", array_reverse($arCoords));
                        break;
                    default:
                        if ($arProperty["MULTIPLE"] === "N") {
                            $arContractor['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                        } else {
                            if ($arProperty["VALUE"]) {
                                $arContractor['PROPERTIES'][$arProperty["CODE"]][] = $arProperty;
                            }
                        }
                        break;
                }
            }

            // Получение названия квалификации
            if ($arContractor["PROPERTIES"]["SKILL"]["VALUE"]) {
                $arContractor["PROPERTIES"]["SKILL"]["VALUE_NAME"] = $this->getSkillName($arContractor["PROPERTIES"]["SKILL"]["VALUE"]);
            }

            // Получение данных из пользователя исполнителя
            $arUserData = $this->getUserData($arContractor["PROPERTIES"]["USER"]["VALUE"]);
            $arContractor["RATING"] = $arUserData["UF_RATING"] ?? 0;
            $arContractor["FREE"] = $arUserData["UF_FREE"];
            $arContractor["TIME"] = $arUserData["UF_TIME"];
            $arContractor["TIME_WORD"] = DataHelper::getWordForm("time",$arUserData["UF_TIME"]) ;
            $arContractor["SERTIFIED"] = $arUserData["UF_SERTIFIED"];


            // Получение данных об услугах
            $obContractor = new Contractor();
            $arContractorServices = $obContractor->getGroupedServices($arContractor["ID"]);
            $arContractor["SERVICES"] = $arContractorServices;

            return $arContractor;
        }

        return false;
    }


    /**
     * Получение данных об исполнителе, хранящихся в его пользователе
     *
     * @param $contractorID
     *
     * @return mixed
     */
    public function getUserData($contractorID) {
        $arUser = \CUser::GetByID($contractorID)->Fetch();
        //$arUserData["RATING"] = $arUser["UF_RATING"];
        $arUserData = $arUser;

        return $arUserData;
    }

    /**
     * Получение данных о местах работы исполнителя
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getJobs($contractorID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_JOBS]);

        $rsData = $this->arHLDataClasses["JOBS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_CONTRACTOR" => $contractorID],
                "order" => ["UF_START_DATE" => "DESC"],
            ]
        );
        $arJobs = [];
        while ($arItem = $rsData->Fetch()) {
            $arJobs[] = [
                "NAME" => $arItem["UF_NAME"],
                "START_DATE" => $arItem["UF_START_DATE"],
                "END_DATE" => $arItem["UF_END_DATE"],
                "IS_NOW" => $arItem["UF_NOW"],
            ];
        }

        return $arJobs;
    }

    /**
     * Получение данных о примерах работы
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getWorks($contractorID) {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT'];
        $arOrder = [
            "SORT" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS_WORKS,
            "PROPERTY_CONTRACTOR_USER_ID" => $contractorID
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arWork = $dbResult->fetch()) {
            // Получение изображения
            if ($arWork['PREVIEW_PICTURE']) {
                $arWork['PREVIEW_PICTURE'] = \CFile::GetFileArray($arWork['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arWork["DATE"] = DataHelper::getFormattedDate($arWork["ACTIVE_FROM"]);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arWork['IBLOCK_ID'],
                $arWork['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "MORE_PHOTO") {
                    if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                        $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                        $arWork['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = [
                            "FULL" => $arPicture,
                            "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 365, 'height' => 230], BX_RESIZE_IMAGE_PROPORTIONAL, true),
                            "TITLE" => $arDescription[0],
                            "DESCRIPTION" => $arDescription[1],
                        ];
                    }
                }elseif ($arProperty["CODE"] == "VIDEOS") {
                    if($arProperty["VALUE"])
                        $arWork['PROPERTIES'][$arProperty["CODE"]]["VALUES"][] = $arProperty["VALUE"];
                }else{
                    $arWork['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }
            $arResult[] = $arWork;
        }

        return $arResult;

    }

    /**
     * Получение данных о посещенных мероприятиях
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getEvents($arEvents) {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'DETAIL_PAGE_URL','PROPERTY_TYPE.ID','PROPERTY_TYPE.NAME'];
        $arOrder = [
            "ACTIVE_FROM" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EVENTS,
            "ID" => $arEvents
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arEvent = $dbResult->fetch()) {
            // Получение даты
            $arEvent["DATE"] = DataHelper::getFormattedDate($arEvent["ACTIVE_FROM"]);

            $arEvent["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arEvent["DETAIL_PAGE_URL"], $arEvent);

            $arResult[] = $arEvent;
        }
        return $arResult;

    }

    /**
     * Получение данных о пройденных обучалках
     *
     *
     * @return array
     */
    public function getStudies($arStudies) {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'DETAIL_PAGE_URL','PROPERTY_TYPE.ID','PROPERTY_TYPE.NAME','PROPERTY_DISTRIBUTOR.PREVIEW_PICTURE'];
        $arOrder = [
            "ACTIVE_FROM" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EDUCATIONS,
            "ID" => $arStudies
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arStudy = $dbResult->fetch()) {

            // Получение даты
            $arStudy["DATE"] = DataHelper::getFormattedDate($arStudy["ACTIVE_FROM"]);
            $arStudy["AUTHOR_PICTURE"] = \CFile::GetFileArray($arStudy['PROPERTY_DISTRIBUTOR_PREVIEW_PICTURE']);
            $arStudy["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arStudy["DETAIL_PAGE_URL"], $arStudy);

            $arResult[] = $arStudy;
        }
        return $arResult;

    }

    /**
     * Получение данных об уровнях владения языками исполнителя
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getLanguages($contractorID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_LANGUAGES]);
        $rsData = $this->arHLDataClasses["LANGUAGES"]::getList(
            [
                "select" => ["*"],
                "filter" => ["UF_CONTRACTOR" => $contractorID],
                "order" => ["UF_LANGUAGE" => "ASC"],
            ]
        );
        $arLanguages = [];
        while ($arItem = $rsData->Fetch()) {
            $arLanguages[] = [
                "LANGUAGE" => $arItem["UF_LANGUAGE"],
                "LEVEL" => $arItem["UF_LEVEL"],
            ];
        }

        $arLanguagesNames = $this->getLanguagesNames(array_column($arLanguages, "LANGUAGE"));
        $arLanguagesLevels = $this->getLanguagesLevels(array_column($arLanguages, "LEVEL"));
        foreach ($arLanguages as &$arLanguage) {
            $arLanguage = [
                "LANGUAGE" => [
                    "ID" => $arLanguage["LANGUAGE"],
                    "NAME" => $arLanguagesNames[$arLanguage["LANGUAGE"]],
                ],
                "LEVEL" => [
                    "ID" => $arLanguage["LEVEL"],
                    "NAME" => $arLanguagesLevels[$arLanguage["LEVEL"]],
                ],
            ];
        }

        return $arLanguages;
    }

    /**Собираем статьи исполнителя
     * @param null $userID
     * @return array
     */
    public function getArticles($userID = null){

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arSelect = ['ID', 'ACTIVE', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', "PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES,
            "=PROPERTY_USER" =>  $userID,
        ];
        $dbResult = \CIBlockElement::GetList(["ACTIVE_FROM" => "DESC"], $arFilter, false, false, $arSelect);

        $arArticles = [];
        $arTypesID = [];
        while ($arArticle = $dbResult->Fetch()) {

            // Получение URL детальной страницы
            $arIblock = \CIBlock::GetByID(IBLOCK_ARTICLES)->GetNext();
            $urlTemplate = $arIblock["DETAIL_PAGE_URL"];
            $arArticle["DETAIL_PAGE_URL"] = \CIBlock::ReplaceDetailUrl($urlTemplate, $arArticle);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arArticle['IBLOCK_ID'],
                $arArticle['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "TAGS") {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = $arProperty["VALUE"];
                } else {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }

            $arTypesID[] = $arArticle["PROPERTIES"]["TYPE"]["VALUE"];

            $arArticles[] = $arArticle;
        }

        // Получение категорий статей
        $obArticle = new Article();
        $arTypes = $obArticle->getArticlesTypes($arTypesID, false);

        $arArticlesData = [];
        foreach ($arArticles as $arArticle) {

            $arCommentsCount = $obArticle->getCommentsCount($arArticle["ID"], true);
            $arArticle["COMMENTS_NUMBER"] = $arCommentsCount["TOTAL"];
            $arArticle["COMMENTS_NUMBER_TEXT"] = DataHelper::getWordForm("comments", $arCommentsCount["TOTAL"]);
            $arArticle["NEW_COMMENTS_NUMBER"] = $arCommentsCount["NEW"];
            $arArticle["NEW_COMMENTS_NUMBER_TEXT"] = DataHelper::getWordForm("new", $arCommentsCount["NEW"]);
            $arArticle["THEMES"] = [];
            $arArticle["CATEGORY"] = [];
            $arArticle["DATE"] = DataHelper::getFormattedDate($arArticle["ACTIVE_FROM"]);


            if ($arArticle["PREVIEW_PICTURE"]) {
                $arArticle["PREVIEW_PICTURE"] = [
                    "SRC" =>  \CFile::GetFileArray($arArticle["PREVIEW_PICTURE"])["SRC"],
                ];
            }

            if ($arArticle["PROPERTIES"]["TYPE"]["VALUE"]) {
                $arArticle["CATEGORY"] = [
                    "NAME" => $arTypes[$arArticle["PROPERTIES"]["TYPE"]["VALUE"]],
                    "CODE" => $arArticle["PROPERTIES"]["TYPE"]["VALUE"],
                ];
            }

            foreach ($arArticle["PROPERTIES"]["TAGS"]["VALUE"] as $tag) {
                if ($tag) {
                    $arArticleData["THEMES"][] = [
                        "NAME" => $tag,
                    ];
                }
            }


            $arArticlesData[] = $arArticle;
        }
        return $arArticlesData;
    }

    /**
     * Получение данных об образовании исполнителя
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getEductaions($contractorID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_EDUCATIONS]);

        $rsData = $this->arHLDataClasses["EDUCATIONS"]::getList(
            [
                "select" => ["*"],
                "filter" => ["UF_CONTRACTOR" => $contractorID],
                "order" => ["ID" => "ASC"],
            ]
        );
        $arEducations = [];
        while ($arItem = $rsData->Fetch()) {
            $arEducations[] = [
                "NAME" => $arItem["UF_NAME"],
                "STATUS" => $arItem["UF_STATUS"],
            ];
        }

        return $arEducations;
    }

    /**
     * Получение данных о курсах исполнителя
     *
     * @param $contractorID
     *
     * @return array
     */
    public function getCourses($contractorID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_COURSES]);

        $rsData = $this->arHLDataClasses["COURSES"]::getList(
            [
                "select" => ["*"],
                "filter" => ["UF_CONTRACTOR" => $contractorID],
                "order" => ["ID" => "ASC"],
            ]
        );
        $arCourses = [];
        while ($arItem = $rsData->Fetch()) {
            $arCourses[] = [
                "NAME" => $arItem["UF_NAME"],
            ];
        }

        return $arCourses;
    }

    /**
     * Получение названия квалификации по её ID
     *
     * @param int $skillID
     *
     * @return string|bool
     */
    public function getSkillName($skillID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_SKILLS]);

        $rsData = $this->arHLDataClasses["SKILLS"]::getList(
            array(
                "select" => ['*'],
                "filter" => ["ID" => $skillID],
            )
        );
        if ($arItem = $rsData->Fetch()) {
            $skillName = $arItem["UF_NAME"];

            return $skillName;
        } else {

            return false;
        }
    }

    /**
     * Получение названий языков по их ID
     *
     * @param array $arLanguagesID
     *
     * @return array
     */
    public function getLanguagesNames($arLanguagesID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_LANGUAGES_LIST]);

        $arLanguagesNames = [];
        if ($arLanguagesID) {
            $rsData = $this->arHLDataClasses["LANGUAGES_LIST"]::getList(
                array(
                    "select" => ['*'],
                    "filter" => ["ID" => $arLanguagesID],
                )
            );
            while ($arItem = $rsData->Fetch()) {
                $arLanguagesNames[$arItem["ID"]] = $arItem["UF_NAME"];
            }
        }

        return $arLanguagesNames;
    }

    /**
     * Получение названий уровней владения языками по их ID
     *
     * @param array $arLevelsID
     *
     * @return array
     */
    public function getLanguagesLevels($arLevelsID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_LANGUAGES_LEVELS]);

        $arLanguagesLevels = [];
        if ($arLevelsID) {
            $rsData = $this->arHLDataClasses["LANGUAGES_LEVELS"]::getList(
                array(
                    "select" => ['*'],
                    "filter" => ["ID" => $arLevelsID],
                )
            );
            while ($arItem = $rsData->Fetch()) {
                $arLanguagesLevels[$arItem["ID"]] = $arItem["UF_NAME"];
            }
        }

        return $arLanguagesLevels;
    }

    /**
     * Получение полного набора полей и свойств для создания копии элемента исполнителя
     *
     * @param int $contractorID
     *
     * @return array|bool
     */
    private function getContractorCopyArray($contractorID) {
        $dbResult = \CIBlockElement::GetByID($contractorID);
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
            $dbProperty = \CIBlockElement::getProperty(IBLOCK_CONTRACTORS, $arFieldsOriginal['ID']);
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

    private function updateHLReferences($contractorID, $arHLReferences) {

        foreach ($arHLReferences as $key => $arHLReferencesItem) {
            $rsData = $this->arHLDataClasses[$key]::getList(
                [
                    "select" => ["ID"],
                    "filter" => ["UF_CONTRACTOR" => $contractorID],
                    "order" => [],
                ]
            );
            $arItems = $rsData->fetchAll();
            foreach ($arItems as $arItem) {
                $this->arHLDataClasses[$key]::delete($arItem["ID"]);
            }
            foreach ($arHLReferencesItem as $arRow) {
                $arRow["UF_CONTRACTOR"] = $contractorID;
                $this->arHLDataClasses[$key]::add($arRow);
            }
        }
    }

    private function copyHLReferences($newContractorID, $originalContractorID, $arIgnore = []) {

        $arHLBlocks = ["JOBS", "LANGUAGES", "SERVICES", "EDUCATIONS", "COURSES"];

        foreach ($arHLBlocks as $hlBlock) {
            if (!in_array($hlBlock, $arIgnore)) {
                $rsData = $this->arHLDataClasses[$hlBlock]::getList(
                    [
                        "select" => ['*'],
                        "filter" => ["UF_CONTRACTOR" => $originalContractorID],
                        "order" => [],
                    ]
                );
                $arItems = $rsData->fetchAll();
                foreach ($arItems as $arItem) {
                    unset($arItem["ID"]);
                    $arItem["UF_CONTRACTOR"] = $newContractorID;
                    $this->arHLDataClasses[$hlBlock]::add($arItem);
                }
            }
        }
    }


    /**
     * Обновление исполнителя путём создания неактивной копии его элемента, включающей изменённые данные
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
    public function updateContractor_old($userID = null, $arFields = [], $arProperties = [], $arHLReferences = []) {
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

        $obElement = new \CIBlockElement;

        // Если черновик уже существует - обновить его
        if ($draftID = $this->getDraftID($userID)) {

            // Обновление полей
            if (!$obElement->Update($draftID, $arFields)) {
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

                \CIBlockElement::SetPropertyValuesEx($draftID, IBLOCK_CONTRACTORS, $arProperties);
            }

            // Удаление временных файлов для свойств
            foreach ($arProperties as $arProperty) {
                if ($tmpName = current($arProperty)["VALUE"]["tmp_name"]) {
                    unlink($tmpName);
                }
            }

            // Добавление изменённых связей для существующего черновика
            $this->updateHLReferences($draftID, $arHLReferences);

            // Отправка модератору уведомления об изменении анкеты исполнителя
            $arMailData = [
                "USER_ID" => $userID,
                "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CONTRACTORS."&type=users&ID=".$draftID,
            ];
            Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

            $resultID = $draftID;
        } else {
            // Если черновика ещё нет - создать новый
            $contractorID = $this->getContractorID($userID);
            $arContractorCopy = $this->getContractorCopyArray($contractorID);

            $arContractorDraft = [
                "FIELDS" => array_merge($arContractorCopy["FIELDS"], $arFields),
                "PROPERTIES" => array_merge($arContractorCopy["PROPERTIES"], $arProperties, ["IS_DRAFT" => ENUM_VALUE_CONTRACTORS_IS_DRAFT]),
            ];

            if ($newDraftID = $obElement->Add($arContractorDraft["FIELDS"])) {
                \CIBlockElement::SetPropertyValuesEx($newDraftID, false, $arContractorDraft["PROPERTIES"]);

                // Добавление изменённых связей для нового черновика
                $this->updateHLReferences($newDraftID, $arHLReferences);

                // Копирование связей для нового черновика
                $this->copyHLReferences($newDraftID, $contractorID, array_keys($arHLReferences));

                // Отправка модератору уведомления об изменении анкеты исполнителя
                $arMailData = [
                    "USER_ID" => $userID,
                    "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CONTRACTORS."&type=users&ID=".$newDraftID,
                ];
                Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

                $resultID = $newDraftID;
            } else {
                throw new \Exception($obElement->LAST_ERROR);
            };

        };

        return $resultID;
    }

    /**
     * Обновление данных исполнителя без премодерации если он не сертифицирован.
     * И с премодерацией, если сертифицирован
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
    public function updateContractor($userID = null, $arFields = [], $arProperties = [], $arHLReferences = []) {
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

        $obElement = new \CIBlockElement;
        $obUser = new User();


        $isCertificatedContractor = $obUser->isCertified($userID);
        if($isCertificatedContractor){
            // Если черновик уже существует - обновить его
            if ($draftID = $this->getDraftID($userID)) {

                // Обновление полей
                if (!$obElement->Update($draftID, $arFields)) {
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

                    \CIBlockElement::SetPropertyValuesEx($draftID, IBLOCK_CONTRACTORS, $arProperties);
                }

                // Удаление временных файлов для свойств
                foreach ($arProperties as $arProperty) {
                    if ($tmpName = current($arProperty)["VALUE"]["tmp_name"]) {
                        unlink($tmpName);
                    }
                }

                // Добавление изменённых связей для существующего черновика
                $this->updateHLReferences($draftID, $arHLReferences);

                // Отправка модератору уведомления об изменении анкеты исполнителя
                $arMailData = [
                    "USER_ID" => $userID,
                    "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CONTRACTORS."&type=users&ID=".$draftID,
                ];
                Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

                $resultID = $draftID;
            }
            else {
                // Если черновика ещё нет - создать новый
                $contractorID = $this->getContractorID($userID);
                $arContractorCopy = $this->getContractorCopyArray($contractorID);

                $arContractorDraft = [
                    "FIELDS" => array_merge($arContractorCopy["FIELDS"], $arFields),
                    "PROPERTIES" => array_merge($arContractorCopy["PROPERTIES"], $arProperties, ["IS_DRAFT" => ENUM_VALUE_CONTRACTORS_IS_DRAFT]),
                ];

                if ($newDraftID = $obElement->Add($arContractorDraft["FIELDS"])) {
                    \CIBlockElement::SetPropertyValuesEx($newDraftID, false, $arContractorDraft["PROPERTIES"]);

                    // Добавление изменённых связей для нового черновика
                    $this->updateHLReferences($newDraftID, $arHLReferences);

                    // Копирование связей для нового черновика
                    $this->copyHLReferences($newDraftID, $contractorID, array_keys($arHLReferences));

                    // Отправка модератору уведомления об изменении анкеты исполнителя
                    $arMailData = [
                        "USER_ID" => $userID,
                        "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CONTRACTORS."&type=users&ID=".$newDraftID,
                    ];
                    Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

                    $resultID = $newDraftID;
                } else {
                    throw new \Exception($obElement->LAST_ERROR);
                };

            };
        }else{
            $contractorID = $this->getContractorID($userID);
            // Обновление полей
            if (!$obElement->Update($contractorID, $arFields)) {
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

                \CIBlockElement::SetPropertyValuesEx($contractorID, IBLOCK_CONTRACTORS, $arProperties);
            }

            // Удаление временных файлов для свойств
            foreach ($arProperties as $arProperty) {
                if ($tmpName = current($arProperty)["VALUE"]["tmp_name"]) {
                    unlink($tmpName);
                }
            }

            // Добавление изменённых связей для существующего черновика
            $this->updateHLReferences($contractorID, $arHLReferences);

            // Отправка модератору уведомления об изменении анкеты исполнителя
            $arMailData = [
                "USER_ID" => $userID,
                "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_CONTRACTORS."&type=users&ID=".$contractorID,
            ];
            Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);

            $resultID = $contractorID;
        }


        return $resultID;
    }

    /**
     * Обновления примеров работы исполнителя
    */
    public function updateWorks($userID = null, $arWorks){

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arDeleteID = [];
        $arSelect = ['ID'];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS_WORKS,
            "PROPERTY_CONTRACTOR_USER_ID" => $userID
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arEvent = $dbResult->fetch()) {
            $arDeleteID[] = $arEvent["ID"];
        }

        $obElement = new \CIBlockElement;

        foreach ($arWorks as $arWork) {
            $arWork["FIELDS"]["IBLOCK_ID"] = IBLOCK_CONTRACTORS_WORKS;
            $arWork["PROPERTIES"]["CONTRACTOR_USER_ID"] = $userID;

            if($newID = $obElement->Add($arWork["FIELDS"])){
                \CIBlockElement::SetPropertyValuesEx($newID, false, $arWork["PROPERTIES"]);
            }else{
                throw new \Exception($obElement->LAST_ERROR);
            }

        }

        foreach ($arDeleteID as $id){
            \CIBlockElement::Delete($id);
        }

        return true;
    }

    /**
     * Получение ID актуальной версии анкеты исполнителя для пользователя
     *
     * @param int $userID
     *
     * @return int|bool
     */
    public function getContractorID($userID) {
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $rsContractors = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($arContractor = $rsContractors->Fetch()) {

            return $arContractor["ID"];
        } else {

            return false;
        }
    }

    /**
     * Получение ID черновика анкеты исполнителя для пользователя, если он существует
     *
     * @param int $userID
     *
     * @return int|bool
     */
    public function getDraftID($userID) {
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
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
     * Получение списка услуг исполнителя, сгруппированного по категориям услуг
     *
     * @param int $contractorID
     *
     * @return array
     */
    public function getGroupedServices($contractorID = null)
    {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_CONTRACTOR_TO_SERVICE]);
        $arGroupedServices = [];

        $rsData = $this->arHLDataClasses["SERVICES"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_CONTRACTOR" => $contractorID],
                "order" => ["ID" => "DESC"],
            ]
        );
        $arContractorServices = [];
        while ($arItem = $rsData->Fetch()) {
            $arContractorServices[$arItem["UF_SERVICE"]] = [
                "ID" => $arItem["UF_SERVICE"],
                "PRICE" => $arItem["UF_PRICE"],
                "DESCRIPTION" => $arItem["UF_DESCRIPTION"],
            ];
        }

        if ($arContractorServices) {
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_SERVICES,
                'ACTIVE' => 'Y',
                'ID' => array_column($arContractorServices, "ID"),
            ];
            $arSelect = [
                'ID',
                'NAME',
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
            ];
            $dbResult = \CIBlockElement::GetList(["NAME" => "ASC"], $arFilter, false, false, $arSelect);
            $arServices = [];
            while ($arService = $dbResult->Fetch()) {
                $arServices[$arService["ID"]] = $arService;
            }

            if ($arServices) {
                $arOrder = [
                    'SORT' => 'ASC',
                ];
                $arFilter = [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => IBLOCK_SERVICES,
                    'ID' => array_column($arServices, 'IBLOCK_SECTION_ID'),
                ];
                $arSelect = [
                    'ID',
                    'NAME',
                ];
                $dbResult = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect, ['nPageSize' => 3]);
                $arSections = [];
                while($arSection = $dbResult->GetNext()) {
                    $arSections[$arSection["ID"]] = $arSection;
                }

                $arInitialOrder = array_keys($arContractorServices);
                $arSortedSections = [];
                foreach ($arInitialOrder as $serviceID) {
                    $arService = array_merge($arServices[$serviceID], $arContractorServices[$arServices[$serviceID]["ID"]]);

                    $arSortedSections[$arService["IBLOCK_SECTION_ID"]]["SERVICES"][] = $arService;
                }

                foreach ($arSortedSections as $key => &$arSortedSection) {
                    $arSortedSection = array_merge($arSortedSection, $arSections[$key]);
                }

                $arGroupedServices = $arSortedSections;

                foreach ($arGroupedServices as &$arServiceGroup) {
                    $arNames = array_column($arServiceGroup["SERVICES"], 'NAME');
                    array_multisort($arNames, SORT_ASC, $arServiceGroup["SERVICES"]);
                }
            }
        }

        return $arGroupedServices;
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

    public function getOrdersCount($userID = null){

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_ORDERS,
            'ACTIVE' => 'Y',
            'PROPERTY_USER_CONTRACTOR' => $userID,
        ];

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        $ordersCount = $dbResult->SelectedRowsCount();

        return $ordersCount;

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

    public function getViewsCount($userID = null, $obStartDate = null, $obEndDate = null){



    }

    public function getViewsChange($userID = null) {

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $viewsLastMonth = $this->getViewsCount($userID, (new \DateTime())->modify('-1 month'));
        $viewsMonthBefore = $this->getViewsCount($userID, (new \DateTime())->modify('-2 month'), (new \DateTime())->modify('-1 month'));

        $viewsChanges = [
            "LAST_MONTH" => $viewsLastMonth,
            "MONTH_CHANGE" => $viewsLastMonth - $viewsMonthBefore,
        ];

        return $viewsChanges;

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
     * Сохранение рейтинга в пользователе исполнителя
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


    /**
     * Поиск исполнителя по хэшу телефона
     *
     * @param $hash - md5( телефон + uid партнера )
     * @param $partnerID
     *
     * @return bool
     */
    public function findByHash($hash,$partnerID){

        $obUser = new User;
        $whoIsPartner = $obUser->getType($partnerID);
        $contractorID = false;
        $uid = false;
        $arPartner = [];
        if($whoIsPartner == "distributor"){
            $obDistr = new Distributor();
            $arPartner = $obDistr->getActualDistributor($partnerID,["PROPERTY_UID"]);
            $uid = $arPartner["PROPERTY_UID_VALUE"];
        }elseif($whoIsPartner == "vendor"){
            $obVendor = new Vendor();
            $arPartner = $obVendor->getActualVendor($partnerID,["PROPERTY_UID"]);
            $uid = $arPartner["PROPERTY_UID_VALUE"];
        }

        $arSelect = ["ID", "IBLOCK_ID", "NAME","PROPERTY_PHONE"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "!PROPERTY_PHONE" => false
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($arResult = $dbResult->Fetch()) {
            if($arResult["PROPERTIES_PHONE_VALUE"]){
                $checkHash = md5($arResult["PROPERTIES_PHONE_VALUE"].$uid);
            }
            return $arResult;
        }

        return $contractorID;

    }


    /**
     * Собираем очередь исполнителей для новой заявки
     * @$city_fias - город пользователя
     * @return array
     */
    public function getQueue($city_fias){
        $arContractors = [];
        $arSelect = ["ID", "IBLOCK_ID", "NAME","PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "PROPERTY_LOCATIONS" => $city_fias
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($arResult = $dbResult->Fetch()) {
            $arContractors[] = $arResult["PROPERTY_USER_VALUE"];
        }


        //todo: проверить правильность сборки очереди
        $arQueue = [];
        $arFilter = [
            "ID" => $arContractors,
            "GROUPS_ID" => [USER_GROUP_CONTRACTORS],
            "UF_FREE" => 1,
            "UF_SERTIFIED" => 1,
            "UF_LOCATION" => $city_fias,
            //">UF_CONTRACTOR_RATING" => 0
        ];
        $rsUsers = \CUser::GetList($by="UF_CONTRACTOR_RATING", $order="DESC", $arFilter,["SELECT"=>["UF_CONTRACTOR_RATING"]]);
        while($arUser = $rsUsers->fetch()) {
            $arQueue[] = $arUser['ID'];
        }
        $arFilter = [
            "ID" => $arContractors,
            "GROUPS_ID" => [USER_GROUP_CONTRACTORS],
            "UF_FREE" => 1,
            "UF_SERTIFIED" => 0,
            "UF_LOCATION" => $city_fias,
            //">UF_CONTRACTOR_RATING" => 0
        ];
        $rsUsers = \CUser::GetList($by="UF_CONTRACTOR_RATING", $order="DESC", $arFilter,["SELECT"=>["UF_CONTRACTOR_RATING"]]);
        while($arUser = $rsUsers->fetch()) {
            $arQueue[] = $arUser['ID'];
        }

        return $arQueue;
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

    /**
     * Метод вызвается после регистрации исполнителя для проверки наличия его у дистрибьютора
     */
    public function checkUserFromDistributor($phone = false, $userID = false){
        if(!$phone) return;
        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/_test/checkUserFromDistributor_log.txt");
        AddMessage2Log("phone: ".$phone, "checkUser");
        $arIntPartners = DataHelper::getAllIntegrationPartners();
        $parsedPhone = Parser::getInstance()->parse($phone);
        $phone = $parsedPhone->format(Format::E164); //+79101234567
        $phone = str_replace("+","",$phone);

        $rest = new Request();
        $requestSoap = new RequestSoap();
        $obDistr = new Distributor();
        foreach ($arIntPartners as $arIntPartner) {
            if (!$arIntPartner["URL"]) continue;
            AddMessage2Log("Дистр: ".$arIntPartner["NAME"], "checkUser");

            $UID_DIST = $arIntPartner["UID"];
            $distUserId = $obDistr->getUserIdByUID($UID_DIST);
            $phone_new = md5($phone.$UID_DIST);
            $arData = [
                "action" => "user.check",
                "phone" => $phone_new,
            ];
            if($arIntPartner["TYPE"] == CONNECTION_TYPE_CURL){
                $rest->setHost($arIntPartner["URL"]);
                $rest->execute($arData);
                $arResult = $rest->getResult();
                $status = $rest->getStatusCode();
            }
            if($arIntPartner["TYPE"] == CONNECTION_TYPE_SOAP){

                $requestSoap->setHost($arIntPartner["URL"]);
                $requestSoap->execute($arData);
                $arResult = $requestSoap->getResult();
                $status = "200";
            }

            if($arResult["exist"]){
                if(intval($arResult["points"]) > 0){
                    $status = false;
                    $arFilter = [
                        'UF_CONTACTOR_USER' => $userID,
                        'UF_PARTNER_ID' => $distUserId
                    ];

                    $arHLDataClasses = User::getHLDataClasses([HLBLOCK_CONTRACTOR_POINTS,HLBLOCK_CONTRACTOR_POINTS_HISTORY]);

                    $rsData = $arHLDataClasses["CONTRACTOR_POINTS"]::getList(array(
                        'select' => ['*'],
                        'filter' => $arFilter,
                        'order' => ["ID" => "ASC"],
                    ));
                    if($el = $rsData->fetch()){
                        $diffPoints = $arResult["points"] - $el["UF_POINTS"];
                        $status = $arHLDataClasses["CONTRACTOR_POINTS"]::update($el["ID"],["UF_POINTS" => $arResult["points"]]);

                    }else{
                        $diffPoints = $arResult["points"];
                        $status = $arHLDataClasses["CONTRACTOR_POINTS"]::add(
                            [
                                'UF_CONTACTOR_USER' => $userID,
                                'UF_PARTNER_ID' => $distUserId,
                                "UF_POINTS" => $arResult["points"]
                            ]
                        );
                    }
                    if($status->isSuccess()){
                        if($diffPoints !== 0){
                            $arHLDataClasses["CONTRACTOR_POINTS_HISTORY"]::add(
                                [
                                    "UF_USER_ID" => $userID,
                                    "UF_PARTNER_ID" => $distUserId,
                                    "UF_POINTS" => $diffPoints,
                                    "UF_DATE" => date("d.m.Y")
                                ]
                            );
                        }

                    }
                }
            }


        }
    }
}
