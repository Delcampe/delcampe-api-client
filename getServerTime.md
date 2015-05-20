Call this function to get Delcampe's server time


### Call ###

  * syntax : getServerTime()

  * SOAP envelope
```
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'
    xmlns:xsd='http://www.w3.org/2001/XMLSchema'
    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' 
    xmlns:ns1='http://xml.apache.org/xml-soap' 
    xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' 
    SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'>
<SOAP-ENV:Body>
<SOAP-ENV:getServerTime/>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Response ###

  * response : _timestamp_ : Delcampe's server time

  * SOAP envelope
```
<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>
<SOAP-ENV:getServerTimeResponse>
  <getServerTimeReturn xsi:type="xsd:int">1281535017</getServerTimeReturn>
</SOAP-ENV:getServerTimeResponse>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Notification ###
  * None

### Code example (PHP) ###
```
//FUNCTION CALL
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');
list($success, $token) = $objSoapClient->authenticateUser('MyPersonalApiKey');

if (true === $success) {
  $return = $objSoapClient->getServerTime();
}
```