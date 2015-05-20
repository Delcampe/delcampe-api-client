To check if you have access to this function, call [getAuthorizedActions](getAuthorizedActions.md).

Call this function if you need to close **immediately** an item on Delcampe website.<br>
This closing method must <b>only</b> be used to close an item sold on another website.<br>
However, note that you cannot close a Delcampe auction item if there's a bid on it.<br>

A delay of 5 seconds is applied between two calls of this function.<br>
<br>
<br>
<h3>Call</h3>

<ul><li>syntax : closeItemPriority(string token, integer idItem)</li></ul>

<ul><li>parameter 1 : <i>string</i>  : secret token<br>
</li><li>parameter 2 : <i>integer</i> : id of the item you want to close</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope <br>
    xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'<br>
    xmlns:xsd='http://www.w3.org/2001/XMLSchema'<br>
    xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' <br>
    xmlns:ns1='http://xml.apache.org/xml-soap' <br>
    xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' <br>
    SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:closeItemPriority&gt;<br>
  &lt;token xsi:type="xsd:string"&gt;30df4259225ca44280be10978efa87f2&lt;/token&gt;<br>
  &lt;id_item xsi:type="xsd:int"&gt;99664631&lt;/id_item&gt;<br>
&lt;/SOAP-ENV:closeItemPriority&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Response</h3>
<ul><li>ResponseSimple, giving you more information on the function call result</li></ul>

<ul><li>SOAP envelope<br>
<pre><code>&lt;SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"&gt;<br>
&lt;SOAP-ENV:Body&gt;<br>
&lt;SOAP-ENV:closeItemPriorityResponse&gt;<br>
  &lt;closeItemPriorityReturn xsi:type="ns1:ResponseSimple"&gt;<br>
    &lt;status xsi:type="xsd:boolean"&gt;true&lt;/status&gt;<br>
    &lt;errorMsg xsi:nil="true"/&gt;<br>
    &lt;data xsi:type="xsd:string"&gt;Your item #99664631 has been closed!&lt;/data&gt;<br>
    &lt;personal_reference xsi:nil="true"/&gt;<br>
  &lt;/closeItemPriorityReturn&gt;<br>
&lt;/SOAP-ENV:closeItemPriorityResponse&gt;<br>
&lt;/SOAP-ENV:Body&gt;<br>
&lt;/SOAP-ENV:Envelope&gt;<br>
</code></pre></li></ul>

<h3>Notification</h3>
<ul><li>None</li></ul>

<h3>Code example (PHP)</h3>
<pre><code>//FUNCTION CALL<br>
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');<br>
$return = $objSoapClient-&gt;authenticateUser('MyPersonalApiKey');<br>
<br>
if ($return-&gt;status === true) {<br>
  $return = $objSoapClient-&gt;closeItemPriority($return-&gt;data, 96485615);<br>
<br>
  if ($return-&gt;status === true) {<br>
    var_export($return-&gt;data, 1);<br>
  } else {<br>
    var_export($return-&gt;errorMsg, 1);<br>
  } <br>
}<br>
</code></pre>