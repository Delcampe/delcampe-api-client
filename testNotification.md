To check if you have access to this function, call [getAuthorizedActions](getAuthorizedActions.md).

Call this function to test the reception of a specific XML notification type.<br>
The returned notification contains data of a dummy item.<br>

For more information about Delcampe's notifications, see <a href='Notifications.md'>Notifications</a>.<br>
<br>
<h3>Call</h3>

<ul><li>syntax : testNotification(string token, string type)</li></ul>

<ul><li>parameter 1 : <i>string</i> : secret token<br>
</li><li>parameter 2 : <i>string</i> : notification type. To get a list of available notifications types, call <a href='getAvailableNotifications.md'>getAvailableNotifications</a>. Only XML notifications can be tested.</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope <br>
    xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'<br>
    xmlns:xsd='http://www.w3.org/2001/XMLSchema'<br>
    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' <br>
    xmlns:ns1='http://xml.apache.org/xml-soap' <br>
    xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' <br>
    SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:testNotification&gt;<br>
  &lt;token xsi:type="xsd:string"&gt;bf56d65bd9bab33dbf5afec567b428f4&lt;/token&gt;<br>
  &lt;notificationType xsi:type="xsd:string"&gt;Curl_Seller_Item_Close_Sold&lt;/notificationType&gt;<br>
&lt;/SOAP-ENV:testNotification&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Response</h3>

<ul><li>ResponseSimple, giving you more information on the function call result</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:testNotificationResponse&gt;<br>
  &lt;testNotificationReturn xsi:type="ns1:ResponseSimple"&gt;<br>
  &lt;status xsi:type="xsd:boolean"&gt;true&lt;/status&gt;<br>
  &lt;errorMsg xsi:nil="true"/&gt;<br>
  &lt;data xsi:type="xsd:string"&gt;<br>
    A test notification of type Curl_Seller_Item_Close_Sold has been sent!<br>
  &lt;/data&gt;<br>
  &lt;personal_reference xsi:nil="true"/&gt;<br>
  &lt;/testNotificationReturn&gt;<br>
&lt;/SOAP-ENV:testNotificationResponse&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Notification</h3>
<ul><li>The notification you want to test</li></ul>

<h3>Code example (PHP)</h3>
<pre><code>//FUNCTION CALL<br>
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');<br>
<br>
$return = $objSoapClient-&gt;authenticateUser('MyPersonalApiKey');<br>
<br>
if ($return-&gt;status === true) {<br>
  $return = $objSoapClient-&gt;testNotification($return-&gt;data, 'Curl_Seller_Item_Close_Sold');<br>
<br>
  if (return-&gt;status === true) {<br>
    var_export($return-&gt;data, 1);<br>
  } else {<br>
    var_export($return-&gt;errorMsg, 1);<br>
  } <br>
}<br>
</code></pre>