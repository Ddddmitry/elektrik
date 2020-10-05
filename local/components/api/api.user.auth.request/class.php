<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\User;

class ApiUserAuthRequest extends AjaxComponent
{
    protected $login, $password;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();

        $this->login = $input['autorization-email'];
        $this->login = $input['autorization-phone'];
        $this->password = $input['autorization-password'];
    }

    /**
     * Авторизовывает пользователя
     */
    protected function authUserRequest()
    {
        global $USER;

        $rsUser = \CUser::GetByLogin($this->login);
        if (!$rsUser->Fetch()) {
            throw new \Exception("Пользователь не найден");
        };

        if (!is_object($USER)) $USER = new \CUser;

        $result = $USER->Login(
            $this->login,
            $this->password,
            "Y"
        );


        if (is_array($result)) {
            if ($result["ERROR_TYPE"] == "LOGIN") {
                throw new \Exception("Неверный пароль");
            } else {
                throw new \Exception($result['MESSAGE']);
            }
        }

        return $result;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->authUserRequest();
            if($result){
                $obUser = new User();
                $type = $obUser->getType();
                switch ($type){
                    case "client":
                        $link = '/backoffice/clients/';
                        break;
                    case "contractor":
                        $link = '/backoffice/contractors/orders/';
                        break;
                    case "distributor":
                        $link = '/backoffice/distributors/';
                        break;
                    case "vendor":
                        $link = '/backoffice/vendors/';
                        break;
                }
                $this->addResponseData(['link' => $link]);
            }
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
