<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Electric\Component\BaseComponent;

class ArticlesOnMain extends BaseComponent
{
    public $requiredModuleList = ['iblock'];

    /**
     * Получение списка статей для вывода на главной странцие
     *
     * @return array $arItems
     */
    protected function getArticles()
    {
        $arOrder = [];
        $arSelect = [
            'ID',
            'NAME',
            'IBLOCK_ID',
            'PREVIEW_PICTURE',
            'DETAIL_PAGE_URL',
            'PROPERTY_TYPE',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IBLOCK_ARTICLES,
            '!PROPERTY_SHOW_ON_MAIN_VALUE' => false,
        ];
        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, ['nPageSize' => 4], $arSelect);
        $arArticles = [];
        while ($arElement = $dbResult->GetNext()) {
            if ($arElement['PREVIEW_PICTURE']) {
                $arPicture = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
                $arElement['PREVIEW_PICTURE_SRC'] = $arPicture['SRC'];
            }
            $arArticles[] = $arElement;
        }

        $arArticleTypesID = array_unique(array_column($arArticles, 'PROPERTY_TYPE_VALUE'));
        $arSelect = [
            'ID',
            'NAME',
        ];
        $arFilter = [
            'ID' => $arArticleTypesID,
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arArticleTypes = [];
        while ($arElement = $dbResult->Fetch()) {
            $arArticleTypes[$arElement['ID']] = $arElement['NAME'];
        }

        foreach ($arArticles as &$arArticle) {
            $articleTypeID = $arArticle['PROPERTY_TYPE_VALUE'];
            if ($articleTypeID) {
                $arArticle['PROPERTY_TYPE_NAME'] = $arArticleTypes[$articleTypeID];
            }
        }

        return $arArticles;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }

        if ($this->StartResultCache(false, [$this->navigation])) {
            $this->arResult["ITEMS"] = $this->getArticles();
            $this->arResult["TYPE_VIDEO_ID"] = ARTICLES_PROPERTY_TYPE_VIDEO;
            $this->includeComponentTemplate();
        }
    }
}

?>
