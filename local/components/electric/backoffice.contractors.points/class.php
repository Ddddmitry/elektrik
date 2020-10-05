<?php
namespace Electric\Component;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Electric\Client;
use \Electric\Component\BaseComponent;
use Electric\Contractor;
use Electric\Distributor;
use Electric\Order;
use Electric\User;
use Electric\Vendor;
use \Electric\Helpers\DataHelper;

class ContractorsPoints extends BaseComponent
{
    public $requiredModuleList = ['iblock', 'highloadblock'];

    protected $arClient;

    private $folder;

    protected $curUserId;

    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    /**
     * Получение данных клиента
     *
     * @return array|bool
     */
    protected function getPoints()
    {
        global $USER;
        $this->curUserId = $USER->GetID();
        $arPoints = [];
        $obUser = new User();
        $obContractor = new Contractor();
        $arResult["TOTAL_POINTS"] = 0;
        $arHLDataClasses = $obContractor->getHLDataClasses([HLBLOCK_CONTRACTOR_POINTS,HLBLOCK_CONTRACTOR_POINTS_HISTORY]);


        /**
         * Собираем историю начислений баллов
        */
        $rsData = $arHLDataClasses["CONTRACTOR_POINTS_HISTORY"]::getList(
            [
                'select' => ['*'],
                'filter' => [
                    'UF_USER_ID' => $this->curUserId,
                ],
                'order' => ["ID" => "DESC"],
            ]
        );
        while($el = $rsData->fetch()){

            $el["DATE"] = DataHelper::getFormattedDate($el["UF_DATE"]);
            $el["POINTS_WORD"] = DataHelper::getWordForm("points",$el["UF_POINTS"]);

            $type = $obUser->getType($el["UF_PARTNER_ID"]);
            if($type == "distributor")
                $arHistory["DISTRIBUTORS"][] = $el;
            if($type == "vendor")
                $arHistory["VENDORS"][] = $el;
            $arPartnersID[$el["UF_PARTNER_ID"]] = $el["UF_PARTNER_ID"];
        }
        //Получение дистрибьюторов и вендоров
        $arSelect = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'PROPERTY_USER'
        ];
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_DISTRIBUTORS,
            'PROPERTY_USER' => $arPartnersID
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arPartners = [];
        while ($arPartner = $dbResult->GetNext()) {

            // Получение изображений
            if ($arPartner['PREVIEW_PICTURE']) {
                $arPartner['PREVIEW_PICTURE'] = \CFile::GetFileArray($arPartner['PREVIEW_PICTURE']);
            }

            $arPartners[$arPartner["PROPERTY_USER_VALUE"]] = $arPartner["PREVIEW_PICTURE"];
        }

        $arFilter = [
            'IBLOCK_ID' => IBLOCK_VENDORS,
            'PROPERTY_USER' => $arPartnersID
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arPartner = $dbResult->GetNext()) {

            // Получение изображений
            if ($arPartner['PREVIEW_PICTURE']) {
                $arPartner['PREVIEW_PICTURE'] = \CFile::GetFileArray($arPartner['PREVIEW_PICTURE']);
            }

            $arPartners[$arPartner["PROPERTY_USER_VALUE"]] = $arPartner["PREVIEW_PICTURE"];
        }

        foreach ($arHistory["DISTRIBUTORS"] as &$history) {
            $history["PARTNER"] = $arPartners[$history["UF_PARTNER_ID"]];
        }
        foreach ($arHistory["VENDORS"] as &$history) {
            $history["PARTNER"] = $arPartners[$history["UF_PARTNER_ID"]];
        }
        $arResult["HISTORY"] = $arHistory;

        /**
         * Собираем количество баллов
        */
        $arPoints = [];
        $rsData = $arHLDataClasses["CONTRACTOR_POINTS"]::getList(
            [
                'select' => ['*'],
                'filter' => [
                    'UF_CONTACTOR_USER' => $this->curUserId,
                ],
                'order' => ["ID" => "DESC"],
            ]
        );
        while($el = $rsData->fetch()){
            $arResult["TOTAL_POINTS"] += $el["UF_POINTS"];
            $el["POINTS_WORD"] = DataHelper::getWordForm("points",$el["UF_POINTS"]);

            $type = $obUser->getType($el["UF_PARTNER_ID"]);
            if($type == "distributor")
                $arPoints["DISTRIBUTORS"][] = $el;
            if($type == "vendor")
                $arPoints["VENDORS"][] = $el;
        }
        foreach ($arPoints["DISTRIBUTORS"] as &$history) {
            $history["PARTNER"] = $arPartners[$history["UF_PARTNER_ID"]];
        }
        foreach ($arPoints["VENDORS"] as &$history) {
            $history["PARTNER"] = $arPartners[$history["UF_PARTNER_ID"]];
        }
        $arResult["POINTS"] = $arPoints;


        $arResult["TOTAL_POINTS_WORD"] = DataHelper::getWordForm("points",$arResult["TOTAL_POINTS"]);

        return $arResult;
    }

    public function executeComponent()
    {
        if (!$this->checkRequiredModuleList()) {
            ShowError('Установите необходимые модули');

            return false;
        }
        if ($this->StartResultCache(false)) {

            $this->arResult = $this->getPoints();

            $this->includeComponentTemplate();
        }

    }
}
