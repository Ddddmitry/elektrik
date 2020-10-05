<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Client;
use \Electric\Component\BaseComponent;
use Electric\Contractor;
use Electric\Distributor;
use Electric\Order;
use Electric\Vendor;
use \Electric\Helpers\DataHelper;

class Sales extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arClient;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {
        $this->folder = $arParams['SEF_FOLDER'];

        return $arParams;
    }

    /**
     * Получение данных клиента
     *
     * @return array|bool
     */
    protected function getSales()
    {
        global $USER;
        $this->curUserId = $USER->GetID();

        $arSelect = ['ID', 'NAME', 'CODE', 'IBLOCK_ID', 'ACTIVE_FROM', 'PREVIEW_PICTURE', 'PREVIEW_TEXT','DETAIL_TEXT', 'DETAIL_PICTURE'];
        $arOrder = [
            "SORT" => "ASC",
            "DATE_CREATE" => "DESC",
        ];

        // Добавление фильтрации
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => IBLOCK_SALES,
        ];
        $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $arSales = [];

        while ($arItem = $dbResult->fetch()) {

            // Получение изображений
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PREVIEW_PICTURE'] = \CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
            }
            if ($arItem['DETAIL_PICTURE']) {
                $arItem['DETAIL_PICTURE'] = \CFile::GetFileArray($arItem['DETAIL_PICTURE']);
            }

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arItem['IBLOCK_ID'],
                $arItem['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                $arItem['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
            }

            $arSales[] = $arItem;
        }



        return $arSales;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }
        if ($this->StartResultCache(false)) {

            $this->arResult['SALES'] = $this->getSales();
            $this->arResult['SEF_FOLDER'] = $this->folder;
            $this->includeComponentTemplate();
        }

    }
}
