<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cabinet__info">
    <div class="cabinet__info-photo" style="background-image: url('<?=$arResult["PROFILE"]["PREVIEW_PICTURE"]["SRC"]?>');"></div>
    <div class="cabinet__info-description">
        <div class="cabinet__info-top">
            <div class="main__text main__text--lg">
                <?=$arResult["PROFILE"]["NAME"];?>
            </div>
            <div class="cabinet__info-status">
                <div class="main__text main__text--sm">Свободен</div>
                <label for="check-status" class="field-checkbox-on">
                    <input type="checkbox" id="check-status" name="" <?if(!$arResult["PROFILE"]["FREE"]):?> checked <?endif;?> data-checkbox-status value="Y">
                    <span class="field-checkbox-on__mark"></span>
                </label>
                <div class="main__text main__text--sm">Занят</div>
                <div class="cabinet__info-question">
                    <div class="tooltip">
                        <div class="tooltip__content">
                            <div class="tooltip__maintext">
                                Поддерживайте ваш статус актуальным, чтобы избежать блокировки на портале
                            </div>
                            <div class="tooltip__mainlink">
                                <a href="#">Правила портала</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cabinet__info-inner">
            <?if($arResult["PROFILE"]["SERTIFIED"]):?>
                <div class="btn btn--white-icon btn--white-icon--xs">
                    <img src="<?=SITE_TEMPLATE_PATH?>/images/logo-e.svg" alt="">
                    <span>Сертифицирован</span>
                </div>
            <?endif;?>
            <?if($arResult["PROFILE"]["TIME"]):?>
                <div class="cabinet__info-time is-fast main__text main__text--xs light">
                    Среднее время ответа: <?=$arResult["PROFILE"]["TIME"]?> <?=$arResult["PROFILE"]["TIME_WORD"]?>
                </div>
            <?endif;?>
        </div>
    </div>
</div>
