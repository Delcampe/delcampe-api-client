Call this function to get the list of authorized reasons to close an item, used when calling [closeItem](closeItem.md).


### Call ###

  * syntax : getAuthorizedCloseItemReasons()

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
<SOAP-ENV:getAuthorizedCloseItemReasons/>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Response ###

  * ResponseComplex, giving you a list of authorized reasons to close an item

  * SOAP envelope
```
<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>
<SOAP-ENV:getAuthorizedCloseItemReasonsResponse>
  <getAuthorizedCloseItemReasonsReturn xsi:type="ns1:ResponseComplex">
  <data SOAP-ENC:arrayType="ns1:String[4]" xsi:type="ns1:ArrayOfString">
    <item xsi:type="ns1:String">
      <data xsi:type="xsd:string">TestItem</data>
    </item>
    <item xsi:type="ns1:String">
      <data xsi:type="xsd:string">LostOrBrokenItem</data>
    </item>
    <item xsi:type="ns1:String">
      <data xsi:type="xsd:string">NotAvailableItem</data>
    </item>
    <item xsi:type="ns1:String">
      <data xsi:type="xsd:string">IncorrectItem</data>
    </item>
  </data>
  <status xsi:type="xsd:boolean">true</status>
  <errorMsg xsi:nil="true"/>
  <personal_reference xsi:nil="true"/>
  </getAuthorizedCloseItemReasonsReturn>
</SOAP-ENV:getAuthorizedCloseItemReasonsResponse>
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
  $return = $objSoapClient->getAuthorizedCloseItemReasons();

  if ($return->status === true) {
    foreach ($return->data as $objValue) {     
      var_export($objValue->data, 1);
    }
  } else {
    var_export($return->errorMsg, 1);
  } 
}
```