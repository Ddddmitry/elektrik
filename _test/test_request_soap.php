<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Electric\Request;

use \Bitrix\Main\Loader;
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
ini_set('soap.wsdl_cache_enabled', '0');
$client = new SoapClient(   "http://212.49.107.36:3508/elektrik/ws/Electrik_EM.1cws?wsdl",
    array(
        //"soap_version"   => SOAP_1_2,
        ));

var_dump($client->__getFunctions());
$arResult = $client->CheckUser(["TelHeh"=>"69D90433542668CF39D74983C8FA1427"]);
var_dump(json_decode($arResult->return,true));
//var_dump($client->__soapCall('CheckUser', ["69D90433542668CF39D74983C8FA1427"]));
die();

echo "</pre>";

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>




