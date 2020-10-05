<?php
namespace Api\Components;

use Electric\Client;
use \Electric\Component\AjaxComponent;
use \Electric\Contractor;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Location;

class ApiUserRegisterRequest extends AjaxComponent
{
    protected $input;
    private $name, $login, $password, $type, $phone;
    private $infoType;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();

        $this->name = $this->input['registration-name'];
        $this->login = $this->input['registration-email'];
        $this->phone = $this->input['registration-phone'];
        $this->password = $this->input['registration-password'];
        $this->type = $this->input['registration-type'];
        $this->infoType = $this->input["registration-info-type"];
    }

    /**
     * Регистрация пользователя
     *
     * @return mixed
     * @throws \Exception
     */
    protected function registerUserRequest()
    {
        global $USER;
        if (!is_object($USER)) {
            $USER = new \CUser;
        }

        $arResult["PHONE_REGISTRATION"] = (\COption::GetOptionString("main", "new_user_phone_auth", "N") == "Y");
        $arResult["PHONE_REQUIRED"] = ($arResult["PHONE_REGISTRATION"] && \COption::GetOptionString("main", "new_user_phone_required", "N") == "Y");
        $arResult["EMAIL_REGISTRATION"] = (\COption::GetOptionString("main", "new_user_email_auth", "Y") <> "N");
        $arResult["EMAIL_REQUIRED"] = ($arResult["EMAIL_REGISTRATION"] && \COption::GetOptionString("main", "new_user_email_required", "Y") <> "N");
        $arResult["USE_EMAIL_CONFIRMATION"] = (\COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y" && $arResult["EMAIL_REQUIRED"]? "Y" : "N");
        $arResult["PHONE_CODE_RESEND_INTERVAL"] = \CUser::PHONE_CODE_RESEND_INTERVAL;

        // start values
        $arResult["VALUES"] = [];
        $arResult["ERRORS"] = [];
        $arResult["SHOW_SMS_FIELD"] = false;
        $register_done = false;
        $arRegisterResult = [];

        // Register user
        if ($this->input["register_submit_button"] <> '' && !$USER->IsAuthorized()){


            $bConfirmReq = ($arResult["USE_EMAIL_CONFIRMATION"] === "Y");
            $active = ($bConfirmReq || $arResult["PHONE_REQUIRED"]? "N": "Y");

            $arResult['VALUES']["CHECKWORD"] = md5(\CMain::GetServerUniqID().uniqid());
            $arResult['VALUES']["~CHECKWORD_TIME"] = \CDatabase::CurrentTimeFunction();
            $arResult['VALUES']["ACTIVE"] = $active;
            $arResult['VALUES']["CONFIRM_CODE"] =  ($bConfirmReq? randString(8): "");
            $arResult['VALUES']["LID"] = SITE_ID;
            $arResult['VALUES']["LANGUAGE_ID"] = LANGUAGE_ID;

            $arResult['VALUES']["LOGIN"] = $this->phone;
            $arResult['VALUES']["PASSWORD"] = $this->password;
            $arResult['VALUES']["PHONE_NUMBER"] = $this->phone;
            $arResult['VALUES']["PERSONAL_PHONE"] = $this->phone;

            $arResult['VALUES']["USER_IP"] = $_SERVER["REMOTE_ADDR"];
            $arResult['VALUES']["USER_HOST"] = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

            $bOk = true;

            $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
            foreach($events as $arEvent)
            {
                if(ExecuteModuleEventEx($arEvent, array(&$arResult['VALUES'])) === false)
                {
                    $bOk = false;
                    break;
                }
            }

            $ID = 0;
            $user = new \CUser();
            $ID = $user->Add($arResult["VALUES"]);

            if (intval($ID) > 0){
                if($arResult["PHONE_REGISTRATION"] == true && $arResult['VALUES']["PHONE_NUMBER"] <> '')
                {
                    //added the phone number for the user, now sending a confirmation SMS
                    list($code, $phoneNumber) = \CUser::GeneratePhoneCode($ID);

                    $sms = new \Bitrix\Main\Sms\Event(
                        "SMS_USER_CONFIRM_NUMBER",
                        [
                            "USER_PHONE" => $phoneNumber,
                            "CODE" => $code,
                        ]
                    );
                    $smsResult = $sms->send(true);

                    if(!$smsResult->isSuccess())
                    {
                        $arRegisterResult["ERRORS"] = array_merge($arResult["ERRORS"], $smsResult->getErrorMessages());
                    }

                    $arRegisterResult["TYPE"] = $this->type;
                    $arRegisterResult["NAME"] = $this->name;
                    $arRegisterResult["SHOW_SMS_FIELD"] = true;
                    $arRegisterResult["SIGNED_DATA"] = \Bitrix\Main\Controller\PhoneAuth::signData(['phoneNumber' => $phoneNumber]);
                }

            }else{
                throw new \Exception("Ошибка: ".$user->LAST_ERROR);
            }
        }
        // verify phone code
        if ($this->input["code_submit_button"] <> '' && !$USER->IsAuthorized())
        {
            if($this->input["SIGNED_DATA"] <> '')
            {
                if(($params = \Bitrix\Main\Controller\PhoneAuth::extractData($this->input["SIGNED_DATA"])) !== false)
                {
                    if(($userId = \CUser::VerifyPhoneCode($params['phoneNumber'], $this->input["SMS_CODE"])))
                    {
                        $register_done = true;

                        if($arResult["PHONE_REQUIRED"])
                        {
                            //the user was added as inactive, now phone number is confirmed, activate them
                            $user = new \CUser();
                            $user->Update($userId, ["ACTIVE" => "Y"]);
                        }

                        //here should be login
                        $USER->Authorize($userId);

                        $arRegisterResult["TYPE"] = "OK";
                        $arRegisterResult["ID"] = $userId;
                        $arRegisterResult["CONTINUE"] = true;

                    }
                    else
                    {
                        $arRegisterResult["ERRORS"][] = GetMessage("main_register_error_sms");
                        $arRegisterResult["SHOW_SMS_FIELD"] = true;
                        $arRegisterResult["SMS_CODE"] = $this->input["SMS_CODE"];
                        $arRegisterResult["SIGNED_DATA"] = $this->input["SIGNED_DATA"];
                    }
                }
            }
        }


        if($arRegisterResult["CONTINUE"]){
            if ($arRegisterResult["TYPE"] == "OK") {

                $arUserGroups = [];
                switch ($this->type):
                    case "client":
                        $arName = explode(" ", trim($this->name));
                        $obUser = new \CUser;
                        $arUserFields = Array(
                            "LAST_NAME" => $arName[0],
                            "NAME" => $arName[1],
                            "SECOND_NAME" => $arName[2],
                        );
                        $obUser->Update($arRegisterResult["ID"], $arUserFields);
                        $arUserGroups[] = USER_GROUP_CLIENTS;
                        try {

                            $obClient = new Client();
                            $obClient->createProfile(["LAST_NAME"=>trim($this->name)],$arRegisterResult["ID"]);
                            //$result = $this->createClientProfile($arRegisterResult["ID"]);
                        } catch (\Exception $e) {
                            $this->addResponseData(['error' => $e->getMessage()]);
                            return false;
                        }

                        break;
                    case "contractor":
                        $obContractor= new \CUser;
                        $arContractorFields = Array(
                            "UF_RECOMMENDED" => false,
                        );
                        $obContractor->Update($arRegisterResult["ID"], $arContractorFields);
                        $arUserGroups[] = USER_GROUP_CONTRACTORS;
                        break;
                    default:
                        $arUserGroups[] = USER_GROUP_CLIENTS;
                        break;
                endswitch;

                \CUser::SetUserGroup($arRegisterResult["ID"], $arUserGroups);

                $arUser = \CUser::GetByID($arRegisterResult["ID"])->Fetch();
                $arRegisterResult["USER_TYPE"] = $this->type;
                $arRegisterResult["HASH"] = $arUser["PASSWORD"];

                $arRegisterResult["MESSAGE"] = [
                    "TITLE" => "Регистрация прошла успешено",
                    /*"TEXT" => "<p>На вашу почту <b>".$this->login."</b> отправлено письмо со ссылкой на подтверждение.</p>".
                        "<p>Если письмо не приходит, то возможно, оно попало в папку «Спам» или ваша почта указана неверно.</p>"*/
                    "TEXT" => "<p>Можете перейти в <a href='/backoffice/'>личный кабинет</a></p>"
                ];

            } else {
                $message = $arRegisterResult['MESSAGE'];
                if (strpos($arRegisterResult['MESSAGE'], "уже существует") != false) {
                    $message .= "Хотите <a href=\"/auth/\">авторизоваться</a> или <a href=\"/auth/restore/\">восстановить пароль</a>?<br>";
                }

                throw new \Exception($message);
            }
        }




        return $arRegisterResult;
    }


    /**
     * Создание профиля исполнителя
     *
     * @param $userID
     *
     * @return mixed
     * @throws \Exception
     */
    protected function registerContractorRequest($userID)
    {
        global $USER;
        \CModule::IncludeModule('iblock');

        if ($this->input["registration-info-name"]) {
            $fullName = trim($this->input["registration-info-name"]);
        } else {
            throw new \Exception("Не заполнено ФИО исполнителя");
        }

        $obSection = new \CIBlockSection;
        $arContractorSectionFields = Array(
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "NAME" => $fullName,
        );
        $contractorSectionID = $obSection->Add($arContractorSectionFields);
        if (!$contractorSectionID) {
            throw new \Exception($obSection->LAST_ERROR);
        }

        $arUser = \CUser::GetByID($userID)->Fetch();

        $obElement = new \CIBlockElement;
        $arContractorFields = Array(
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "NAME" => $fullName,
            "PREVIEW_PICTURE" => $this->request->getFile("registration-info-photo"),
            "IBLOCK_SECTION_ID" => $contractorSectionID,
        );
        if ($contractorID = $obElement->Add($arContractorFields)) {
            $arRegisterInfoResult["ID"] = $contractorID;
            $arContractorProperties = array(
                "USER" => $userID,
                "TYPE" => ($this->infoType == "legal") ? ENUM_VALUE_CONTRACTORS_TYPE_LEGAL : ENUM_VALUE_CONTRACTORS_TYPE_INDIVIDUAL,
                "PHONE" => $this->input["registration-info-phone"],
                "ADDRESS" => $this->input["registration-info-address"],
                "SITE" => $this->input["registration-info-site"],
                "EMAIL" => $arUser["EMAIL"],
            );

            // Заполнение координат исполнителя
            if ($arContractorProperties["ADDRESS"]) {
                $arContractorProperties["ADDRESS_COORDS"] = Location::getCoordsByAddress($arContractorProperties["ADDRESS"]);
            }

            \CIBlockElement::SetPropertyValuesEx($contractorID, false, $arContractorProperties);

            // Назначение имени и фамилии пользователю исполнителя
            $obContractorUser = new \CUser;
            if ($this->infoType == "legal") {
                $arContractorUserFields = Array(
                    "LAST_NAME" => "",
                    "NAME" => $fullName,
                    "SECOND_NAME" => "",
                );
            } else {
                $arName = explode(" ", $fullName);
                $arContractorUserFields = Array(
                    "LAST_NAME" => $arName[0],
                    "NAME" => $arName[1] ? $arName[1] : "",
                    "SECOND_NAME" => $arName[2] ? $arName[2] : "",
                );
            }

            $obContractorUser->Update($userID, $arContractorUserFields);

            // Назначение символьного кода созданному элементу
            $obElement->Update($contractorID, array("CODE" => \Cutil::translit($fullName . "_" . $contractorID, "ru")));

            if ($USER->IsAuthorized()) {
                $arRegisterInfoResult["ACTIVATED"] = true;
                $arRegisterInfoResult["MESSAGE"] = [
                    "TITLE" => "Спасибо за регистрацию!",
                    "TEXT" => "<p>Вы успешно зарегистированы и авторизованы.</p>".
                              "<br><a href=\"/backoffice/\" class=\"button2\">Заполнить анкету исполнителя</a>",
                ];
            } else {
                $arRegisterInfoResult["ACTIVATED"] = false;
                $arUser = \CUser::GetByID($userID)->Fetch();
                $arRegisterInfoResult["MESSAGE"] = [
                    "TITLE" => "Подтвердите свою почту",
                    "TEXT" => "<p>На вашу почту <b>".$arUser["LOGIN"]."</b> отправлено письмо со ссылкой на подтверждение.</p>".
                              "<p>Если письмо не приходит, то возможно, оно попало в папку «Спам» или ваша почта указана неверно.</p>"
                ];
            }

        } else {
            throw new \Exception($obElement->LAST_ERROR);
        }

        return $arRegisterInfoResult;
    }

    private function checkPermission() {
        if ($this->input["registration-info-user"] && $this->input["registration-info-hash"]) {
            $userID = $this->input["registration-info-user"];
            $hash = $this->input["registration-info-hash"];
            $arUser = \CUser::GetByID($userID)->Fetch();
            $hashPassword = $arUser['PASSWORD'];
            if ($hash == $hashPassword) {

                return true;
            }
        }

        return false;
    }

    protected function executeAjaxComponent()
    {
        global $USER;

        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        if ($this->infoType) {

            if (!$this->checkPermission()) {
                $this->addResponseData(["error" => "Нет разрешения на создание анкеты для этого пользователя"]);

                return false;
            }

            /* Регистрация исполнителя */
            $userID = $USER->IsAuthorized() ? $USER->GetID() : $this->input["registration-info-user"];
            $obContractor = new Contractor();
            if ($obContractor->getContractorID($userID)) {
                $this->addResponseData(['error' => "Анкета исполнителя уже заполнена"]);
                return false;
            }

            try {
                $result = $this->registerContractorRequest($this->input["registration-info-user"]);
            } catch (\Exception $e) {
                $this->addResponseData(['error' => $e->getMessage()]);
                return false;
            }
        } else {
            /* Основная регистрация */
            try {
                $result = $this->registerUserRequest();
            } catch (\Exception $e) {
                $this->addResponseData(['error' => $e->getMessage()]);
                return false;
            }
        }

        $this->addResponseData(['result' => $result]);
    }
}
