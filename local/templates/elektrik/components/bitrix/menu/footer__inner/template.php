<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult):?>
<div class="footer__inner">
    <div class="footer__title main__text red"><?=$arParams["TITLE"]?></div>
    <ul class="footer__menu">
        <?foreach ($arResult as $arItem):?>
            <li><a href="<?=$arItem["LINK"]?>" class="main__text footer-link"><?=$arItem["TEXT"]?></a></li>
        <?endforeach;?>
    </ul>
</div>
<?endif;?>