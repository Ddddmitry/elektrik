<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Electric\Notification;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use \Electric\Helpers\FileHelper;

class ApiBackofficeArticlesAdd extends AjaxComponent
{
    private $formData;

    protected function parseRequest()
    {
        //$input = json_decode($this->request->getInput(), true);
        $input = $this->request->getPostList()->toArray();
        $this->formData = [
            "TITLE" => $input['NAME'],
            "CONTENT" => $input['PREVIEW_TEXT'],
            "IMAGE" => $this->request->getFile("PREVIEW_PICTURE"),
        ];
    }

    /**
     * Создание новой статьи
     *
     */
    protected function addArticle()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $arImage = FileHelper::saveFileFromForm($this->formData["IMAGE"]);

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => \ConvertTimeStamp(time(), "FULL"),
            "NAME" => $this->formData["TITLE"],
            "CODE" => \Cutil::translit($this->formData["TITLE"], "ru"),
            "IBLOCK_ID" => IBLOCK_ARTICLES,
            "DETAIL_TEXT" => $this->formData["CONTENT"],
            "DETAIL_TEXT_TYPE" => "html",
            "PREVIEW_PICTURE" => $arImage,
        );

        if ($elementID = $obElement->Add($arFields)) {
            $arProperties = [
                "USER" => $userID,
            ];

            // Получение элемента категории "Предложенные статьи" по символьному коду
            // и назначение этого типа создаваемой статье
            $arType = \CIBlockElement::GetList([], ["IBLOCK_ID" => IBLOCK_ARTICLES_TYPES, "=CODE" => ARTICLE_TYPE_PROPOSED_CODE], false, false, ["ID"])->Fetch();
            if ($arType) $arProperties["TYPE"] = $arType["ID"];

            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            $arMailData = [
                "USER_ID" => $userID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_ARTICLE", $arMailData);

            return $elementID;
        } else {
            if (!$userID) throw new \Exception("element_creation_error");
            else throw new \Exception($obElement->LAST_ERROR);
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addArticle();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
