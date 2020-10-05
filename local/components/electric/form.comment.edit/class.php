<?php
namespace Electric\Component;

use Electric\Component\BaseComponent;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

class CommentEditForm extends BaseComponent
{
    private $commentID;

    public function onPrepareComponentParams($arParams)
    {
        $this->commentID = $arParams["COMMENT"];
        $this->text = $arParams["TEXT"];

        return $arParams;
    }

    public function executeComponent()
    {
        $this->arResult["COMMENT"] = $this->commentID;
        $this->arResult["TEXT"] = $this->text;

        $this->includeComponentTemplate();
    }
}
