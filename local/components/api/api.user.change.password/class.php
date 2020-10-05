<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiUserChangePassword extends AjaxComponent
{
    private $arFormData;

    protected function parseRequest()
    {
        $inputPost = $this->request->getPostList();

        $this->arFormData = [
            "OLD_PASSWORD" => $inputPost["edpassword-oldpassword"],
            "NEW_PASSWORD" => $inputPost["edpassword-newpassword"],
        ];
    }

    /**
     * Изменение пароля пользователя
     */
    protected function changePassword()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arUser = \CUser::GetByID($userID)->Fetch();

        $salt = substr($arUser['PASSWORD'], 0, (strlen($arUser['PASSWORD']) - 32));
        $currentPassword = substr($arUser['PASSWORD'], -32);

        if (md5($salt.$this->arFormData["OLD_PASSWORD"]) == $currentPassword) {
            $obUser   = new \CUser;
            $arFields = [
                "PASSWORD"         => $this->arFormData["NEW_PASSWORD"],
                "CONFIRM_PASSWORD" => $this->arFormData["NEW_PASSWORD"],
            ];
            if ($obUser->Update($userID, $arFields)) {

                return true;
            } else {
                throw new \Exception($obUser->LAST_ERROR);
            }
        } else {
            throw new \Exception("wrong_old_password");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = ["SUCCESS" => $this->changePassword()];
            $result["MESSAGE"] = [
                "TITLE" => "Пароль успешно изменён",
                "TEXT" => "<p>Авторизуйтесь, используя новый пароль.</p>",
            ];
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
