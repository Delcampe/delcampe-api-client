Call this function to authenticate yourself and get a personal token. When needed, you'll have to pass this token as first parameter when calling API functions. See detail of each API function to have more informations.

**Call authenticateUser() only once per session**. Your token will be available during 30 minutes.

### Call ###

  * syntax : authenticateUser(string ApiKey)

  * parameter : _string_ : your personal API key received from Delcampe

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
<SOAP-ENV:authenticateUser>
  <apiKey xsi:type="xsd:string">
    nUvUtHu98swUrAQuT5uaNAk5yaph89aspUr7pEmeecUHubRagaTe5HawexE
  </apiKey>
</SOAP-ENV:authenticateUser>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```


### Response ###

  * ResponseSimple, giving you a token

  * SOAP envelope
```
<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>
<SOAP-ENV:authenticateUserResponse>
  <authenticateUserReturn xsi:type="ns1:ResponseSimple">
    <status xsi:type="xsd:boolean">true</status>
    <errorMsg xsi:nil="true"/>
    <data xsi:type="xsd:string">327c1a8b5111b35105d9eac5b640</data>
    <personal_reference xsi:nil="true"/>
  </authenticateUserReturn>
</SOAP-ENV:authenticateUserResponse>
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
  var_export($return->data, 1);
} else {
  var_export($return->errorMsg, 1); 

}
```