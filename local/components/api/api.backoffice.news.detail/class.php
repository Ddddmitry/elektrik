<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiBackofficeNewsDetail extends AjaxComponent
{

    private $formData;

    protected function parseRequest()
    {
        $input = $input = $this->request->getQueryList()->toArray();

        $this->formData = [
            "ID" => $input['id'],
        ];
    }

    protected function getNewsDetail()
    {
        $arSelect = ["ID", "NAME", "ACTIVE_FROM", "DETAIL_TEXT"];
        $arFilter = [
            "ID" => $this->formData["ID"],
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_NEWS,
            "ACTIVE" => "Y",
        ];

        $rsNews = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arElement = $rsNews->Fetch()) {
            $arNewsDetail = [
                "date" => $arElement["ACTIVE_FROM"],
                "title" => $arElement["NAME"],
                "description" => $arElement["DETAIL_TEXT"],
            ];

            return $arNewsDetail;
        } else {
            throw new \Exception("element_not_found");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getNewsDetail();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
