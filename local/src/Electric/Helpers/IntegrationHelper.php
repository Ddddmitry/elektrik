<?php

namespace Electric\Helpers;

use Bitrix\Main\Application;

class IntegrationHelper
{
    const COMPONENTS_NAMESPACE = 'integration';

    static public function init()
    {

        if (($component = static::guessComponent()) !== false) {
            static::includeComponent($component);
        } else {
            LocalRedirect('/404/');
        }
    }

    static function guessComponent()
    {
        $component = Application::getInstance()->getContext()->getRequest()->get('action');
        $phone = Application::getInstance()->getContext()->getRequest()->get('phone');
        $uid = Application::getInstance()->getContext()->getRequest()->get('uid');
        $points = Application::getInstance()->getContext()->getRequest()->get('points');
        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/integration_log.txt");
        AddMessage2Log("action: ".$component, "integration");
        AddMessage2Log("phobe: ".$phone, "integration");
        AddMessage2Log("uid: ".$uid, "integration");
        AddMessage2Log("points: ".$points, "integration");
        if ($component = Application::getInstance()->getContext()->getRequest()->get('action')) {
            if (static::checkComponent($component)) {
                return $component;
            }
        }

        return false;
    }

    static function checkComponent($component)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/components/' . static::COMPONENTS_NAMESPACE . '/i.' . $component . '/class.php')) {
            return true;
        }

        return false;
    }

    static function includeComponent($component)
    {
        global $APPLICATION;
        $APPLICATION->IncludeComponent(static::COMPONENTS_NAMESPACE . ':i.'.$component, '', []);
    }
}