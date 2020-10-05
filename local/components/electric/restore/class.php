<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class UserRestoreRequest extends BaseComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    /**
     * Проверка на наличие параметров для сброса пароля в URL
     */
    protected function checkParams()
    {
        $result = false;

        $request = $this->getRequest();
        if($request->getQuery('change') == 'yes') {
            $fields = $request->getQueryList()->toArray();

            $result['checkword'] = $fields['checkword'];
            $result['login'] = $fields['login'];
        }

        return $result;
    }

    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            LocalRedirect('/');
        }

        $params = $this->checkParams();
        if(!$result) {
            $this->arResult['params'] = $params;
        }
        $this->includeComponentTemplate();
    }
}
