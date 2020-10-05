<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Contractor;
use Electric\Helpers\DataHelper;
use \Electric\Location;

class ApiBackofficeWorksheet extends AjaxComponent
{
    protected function parseRequest() { }

    protected function getWorksheet()
    {
        \Bitrix\Main\Loader::IncludeModule("iblock");
        \Bitrix\Main\Loader::IncludeModule("sale");

        global $USER;
        if (!$USER->IsAuthorized()) {
            throw new \Exception('authorization_required');
        }

        $obContractor = new Contractor();
        $arContractor = $obContractor->getContractorData(null, true);

        $type = ($arContractor["PROPERTIES"]["TYPE"]["VALUE"] == ENUM_VALUE_CONTRACTORS_TYPE_LEGAL) ? "legal" : "individual";

        $arWorksheetData = [
            "type" => $type,
            "name" => $arContractor["NAME"],
            "adress" => $arContractor["PROPERTIES"]["ADDRESS"]["VALUE"],
            "phone" => $arContractor["PROPERTIES"]["PHONE"]["VALUE"],
            "email" => $arContractor["PROPERTIES"]["EMAIL"]["VALUE"],
            "site" => $arContractor["PROPERTIES"]["SITE"]["VALUE"],
            "img" => [
                "src" => $arContractor["PREVIEW_PICTURE"]["SRC"],
            ],
            "about" => $arContractor["PREVIEW_TEXT"],
            "qualification" => null,
            "services" => [],
            "works" => [],
            "sertificates" => [],
            "experience" => [],
            "languages" => [],
            "education" => [],
            "courses" => [],
            "places" => [],
            "typePlace" => [],
        ];

        foreach ($arContractor["SERVICES"] as $arServicesGroup) {
            $arServices = [];
            foreach ($arServicesGroup["SERVICES"] as $arService) {
                $arServices[$arService["ID"]] = [
                    "name" => $arService["NAME"],
                    "price" => $arService["PRICE"],
                    "delete" => false,
                ];
            }
            $arWorksheetData["services"][] = [
                "name" => $arServicesGroup["NAME"],
                "items" => $arServices,
            ];
        }

        foreach ($arContractor["PROPERTIES"]["EXAMPLES"] as $arExample) {
            $arDescription = explode("||", $arExample["FULL"]["DESCRIPTION"]);
            $arWorksheetData["works"][] = [
                "preview_img" => $arExample["PREVIEW"]["src"],
                "detail_img" => $arExample["FULL"]["SRC"],
                "title" => $arDescription[0],
                "description" => $arDescription[1],
            ];
        }

        foreach ($arContractor["PROPERTIES"]["DOCUMENTS"] as $arDocument) {
            $arWorksheetData["sertificates"][] = [
                "preview_img" => $arDocument["SRC"],
                "detail_img" => $arDocument["SRC"],
                "title" => $arDocument["DESCRIPTION"],
                "date" => $arDocument["DATE"],
            ];
        }

        if ($arContractor["PROPERTIES"]["SKILL"]["VALUE"]) {
            $arWorksheetData["qualification"]["ID"] = $arContractor["PROPERTIES"]["SKILL"]["VALUE"];
            if ($skillName = $obContractor->getSkillName($arContractor["PROPERTIES"]["SKILL"]["VALUE"])) {
                $arWorksheetData["qualification"]["NAME"] = $skillName;
            }
        }

        $arJobs = $obContractor->getJobs($arContractor["ID"]);

        foreach ($arJobs as $arJob) {
            $arWorksheetData["experience"][] = [
                "monthStart" => $arJob["START_DATE"] ? DataHelper::getMonth((int)$arJob["START_DATE"]->format("m") - 1) : "",
                "monthStartNumber" => $arJob["START_DATE"] ? (int)($arJob["START_DATE"]->format("m")) - 1 : "",
                "yearStart" => $arJob["START_DATE"] ? $arJob["START_DATE"]->format("Y") : "",
                "monthEnd" => $arJob["END_DATE"] ? DataHelper::getMonth((int)$arJob["END_DATE"]->format("m") - 1) : "",
                "monthEndNumber" => $arJob["END_DATE"] ? (int)($arJob["END_DATE"]->format("m")) - 1 : "",
                "yearEnd" => $arJob["END_DATE"] ? $arJob["END_DATE"]->format("Y") : "",
                "currentPlace" => (int)$arJob["IS_NOW"] ? true : false,
                "place" => $arJob["NAME"],
            ];
        }

        $arLanguages = $obContractor->getLanguages($arContractor["ID"]);
        foreach ($arLanguages as $arLanguage) {
            $arWorksheetData["languages"][] = [
                "language" => [
                    "ID" =>$arLanguage["LANGUAGE"]["ID"] ? (int)$arLanguage["LANGUAGE"]["ID"] : "",
                    "NAME" => $arLanguage["LANGUAGE"]["NAME"] ? $arLanguage["LANGUAGE"]["NAME"] : "",
                ],
                "skill" => [
                    "ID" => $arLanguage["LEVEL"]["ID"] ? (int)$arLanguage["LEVEL"]["ID"] : "",
                    "NAME" => $arLanguage["LEVEL"]["NAME"] ? $arLanguage["LEVEL"]["NAME"] : "",
                ],
            ];
        }

        $arEducations = $obContractor->getEductaions($arContractor["ID"]);
        foreach ($arEducations as $arEducation) {
            $arWorksheetData["education"][] = [
                "place" => $arEducation["NAME"],
                "type" => $arEducation["STATUS"],
            ];
        }

        $arCourses = $obContractor->getCourses($arContractor["ID"]);
        foreach ($arCourses as $arCourse) {
            $arWorksheetData["courses"][] = [
                "name" => $arCourse["NAME"],
            ];
        }

        if ($arContractor["PROPERTIES"]["LOCATIONS"]) {
            foreach ($arContractor["PROPERTIES"]["LOCATIONS"] as $arLocation) {

                // Получение данных локации из кэш-таблицы. Если такой записи нет - запросом к API Dadata
                if ($arCachedLocation = Location::getCachedLocationByID($arLocation["VALUE"])) {
                    $arWorksheetData["places"][] = [
                        "id" => $arCachedLocation["UF_FIAS_ID"],
                        "name" => $arCachedLocation["UF_NAME"],
                        "level" => $arCachedLocation["UF_LEVEL"],
                    ];
                } else {
                    $arLocationData = Location::getLocationDataByID($arLocation["VALUE"]);
                    $arWorksheetData["places"][] = [
                        "id" => $arLocation["VALUE"],
                        "name" => $arLocationData["DISPLAY_NAME"],
                        "level" => $arLocationData["data"]["fias_level"],
                    ];
                }

            }
        }

        if ($arContractor["PROPERTIES"]["ROOM"]) {
            $arSelectedRooms = array_column($arContractor["PROPERTIES"]["ROOM"], "VALUE");
            $obPropertyEnum = \CIBlockPropertyEnum::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => IBLOCK_CONTRACTORS, "CODE" => "ROOM"]);
            while ($arPropertyEnum = $obPropertyEnum->Fetch()) {
                $arWorksheetData["typePlace"][$arPropertyEnum["ID"]] = [
                    "name" => $arPropertyEnum["VALUE"],
                    "checked" => in_array($arPropertyEnum["ID"], $arSelectedRooms),
                ];
            }
        }


        return $arWorksheetData;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        try {
            $result = $this->getWorksheet();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
