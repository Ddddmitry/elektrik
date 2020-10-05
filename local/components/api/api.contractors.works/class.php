<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\User;

class ApiContractorsServiceSuggest extends AjaxComponent
{
    private $workId;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        if ($input['workId']) {

            $this->workId = $input['workId'];
        }

    }

    /**
     * Получение примера работы по ID
     */
    protected function getWorks()
    {
        $arResult = false;
        $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT'];
        $arOrder = [
            "ACTIVE_FROM" => "DESC",
        ];
        // Добавление фильтрации
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS_WORKS,
            "ID" => $this->workId
        ];

        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arWork = $dbResult->fetch()) {
            // Получение изображения
            if ($arWork['PREVIEW_PICTURE']) {
                $arWork['PREVIEW_PICTURE'] = \CFile::GetFileArray($arWork['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arWork["DATE"] = DataHelper::getFormattedDate($arWork["ACTIVE_FROM"]);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arWork['IBLOCK_ID'],
                $arWork['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "TAGS") {
                    $arWork['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = $arProperty["VALUE"];
                } else {
                    $arWork['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }
            $arResult = $arWork;
        }

        return $arResult;
    }


    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->getWorks();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
