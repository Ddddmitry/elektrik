<?php
namespace Api\Components;

use Electric\Component\AjaxComponent;
use Electric\Notification;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;


class ApiArticlesPropose extends AjaxComponent
{
    private $name, $comment, $arFile;

    protected function parseRequest()
    {
        $inputPost = $this->request->getPostList();
        $inputFiles = $this->request->getFileList();

        $this->name = $inputPost["newart-title"];
        $this->comment = $inputPost["newart-comm"];
        $this->arFile = $inputFiles["newart-file"];
    }

    /**
     * Создание предложения на размещение статьи
     */
    protected function addArticleProposal()
    {
        if (!$this->arFile || !$this->arFile["size"]) throw new \Exception("no_files_uploaded");

        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . "/upload/";
        $file = file_get_contents($this->arFile["tmp_name"]);
        file_put_contents($uploadPath . $this->arFile["name"], $file);
        $arFile = \CFile::MakeFileArray($uploadPath . $this->arFile["name"]);

        $obElement = new \CIBlockElement;
        $arFields = Array(
            "NAME" => $this->name ? $this->name : "Предложенная статья",
            "PREVIEW_TEXT" => $this->comment,
            "IBLOCK_ID" => IBLOCK_ARTICLES_PROPOSALS,
        );
        if ($elementID = $obElement->Add($arFields)) {
            $arProperties = [
                "USER" => $userID,
                "FILE" => $arFile,
            ];
            \CIBlockElement::SetPropertyValuesEx($elementID, false, $arProperties);

            unlink($file["tmp_name"]);

            $arFileProperty = \CIBlockElement::GetProperty(IBLOCK_ARTICLES_PROPOSALS, $elementID, [], ["CODE" => "FILE"])->Fetch();
            $arFile = \CFile::GetFileArray($arFileProperty["VALUE"]);
            $arMailData = [
                "USER_ID" => $userID,
            ];
            Notification::sendNotification("ELECTRIC_NEW_ARTICLE", $arMailData, [$_SERVER["DOCUMENT_ROOT"].$arFile["SRC"]]);

            return $elementID;
        } else {
            if (!$userID) throw new \Exception("element_creation_error");
        }
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addArticleProposal();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
