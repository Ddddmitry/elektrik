<?php
namespace Electric\Component;

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\GroupTable;
use \Electric\Helpers\IblockHelper;

/**
 * Class BaseComponent
 */
class BaseComponent extends \CBitrixComponent
{
    public $requiredModuleList = [];
    public $arGroupAvalaible = [];

    /**
     * @return bool
     */
    public function checkRequiredModuleList()
    {
        $result = true;

        foreach ($this->requiredModuleList as $moduleName) {
            if (!Loader::includeModule($moduleName)) {
                $result = false;
            }
        }

        return $result;
    }

    public function getRequest()
    {
        return Application::getInstance()->getContext()->getRequest();
    }

    /**
     * Get Group Id by string code.
     *
     * @param $code
     */
    public function getUserGroupId($code)
    {
        if (!$code) {
            return false;
        }

        $id = null;
        $result = GroupTable::getList(array(
            'select' => ['NAME', 'ID', 'STRING_ID'],
            'filter' => ['=STRING_ID' => $code],
            'limit' => 1
        ));
        if($arGroup = $result->fetch()) {
            $id = $arGroup['ID'];
        }

        return $id;
    }

    /**
     * Get Iblock Id.
     *
     * @param $code
     * @param $type
     * @param $siteId
     * @param $cacheTime
     * @return bool|null
     */
    public function getIblockId($code, $type, $siteId, $cacheTime = '9999999')
    {
        return IblockHelper::getIblockId($code, $type, $siteId, $cacheTime);
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        $result = false;

        global $USER;
        $arGroups = $USER->GetUserGroupArray();

        if (!empty($arGroups) && !empty($this->arGroupAvalaible)) {
            $resIntersect = array_intersect($this->arGroupAvalaible, $arGroups);

            if (!empty($resIntersect)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isUserGroup($groupId)
    {
        global $USER;
        return in_array($groupId, $USER->GetUserGroupArray());
    }

    public function set404()
    {
        global $APPLICATION;
        //$APPLICATION->RestartBuffer();
        require(\Bitrix\Main\Application::getDocumentRoot()."/404.php");
        die;
    }

    public function setTwigLayout($layout)
    {
        global $APPLICATION;
        $APPLICATION->SetPageProperty('layout', $layout);
    }

    public function isAjaxRequest() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    public function restartBuffer(){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
    }

}
