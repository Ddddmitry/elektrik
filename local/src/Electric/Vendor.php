<?php

namespace Electric;

use Bitrix\Highloadblock\HighloadBlockTable;
use \Bitrix\Main\Type\Date;
use Electric\Notification;
use Electric\Helpers\DataHelper;

/**
 * Class Vendor
 *
 * Класс, содержащий методы для работы с вендорами
 *
 * @package Electric
 */
class Vendor
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

    public function getActualVendor($userID, $arSelect = []) {

        $arSelectDefault = ["ID", "IBLOCK_ID", "NAME"];
        $arSelect = array_unique(array_merge($arSelectDefault, $arSelect));
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_VENDORS,
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

    public function getActualVendorCode($userID) {
        $arSelect = ["ID", "IBLOCK_ID", "CODE"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_VENDORS,
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
     * Получение данных об вендоре
     *
     * @param int $arFindBy
     * @param bool $showDraft
     *
     * @return array|bool
     */
    public function getVendorData($arFindBy = null, $showDraft = false) {
        if (!$arFindBy) {
            if ($this->currentUserID) {
                $arFindBy = ["PROPERTY_USER" => $this->currentUserID];
            } else {
                return false;
            }
        }

        $arOrder = [];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_VENDORS,
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

        if ($arVendor = $dbResult->Fetch()) {

            // Получение изображения
            if ($arVendor['PREVIEW_PICTURE']) {
                $arVendor['PREVIEW_PICTURE'] = \CFile::GetFileArray($arVendor['PREVIEW_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arVendor['IBLOCK_ID'],
                $arVendor['ID'],
                ["value_id" => "asc"]
            );
            while ($arProperty = $dbProperty->Fetch()) {
                switch ($arProperty["CODE"]) {
                    case "DOCUMENTS":
                        if ($arFile = \CFile::GetFileArray($arProperty["VALUE"])) {
                            $arDescription = explode("||", $arFile["DESCRIPTION"]);
                            $arFile["DESCRIPTION"] = $arDescription[0];
                            $arFile["DATE"] = $arDescription[1];
                            $arFile["DATE_DISPLAY"] = (new Date($arDescription[1], "Y-m-d"))->toString();
                            $arVendor["PROPERTIES"]["DOCUMENTS"][] = $arFile;
                        }
                        break;
                    case "EXAMPLES":
                        if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                            $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                            $arVendor["PROPERTIES"]["EXAMPLES"][] = [
                                "FULL" => $arPicture,
                                "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 365, 'height' => 900], BX_RESIZE_IMAGE_PROPORTIONAL, true),
                                "TITLE" => $arDescription[0],
                                "DESCRIPTION" => $arDescription[1],
                            ];
                        }
                        break;
                    case "ROOM":
                        $arVendor['PROPERTIES'][$arProperty["CODE"]][] = [
                            "VALUE" => $arProperty["VALUE"],
                            "VALUE_ENUM" => $arProperty["VALUE_ENUM"],
                        ];
                        break;
                    case "ADDRESS_COORDS":
                        $arCoords = explode(" ", $arProperty["VALUE"]);
                        $arVendor['PROPERTIES'][$arProperty["CODE"]]["VALUE"] = implode(", ", array_reverse($arCoords));
                        break;
                    default:
                        if ($arProperty["MULTIPLE"] === "N") {
                            $arVendor['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                        } else {
                            if ($arProperty["VALUE"]) {
                                $arVendor['PROPERTIES'][$arProperty["CODE"]][] = $arProperty;
                            }
                        }
                        break;
                }
            }

            // Получение данных из пользователя исполнителя
            $arUserData = $this->getUserData($arVendor["PROPERTIES"]["USER"]["VALUE"]);

            return $arVendor;
        }

        return false;
    }


    /**
     * Получение данных об исполнителе, хранящихся в его пользователе
     *
     * @param $vendorID
     *
     * @return mixed
     */
    public function getUserData($vendorID) {
        $arUser = \CUser::GetByID($vendorID)->Fetch();
        //$arUserData["RATING"] = $arUser["UF_RATING"];
        $arUserData = $arUser;

        return $arUserData;
    }


    /**
     * Получение данных о местах работы исполнителя
     *
     * @param $vendorID
     *
     * @return array
     */
    public function getJobs($vendorID) {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_JOBS]);

        $rsData = $this->arHLDataClasses["JOBS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_CONTRACTOR" => $vendorID],
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
     * Получение данных о мероприятиях вендора
     *
     * @param $vendorID
     *
     * @return array
     */
    public function getEvents() {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'DETAIL_PAGE_URL','PROPERTY_TYPE.ID','PROPERTY_TYPE.NAME','PREVIEW_TEXT','PREVIEW_PICTURE'];
        $arOrder = [
            "ACTIVE_FROM" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EVENTS,
            "PROPERTY_USER" => $this->currentUserID
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arEvent = $dbResult->fetch()) {
            // Получение изображения
            if ($arEvent['PREVIEW_PICTURE']) {
                $arEvent['PREVIEW_PICTURE'] = \CFile::GetFileArray($arEvent['PREVIEW_PICTURE']);
            }
            // Получение даты
            $arEvent["DATE"] = DataHelper::getFormattedDate($arEvent["ACTIVE_FROM"]);
            $arEvent["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arEvent["DETAIL_PAGE_URL"], $arEvent);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arEvent['IBLOCK_ID'],
                $arEvent['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arEvent['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }


            $arResult[] = $arEvent;
        }
        return $arResult;

    }



    /**
     * Получение данных о статьях вендора
     *
     * @param $vendorID
     *
     * @return array
     */
    public function getArticles($userID = null) {

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
     * Получение данных об обучалках вендора
     *
     * @param $vendorID
     *
     * @return array
     */
    public function getEducations() {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'DETAIL_PAGE_URL','PROPERTY_TYPE.ID','PROPERTY_TYPE.NAME','PROPERTY_THEME.NAME','PREVIEW_TEXT','PREVIEW_PICTURE'];
        $arOrder = [
            "ACTIVE_FROM" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EDUCATIONS,
            "PROPERTY_USER" => $this->currentUserID
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arEducation = $dbResult->fetch()) {
            // Получение изображения
            if ($arEducation['PREVIEW_PICTURE']) {
                $arEducation['PREVIEW_PICTURE'] = \CFile::GetFileArray($arEducation['PREVIEW_PICTURE']);
            }
            // Получение даты
            $arEducation["DATE"] = DataHelper::getFormattedDate($arEducation["ACTIVE_FROM"]);
            $arEducation["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arEducation["DETAIL_PAGE_URL"], $arEducation);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arEducation['IBLOCK_ID'],
                $arEducation['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arEducation['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }


            $arResult[] = $arEducation;
        }
        return $arResult;

    }

    /**
     * Получение данных об акциях вендора
     *
     *
     * @return array
     */
    public function getSales() {

        $arResult = [];
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'];
        $arOrder = [
            "ACTIVE_FROM" => "ASC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_SALES,
            "PROPERTY_USER" => $this->currentUserID
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arEducation = $dbResult->fetch()) {
            // Получение изображения
            if ($arEducation['PREVIEW_PICTURE']) {
                $arEducation['PREVIEW_PICTURE'] = \CFile::GetFileArray($arEducation['PREVIEW_PICTURE']);
            }
            // Получение даты
            $arEducation["DATE"] = DataHelper::getFormattedDate($arEducation["ACTIVE_FROM"]);
            $arEducation["DETAIL_PAGE_LINK"] = \CIBlock::ReplaceDetailUrl($arEducation["DETAIL_PAGE_URL"], $arEducation);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arEducation['IBLOCK_ID'],
                $arEducation['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arEducation['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $arResult[] = $arEducation;
        }
        return $arResult;

    }

    /**
     * Получение статистики по вендору
     *
     *
     * @return array
     */
    public function getStatistics(){
        return false;
    }

    /**
     * Получение полного набора полей и свойств для создания копии элемента исполнителя
     *
     * @param int $vendorID
     *
     * @return array|bool
     */
    private function getVendorCopyArray($vendorID) {
        $dbResult = \CIBlockElement::GetByID($vendorID);
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
            $dbProperty = \CIBlockElement::getProperty(IBLOCK_VENDORS, $arFieldsOriginal['ID']);
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

    private function updateHLReferences($vendorID, $arHLReferences) {

        foreach ($arHLReferences as $key => $arHLReferencesItem) {
            $rsData = $this->arHLDataClasses[$key]::getList(
                [
                    "select" => ["ID"],
                    "filter" => ["UF_CONTRACTOR" => $vendorID],
                    "order" => [],
                ]
            );
            $arItems = $rsData->fetchAll();
            foreach ($arItems as $arItem) {
                $this->arHLDataClasses[$key]::delete($arItem["ID"]);
            }
            foreach ($arHLReferencesItem as $arRow) {
                $arRow["UF_CONTRACTOR"] = $vendorID;
                $this->arHLDataClasses[$key]::add($arRow);
            }
        }
    }

    private function copyHLReferences($newVendorID, $originalVendorID, $arIgnore = []) {

        $arHLBlocks = ["JOBS", "LANGUAGES", "SERVICES", "EDUCATIONS", "COURSES"];

        foreach ($arHLBlocks as $hlBlock) {
            if (!in_array($hlBlock, $arIgnore)) {
                $rsData = $this->arHLDataClasses[$hlBlock]::getList(
                    [
                        "select" => ['*'],
                        "filter" => ["UF_CONTRACTOR" => $originalVendorID],
                        "order" => [],
                    ]
                );
                $arItems = $rsData->fetchAll();
                foreach ($arItems as $arItem) {
                    unset($arItem["ID"]);
                    $arItem["UF_CONTRACTOR"] = $newVendorID;
                    $this->arHLDataClasses[$hlBlock]::add($arItem);
                }
            }
        }
    }


    /**
     * Обновление вендора путём создания неактивной копии его элемента, включающей изменённые данные
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
    public function updateVendor($userID = null, $arFields = [], $arProperties = [], $arHLReferences = []) {

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $distID = $this->getVendorID($userID);



        $obElement = new \CIBlockElement;
        // Обновление полей
        if (!$obElement->Update($distID, $arFields)) {
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

            \CIBlockElement::SetPropertyValuesEx($distID, IBLOCK_VENDORS, $arProperties);
        }

        // Удаление временных файлов для свойств
        foreach ($arProperties as $arProperty) {
            if ($tmpName = current($arProperty)["VALUE"]["tmp_name"]) {
                unlink($tmpName);
            }
        }

        // Отправка модератору уведомления об изменении анкеты исполнителя
        /*$arMailData = [
            "USER_ID" => $userID,
            "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_VENDORS."&type=users&ID=".$distID,
        ];
        Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);*/

        $resultID = $distID;



        return $resultID;
    }


    /**
     * Получение ID актуальной версии анкеты исполнителя для пользователя
     *
     * @param int $userID
     *
     * @return int|bool
     */
    public function getVendorID($userID) {
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_VENDORS,
            "ACTIVE" => "Y",
            "PROPERTY_USER" => $userID,
        ];
        $rsVendors = \CIBlockElement::GetList([], $arFilter, false, false, ["ID"]);
        if ($arVendor = $rsVendors->Fetch()) {

            return $arVendor["ID"];
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
            "IBLOCK_ID" => IBLOCK_VENDORS,
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
     * @param int $vendorID
     *
     * @return array
     */
    public function getGroupedServices($vendorID = null)
    {
        $this->arHLDataClasses = self::getHLDataClasses([HLBLOCK_CONTRACTOR_TO_SERVICE]);
        $arGroupedServices = [];

        $rsData = $this->arHLDataClasses["SERVICES"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_CONTRACTOR" => $vendorID],
                "order" => ["ID" => "DESC"],
            ]
        );
        $arVendorServices = [];
        while ($arItem = $rsData->Fetch()) {
            $arVendorServices[$arItem["UF_SERVICE"]] = [
                "ID" => $arItem["UF_SERVICE"],
                "PRICE" => $arItem["UF_PRICE"],
                "DESCRIPTION" => $arItem["UF_DESCRIPTION"],
            ];
        }

        if ($arVendorServices) {
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_SERVICES,
                'ACTIVE' => 'Y',
                'ID' => array_column($arVendorServices, "ID"),
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

                $arInitialOrder = array_keys($arVendorServices);
                $arSortedSections = [];
                foreach ($arInitialOrder as $serviceID) {
                    $arService = array_merge($arServices[$serviceID], $arVendorServices[$arServices[$serviceID]["ID"]]);

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

    public function isMy($elementID){


        $arFilter = [
            //"IBLOCK_ID" => IBLOCK_EVENTS,
            "ACTIVE" => "Y",
            "ID" => $elementID,
            "PROPERTY_USER" => $this->currentUserID,
        ];

        $rsElements = \CIBlockElement::GetList(array(), $arFilter, false, false, ["ID"]);
        if ($arElement = $rsElements->Fetch()) {

            return $arElement["ID"];
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
