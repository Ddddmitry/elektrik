<?php
namespace Api\Components;

use Electric\Client;
use Electric\Component\AjaxComponent;
use Electric\Location;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;
use Electric\Order;

class ApiOrderAdd extends AjaxComponent
{
    private $requestData;
    private $orderFile;

    protected function parseRequest()
    {

        $this->requestData = $this->request->getPostList()->toArray();

    }

    /**
     * Добавление заявки
     *
     * @return mixed
     * @throws \Exception
     */
    protected function addOrder()
    {
        \CModule::IncludeModule('iblock');

        $this->checkRequiered();

        global $USER;
        $arOrderProperties = [];
        $obElement = new \CIBlockElement;
        $arOrderFields = Array(
            "IBLOCK_ID" => IBLOCK_ORDERS,
            "NAME" => $this->requestData["order-service-name"],
            "PREVIEW_TEXT" => $this->requestData["order-description"],
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
        );
        if ($arOrderId = $obElement->Add($arOrderFields)) {

            //todo: получать свойства инфблока с заявками и автоматом заполнять их, чтобы в коде компонента
            // не было прописанных свойств.

            if($USER->IsAuthorized())
                $arOrderProperties["USER"] = $USER->GetID();

            $arOrderProperties["STATUS"] = ENUM_VALUE_ORDERS_STATUS_NEW;
            $arOrderProperties["CITY_FIAS"] = $this->requestData["restricted"];
            $arOrderProperties["CITY"] = $this->requestData["restricted-name"];

            $arOrderProperties["STREET"] = $this->requestData["order-street"];
            $arOrderProperties["HOUSE"] = $this->requestData["order-house"];
            $arOrderProperties["FLAT"] = $this->requestData["order-flat"];
            $arOrderProperties["KORP"] = $this->requestData["order-korp"];
            $arOrderProperties["FLOOR"] = $this->requestData["order-floor"];
            $arOrderProperties["INTERCOM"] = $this->requestData["order-intercom"];

            $arOrderProperties["MY_SPARES"] = $this->requestData["order-my-spares"];
            $arOrderProperties["MY_TOOLS"] = $this->requestData["order-my-tools"];

            $arOrderProperties["WHEN"] = $this->requestData["order-when"];

            if($this->requestData["order-date"]){
                $timeTmp = $this->requestData["order-date"];
                if($this->requestData["order-time"]) $timeTmp .= " ".$this->requestData["order-time"] ?? " 00:00";
                $arOrderProperties["DATE_TIME"] = $timeTmp;
            }

            $arOrderProperties["CONTACT_INFO"] = $this->requestData["order-name"];
            $arOrderProperties["CONTACT_PHONE"] = $this->requestData["order-phone"];

            if($this->requestData["order-call-time"])
                $arOrderProperties["CALL_TIME"] = $this->requestData["order-call-time"];

            $arOrderProperties["CALL_ANYTIME"] = $this->requestData["order-call-anytime"];

            if($this->requestData["order-room"]){
                $arRoomIds = explode(",",$this->requestData["order-room"]);
                $arRooms = [];
                foreach ($arRoomIds as $id){
                    $arRooms[] = ["VALUE"=>$id];
                }
                $arOrderProperties["ROOM"] = $arRooms;
            }

            $orderFile = $this->request->getFile("order-file");

            $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/orders/";
            $file = file_get_contents($orderFile["tmp_name"]);
            file_put_contents($uploadPath . $orderFile["name"], $file);
            $arOrderProperties["PHOTOS"]["n0"] = \CFile::MakeFileArray($uploadPath . $orderFile["name"]);



            \CIBlockElement::SetPropertyValuesEx($arOrderId, false, $arOrderProperties,"NewElement");


            // Добавление уведомления
            /*User::addNotification([
                "TYPE" => "NEW_COMMENT",
                "AUTHOR" => $authorID,
                "ARTICLE" => $this->articleID,
            ]);*/

            /**
             * Добавляем заявку в миксер
            */
            $obOrder = new Order();
            $obOrder->addOrderToMixer($arOrderId, $arOrderProperties["CITY_FIAS"]);

            if($this->requestData["order-register"] == "Y"){
                $login = $this->requestData["order-phone"];
                $password = $this->requestData["order-password"];
                $name = $this->requestData["order-name"];

                $newUser = $this->registerUserRequest($login,$password,$name);
                \CIBlockElement::SetPropertyValuesEx($arOrderId, false, ["USER" => $newUser,"PHONE" => $login]);
            }

        } else {
            throw new \Exception($obElement->LAST_ERROR);
        }

        return ["order"=>$arOrderId];
    }

    protected function checkRequiered(){

        if(!$this->requestData["order-street"]){
            throw new \Exception("Не указана улица");
        }
        if(!$this->requestData["order-house"]){
            throw new \Exception("Не указан дом");
        }
        if(!$this->requestData["order-service-name"]){
            throw new \Exception("Не указана услуга");
        }
        if(!$this->requestData["order-name"]){
            throw new \Exception("Не указано имя");
        }
        if(!$this->requestData["order-phone"]){
            throw new \Exception("Не указан телефон");
        }

    }

    /**
     * Регистрация пользователя
     * как клиента
     *
     * @return mixed
     * @throws \Exception
     */
    protected function registerUserRequest($login, $password, $name)
    {
        global $USER;
        if (!is_object($USER)) {
            $USER = new \CUser;
        }
        $email = time()."_1@yahoo.com";
        $obUser = new \CUser;
        $arRegisterResult = $obUser->Register($login, false, false, $password, $password, $email);

        if ($arRegisterResult["TYPE"] == "OK") {

            $arUserGroups = array();

            $arName = explode(" ", trim($name));
            $obClient = new \CUser;
            $arClientFields = Array(
                "LAST_NAME" => $arName[0],
                "NAME" => $arName[1],
                "SECOND_NAME" => $arName[2],
            );
            $obClient->Update($arRegisterResult["ID"], $arClientFields);
            $arUserGroups[] = USER_GROUP_CLIENTS;
            \CUser::SetUserGroup($arRegisterResult["ID"], $arUserGroups);

            $obClient = new Client();
            $obClient->createProfile(["LAST_NAME"=>trim($name)],$arRegisterResult["ID"]);



        }

        return $arRegisterResult["ID"];
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->requestData;
            if (check_bitrix_sessid()){
                $result = $this->addOrder();
            }else{
                $result = ['error' => 'Sess id error'];
            }
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
