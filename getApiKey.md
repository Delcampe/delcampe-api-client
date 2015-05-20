To check if you have access to this function, call [getAuthorizedActions](getAuthorizedActions.md).

Call this function to get an api key. This function is used only for third-party applications : see ServiceAuthenticationProcess for more details.

### Call ###

  * syntax : getApiKey(string token, string accessKey)

  * parameter 1 : _string_ : secret token
  * parameter 2 : _string_ : access key received by calling [getAccessKey](getAccessKey.md)

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
<SOAP-ENV:getApiKey>
  <token xsi:type="xsd:string">5204038adeab61e144cf2dd90af</token>
  <accessKey xsi:type="xsd:string">e541fee822f9e1c2bb7408bd0345</accessKey>
</SOAP-ENV:getApiKey>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Response ###

  * ResponseApiKey, giving you the API key

  * SOAP envelope
```
<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>
<SOAP-ENV:getApiKeyResponse>
  <getApiKeyReturn xsi:type="ns1:ResponseApiKey">
    <status xsi:type="xsd:boolean">true</status>
    <errorMsg xsi:nil="true"/>
    <apiKey xsi:type="xsd:string">5J68YPGuA7cfQAWFucUiRwxJKChwfAQAbcgaRPcicfXTFT8aViSK6XmZAu</apiKey>
    <accepted xsi:type="xsd:boolean">true</accepted>
  </getApiKeyReturn>
</SOAP-ENV:getApiKeyResponse>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Notification ###
  * None

### Code example (PHP) ###
```
//FUNCTION CALL
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');
$return = $objSoapClient->authenticateUser('MyPersonalApiKey');

if ($return->status === true) {
  $return = $objSoapClient->getApiKey($return->data, 'e541fee822f9e1c2bb7408bd0345');

  if ($return->status === true) {
    var_export('Api key : ' . $return->apiKey, 1);
  } else {
    var_export($return->errorMsg, 1);
  } 
}
```