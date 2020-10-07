<?php

use Electric\Distributor;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/_test/integration_log.txt");
//AddMessage2Log("UserHandler : ", "Event OnAfterUserAddHandler");
$obDistr = new Distributor();
$distrId = $obDistr->getUserIdByUID("7f975a56c761db6506eca0b37ce6ec87");
var_dump($distrId);
?>