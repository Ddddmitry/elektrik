<?php

namespace Electric;

use Electric\Helpers\DataHelper;
use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Class Order
 * Класс, содержащий методы для работы с заявкой
 *
 * @package Electric
 */
class Order
{

    private $iblockOrders = IBLOCK_ORDERS;
    private $arStatus = [];
    private $currentUserID;

    public function __construct()
    {
        global $USER;
        $this->currentUserID = $USER->GetID();
        $this->collectOrderStatus();
    }

    public function getOrders($byCity = true){

        $arSelect = [
            'ID',
            'NAME',
            'CODE',
            'IBLOCK_ID',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'ACTIVE_FROM'
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->iblockOrders,
        ];

        if($byCity){
            // Фильтрация по местоположению исполнителя
            // (город, выбранный пользователем + улица, выбранная в фильтре)
            if ($this->currentUserID || $_COOKIE["BITRIX_SM_USER_CITY"]) {

                if ($this->currentUserID) {
                    $arUser = \CUser::GetByID($this->currentUserID)->Fetch();
                    $userCityID = $arUser["UF_LOCATION"];
                } else {
                    $userCityID = $_COOKIE["BITRIX_SM_USER_CITY"];
                }

                $arFilteredByLocation = $this->filterByMultipleProperty(PROPERTY_ORDERS_CITY_FIAS, $userCityID);

                if ($arFilter["ID"]) {
                    $arFilter["ID"] = array_intersect($arFilter["ID"], $arFilteredByLocation);
                } else {
                    $arFilter["ID"] = $arFilteredByLocation;
                }

            }
        }

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arOrder = $dbResult->GetNext()) {
            // Получение изображения
            if ($arOrder['PREVIEW_PICTURE']) {
                $arOrder['PREVIEW_PICTURE'] = \CFile::GetFileArray($arOrder['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arOrder["DATE"] = DataHelper::getFormattedDate($arOrder["ACTIVE_FROM"]);
            $arOrder["TIME"] = DataHelper::getFormattedDateSingle($arOrder["ACTIVE_FROM"],"H:i");

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arOrder['IBLOCK_ID'],
                $arOrder['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "PHOTOS") {
                    if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                        $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = [
                            "FULL" => $arPicture,
                            "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_EXACT, true),
                            "TITLE" => $arDescription[0],
                            "DESCRIPTION" => $arDescription[1],
                        ];
                    }
                }elseif ($arProperty["CODE"] == "VIDEOS") {
                    if($arProperty["VALUE"])
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUES"][] = $arProperty["VALUE"];
                }elseif($arProperty["CODE"] == "DATE_TIME"){
                    $arProperty["DATE"] = DataHelper::getFormattedDate($arProperty["VALUE"]);
                    $arProperty["TIME"] = DataHelper::getFormattedDateSingle($arProperty["VALUE"],"H:m");
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }else{
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }
            $arOrders[] = $arOrder;


        }


        return $arOrders;
    }

    public function getOrderById($id){

        if(!id){
            return false;
        }
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
            'IBLOCK_ID',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'ACTIVE_FROM'
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->iblockOrders,
            'ID' => $id
        ];


        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arOrder = $dbResult->GetNext()) {
            // Получение изображения
            if ($arOrder['PREVIEW_PICTURE']) {
                $arOrder['PREVIEW_PICTURE'] = \CFile::GetFileArray($arOrder['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arOrder["DATE"] = DataHelper::getFormattedDate($arOrder["ACTIVE_FROM"]);
            $arOrder["TIME"] = DataHelper::getFormattedDateSingle($arOrder["ACTIVE_FROM"],"H:i");

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arOrder['IBLOCK_ID'],
                $arOrder['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "PHOTOS") {
                    if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                        $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = [
                            "FULL" => $arPicture,
                            "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_EXACT, true),
                            "TITLE" => $arDescription[0],
                            "DESCRIPTION" => $arDescription[1],
                        ];
                    }
                }elseif ($arProperty["CODE"] == "VIDEOS") {
                    if($arProperty["VALUE"])
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUES"][] = $arProperty["VALUE"];
                }elseif($arProperty["CODE"] == "DATE_TIME"){
                    $arProperty["DATE"] = DataHelper::getFormattedDate($arProperty["VALUE"]);
                    $arProperty["TIME"] = DataHelper::getFormattedDateSingle($arProperty["VALUE"],"H:m");
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }else{
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }
            $arOrders = $arOrder;


        }


        return $arOrders;
    }

    /**
     * Получение заказов клиента
     * @param integer $userID
     * @return array
     */
    public function getClientsOrders($userID = null){

        if (!$userID) {
            if ($this->currentUserID) {
                $userID = $this->currentUserID;
            } else {
                return false;
            }
        }

        $arStatus = [];
        $arSelect = [
            'ID',
            'NAME',
            'CODE',
            'IBLOCK_ID',
            'PREVIEW_PICTURE',
            'PREVIEW_TEXT',
            'ACTIVE_FROM'
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->iblockOrders,
            'PROPERTY_USER' => $userID,
        ];

        $dbResult = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arOrder = $dbResult->GetNext()) {
            // Получение изображения
            if ($arOrder['PREVIEW_PICTURE']) {
                $arOrder['PREVIEW_PICTURE'] = \CFile::GetFileArray($arOrder['PREVIEW_PICTURE']);
            }

            // Получение даты
            $arOrder["DATE"] = DataHelper::getFormattedDate($arOrder["ACTIVE_FROM"]);
            $arOrder["TIME"] = DataHelper::getFormattedDateSingle($arOrder["ACTIVE_FROM"],"H:m");

            // Получение пользовательских свойств
            $dbProperty = \CIBlockElement::getProperty(
                $arOrder['IBLOCK_ID'],
                $arOrder['ID']
            );
            while($arProperty = $dbProperty->Fetch()){
                if ($arProperty["CODE"] == "PHOTOS") {
                    if ($arPicture = \CFile::GetFileArray($arProperty["VALUE"])) {
                        $arDescription = explode("||", $arPicture["DESCRIPTION"]);
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUE"][] = [
                            "FULL" => $arPicture,
                            "PREVIEW" => \CFile::ResizeImageGet($arPicture, ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_EXACT, true),
                            "TITLE" => $arDescription[0],
                            "DESCRIPTION" => $arDescription[1],
                        ];
                    }
                }
                elseif ($arProperty["CODE"] == "VIDEOS") {
                    if($arProperty["VALUE"])
                        $arOrder['PROPERTIES'][$arProperty["CODE"]]["VALUES"][] = $arProperty["VALUE"];
                }
                elseif($arProperty["CODE"] == "DATE_TIME"){
                    $arProperty["DATE"] = DataHelper::getFormattedDate($arProperty["VALUE"]);
                    $arProperty["TIME"] = DataHelper::getFormattedDateSingle($arProperty["VALUE"],"H:m");
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
                elseif($arProperty["CODE"] == "STATUS"){
                    $arStatus[$arProperty["VALUE_SORT"]] = ["NAME" => $arProperty["VALUE_ENUM"], "CODE" => $arProperty["VALUE_XML_ID"]];
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }else{
                    $arOrder['PROPERTIES'][$arProperty["CODE"]] = $arProperty;
                }
            }


            $arOrders[] = $arOrder;
        }

        $arResult["ORDERS"] = $arOrders;
        ksort($arStatus);
        $arResult["STATUS"] = $arStatus;
        return $arResult;
    }

