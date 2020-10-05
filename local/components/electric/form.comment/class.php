<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class CommentForm extends BaseComponent
{
    private $userID, $articleID, $commentID;

    public function onPrepareComponentParams($arParams)
    {
        $this->articleID = $arParams["ARTICLE"];
        $this->commentID = $arParams["COMMENT"];

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->arResult["IS_AUTHORIZED"] = true;
        };

        $this->arResult["ARTICLE"] = $this->articleID;
        $this->arResult["COMMENT"] = $this->commentID;

        $this->includeComponentTemplate();
    }
}
