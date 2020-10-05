<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult):?>
    <ul>
        <?foreach ($arResult as $arItem):?>
            <li><a href="<?=$arItem["LINK"]?>" class="main__text link-color-red <?=($arItem["SELECTED"])? "is-active" : ""?>"><?=$arItem["TEXT"]?></a></li>
        <?endforeach;?>
    </ul>
<?endif;?>