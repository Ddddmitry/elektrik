<?php

namespace Electric;

use Electric\Component\PopularServices;
use Electric\Helpers\DataHelper;
use Electric\Location;

/**
 * Class Events
 * Класс, содержащий методы для работы с мероприятиями
 *
 * @package Electric
 */
class Events
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
     * Получение типов мероприятий
     *
     * @param array $arTypesID - массив ID для получение только определённого набора типов статей
     * @param bool $withoutProposed - исключать из результата тип "Предложенные статьи"
     *
     * @return array
     */
    public function getEventsTypes($arTypesID = [], $withoutProposed = true) {
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EVENTS_TYPES,
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
     * Получение списка тегов мероприятий
     *
     * @return array
     */
    public function getEventsTags() {
        $arSelect = [
            'ID',
            'NAME',
            'PROPERTY_TAGS',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EVENTS,
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
     * Получение годов мероприятий
     *
     * @return array
     */
    public function getEventsYears() {
        $arSelect = [
            'ID',
            'NAME',
            'ACTIVE_FROM',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EVENTS,
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
     * Получение месяцев мероприятий
     *
     * @return array
     */
    public function getEventsMonths() {
        $arSelect = [
            'ID',
            'NAME',
            'ACTIVE_FROM',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_EVENTS,
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
     * Получение городов мероприятий
     *
     * @return array
     */
    public function getEventsCities() {

        $arCities = Location::getAvailableCitiesEvents();

        return $arCities;
    }

    /**
     * Работаем с галочкой "В архиве"
     *
     * @return array
     */
    public function updateArchive($eventID,$isArchive) {


        if(!$eventID){
            return false;
        }
        $value = "";
        if($isArchive == "Y")
            $value = ENUM_VALUE_EVENTS_ARCHIVE;

        \CIBlockElement::SetPropertyValuesEx($eventID, false, ["IS_ARCHIVE" => $value]);

        return true;
    }

    /** Получаем общее количество мероприятий */
    public function getTotalCount(){
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EVENTS,
            'ACTIVE' => 'Y',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        return $dbResult->SelectedRowsCount();
    }

    public function updateEvent($eventID = null, $arFields = [], $arProperties = []) {

        if (!$eventID) {
                return false;
        }

        $obElement = new \CIBlockElement;
        // Обновление полей
        if (!$obElement->Update($eventID, $arFields)) {
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

            \CIBlockElement::SetPropertyValuesEx($eventID, IBLOCK_EVENTS, $arProperties);
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

        $resultID = $eventID;



        return $resultID;
    }

    /**
     * АГЕНТ!!!!
     * Метод запускается по крону, для деактивации прошедших мероприятий
     *
     * @return bool
     */
     public static function deactivatePastEvents(){
         \CModule::IncludeModule('iblock');
        $curDate = date("d.m.Y H:i:s");
        $obElement = new \CIBlockElement;
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_EVENTS,
            'ACTIVE' => 'Y',
        ];

        $dbResult = \CIBlockElement::GetList(["ACTIVE_FROM" => "ASC"], $arFilter, false, false, ["ID", "IBLOCK_ID","ACTIVE_FROM"]);
        while ($arEvent = $dbResult->GetNext()) {
            if($arEvent["ACTIVE_FROM"] < $curDate){
                $obElement->Update($arEvent["ID"], ["ACTIVE"=>"N"]);
            }
        }
        
        return "\Electric\Events::deactivatePastEvents();";

    }


}
