<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\User;
use \Electric\Location;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

class ApiCitiesChange extends AjaxComponent
{
    private $formData;

    protected function parseRequest()
    {
        $input = $this->request->getPostList()->toArray();
        $this->formData = [
            "CITY_ID" => $input['search_city-id'],
        ];
    }

    protected function executeAjaxComponent()
    {
        global $APPLICATION;
        global $USER;

        if (!$this->request->isAjaxRequest()) {

            return false;
        }

        $cityName = Location::getCityNameByID($this->formData["CITY_ID"]);

        if ($USER->IsAuthorized()) {

            try {
                $arLocation = [
                    "ID" => $this->formData["CITY_ID"],
                    "NAME" => $cityName,
                ];
                $obUser = new User();
                $result = $obUser->setUserCity(null, $arLocation);
            } catch (\Exception $e) {
                $this->addResponseData(['error' => $e->getMessage()]);

                return false;
            }

        } else {

            $APPLICATION->set_cookie("USER_CITY", $this->formData["CITY_ID"]);
            $APPLICATION->set_cookie("USER_CITY_NAME", $cityName);
            $result = true;
        }

        $this->addResponseData(['result' => $result]);
    }
}
