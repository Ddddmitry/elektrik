<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;
use \Electric\Article;
use \Electric\Helpers\DataHelper;

class ArticlesDetail extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $articleCode;
    protected $arArticle;

    private $input;

    private $folder;

    private $obHLDataClassServices;

    public function onPrepareComponentParams($arParams)
    {
        $this->articleCode = $arParams['CODE'];
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    protected function setSeo(){
        // Получение значений SEO для элемента
        global $APPLICATION;

        $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(IBLOCK_ARTICLES, $this->arResult['ARTICLE']["ID"]);
        $arElementSEO = $ipropElementValues->getValues();

        if ($arElementSEO["ELEMENT_META_TITLE"]) {
            $APPLICATION->SetPageProperty('title', $arElementSEO["ELEMENT_META_TITLE"]);
        } else {
            $APPLICATION->SetPageProperty('title', $this->arResult['ARTICLE']['NAME']);
        }

        if ($arElementSEO["ELEMENT_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["ELEMENT_META_KEYWORDS"];
        } elseif ($arElementSEO["SECTION_META_KEYWORDS"]) {
            $sSEOKeywords = $arElementSEO["SECTION_META_KEYWORDS"];
        }
        if ($arElementSEO["ELEMENT_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["ELEMENT_META_DESCRIPTION"];
        } elseif ($arElementSEO["SECTION_META_DESCRIPTION"]) {
            $sSEODescription = $arElementSEO["SECTION_META_DESCRIPTION"];
        }
        if ($sSEOKeywords) {
            $APPLICATION->SetPageProperty('keywords', $sSEOKeywords);
        }
        if ($sSEODescription) {
            $APPLICATION->SetPageProperty('description', $sSEODescription);
        }
    }

    /**
     * Получение данных статьи
     *
     * @return bool
     * @throws \Exception
     */
    protected function getArticle()
    {
        if (empty($this->articleCode)) {
            return false;
        }

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'DETAIL_TEXT', 'PREVIEW_TEXT'];
        $arFilter = array(
            "IBLOCK_ID" => IBLOCK_ARTICLES,
            "CODE" => $this->articleCode,
        );
        $dbResult = \Bitrix\Iblock\ElementTable::getList(array(
             'select' => $arSelect,
             'filter' => $arFilter,
         ));

        if ($arArticle = $dbResult->fetch()) {

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arArticle['IBLOCK_ID'],
                $arArticle['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["MULTIPLE"] == "Y") {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = $arProperty["VALUE"];
                } else {
                    $arArticle['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }

            // Получение даты
            $arArticle["DATE"] = DataHelper::getFormattedDate($arArticle["ACTIVE_FROM"]);

            // Получение категории статьи
            if ($arArticle["PROPERTIES"]["TYPE"]["VALUE"]) {
                $arSelect = [
                    'ID',
                    'NAME',
                ];
                $arFilter = [
                    'IBLOCK_ID' => IBLOCK_ARTICLES_TYPES,
                    'ID' => $arArticle["PROPERTIES"]["TYPE"]["VALUE"],
                ];
                $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
                if ($arType = $dbResult->GetNext()) {
                    $arArticle["PROPERTIES"]["TYPE"]["VALUE_NAME"] = $arType["NAME"];
                }
            }

            // Получение автора статьи
            if ($arArticle["PROPERTIES"]["USER"]["VALUE"]) {
                $arFilter = [
                    "ID" => $arArticle["PROPERTIES"]["USER"]["VALUE"],
                ];
                $dbResult = \Bitrix\Main\UserTable::getList([
                    "select" => Array("ID", "NAME", "LAST_NAME"),
                    "filter" => $arFilter,
                ]);
                if ($arAuthor = $dbResult->fetch()) {
                    $arArticle["PROPERTIES"]["USER"]["VALUE_NAME"] = $arAuthor["NAME"] . " " . $arAuthor["LAST_NAME"];
                }
            }

            // Получение данных баннера
            if ($arArticle["PROPERTIES"]["BANNER"]["VALUE"]) {
                $arArticle["BANNER"]["SRC"] = \CFile::GetFileArray($arArticle["PROPERTIES"]["BANNER"]["VALUE"])["SRC"];
                if ($arArticle["PROPERTIES"]["BANNER_LINK"]["VALUE"]) $arArticle["BANNER"]["LINK"] = $arArticle["PROPERTIES"]["BANNER_LINK"]["VALUE"];
            }

            $obArticle = new Article();

            // Получение количества комментариев
            $arArticle["COMMENTS"]["COUNT"] = $obArticle->getCommentsCount($arArticle["ID"])["TOTAL"];
            $arArticle["COMMENTS"]["COUNT_WORD"] = DataHelper::getWordForm("comments", $arArticle["COMMENTS"]["COUNT"]);

            // Получение связанных статей
            if (array_filter($arArticle["PROPERTIES"]["RELATED"]["VALUE"])) {
                $urlTemplate = $this->arParams["SEF_FOLDER"] . $this->arParams["SEF_URL_TEMPLATES"]["detail"];
                $arArticle["RELATED_ARTICLES"] = $obArticle->getRelatedArticles($arArticle["PROPERTIES"]["RELATED"]["VALUE"], $urlTemplate);
            }

            $this->arArticle = $arArticle;

            return true;
        } else {

            return false;
        }
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        if ($this->StartResultCache()) {
            if ($this->getArticle() !== false) {
                $this->arResult['ARTICLE'] = $this->arArticle;
                $this->arResult["SEF_FOLDER"] = $this->folder;
                $this->arResult["BACK_URL"] = $this->input["backoffice"] ? PATH_BACKOFFICE_ARTICLES : PATH_ARTICLES;
                $this->includeComponentTemplate();
            } else {
                $this->abortResultCache();
                $this->set404();
            }
        }

        $this->setSeo();
    }
}
