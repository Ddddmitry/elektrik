<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiBackofficeNewsList extends AjaxComponent
{

    protected function parseRequest()
    {

    }

    protected function getNews()
    {
        $arSelect = ["ID", "NAME", "ACTIVE_FROM", "PREVIEW_TEXT"];
        $arOrder = ["ACTIVE_FROM" => "DESC"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_NEWS,
            "ACTIVE" => "Y",
        ];
        $arNews = [];
        $rsNews = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $rsNews->Fetch()) {
            $arNews[] = [
                "id" => $arElement["ID"],
                "date" => $arElement["ACTIVE_FROM"],
                "description" => $arElement["PREVIEW_TEXT"],
            ];
        }

        return $arNews;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getNews();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
