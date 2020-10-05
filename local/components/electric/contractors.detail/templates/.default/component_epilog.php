<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$APPLICATION->IncludeComponent('electric:contractors.reviews', '', [
    "USER" =>$arResult["PROFILE"]["PROPERTIES"]["USER"]["VALUE"],
    "PAGE_SIZE" => REVIEWS_PAGE_SIZE,
]);
?>

<?if($_GET["contacts"] == "Y"):?>
<?$APPLICATION->AddHeadString('<metaname="googlebot" content="noindex">',true);?>
<script>
    $(document).ready(function () {
        $('.card__rside-open').trigger("click");
    });
</script>


<?endif;?>

<?php
global $USER;
if($USER->IsAuthorized()){
    $obClient = new \Electric\Client();
    $arClient = $obClient->getClientData();
    if($arClient["PROPERTIES"]["PHONE"]["VALUE"] === false){?>
    <script>
        $(document).ready(function(){
            $('[data-make-call]').replaceWith('<div class="main__text main__text--xs">Чтобы связаться с электриком вы должны <a href="/auth/"> войти</a> на портал как клиент и заполнить свой телефон.</div>');
        });
    </script>
    <?}
}
?>
