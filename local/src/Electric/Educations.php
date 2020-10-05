<?php

namespace Electric;

use Electric\Component\PopularServices;
use Electric\Helpers\DataHelper;
use Electric\Location;

/**
 * Class Educations
 * Класс, содержащий методы для работы с обучением
 *
 * @package Electric
 */
class Educations
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
     * Получение типов обучения
     *
     * @param array $arTypesID - массив ID для получение только определённого набора типов обучений
     * @param bool $withoutProposed - исключать из результата тип "Предложенные статьи"
     *
     * @return array
     */
    public function getEducationsTypes($arTypesID = [], $withoutProposed = true) {
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EDUCATIONS_TYPES,
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
     * Получение тем обучения
     *
     * @param array $arThemesID - массив ID для получение только определённого набора типов обучений
     *
     * @return array
     */
    public function getEducationsThemes($arThemesID = []) {
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EDUCATIONS_THEMES,
        ];
        if ($arThemesID) {
            $arFilter["ID"] = array_unique($arThemesID);
        }
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arThemes = [];
        while ($arTheme = $dbResult->GetNext()) {
            $arThemes[$arTheme["ID"]] = $arTheme["NAME"];
        }
        return $arThemes;
    }

    /**
     * Получение тем обучения
     *
     * @param array $arThemesID - массив ID для получение только определённого набора типов обучений
     *
     * @return array
     */
    public function getEducationsVendors($arVendorsID = []) {
        $arSelect = ['ID','NAME','CODE',];
        $arFilter = ['IBLOCK_ID' => IBLOCK_VENDORS,];
        if ($arVendorsID) {
            $arFilter["ID"] = array_unique($arVendorsID);
        }
        $arVendors = [];
        /*$dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arVendor = $dbResult->GetNext()) {
            $arVendors[$arVendor["ID"]] = $arVendor["NAME"];
        }*/
        $arFilter = ['IBLOCK_ID' => IBLOCK_DISTRIBUTORS];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arVendor = $dbResult->GetNext()) {
            $arVendors[$arVendor["ID"]] = $arVendor["NAME"];
        }
        return $arVendors;
    }

    /**
     * Получение форматов обучения
     *
     * @return array
     */
    public function getEducationsFormats(){
        $arFormats = [];
        $db_enum_list = \CIBlockProperty::GetPropertyEnum("FORMAT", Array(), Array("IBLOCK_ID"=>IBLOCK_EDUCATIONS));
        while($ar_enum_list = $db_enum_list->GetNext())
        {
            $arFormats[$ar_enum_list["ID"]] = $ar_enum_list["VALUE"];
        }
        return $arFormats;
    }

    /**
     * Получение списка тегов обучения
     *
     * @return array
     */
    public function getEducationsTags() {
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_TAGS',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EDUCATIONS,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arTags = [];
        while ($arEvent = $dbResult->GetNext()) {
            if (!in_array($arEvent["PROPERTY_TAGS_VALUE"], $arTags)) {
                $arTags[] = $arEvent["PROPERTY_TAGS_VALUE"];
            }
        }

        return $arTags;
    }

    /**
     * Получение годов обучений
     *
     * @return array
     */
    public function getEducationsYears() {
        $arSelect = [
            'ID',
            'NAME',
            'ACTIVE_FROM',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EDUCATIONS,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arYears = [];
        while ($arEvent = $dbResult->GetNext()) {
            $arYears[] = DataHelper::getFormattedDateSingle($arEvent["ACTIVE_FROM"],"Y");
        }
        sort($arYears);
        return array_unique($arYears);
    }

    /**
     * Получение месяцев обучений
     *
     * @return array
     */
    public function getEducationsMonths() {
        $arSelect = [
            'ID',
            'NAME',
            'ACTIVE_FROM',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EDUCATIONS,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arMonths = [];
        while ($arEvent = $dbResult->GetNext()) {
            $i = DataHelper::getFormattedDateSingle($arEvent["ACTIVE_FROM"],"n")-1;
            $arMonths[$i] = DataHelper::getMonth($i);
        }
        ksort($arMonths);
        return array_unique($arMonths);
    }

    /**
     * Получение городов обучений
     *
     * @return array
     */
    public function getEducationsCities() {

        $arCities = Location::getAvailableCitiesEducations();

        return $arCities;
    }

    /**
     * Работаем с галочкой "В архиве"
     *
     * @return array
     */
    public function updateArchive($educationID,$isArchive) {
        if(!$educationID){
            return false;
        }
        $value = "";
        if($isArchive == "Y")
            $value = ENUM_VALUE_EDUCATIONS_ARCHIVE;

        \CIBlockElement::SetPropertyValuesEx($educationID, false, ["IS_ARCHIVE" => $value]);

        return true;
    }

    /** Получаем общее количество мероприятий */
    public function getTotalCount(){
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EDUCATIONS,
            'ACTIVE' => 'Y',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        return $dbResult->SelectedRowsCount();
    }

    /**
     * @param null $educationID
     * @param array $arFields
     * @param array $arProperties
     * @return bool|null
     * @throws \Exception
     */
    public function updateEducation($educationID = null, $arFields = [], $arProperties = []) {

        if (!$educationID) {
            return false;
        }

        $obElement = new \CIBlockElement;
        // Обновление полей
        if (!$obElement->Update($educationID, $arFields)) {
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

            \CIBlockElement::SetPropertyValuesEx($educationID, IBLOCK_EDUCATIONS, $arProperties);
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
            "LINK" => "bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".IBLOCK_DISTRIBUTORS."&type=users&ID=".$distID,
        ];
        Notification::sendNotification("ELECTRIC_CONTRACTOR_CHANGES", $arMailData);*/

        $resultID = $educationID;



        return $resultID;
    }
}
