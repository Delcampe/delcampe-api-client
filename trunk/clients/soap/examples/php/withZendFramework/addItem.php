<?php


$_API_CLIENT_CONF = parse_ini_file ( '../config.api.ini' );

Zend_Loader::loadClass('Zend_Soap_Client');
$objSoapClient = new Zend_Soap_Client('http://api.delcampe.net/soap.php?wsdl');

$item_data['id_country']      = 0;
$item_data['id_site']         = 1;
$item_data['id_category']     = 80;
$item_data['price_starting']  = rand(10, 15) . '.00';
$item_data['price_increment'] = '0.' . rand(10, 99);
$item_data['currency']        = 'EUR';
$item_data['title']           = 'FFF Testing item . ' . date('Y-m-d H:i:s');

                                            
foreach ($item_data as $key => $value) {
    if (is_string($value)) {
        $item_data[$key] = utf8_encode($value);
    }
}

list($success, $token) = $objSoapClient->authenticateUser($_API_CLIENT_CONF['apiKey']);

printVar($success);
printVar($token);

if (true === $success) {
    $return = $objSoapClient->addItem($item_data, $token);
    printVar($return);
} else {
    printVar($token);
}


