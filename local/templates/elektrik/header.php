<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <meta name="theme-color" content="#d70926">
    <title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->ShowHead();?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/style.css");?>

    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/jquery.fancybox.min.css");?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/slick.css");?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/vendor/datepicker.min.css");?>

    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/main.js");?>
    <?Asset::getInstance()->addJs( "/service-worker.js");?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/custom.js");?>

    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon-96x96.png" sizes="96x96">

    <link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-180x180.png" />

    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" sizes="36x36" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-36x36.png">
    <link rel="apple-touch-icon" sizes="48x48" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-48x48.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="96x96" href="<?=SITE_TEMPLATE_PATH?>/favicon-96x96.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="192x192" href="<?=SITE_TEMPLATE_PATH?>/apple-touch-icon-180x180.png">

    <meta name="yandex-verification" content="fe78db7f439ecf37" />
    <meta name="google-site-verification" content="JGbxVu42DamM_otKtDj49Mz1m9lDUL5970CvWejoyqo" />
    <meta name='wmail-verification' content='5943aa8320f2628e922d7cd56609fa60' />
    <meta name="msvalidate.01" content="2D3BFA67AEC9FD308380E90ED585BE8B" />

    <meta name="yandex-verification" content="a0a9cf603f3704d0" />
    <meta name="google-site-verification" content="O6YNTYSQmE89RzrQVEIV9k1CEQs9B_ZYRtdVwtMq1vw" />

    <meta name="yandex-verification" content="b3d6c029f4bb64da" />

    <script type="text/javascript">
        var __cs = __cs || [];
        __cs.push(["setCsAccount", "OaucoNzGgWkIIWwsoI41CSiIKW4ngqfB"]);
    </script>
    <script type="text/javascript" async src="https://app.comagic.ru/static/cs.min.js"></script>

</head>

<body>
<?//$APPLICATION->ShowPanel();?>
<?include_once('defines.php');?>
<?if(!$IS_AUTH && !$IS_REG):?>
<main class="main">
    <div class="scroll-top" id="top"></div>
    <header class="header">
            <div class="container">
                <div class="header__content fr">
                    <div class="header__navigation">
                        <div class="header__burger">
                            <span></span>
                            <span></span>
                        </div>
                        <?$APPLICATION->IncludeComponent('electric:city.suggest', 'head', []);?>
                        <?$APPLICATION->IncludeComponent('electric:city.suggest', 'popup', ["SET_LOCATION"=>true]);?>
                        <div class="header__contact">
                            <a href="tel:<?=PHONE_TEL?>" class="main__text white" onclick="ym(56492371, 'reachGoal', 'click-phone'); return true;"><?=PHONE?></a>
                            <div class="main__text main__text--xs light">звонок бесплатный</div>
                        </div>
                    </div>
                    <a href="/" class="header__logo">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg" alt="Электрик.ру">
                    </a>

                    <?$APPLICATION->IncludeComponent('electric:auth.link', 'head', []);?>

                </div>
            </div>
        </header>

    <aside class="sidebar">
        <div class="sidebar__container">

            <?$APPLICATION->IncludeComponent('electric:menu.main', '', []);?>

            <?if (!$USER->IsAuthorized()):?>
                <div class="sidebar__inner">
                    <a href="/register/" class="btn btn--border-red"><span class="white">Стать исполнителем</span></a>
                </div>
            <?endif;?>

            <div class="sidebar__inner sidebar__inner--mob">
                <a href="tel:88001234567" class="main__text main__text--md white">8 800 123-45-67</a>
                <div class="main__text main__text--sm light">звонок бесплатный</div>
            </div>
        </div>
    </aside>

    <?if(!$IS_MAIN && !$IS_MARKETPLACE && !$IS_ARTICLES && !$IS_EDUCATION):?>
        <section class="<?echo $APPLICATION->GetDirProperty("class");?>">
            <div class="container <?echo $APPLICATION->GetDirProperty("class_container");?>">
    <?endif;?>
<?else:?>

    <? // Страница авторизации ?>
    <div class="app">
        <div class="app-inner js-app-inner js-menuUnder app-inner_flex">
        <div class="formSolo js-formSolo">
            <div class="formSolo-inner typicalBlock typicalBlock_one">
                <?if($IS_REG):?>
                    <?$APPLICATION->IncludeComponent('electric:form.banner', 'head', ["CODE"=> "REGISTER"]);?>
                <?elseif($IS_AUTH):?>
                    <?$APPLICATION->IncludeComponent('electric:form.banner', 'head', ["CODE"=> "AUTH"]);?>
                <?endif;?>

<?endif;?>
