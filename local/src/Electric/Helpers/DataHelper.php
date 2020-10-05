<?php

namespace Electric\Helpers;

use Bitrix\Main\Application;
use Bitrix\Highloadblock\HighloadBlockTable;

class DataHelper
{
    public static function getFormattedDate($date) {
        $arMonth = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"];
        $obDateTime = new \DateTime($date);
        $formattedDate = (int)$obDateTime->format("d") . " " . $arMonth[(int)$obDateTime->format("m") - 1] . strtolower($obDateTime->format(" Y"));

        return $formattedDate;
    }

    public static function getFormattedDateSingle($timestamp,$format = "d m Y") {
        $obDateTime = new \DateTime($timestamp);
        return $obDateTime->format($format);
    }

    public static function getMonth($number) {
        $arMonths = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];

        return $arMonths[$number];
    }

    public static function getBannerData($bannerCode) {
        \CModule::IncludeModule('highloadblock');

        if (!$bannerCode) $bannerCode = "DEFAULT";

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_BANNERS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $obHLDataClassBanners = $obEntity->getDataClass();

        $rsData = $obHLDataClassBanners::getList(
            array(
                "select" => ['*'],
                "filter" => ["UF_CODE" => $bannerCode],
                "order" => ["UF_SORT" => "ASC"],
            )
        );
        while($arBanner = $rsData->fetch()){
            $arFile = \CFile::GetFileArray($arBanner["UF_FILE"]);

            $arResult[] = [
                "SRC" => $arFile["SRC"],
                "TITLE" => $arBanner["UF_TITLE"],
                "SUBTITLE" => $arBanner["UF_SUBTITLE"],
                "HEIGHT" => $arBanner["UF_HEIGHT"] ? $arBanner["UF_HEIGHT"] : $arFile["HEIGHT"],
                "FORMAT" => $arBanner["UF_FORMAT"] ? "search_".$arBanner["UF_FORMAT"] : "",
            ];
        }

        return $arResult;
    }


    public static function getFormBannerData($bannerCode) {
        \CModule::IncludeModule('highloadblock');

        if (!$bannerCode) $bannerCode = "DEFAULT";

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_FORM_BANNERS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $obHLDataClassBanners = $obEntity->getDataClass();

        $rsData = $obHLDataClassBanners::getList(
            array(
                "select" => ['*'],
                "filter" => ["UF_CODE" => $bannerCode],
                "order" => ["UF_SORT" => "ASC"],
            )
        );

        $arResult = [];
        while ($arBanner = $rsData->fetch()) {
            $arResult[] = [
                "SRC" => \CFile::GetFileArray($arBanner["UF_FILE"])["SRC"],
                "TITLE" => $arBanner["UF_TITLE"],
                "SUBTITLE" => $arBanner["UF_SUBTITLE"],
            ];
        }

        return $arResult;
    }


    public static function getWordForm($type, $number) {
        $wordForm = "";
        $lastDigit = substr($number, -1);
        if ($number > 10) $prevDigit = substr($number, -2, 1);
        switch ($type) {
            case "reviews":
                if ($prevDigit == 1) return "отзывов";
                switch ($lastDigit) {
                    case "1":
                        $wordForm = "отзыв";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "отзыва";
                        break;
                    default:
                        $wordForm = "отзывов";
                        break;
                }
                break;
            case "comments":
                if ($prevDigit == 1) return "комментариев";
                switch ($lastDigit) {
                    case "1":
                        $wordForm = "комментарий";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "комментария";
                        break;
                    default:
                        $wordForm = "комментариев";
                        break;
                }
                break;
            case "new":
                if ($prevDigit == 1) return "новый";
                switch ($lastDigit) {
                    case "1":
                        $wordForm = "новый";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "новых";
                        break;
                    default:
                        $wordForm = "новых";
                        break;
                }
                break;
            case "articles":
                if ($prevDigit == 1) return "статей";
                switch ($lastDigit) {
                    case "1":
                        $wordForm = "статья";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "статьи";
                        break;
                    default:
                        $wordForm = "статей";
                        break;
                }
                break;
            case "views":
                if ($prevDigit == 1) return "новых просмотров";
                switch ($lastDigit) {
                    case "1":
                        $wordForm = "новый просмотр";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "новых просмотра";
                        break;
                    default:
                        $wordForm = "новых просмотров";
                        break;
                }
                break;
            case "time":

                switch ($lastDigit) {
                    case "1":
                        $wordForm = "минута";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "минуты";
                        break;
                    default:
                        $wordForm = "минут";
                        break;
                }
                break;
            case "points":

                switch ($lastDigit) {
                    case "1":
                        $wordForm = "балл";
                        break;
                    case "2":
                    case "3":
                    case "4":
                        $wordForm = "балла";
                        break;
                    default:
                        $wordForm = "баллов";
                        break;
                }
                break;
        }

        return $wordForm;
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

        if (in_array(HLBLOCK_NOTIFICATIONS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_NOTIFICATIONS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["NOTIFICATIONS"] = $obEntity->getDataClass();
        }
        if (in_array(HLBLOCK_VIEWS_DETAIL, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_VIEWS_DETAIL)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["VIEWS_DETAIL"] = $obEntity->getDataClass();
        }

        return $arDataClasses;

    }

    /**
     * Подключение классов для работы с сущностями D7
     *
     * @param array $arList - массив ID нужных HL-блоков
     *
     * @return array
     */
    public static function getAllIntegrationPartners() {
        \CModule::IncludeModule('highloadblock');

        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_INTEGRATION_PARTNERS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $obHLDataClassBanners = $obEntity->getDataClass();

        $rsData = $obHLDataClassBanners::getList(
            array(
                "select" => ['*'],
                "filter" => [],
                "order" => [],
            )
        );

        $arResult = [];
        while ($arItem = $rsData->fetch()) {
            $arResult[] = [
                "NAME" => $arItem["UF_NAME"],
                "URL" => $arItem["UF_URL"],
                "UID" => $arItem["UF_UID"],
                "TYPE" => $arItem["UF_TYPE"],
                "CERT" => $arItem["UF_CERT"],
            ];
        }

        return $arResult;
    }

}
