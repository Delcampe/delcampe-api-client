To check if you have access to this function, call [getAuthorizedActions](getAuthorizedActions.md).

Call this function to add a new auction item on Delcampe website.

This new item will be placed in a queue and processed later.

You can place a maximum of 99 pictures for each item.

Note : There is no need to put the description, even with HTML tags, in a CDATA.

**Attention: there's no sandbox to make your tests**. The items you'll send will be seen by every Delcampe member.
We encourage you thus to send your Test items in small quantity and to immediately close them when your test is done.


### Call ###

  * syntax : addItemAuction(string token, object ItemAuctionParam)

  * parameter 1 : _string_ : secret token
  * parameter 2 : _object_ ItemAuctionParam  : containing the data needed to create an auction item

  * note : for parameter 2, php developers can use an array in place of an object. See [PHP code example](addItemAuction#Code_example_(PHP).md)

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
<SOAP-ENV:addItemAuction>
  <token xsi:type="xsd:string">f4cb687c225fd87ff9b204615ec802ad</token>
  <data xsi:type="ns1:ItemAuctionParam">
    <id_country xsi:type="xsd:int">0</id_country>
    <id_category xsi:type="xsd:int">80</id_category>
    <title xsi:type="xsd:string">MyTitle</title>
    <subtitle xsi:nil="true"/>
    <personal_reference xsi:type="xsd:string">MyPersRef</personal_reference>
    <description xsi:type="xsd:string">My description</description>
    <price_starting xsi:type="xsd:float">11</price_starting>
    <price_increment xsi:type="xsd:float">0.19</price_increment>
    <currency xsi:type="xsd:string">EUR</currency>
    <date_end xsi:nil="true"/>
    <duration xsi:nil="true"/>
    <prefered_end_day xsi:nil="true"/>
    <prefered_end_hour xsi:nil="true"/>
    <renew xsi:nil="true"/>
    <option_boldtitle xsi:type="xsd:boolean">false</option_boldtitle>
    <option_coloredborder xsi:type="xsd:boolean">true</option_coloredborder>
    <option_highlight xsi:type="xsd:boolean">false</option_highlight>
    <option_keepoptionsonrenewal xsi:type="xsd:boolean">false</option_keepoptionsonrenewal>
    <option_lastminutebidding xsi:type="xsd:boolean">false</option_lastminutebidding>
    <option_privatebidding xsi:type="xsd:boolean">false</option_privatebidding>
    <option_subtitle xsi:type="xsd:boolean">false</option_subtitle>
    <option_topcategory xsi:type="xsd:boolean">false</option_topcategory>
    <option_toplisting xsi:type="xsd:boolean">false</option_toplisting>
    <option_topmain xsi:type="xsd:boolean">false</option_topmain>
    <images SOAP-ENC:arrayType="xsd:string[2]" xsi:type="ns1:String[]">
    <item xsi:type="xsd:string">http://www.mypics.net/pictures/testPicsApi_1.jpg</item>
    <item xsi:type="xsd:string">http://www.mypics.net/pictures/testPicsApi_2.jpg</item>
    </images>
  </data>
</SOAP-ENV:addItemAuction>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Response ###

  * ResponseSimple, giving you more information on the function call result

  * SOAP envelope

```
<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<SOAP-ENV:Body>
<SOAP-ENV:addItemAuctionResponse>
  <addItemAuctionReturn xsi:type="ns1:ResponseSimple">
    <status xsi:type="xsd:boolean">true</status>
    <errorMsg xsi:nil="true"/>
    <data xsi:type="xsd:string">Your request was taken into account!</data>
    <personal_reference xsi:type="xsd:string">MyPersRef</personal_reference>
  </addItemAuctionReturn>
</SOAP-ENV:addItemAuctionResponse>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Notification ###
  * [Seller\_Item\_Add](Notifications#Seller_Item_Add.md) or [Seller\_Item\_Add\_Error\_Image](Notifications#Seller_Item_Add_Error_Image.md)

### Code example (PHP) ###
```
// MANDATORY DATA
$data['id_country']                  = 0;
$data['id_category']                 = 80;
$data['price_starting']              = '1.00';
$data['price_increment']             = '0.01';
$data['currency']                    = 'EUR';
$data['title']                       = 'My title';
$data['personal_reference']          = 'My personal ref';

//OPTIONAL DATA
$data['price_reserve']               = '5.00';
$data['date_end']                    = '2010-07-01 10:00:00';
$data['duration']                    = 10;
$data['description']                 = 'My description'
$data['renew']                       = 2;
$data['option_lastminutebidding']    = true;
$data['option_privatebidding']       = false;
$data['option_subtitle']             = true;
$data['subtitle']                    = 'My subtitle';
$data['option_boldtitle']            = true;
$data['option_highlight']            = false;
$data['option_coloredborder']        = true;
$data['option_toplisting']           = false;
$data['option_topcategory']          = false;
$data['option_topmain']              = false;
$data['option_keepoptionsonrenewal'] = true;
$data['prefered_end_day']            = 'mon';
$data['prefered_end_hour']           = '15:00';

//OPTIONAL IMAGES
$data['images']                      = array('http://www.mypictures.com/pic1.jpg',
                                             'http://www.mypictures.com/pic2.jpg',
	                                     'http://www.mypictures.com/pic3.jpg'
                                            );

//FUNCTION CALL
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');
$return = $objSoapClient->authenticateUser('MyPersonalApiKey');

if ($return->status === true) {
  $return = $objSoapClient->addItemAuction($return->data, $data);

  if ($return->status === true) {
    var_export($return->data, 1);
  } else {
    var_export($return->errorMsg, 1);
  } 
}
```