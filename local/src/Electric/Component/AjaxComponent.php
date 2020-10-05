<?php

namespace Electric\Component;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AjaxComponent extends \CBitrixComponent
{
    const MODULE_IBLOCK = 'iblock';

    protected
        $obResponse,
        $arResponseData = []
    ;

    protected $modules = [];
    
    public function __construct($component)
    {
        parent::__construct($component);

        $this->arResult['this'] = $this;

        $this->obResponse = new JsonResponse();
        $this->obResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function addResponseData($arResponseData) {
        $this->arResponseData = array_merge($this->arResponseData, $arResponseData);
        return $this;
    }

    public function setResponseData($arResponseData) {
        $this->arResponseData = $arResponseData;
        return $this;
    }

    public function executeComponent()
    {
        try {

            $this->includeModules();
            $this->parseRequest();
            $this->executeAjaxComponent();
            $this->obResponse->setData(array_merge(['success' => true], $this->getResponseData()));
            $this->obResponse->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $e) {
                $this->obResponse->setStatusCode($e->getCode() == 400 ? 400 : Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->obResponse->setData([
                'success' => false,
                'error' => [
                    'exception' => get_class($e),
                    'code' => $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage(),
                ],
            ]);
        }

        Application::getInstance()->getContext()->getResponse()->setStatus($this->obResponse->getStatusCode());
        Application::getInstance()->getContext()->getResponse()->addHeader('Content-Type', 'application/json; charset=utf-8');
        echo $this->obResponse->getContent();
    }

    private function includeModules() {
        foreach ($this->modules as $module) {
            Loader::includeModule($module);
        }
    }

    abstract protected function parseRequest();

    abstract protected function executeAjaxComponent();

    public function getResponseData() {
        return $this->arResponseData;
    }

    protected function getResponse() {
        return $this->obResponse;
    }
}