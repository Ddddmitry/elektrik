<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="cabinet__box cabinet__box--single">
    <div class="cabinet__points">
        <div class="cabinet__points-title main__text main__text--lg">Баллы</div>
        <div class="main__text">Получайте баллы от Электрика, чтобы потом с удовольствием потратить их на свой
            бизнес
        </div>
        <div class="cabinet__points-numbers main__headline main__headline--red"><?=number_format($arResult["TOTAL_POINTS"],"0","."," ")?> <?=$arResult["TOTAL_POINTS_WORD"]?></div>
        <div class="cabinet__points-description main__text main__text--xs light">Баллы начисляются в течение
            10 календарных дней.
            Баллы надо потратить за 2 года, иначе они сгорят.
        </div>
    </div>
</div>
<div class="cabinet__box cabinet__box--single">
    <div class="cabinet__points">
        <div class="cabinet__points-tabs">
            <div class="cabinet__points-tab main__text is-open" data-open="tab-points">Ваши баллы</div>
            <div class="cabinet__points-tab main__text" data-open="tab-history">История начислений</div>
            <div class="cabinet__points-tab main__text" data-open="tab-spends" onclick="window.location.href='/poffers/'">Куда потратить</div>
        </div>
        <div class="cabinet__points-contents">
            <div class="cabinet__points-content tab-points is-open">
                <div class="cabinet__points-inners">
                    <?if($arResult["POINTS"]["DISTRIBUTORS"]):?>
                    <div class="cabinet__points-inner">
                        <div class="main__text main__text--md">От дистрибьюторов</div>
                        <ul class="cabinet__points-items">
                            <? foreach ($arResult["POINTS"]["DISTRIBUTORS"] as $arDistributor) {?>
                                <li class="cabinet__points-item">
                                    <div class="cabinet__points-logo">
                                        <img src="<?=$arDistributor["PARTNER"]["SRC"]?>" alt="">
                                    </div>
                                    <div class="main__text main__text--md"><?=$arDistributor["UF_POINTS"]?> <?=$arDistributor["POINTS_WORD"]?></div>
                                </li>
                            <?}?>
                        </ul>
                    </div>
                    <?endif;?>
                    <?if($arResult["POINTS"]["VENDORS"]):?>
                    <div class="cabinet__points-inner">
                        <div class="main__text main__text--md">От вендоров</div>
                        <ul class="cabinet__points-items">
                            <? foreach ($arResult["POINTS"]["VENDORS"] as $arVendor) {?>
                                <li class="cabinet__points-item">
                                    <div class="cabinet__points-logo">
                                        <img src="<?=$arVendor["PARTNER"]["SRC"]?>" alt="">
                                    </div>
                                    <div class="main__text main__text--md"><?=$arVendor["UF_POINTS"]?> <?=$arVendor["POINTS_WORD"]?></div>
                                </li>
                            <?}?>
                        </ul>
                    </div>
                    <?endif;?>
                </div>
            </div>
            <div class="cabinet__points-content tab-history">
                <div class="cabinet__points-inners">
                    <?if($arResult["HISTORY"]["DISTRIBUTORS"]):?>
                        <div class="cabinet__points-inner" data-history-block>
                            <div class="main__text main__text--md">От дистрибьюторов</div>
                            <ul class="cabinet__points-items">
                                <? foreach ($arResult["HISTORY"]["DISTRIBUTORS"] as $index => $arDistributor) {?>
                                    <li class="cabinet__points-item js-cabinet__points-item <?if($index > 2):?>hide<?endif;?>">
                                        <div class="cabinet__points-logo">
                                            <img src="<?=$arDistributor["PARTNER"]["SRC"]?>">
                                        </div>
                                        <div class="main__text main__text--sm">
                                            <?if($arDistributor["UF_POINTS"] > 0):?>+<?endif;?><?=$arDistributor["UF_POINTS"]?> <?=$arDistributor["POINTS_WORD"]?>
                                        </div>
                                        <div class="main__text main__text--xs light"><?=$arDistributor["DATE"]?></div>
                                    </li>
                                <?}?>
                            </ul>
                            <?if($index > 2):?>
                                <a href="#" class="btn-refresh" data-show-more-history><span>Показать еще</span></a>
                            <?endif;?>
                        </div>
                    <?endif;?>
                    <?if($arResult["HISTORY"]["VENDORS"]):?>
                        <div class="cabinet__points-inner" data-history-block>
                            <div class="main__text main__text--md">От вендоров</div>
                            <ul class="cabinet__points-items">
                                <? foreach ($arResult["HISTORY"]["VENDORS"] as $index=> $arVendors) {?>
                                    <li class="cabinet__points-item js-cabinet__points-item <?if($index > 2):?>hide<?endif;?>">
                                        <div class="cabinet__points-logo">
                                            <img src="<?=$arVendors["PARTNER"]["SRC"]?>">
                                        </div>
                                        <div class="main__text main__text--sm">
                                            <?if($arVendors["UF_POINTS"] > 0):?>+<?endif;?><?=$arVendors["UF_POINTS"]?> <?=$arVendors["POINTS_WORD"]?>
                                        </div>
                                        <div class="main__text main__text--xs light"><?=$arVendors["DATE"]?></div>
                                    </li>
                                <?}?>
                            </ul>
                            <?if($index > 2):?>
                                <a href="#" class="btn-refresh" data-show-more-history><span>Показать еще</span></a>
                            <?endif;?>
                        </div>
                    <?endif;?>
                </div>
            </div>
            <div class="cabinet__points-content tab-spends">

            </div>
        </div>
    </div>
</div>
<div class="cabinet__action">
    <a href="#" class="btn-pdf"><span>Правила начисления баллов на портале Электрик.ру</span></a>
</div>
