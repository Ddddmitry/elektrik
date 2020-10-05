<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Component\BaseComponent;
use Electric\Events;
use Electric\Helpers\DataHelper;
use Bitrix\Main\Application;
use Bitrix\Iblock;

class  OrderElement extends BaseComponent
{

    public $requiredModuleList = ['iblock'];
    private $params;
    private $curUser,$isAuth;



    public function onPrepareComponentParams($arParams)
    {
        $this->params = $arParams;

        return $arParams;
    }

    /**
     * Получение заказа
     *
     * @return array
     * @throws \Exception
     */
    protected function getOrder()
    {


        $arSelect = [
            'ID',
            'NAME',
            'CODE',
            'IBLOCK_ID',
            'ACTIVE_FROM',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'DETAIL_PICTURE',
            'DETAIL_PAGE_URL'
        ];
        $arFilter = [
            "ID" => $this->params["ELEMENT_ID"],
            "IBLOCK_ID" => IBLOCK_ORDERS,
            "ACTIVE" => "Y",
        ];


        $rsOrder = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arReturn = [];
        if ($arElement = $rsOrder->Fetch()) {
            // Получение изображений
            if ($arElement['PREVIEW_PICTURE']) {
                $arElement['PREVIEW_PICTURE'] = \CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
            }
            if ($arElement['DETAIL_PICTURE']) {
                $arElement['DETAIL_PICTURE'] = \CFile::GetFileArray($arElement['DETAIL_PICTURE']);
            }

            $arElement["DATE"] = DataHelper::getFormattedDate($arElement["ACTIVE_FROM"]);
            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arElement['IBLOCK_ID'],
                $arElement['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if($arProperty["PROPERTY_TYPE"] == "F"){
                    if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                        $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                        $arElement["PROPERTIES"][$arProperty["CODE"]][] = [
                            "FULL" => $arPicture,
                            "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
                            "TITLE" => $arDescription[0],
                            "DESCRIPTION" => $arDescription[1],
                        ];
                    }
                }elseif($arProperty["CODE"] == "DATE_TIME"){
                    $arProperty["DATE"] = DataHelper::getFormattedDate($arProperty["VALUE"]);
                    $arProperty["TIME"] = DataHelper::getFormattedDateSingle($arProperty["VALUE"],"H:m");
                    $arElement['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }else{
                    $arElement['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }

            if($arElement["PROPERTIES"]["USER"]["VALUE"] != $this->curUser){
                LocalRedirect('/order/');
                $arElement = [];
            }

            $arReturn = $arElement;
        }

        return $arReturn;
    }


    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');
            return false;
        }

        global $USER;
        $this->curUser = $USER->GetID();
        if (!$USER->IsAuthorized()) {
            //LocalRedirect('/order/');
        }else{
            $this->isAuth = true;
        }

        $arItem = $this->getOrder();

        if ($this->StartResultCache(false, [$arItem["ID"]])) {
            $this->arResult["ELEMENT"] = $arItem;
            $this->arResult["IS_AUTH"] = $this->isAuth;
            $this->includeComponentTemplate();
        }

    }
}