    private function filterByMultipleProperty($propertyID, $filterValue) {
        global $DB;

        $query = "
            SELECT DISTINCT
                `iblock_element_property`.`IBLOCK_ELEMENT_ID` AS `IBLOCK_ELEMENT_ID`
            FROM `b_iblock_element_property` `iblock_element_property` 
            
            WHERE 
                `iblock_element_property`.`IBLOCK_PROPERTY_ID` = ".$propertyID."
                AND
                `iblock_element_property`.`VALUE` = '".$filterValue."'
            ";
        $rsQueryResult = $DB->Query($query, false, "");
        $arQueryResult = [];
        while ($arRow = $rsQueryResult->fetch()) {
            $arQueryResult[] = $arRow["IBLOCK_ELEMENT_ID"];
        }

        return $arQueryResult;
    }

    /**
     * Получение заказов исполнителя по статусам
     * @return array
     */
    public function getContractorOrder(){

        $arResult = [];

        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_CONTACTOR_ORDER]);

        $rsData = $obHLBLOCK["CONTRACTORS_ORDER"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_USER" => $this->currentUserID],
                "order" => [],
            ]
        );

        while ($arItem = $rsData->Fetch()) {
            $arResult[] = [
                "USER" => $arItem["UF_USER"],
                "ORDER" => $arItem["UF_ORDER"],
                "STATUS" => $arItem["UF_STATUS"],
            ];
        }

        return $arResult;
    }

    /**
     * Собираем статусы
     * @return array
     */
    public function collectOrderStatus(){

        if(\CModule::IncludeModule("iblock"))
        {
            $obProp = \CIBlockProperty::GetPropertyEnum("STATUS", ["SORT"=>"ASC"], ["IBLOCK_ID"=>IBLOCK_ORDERS]);
            while($arProp = $obProp->GetNext())
            {
                $this->arStatus[$arProp["XML_ID"]] = $arProp;
            }
        }

    }

    /**
     * Возвращает список статусов
     * @return array
     */
    public function getArStatus(){
        return $this->arStatus;
    }

    /**
     * Получение новых заказов для исполнителя
     * @return array
     */
    public function getNewOrders(){

        $arResult = [];

        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_NEW_ORDERS]);

        $rsData = $obHLBLOCK["NEW_ORDERS"]::getList(
            [
                "select" => ['*'],
                "filter" => ["UF_USER" => $this->currentUserID],
                "order" => [],
            ]
        );

        while ($arItem = $rsData->Fetch()) {
            $arResult[] = $arItem["UF_ORDER"];
        }

        return $arResult;
    }

    /**
     * Принимаем заказ в работу, устанавливаем исполнителя и переводим в статус "В работе"
     *
     * @param $arFields
     * @return bool
     */
    public function applyOrder($arFields){

        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["USER_CONTRACTOR" => $arFields["CONTRACTOR"]]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["STATUS" => ENUM_VALUE_ORDERS_STATUS_INWORK]);

        if($this->addToOrdersTable($arFields)){
            $this->removeFromQueue($arFields["ORDER"]);
        }

        return true;
    }

    /**
     * Метод добавляет в таблицу историю заказов
     * @param $arFields
     * @return mixed
     */
    protected function addToOrdersTable($arFields){

        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_CONTACTOR_ORDER]);

        $rsData = $obHLBLOCK["CONTRACTORS_ORDER"]::add(
            [
                "UF_USER" => $arFields["USER"],
                "UF_ORDER" => $arFields["ORDER"],
                "UF_STATUS" => $arFields["STATUS"]
            ]
        );
        return $rsData->isSuccess();

    }

    /**
     * Удаляем из таблицы очередей заказ, который приняли
     * @param $orderID
     * @return bool
     */
    protected function removeFromQueue($orderID){

        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_NEW_ORDERS]);
        $result = false;
        $rsData = $obHLBLOCK["NEW_ORDERS"]::getList(
            [
                "select" => ['*'],
                "filter" => ['UF_ORDER' => $orderID],
                "order" => ["ID" => "ASC"],
            ]
        )->fetch();
        if($rsData["ID"]):
            $result = $obHLBLOCK["NEW_ORDERS"]::delete($rsData["ID"]);
        endif;

        return $result;


    }

    /**
     * Переводим заказ в статус "Оставить отзыв"
     *
     * @param $arFields
     * @return bool
     */
    public function stayReviewOrder($arFields){
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["STATUS" => ENUM_VALUE_ORDERS_STATUS_STAY_REVIEW]);
        return true;
    }

    /**
     * Завершаем заказ, переводим в статус "Завершена"
     *
     * @param $arFields
     * @return bool
     */
    public function completeOrder($arFields){

        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["STATUS" => ENUM_VALUE_ORDERS_STATUS_COMPLETED]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["CONTACTOR_REVIEW" => $arFields["REVIEW"]]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["MARK" => $arFields["MARK"]]);

        $this->addToOrdersTable($arFields);

        return true;
    }

    /**
     * Отменяем заказ, переводим в статус "Отклонена"
     *
     * @param $arFields
     * @return bool
     */
    public function cancelOrder($arFields){

        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["STATUS" => ENUM_VALUE_ORDERS_STATUS_CANCELED]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["USER_CONTRACTOR" => $arFields["CONTRACTOR"]]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["CANCEL_REASONS" => $arFields["CANCEL_REASONS"]]);
        \CIBlockElement::SetPropertyValuesEx($arFields["ORDER"], false, ["CANCEL_REASONS_DESCRIPTION" => $arFields["CANCEL_REASONS_DESCRIPTION"]]);

        if($this->addToOrdersTable($arFields)){
            $this->removeFromQueue($arFields["ORDER"]);
        };

        return true;
    }

    /**
     * Добавление заявки в миксер
     * @param $orderID
     * @param null $city_fias
     * @return bool
     */
    public function addOrderToMixer($orderID, $city_fias = null){

        $obContractor = new Contractor();
        $arContractorsQueue = $obContractor->getQueue($city_fias);

        $firstContractor = array_shift($arContractorsQueue);
        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_NEW_ORDERS,HLBLOCK_CONTACTOR_ORDER]);

        $rsData = $obHLBLOCK["NEW_ORDERS"]::add(
            [
                "UF_USER" => $firstContractor,
                "UF_ORDER" => $orderID,
                "UF_TIME" => time(),
                "UF_QUEUE" => implode(',',$arContractorsQueue),
                ]
        );
        $obHLBLOCK["CONTRACTORS_ORDER"]::add([
            "UF_USER" => $firstContractor,
            "UF_ORDER" => $orderID,
            "UF_STATUS" => "new"
        ]);

        return $rsData->isSuccess();

    }

    /**
     * АГЕНТ!!!!
     * Метод запускается по крону, для проверки заявок в миксере
     * чтобы по истечении времени изменялся исполнитель заявки.
     *
     * @return bool
     */
    function updateOrdersInMixer(){

        $obHLBLOCK = self::getHLDataClasses([HLBLOCK_NEW_ORDERS,HLBLOCK_CONTACTOR_ORDER]);
        $curTime = time();
        $timeInQueue = TIME_IN_QUEUE * 60;
        $rsData = $obHLBLOCK["NEW_ORDERS"]::getList(
            [
                "select" => ['*'],
                "filter" => [],
                "order" => ["ID" => "ASC"],
            ]
        );

        while ($arItem = $rsData->Fetch()) {
            if(($curTime - $arItem["UF_TIME"]) >= $timeInQueue){
                $arQueue = [];
                if(strlen(trim($arItem["UF_QUEUE"])) > 0){
                    $arQueue = explode(",",trim($arItem["UF_QUEUE"]));
                }
                if(count($arQueue) > 0){
                    $newContractor = array_shift($arQueue);
                    $resUpdate = $obHLBLOCK["NEW_ORDERS"]::update($arItem["ID"],
                        [
                            "UF_USER" => $newContractor,
                            "UF_QUEUE" => implode(",",$arQueue),
                            "UF_TIME" => $arItem["UF_TIME"] + $timeInQueue
                        ]);
                }
                else{
                    try{
                        if(\CModule::IncludeModule("iblock")){
                            \CIBlockElement::SetPropertyValuesEx($arItem["UF_ORDER"], false, ["STATUS" => ENUM_VALUE_ORDERS_STATUS_SKIPPED]);
                            $resDelete = $obHLBLOCK["NEW_ORDERS"]::delete($arItem["ID"]);
                            //todo: при пропуске всех исполнителей, необходимо уведомлять админа об этом случае.
                        }
                    }catch (\Exception $e){
                        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
                        AddMessage2Log("Ошибка заказа: ".$arItem["UF_ORDER"]." \t".$e->getMessage(), "my_module_id");
                        return "\Electric\Order::updateOrdersInMixer();";
                    }

                }
                if($arItem["UF_USER"] && $arItem["UF_ORDER"]){
                    //Прописываем пользователю пропуск заявки
                    $obHLBLOCK["CONTRACTORS_ORDER"]::add([
                        "UF_USER" => $arItem["UF_USER"],
                        "UF_ORDER" => $arItem["UF_ORDER"],
                        "UF_STATUS" => "skipped"
                    ]);
                }

            }
        }
        return "\Electric\Order::updateOrdersInMixer();";

    }

    /**
     * Подключение классов для работы с сущностями D7
     *
     * @param array $arList - массив ID нужных HL-блоков
     *
     * @return array
     */
    public static function getHLDataClasses($arList) {
        \CModule::IncludeModule("highloadblock");

        $arDataClasses = [];

        if (in_array(HLBLOCK_CONTACTOR_ORDER, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_CONTACTOR_ORDER)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["CONTRACTORS_ORDER"] = $obEntity->getDataClass();
        }
        if (in_array(HLBLOCK_NEW_ORDERS, $arList)) {
            $arHighloadblock = HighloadBlockTable::getById(HLBLOCK_NEW_ORDERS)->fetch();
            $obEntity = HighloadBlockTable::compileEntity($arHighloadblock);
            $arDataClasses["NEW_ORDERS"] = $obEntity->getDataClass();
        }


        return $arDataClasses;

    }

}
