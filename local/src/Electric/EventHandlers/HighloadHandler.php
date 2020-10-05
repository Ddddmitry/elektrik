<?php

namespace Electric\EventHandlers;

use Bitrix\Highloadblock\HighloadBlockTable;

class HighloadHandler
{

    /**
     * Обработчики события после добавления элементов хайлоадблока Услуги
     *
     * @param $arFields
     *
     * @return bool
     */
    function ContractorToServiceOnAfterAddHandler(\Bitrix\Main\Entity\Event $event){

        // получаем массив полей хайлоад блока
        $arFields = $event->getParameter("fields");
        if($arFields["UF_SERVICE"]){
            \CModule::IncludeModule("iblock");
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_SERVICES,
                'ID' => $arFields["UF_SERVICE"],
            ];
            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_COUNT_CONTRACTORS',
            ];
            $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            if ($arService = $dbResult->Fetch()) {
                $newCount = $arService["PROPERTY_COUNT_CONTRACTORS_VALUE"] + 1;
                \CIBlockElement::SetPropertyValuesEx($arService["ID"], false, ["COUNT_CONTRACTORS" => $newCount]);
            };
        }
    }

    /**
     * Обработчики события после удаления элементов хайлоадблока Услуги
     *
     * @param $arFields
     *
     * @return bool
     */
    function ContractorToServiceOnBeforeDeleteHandler(\Bitrix\Main\Entity\Event $event){

        $id = $event->getParameter("id");

        \CModule::IncludeModule("highloadblock");
        $arDataClasses = [];
        $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CONTRACTOR_TO_SERVICE)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
        $arDataClasses["SERVICES"] = $obEntity->getDataClass();
        $arFields = $arDataClasses["SERVICES"]::getList(
            [
                "select" => ["*"],
                "filter" => ["ID" => $id],
                "order" => [],
            ]
        )->fetch();

        if($arFields["UF_SERVICE"]){
            \CModule::IncludeModule("iblock");
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_SERVICES,
                'ID' => $arFields["UF_SERVICE"],
            ];
            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_COUNT_CONTRACTORS',
            ];
            $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            if ($arService = $dbResult->Fetch()) {
                $newCount = $arService["PROPERTY_COUNT_CONTRACTORS_VALUE"] - 1;
                \CIBlockElement::SetPropertyValuesEx($arService["ID"], false, ["COUNT_CONTRACTORS" => $newCount]);
            };
        }
    }



}
