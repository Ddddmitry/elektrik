<?php
namespace Api\Components;

use \Electric\Component\IntegrationComponent;
use \Electric\Contractor;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\User;
use Bitrix\Main\Application;
use Bitrix\Main\PhoneNumber\Parser;


class IntegrationUserUpdatePoints extends IntegrationComponent
{
    /**
     * @var
     */
    protected $input;
    /**
     * @var
     */
    private $phone, $uid, $points;

    /**
     * @var
     */
    private $user;
    private $contractor;

    private $distUserId;
    private $contractorUserId;

    /**
     *
     */
    protected function parseRequest()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        $this->phone = $request->get('phone');
        $this->points = $request->get('points');
        $this->uid = $request->get('uid');



        /*$this->input = $this->request->getPostList()->toArray();
        $this->phone = $this->input['phone'];
        $this->points = $this->input['points'];
        $this->uid = $this->input['uid'];*/

        if(!$this->phone){
            throw new \Exception("Phone not found");
        }
        if(!$this->points){
            throw new \Exception("Points not found");
        }
        if(!$this->uid){
            throw new \Exception("UID incorrect");
        }

    }

    /**
     * @param $phone
     * @return array|bool
     * @throws \Exception
     */
    private function updateUser(){

        if(!$this->findDistr()){
            throw new \Exception("UID incorrect");
        }

        if(!$this->findContractor()){
            throw new \Exception("Phone hash incorrect");
        }

        $arResult = false;
        $arFilter = [
            'UF_CONTACTOR_USER' => $this->contractorUserId,
            'UF_PARTNER_ID' => $this->distUserId
        ];
        $arHLDataClasses = User::getHLDataClasses([HLBLOCK_CONTRACTOR_POINTS,HLBLOCK_CONTRACTOR_POINTS_HISTORY]);

        $rsData = $arHLDataClasses["CONTRACTOR_POINTS"]::getList(array(
            'select' => ['*'],
            'filter' => $arFilter,
            'order' => ["ID" => "ASC"],
        ));
        if($el = $rsData->fetch()){
            $diffPoints = $this->points - $el["UF_POINTS"];
            $arResult = $arHLDataClasses["CONTRACTOR_POINTS"]::update($el["ID"],["UF_POINTS" => $this->points]);

        }else{
            $diffPoints = $this->points;
            $arResult = $arHLDataClasses["CONTRACTOR_POINTS"]::add(
                [
                    'UF_CONTACTOR_USER' => $this->contractorUserId,
                    'UF_PARTNER_ID' => $this->distUserId,
                    "UF_POINTS" => $this->points
                ]
            );
        }
        if($arResult->isSuccess()){
            if($diffPoints !== 0){
                $arHLDataClasses["CONTRACTOR_POINTS_HISTORY"]::add(
                    [
                        "UF_USER_ID" => $this->contractorUserId,
                        "UF_PARTNER_ID" => $this->distUserId,
                        "UF_POINTS" => $diffPoints,
                        "UF_DATE" => date("d.m.Y")
                    ]
                );
            }

        }


        return $arResult->isSuccess();

    }

    /**
     * Поиск дистрибьютора по UID
     * @return bool
     */
    private function findDistr(){
        Loader::includeModule("iblock");
        $arSelect = ['ID','IBLOCK_ID','NAME','PROPERTY_USER'];
        $arFilter = ['IBLOCK_ID' => IBLOCK_DISTRIBUTORS,'ACTIVE'=>'Y','PROPERTY_UID' => $this->uid];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($arResult = $dbResult->Fetch()) {
            $this->distUserId = $arResult["PROPERTY_USER_VALUE"];
            return true;
        }
        return false;

    }

    /**
     * Поиск исполнителя по номеру телефона
     * @return bool
     */
    private function findContractor(){

        $arSelect = ["ID", "IBLOCK_ID", "NAME","PROPERTY_PHONE","PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_CONTRACTORS,
            "ACTIVE" => "Y",
            "!PROPERTY_PHONE" => false
        ];
        $dbResult = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        while ($arResult = $dbResult->Fetch()) {
            if($arResult["PROPERTY_PHONE_VALUE"]){
                $phone = $this->clearPhone($arResult["PROPERTY_PHONE_VALUE"]);
                $phoneHash = md5($phone.$this->uid);
                if($phoneHash == $this->phone){
                    $this->contractorUserId = $arResult["PROPERTY_USER_VALUE"];
                    return true;
                }
            }
        }
        return false;

    }

    /**
     * Очищаем телефон
     * @param $phone
     * @return string
     */
    private function clearPhone($phone){

        $parsedPhone = Parser::getInstance()->parse($phone);
        $clearPhone = "";
        $clearPhone .= $parsedPhone->getCountryCode().$parsedPhone->getNationalNumber();
        return $clearPhone;
    }

    /**
     * @return bool
     */
    protected function executeAjaxComponent()
    {

        try {
            $result = $this->updateUser();
        } catch (\Exception $e) {
            $this->addResponseData(['result'=>false,'error' => $e->getMessage()]);
            return false;
        }
        $this->addResponseData(['result' => $result]);


    }
}