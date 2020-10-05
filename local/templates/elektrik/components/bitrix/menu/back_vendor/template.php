<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?foreach ($arResult as $arItem):?>
    <?if($arItem["SELECTED"]):?>
        <?$curPage = $arItem['TEXT'];?>
    <?endif;?>
<?endforeach;?>


<div class="cabinet__navigation">
    <div class="cabinet__navigation-btn">
        <div class="main__text white"><?=$curPage;?></div>
    </div>
    <div class="cabinet__navigation-drop">
        <ul>
            <?foreach ($arResult as $arItem):?>

                <?if($arItem["PARAMS"]["ADDITIONAL"]) continue;?>
                <li <?=($arItem["SELECTED"])? 'class="is-active"' : ''?> >
                    <a href="<?=$arItem["LINK"]?>" class="main__text <?=($arItem["SELECTED"])? "is-active" : ""?>">
                        <?=$arItem["TEXT"]?>
                    </a>
                </li>
            <?endforeach;?>

        </ul>
        <ul>
            <?foreach ($arResult as $arItem):?>
                <?if(!$arItem["PARAMS"]["ADDITIONAL"]) continue;?>
                <li <?=($arItem["SELECTED"])? 'class="is-active"' : ''?>>
                    <a href="<?=$arItem["LINK"]?>" class="main__text <?=($arItem["SELECTED"])? "is-active" : ""?>">
                        <?=$arItem["TEXT"]?>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
    </div>
</div>
