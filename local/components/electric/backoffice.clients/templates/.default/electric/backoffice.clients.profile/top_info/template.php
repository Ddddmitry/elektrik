<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__info">
    <div class="cabinet__info-photo" style="background-image: url('<?=$arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"]?>');"></div>
    <div class="cabinet__info-description">
        <div class="cabinet__info-top">
            <div class="main__text main__text--lg"><?=$arResult["PROFILE"]["NAME"];?></div>
            <a href="/order/" class="btn btn--red">Вызвать электрика</a>
        </div>
    </div>
</div>