<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

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

$comagic = new \Electric\Comagic();
$result = $comagic->makeCall('contact','79004988183','79158878008');
var_dump($result->call_session_id);
die();

$config = [
  // required for Rest API and optional for Call API
    /*'login' => 'raecteh@gmail.com1',
    'password' => 'mR_wOYt2',*/
  // required for Call API if login and password not specified
    //'access_token' => 'hw0j050sd4g2r7rl7jkj86di2w80zk5f3b4slhr9',
];
die();
$callApi = new \Electric\CallApiClient($config);
echo "<pre>";
var_dump($callApi->startSimpleCall([
    "first_call" => "operator",
    "switch_at_once" => false,
    "show_virtual_phone_number" => true,
    "virtual_phone_number" => "78001008415",
    "media_file_id" => 113826,
    "direction" => "in",
    "contact" => "79158878008",
    "operator" =>"79204872890",
    "contact_message" => [
        "type" => "tts",
        "value" => "Привет"
      ],
      "operator_message" => [
        "type" => "tts",
        "value" => "Привет"
      ]
]));
var_dump($callApi->metadata());



?>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>