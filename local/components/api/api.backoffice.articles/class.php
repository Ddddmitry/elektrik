<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\DataHelper;
use \Electric\Article;

class ApiBackofficeArticles extends AjaxComponent
{

    protected function parseRequest()
    {

    }

    protected function getArticles()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $userID = $USER->GetID();
        } else {
            throw new \Exception('authorization_required');
        }

        $arSelect = ['ID', 'ACTIVE', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', "PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_ARTICLES,
            "=PROPERTY_USER" => $userID,
        ];
        $dbResult = \CIBlockElement::GetList(["ACTIVE_FROM" => "DESC"], $arFilter, false, false, $arSelect);

        $arArticles = [];
        $arTypesID = [];
        while ($arArticle = $dbResult->Fetch()) {

            // Получение URL детальной страницы
            $arIblock = \CIBlock::GetByID(IBLOCK_ARTICLES)->GetNext();
            $urlTemplate = $arIblock["DETAIL_PAGE_URL"];
            $arArticle["DETAIL_PAGE_URL"] = \CIBlock::ReplaceDetailUrl($urlTemplate, $arArticle);

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arArticle['IBLOCK_ID'],
                $arArticle['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "TAGS") {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = $arProperty["VALUE"];
                } else {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }

            $arTypesID[] = $arArticle["PROPERTIES"]["TYPE"]["VALUE"];

            $arArticles[] = $arArticle;
        }

        // Получение категорий статей
        $obArticle = new Article();
        $arTypes = $obArticle->getArticlesTypes($arTypesID);

        $arArticlesData = [];
        foreach ($arArticles as $arArticle) {

            $arCommentsCount = $obArticle->getCommentsCount($arArticle["ID"], true);

            $arArticleData = [
                "active" => ($arArticle["ACTIVE"] === "Y") ? true : false,
                "title" => $arArticle["NAME"],
                "preview_text" => $arArticle["PREVIEW_TEXT"],
                "link" => $arArticle["DETAIL_PAGE_URL"],
                "date" => DataHelper::getFormattedDate($arArticle["ACTIVE_FROM"]),
                "category" => null,
                "themes" => [],
                "commentsNumber" => $arCommentsCount["TOTAL"],
                "newCommentsNumber" => $arCommentsCount["NEW"],
            ];

            if ($arArticle["PREVIEW_PICTURE"]) {
                $arArticleData["img"] = [
                    "src" =>  \CFile::GetFileArray($arArticle["PREVIEW_PICTURE"])["SRC"],
                ];
            }

            if ($arArticle["PROPERTIES"]["TYPE"]["VALUE"]) {
                $arArticleData["category"] = [
                    "name" => $arTypes[$arArticle["PROPERTIES"]["TYPE"]["VALUE"]],
                    "code" => $arArticle["PROPERTIES"]["TYPE"]["VALUE"],
                ];
            }

            foreach ($arArticle["PROPERTIES"]["TAGS"]["VALUE"] as $tag) {
                if ($tag) {
                    $arArticleData["themes"][] = [
                        "name" => $tag,
                    ];
                }
            }


            $arArticlesData[] = $arArticleData;
        }

        return $arArticlesData;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->getArticles();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
