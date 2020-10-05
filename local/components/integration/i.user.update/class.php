<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\Contractor;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Location;
use Electric\User;

class IntegrationUserUpdate extends AjaxComponent
{
    /**
     * @var
     */
    protected $input;
    /**
     * @var
     */
    private $phone, $id, $points;
    /**
     * @var
     */
    private $action;
    /**
     * @var
     */
    private $user;
    private $contractor;

    private $distr;

    /**
     *
     */
    protected function parseRequest()
    {
        $this->input = $this->request->getPostList()->toArray();

        $this->action = $this->input['action'];
        $this->phone = $this->input['phone'];
        $this->points = $this->input['points'];
        $this->distr = $this->input['distr'];
    }

    /**
     * @param $phone
     * @return array|bool
     * @throws \Exception
     */
    private function updateUser(){

        if(!$this->findUser()){
            return false;
        }

        $arResult = [];
        switch ($this->action) {
            case 'updatePoints':
                $arResult = $this->updatePoints();
                break;
        }

        return $arResult;

    }

    /**
     * Поиск пользователя по номеру телефона
     * @return bool
     */
    private function findUser(){

        $obUser = new \CUser();
        $rsUsers = $obUser->GetList(($by = "ID"), ($order = "asc"),["PERSONAL_PHONE" => $this->phone]);
        if ($arUser = $rsUsers->Fetch()) {
            $this->user = $arUser;
            $cont = new Contractor();
            $this->contractor = $cont->getActualContractor($arUser["ID"]);
            return true;
        }
        return false;
    }

    private function updatePoints(){
        $points = $this->points;
        $result = false;
        $arFilter = [
            'UF_USER' => $this->contractor["ID"],
            'UF_VENDOR' => $this->distr
        ];
        $arHLDataClasses = User::getHLDataClasses([HLBLOCK_CONTRACTOR_POINTS]);

        $rsData = $arHLDataClasses["CONTRACTOR_POINTS"]::getList(array(
            'select' => ['*'],
            'filter' => $arFilter,
            'order' => ["ID" => "ASC"],
        ));
        if($el = $rsData->fetch()){

            $result = $arHLDataClasses["CONTRACTOR_POINTS"]::update($el["ID"],["UF_POINTS" => $points]);
        }else{

            $result = $arHLDataClasses["CONTRACTOR_POINTS"]::add(
                ['UF_USER' => $this->contractor["ID"],
                    'UF_VENDOR' => $this->distr,
                    "UF_POINTS" => $points]
            );
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function executeAjaxComponent()
    {

        if (!$this->request->isAjaxRequest()) {
            return false;
        }

        try {
            $result = $this->updateUser();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
