To check if you have access to this function, call [getAuthorizedActions](getAuthorizedActions.md).

Call this function to close an item on Delcampe website.<br>
You cannot close an auction item when there's a bid on it.<br>
You have to specify the reason of the closing by selecting one of the valid reasons.<br>
<br>
This function call will be placed in a queue and processed later.<br>
<br>
<br>
<h3>Call</h3>

<ul><li>syntax : closeItem(string token, integer idItem, string reason)</li></ul>

<ul><li>parameter 1 : <i>string</i>  : secret token<br>
</li><li>parameter 2 : <i>integer</i> : id of the item you want to close<br>
</li><li>parameter 3 : <i>string</i>  : reason why you want to close the item. Call <a href='getAuthorizedCloseItemReasons.md'>getAuthorizedCloseItemReasons</a>() to get a list of valid reasons</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope <br>
    xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'<br>
    xmlns:xsd='http://www.w3.org/2001/XMLSchema'<br>
    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' <br>
    xmlns:ns1='http://xml.apache.org/xml-soap' <br>
    xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' <br>
    SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:closeItem&gt;<br>
  &lt;token xsi:type="xsd:string"&gt;e6c95bd2eb4cc458710106ecae9cde90&lt;/token&gt;<br>
  &lt;id_item xsi:type="xsd:int"&gt;99664633&lt;/id_item&gt;<br>
  &lt;reason xsi:type="xsd:string"&gt;TestItem&lt;/reason&gt;<br>
&lt;/SOAP-ENV:closeItem&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Response</h3>
<ul><li>ResponseSimple, giving you more information on the function call result</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:closeItemResponse&gt;<br>
  &lt;closeItemReturn xsi:type="ns1:ResponseSimple"&gt;<br>
    &lt;status xsi:type="xsd:boolean"&gt;true&lt;/status&gt;<br>
    &lt;errorMsg xsi:nil="true"/&gt;<br>
    &lt;data xsi:type="xsd:string"&gt;Your request was taken into account!&lt;/data&gt;<br>
    &lt;personal_reference xsi:nil="true"/&gt;<br>
  &lt;/closeItemReturn&gt;<br>
&lt;/SOAP-ENV:closeItemResponse&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Notification</h3>
<ul><li><a href='Notifications#Seller_Item_Close_Manually.md'>Seller_Item_Close_Manually</a></li></ul>

<h3>Code example (PHP)</h3>
<pre><code>//FUNCTION CALL<br>
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');<br>
$return = $objSoapClient-&gt;authenticateUser('MyPersonalApiKey');<br>
<br>
if ($return-&gt;status === true) {<br>
  $return = $objSoapClient-&gt;closeItem($return-&gt;data, 96485615, 'TestItem');<br>
<br>
  if ($return-&gt;status === true) {<br>
    var_export($return-&gt;data, 1);<br>
  } else {<br>
    var_export($return-&gt;errorMsg, 1);<br>
  } <br>
}<br>
</code></pre>