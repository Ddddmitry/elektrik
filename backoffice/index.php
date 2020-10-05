<?
use Electric\User;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty('title', 'Бэкофис');

global $USER;
if (!$USER->IsAuthorized()) {
    LocalRedirect('/auth/');
}

$obUser = new User;
$type = $obUser->getType();
switch ($type){
    case "client":
        LocalRedirect('clients/');
        break;
    case "contractor":
        LocalRedirect('contractors/');
        break;
    case "distributor":
        LocalRedirect('distributors/');
        break;
    case "vendor":
        LocalRedirect('vendors/');
        break;
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
