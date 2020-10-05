<?php
namespace Electric;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Sale\Location\GeoIp;
use Bitrix\Main\Service\GeoIp as MainGeoIP;

/**
 * Class Location
 *
 * Класс, содержащий методы для работы с адресами и геолокацией пользователя
 *
 * @package Electric
 */
class Location
{
    private $currentUserID;

    const POST_HEADERS = [
        "Content-Type" => "application/json",
        "Accept" => "application/json",
        "Authorization" => "Token " . DADATA_API_TOKEN,
    ];

    const GET_HEADERS = [
        "Accept" => "application/json",
        "Authorization" => "Token " . DADATA_API_TOKEN,
    ];

    public function __construct($userID = null)
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->currentUserID = $USER->GetID();
        }
    }


    /**
     * Получение города (местоположения) пользователя по IP
     *
     * @param int $userID
     *
     * @return array
     */
    public function getSuggestedCity()
    {
        \CModule::IncludeModule("sale");

        $userIP = MainGeoIP\Manager::getRealIp();

        $client = new \GuzzleHttp\Client([
            'base_uri' => DADATA_API_ROOT,
        ]);
        $res = $client->request('GET', 'iplocate/address?ip='.$userIP, [
            "headers" => self::GET_HEADERS,
        ]);

        if ($arResponse = json_decode($res->getBody(), true)["location"]) {
            $arResult = [
                "ID" => $arResponse["data"]["fias_id"],
                "NAME" => $arResponse["data"]["city"],
            ];
        } else {
            $arResult = DEFAULT_CITY;
        }

        return $arResult;
    }


    /**
     * Получение названия локации по коду ФИАС
     *
     * @param $code
     *
     * @return string
     */
    public static function getCityNameByID($code) {
        $client = new \GuzzleHttp\Client([
            'base_uri' => DADATA_API_ROOT,
        ]);
        $arRequest = [
            "query" => $code,
        ];
        $res = $client->request('POST', 'findById/address', [
            "headers" => self::POST_HEADERS,
            "json" => $arRequest,
        ]);

        $arResponse = current(json_decode($res->getBody(), true)["suggestions"]);

        if ($arResponse["data"]["fias_level"] == 7) {
            $arResult = $arResponse["data"]["city"] . ", " . $arResponse["data"]["street_with_type"];
        } elseif (in_array($arResponse["data"]["fias_level"], array(4, 1))) {
            $arResult = $arResponse["data"]["city"];
        } else {
            $arResult = $arResponse["fias_id"];
        }

        return $arResult;
    }


    /**
     * Получение данных о локации по коду ФИАС
     *
     * @param $code
     *
     * @return mixed
     */
    public static function getLocationDataByID($code) {
        $client = new \GuzzleHttp\Client([
            'base_uri' => DADATA_API_ROOT,
        ]);
        $arRequest = [
            "query" => $code,
        ];
        $res = $client->request('POST', 'findById/address', [
            "headers" => self::POST_HEADERS,
            "json" => $arRequest,
            "connect_timeout" => 3
        ]);

        $arResponse = current(json_decode($res->getBody(), true)["suggestions"]);

        if ($arResponse["data"]["fias_level"] == 7) {
            $arResponse["DISPLAY_NAME"] = $arResponse["data"]["city"] . ", " . $arResponse["data"]["street_with_type"];
        } elseif (in_array($arResponse["data"]["fias_level"], array(4, 1))) {
            $arResponse["DISPLAY_NAME"] = $arResponse["data"]["city"];
        } else {
            $arResponse["DISPLAY_NAME"] = $arResponse["fias_id"];
        }

        return $arResponse;
    }


    public static function getCachedLocationByID($code) {
        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_LOCATIONS]);
        $rsData = $arHLDataClasses["LOCATIONS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_FIAS_ID" => $code],
                "order" => [],
            ]
        );

        $arCachedLocation = $rsData->Fetch();

        return $arCachedLocation;
    }


    public static function getCachedLocationByCode($code) {
        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_LOCATIONS]);
        $rsData = $arHLDataClasses["LOCATIONS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_CODE" => $code],
                "order" => [],
            ]
        );

        $arCachedLocation = $rsData->Fetch();

        return $arCachedLocation;
    }


    /**
     * Получение списка поисковых подсказок локации по поисковой фразе
     * с использованием API Dadata
     *
     * @param string $searchPhrase
     * @param array $arOptions
     *
     * @return array
     */
    public static function getLocationSuggestions($searchPhrase, $arOptions = []) {

        $client = new \GuzzleHttp\Client([
             'base_uri' => DADATA_API_ROOT,
        ]);
        $arRequest = [
            "query" => $searchPhrase,
            "count" => 10,
        ];
        if ($arOptions["SHOW_ONLY_CITIES"]) {
            $arRequest["from_bound"] = ["value" => "city"];
            $arRequest["to_bound"] = ["value" => "city"];
            $arRequest["locations"][] = ["city_type_full" => "город"];
        }

        if ($arOptions["RESTRICTED"]) {
            $arRequest["locations"][] = ["city_fias_id" => $arOptions["RESTRICTED"]];
        }

        $res = $client->request('POST', 'suggest/address', [
            "headers" => self::POST_HEADERS,
            "json" => $arRequest,
        ]);

        $arResponse = json_decode($res->getBody(), true)["suggestions"];

        $arResult = [];
        foreach ($arResponse as $arSuggestion) {
            $arResult[] = [
                "value" => $arSuggestion["value"],
                "id" => $arSuggestion["data"]["fias_id"],
                "level" => $arSuggestion["data"]["fias_level"],
                "street_with_type" => $arSuggestion["data"]["street_with_type"] ?? $arSuggestion["value"],
                "raw_data" => $arSuggestion
            ];
        }

        return $arResult;
    }


    /**
     * Получение списка кодов ФИАС всех городов, в которых есть действующие исполнители
     *
     * @return array
     */
    public static function getAvailableCitiesID() {
        // @todo при необходимости вернуть функционал учёта наличия сертификата РАЭК
        // как условия отображения в маркетплейсе - добавить соответствующую проверку и сюда
        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_LOCATIONS_CITIES"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "!PROPERTY_LOCATIONS_CITIES" => false,
        ];

        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $arAvailableCitiesID = [];
        while ($arContractor = $dbResult->Fetch()) {
            if (!in_array($arContractor["PROPERTY_LOCATIONS_CITIES_VALUE"], $arAvailableCitiesID)) {
                $arAvailableCitiesID[] = $arContractor["PROPERTY_LOCATIONS_CITIES_VALUE"];
            }
        }

        return $arAvailableCitiesID;
    }

    /**
     * Получение списка всех городов, в которых есть мероприятия
     *
     * @return array
     */
    public static function getAvailableCitiesEvents() {
        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_CITIES]);

        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_LOCATIONS_CITIES"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EVENTS,
            "ACTIVE" => "Y",
            "!PROPERTY_LOCATIONS_CITIES" => false,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arAvailableCitiesID = [];
        while ($arContractor = $dbResult->Fetch()) {
            if (!in_array($arContractor["PROPERTY_LOCATIONS_CITIES_VALUE"], $arAvailableCitiesID)) {
                $arAvailableCitiesID[] = $arContractor["PROPERTY_LOCATIONS_CITIES_VALUE"];
            }
        }

        $rsData = $arHLDataClasses["CITIES"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_FIAS_ID" => $arAvailableCitiesID],
                "order" => ["UF_NAME" => "ASC"],
            ]
        );
        $arCitiesFromHLBlock = [];
        while ($arItem = $rsData->Fetch()) {
            $arCitiesFromHLBlock[$arItem["UF_FIAS_ID"]] = [
                "ID" => $arItem["UF_FIAS_ID"],
                "NAME" => $arItem["UF_NAME"],
                "LATITUDE" => $arItem["UF_LAT"],
                "LONGITUDE" => $arItem["UF_LON"],
            ];
        }

        $arAvailableCities = [];
        $newCitiesID = [];
        foreach ($arAvailableCitiesID as $cityID) {
            if (key_exists($cityID, $arCitiesFromHLBlock)) {
                $arAvailableCities[] = $arCitiesFromHLBlock[$cityID];
            } else {
                $newCitiesID[] = $cityID;
            }
        }

        if ($newCitiesID) {
            foreach ($newCitiesID as $cityID) {
                $arCityData = Location::getLocationDataByID($cityID);
                $arAvailableCities[] = [
                    "ID" => $cityID,
                    "NAME" => $arCityData["data"]["city"],
                    "LATITUDE" => $arCityData["data"]["geo_lat"],
                    "LONGITUDE" => $arCityData["data"]["geo_lon"],
                ];
                $arHLDataClasses["CITIES"]::add(
                    [
                        "UF_FIAS_ID" => $cityID,
                        "UF_NAME" => $arCityData["data"]["city"],
                        "UF_LAT" => $arCityData["data"]["geo_lat"],
                        "UF_LON" => $arCityData["data"]["geo_lon"],
                    ]
                );
            }
        }

        return $arAvailableCities;
    }

    /**
     * Получение списка всех городов, в которых есть обучалки
     *
     * @return array
     */
    public static function getAvailableCitiesEducations() {

        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_CITIES]);

        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_LOCATIONS_CITIES"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_EDUCATIONS,
            "ACTIVE" => "Y",
            "!PROPERTY_LOCATIONS_CITIES" => false,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arAvailableCitiesID = [];
        while ($arEducation = $dbResult->Fetch()) {
            if (!in_array($arEducation["PROPERTY_LOCATIONS_CITIES_VALUE"], $arAvailableCitiesID)) {
                $arAvailableCitiesID[] = $arEducation["PROPERTY_LOCATIONS_CITIES_VALUE"];
            }
        }


        $rsData = $arHLDataClasses["CITIES"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_FIAS_ID" => $arAvailableCitiesID],
                "order" => ["UF_NAME" => "ASC"],
            ]
        );
        $arCitiesFromHLBlock = [];
        while ($arItem = $rsData->Fetch()) {
            $arCitiesFromHLBlock[$arItem["UF_FIAS_ID"]] = [
                "ID" => $arItem["UF_FIAS_ID"],
                "NAME" => $arItem["UF_NAME"],
                "LATITUDE" => $arItem["UF_LAT"],
                "LONGITUDE" => $arItem["UF_LON"],
            ];
        }

        $arAvailableCities = [];
        $newCitiesID = [];
        foreach ($arAvailableCitiesID as $cityID) {
            if (key_exists($cityID, $arCitiesFromHLBlock)) {
                $arAvailableCities[] = $arCitiesFromHLBlock[$cityID];
            } else {
                $newCitiesID[] = $cityID;
            }
        }

        if ($newCitiesID) {
            foreach ($newCitiesID as $cityID) {
                $arCityData = Location::getLocationDataByID($cityID);
                $arAvailableCities[] = [
                    "ID" => $cityID,
                    "NAME" => $arCityData["data"]["city"],
                    "LATITUDE" => $arCityData["data"]["geo_lat"],
                    "LONGITUDE" => $arCityData["data"]["geo_lon"],
                ];
                $arHLDataClasses["CITIES"]::add(
                    [
                        "UF_FIAS_ID" => $cityID,
                        "UF_NAME" => $arCityData["data"]["city"],
                        "UF_LAT" => $arCityData["data"]["geo_lat"],
                        "UF_LON" => $arCityData["data"]["geo_lon"],
                    ]
                );
            }
        }

        return $arAvailableCities;
    }

    /**
     * Получение списка ближайших городов, в которых есть действующие исполнители
     *
     * @param $userCityID string код ФИАС города текущего пользователя
     * @param $count int число получаемых городов
     *
     * @return array
     */
    public static function getClosestCities($userCityID, $count) {
        $arHLDataClasses = self::getHLDataClasses([HLBLOCK_CITIES]);

        $arAvailableCitiesID = self::getAvailableCitiesID();

        $rsData = $arHLDataClasses["CITIES"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_FIAS_ID" => $arAvailableCitiesID],
                "order" => [],
            ]
        );
        $arCitiesFromHLBlock = [];
        while ($arItem = $rsData->Fetch()) {
            $arCitiesFromHLBlock[$arItem["UF_FIAS_ID"]] = [
                "ID" => $arItem["UF_FIAS_ID"],
                "NAME" => $arItem["UF_NAME"],
                "LATITUDE" => $arItem["UF_LAT"],
                "LONGITUDE" => $arItem["UF_LON"],
            ];
        }

        $arAvailableCities = [];
        $newCitiesID = [];
        foreach ($arAvailableCitiesID as $cityID) {
            if (key_exists($cityID, $arCitiesFromHLBlock)) {
                $arAvailableCities[] = $arCitiesFromHLBlock[$cityID];
            } else {
                $newCitiesID[] = $cityID;
            }
        }

        if ($newCitiesID) {
            foreach ($newCitiesID as $cityID) {
                $arCityData = Location::getLocationDataByID($cityID);
                $arAvailableCities[] = [
                    "ID" => $cityID,
                    "NAME" => $arCityData["data"]["city"],
                    "LATITUDE" => $arCityData["data"]["geo_lat"],
                    "LONGITUDE" => $arCityData["data"]["geo_lon"],
                ];
                $arHLDataClasses["CITIES"]::add(
                    [
                        "UF_FIAS_ID" => $cityID,
                        "UF_NAME" => $arCityData["data"]["city"],
                        "UF_LAT" => $arCityData["data"]["geo_lat"],
                        "UF_LON" => $arCityData["data"]["geo_lon"],
                    ]
                );
            }
        }

        $arUserCityData = Location::getLocationDataByID($userCityID);
        $userCityCoordinates = [
            "LATITUDE" => $arUserCityData["data"]["geo_lat"],
            "LONGITUDE" => $arUserCityData["data"]["geo_lon"],
        ];

        foreach ($arAvailableCities as &$arAvailableCity) {
            $arAvailableCity["DISTANCE"] = Location::getDistance($userCityCoordinates, $arAvailableCity);
        }

        array_multisort(array_column($arAvailableCities, 'DISTANCE'), SORT_ASC, $arAvailableCities);
        $arAvailableCities = array_slice($arAvailableCities, 0, 5);

        return $arAvailableCities;
    }


    public static function getCoordsByAddress($address) {

        if ($address) {
            $arGeocoderParams = array(
                'geocode' => $address,
                'format'  => 'json',
                'results' => 1,
                'apikey'  => YANDEX_MAPS_API_KEY,
            );
            $url = 'https://geocode-maps.yandex.ru/1.x/?' . http_build_query($arGeocoderParams, '', '&');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $arResponse = json_decode($response, true);
            $coords = $arResponse["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["Point"]["pos"];

            return $coords;
        } else {

            return false;
        }
    }


    /**
     * Получение расстояния (км) между двумя точками по их координатам
     *
     * @param $arPoint1
     * @param $arPoint2
     *
     * @return float
     */
    public static function getDistance($arPoint1, $arPoint2) {
        $theta = $arPoint1["LONGITUDE"] - $arPoint2["LONGITUDE"];
        $distance = sin(deg2rad($arPoint1["LATITUDE"])) * sin(deg2rad($arPoint2["LATITUDE"]))
                    + cos(deg2rad($arPoint1["LATITUDE"])) * cos(deg2rad($arPoint2["LATITUDE"])) * cos(deg2rad($theta));
        $distance = rad2deg(acos($distance));
        $distance = $distance * 60 * 1.1515 * 1.609344;

        return $distance;
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

        if (in_array(HLBLOCK_CITIES, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CITIES)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["CITIES"] = $obEntity->getDataClass();
        }

        if (in_array(HLBLOCK_LOCATIONS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_LOCATIONS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["LOCATIONS"] = $obEntity->getDataClass();
        }

        return $arDataClasses;

    }



}
