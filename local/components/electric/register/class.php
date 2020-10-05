<?php
namespace Electric\Component;

use Electric\Client;
use Electric\Contractor;
use Electric\Component\BaseComponent;
use Electric\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class UserRegister extends BaseComponent
{
    private $input;

    public function executeComponent()
    {
        $obRequest = $this->getRequest();
        $this->input = $obRequest->getQueryList();

        global $USER;

        $userID = $this->input["user-id"];
        $userHash = $this->input["hash"];
        $userType = $this->input["user-type"];


        // Функционал регистрации через социальные сервисы
        if ($USER->IsAuthorized() && $socialUserType = $_COOKIE["social-user-type"]) {
            $userID = $USER->GetID();
            $arUser = \CUser::GetByID($userID)->Fetch();
            $userHash = $arUser["PASSWORD"];

            if ($socialUserType === "#contractor") {
                $userType = "contractor";
                \CUser::SetUserGroup($userID, [USER_GROUP_CONTRACTORS]);
            }
            if ($socialUserType === "#client") {
                $userType = "client";
                \CUser::SetUserGroup($userID, [USER_GROUP_CLIENTS]);
            }
        }
        unset($_COOKIE["social-user-type"]);
        setcookie("social-user-type", null, -1);

        if ($this->input["confirm_registration"]) { // Подтверждение регистрации
            $this->arResult["CONFIRM"] = true;
            $arUser = \CUser::GetByID($this->input["confirm_user_id"])->Fetch();
            if ($arUser["CONFIRM_CODE"] == $this->input["confirm_code"]) {
                $obUser = new \CUser;
                $obUser->Update($this->input["confirm_user_id"], ["ACTIVE" => "Y"]);
                $this->arResult["CONFIRM_SUCCESS"] = true;
                $this->arResult["CONFIRM_MESSAGE"] = "Регистрация пользователя успешно подтверждена!";
                $USER->Authorize($arUser["ID"]);
                LocalRedirect("/backoffice/");

            } else {
                $this->arResult["CONFIRM_SUCCESS"] = false;
                $this->arResult["CONFIRM_MESSAGE"] = "Неверный код подтверждения регистрации!";
            }
        } elseif ($userType) {
            if ($userType == "contractor") {
                $obContractor = new Contractor();
                if ($obContractor->getContractorID($userID)) {
                    // Если нашли анкету исполнителя, то отправляем его в кабинет
                    LocalRedirect("/backoffice/");
                } else {
                    // Если нет, то Дозаполненение анкеты
                    $this->arResult["CONTRACTOR_REGISTRATION"] = true;
                    $this->arResult["USER"] = $userID;
                    $this->arResult["HASH"] = $userHash;
                    $obUser = new User();
                    $arUser = $obUser->getClientData();
                    $this->arResult["PHONE"] = $arUser["PHONE"];
                }
            }elseif ($userType == "client") {
                $obClient = new Client();
                if($obClient->getClientID($userID)){
                    LocalRedirect("/backoffice/");
                }else{
                    $arFields = [];
                    $arFields["ID"] = $USER->GetID();
                    $arFields["LAST_NAME"] = $USER->GetLastName();
                    $arFields["NAME"] = $USER->GetFirstName();
                    if($obClient->createProfile($arFields)){
                        LocalRedirect("/backoffice/clients/?soc-reg=Y");
                    }
                }
            } else {
                LocalRedirect("/");
            }
        }

        $this->includeComponentTemplate();
    }
}
