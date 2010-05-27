<?php
/**
 * Service Delcampe Api
 * @copyright Delcampe International sprl
 * 
 * @author Delcampe Dev Team <info@delcampe.com>
 * @version 2.0
 * @package delcampe_service
 * 
 */

// config
$arrCfgAllowedCurrenciesByIdCountry = array (
   '0' => array('EUR','USD','GBP','CHF','CAD'), // .net
   '1' => array('EUR'),                         // .fr
   '2' => array('EUR'),                         // .de
   '3' => array('EUR'),                         // .nl
   '4' => array('EUR'),                         // .it
   '5' => array('EUR'),                         // .es
   '6' => array('GBP'),                         // .co.uk
   '7' => array('USD'),                         // .com
   '8' => array('CHF'),                         // .ch
   '9' => array('EUR'),                         // .be
  '10' => array('EUR'),                         // .at
  '11' => array('CAD'),                         // .ca
);
 
/**
 * This class handle features of the Delcampe api.
 * 
 * @version 2.0
 */
class delcampe_service {
  
  /**#@+
   * Constante for code language
   */
  const LANGUAGE_E = 'E';
  const LANGUAGE_F = 'F';
  const LANGUAGE_D = 'D';
  const LANGUAGE_G = 'G';
  const LANGUAGE_I = 'I';
  const LANGUAGE_S = 'S';
  /**#@-*/
  
  /**
   * Nickname delcampe developer
   * @var string
   */
  private $developer_nickname;
  /**
   * password delcampe developer
   * @var
   */
  private $developer_password;
  /**
   * encrypt_key
   * @var
   */
  private $encrypt_key;
  
  private $ttl = 36000; //3600*10;
   
  private $url_prod = 'http://api.delcampe.net/api.php';   
  
  /**
   * Token for connection to api delcampe web service
   * @var string
   */
  private $token;

  /**
   * User delcampe nickname
   * @var string
   */
  private $user_nickname = NULL;
  /**
   * User delcampe password
   * @var string
   */
  private $user_password = NULL;
  
  /**
   * Object type for action. 
   * Possible values : server, auction, auctionList, api, category 
   * @var string
   */
  public $object_type;
  
  /**
   * Action send to api. 
   * Possible values : getServerTime, items_add, update, update_image, close, list, set_feedback_url, get_user_action, get_developer_info, get_category_listing
   * @var string
   */
  public $action;
  
  /**
   * XML sent to api
   * @var string
   */
  public $xml_api_request;
  
  /**
   * Array content all message send by api
   * @var array
   */
  public $xml_api_return;
  
  /**
   * Array content all message send by api
   * @var array
   */
  public $message_array;
  
  /**
   * Response of the function get server time
   *
   * @var string
   */
  public $response_grt;
  
  /**
   * Response of the function end item
   *
   * @var string
   */
  public $response_endItem;
  /**
   * Response of the function item list
   *
   * @var string
   */
  public $response_itemList;
  /**
   * Response of the function modify image
   *
   * @var string
   */
  public $response_modifyImage;
  
  /**
   * Response of the function set feedback url
   *
   * @var string
   */  
  public $response_feedbackUrl;
  
  /**
   * Response of the function modify item
   *
   * @var string
   */
  public $response_modifyItem;
  /**
   * Response of the function get user action
   *
   * @var string
   */
  public $response_gua;
  /**
   * Response of the function set_feedback_url
   *
   * @var string
   */
  public $response_sfu;
  /**
   * Response of the function  add item
   *
   * @var string
   */
  private $response_addItems;
  /**
   * Response of the api
   * @var string
   */
  public $response;
  
  
  /**
   * Construct delcampe_service object
   * @param string developer nickname
   * @param string $devpassword default NULL
   * @param string $apicryptkey default NULL
   * @param string $ttl default NULL
   * @return boolean
   */
  public function __construct($devname, $devpassword=null, $apicryptkey=null, $ttl=null) {
    $this->developer_nickname = $devname;
    $this->developer_password = $devpassword;
    $this->encrypt_key        = $apicryptkey;
    if( !is_null($devpassword )&& !is_null($apicryptkey)) {
      if ( !is_null($ttl) &&  $ttl > 0 ){
        $this->ttl = $ttl;
      }
      $this->createToken();
    }
    
    return true;
  }
  
