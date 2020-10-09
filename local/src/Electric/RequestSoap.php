<?php
namespace Electric;

use Bitrix\Main\Application;
use Electric\Helpers\DataHelper;
use SoapClient;


class RequestSoap
{

    /**
     * @var
     */
    private $result;
    /**
     * @var
     */
    private $statusCode;

    /**
     * @var string
     */
    private $host;

    private $action;

    public function __construct( $host = false)
    {
        try {
            $this->checkClass();
        } catch (\Exception $e) {
            return false;
        }

        if($host)
            $this->host = trim($host);

        ini_set("soap.wsdl_cache_enabled", "0" );

    }

    /**
     * @param array $parameters
     */
    public function execute(array $parameters = [], array $options = []){

        $url = $this->host;

        return $this->get($url, $parameters, $options);

    }

    /**
     * @param $url
     * @param array $parameters
     */
    private function get($url, array $parameters = [], array $options = [])
    {
        $request = $this->prepareRequest($url,$options);

        return $this->executeRequest($request, $parameters);
    }

    /**
     * @param $url
     * @param array $options
     * @return false|resource
     */
    public function prepareRequest($url, array $options = [])
    {
        $request = new SoapClient($url, $options);

        return $request;
    }

    /**
     * @param $request
     * @param array $parameters
     */
    public function executeRequest($request,$parameters=[])
    {
        //var_dump($parameters);die();
        $jsonParameters = json_encode($parameters);
        $body = $request->__soapCall('CheckPhone', $parameters);
        //$body = $request->CheckPhone(["phone" => $parameters]);
        return $this->build($body->return);
    }

    /**
     * @param $body
     */
    public function build($body)
    {
        $this->result = json_decode($body, true);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setHost($host){
        $this->host = trim($host);
    }

    private function checkClass(){
        if (!class_exists('SoapClient')){
            throw new \Exception("Включите поддержку SOAP в PHP");
        }
        return true;
    }

}