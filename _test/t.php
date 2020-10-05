<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Electric\Order;
use Electric\Request;
use Electric\RequestSoap;
use Electric\Location;
use Electric\Helpers\DataHelper;
use Bitrix\Main\PhoneNumber\ShortNumberFormatter;
use Bitrix\Main\PhoneNumber\Parser;
use Bitrix\Main\PhoneNumber\Format;
use \Bitrix\Main\Loader;
?>
<?php

//$obOrder = new Order();
//$obOrder->addOrderToMixer(1361, "5bf5ddff-6353-4a3d-80c4-6fb27f00c6c1");

$obContractor = new \Electric\Contractor();
//$obContractor->getQueue("a101dd8b-3aee-4bda-9c61-9df106f145ff");
$obContractor->getQueue("5bf5ddff-6353-4a3d-80c4-6fb27f00c6c1");
die();
$arIntPartners = DataHelper::getAllIntegrationPartners();

$phone = "79097140021";
//$phone = "79534154452";  // Толедо
$phone = "79195161202";  // Кристал

$phone = "79513711469";


echo "<pre>";

$rest = new Request();
$requestSoap = new RequestSoap();
foreach ($arIntPartners as $arIntPartner) {
    if(!$arIntPartner["URL"]) continue;
    $UID_DIST = $arIntPartner["UID"];
    $phone_new = md5($phone.$UID_DIST);
    $arData = [
        "action" => "user.check",
        "phone" => $phone_new,
    ];
    if($arIntPartner["TYPE"] == CONNECTION_TYPE_CURL){
        $rest->setHost($arIntPartner["URL"]);
        $rest->execute($arData);
        $arResult = $rest->getResult();
        $status = $rest->getStatusCode();
    }
    if($arIntPartner["TYPE"] == CONNECTION_TYPE_SOAP){

        $requestSoap->setHost($arIntPartner["URL"]);
        $requestSoap->execute($arData);
        $arResult = $requestSoap->getResult();
        $status = "SOAP - 200";
    }


    echo "Партнер: ".$arIntPartner["NAME"]."<br>";
    echo "Статус ответа: ".$status."<br>";
    var_dump($arResult);
    echo "<hr>";

}
echo "</pre>";
die();
/*$password = randString(8);

$obUser   = new \CUser;
$arFields = [
    "PASSWORD"         => $password,
    "CONFIRM_PASSWORD" => $password,
];
if ($obUser->Update(413, $arFields)) {
    echo $password;
    return true;
} else {
    throw new \Exception($obUser->LAST_ERROR);
}*/
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>