<?php
namespace Electric;

use Bitrix\Main\Application;
use Electric\Helpers\DataHelper;

class Request
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
    private $user = "user";
    /**
     * @var string
     */
    private $passord = "pass";
    /**
     * @var string
     */
    private $host;

    private $action;

    public function __construct($user  = false, $pass  = false, $host = false)
    {
        if($host)
            $this->host = trim($host);
    }

    /**
     * @param array $parameters
     */
    public function execute(array $parameters = []){

        $headers = [
            'Accepts: application/json',
            'Content-Type:application/json'
        ];

        $url = $this->host;

        return $this->get($url, $parameters, $headers);

    }

    /**
     * @param $url
     * @param array $parameters
     * @param array $headers
     */
    private function get($url, array $parameters = [], array $headers = [])
    {
        $request = $this->prepareRequest($url, $parameters, $headers);

        return $this->executeRequest($request);
    }

    /**
     * @param $url
     * @param array $parameters
     * @param array $headers
     * @return false|resource
     */
    public function prepareRequest($url, $parameters = [], $headers = [])
    {
        $request = curl_init($this->host);

        if ($query = http_build_query($parameters))
            if(strpos($url,"?") !== false)
                $url .= '&' . $query;
            else
                $url .= '?' . $query;

        // Формируем json для отправки через curl
        $query = \GuzzleHttp\json_encode($parameters);
        $headers[] = 'Content-Length: ' . strlen($query) ;

        $url = str_replace("%26gt%3B",">",$url);

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $query);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);

        return $request;
    }

    /**
     * @param $request
     */
    public function executeRequest($request)
    {

        $body = curl_exec($request);
        $info = curl_getinfo($request);
        curl_close($request);
        $statusCode = $info['http_code'];
        return $this->build($body, $statusCode);
    }

    /**
     * @param $body
     * @param int $statusCode
     */
    public function build($body, $statusCode = 404)
    {

        $this->result = json_decode($body, true);
        $this->statusCode = $statusCode;
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

}