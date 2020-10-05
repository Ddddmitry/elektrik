<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\User;

class ApiContractorsServiceSuggest extends AjaxComponent
{
    private $searchPhrase, $isLimited = false, $limit;

    private $arSearchOptions = [
        "CATEGORY_0" => array("iblock_services"),
        "CATEGORY_0_TITLE" => "Услуги",
        "CATEGORY_0_iblock_services" => array(IBLOCK_SERVICES),
        "CATEGORY_1" => array("iblock_users"),
        "CATEGORY_1_TITLE" => "Исполнители",
        "CATEGORY_1_iblock_users" => array(IBLOCK_CONTRACTORS),
        "INPUT_ID" => "title-search-input",
        "NUM_CATEGORIES" => "2",
        "TOP_COUNT" => "5",
        "MIN_WORD_LENGTH" => 3,
    ];

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->searchPhrase = strtolower(trim($input['searchPhrase']));

        if ($input['limit']) {
            $this->isLimited = true;
            $this->limit = $input['limit'];
        }

    }

    /**
     * Получение списка поисковых подсказок
     */
    protected function getSuggestions()
    {
        $isSearchInstalled = \CModule::IncludeModule("search");
        $arResult = [];

        if ($this->searchPhrase) {
            \CUtil::decodeURIComponent($this->searchPhrase);
            if (!$isSearchInstalled)
            {
                require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/search/tools/language.php");
                require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/search/tools/stemming.php");
            }

            for($i = 0; $i < $this->arSearchOptions["NUM_CATEGORIES"]; $i++)
            {
                $title = trim($this->arSearchOptions["CATEGORY_".$i."_TITLE"]);

                $arResult["CATEGORIES"][$i] = array(
                    "TITLE" => htmlspecialcharsbx($title),
                    "ITEMS" => array()
                );

                if ($isSearchInstalled)
                {
                    $exFILTER = array(
                        0 => \CSearchParameters::ConvertParamsToFilter($this->arSearchOptions, "CATEGORY_".$i),
                    );
                    $exFILTER[0]["LOGIC"] = "OR";

                    $j = 0;
                    $obTitle = new \Electric\Helpers\CSearchTitleCustom;
                    $obTitle->setMinWordLength($this->arSearchOptions["MIN_WORD_LENGTH"]);
                    $obTitle->Search($this->searchPhrase, $this->arSearchOptions["TOP_COUNT"], $exFILTER, false);

                    $obUser = new User;
                    $userCityID = $obUser->getUserCityID();

                    while($ar = $obTitle->Fetch()) {

                        // Разделов не должно быть в результатах
                        if (substr($ar['ITEM_ID'], 0, 1) == 'S') continue;
                        // В результатах не должно быть исполнителей из городов, отличных от города пользователя
                        if ($ar["PARAM1"] == "users" && strpos($ar["BODY"], $userCityID) === false) continue;

                        if ($j++ == $this->arSearchOptions["TOP_COUNT"]) break;

                        $arResult["CATEGORIES"][$i]["ITEMS"][] = array(
                            "TITLE" => $ar["TITLE"],
                            "NAME" => $ar["NAME"],
                            "ID" => $ar["ITEM_ID"],
                        );
                    }

                    foreach ($arResult["CATEGORIES"] as &$arCategory) {
                        array_multisort(array_column($arCategory["ITEMS"], 'TITLE'), SORT_ASC, $arCategory["ITEMS"]);
                    }
                }
            }

        }

        return $arResult;
    }


    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->getSuggestions();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
