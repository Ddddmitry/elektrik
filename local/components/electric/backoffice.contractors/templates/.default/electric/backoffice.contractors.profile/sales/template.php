<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__top">
    <div class="main__text main__text--lg">Акции <?=$arResult["PROFILE"]["NAME"]?></div>
    <a href="add/" class="btn btn--red">Добавить акцию</a>
</div>
<div class="cabinet__stocks">
    <?if($arResult["PROFILE"]["SALES"]):?>
        <? foreach ($arResult["PROFILE"]["SALES"] as $arSale) {?>
            <div class="cabinet__stock <?if($arSale["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>is-archive<?endif;?>" data-sale-<?=$arSale["ID"]?>>
                <div class="cabinet__stock-photo" style="background-image: url('<?=$arSale["PREVIEW_PICTURE"]["SRC"]?>');"></div>
                <div class="cabinet__stock-description">
                    <div class="cabinet__stock-inner">
                        <div class="main__text main__text--md"><?=$arSale["NAME"]?></div>
                        <div class="main__text main__text--sm">
                            <div class="text-archival">Архивное</div>
                        </div>
                        <div class="main__text main__text--sm"><?=$arSale["PREVIEW_TEXT"]?></div>
                    </div>
                    <div class="cabinet__stock-actions">
                        <div class="cabinet__stock-action">
                            <a href="<?=$arResult["SEF_FOLDER"]?>sales/edit/<?=$arSale["ID"]?>/" class="main__text main__text--sm link-red">Редактировать</a>
                        </div>
                        <div class="cabinet__stock-action js-cabinet__stock-action">
                            <?if($arSale["PROPERTIES"]["IS_ARCHIVE"]["VALUE"]):?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="N" data-sale-id="<?=$arSale["ID"]?>">Вернуть из архива</a>
                            <?else:?>
                                <a href="#" class="main__text main__text--sm link-red" data-back-from-archive="Y" data-sale-id="<?=$arSale["ID"]?>">Архивировать</a>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <?}?>
    <?else:?>
        <p>У вас еще нет акций</p>
    <?endif;?>
</div>
