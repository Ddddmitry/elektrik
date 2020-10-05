<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';

require_once('init_constants_dev.php');

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/load/handlers.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/load/handlers.php");
}