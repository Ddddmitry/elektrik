<?php

namespace Electric\EventHandlers;

use Electric\Contractor;
use Electric\User;
use Electric\Notification;
use Electric\Rest;
use Electric\Helpers\DataHelper;
use Symfony\Component\HttpFoundation\ParameterBag;

class UserHandler
{

    /**
     * Обработчики событий после добавления нового пользователя.
     *
     * @param $arFields
     *
     * @return bool
     */
    function OnAfterUserAddHandler($arFields)
    {

        if ($arFields["ID"] > 0) {
            \CModule::IncludeModule('iblock');
            foreach ($arFields["GROUP_ID"] as $arGroup) {
                /** Если пользователь создается в групе Дитрибьюторы, то создаем профиль в инфоблоке Дистрибьюторов */
                if ($arGroup["GROUP_ID"] == USER_GROUP_DISTRIBUTORS) {
                    $fullName = trim($arFields["LAST_NAME"] . " " . $arFields["NAME"]);
                    if ($fullName == "")
                        $fullName = $arFields["LOGIN"];
                    $obSection = new \CIBlockSection;
                    $arDistributorSectionFields = array(
                        "IBLOCK_ID" => IBLOCK_DISTRIBUTORS,
                        "NAME" => $fullName,
                    );
                    $distributorSectionID = $obSection->Add($arDistributorSectionFields);
                    if (!$distributorSectionID) {
                        throw new \Exception($obSection->LAST_ERROR);
                    }

                    $obElement = new \CIBlockElement;
                    $arDistributorFields = array(
                        "IBLOCK_ID" => IBLOCK_DISTRIBUTORS,
                        "NAME" => $fullName,
                        "IBLOCK_SECTION_ID" => $distributorSectionID,
                    );
                    if ($distributorID = $obElement->Add($arDistributorFields)) {
                        $arDistributorProperties = array(
                            "USER" => $arFields["ID"],
                            "UID" => md5($arFields["ID"])
                        );

                        \CIBlockElement::SetPropertyValuesEx($distributorID, false, $arDistributorProperties);
                    }
                }
                /** Если пользователь создается в групе Вендоры, то создаем профиль в инфоблоке Вендоры */
                if ($arGroup["GROUP_ID"] == USER_GROUP_VENDORS) {
                    $fullName = trim($arFields["LAST_NAME"] . " " . $arFields["NAME"]);
                    if ($fullName == "")
                        $fullName = $arFields["LOGIN"];
                    $obSection = new \CIBlockSection;
                    $arVendorSectionFields = array(
                        "IBLOCK_ID" => IBLOCK_VENDORS,
                        "NAME" => $fullName,
                    );
                    $vendorSectionID = $obSection->Add($arVendorSectionFields);
                    if (!$vendorSectionID) {
                        throw new \Exception($obSection->LAST_ERROR);
                    }

                    $obElement = new \CIBlockElement;
                    $arVendorFields = array(
                        "IBLOCK_ID" => IBLOCK_VENDORS,
                        "NAME" => $fullName,
                        "IBLOCK_SECTION_ID" => $vendorSectionID,
                    );
                    if ($vendorID = $obElement->Add($arVendorFields)) {
                        $arVendorProperties = array(
                            "USER" => $arFields["ID"],
                            "UID" => md5($arFields["ID"])
                        );
                        \CIBlockElement::SetPropertyValuesEx($vendorID, false, $arVendorProperties);
                    }
                }
                /** Если пользователь создается в групе клиенты, то создаем профиль в инфоблоке Клиенты */
                if ($arGroup["GROUP_ID"] == USER_GROUP_CLIENTS) {
                    $fullName = trim($arFields["LAST_NAME"] . " " . $arFields["NAME"]);
                    if ($fullName == "")
                        $fullName = $arFields["LOGIN"];
                    $obSection = new \CIBlockSection;
                    $arClientSectionFields = array(
                        "IBLOCK_ID" => IBLOCK_CLIENTS,
                        "NAME" => $fullName,
                    );
                    $clientSectionID = $obSection->Add($arClientSectionFields);
                    if (!$clientSectionID) {
                        throw new \Exception($obSection->LAST_ERROR);
                    }

                    $obElement = new \CIBlockElement;
                    $arClientFields = array(
                        "IBLOCK_ID" => IBLOCK_CLIENTS,
                        "NAME" => $fullName,
                        "IBLOCK_SECTION_ID" => $clientSectionID,
                    );
                    if ($clientID = $obElement->Add($arClientFields)) {
                        $arClientProperties = array(
                            "USER" => $arFields["ID"],
                        );
                        \CIBlockElement::SetPropertyValuesEx($clientID, false, $arClientProperties);
                    }
                }
                /** Если пользователь создается в групе исполнители, то отправляем его Дистру, чтобы проверить */
                if ($arGroup["GROUP_ID"] == USER_GROUP_CONTRACTORS) {

                    $obContractor = new Contractor();
                    $obContractor->checkUserFromDistributor($arFields["LOGIN"], $arFields["ID"]);
                }
            }


            /*

            $arIntPartners = DataHelper::getAllIntegrationPartners();


            $rest = new Rest("","","https://electrograd.pro/local/interface/electricru/");
            $arData = [
                "action" => "checkUser",
                "phone" => "89090528720",
            ];
            foreach ($arIntPartners as $arIntPartner) {
                $rest->setHost($arIntPartner["URL"]);
                $rest->execute($arData);
                $arResult = $rest->getResult();
                $status = $rest->getStatusCode();
                if($status == 200):
                    if($arResult['exist']):
                        // Если пользователь существует у партнера - получаем его баллы
                        $arData = [
                            "action" => "getPoints",
                            "phone" => "89090528720",
                        ];
                        $rest->execute($arData);
                        $arResult = $rest->getResult();
                        var_dump($arResult);
                    endif;
                endif;
            }*/

        }

    }
}
