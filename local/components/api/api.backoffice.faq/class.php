<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiBackofficeFaq extends AjaxComponent
{

    protected function parseRequest() { }

    protected function getFaq()
    {
        $arSelect = ["ID", "NAME", "PREVIEW_TEXT"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_FAQ,
        ];
        $arFaq = [];
        $rsFaq = \CIBlockElement::GetList(["SORT" => "DESC"], $arFilter, false, false, $arSelect);
        while ($arElement = $rsFaq->Fetch()) {
            $arFaq[] = [
                "id" => $arElement["ID"],
                "title" => $arElement["NAME"],
                "text" => $arElement["PREVIEW_TEXT"],
            ];
        }

        return $arFaq;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getFaq();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
