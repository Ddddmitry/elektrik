<?php
namespace Api\Components;

use \Electric\Component\AjaxComponent;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;
use Electric\Contractor;

class ApiBackofficeUpdateAnswer extends AjaxComponent
{
    protected $reviewID, $answerText;

    protected function parseRequest()
    {
        //$input = json_decode($this->request->getInput(), true);

        $input = $this->request->getPostList()->toArray();

        $this->reviewID = $input['reviewID'];
        $this->answerText = $input['answerText'];
    }


    /**
     * Добавление/обновление ответа на отзыв
     *
     * @return bool
     * @throws \Exception
     */
    protected function updateAnswer()
    {
        \CModule::IncludeModule('iblock');

        if (!$this->reviewID) throw new \Exception("review_id_is_required");

        global $USER;
        $userID = $USER->GetID();

        $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_USER"];
        $arFilter = [
            "IBLOCK_ID" => IBLOCK_REVIEWS,
            "=ID" => $this->reviewID,
        ];
        $arReview = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect)->Fetch();
        if ($arReview["PROPERTY_USER_VALUE"] != $userID) throw new \Exception("access_denied");

        $obContractor = new Contractor();
        $result = $obContractor->updateReviewAnswer($this->reviewID, $this->answerText);

        return $result;
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            //return false;
        }

        try {
            $result = $this->updateAnswer();
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);
            return false;
        }

        $this->addResponseData(['result' => $result]);
    }
}
