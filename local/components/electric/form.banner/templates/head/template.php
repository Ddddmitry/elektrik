<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="formSolo-info">
    <div class="formSolo-info-logo">
        <a href="/">
            <img src="/local/templates/.default/img/logo.png"
                 srcset="/local/templates/.default/img/logo@2x.png 2x,
                                                         /local/templates/.default/img/logo@3x.png 3x"
                 alt="электрик.ру" class="formSolo-info-logo-img">
        </a>
    </div>
    <div class="formSolo-info-slider js-slider-formSolo">
        <? foreach ($arResult["BANNERS"] as $arBanner) {?>
                <div class="formSolo-info-slider__single">
                    <div class="formSolo-info-slider__img" style="background-image: url('<?=$arBanner["SRC"]?>');"></div>
                    <div class="formSolo-info-slider__text">
                        <div class="formSolo-info-slider__comm">
                            <div class="formSolo-info-slider__comm-img">
                                <span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-comm"></use></svg></span>
                            </div>
                            <?=$arBanner["TITLE"]?>
                        </div>
                        <div class="formSolo-info-slider__author">
                            <?=$arBanner["SUBTITLE"]?>
                        </div>
                    </div>
                </div>
        <?}?>
    </div>
</div>
