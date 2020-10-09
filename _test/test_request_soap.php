<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Electric\Request;

use \Bitrix\Main\Loader;
use Electric\RequestSoap;

?>

<?
Loader::includeModule("iblock");


echo "<pre>";




$phone = "79625137113";
$phone = "79130065910"; // ЭКС
$UID_DIST = "9431c87f273e507e6040fcb07dcb4509";
$phone_new = md5($phone.$UID_DIST);
$phone_new = "69D90433542668CF39D74983C8FA1427";
$arParamets = array(
    "action" => "user.check",
    "phone" => $phone_new);
$parametrs = json_encode($arParamets);


/*
try {
    $client = new SoapClient("http://es.elektro.ru/elektrikservice/elektrikservice.svc?wsdl", ['soap_version' => SOAP_1_2]);
    $actionHeader[] = new SoapHeader('http://www.w3.org/2005/08/addressing',
        'Action',
        'http://tempuri.org/IElektrikService/CheckUser');
    $actionHeader[] = new SoapHeader('http://www.w3.org/2005/08/addressing','To','http://es.elektro.ru/elektrikservice/elektrikservice.svc');

    $client->__setSoapHeaders($actionHeader);

    $params = array();
    $test = $client->CheckUser($arParamets);
    var_dump($test->CheckUserResult->exist);
    var_dump($test->CheckUserResult->points);
}
catch (SoapFault $exception)
{
    echo $exception->getMessage();
}

die();*/

try {
$ph = md5("79158878008"."a8abb4bb284b5b27aa7cb790dc20f80b");
        $arData = [["massPhone"=>$ph]];

        $requestSoap = new RequestSoap();
        $requestSoap->setHost("http://api-electrik.toledo24.ru:8081/ka/ws/Electrik_check.1cws?wsdl");
        $requestSoap->execute($arData,["soap_version" => SOAP_1_2]);
        $arResult = $requestSoap->getResult();
        var_dump($arResult);
        die();



ini_set('soap.wsdl_cache_enabled', '0');
$client = new SoapClient(   "http://api-electrik.toledo24.ru:8081/ka/ws/Electrik_check.1cws?wsdl",
    array(
        "soap_version"   => SOAP_1_2,
        ));
$client->__setLocation("http://api-electrik.toledo24.ru:8081/ka/ws/Electrik_check.1cws");
var_dump($client->__getFunctions());
//$arResult = $client->CheckPhone(["massPhone"=>$ph]);
//var_dump(json_decode($arResult->return,true));
//$obResult = $client->__soapCall('CheckPhone', [["massPhone"=>json_encode(["action"=>"user.check","phone"=>$ph])]]);
$obResult = $client->__soapCall('CheckPhone', [["massPhone"=>$ph]]);
$arResult = json_decode($obResult->return,true);
var_dump($arResult);
}
catch (SoapFault $exception)
{
        echo $exception->getMessage();
}
die();

echo "</pre>";

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>




