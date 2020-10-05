<?php

namespace Electric;

use Electric\Component\PopularServices;
use Electric\Helpers\DataHelper;
use Electric\Location;

/**
 * Class Sales
 * Класс, содержащий методы для работы с обучением
 *
 * @package Electric
 */
class Sales
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
     * Получение городов
     *
     * @return array
     */
    public function getSalesCities() {

        return false;

        $arCities = Location::getAvailableCitiesSales();
        return $arCities;
    }

    /**
     * Работаем с галочкой "В архиве"
     *
     * @return array
     */
    public function updateArchive($saleID,$isArchive) {
        if(!$saleID){
            return false;
        }
        $value = "";
        if($isArchive == "Y")
            $value = ENUM_VALUE_SALES_ARCHIVE;

        \CIBlockElement::SetPropertyValuesEx($saleID, false, ["IS_ARCHIVE" => $value]);

        return true;
    }

    /** Получаем общее количество мероприятий */
    public function getTotalCount(){
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_SALES,
            'ACTIVE' => 'Y',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, ["ID", "IBLOCK_ID"]);
        return $dbResult->SelectedRowsCount();
    }

    /**
     * @param null $saleID
     * @param array $arFields
     * @param array $arProperties
     * @return bool|null
     * @throws \Exception
     */
    public function updateSale($saleID = null, $arFields = [], $arProperties = []) {

        if (!$saleID) {
            return false;
        }

        $obElement = new \CIBlockElement;
        // Обновление полей
        if (!$obElement->Update($saleID, $arFields)) {
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

            \CIBlockElement::SetPropertyValuesEx($saleID, IBLOCK_SALES, $arProperties);
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

        $resultID = $saleID;

        return $resultID;
    }
}
