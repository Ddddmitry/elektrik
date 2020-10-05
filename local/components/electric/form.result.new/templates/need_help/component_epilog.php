<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$obUser = new \Electric\User();
$userType = $obUser->getType();
$arData = [];
if($userType == "vendor"){
    $obUserIndividual = new \Electric\Vendor();
    $arUserIndividual = $obUserIndividual->getVendorData();
    $arData["NAME"] = $arUserIndividual["NAME"];
    $arData["PHONE"] = $arUserIndividual["PROPERTIES"]["PHONE"]["VALUE"];
    $arData["EMAIL"] = $arUserIndividual["PROPERTIES"]["EMAIL"]["VALUE"];
}
if($userType == "distributor"){
    $obUserIndividual = new \Electric\Distributor();
    $arUserIndividual = $obUserIndividual->getDistributorData();
    $arData["NAME"] = $arUserIndividual["NAME"];
    $arData["PHONE"] = $arUserIndividual["PROPERTIES"]["PHONE"]["VALUE"];
    $arData["EMAIL"] = $arUserIndividual["PROPERTIES"]["EMAIL"]["VALUE"];
}
if($userType == "contractor"){
    $obUserIndividual = new \Electric\Contractor();
    $arUserIndividual = $obUserIndividual->getContractorData();
    $arData["NAME"] = $arUserIndividual["NAME"];
    $arData["PHONE"] = $arUserIndividual["PROPERTIES"]["PHONE"]["VALUE"];
    $arData["EMAIL"] = $arUserIndividual["PROPERTIES"]["EMAIL"]["VALUE"];
}
if($userType == "client"){
    $obUserIndividual = new \Electric\Client();
    $arUserIndividual = $obUserIndividual->getClientData();
    $arData["NAME"] = $arUserIndividual["NAME"];
    $arData["PHONE"] = $arUserIndividual["PROPERTIES"]["PHONE"]["VALUE"];
    $arData["EMAIL"] = $arUserIndividual["PROPERTIES"]["EMAIL"]["VALUE"];
}
if($arData){?>
    <script>
        $(document).ready(function () {
            $("input[name='form_text_7']").val("<?=$arData["NAME"]?>");
            $("input[name='form_text_8']").val("<?=$arData["PHONE"]?>");
            $("input[name='form_text_9']").val("<?=$arData["EMAIL"]?>");
        });
    </script>
<?}?>