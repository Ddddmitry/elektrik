<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiStatisticsAdd extends AjaxComponent
{
    private $arViewData;

    private $arHLDataClasses;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->arViewData = [
            "TYPE" => $input["type"],
            "USER" => $input["user"],
            "SESSION" => $input["session"],
        ];
    }


    /**
     * Добавление записи о просмотре детальной страницы или
     * контактов исполнителя
     *
     * @return bool
     */
    protected function addView()
    {
        switch ($this->arViewData["TYPE"]) {
            case "detail":
                $hlBlock = "VIEWS_DETAIL";
                break;
            case "contacts":
                $hlBlock = "VIEWS_CONTACTS";
                break;
            default:
                $hlBlock = "VIEWS_DETAIL";
        }

        $rsData = $this->arHLDataClasses[$hlBlock]::getList(
            [
                "select" => ['*'],
                "filter" => [
                    "UF_USER" => $this->arViewData["USER"],
                    "UF_SESSION" => $this->arViewData["SESSION"]
                ],
                "order" => [],
            ]
        );
        $arItems = $rsData->fetchAll();
        if (!$arItems) {
            $arItem = [
                "UF_USER" => $this->arViewData["USER"],
                "UF_SESSION" => $this->arViewData["SESSION"],
                "UF_DATE" => (new \Bitrix\Main\Type\DateTime)->toString(),
            ];
            $this->arHLDataClasses[$hlBlock]::add($arItem);

            return true;
        } else {

            return false;
        }
    }

    private function getHLDataClasses() {
        \CModule::IncludeModule('highloadblock');
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_DETAIL)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_DETAIL"] = $obEntity->getDataClass();
        $arHighloadblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_VIEWS_CONTACTS)->fetch();
        $obEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHighloadblock);
        $this->arHLDataClasses["VIEWS_CONTACTS"] = $obEntity->getDataClass();
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        $this->getHLDataClasses();

        try {
            $result = $this->addView();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