  private function keyED($txt) { 
    $encrypt_key = md5($this->encrypt_key); 
    $ctr=0; 
    $tmp = ""; 
    for ($i=0;$i<strlen($txt);$i++) { 
       if ($ctr==strlen($encrypt_key)) $ctr=0; 
       $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1); 
       $ctr++; 
    } 
    return $tmp; 
  } 
  
  private function encrypt($txt){ 
    srand((double)microtime()*1000000); 
    $encrypt_key = md5(rand(0,32000)); 
    $ctr=0; 
    $tmp = ""; 
    for ($i=0;$i<strlen($txt);$i++){ 
       if ($ctr==strlen($encrypt_key)) $ctr=0; 
       $tmp.= substr($encrypt_key,$ctr,1) . 
           (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1)); 
       $ctr++; 
    } 
    return base64_encode($this->keyED($tmp)); 
  }
   
  /**
  * Create a token
  * and set $this->token 
  */
  private function createToken() {
      
    $base   = $this->encrypt($this->developer_nickname . ':' . $this->developer_password);
    $mobile = $this->encrypt($base.':'.date('YmdHis',time()+ $this->ttl ));
    
    $this->token = $mobile;
  }  
  
  /**
   * Set user delcampe member nickname and password
   * @param string user nickname 
   * @param string user password
   * @return boolean
   */
  public function set_user_member($user_nickname, $user_password) {
    if (! is_string ( $user_nickname )) {
      trigger_error ( 'First param is not a string', E_USER_WARNING );
      return false;
    }
    if (! is_string ( $user_password )) {
      trigger_error ( 'Second param is not a string', E_USER_WARNING );
      return false;
    }
    $this->user_nickname = $user_nickname;
    $this->user_password = $user_password;
    return true;
  }
  
  /**
   * Send xml data to api
   * @param array $_POST['xml'] => xml data
   * @return string xml return
   */
  private function api_request($post_xml) {
    $rCurl = curl_init ();
    curl_setopt ( $rCurl, CURLOPT_URL, $this->url_prod );
    curl_setopt ( $rCurl, CURLOPT_HEADER, false );
    curl_setopt ( $rCurl, CURLOPT_POST, true );
    curl_setopt ( $rCurl, CURLOPT_POSTFIELDS, $post_xml );
    curl_setopt ( $rCurl, CURLOPT_RETURNTRANSFER, true );
    $bResult = curl_exec ( $rCurl );   
   
    curl_close ( $rCurl );
    $this->xml_api_request = $post_xml['xml'];
    return $bResult;
  }
  
  /**
   * Create xml for sending to api
   * @param string params created by a function
   * @return string xml
   */
  private function create_xml($params = '') {
    $return_xml = '      
      <?xml version="1.0" encoding="ISO-8859-1"?>
        <delcampe_api version="2.0">
          <environement>Prod</environement>
          <dev_login>
            <dev_nickname>' . $this->developer_nickname . '</dev_nickname>
            <token>' . $this->token . '</token>
          </dev_login>';
    if (($this->user_nickname != NULL) and ($this->user_password != NULL)) {
      $return_xml .= '
          <user_login>
            <user_nickname>' . $this->user_nickname . '</user_nickname>
            <user_password>' . $this->user_password . '</user_password>
          </user_login>';
    }
    $return_xml .= '
          <objectType>' . $this->object_type . '</objectType>
          <action>' . $this->action . '</action>
    ';
    $return_xml .= $params;
    $return_xml .= '
        </delcampe_api>';
    return $return_xml;
  }
  
  /**
   * Set api return message in public var message array
   * @param array $array_message 
   * @return boolean
   */
  private function set_message_api($array_message) {
    if (! is_array ( $array_message )) {
      trigger_error ( 'Param ( array_message ) is not an array', E_USER_WARNING );
      return false;
    }
    foreach ( $array_message as $key => $message ) {
      if (is_integer ( $key )) {
        $this->message_array [] = $message;
      }
    }
    return true;
  }
  
  /**
   * Get delcampe server timestamp
   * @return string api xml response
   * 
   * @example demo/get_category_listing.php
   */
  public function get_server_time() {
    $this->object_type = 'server';
    $this->action = 'getServerTime';
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml () ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    
    $arrData = $this->xml2array ( $this->xml_api_return );
    
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    $this->response_grt = $arrData ['delcampe_api'] ['responses'] ['response'];
    
    return $this->response_grt;
  }
  
  /**
   * Add item by api delcampe
   *   Create xml with data in array 
   *   and send to delcampe
   * @param array content object item delcampe
   * @return string response api or false if param is not an array
   * 
   * @example demo/add_item.php
   */
  public function add_items($array_item) {
    if (! is_array ( $array_item )) {
      trigger_error ( 'Param ( $array_item ) is not an array', E_USER_WARNING );
      return false;
    }
    $this->object_type = 'auction';
    $this->action      = 'items_add';    
    
    $xml_param = '
        <params>
          <items count="' . count ( $array_item ) . '">';
          
    foreach ( $array_item as $data ) {
      if (! is_array ( $data->images )) {
        trigger_error ( 'Param images is not an array', E_USER_WARNING );
        return false;
      }
      if (! is_array ( $data->options )) {
        trigger_error ( 'Param options is not an array', E_USER_WARNING );
        return false;
      }
      
      $xml_param .= '
            <item>
              <id_country>'         . ( int    ) $data->id_country         . '</id_country>
              <id_site>'            . ( int    ) $data->id_site            . '</id_site>
              <id_category>'        . ( int    ) $data->id_category        . '</id_category>
              <title>'              . ( string ) $data->title              . '</title>
              <personal_reference>' . ( string ) $data->personal_reference . '</personal_reference>
              <description>'        . ( string ) $data->description        . '</description>
              <images>';
                foreach ( $data->images as $image ) {
                  $xml_param .= '                <image>' . ( string ) $image . '</image>'."\n";
                }
      $xml_param .= '
              </images>
              <currency>'           . ( string ) $data->currency           . '</currency>
              <sale_type>'          . ( string ) $data->sale_type          . '</sale_type>
              <price_starting>'     . ( float  ) $data->price_starting     . '</price_starting>
              <price_reserve>'      . ( float  ) $data->price_reserve      . '</price_reserve>
              <price_increment>'    . ( float  ) $data->price_increment    . '</price_increment>
              <price_buyitnow>'     . ( string ) $data->price_buyitnow     . '</price_buyitnow>
              <qty>'                . ( int    ) $data->qty                . '</qty>
              <timezone>'           . ( float  ) $data->timezone           . '</timezone>
              <date_end>'           . ( string ) $data->date_end           . '</date_end>
              <prefered_end_day>'   . ( string ) $data->prefered_end_day   . '</prefered_end_day>
              <prefered_end_hour>'  . ( string ) $data->prefered_end_hour  . '</prefered_end_hour>
              <renew_origin>'       . ( int    ) $data->renew_origin       . '</renew_origin>
              <duration>'           . ( int    ) $data->duration           . '</duration>
              <options>
                <subtitle>'              . (string) $data->options ['subtitle'             ] . '</subtitle>
                <last_minute_extension>' . (string) $data->options ['last_minute_extension'] . '</last_minute_extension>
                <anonymous_sale>'        . (string) $data->options ['anonymous_sale'       ] . '</anonymous_sale>
                <bold_title>'            . (string) $data->options ['bold_title'           ] . '</bold_title>
                <colour_border>'         . (string) $data->options ['colour_border'        ] . '</colour_border>
                <colour_background>'     . (string) $data->options ['colour_background'    ] . '</colour_background>
                <selection_list>'        . (string) $data->options ['selection_list'       ] . '</selection_list>
                <selection_category>'    . (string) $data->options ['selection_category'   ] . '</selection_category>
                <selection_homepage>'    . (string) $data->options ['selection_homepage'   ] . '</selection_homepage>
                <keepoptionsonrenewal>'  . (string) $data->options ['keepoptionsonrenewal' ] . '</keepoptionsonrenewal>
              </options>
            </item>'."\n";
    }
    $xml_param .= '
          </items>
        </params>';
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    $arrData = $this->xml2array ( $this->xml_api_return );

    //$this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    //if (isset($arrData ['delcampe_api'] ['responses'] ['response'])) $this->response_addItems = $arrData ['delcampe_api'] ['responses'] ['response'];
    //if (isset($arrData ['delcampe_api'] ['error_text'] ['error'])) $this->response_addItems = $arrData ['delcampe_api'] ['error_text'] ['error'];
    
    
    return $this;
  }
 
  /**
   * getter for response_modifyImage
   *
   * @return array of string
   */
  public function get_response_modify_images() {
    return $this->response_modifyImage;
  }
  
  /**
   * getter for response_modifyItem
   *
   * @return array of string
   */
  public function get_response_modify_items() {
    return $this->response_modifyItem;
  }
  
  /**
   * getter for response_modifyItem
   *
   * @return array of string
   */
  public function get_response_set_feedback_url() {
    return $this->response_feedbackUrl;
  }
  
  /**
   * getter for response_addItems
   *
   * @return array of string
   */
  public function get_response_add_items() {
    return $this->response_addItems;
  }
  
  /**
   * alias for get_response_add_items
   * @deprecated 0.9 
   * @return array of string
   */
  public function get_response_add_item() { // Deprecated function
    return $this->response_addItems;
  }
  
  /**
   * Modify an item in delcampe
   * Create xml with data in array 
   *   and send to delcampe
   * @param array content object item delcampe
   * @return string response api or false if param is not an array
   * 
   * @example demo/modify_item.php
   */
  public function modify_item($array_item) {

    if (! is_array ( $array_item )) {
      trigger_error ( 'Param ( $array_item ) is not an array', E_USER_WARNING );
      return false;
    }
    $this->object_type = 'auction';
    $this->action      = 'update';
    
    $xml_param = '
        <params>
          <items>';

    foreach ( $array_item as $data ) {
            
      $xml_param .= "\n" . '<filter>';            
      
      //Filter
      foreach ($data['filter'] as $key => $value) {
        $xml_param .= "\n" . '<'.$key.'>' . $value . '</'.$key.'>';     
      }      
      
      $xml_param .= "\n" . '</filter>';      
      $xml_param .= "\n" . '<data>';
            
      //Data
      foreach ($data['data'] as $key => $value) {
        if ($key == 'options') {
          $xml_param .= "\n" . '<options>';
          foreach ($value as $opKey => $opValue) {            
            $xml_param .= "\n" . '<'.$opKey.'>' . $opValue . '</'.$opKey.'>';              
          }
          $xml_param .= "\n" . '</options>';
        } else {          
          switch ($key)                    
          {                        
              case 'id_country' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';
                break;
              case 'id_site' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';              
                break;
              case 'id_category' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';          
                break;
              case 'title' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';              
                break;
              case 'personal_reference' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';                            
                break;
              case 'description' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'currency' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'sale_type' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'price_starting' :
                $xml_param .= "\n" . '<'.$key.'>' . (float) $value . '</'.$key.'>';          
                break;
              case 'price_reserve' :
                $xml_param .= "\n" . '<'.$key.'>' . (float) $value . '</'.$key.'>';          
                break;
              case 'price_increment' :
                $xml_param .= "\n" . '<'.$key.'>' . (float) $value . '</'.$key.'>';          
                break;
              case 'price_buyitnow' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'qty' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';          
                break;
              case 'timezone' :
                $xml_param .= "\n" . '<'.$key.'>' . (float) $value . '</'.$key.'>';          
                break;
              case 'date_end' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'prefered_end_day' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'prefered_end_hour' :
                $xml_param .= "\n" . '<'.$key.'>' . (string) $value . '</'.$key.'>';          
                break;
              case 'renew_origin' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';          
                break;
              case 'duration' :
                $xml_param .= "\n" . '<'.$key.'>' . (int) $value . '</'.$key.'>';          
                break;              
              default :
                $xml_param .= "\n" . '<'.$key.'>' . $value . '</'.$key.'>';          
                break;
          }
        }
      }
      
      $xml_param .= "\n" . '</data>';               
    }
    
    $xml_param .= '
          </items>
        </params>';   
            
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) ); 
    
    $this->xml_api_return = $this->api_request ( $xml_to_post );    
    
    $arrData = $this->xml2array ( $this->xml_api_return );

    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    if (isset($arrData ['delcampe_api'] ['responses'] ['response'])) $this->response_modifyItem = $arrData ['delcampe_api'] ['responses'] ['response'];
    if (isset($arrData ['delcampe_api'] ['error_text'] ['error'])) $this->response_modifyItem = $arrData ['delcampe_api'] ['error_text'] ['error'];
                
    return $this->response_modifyItem;
  }
  
  /**
   * Modify media image of one item
   * @param integer $intIdItem
   * @param array $arrNewImage
   * @return string response api or false if param is not an array
   * 
   * @example demo/modify_images.php
   */
  public function modify_image($intIdItem, $arrNewImage) {
    if (! is_array ( $arrNewImage )) {
      trigger_error ( 'Param ( $array_item ) is not an array', E_USER_WARNING );
      return false;
    }
    $this->object_type = 'auction';
    $this->action = 'update_image';
    
    $xml_param = '
        <params>
          <item>' . $intIdItem . '</item>';
    
    $xml_param .= "\n" . '<images>';
            
    foreach ($arrNewImage as $value) {      
      $xml_param .= "\n" . '<image>' . htmlentities($value) . '</image>';
    }     
      
    $xml_param .= '
          </images>
        </params>';
                      
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) );              

    //Call the cURL and get the response
    $this->xml_api_return = $this->api_request ( $xml_to_post );

    $arrData = $this->xml2array ( $this->xml_api_return );

    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    if (isset($arrData ['delcampe_api'] ['responses'] ['response'])) $this->response_modifyImage = $arrData ['delcampe_api'] ['responses'] ['response'];
    if (isset($arrData ['delcampe_api'] ['error_text'] ['error'])) $this->response_modifyImage = $arrData ['delcampe_api'] ['error_text'] ['error'];
       
    return $this->response_modifyImage;
  }
  
  /**
   * Close an item
   *   Create a xml with id of item to close
   *   and send request to delcampe
   * @param integer id of item to close
   * @param string reason of the close ItWasATest|LostOrBroken|NotAvailable|Incorrect|OtherListingError|CustomCode
   * @return response of API or FALSE if id item is not an integer 
   * 
   * @example demo/end_item.php
   */
  public function end_item($id_item, $reason) {
    $id_item = intval($id_item);
    
    $this->object_type = 'auction';
    $this->action      = 'close';
    
    $xml_param = '
        <params>
          <items>';
    
    $xml_param .= '
            <id>' . $id_item . '</id>';
    
    $xml_param .= '
            <reason>' . $reason . '</reason>';
    
    $xml_param .= '
          </items>
        </params>';
    
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    $arrData = $this->xml2array ( $this->xml_api_return );
    
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    $this->response_endItem = $arrData ['delcampe_api'] ['responses'] ['response'];
    
    return $this->response_endItem;
  }
  
  /**
   * Get item list
   *   - Request a list of all items of the member init.
   *     delcampe_service::item_list();
   *   - Request to return an item by id.
   *     delcampe_service::item_list(654135);
   *   - Request to return a list of items by their id.
   *     delcampe_service::item_list( array(654135,354686,) );
   *   - Request to return a list of items by personal ref.
   *     delcampe_service::item_list( null, 'My personal ref' );
   * @param mixed integer id item or array id item
   * @param string personal reference.
   * @return array content object item
   * 
   * 
   * @example demo/item_list.php
   * @example demo/listing_item_info.php
   * 
   */
  public function item_list($id_item = NULL, $personal_ref = NULL) {
    $this->object_type = 'auctionList';
    $this->action      = 'list';
    
    $xml_param = '';
    
    if (($id_item != NULL) or ($personal_ref != NULL)) {
      
      $xml_param = '
          <params>';
      
      if ($id_item != NULL) {
        if (is_array ( $id_item )) {
          $xml_param .= '
                  <id>' . serialize ( $id_item ) . '</id>';
        } else {
          $xml_param .= '
                  <id>' . $id_item . '</id>';
        }
      }
      
      if ($personal_ref != NULL) {
        $xml_param .= '
                  <personal_ref>' . $personal_ref . '</personal_ref>';
      }
      
      $xml_param .= '
          </params>';
    
    }
    
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    
    $arrData = $this->xml2array ( $this->xml_api_return );
    
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    
    $this->response_itemList = array ();
    $item_count_return = $arrData ['delcampe_api'] ['responses'] ['response'] ['item_count'];
    unset ( $arrData ['delcampe_api'] ['responses'] ['response'] ['item_count'] );
    
    if ( is_array( $arrData ['delcampe_api'] ['responses'] ['response'] ) ) {
      foreach ( $arrData ['delcampe_api'] ['responses'] ['response'] as $item_list ) {
        if ($item_count_return > 1) {
          foreach ( $item_list ['item'] as $item ) {
            $oItem = new item_delcampe ( );
            $oItem->set_data_from_array_delcampe ( $item );
            $this->response_itemList [$oItem->id] = $oItem;
          }
        } else {
          $oItem = new item_delcampe ( );
          $oItem->set_data_from_array_delcampe ( $item_list ['item'] );
          $this->response_itemList [$oItem->id] = $oItem;
        }
      }
    }          

    return $this->response_itemList;
  }
  
  /**
   * Return serialized array content action for user api member
   * @return serialized array
   * 
   * @example demo/set_feedback_url.php
   * 
   */
  public function set_feedback_url($feedback_url) {
    $this->object_type = 'api';
    $this->action      = 'set_feedback_url';
    $xml_param = '<params>
                    <feedback_url>' . $feedback_url . '</feedback_url>
                  </params>';
    $xml_to_post = array ('xml' => $this->create_xml ( $xml_param ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    $arrData = $this->xml2array ( $this->xml_api_return );
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );    
    $this->response_sfu = $arrData ['delcampe_api'] ['responses'] ['response'];
    return $this->response_sfu;
  }
  
  /**
   * Return serialized array content action for user api member
   * @return serialized array
   * 
   * @example demo/get_user_action.php
   * 
   */
  public function get_user_action() {
    $this->object_type = 'api';
    $this->action = 'get_user_action';
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml () ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    
    $arrData = $this->xml2array ( $this->xml_api_return );
    
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    $this->response_gua = $arrData ['delcampe_api'] ['responses'] ['response'];
    
    return $this->response_gua;
  }
  
  /**
   * Return serialized array content information for developer
   * @return serialized array
   * 
   * @example demo/get_developer_info.php
   * 
   */
  public function get_developer_info() {
    $this->object_type = 'api';
    $this->action = 'get_developer_info';
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml () ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    
    $this->response = new SimpleXMLElement ( $this->xml_api_return );
    
    $arrData = $this->xml2array ( $this->xml_api_return );
    
    $this->set_message_api ( $arrData ['delcampe_api'] ['messages'] ['message'] );
    $this->response = $this->response->responses->response->developer;
    return $this->response;
  }
  
  /**
   * Get listing category by language or/and category-parent
   *       /!\ label is in UTF8  
   * @param string language default LANGUAGE_E,  valid codes are LANGUAGE_E (english), LANGUAGE_F (French), LANGUAGE_D (Dutch), LANGUAGE_I (Italian), LANGUAGE_S (Spanish), LANGUAGE_G (German)
   * @param integer id catégory parent 
   * @return object simple xml
   * 
   * @example demo/get_category_listing.php
   * 
   */
  public function get_category_listing($language = self::LANGUAGE_E, $cat_parent = null) {
    $this->object_type = 'category';
    $this->action = 'get_category_listing';
    
    $xml_param = '';
    
    if (($language != NULL) or ($cat_parent != NULL)) {
      
      $xml_param = '
          <params>';
      
      if ($language != NULL) {
        
        $xml_param .= '
                <language>' . $language . '</language>';
      
      }
      
      if ($cat_parent != NULL) {
        $xml_param .= '
                  <category_parent>' . $cat_parent . '</category_parent>';
      }
      
      $xml_param .= '
          </params>';
    
    }
    
    $xml_to_post = array ('xml' => stripslashes ( $this->create_xml ( $xml_param ) ) );
    $this->xml_api_return = $this->api_request ( $xml_to_post );
    
    $xml = new SimpleXMLElement ( $this->xml_api_return );
    
    return $xml;
  }
  
  /**
   * Create an array from a xml
   * @param string 
   * @param integer 
   * @param string
   * @return array 
   */
  private function xml2array($contents, $get_attributes = 1, $priority = 'tag') {
    if (! $contents)
      return array ();
    
    if (! function_exists ( 'xml_parser_create' )) {      
      return array ();
    }
    
    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create ( '' );
    //xml_parser_set_option ( $parser, XML_OPTION_TARGET_ENCODING, "UTF-8" );
    xml_parser_set_option ( $parser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1" );    
    xml_parser_set_option ( $parser, XML_OPTION_CASE_FOLDING, 0 );
    xml_parser_set_option ( $parser, XML_OPTION_SKIP_WHITE, 1 );
    $xml_values = null;
    xml_parse_into_struct ( $parser, trim ( $contents ), $xml_values );
    xml_parser_free ( $parser );
    
    if (! $xml_values)
      return;
    

    //Initializations
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    
    $current = &$xml_array; //Reference
    

    //Go through the tags.
    $repeated_tag_index = array (); //Multiple tags with same name will be turned into an array
    foreach ( $xml_values as $data ) {      
 
      unset ( $attributes, $value );
      

      //This command will extract these variables into the foreach scope
      // tag(string), type(string), level(int), attributes(array).
      $tag = null;
      $type = null;
      $level = null;
      $attributes = null;
      extract ( $data );
      

      $result = array ();
      $attributes_data = array ();
      
      if (isset ( $value )) {
        if ($priority == 'tag')
          $result = $value;
        else
          $result ['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
      }
      
      //Set the attributes too.
      if (isset ( $attributes ) and $get_attributes) {
        foreach ( $attributes as $attr => $val ) {
          if ($priority == 'tag')
            $attributes_data [$attr] = $val;
          else
            $result ['attr'] [$attr] = $val; //Set all the attributes in a array called 'attr'
        }
      }
      
      //See tag status and do the needed.
      if ($type == 'open') { //The starting of the tag '<tag>'
        $parent [$level - 1] = &$current;
        if (! is_array ( $current ) or (! in_array ( $tag, array_keys ( $current ) ))) { //Insert New tag
          $current [$tag] = $result;
          if ($attributes_data)
            $current [$tag . '_attr'] = $attributes_data;
          $repeated_tag_index [$tag . '_' . $level] = 1;
          
          $current = &$current [$tag];
        
        } else { //There was another element with the same tag name
          

          if (isset ( $current [$tag] [0] )) { //If there is a 0th element it is already an array
            $current [$tag] [$repeated_tag_index [$tag . '_' . $level]] = $result;
            $repeated_tag_index [$tag . '_' . $level] ++;
          } else { //This section will make the value an array if multiple tags with the same name appear together
            $current [$tag] = array ($current [$tag], $result ); //This will combine the existing item and the new item together to make an array
            $repeated_tag_index [$tag . '_' . $level] = 2;
            
            if (isset ( $current [$tag . '_attr'] )) { //The attribute of the last(0th) tag must be moved as well
              $current [$tag] ['0_attr'] = $current [$tag . '_attr'];
              unset ( $current [$tag . '_attr'] );
            }
          
          }
          $last_item_index = $repeated_tag_index [$tag . '_' . $level] - 1;
          $current = &$current [$tag] [$last_item_index];
        }
      
      } elseif ($type == "complete") { //Tags that ends in 1 line '<tag >'
        //See if the key is already taken.
        if (! isset ( $current [$tag] )) { //New Key
          $current [$tag] = $result;
          $repeated_tag_index [$tag . '_' . $level] = 1;
          if ($priority == 'tag' and $attributes_data)
            $current [$tag . '_attr'] = $attributes_data;
        
        } else { //If taken, put all things inside a list(array)
          if (isset ( $current [$tag] [0] ) and is_array ( $current [$tag] )) { //If it is already an array...
            

            // ...push the new element into that array.
            $current [$tag] [$repeated_tag_index [$tag . '_' . $level]] = $result;
            
            if ($priority == 'tag' and $get_attributes and $attributes_data) {
              $current [$tag] [$repeated_tag_index [$tag . '_' . $level] . '_attr'] = $attributes_data;
            }
            $repeated_tag_index [$tag . '_' . $level] ++;
          
          } else { //If it is not an array...
            $current [$tag] = array ($current [$tag], $result ); //...Make it an array using using the existing value and the new value
            $repeated_tag_index [$tag . '_' . $level] = 1;
            if ($priority == 'tag' and $get_attributes) {
              if (isset ( $current [$tag . '_attr'] )) { //The attribute of the last(0th) tag must be moved as well
                

                $current [$tag] ['0_attr'] = $current [$tag . '_attr'];
                unset ( $current [$tag . '_attr'] );
              }
              
              if ($attributes_data) {
                $current [$tag] [$repeated_tag_index [$tag . '_' . $level] . '_attr'] = $attributes_data;
              }
            }
            $repeated_tag_index [$tag . '_' . $level] ++; //0 and 1 index is already taken
          }
        }
      
      } elseif ($type == 'close') { //End of tag '</tag>'
        $current = &$parent [$level - 1];
      }
    }
    return ($xml_array);
  }
}

/* ========================================================================== */

/**
 * Class Item_delcampe
 *  
 * @copyright Delcampe International sprl 
 * 
 * @author Delcampe Dev Team <info@delcampe.com>
 * @version 2.0
 * @package delcampe_service
 *   
 */
class item_delcampe {
  
  /**
   * id of item delcampe
   * @var integer max 10 pos
   */
  public $id;

  /**
   * id of country
   * 0 net | 1 fr | 2 de | 3 nl | 4 it | 5 es | 6 co.uk | 7 com | 8 ch | 9 be | 10 at | 11 ca
   * @var integer max 1 pos
   */
  public $id_country;

  /**
   * id of site delcampe
   * 1 stamps | 2 postcards | 3 coins | 4 books | 5 collections | 6 phonecards | 7 pins | 8 oldpaper 
   * @var integer max 3 pos
   */
  public $id_site;

  /**
   * Id of category 
   *   Find the full list here: http://www.delcampe.net/categories_print.php?language=E&site=0
   * @var integer max 6 pos
   */
  public $id_category;

  /**
   * Id member seller
   * @var integer max 8 pos
   */
  public $seller;

  /**
   * Id member buyer
   * @var integer max 8 pos
   */
  public $buyer;

  /**
   * Title of item
   * @var string max 120 chars
   */
  public $title;

  /**
   * 
   * @var string max 20 chars
   */
  public $personal_reference;

  /**
   * Description of item
   * @var text max 10,000 chars
   */
  public $description;

  /**
   * Number of images
   * @var int (0,255)
   */
  public $img_nbr;

  /**
   * array containing image link(s)
   * @var array 10
   */
  public $images;

  /**
   * Currency 
   *   'EUR' | 'USD' | 'GBP' | 'CHF' | 'CAD'
   * @var string max 3 char
   */
  public $currency;

  /**
   * Sale type
   *   'auction' | 'fixedprice'
   * @var string
   */
  public $sale_type;

  /**
   * Current auction price
   * @var float(10,2)
   */
  public $price_present;
  
  /**
   * Price of starting auction
   * @var float(10,2)
   */
  public $price_starting;

  /**
   * Auction increment
   * @var float(10,2)
   */
  public $price_increment;

  /**
   * Reserve price
   * @var float(10,2)
   */
  public $price_reserve;

  /**
   * Price for buy it now
   * @var float(10,2)
   */
  public $price_buyitnow;

  /**
   * Qty for buy it now
   * @var int
   */
  public $qty;

  /**
   * @var bids number for this item
   */
  public $bids_nbr;
  
  /**
   * Timezone (1 for GMT+1 etc)
   * @var float
   */
  public $timezone;

  /**
   * Date of first start auction
   * @var dateTime '0000-00-00 00:00:00
   */
  public $date_begin_origin;

  /**
   * Date of start auction
   * @var dateTime '0000-00-00 00:00:00
   */
  public $date_begin;

  /**
   * Date of end auction
   * @var dateTime '0000-00-00 00:00:00
   */
  public $date_end;

  /**
   * Prefered end day of week
   *   NULL = not important
   *   ('mon','tue','wed','thu','fri','sat','sun')
   * @var string
   */
  public $prefered_end_day;

  /**
   * Prefered end hour of week
   * @var time '00:00:00'
   */
  public $prefered_end_hour;

  /**
   * Days of duraction auction
   *   7 | 10 | 14 | 21 | 28
   * @var integer 
   */
  public $duration;

  /**
   * Number of renew auction
   * @var integer max 5 pos
   */
  public $renew_origin;

  /**
   * Number of current renew
   * @var integer max 5 pos
   */
  public $renew;

  /**
   * Wether or not the auction has been renewed (Y/N)
   * @var char 1
   */
  public $renewed;

  /**
   * Wether or not the auction is closed (Y/N)
   * @var char 1
   */
  public $closed;

  /**
   * Wether or not the auction has been sold (Y/N)
   * @var char 1
   */
  public $sold;

  /**
   * Array containing option(s)
   * @var array
   */
  public $options;

  /**
   * Date payment asked
   * @var dateTime '0000-00-00 00:00:00
   */
  public $payment_asked_date;

  /**
   * Date payment sent
   * @var dateTime '0000-00-00 00:00:00
   */
  public $payment_sent_date;

  /**
   * Date item sent
   * @var dateTime '0000-00-00 00:00:00
   */
  public $item_sent_date;

  /**
   * Date item received
   * @var dateTime '0000-00-00 00:00:00
   */
  public $item_received_date;

  /**
   * Feedback rating received by seller
   * @var integer max 3 pos
   */
  public $seller_rating;

  /**
   * Feedback rating received by buyer
   * @var integer max 3 pos
   */
  public $buyer_rating;

  /**
   * Stack of error messages
   * @var array of strings
   */
  public $error_delcampe_item;

  /**
   * Wether or not the auction is active (has not been cancelled) (Y/N)
   * @var char 1
   */
  public $active;
  
  /**
   * Construct item
   */
  public function __construct() {
    return true;
  }
  
  /**
   * Set data item delcampe
   * Array data for add item
   * 
   * @param array content data of item 
   * @return boolean
   */
  public function set_data_from_array($array_data) {
    if (! is_array ( $array_data )) {
      trigger_error ( 'Param for this function is not an array', E_USER_WARNING );
      $return = false;
    } else {
      $this->id_country         = ( int    ) $array_data ['id_country'        ];
      $this->id_site            = ( int    ) $array_data ['id_site'           ];
      $this->id_category        = ( int    ) $array_data ['id_category'       ];
      
      $this->title              = ( string ) $array_data ['title'             ];
      $this->personal_reference = ( string ) $array_data ['personal_reference'];
      $this->description        = ( string ) $array_data ['description'       ];
      $this->images             = ( array  ) $array_data ['images'            ];
      
      $this->currency           = ( string ) $array_data ['currency'          ];
      $this->sale_type          = ( string ) $array_data ['sale_type'         ];

      //Starting price is only set when type = auction
      isset($array_data ['price_starting']) ? $this->price_starting = (float) $array_data ['price_starting'] : 0;
      
      //Reserve price is only set when type = auction
      isset($array_data ['price_reserve'])  ? $this->price_reserve  = (float) $array_data ['price_reserve']  : 0;
      
      //Bid increment is only set when type = auction
      isset($array_data ['price_increment']) ? $this->price_increment = (float) $array_data ['price_increment'] : 0;
       
      //BuyItNow price is only set when type = fixed price
      isset($array_data ['price_buyitnow']) ? $this->price_buyitnow = (float) $array_data ['price_buyitnow'] : 0;
           
      //Quantity is only set when type = fixed price
      isset($array_data ['qty']) ? $this->qty = (int) $array_data ['qty'] : 0;
      
      $this->timezone           = ( float  ) $array_data ['timezone'          ];
      $this->date_end           = ( string ) $array_data ['date_end'          ];
      $this->prefered_end_day   = ( string ) $array_data ['prefered_end_day'  ];
      $this->prefered_end_hour  = ( string ) $array_data ['prefered_end_hour' ];
      $this->renew_origin       = ( string ) $array_data ['renew_origin'      ];
      $this->duration           = ( int    ) $array_data ['duration'          ];
      
      $this->options            = ( array  ) $array_data ['options'           ];
      
      $return = true;
    }
    return $return;
  }
  
  /**
   * Set data object with an array coming from delcampe
   * 
   * @return boolean
   * @param object $array_data
   */
  public function set_data_from_array_delcampe($array_data) {
    if (! is_array ( $array_data )) {
      trigger_error ( 'Param for this function is not an array', E_USER_WARNING );
      return false;
    }

    $this->id                 = ( int    ) $array_data ['id'];
    $this->id_country         = ( int    ) $array_data ['id_country'];
    $this->id_site            = ( int    ) $array_data ['id_site'];
    $this->id_category        = ( int    ) $array_data ['id_category'];
    $this->seller             = ( int    ) $array_data ['seller'];
    $this->buyer              = ( int    ) $array_data ['buyer'];

    $this->title              = ( string ) $array_data ['title'];
    $this->personal_reference = ( string ) $array_data ['personal_reference'];
    $this->description        = ( string ) $array_data ['description'];
    $this->img_nbr            = ( int    ) $array_data ['img_nbr'];

    $this->currency           = ( string ) $array_data ['currency'];
    $this->price_present      = ( string ) $array_data ['price_present'];
    $this->price_starting     = ( string ) $array_data ['price_starting'];
    $this->price_increment    = ( string ) $array_data ['price_increment'];
    $this->price_reserve      = ( string ) $array_data ['price_reserve'];
    $this->price_buyitnow     = ( string ) $array_data ['price_buyitnow'];
    $this->qty                = ( int    ) $array_data ['qty'];

    $this->bids_nbr           = ( int    ) $array_data ['bids_nbr'];

    $this->date_begin_origin  = ( string ) $array_data ['date_begin_origin'];
    $this->date_begin         = ( string ) $array_data ['date_begin'];
    $this->date_end           = ( string ) $array_data ['date_end'];
    $this->duration           = ( int    ) $array_data ['duration'];
    $this->renew_origin       = ( int    ) $array_data ['renew_origin'];
    $this->renew              = ( int    ) $array_data ['renew'];
    $this->renewed            = ( string ) $array_data ['renewed'];
    $this->closed             = ( string ) $array_data ['closed'];
    $this->sold               = ( string ) $array_data ['sold'];

    $this->payment_asked_date = ( string ) $array_data ['payment_asked_date'];
    $this->payment_sent_date  = ( string ) $array_data ['payment_sent_date'];
    $this->item_sent_date     = ( string ) $array_data ['item_sent_date'];
    $this->item_received_date = ( string ) $array_data ['item_received_date'];
    
    $this->buyer_rating       = ( int    ) $array_data ['buyer_rating'];
    $this->seller_rating      = ( int    ) $array_data ['seller_rating'];
    
    $this->active             = ( string ) $array_data ['active'];
    
    $this->options            = array ('last_minute_extension'   => $array_data ['option_lastminutebidding'],
                                       'anonymous_sale'          => $array_data ['option_privatebidding'],
                                       'subtitle'                => ($array_data ['option_subtitle'] ? $array_data ['option_subtitle_param'] : NULL),
                                       'bold_title'              => $array_data ['option_boldtitle'],
                                       'colour_background'       => $array_data ['option_highlight'],
                                       'colour_border'           => $array_data ['option_coloredborder'],
                                       'selection_list'          => $array_data ['option_toplisting'],
                                       'selection_category'      => $array_data ['option_topcategory'],
                                       'selection_homepage'      => $array_data ['option_topmain'],
                                       'keep_options_on_renewal' => $array_data ['option_keepoptionsonrenewal']
                                      );
    return true;
  }
  
  /**
   * Test auction data coming from api
   * @return boolean 
   */
  public function testing_data_item() {
    // Test id_country
    if (($this->id_country < 0) || ($this->id_country > 11))
      $this->error_delcampe_item [] = 'id_country should be between 0 and 11';
      
    // Test id_site
    if (($this->id_site < 1) || ($this->id_site > 8))
      $this->error_delcampe_item [] = 'id_site should be between 1 and 8';
      
    // Test id_category
    if (($this->id_category < 1) || ($this->id_category > 50000))
      $this->error_delcampe_item [] = 'id_category should be between 1 and 50000';
      
    // Test title
    if ($this->title == '')
      $this->error_delcampe_item [] = 'title is empty';
    if (strlen($this->title) > 120)
      $this->error_delcampe_item [] = 'title is too long (max 120 chars)';
      
    // Test personal_reference
    if (strlen($this->personal_reference) > 20)
      $this->error_delcampe_item [] = 'personal_reference is too long (max 20 chars)';
      
    // Test description
    if (strlen($this->description) > 10000)
      $this->error_delcampe_item [] = 'description is too long (max 10,000 chars)';
    
    // Test images
    foreach($this->images as $image) {
      if (!strstr($image,'http://'))
        $this->error_delcampe_item [] = 'image does not start with http://';
    }
      
    // Test currency
    if (!in_array($this->currency,$GLOBALS['arrCfgAllowedCurrenciesByIdCountry'][$this->id_country]))
      $this->error_delcampe_item [] = 'currency should be in ('.implode(',',$GLOBALS['arrCfgAllowedCurrenciesByIdCountry'][$this->id_country]).') for id_country = '.$this->id_country;
    
    // Test sale_type
    if (!in_array($this->sale_type,array('auction','fixedprice')))
      $this->error_delcampe_item [] = "sale_type should be in ('auction','fixedprice')";
    
    switch($this->sale_type) {
      case 'auction'   :
        // Test price_starting
        if (($this->price_starting < 0.01) || ($this->price_starting > 999999.99))
          $this->error_delcampe_item [] = 'price_starting should be between 0.01 and 999999.99';
        // Test price_reserve
        if ($this->price_reserve > 999999.99)
          $this->error_delcampe_item [] = 'price_reserve should be under 999999.99';
        break;
        // Test price_increment
        if (($this->price_increment < 0.01) || ($this->price_increment > 999999.99))
          $this->error_delcampe_item [] = 'price_increment should be between 0.01 and 999999.99';
      case 'fixedprice':
        // Test price_buyitnow
        if (($this->price_buyitnow < 0.01) || ($this->price_buyitnow > 999999.99))
          $this->error_delcampe_item [] = 'price_buyitnow should be between 0.01 and 999999.99';
        // Test qty
        if (($this->qty < 1) || ($this->qty > 50000))
          $this->error_delcampe_item [] = 'qty should be between 1 and 50000';
        break;
      default          :
        break;
    }

    // Test timezone
    if (($this->timezone < -12) || ($this->timezone > 12))
      $this->error_delcampe_item [] = 'timezone should be between -12 and +12';
      
    if ($this->date_end != '') {
      // Test date_end
      $date_end_timestamp = strtotime($this->date_end);
      if (!$date_end_timestamp) {
        $this->error_delcampe_item [] = 'date_end is not an understandable datetime';
      } elseif (($date_end_timestamp - mktime()) < 0) {
        $this->error_delcampe_item [] = 'date_end is in the past';
      }
    } else {
      // Test prefered_end_day
        if (!in_array($this->prefered_end_day,array(NULL,'mon','tue','wed','thu','fri','sat','sun')))
          $this->error_delcampe_item [] = "prefered_end_day should be in (NULL,'mon','tue','wed','thu','fri','sat','sun')";
      // Test prefered_end_hour
        $prefered_end_hour_timestamp = strtotime($this->prefered_end_hour);
        if (!$prefered_end_hour_timestamp)
          $this->error_delcampe_item [] = 'prefered_end_hour is not an understandable time';
    }
      
    // Test renew_origin
    if (($this->renew_origin < 0) or ($this->renew_origin > 99))
      $this->error_delcampe_item [] = 'renew_origin should be between 0 and 99';
      
    // Test duration
    if (($this->duration < 7) or ($this->duration > 28))
      $this->error_delcampe_item [] = 'duration should be between 7 and 28';
    
    // Test options/subtitle
    if (strlen($this->options['subtitle']) > 120)
      $this->error_delcampe_item [] = 'option/subtitle is too long (max 120 chars)';

    // Test options/last_minute_extension
    if (!in_array($this->options['last_minute_extension'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/last_minute_extension should be Y/N';

    // Test options/anonymous_sale
    if (!in_array($this->options['anonymous_sale'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/anonymous_sale should be Y/N';

    // Test options/bold_title
    if (!in_array($this->options['bold_title'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/bold_title should be Y/N';

    // Test options/colour_border
    if (!in_array($this->options['colour_border'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/colour_border should be Y/N';

    // Test options/colour_background
    if (!in_array($this->options['colour_background'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/colour_background should be Y/N';

    // Test options/selection_list
    if (!in_array($this->options['selection_list'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/selection_list should be Y/N';

    // Test options/selection_category
    if (!in_array($this->options['selection_category'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/selection_category should be Y/N';

    // Test options/selection_homepage
    if (!in_array($this->options['selection_homepage'],array('Y','N')))
      $this->error_delcampe_item [] = 'option/selection_homepage should be Y/N';

    if (isset ( $this->error_delcampe_item )) {
      $return = false;
    } else {
      $return = true;
    }
    
    return $return;
  
  }

}
