<?php
try {
    $client = new SoapClient( "http://es.elektro.ru/elektrikservice/elektrikservice.svc?wsdl" , array(
    'cache_wsdl' => 0,
));
    var_dump($client->__getFunctions());
} catch (SoapFault $e) {
    echo $e->getMessage();
}


?>