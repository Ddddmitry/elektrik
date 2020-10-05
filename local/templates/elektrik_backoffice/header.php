<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
global $is404;
global $USER;
$is404 = (defined("ERROR_404") && ERROR_404 === "Y");
Loc::loadMessages(__FILE__);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->ShowHead();?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/style.css");?>

    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/jquery.fancybox.min.css");?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/select2.min.css");?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/slick.css");?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/datepicker.min.css");?>

    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main.js");?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/custom.js");?>

    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-96x96.png" sizes="96x96">

    <meta name="yandex-verification" content="fe78db7f439ecf37" />
    <meta name="google-site-verification" content="JGbxVu42DamM_otKtDj49Mz1m9lDUL5970CvWejoyqo" />
    <meta name='wmail-verification' content='5943aa8320f2628e922d7cd56609fa60' />
    <meta name="msvalidate.01" content="2D3BFA67AEC9FD308380E90ED585BE8B" />
    <script type="text/javascript">
        var __cs = __cs || [];
        __cs.push(["setCsAccount", "SK0DabF_DEMGoVtQ1P8pPGUiGegkf_37"]);
    </script>
    <script type="text/javascript" async src="https://app.comagic.ru/static/cs.min.js"></script>

</head>

<body>
<?//$APPLICATION->ShowPanel();?>

<main class="main">
    <div class="scroll-top" id="top"></div>



    <header class="header header--cabinet">
        <div class="container">
            <div class="header__content">
                <div class="header__navigation">
                    <div class="header__burger">
                        <span></span>
                        <span></span>
                    </div>
                    <div class="header__title main__text main__text--xs white">Личный кабинет <span>Электрик.ру</span></div>
                </div>
                <a href="/" class="header__logo">
                    <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg" alt="Электрик.ру">
                </a>
                <ul class="header__menu">

                    <?$APPLICATION->IncludeComponent('electric:auth.link', 'backoffice_head', []);?>

                    <?$APPLICATION->IncludeComponent('electric:notifications', '', []);?>

                </ul>
            </div>
        </div>
    </header>



    <aside class="sidebar">
            <div class="sidebar__container">
                <?$APPLICATION->IncludeComponent('electric:menu.main', '', []);?>
            </div>
        </aside>

    <?if(!$IS_MAIN && !$IS_MARKETPLACE && !$IS_ARTICLES):?>
        <section class="<?echo $APPLICATION->GetDirProperty("class");?>">
            <div class="container <?echo $APPLICATION->GetDirProperty("class_container");?>">
    <?endif;?>
