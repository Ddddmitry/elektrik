<?php

namespace Core\Twig;

use Bitrix\Main\Application;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;

class BitrixTwigExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'bitrix';
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return [
            'LANG'                  => LANG,
            'LANGUAGE_ID'           => LANGUAGE_ID,
            'SITE_DIR'              => SITE_DIR,
            'SITE_ID'               => SITE_ID,
            '_get'                  => $_GET,
            '_post'                 => $_POST,
            'server'                => $_SERVER,
            '_cookie'               => $_COOKIE,
            '__FILE__'              => __FILE__,
            'SITE_TEMPLATE_PATH'    => SITE_TEMPLATE_PATH,
            'SITE_TEMPLATE_FULL_PATH' => $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH,
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        global $APPLICATION;
        global $USER;

        Loader::includeModule('iblock');

        return [
            // Bitrix
            new \Twig_SimpleFunction('includeComponent', [$this, 'includeComponent']),
            new \Twig_SimpleFunction('shouldShowPanel', ['CTopPanel', 'shouldShowPanel']),
            new \Twig_SimpleFunction('showTitle', [$APPLICATION, 'showTitle']),
            new \Twig_SimpleFunction('getTitle', [$APPLICATION, 'getTitle']),
            new \Twig_SimpleFunction('setTitle', [$APPLICATION, 'setTitle']),
            new \Twig_SimpleFunction('showHead', [$APPLICATION, 'showHead']),
            new \Twig_SimpleFunction('showHeadStrings', [$APPLICATION, 'ShowHeadStrings']),
            new \Twig_SimpleFunction('showHeadScripts', [$APPLICATION, 'ShowHeadScripts']),
            new \Twig_SimpleFunction('showCSS', [$APPLICATION, 'showCSS']),
            new \Twig_SimpleFunction('showPanel', [$APPLICATION, 'showPanel']),
            new \Twig_SimpleFunction('htmlspecialcharsBack', 'htmlspecialcharsBack', ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getProperty', [$APPLICATION, 'GetProperty']),
            new \Twig_SimpleFunction('getPageProperty', [$APPLICATION, 'GetPageProperty']),
            new \Twig_SimpleFunction('showProperty', [$APPLICATION, 'ShowProperty']),
            new \Twig_SimpleFunction('getCurPage', [$APPLICATION, 'GetCurPage']),
            new \Twig_SimpleFunction('getCurDir', [$APPLICATION, 'GetCurDir']),
            new \Twig_SimpleFunction('inDir', ['CSite', 'InDir']),
            new \Twig_SimpleFunction('ExpocentrHelpersRecaptchaRender', ['\Expocentr\Helpers\Recaptcha', 'render']),
            new \Twig_SimpleFunction('AddBitrixActions', function($arItem){
                $component = new \CBitrixComponent();
                $component->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], \CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $component->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], \CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => 'Удалить элемент?']);
            }),
            new \Twig_SimpleFunction('GetEditAreaId', function($arItem){
                $component = new \CBitrixComponent();
                return $component->GetEditAreaId($arItem['ID']);
            }),
            new \Twig_SimpleFunction('getRequestedPageDirectory', function(){
                $request = Context::getCurrent()->getRequest();
                return $request->getRequestedPageDirectory();
            }),

            // User
            new \Twig_SimpleFunction('getUserID', [$USER, 'GetID']),
            new \Twig_SimpleFunction('isUserAdmin', [$USER, 'IsAdmin']),
            new \Twig_SimpleFunction('isUserAuthorized', [$USER, 'IsAuthorized']),

            //File
            new \Twig_SimpleFunction('getFilePath', ['CFile', 'getPath']),
            new \Twig_SimpleFunction('getFileArray', ['CFile', 'getFileArray']),

            // Session
            new \Twig_SimpleFunction('bitrixSessidPost', 'bitrix_sessid_post', ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('bitrixSessidGet', 'bitrix_sessid_get', ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('bitrixSessid', 'bitrix_sessid'),

            // Notification
            new \Twig_SimpleFunction('showMessage', [$this, 'showMessage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('showError', [$this, 'showError'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('showNote', [$this, 'showNote'], ['is_safe' => ['html']]),

            //Localization
            new \Twig_SimpleFunction('getMessage', ['Bitrix\Main\Localization\Loc', 'getMessage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('loadMessages', ['Bitrix\Main\Localization\Loc', 'loadMessages']),

            //Assets
            new \Twig_SimpleFunction('addJs', [$this, 'addJs']),
            new \Twig_SimpleFunction('addCss', [$this, 'addCss']),

            // Copy Date
            new \Twig_SimpleFunction('showCopyDate', [$this, 'showCopyDate']),

            // Site
            new \Twig_SimpleFunction('InDir', ['CSite', 'InDir']),
            new \Twig_SimpleFunction('getGlobals', [$this, 'getGlobals']),
        ];
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('formatDate', 'FormatDateFromDB'),
        ];
    }

    /**
     * @param $message
     */
    public function showMessage($message)
    {
        ShowMessage($message);
    }

    /**
     * @param $message
     * @param string $css_class
     */
    public function showError($message, $css_class = "errortext")
    {
        ShowError($message, $css_class);
    }

    /**
     * @param $message
     * @param string $css_class
     */
    public function showNote($message, $css_class = "notetext")
    {
        ShowNote($message, $css_class);
    }

    /**
     * @param string $componentName
     * @param string $componentTemplate
     * @param array $arParams
     * @param null $parentComponent
     * @param array $arFunctionParams
     * @param bool|false $retVal
     * @return mixed|null
     */
    public function includeComponent($componentName,
                                     $componentTemplate = '.default',
                                     array $arParams = [],
                                     $parentComponent = null,
                                     array $arFunctionParams = [],
                                     $retVal = false
    )
    {
        global $APPLICATION;
        $result = $APPLICATION->IncludeComponent($componentName, $componentTemplate, $arParams, $parentComponent, $arFunctionParams);

        return $retVal ? $result : null;
    }

    /**
     *
     * @param array $arParams
     * @return string
     */
    public function showLHEEditor($arParams)
    {
        \Bitrix\Main\Loader::includeModule('fileman');

        $LHE = new \CLightHTMLEditor();

        $arEditorParams = array(
            'id' => $arParams['TOPIC_ID'],
            'content' => "",
            'inputName' => $arParams['fieldName'] ?:"REVIEW_TEXT",
            'inputId' => "",
            'width' => "100%",
            'height' => "200px",
            'minHeight' => "200px",
            'bUseFileDialogs' => false,
            'bUseMedialib' => false,
            'BBCode' => true,
            'bBBParseImageSize' => true,
            'jsObjName' => $arParams["jsObjName"],
            'toolbarConfig' => array(),
            'smileCountInToolbar' => 3,
            'arSmiles' => $arSmiles,
            'bQuoteFromSelection' => true,
            'ctrlEnterHandler' => 'commentsCtrlEnterHandler_'.$arParams['topicId'],
            'bSetDefaultCodeView' => false,
            'bResizable' => true,
            'bAutoResize' => true,
            'bInitByJS' => true
        );

        $arEditorFeatures = array(
            "ALLOW_BIU" => array('Bold', 'Italic', 'Underline', 'Strike'),
            "ALLOW_QUOTE" => array('Quote'),
            'ALLOW_ANCHOR' => array('CreateLink', 'DeleteLink'),
            "ALLOW_IMG" => array('Image'),
            "ALLOW_VIDEO" => array('ForumVideo'),
            "ALLOW_UPLOAD" => array(''),
            "ALLOW_NL2BR" => array(''),
        );
        foreach ($arEditorFeatures as $featureName => $toolbarIcons)
        {
            $arEditorParams['toolbarConfig'] = array_merge($arEditorParams['toolbarConfig'], $toolbarIcons);
        }
        $arEditorParams['toolbarConfig'] = array_merge($arEditorParams['toolbarConfig'], array('Source'));

        ob_start();
        $LHE->Show($arEditorParams);
        $html = ob_get_clean();

        $arConfig = $LHE->JSConfig;
        $html = '<div data-lhe data-id="'.$arConfig['id'].'" data-params=\''.json_encode($arConfig).'\'>'.$html.'</div>';

        return $html;
    }

    public function addCss($path)
    {
        $asset = $this->getAsset($path);
        $assetInstance = Asset::getInstance();
        $assetInstance->addCss($asset);
    }

    public function addJs($path)
    {
        $asset = $this->getAsset($path);
        $assetInstance = Asset::getInstance();
        $assetInstance->addJs($asset);
    }

    public function getAsset($path)
    {
        $url = parse_url($path);
        $serverName = Application::getInstance()->getContext()->getServer()->getServerName();
        if (array_key_exists('host', $url) && $url['host'] != $serverName) {
            return $path;
        }

        $pathAbs = $_SERVER['DOCUMENT_ROOT'] . $path;

        if (!file_exists($pathAbs)) {
            return false;
        }

        $pathInfo = pathinfo($url['path']);

        $minPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.min.' . $pathInfo['extension'];
        $minPathAbs = $_SERVER['DOCUMENT_ROOT'] . $minPath; //сжимается битрикс-агентом

        $canUseMinifiedAssets = Option::get('main', 'use_minified_assets', 'Y') == 'Y';

        if ($canUseMinifiedAssets && file_exists($minPathAbs) && filemtime($minPathAbs) > filemtime($pathAbs) && filesize($minPathAbs) > 0) {
            return $minPath;
        } else {
            return $path;
        }
    }

    public function showCopyDate()
    {
        return date('Y');
    }
}
