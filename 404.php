<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->SetTitle("404 Not Found");


?>
<p style="text-align: center"><br><br><br><br></p>
<h1 style="text-align: center">Данная страница не существует!</h1>
<p style="text-align: center">Вы можете <a href="/">перейти на главную страницу сайта</a> или воспользоваться основным меню сайта.</p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>