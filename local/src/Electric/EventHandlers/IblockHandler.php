<?php

namespace Electric\EventHandlers;

use Electric\Contractor;
use Electric\User;
use Electric\Notification;
use Symfony\Component\HttpFoundation\ParameterBag;

class IblockHandler
{

    /**
     * Обработчики событий после обновления элементов инфоблока
     *
     * @param $arFields
     *
     * @return bool
     */
    function OnAfterIBlockElementUpdateHandler($arFields)
    {
        // Пересчёт рейтинга исполнителя после изменения отзыва
        if ($arFields["IBLOCK_ID"] == IBLOCK_REVIEWS) {
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_REVIEWS,
                'ID' => $arFields["ID"],
            ];
            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_USER',
            ];
            $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            if ($arReview = $dbResult->Fetch()) {
                $userID = $arReview["PROPERTY_USER_VALUE"];
                $obContractor = new Contractor();
                $rating = $obContractor->getRating($userID);
                try {
                    $obContractor->saveRating($userID, $rating);
                } catch (\Exception $e) {

                    return false;
                }
            };
        }

        return true;
    }

    /**
     * Обработчики событий после добавления элементов инфоблока
     *
     * @param $arFields
     *
     * @return bool
     */
    function OnAfterIBlockElementAddHandler($arFields){

        // Пересчёт количества заказов по определённой услуге
        if ($arFields["IBLOCK_ID"] == IBLOCK_ORDERS){
            $arFilter = [
                'IBLOCK_ID' => IBLOCK_SERVICES,
                'NAME' => $arFields["NAME"],
            ];
            $arSelect = [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_COUNT_ORDERS',
            ];
            $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            if ($arService = $dbResult->Fetch()) {
                $newCount = $arService["PROPERTY_COUNT_ORDERS_VALUE"] + 1;
                \CIBlockElement::SetPropertyValuesEx($arService["ID"], false, ["COUNT_ORDERS" => $newCount]);
            };
        }

    }

    /**
     * Обработчики событий перед обновлением элементов инфоблока
     *
     * @param $arFields
     *
     * @return bool
     */
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        // Отправка исполнителю почтового уведомления / добавление уведомления о новом
        // отзыве после его активации модератором
        if ($arFields["IBLOCK_ID"] == IBLOCK_REVIEWS) {

            if ($arFields["ACTIVE"] == "Y" && !$arFields["PROPERTY_VALUES"][PROPERTY_REVIEWS_NOTIFICATION_SENT]) {

                if ($arFields["PROPERTY_VALUES"]) {
                    $userID = current($arFields["PROPERTY_VALUES"][PROPERTY_REVIEWS_USER])["VALUE"];
                    $authorID = current($arFields["PROPERTY_VALUES"][PROPERTY_REVIEWS_AUTHOR])["VALUE"];
                } else {

                    $arFilter = [
                        "IBLOCK_ID" => IBLOCK_REVIEWS,
                        "ID" => $arFields["ID"],
                    ];
                    $arSelect = [
                        "ID",
                        "IBLOCK_ID",
                        "PROPERTY_USER",
                        "PROPERTY_AUTHOR",
                    ];
                    $arReview = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->Fetch();
                    $userID = $arReview["PROPERTY_USER_VALUE"];
                    $authorID = $arReview["PROPERTY_AUTHOR_VALUE"];
                }

                // Добавление уведомления
                User::addNotification([
                      "USER" => $userID,
                      "TYPE" => "NEW_REVIEW",
                      "AUTHOR" => $authorID,
                ]);

                $obUser = new User;
                $obContractor = new Contractor();
                $arMailData = [
                    "USER_EMAIL" => $obUser->getEmail($userID),
                    "CONTRACTOR_CODE" => $obContractor->getActualContractor($userID, ["CODE"])["CODE"],
                ];
                Notification::sendNotification("ELECTRIC_NEW_REVIEW", $arMailData);

                if (isset($arFields["PROPERTY_VALUES"])) {
                    $arFields["PROPERTY_VALUES"][PROPERTY_REVIEWS_NOTIFICATION_SENT] = [
                        0 => [
                            "VALUE" => ENUM_VALUE_REVIEWS_NOTIFICATION_SENT,
                        ],
                    ];
                } else {
                    \CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, ["NOTIFICATION_SENT" => ENUM_VALUE_REVIEWS_NOTIFICATION_SENT]);
                }

            }
        }


        // Модерация изменений профиля

        if ($arFields["IBLOCK_ID"] == IBLOCK_CONTRACTORS) {

            if ($arFields["ACTIVE"] == "Y") {

                if ($arFields["PROPERTY_VALUES"]) {
                    $isDraft = current($arFields["PROPERTY_VALUES"][PROPERTY_CONTRACTORS_IS_DRAFT]);
                    $userID = current($arFields["PROPERTY_VALUES"][PROPERTY_CONTRACTORS_USER])["VALUE"];
                    $name = $arFields["NAME"];
                    $code = $arFields["CODE"];
                    $contractorType = current($arFields["PROPERTY_VALUES"][PROPERTY_CONTRACTORS_TYPE])["VALUE"];
                } else {

                    $arFields["ACTIVE"] = "N";

                    /*
                    $arFilter = [
                        "IBLOCK_ID" => IBLOCK_CONTRACTORS,
                        "ID" => $arFields["ID"],
                    ];
                    $arSelect = [
                        "ID",
                        "IBLOCK_ID",
                        "NAME",
                        "CODE",
                        "PROPERTY_USER",
                        "PROPERTY_IS_DRAFT",
                        "PROPERTY_TYPE",
                    ];
                    $arContractor = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->Fetch();
                    $userID = $arContractor["PROPERTY_USER_VALUE"];
                    $name = $arContractor["NAME"];
                    $code = $arContractor["CODE"];
                    $isDraft = $arContractor["PROPERTY_IS_DRAFT_ENUM_ID"];
                    $contractorType = $arContractor["PROPERTY_TYPE_ENUM_ID"];
                    */
                }

                if ($isDraft) {

                    $arFields["PROPERTY_VALUES"][PROPERTY_CONTRACTORS_IS_DRAFT] = false;

                    $obContractor = new Contractor();
                    $actualElementID = $obContractor->getContractorID($userID);
                    $actualName = \CIBlockElement::GetByID($actualElementID)->Fetch()["NAME"];

                    if ($name !== $actualName) {

                        $obUser = new \CUser;
                        if ($contractorType == ENUM_VALUE_CONTRACTORS_TYPE_LEGAL) {
                            $arNameFields = Array(
                                "LAST_NAME" => "",
                                "NAME" => $name,
                                "SECOND_NAME" => "",
                            );
                        } else {
                            $arName = explode(" ", $name);
                            $arNameFields = Array(
                                "LAST_NAME" => $arName[0],
                                "NAME" => $arName[1],
                                "SECOND_NAME" => $arName[2],
                            );
                        }

                        $obUser->Update($userID, $arNameFields);
                    }

                    // Добавление уведомления
                    User::addNotification(
                        [
                            "USER" => $userID,
                            "TYPE" => "PROFILE_MODERATION",
                            "CODE" => $code,
                        ]);


                    \CIBlockElement::Delete($actualElementID);
                }

            }

        }



        return true;
    }


    /**
     * Обработчики событий удаления элементов инфоблока
     *
     * @param $arFields
     *
     * @return bool
     */
    function OnBeforeIBlockElementDeleteHandler($reviewID)
    {
        // Пересчёт рейтинга исполнителя после удаления активного отзыва.
        $arFilter = [
            'IBLOCK_ID' => IBLOCK_REVIEWS,
            'ACTIVE' => 'Y',
            'ID' => $reviewID,
        ];
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_USER',
        ];
        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($arReview = $dbResult->Fetch()) {
            $userID = $arReview["PROPERTY_USER_VALUE"];
            $obElement = new \CIBlockElement;
            $obElement->Update($arReview["ID"], ["ACTIVE" => "N"]);
            $obContractor = new Contractor();
            $rating = $obContractor->getRating($userID);
            try {
                $obContractor->saveRating($userID, $rating);
            } catch (\Exception $e) {

                return true;
            }
        };

        return true;
    }

}
