<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="header__menu">
<?if($arResult["IS_AUTHORIZED"]):?>
    <li>
        <a href="/backoffice/" class="main__link"><?=$arResult["USER"]["NAME"]?></a>
    </li>
    <li>
        <a href="/logout/" class="main__link">Выйти</a>
    </li>
<?else:?>
    <li>
        <a href="/order/" class="btn btn--menu"><span>Вызвать электрика</span></a>
    </li>
    <li>
        <a href="/auth/" class="main__link">Войти</a>
    </li>
    <li>
        <a href="/register/" class="main__link">Регистрация</a>
    </li>
<?endif;?>
</ul>
