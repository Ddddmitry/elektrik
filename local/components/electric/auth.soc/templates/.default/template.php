
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($_COOKIE["social-register"] == "Y" || $arParams["IGNOR_REG"] == "Y" ):?>
<div class="formSolo-workarea-content-social">
    <p><?=$arParams["TITLE"]?></p>
    <div class="formSolo-workarea-content-social-list">
        <? foreach ($arResult["AUTH_DATA"] as $arAuth) {?>
            <div class="formSolo-workarea-content-social-list__single">
                <a href="javascript:void(0)" onclick="<?=$arAuth["ONCLICK"]?>">
                    <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-<?=$arAuth["ICON"]?>"></use></svg></span>
                </a>
            </div>
        <?}?>
    </div>
</div>
<?endif;?>
