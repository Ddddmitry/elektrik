<?php
namespace Electric;

use Bitrix\Main\Application;
use Electric\CallApiClient;

class Comagic
{
    private $VIRTUAL_NUMBER = "78001008415";
    /**
     * @param $firstCall
     * @param $phoneContact
     * @param $phoneOperator
     * @return mixed
     */
    public function makeCall($firstCall = 'contact', $phoneContact, $phoneOperator){
        $callSessionId = false;
        $callApi = new CallApiClient([]);
        if(!$firstCall){
            $firstCall = 'contact';
        }
        $arData = [
            "first_call" => $firstCall,
            "switch_at_once" => false,
            "show_virtual_phone_number" => true,
            "virtual_phone_number" => $this->VIRTUAL_NUMBER,
            "direction" => "in",
            "contact" => $phoneContact,
            "operator" => $phoneOperator,
            "contact_message" => [
                "type" => "tts",
                "value" => "Добрый день! Входящий звонок с портала Электрик.ру"
            ],
            "operator_message" => [
                "type" => "tts",
                "value" => "Добрый день! Входящий звонок с портала Электрик.ру"
            ]
        ];
        $callSessionId = $callApi->startSimpleCall($arData);
        return $callSessionId;

    }

}