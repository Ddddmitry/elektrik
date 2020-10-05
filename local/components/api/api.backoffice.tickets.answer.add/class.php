<?php
namespace Api\Components;

use Electric\Component\AjaxComponent;
use Electric\Helpers\DataHelper;
use Electric\Notification;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;


class ApiBackofficeTicketsAnswerAdd extends AjaxComponent
{
    private $ticket, $text;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->ticket = $input["ticket"];
        $this->text = $input["text"];
    }

    /**
     * Добавление ответа автора обращения на ответ поддержки
     */
    protected function addAuthorAnswer()
    {
        global $USER;
        $userID = $USER->GetID();
        if (!$userID) throw new \Exception("authorization_required");

        if ($this->ticket) {
            \CIBlockElement::SetPropertyValuesEx($this->ticket, false, ["AUTHOR_ANSWER" => $this->text]);
        } else {
            throw new \Exception("element_not_found");
        }

        return true;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        \Bitrix\Main\Loader::IncludeModule("iblock");

        try {
            $result = $this->addAuthorAnswer();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
