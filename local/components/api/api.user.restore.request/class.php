<?php
namespace Api\Components;

use Electric\Component\AjaxComponent;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\UserTable;

class ApiUserRestoreRequest extends AjaxComponent
{

    protected
        $email,
        $phone,
        $password,
        $login,
        $checkword,
        $isChange,
        $userID,
        $confirmCode,
        $input
    ;

    protected function parseRequest()
    {
        $input = $this->request->getPostList();
        $this->input = $input;
        $this->phone = $input['phone'];
        $this->email = $input['email'];
        $this->isChange = $input['change'];
        $this->checkword = $input['checkword'];
        $this->login = $input['login'];
        $this->password = $input['password'];
        $this->confirmCode = $input["code_submit_button"];
    }

    /**
     * Получение логина пользователя
     */
    protected function getUserLogin()
    {
        $arUser = UserTable::getRow(array(
            'select' => [
                'LOGIN',
                'ID'
            ],
            'filter' => ['=LOGIN' => $this->phone],
        ));
        if(!empty($arUser) && is_array($arUser)) {
            $this->userID = $arUser["ID"];
            return $arUser['LOGIN'];
        }

        return false;
    }

    /**
     * Сохранение нового пароля пользователя
     */
    protected function changeUserRequest()
    {
        global $USER;
        $arResult = $USER->ChangePassword($this->login, $this->checkword, $this->password, $this->password, 's1', '', 0, false);
        if($arResult["TYPE"] == "OK") {
            $result = "Пароль успешно изменен. Через несколько секунд Вы будете переадресованы на страницу авторизации.";
        } else {
            throw new \Exception($arResult['MESSAGE']);
        }

        return $result;
    }

    /**
     * Отправка контрольной строки на email пользователя
     */
    protected function restoreUserRequest()
    {
        $result = false;

        if($this->getUserLogin() != false) {
            global $USER;
            //$arResult = $USER->SendPassword($this->getUserLogin(), $this->email);
            if($this->confirmCode != "Y"){
                $arResult = $this->sendSmsCode();
            }else{
                $arResult = $this->checkSmsCode();
            }

        }else {
            throw new \Exception('Указанный телефон не найден.');
        }


        return $arResult;
    }

    protected function sendSmsCode(){

        //added the phone number for the user, now sending a confirmation SMS
        list($code, $phoneNumber) = \CUser::GeneratePhoneCode($this->userID);

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
            $arRegisterResult["ERRORS"][] = $smsResult->getErrorMessages();
        }

        $arRegisterResult["PHONE"] = $this->phone;
        $arRegisterResult["TYPE"] = "SEND_CODE";
        $arRegisterResult["SHOW_SMS_FIELD"] = true;
        $arRegisterResult["SIGNED_DATA"] = \Bitrix\Main\Controller\PhoneAuth::signData(['phoneNumber' => $phoneNumber]);


        return $arRegisterResult;

    }

    protected function checkSmsCode() {

        if($this->input["SIGNED_DATA"] <> '')
        {
            if(($params = \Bitrix\Main\Controller\PhoneAuth::extractData($this->input["SIGNED_DATA"])) !== false)
            {
                if(($userId = \CUser::VerifyPhoneCode($params['phoneNumber'], $this->input["SMS_CODE"])))
                {

                    //the user was added as inactive, now phone number is confirmed, activate them

                    $password = randString(8);

                    $obUser   = new \CUser;
                    $arFields = [
                        "PASSWORD"         => $password,
                        "CONFIRM_PASSWORD" => $password,
                    ];
                    if ($obUser->Update($userId, $arFields,false)) {

                        $sms = new \Bitrix\Main\Sms\Event(
                            "SMS_USER_NEW_PASSWORD",
                            [
                                "USER_PHONE" => $this->phone,
                                "PASSWORD" => $password
                            ]
                        );
                        $smsResult = $sms->send(true);

                        if(!$smsResult->isSuccess())
                        {
                            $arRegisterResult["ERRORS"][] = $smsResult->getErrorMessages();
                        }

                        $arRegisterResult["TYPE"] = "OK";
                        $arRegisterResult["ID"] = $userId;
                        $arRegisterResult["MESSAGE"] = [
                            "TEXT" => "<p>На ваш телефон <b>".$this->phone."</b> отправлен новый пароль.</p>".
                                "<br><a href=\"/auth/\" class=\"button2\">Войти</a>",
                        ];

                    } else {
                        throw new \Exception($obUser->LAST_ERROR);
                    }

                }
                else
                {
                    $arRegisterResult["ERRORS"][] = "Код не совпадает";
                    $arRegisterResult["SHOW_SMS_FIELD"] = true;
                    $arRegisterResult["SMS_CODE"] = $this->input["SMS_CODE"];
                    $arRegisterResult["SIGNED_DATA"] = $this->input["SIGNED_DATA"];
                }
            }
        }
        return $arRegisterResult;

    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->restoreUserRequest();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result, 'redirect' => '/auth/']);
    }

}
