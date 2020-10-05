<?php
namespace Api\Components;

use Electric\Component\AjaxComponent;
use Electric\Location;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;

class ApiOrderLocationSuggest extends AjaxComponent
{
    private $requestData;

    protected function parseRequest()
    {
        $input = json_decode($this->request->getInput(), true);

        $this->requestData = [
            "SEARCH_PHRASE" => strtolower(trim($input['searchPhrase'])),
            "SHOW_ONLY_CITIES" => $input['showOnlyCities'] ? true : false,
            "RESTRICTED" => $input['restricted']
        ];
    }

    protected function executeAjaxComponent()
    {
        if (!$this->request->isAjaxRequest()) {
            // return false;
        }

        try {
            if ($this->requestData["SHOW_ONLY_CITIES"]) $arOptions["SHOW_ONLY_CITIES"] = true;
            if ($this->requestData["RESTRICTED"]) $arOptions["RESTRICTED"] = $this->requestData["RESTRICTED"];

            $arSuggestions = Location::getLocationSuggestions($this->requestData["SEARCH_PHRASE"], $arOptions);
        } catch (\Exception $e) {
            $this->addResponseData(['error' => $e->getMessage()]);

            return false;
        }

        $this->addResponseData(['result' => $arSuggestions]);
    }
}
