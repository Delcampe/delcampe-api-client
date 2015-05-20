# Documentation on the WSDL file #

  * http://api.delcampe.net/soap.php?wsdl

  * http://xmethods.net/ve2/WSDLAnalyzer.po?wsdlurl=http%3A%2F%2Fapi.delcampe.net%2Fsoap.php%3Fwsdl&submit=submit

# Sample Clients #
## Php ##
### With PECL (native in php) ###
```
<?php
$objSoapClient = new SoapClient('http://api.delcampe.net/soap.php?wsdl');
var_export($objSoapClient->getServerTime());
?>
```
### With Zend\_Framework ###
```
<?php
Zend_Loader::loadClass('Zend_Soap_Client');
$objSoapClient = new Zend_Soap_Client('http://api.delcampe.net/soap.php?wsdl');
var_export($objSoapClient->getServerTime());
}
?>
```
### With nuSoap ###
```
<?php
require_once('nusoap.php');
$objSoapClient = new soapclient('http://api.delcampe.net/soap.php?wsdl', true);

var_export($objSoapClient->getServerTime());
?>
```

  * For more info : http://thedrupalblog.com/executing-soap-call-drupal-using-nusoap

## Perl ##

```
#!/usr/bin/perl

use SOAP::Lite;
use SOAP::WSDL;
use strict;
use warnings;

my $service = SOAP::Lite;
   -> service('http://api.delcampe.net/soap.php?wsdl');

print $service->getServerTime()->result;
```

  * For more info on Perl and SOAP : http://msdn.microsoft.com/en-us/library/ms995764.aspx

## Ruby ##
```
require 'savon'

class DelcampeSoap
  attr_accessor :wsdl_url,:connection
  def initialize(url)
    @wsdl_url = url
    @connection = Savon::Client.new @wsdl_url
  end
end

dsoap = DelcampeSoap.new "http://api.delcampe.net/soap.php?wsdl"
response = dsoap.connection.get_server_time
puts response.to_hash[:get_server_time_response][:get_server_time_return]
```

  * For more info on Ruby and SOAP : http://savon.rubiii.com

## Java ##

  * For info on Java and SOAP : http://cafeconleche.org/books/xmljava/chapters/ch03s05.html

## Python ##

  * For info on Python and SOAP : http://www.ibm.com/developerworks/library/ws-pyth5/

## .net ##

If you intend to call a PHP SOAP server from a .NET Client, there are a couple of things to be aware of. First of all, we've only got it working in WSDL mode. If anyone has made it worked in non-WSDL-mode, we'd be interested to get some info, so that we can inform you even more here. In WSDL-mode it works, but both input and output-parameters are wrapped from .NET.