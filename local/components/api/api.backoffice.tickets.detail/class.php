<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\DataHelper;

class ApiBackofficeQuestionsDetail extends AjaxComponent
{

    private $formData;

    protected function parseRequest()
    {
        $input = $input = $this->request->getQueryList()->toArray();

        $this->formData = [
            "ID" => $input['id'],
        ];
    }

    protected function getQuestionsDetail()
    {
        $arSelect = ["ID", "NAME", "ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT", "PROPERTY_AUTHOR_ANSWER",
                     "PROPERTY_IS_CLOSED", "PROPERTY_ANSWER_DATE", "PROPERTY_FILES"];
        $arFilter = [
            "ID" => $this->formData["ID"],
            "IBLOCK_ID" => IBLOCK_BACKOFFICE_TICKETS,
            "ACTIVE" => "Y",
        ];

        $rsQuestions = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arElement = $rsQuestions->Fetch()) {

            $arQuestionsDetail = [
                "id" => $arElement["ID"],
                "date" => DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]),
                "title" => $arElement["NAME"],
                "text" => $arElement["PREVIEW_TEXT"],
                "answer" => $arElement["DETAIL_TEXT"],
                "answerDate" => DataHelper::getFormattedDate($arElement["PROPERTY_ANSWER_DATE_VALUE"]),
                "authorAnswer" => $arElement["PROPERTY_AUTHOR_ANSWER_VALUE"]["TEXT"],
                "isClosed" => $arElement["PROPERTY_AUTHOR_ANSWER_VALUE"] ? true : false,
            ];

            $dbProperty = \CIBlockElement::getProperty(
                IBLOCK_BACKOFFICE_TICKETS,
                $arElement["ID"]
            );
            $arFiles = [];
            while ($arProperty = $dbProperty->Fetch()) {
                if ($arProperty["CODE"] == "FILES") {
                    $arFiles[] = \CFile::GetFileArray($arProperty["VALUE"])["SRC"];
                }
            }

            $arQuestionsDetail["files"] = $arFiles;

            return $arQuestionsDetail;
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
            $result = $this->getQuestionsDetail();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
