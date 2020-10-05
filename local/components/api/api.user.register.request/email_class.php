<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\Contractor;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Location;

class ApiUserRegisterRequest extends AjaxComponent
{
    protected $input;
    private $name, $login, $password, $type;
    private $infoType;

    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();

        $this->name = $this->input['registration-name'];
        $this->login = $this->input['registration-email'];
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

        $obUser = new \CUser;
        $arRegisterResult = $obUser->Register($this->login, false, false, $this->password, $this->password, $this->login);

        if ($arRegisterResult["TYPE"] == "OK") {

            $arUserGroups = array();
            switch ($this->type):
                case "client":
                    $arName = explode(" ", trim($this->name));
                    $obClient = new \CUser;
                    $arClientFields = Array(
                        "LAST_NAME" => $arName[0],
                        "NAME" => $arName[1],
                        "SECOND_NAME" => $arName[2],
                    );
                    $obClient->Update($arRegisterResult["ID"], $arClientFields);
                    $arUserGroups[] = USER_GROUP_CLIENTS;
                    try {
                        $result = $this->createClientProfile($arRegisterResult["ID"]);
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
                "TITLE" => "Подтвердите свою почту",
                "TEXT" => "<p>На вашу почту <b>".$this->login."</b> отправлено письмо со ссылкой на подтверждение.</p>".
                          "<p>Если письмо не приходит, то возможно, оно попало в папку «Спам» или ваша почта указана неверно.</p>"
            ];

        } else {
            $message = $arRegisterResult['MESSAGE'];
            if (strpos($arRegisterResult['MESSAGE'], "уже существует") != false) {
                $message .= "Хотите <a href=\"/auth/\">авторизоваться</a> или <a href=\"/auth/restore/\">восстановить пароль</a>?<br>";
            }

            throw new \Exception($message);
        }

        return $arRegisterResult;
    }

    /**
     * Создание профиля клиента
     *
     * @param $userID
     *
     * @return mixed
     * @throws \Exception
     */
    protected function createClientProfile($userID)
    {
        global $USER;
        \CModule::IncludeModule('iblock');
        $arUser = \CUser::GetByID($userID)->Fetch();
        $fullName = trim($this->name);
        $obSection = new \CIBlockSection;
        $arClientSectionFields = Array(
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "NAME" => $fullName,
        );
        $clientSectionID = $obSection->Add($arClientSectionFields);
        if (!$clientSectionID) {
            throw new \Exception($obSection->LAST_ERROR);
        }

        $obElement = new \CIBlockElement;
        $arClientFields = Array(
            "IBLOCK_ID" => IBLOCK_CLIENTS,
            "NAME" => $fullName,
            "IBLOCK_SECTION_ID" => $clientSectionID,
        );
        if ($clientID = $obElement->Add($arClientFields)) {
            $arClientProperties = array(
                "USER" => $arUser['ID'],
                "EMAIL" => $arUser["EMAIL"],
            );
            \CIBlockElement::SetPropertyValuesEx($clientID, false, $arClientProperties);
        }

        return true;
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
