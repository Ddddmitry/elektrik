<?php
namespace Electric;

/**
 * Class User
 *
 * Класс, содержащий методы для работы с услугами
 *
 * @package Electric
 */
class Service
{
    /**
     * Получение полного списка услуг, сгрупированного по разделам
     *
     * @return array
     */
    public function getAllServices()
    {
        \CModule::IncludeModule("iblock");

        $arGroupedServices = [];

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_SERVICES,
            "ACTIVE" => "Y",
        ];
        $arSelect = [
            "ID",
            "NAME",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arServices = [];
        while ($arService = $dbResult->Fetch()) {
            $arServices[] = $arService;
        }

        if ($arServices) {
            $arOrder = [
                "SORT" => "ASC",
            ];
            $arFilter = [
                "ACTIVE" => "Y",
                "IBLOCK_ID" => IBLOCK_SERVICES,
                "ID" => array_column($arServices, "IBLOCK_SECTION_ID"),
            ];
            $arSelect = [
                "ID",
                "NAME",
            ];
            $dbResult = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
            $arSections = [];
            while ($arSection = $dbResult->GetNext()) {
                $arSections[$arSection["ID"]] = $arSection;
            }

            foreach ($arServices as $arService) {
                $arSections[$arService["IBLOCK_SECTION_ID"]]["SERVICES"][] = $arService;
            }

            $arGroupedServices = $arSections;
        }

        return $arGroupedServices;
    }


    /**
     * Получение услуги по коду
     *
     * @param string $code
     *
     * @return array
     */
    public function getServiceByCode($code)
    {
        \CModule::IncludeModule("iblock");

        $arFilter = [
            "IBLOCK_ID" => IBLOCK_SERVICES,
            "ACTIVE" => "Y",
            "CODE" => $code,
        ];
        $arSelect = [
            "ID",
            "NAME",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arService = $dbResult->Fetch()) {

            return $arService;
        } else {

            return [];
        }
    }



}
