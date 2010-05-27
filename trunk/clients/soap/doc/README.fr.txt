<h1>Delcampe Web Service - Client - Read me</h1> 
<pre>
+-----
| This is the first version of the Delcampe API.
| 
|  The goal of it is for our customers to check 
|  wether all functions they need are possible.
|  
| All feedbacks are thus very welcome to : <a href="mailto:support.api@delcampe.com">support.api@delcampe.com</a>
+-----
</pre>

<h3>Requirements:</h3>
<ul>
<li>PHP 4 with curl 
</li></ul>

<pre>
// WARNING: There is presently no Sandbox environment on Delcampe !
// All actions are really handled on the production website.
// A sandbox is previewed for future
</pre>

<br>  
Dear Delcampe Customer, dear Developer,<br> 
<br>
This is the client package for Delcampe API version 2.0<br>
<br>
This is the PHP version of this package, presently the only one available. If you convert it to another language or technology, please send us a copy, so we can add it in the package and try to maintain it for future improvements.<br>
<br>
We tried to have this client class self-explaining enough in order for you to integrate it as easy as possible.<br>
<br>
We suggest you to begin with the demo of the simpler script: 'server_get_time.php' to become familiar with the global working way, then test 'user_get_action.php'<br>

<br>
<h3>Package contents</h3>
<dl>
<dt>File "delcampe_service.php"</dt>
<dd>Include it in your code. This is the gateway from your PHP code to our servers.</dd> 
<dt>Directory "demo"</dt>
<dd>You will find there several samples. One for each API method and one for the reception of server http feedbacks.</dd>
<dt>Directory "phpdoc"</dt>
<dd>You will find there all the client documentation.</dd>
</dl>

<br>
<h3>Action available on api:</h3>

<ul><li>With an api account
<dl>
<dt>server_get_time.php<dd>fetch the timestamp of server to synchronize your actions
<dt>user_get_action.php<dd>fetch list of available action on you api member account
<dt>developer_get_info.php<dd>info about the current api account
</dl>
<li>With an api account and a seller account
<dl>
<dt>item_add.php<dd>add an item on user account 
<dt>item_update.php<dd>update datas of an item on user account
<dt>item_close.php<dd>ending sale of an item
<dt>item_get_list.php<dd>fetch listing of items  
<dt>category_get_list.php<dd>fetch listing of categories  
<dt>feedback_set_url.php<dd>change the feedback url  
</dl>
</ul>

<br>
<h3>Feedback listener</h3>
<dl>
<dt>feedback_get.php<dd>listen and log message from server 
<dt>log<dd>directory where feedback is stored 
</dl>

<br>
<h3>Very light sample of use</h3>
<pre>

1. : Include the client class "delcampe_service.php"

2. : Instantiate the client. To do this, you need your dev_nick, your password and your encrypt key.

In our sample replace the lines
 |	$dev_nick    = '***dev nick ***'; 
 |	$dev_pwd     = '*** dev password***';
 |	$encrypt_key = '*** encrypt key ***';
 |	$oApi_delcampe = new delcampe_service($dev_nick, $dev_pwd, $encrypt_key);

3. : For most of action, you need to provide info for an account
 |	$user_nick = '***user nick    ***';
 |	$user_pwd  = '***user password***';
 |	$oApi_delcampe->set_user_member($user_nick, $user_pwd);

4. : call action with right parameters
 |	$oApi_delcampe->***action***();
 the list of currently available actions is  
 - add_items  (array $array_item)
 - end_item (integer $id_item, string $reason)
 - get_category_listing ([string $language = self::LANGUAGE_E], [integer $cat_parent = null])
 - get_developer_info ()
 - get_server_time ()
 - get_user_action ()
 - item_list ([mixed $id_item = NULL], [string $personal_ref = NULL])
 - modify_image (integer $intIdItem, array $arrNewImage)
 - modify_item (array $array_item)

Note about Step 3 : This way, you can manage many accounts on the website with only one api account
</pre>

<br>
<H3>Feedbacks</H3>

<pre>
There are 3 ways to get server feedback : 

1. Direct feedback. When you make a call, you receive an xml reply. 
   It contains information over the call.
 
2. Feedback sent to an url on your server.
   As some actions are queued, this second feedback may come at a later time.
   The immediate response of action needing a "long" process is just an acknowledgment.
   The feedback to signify the good or bad treatment of the requests is sent later  
   on a url defined in your api account.
   demo/delcampe_feedback.php is a sample of code to manage them.
 
3. There is a third type of feedback by mail on email address defined in your api account 

The feedback by email is only sent if the direct feedback contains error messages.
</pre>

<br>
<h2>General information for some actions :</h2>

<h3>modify_item</h3>
<pre>
To edit an item, there are 2 constraints :
 
1. You must give all information related to item's properties (price, type, title, description, ...), the missing values would be replaced by a default value and not by previous value. This will be fixed in a next release.

2. You should give the Delcampe-Id of the item

You can get this id from your personal reference with the method item_list(), or store the id you got sooner from our server feedback.
</pre>
