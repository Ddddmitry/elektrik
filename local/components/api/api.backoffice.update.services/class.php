<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;
use \Electric\Contractor;
use \Electric\Location;

class ApiBackofficeUpdateServices extends AjaxComponent
{

    private $input;

    protected function parseRequest()
    {
        $this->input = json_decode($this->request->getInput(), true);
    }

    protected function updateServices()
    {
        $arFields = [];
        $arProperties = [];
        $arHLReferences = [];

        if (isset($this->input["services"])) {
            $arHLReferences["SERVICES"] = [];

            $arServices = [];
            foreach ($this->input["services"] as $arSection) {
                foreach ($arSection["items"] as $index => $arItem) {
                    if (!$arItem["delete"]) $arServices[$arItem["id"]] = $arItem;
                }
            }

            foreach ($arServices as $index => $arServiceData) {
                $arService = [
                    "UF_SERVICE" => $arServiceData["id"],
                    "UF_PRICE" => $arServiceData["price"],
                    "UF_DESCRIPTION" => "",
                ];
                $arHLReferences["SERVICES"][] = $arService;
            }
        }

        if (isset($this->input["places"])) {

            $arProperties["LOCATIONS"] = array_column($this->input["places"], "id");

            $arHLDataClasses = Location::getHLDataClasses([HLBLOCK_LOCATIONS]);

            foreach ($this->input["places"] as $arLocation) {

                // Если локации ещё нет в кэш-таблице локаций - занести её туда
                if (!Location::getCachedLocationByID($arLocation["id"])) {

                    if (strpos($arLocation["name"], "г ") === 0) {
                        $code = substr($arLocation["name"], 2);
                        $code = \CUtil::translit($code, "ru", ["replace_space" => "-","replace_other" => "-"]);
                    } else {
                        $code = \CUtil::translit($arLocation["name"], "ru", ["replace_space" => "-","replace_other" => "-"]);
                    }

                    $arHLDataClasses["LOCATIONS"]::add(
                        [
                            "UF_FIAS_ID" => $arLocation["id"],
                            "UF_NAME" => $arLocation["name"],
                            "UF_LEVEL" => $arLocation["level"],
                            "UF_CODE" => $code,
                        ]
                    );
                }

                if ($arLocation["level"] == 7) {
                    $arProperties["LOCATIONS_STREETS"][] = $arLocation["id"];
                } elseif (in_array($arLocation["level"], array(4, 1))) {
                    $arProperties["LOCATIONS_CITIES"][] = $arLocation["id"];
                }
            }

            // Добавление городов по улицам
            $arAdditionalCities = [];
            foreach ($arProperties["LOCATIONS_STREETS"] as $streetID) {
                $arStreetData = Location::getLocationDataByID($streetID);
                if (!in_array($arStreetData["data"]["city_fias_id"], $arProperties["LOCATIONS_CITIES"])) {
                    $arAdditionalCities[] = $arStreetData["data"]["city_fias_id"];
                }
            }

            $arProperties["LOCATIONS"] = array_merge($arProperties["LOCATIONS"], $arAdditionalCities);
            $arProperties["LOCATIONS_CITIES"] = array_merge($arProperties["LOCATIONS_CITIES"], $arAdditionalCities);
            $arFields["DETAIL_TEXT"] = implode(" || ", $arProperties["LOCATIONS_CITIES"]);

            if (!$arProperties["LOCATIONS_STREETS"]) $arProperties["LOCATIONS_STREETS"] = false;
        }


        if (isset($this->input["typePlace"])) {
            foreach ($this->input["typePlace"] as $key => $arRoom) {
                if ($arRoom["checked"]) $arProperties["ROOM"][] = $arRoom["id"];
            };
        }

        $obContractor = new Contractor();
        try {
            $obContractor->updateContractor(null, $arFields, $arProperties, $arHLReferences);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }
        
        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->updateServices();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
