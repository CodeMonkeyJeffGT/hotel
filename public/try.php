<?php

$soap = new SoapClient('http://47.93.39.7:8080/SOA/webservice/WebserviceTest?wsdl');
$param = array();
$arr = $soap->__soapCall('getAllScenery', array('parameters' => $param));
echo '<pre>';
print_r($arr);
