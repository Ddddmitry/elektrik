<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["IS_AUTHORIZED"]):?>
    <li class="select-transparent-white">
<!--        <a href="/backoffice/">--><?//=$arResult["USER"]["NAME"]?><!--</a>-->
        <select name="" id="" onchange="window.location.href = this.value;">
            <option value="/backoffice/"><?=$arResult["USER"]["NAME"]?></option>
<!--            <option value="/backoffice/--><?//=$arResult["TYPE"]?><!--s/statistic/">Статистика</option>-->
            <option value="/logout/">Выйти</option>
        </select>
    </li>

<?endif;?>

