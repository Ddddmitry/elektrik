<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Contractor;
use \Electric\Service;

class ApiBackofficeHelperLists extends AjaxComponent
{

    private $arRequiredLists;

    protected function parseRequest()
    {
        $input = $this->request->getQueryList()->toArray();
        $this->arRequiredLists = $input['lists'];
    }

    protected function getLists()
    {
        $arHLDataClasses = Contractor::getHLDataClasses([HLBLOCK_SKILLS, HLBLOCK_LANGUAGES_LIST, HLBLOCK_LANGUAGES_LEVELS]);

        $arLists = [];

        foreach ($this->arRequiredLists as $requiredList) {
            switch ($requiredList) {
                case "SERVICES":
                    $obService = new Service;
                    $arGroupedServices = $obService->getAllServices();

                    foreach ($arGroupedServices as $key => $arSection) {
                        $arLists[$requiredList][$key] = [
                            "name" => $arSection["NAME"],
                        ];
                        foreach ($arSection["SERVICES"] as $arService) {
                            $arLists[$requiredList][$key]["items"][$arService["ID"]] = [
                                "name" => $arService["NAME"],
                                "added" => false,
                                "price" => "",
                            ];
                        }
                    }
                    break;
                default:
                    $rsData = $arHLDataClasses[$requiredList]::getList(
                        [
                            "select" => ['*'],
                            "filter" => [],
                            "order" => [],
                        ]
                    );
                    while ($arItem = $rsData->Fetch()) {
                        $arLists[$requiredList][] = [
                            "ID" => $arItem["ID"],
                            "NAME" => $arItem["UF_NAME"]
                        ];
                    }
                    break;
            }

        }

        return $arLists;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        try {
            $result = $this->getLists();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
