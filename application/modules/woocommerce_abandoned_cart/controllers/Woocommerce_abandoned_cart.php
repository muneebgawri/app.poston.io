<?php
/*
Addon Name: WooCommerce Abandoned Cart Recovery 
Unique Name: woocommerce_abandoned_cart
Modules:
{
   "266":{
      "bulk_limit_enabled":"0",
      "limit_enabled":"1",
      "extra_text":"",
      "module_name":"WooCommerce Abandoned Cart Recovery"
   }
}
Project ID: 33
Addon URI: https://xerochat.com
Author: Xerone IT
Author URI: http://xeroneit.net
Version: 2.0
Description: 
*/


require_once("application/controllers/Home.php"); // loading home controller

class Woocommerce_abandoned_cart extends Home
{
  public $addon_data=array();
    
  public function __construct()
  {
      parent::__construct();

      $function_name=$this->uri->segment(2);
      if($function_name!="messenger_checkbox_plugin.js" && $function_name!="webhook_callback") 
      {
        if ($this->session->userdata('logged_in')!= 1) redirect('home/login', 'location');      
        $this->member_validity();
      }
      // getting addon information in array and storing to public variable
      // addon_name,unique_name,module_id,addon_uri,author,author_uri,version,description,controller_name,installed
      //------------------------------------------------------------------------------------------
      $addon_path=APPPATH."modules/".strtolower($this->router->fetch_class())."/controllers/".ucfirst($this->router->fetch_class()).".php"; // path of addon controller
      $addondata=$this->get_addon_data($addon_path); 
      $this->addon_data=$addondata;     
    }

    public function index()
    {
      $this->recovery_plugin_list();
    }

    // Woo commerce abandoned cart recovery add-on
    private function woocommerce_spin_and_replace($str="",$replace = array(),$is_spin=true)
    {
      if(!isset($replace['first_name'])) $replace['first_name'] = '';
      if(!isset($replace['last_name'])) $replace['last_name'] = '';
      if(!isset($replace['email'])) $replace['email'] = '';
      if(!isset($replace['mobile'])) $replace['mobile'] = '';
      if(!isset($replace['cart_url'])) $replace['cart_url'] = '';
      if(!isset($replace['checkout_url'])) $replace['checkout_url'] = '';

      $replace_values = array_values($replace);
      $str = str_replace(array("{{first_name}}","{{last_name}}","{{email}}","{{mobile}}","{{cart_url}}","{{checkout_url}}"), $replace_values, $str);

      if($is_spin) return spintax_process($str);
      else return $str;
    }

    public function webhook_callback($verification_code='')
    {
      if($verification_code=='') exit();
      $join = array('messenger_bot_engagement_checkbox'=>"messenger_bot_engagement_checkbox.id=woocommerce_drip_campaign.engagement_table_id,left");
      $select = "woocommerce_drip_campaign.*,domain_name,skin,center_align,label_ids,reference,language";
      $plugin_data = $this->basic->get_data("woocommerce_drip_campaign",array("where"=>array("verification_code"=>$verification_code)),$select,$join);
      if(!isset($plugin_data[0])) exit();

      $response_raw=$this->input->post('response_raw');
      // $response_raw = '{"action_type":"checkout","order_id":105,"user_id":1,"email":"admin@mysitespy.net","first_name":"Shawn","last_name":"D. Johnson","mobile":"+1805-899-4792","product_ids":[75,71,78],"product_list":{"75":{"product_id":75,"product_name":"Cap","price":"16","quantity":1,"currency":"GBP","permalink":"https:\/\/mysitespy.net\/wc\/product\/cap\/","thumbnail":"https:\/\/mysitespy.net\/wc\/wp-content\/uploads\/2019\/11\/cap-2.jpg"},"71":{"product_id":71,"product_name":"Hoodie with Logo","price":"45","quantity":1,"currency":"GBP","permalink":"https:\/\/mysitespy.net\/wc\/product\/hoodie-with-logo\/","thumbnail":"https:\/\/mysitespy.net\/wc\/wp-content\/uploads\/2019\/11\/hoodie-with-logo-2.jpg"},"78":{"product_id":78,"product_name":"Hoodie with Zipper","price":"45","quantity":1,"currency":"GBP","permalink":"https:\/\/mysitespy.net\/wc\/product\/hoodie-with-zipper\/","thumbnail":"https:\/\/mysitespy.net\/wc\/wp-content\/uploads\/2019\/11\/hoodie-with-zipper-2.jpg"}},"verification_code":"3988360615","unique_id":"54590791d1ace74caab75982e38f5f07","cart_user_unique_ref":"157209486110","cart_url":"https:\/\/mysitespy.net\/wc\/cart\/","checkout_url":"https:\/\/mysitespy.net\/wc\/checkout\/","checkout_info":{"id":105,"parent_id":0,"status":"pending","currency":"GBP","version":"3.7.0","prices_include_tax":false,"date_created":"2019-11-03 11:19:10","date_modified":"2019-11-03 11:19:10","discount_total":"13","coupons":["y7mnndxu"],"discount_tax":"0","shipping_total":"0.00","shipping_tax":"0","cart_tax":"0","total":"93.00","total_tax":"0","customer_id":1,"order_key":"wc_order_8posj2LqCSg0x","billing":{"first_name":"Alamin","last_name":"Jwel","company":"","address_1":"3635 Par Drive","address_2":"","city":"Santa barbara","state":"az","postcode":"93101","country":"us","email":"jwel.cse@gmail.com","phone":"8801723309003"},"shipping":{"first_name":"","last_name":"","company":"","address_1":"","address_2":"","city":"","state":"","postcode":"","country":""},"payment_method":"cod","payment_method_title":"Cash on delivery","transaction_id":"","customer_ip_address":"103.121.60.46","customer_user_agent":"Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/78.0.3904.70 Safari\/537.36","created_via":"checkout","customer_note":"","date_completed":null,"date_paid":null,"cart_hash":"d541dbea65647ae155f6f312f954ce7e","number":"105","order_url":"https:\/\/mysitespy.net\/wc\/my-account\/view-order\/105\/"}}';
       // file_put_contents("wc.txt",$response_raw, FILE_APPEND | LOCK_EX);

      $response = json_decode($response_raw,true);
      if(empty($response)) exit();
      // echo "<pre>";print_r($response);  

      $website_url = isset($plugin_data[0]['domain_name'])?$plugin_data[0]['domain_name']:'';
      $user_id = isset($plugin_data[0]['user_id'])?$plugin_data[0]['user_id']:'0';
      $page_id = isset($plugin_data[0]['page_id'])?$plugin_data[0]['page_id']:'0';
      $woocommerce_drip_campaign_id = isset($plugin_data[0]['id'])?$plugin_data[0]['id']:'0';
      $sms_api_id = isset($plugin_data[0]['sms_api_id'])?$plugin_data[0]['sms_api_id']:'0';
      $sms_content = isset($plugin_data[0]['sms_content'])?json_decode($plugin_data[0]['sms_content'],true):array();
      $email_api_id = isset($plugin_data[0]['email_api_id'])?$plugin_data[0]['email_api_id']:'0';
      $email_content = isset($plugin_data[0]['email_content'])?json_decode($plugin_data[0]['email_content'],true):array();
      $configure_email_table = isset($plugin_data[0]['configure_email_table'])?$plugin_data[0]['configure_email_table']:'';
      $email_subject = isset($plugin_data[0]['email_subject'])?$plugin_data[0]['email_subject']:'';
      if($email_subject!="") $email_subject = $email_subject." : Order Confirmation"; else $email_subject ="Order Confirmation";
      $wc_user_id = isset($response['user_id'])?$response['user_id']:'0';      
      $wc_session_unique_id = isset($response['unique_id'])?$response['unique_id']:'';
      $wc_first_name = isset($response['first_name'])?$response['first_name']:'';
      $wc_last_name = isset($response['last_name'])?$response['last_name']:'';
      $wc_email = isset($response['email'])?$response['email']:'';
      $wc_mobile = isset($response['mobile'])?$response['mobile']:'';
      $wc_mobile = preg_replace("/[^0-9]+/", "", $wc_mobile);
      $check_box_unique_id = isset($response['cart_user_unique_ref'])?$response['cart_user_unique_ref']:'';
      $action_type = isset($response['action_type'])?$response['action_type']:'add';
      $cart_info = isset($response['product_list'])?json_encode($response['product_list'],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE):json_encode(array());
      
      $cart_url = isset($response['cart_url'])?$response['cart_url']:'';
      if($cart_url=="") $cart_url = $website_url."/cart";       
      $checkout_url = isset($response['checkout_url'])?$response['checkout_url']:'';
      if($checkout_url=="") $checkout_url = $website_url."/checkout";

      $initial_date = $updated_at = $checkout_at = date("Y-m-d H:i:s");

      if($wc_session_unique_id=="") exit();

      $wc_first_name_bill = $wc_last_name_bill = $wc_email_bill = $wc_mobile_bill = "";
      $checkout_amount = 0;
      $checkout_country = "";
      $checkout_currency = "";

      $checkout_info = array();
      $confirmation_response = array();
      if($action_type=='checkout')
      {
        
        $previous_webhook_data = $this->basic->get_data("woocommerce_drip_campaign_webhook_call",array('where'=>array('wc_session_unique_id'=>$wc_session_unique_id)));
        $previous_check_box_unique_id = isset($previous_webhook_data[0]['check_box_unique_id']) ? $previous_webhook_data[0]['check_box_unique_id'] : "";

        $i=0;
        $subtotal = 0;
        $elements = array();

        if(isset($response['product_list']))
        foreach ($response['product_list'] as $key => $value) 
        {
          $subtotal += ($value['quantity']*$value['price']);
          $elements[$i]['title'] = isset($value['product_name']) ? $value['product_name'] : "";
          $elements[$i]['subtitle'] = "";
          $elements[$i]['quantity'] = isset($value['quantity']) ? $value['quantity'] : 1;
          $elements[$i]['price'] = isset($value['price']) ? $value['price'] : 0;
          $elements[$i]['currency'] = isset($value['currency']) ? $value['currency'] : "USD";
          $elements[$i]['image_url'] = isset($value['thumbnail']) ? $value['thumbnail'] : "https://via.placeholder.com/500x300";
          $i++;
        }

        $checkout_info  = isset($response['checkout_info']) ? $response['checkout_info'] : array();

        if(isset($checkout_info['shipping']['country']) && $checkout_info['shipping']['country']!="")
        {
          $address = array
          (
            "street_1" => (isset($checkout_info['shipping']['address_1']) && $checkout_info['shipping']['address_1']!='') ? $checkout_info['shipping']['address_1'] : "-",
            "street_2" => isset($checkout_info['shipping']['address_2'])?$checkout_info['shipping']['address_2']:"",
            "city" => (isset($checkout_info['shipping']['city']) && $checkout_info['shipping']['city']!='') ? $checkout_info['shipping']['city'] : "-",
            "postal_code" => (isset($checkout_info['shipping']['postcode']) && $checkout_info['shipping']['postcode']!='') ? $checkout_info['shipping']['postcode'] : "-",
            "state" => (isset($checkout_info['shipping']['state']) && $checkout_info['shipping']['state']!='') ? $checkout_info['shipping']['state'] : "-",
            "country" => (isset($checkout_info['shipping']['country']) && $checkout_info['shipping']['country']!='') ? $checkout_info['shipping']['country'] : "-"
          );

          $wc_first_name_bill = (isset($checkout_info['shipping']['first_name']) && $checkout_info['shipping']['first_name']!="") ? $checkout_info['shipping']['first_name']:$wc_first_name;
          $wc_last_name_bill = (isset($checkout_info['shipping']['last_name']) && $checkout_info['shipping']['last_name']!="") ? $checkout_info['shipping']['last_name']:$wc_last_name;
          $recipient_name = $wc_first_name_bill." ".$wc_last_name_bill;
          $checkout_country = isset($checkout_info['shipping']['country'])?$checkout_info['shipping']['country']:"";
        }
        else
        {
          $address = array
          (
            "street_1" => (isset($checkout_info['billing']['address_1']) && $checkout_info['billing']['address_1']!='') ? $checkout_info['billing']['address_1'] : "-",
            "street_2" => isset($checkout_info['billing']['address_2'])?$checkout_info['billing']['address_2']:"",
            "city" => (isset($checkout_info['billing']['city']) && $checkout_info['billing']['city']!='') ? $checkout_info['billing']['city'] : "-",
            "postal_code" => (isset($checkout_info['billing']['postcode']) && $checkout_info['billing']['postcode']!='') ? $checkout_info['billing']['postcode'] : "-",
            "state" => (isset($checkout_info['billing']['state']) && $checkout_info['billing']['state']!='') ? $checkout_info['billing']['state'] : "-",
            "country" => (isset($checkout_info['billing']['country']) && $checkout_info['billing']['country']!='') ? $checkout_info['billing']['country'] : "-"
          );

          $wc_first_name_bill = (isset($checkout_info['billing']['first_name']) && $checkout_info['billing']['first_name']!="") ? $checkout_info['billing']['first_name']:$wc_first_name;
          $wc_last_name_bill = (isset($checkout_info['billing']['last_name']) && $checkout_info['billing']['last_name']!="") ? $checkout_info['billing']['last_name']:$wc_last_name;
          $recipient_name = $wc_first_name_bill." ".$wc_last_name_bill;
          $checkout_country = isset($checkout_info['billing']['country'])?$checkout_info['billing']['country']:"";
        }

        $summary =array
        (
          "subtotal"=> $subtotal,
          "shipping_cost"=> isset($checkout_info['shipping_total'])?$checkout_info['shipping_total']:0,
          "total_tax"=> isset($checkout_info['total_tax'])?$checkout_info['total_tax']:0,
          "total_cost"=> isset($checkout_info['total'])?$checkout_info['total']:0
        );
        $checkout_amount  = isset($checkout_info['total'])?$checkout_info['total']:0;

        $coupon_code = (isset($checkout_info['coupons']) && is_array($checkout_info['coupons'])) ? implode(',', $checkout_info['coupons']) : "";
        $adjustments =array
        (
          0 => array
          (
            "name"=> $coupon_code,
            "amount"=> isset($checkout_info['discount_total']) ? $checkout_info['discount_total'] : 0
          )
        );

        $payload = array 
        (
          "template_type" => "receipt",
          "recipient_name"=> $recipient_name,
          "order_number"=> isset($checkout_info['number']) ? $checkout_info['number'] : "",
          "currency"=> isset($checkout_info['currency']) ? $checkout_info['currency'] : "USD",
          "payment_method"=> isset($checkout_info['payment_method_title']) ? $checkout_info['payment_method_title'] : "Cash on delivery",        
          "order_url"=> isset($checkout_info['order_url']) ? $checkout_info['order_url'] : "",
          "timestamp"=> time(),
          "address" => $address,
          "summary" => $summary,
          "elements" => $elements
        );
        if($coupon_code!="") $payload['adjustments'] = $adjustments;
        $checkout_currency = isset($checkout_info['currency']) ? $checkout_info['currency'] : "";

        $messenger_checkout_confirmation = array 
        (
          "recipient" => array("user_ref"=>$previous_check_box_unique_id),
          "messaging_type" => "MESSAGE_TAG",
          "tag" => "POST_PURCHASE_UPDATE",
          'message' => array
          (
            'attachment' => 
            array 
            (
              'type' => 'template',
              'payload' => $payload              
            )
          )           
        );
        // echo "<pre>";print_r($messenger_checkout_confirmation); 
        $messenger_checkout_confirmation = json_encode($messenger_checkout_confirmation);

        $sent_response = array();
        // exit();

        // Messenger send block
        $this->load->library("fb_rx_login"); 
        try
        {
            $page_info = $this->basic->get_data("facebook_rx_fb_page_info",array('where'=>array('id'=>$page_id)));
            $page_access_token = isset($page_info[0]['page_access_token']) ? $page_info[0]['page_access_token'] : "";
            $api_response = $this->fb_rx_login->send_non_promotional_message_subscription($messenger_checkout_confirmation,$page_access_token);  
        
            if(isset($api_response['message_id']))
            {
               $sent_response = array("response"=>$api_response['message_id'],"status"=>'1'); 
            }
            else 
            {
                if(isset($api_response["error"]["message"])) 
                $sent_response = array("response"=> $api_response["error"]["message"],"status"=>'0');              
            }              
            
        }
        catch(Exception $e) 
        {
          $sent_response = array("response"=> $e->getMessage(),"status"=>'0'); 
        }
        $confirmation_response['messenger'] = $sent_response;
        // Messenger send block


        //  SMS Sending block   
        $wc_email_bill = (isset($checkout_info['billing']['email']) && $checkout_info['billing']['email']!="") ? $checkout_info['billing']['email']:$wc_email;
        $wc_mobile_bill = (isset($checkout_info['billing']['phone']) && $checkout_info['billing']['phone']!="") ? $checkout_info['billing']['phone']:$wc_mobile;
        $wc_mobile_bill = preg_replace("/[^0-9]+/", "", $wc_mobile_bill);
        $replace_variables = array("first_name"=>$wc_first_name_bill,"last_name"=>$wc_last_name_bill,"email"=>$wc_email_bill,"mobile"=>$wc_mobile_bill,"cart_url"=>$cart_url,"checkout_url"=>$checkout_url);
        if($wc_mobile_bill!="" && $sms_api_id!='0')
        {
          $checkout_text_sms = isset($sms_content['checkout']['checkout_text']) ? $this->woocommerce_spin_and_replace($sms_content['checkout']['checkout_text'],$replace_variables,false) : "";
          $checkout_text_sms = str_replace(array("'",'"'),array('`','`'),$checkout_text_sms);
          
          $sms_response = array("response"=> 'missing param',"status"=>'0');

          if(trim($checkout_text_sms)!="")
          {
            $this->load->library('Sms_manager');
            $this->sms_manager->set_credentioal($sms_api_id,$user_id);
            try
            {
                $response = $this->sms_manager->send_sms($checkout_text_sms, $wc_mobile_bill);

                if(isset($response['id']) && !empty($response['id']))
                {   
                    $message_sent_id = $response['id'];
                    $sms_response = array("response"=> $message_sent_id,"status"=>'1');              
                }
                else 
                {   if(isset($response['status']) && !empty($response['status']))
                {
                        $message_sent_id = $response["status"];
                        $sms_response = array("response"=> $message_sent_id,"status"=>'0');  
                    }
                }           
                
            }
            catch(Exception $e) 
            {
               $message_sent_id = $e->getMessage();
               $sms_response = array("response"=> $message_sent_id,"status"=>'0');
            }
          }

          $confirmation_response['sms']=$sms_response;
        }
        //  SMS Sending block



        //  Email Sending block
        if($wc_email_bill!="" && $email_api_id!='0')
        {
          $checkout_text_email = isset($email_content['checkout']['checkout_text']) ? $this->woocommerce_spin_and_replace($email_content['checkout']['checkout_text'],$replace_variables,false) : "";
          $from_email = "";

          if ($configure_email_table == "email_smtp_config") 
          {
            $from_email = "smtp_".$email_api_id;
          } 
          elseif ($configure_email_table == "email_mandrill_config") 
          {
            $from_email = "mandrill_".$email_api_id;
          } 
          elseif ($configure_email_table == "email_sendgrid_config") 
          {
            $from_email = "sendgrid_".$email_api_id;
          } 
          elseif ($configure_email_table == "email_mailgun_config") 
          {
            $from_email = "mailgun_".$email_api_id;
          }

          $email_response = array("response"=> 'missing param',"status"=>'0');  
          if(trim($checkout_text_email)!='')
          {
            try
            {
              $response = $this->_email_send_function($from_email, $checkout_text_email, $wc_email_bill, $email_subject, $attachement='', $filename='',$user_id);
    
              if(isset($response) && !empty($response) && $response == "Submited")
              {   
                $message_sent_id = $response;
                if($message_sent_id=="Submited") $message_sent_id = "Submitted";
                $email_response = array("response"=> $message_sent_id,"status"=>'1');  
              }
              else 
              {   
                $message_sent_id = $response;
                $email_response = array("response"=> $message_sent_id,"status"=>'0');  
              }           
            }
            catch(Exception $e) 
            {
              $message_sent_id = $e->getMessage();
              $email_response = array("response"=> $message_sent_id,"status"=>'0');  
            }
          }
          $confirmation_response['email']=$email_response;
        }
        //  Email Sending block

      }

      $checkout_info = json_encode($checkout_info,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      $confirmation_response = json_encode($confirmation_response);

      $sql="INSERT INTO woocommerce_drip_campaign_webhook_call (user_id,page_table_id,wc_user_id,cart_url,checkout_url,wc_session_unique_id,woocommerce_drip_campaign_id,wc_first_name,wc_last_name,wc_email,wc_mobile,check_box_unique_id,action_type,cart_info,checkout_info,confirmation_response,initial_date,updated_at,wc_first_name_bill,wc_last_name_bill,wc_email_bill,wc_mobile_bill) 
       VALUES ('$user_id','$page_id','$wc_user_id','$cart_url','$checkout_url','$wc_session_unique_id','$woocommerce_drip_campaign_id','$wc_first_name','$wc_last_name','$wc_email','$wc_mobile','$check_box_unique_id','$action_type','$cart_info','$checkout_info','$confirmation_response','$initial_date','$updated_at','$wc_first_name_bill','$wc_last_name_bill','$wc_email_bill','$wc_mobile_bill')
      ON DUPLICATE KEY UPDATE cart_info='$cart_info',checkout_info='$checkout_info',confirmation_response='$confirmation_response',action_type='$action_type',updated_at='$updated_at',wc_first_name_bill='$wc_first_name_bill',wc_last_name_bill='$wc_last_name_bill',wc_email_bill='$wc_email_bill',wc_mobile_bill='$wc_mobile_bill'";

      if($check_box_unique_id!='' && $check_box_unique_id!="0") $sql.=",check_box_unique_id='$check_box_unique_id'";
      if($action_type=='checkout') $sql.=",checkout_amount='$checkout_amount',checkout_at='$checkout_at',checkout_country='$checkout_country',checkout_currency='$checkout_currency'";
      $sql.=';';

      $this->basic->execute_complex_query($sql);
       // echo $this->db->last_query();
    }


    /* http://example.com/woocommerce_abandoned_cart/messenger_checkbox_plugin.js?code=54848488 */
    public function messenger_checkbox_plugin() // echo js getting domain code
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/javascript');
        $code=$this->input->get('code');
        if($code=="") 
        {
            echo "console.error('".$this->config->item('product_name')." Error : Facebook messenger checkbox plugin is failed to load, no domain code found.');";
            exit();
        }

        $plugin_data=$this->basic->get_data("messenger_bot_engagement_checkbox",array("where"=>array("domain_code"=>$code)));
        if(!isset($plugin_data[0])) 
        {
            echo "console.error('".$this->config->item('product_name')." Error : Facebook messenger checkbox plugin is failed to load, invalid domain code.');";
            exit();
        }

        $user_id=$plugin_data[0]["user_id"];
        $user_data=$this->basic->get_data("users",array("where"=>array("id"=>$user_id,"status"=>"1")));
        if(!isset($user_data[0])) 
        {
            echo "console.error('".$this->config->item('product_name')." Error : Facebook messenger checkbox plugin is failed to load, the requesting user is no longer valid.');";
            exit();
        }

        $join=array('facebook_rx_fb_user_info'=>"facebook_rx_fb_user_info.id=facebook_rx_fb_page_info.facebook_rx_fb_user_info_id,left",'facebook_rx_config'=>"facebook_rx_config.id=facebook_rx_fb_user_info.facebook_rx_config_id,left");
        $get_app_data=$this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("facebook_rx_fb_page_info.id"=>$plugin_data[0]["page_id"])),array("api_id","page_id"),$join);

        $app_id=isset($get_app_data[0]['api_id']) ? $get_app_data[0]['api_id'] : "";
        $fb_page_id=isset($get_app_data[0]['page_id']) ? $get_app_data[0]['page_id'] : "";
        $unique_ref=time().$user_id;
        $language=$plugin_data[0]["language"];
        if($language=="") $language="en_US";
        $validation_error=$plugin_data[0]["validation_error"];
        $button_click_success_message= htmlentities($plugin_data[0]["button_click_success_message"]);

        $reference=$plugin_data[0]["reference"];
        $redirect=$plugin_data[0]["redirect"];
        $center_align=$plugin_data[0]["center_align"];
        $success_redirect_url=$plugin_data[0]["success_redirect_url"];
        $add_button_with_message=$plugin_data[0]["add_button_with_message"];
        $button_with_message_content=json_decode($plugin_data[0]['button_with_message_content'],true);
        $success_button=isset($button_with_message_content['success_button'])?$button_with_message_content['success_button']:"";
        $success_url=isset($button_with_message_content['success_url'])?$button_with_message_content['success_url']:"";
        $success_button_bg_color=isset($button_with_message_content['success_button_bg_color'])?$button_with_message_content['success_button_bg_color']:"";
        $success_button_color=isset($button_with_message_content['success_button_color'])?$button_with_message_content['success_button_color']:"";
        $success_button_bg_color_hover=isset($button_with_message_content['success_button_bg_color_hover'])?$button_with_message_content['success_button_bg_color_hover']:"";
        $success_button_color_hover=isset($button_with_message_content['success_button_color_hover'])?$button_with_message_content['success_button_color_hover']:"";
        if($success_button_bg_color=='') $success_button_bg_color='#5CB85C';
        if($success_button_color=='') $success_button_color='#FFFFFF';
        if($success_button_bg_color_hover=='') $success_button_bg_color_hover='#339966';
        if($success_button_color_hover=='') $success_button_color_hover='#FFFDDD';

        $margin='';

        switch ($plugin_data[0]["btn_size"]) 
        {
            case 'small':
            $fontsize='10';
            $paddingtop='7';
            $paddingleft='9';
            $radius='3';
            $auto=60;
            $margin='margin:10px 0 10px 100px';
            break;

            case 'medium':
            $fontsize='11';
            $paddingtop='9';
            $paddingleft='12';
            $radius='5';
            $margin='margin:10px 0 10px 85px';
            break;

            case 'large':
            $fontsize='12';
            $paddingtop='11';
            $paddingleft='17';
            $radius='7';
            $margin='margin:10px 0 10px 80px';
            break;

            case 'xlarge':
            $fontsize='14';
            $paddingtop='14';
            $paddingleft='22';
            $radius='10';
            $margin='margin:10px 0 10px 70px';
            break;
            
            default:
            $fontsize='11';
            $paddingtop='9';
            $paddingleft='12';
            $radius='5';
            $margin='margin:10px 0 10px 85px';
            break;
        }

        if($plugin_data[0]['center_align']=='false') $margin='10px 0';

        $html_content='';        
        $html_content.='<div class="fb-messenger-checkbox" origin="'.$plugin_data[0]["domain_name"].'" page_id="'.$fb_page_id.'" messenger_app_id="'.$app_id.'" user_ref="'.$unique_ref.'" prechecked="true" allow_login="true" size="'.$plugin_data[0]["btn_size"].'" skin="'.$plugin_data[0]["skin"].'" center_align="'.$plugin_data[0]["center_align"].'"></div><div id="XIT_WACR_CHECKBOX_SUCCESS_MESSAGE" style="padding-left:12px;"></div><input type="hidden" value="'.$unique_ref.'" id="cart_user_unique_ref" name="cart_user_unique_ref" />';

        $script='
        if(document.getElementById("xit-wacr-unique-hash") !==null)
        {
          var reference = "'.$reference.'";
          var xit_wacr_unique_hash = document.getElementById("xit-wacr-unique-hash").value;
          var xit_wacr_unique_hash = "xitwacr_"+xit_wacr_unique_hash+"_"+reference;     
          var node = document.getElementById("MESSENGER_CHECKBOX_PLUGIN_LOADER_01");
          node.innerHTML=\''.$html_content.'\';

          var myCheckBoxState_01="";
          var send_to_messenger_state_01="";
          window.fbAsyncInit = function(){
                FB.init({
                    appId            : "'.$app_id.'",
                    autoLogAppEvents : true,
                    xfbml            : true,
                    version          : "v2.11"
                });
                  
              FB.Event.subscribe("messenger_checkbox", function(e){            
                if (e.event == "rendered") 
                {
                  console.log("Checkbox plugin was rendered");
                } 
                else if (e.event == "checkbox") 
                {
                  myCheckBoxState_01=e.state;
                  if(myCheckBoxState_01=="checked")
                    confirmOptIn();
                }  
                else if (e.event == "hidden") 
                {
                  console.log("Checkbox plugin was hidden");
                }
                
              });   

              FB.Event.subscribe("send_to_messenger", function(e) {

                if (e.event == "rendered") 
                {
                  console.log("Send to messenger plugin was rendered");
                }  
                else if (e.event == "hidden") 
                {
                  console.log("Send to messenger plugin was hidden");
                }   

                else if(e.event=="opt_in")  
                {
                  send_to_messenger_state_01=e.event;
                  confirm_send_to_messenger();
                }   

              });  
            };
          
            (function(d, s, id){
               var js, fjs = d.getElementsByTagName(s)[0];
               if (d.getElementById(id)) {return;}
               js = d.createElement(s); js.id = id;
               js.src = "https://connect.facebook.net/'.$language.'/sdk.js";
               js.src = "//connect.facebook.net/'.$language.'/sdk/xfbml.customerchat.js";    
               fjs.parentNode.insertBefore(js, fjs);
             }(document, "script", "facebook-jssdk"));

             function confirmOptIn() {
                if(myCheckBoxState_01!="checked")
                {
                  var validation_error="'.$validation_error.'";
                  if(validation_error!="")
                  {
                      alert(validation_error);
                  }
                  return false;
                }
                FB.AppEvents.logEvent("MessengerCheckboxUserConfirmation", null, {
                  "app_id":"'.$app_id.'",
                  "page_id":"'.$fb_page_id.'",
                  "ref": xit_wacr_unique_hash,
                  "user_ref":"'.$unique_ref.'"
                });

                
                var button_click_success_message="'.$button_click_success_message.'";            
                var center_align="'.$center_align.'";            

                if(button_click_success_message!="")
                {                
                  var node1 = document.getElementById("XIT_WACR_CHECKBOX_SUCCESS_MESSAGE");
                  node1.innerHTML = button_click_success_message;
                }
                
             }
        }';

       echo $script;        

    }  
   
    public function recovery_plugin_list()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'recovery_plugin_list';
        $data['page_title'] = $this->lang->line("WooCommerce Abandoned Cart Recovery");
        $data['data_days'] = $data_days = 30;        

        $domain_data=$this->basic->get_data("messenger_bot_engagement_checkbox",array("where"=>array("messenger_bot_engagement_checkbox.user_id"=>$this->user_id,"for_woocommerce"=>"1")),'messenger_bot_engagement_checkbox.*,page_name,page_profile',array('facebook_rx_fb_page_info'=>"facebook_rx_fb_page_info.id=messenger_bot_engagement_checkbox.page_id,left"),'',$start=NULL,$order_by="domain_name ASC");
        $data['domain_info'] = $domain_data;
        $default_plugin_id  = isset($domain_data[0]['id']) ? $domain_data[0]['id'] : "";

        $plugin_id = $this->input->post('plugin_id');
        if($plugin_id=="") $plugin_id = $default_plugin_id;
        $this->session->set_userdata("woocommerce_selected_plugin",$plugin_id);

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        if($to_date=='') $to_date = date("Y-m-d");
        if($from_date=='') $from_date = date("Y-m-d",strtotime("$to_date - ".$data_days." days"));

        if($this->input->post('from_date')=="") $from_date=$from_date." 00:00:00";
        if($this->input->post('to_date')=="") $to_date=$to_date." 23:59:59";

        $this->session->set_userdata("woocommerce_from_date",$from_date);
        $this->session->set_userdata("woocommerce_to_date",$to_date);
        
        // if($plugin_id=="") 
        // {
        //   $data["plugin_info"] = array();
        //   $data["webhook_info"] = array();
        //   $this->_viewcontroller($data);
        //   exit();
        // }

        $where_simple=array();
        $where_simple['messenger_bot_engagement_checkbox.id'] = $plugin_id;
        $where_simple['messenger_bot_engagement_checkbox.user_id'] = $this->user_id;
        $where_simple['messenger_bot_engagement_checkbox.for_woocommerce'] ='1';
        $where  = array('where'=>$where_simple);
        $select = array("messenger_bot_engagement_checkbox.*","page_name","facebook_rx_fb_page_info.page_id as fb_page_id");
        $join   = array('facebook_rx_fb_page_info'=>"facebook_rx_fb_page_info.id=messenger_bot_engagement_checkbox.page_id,left");   
        $table = "messenger_bot_engagement_checkbox";
        $info = $this->basic->get_data($table,$where,$select,$join,$limit='',$start='',$order_by='created_at desc');


        $where_simple2=array();
        $where_simple2['woocommerce_drip_campaign.engagement_table_id'] = $plugin_id;
        $where_simple2['woocommerce_drip_campaign.user_id'] = $this->user_id;
        $where_simple2['woocommerce_drip_campaign_webhook_call.updated_at >='] = $from_date;
        $where_simple2['woocommerce_drip_campaign_webhook_call.updated_at <='] = $to_date;
        $where2  = array('where'=>$where_simple2);
        $select2 = array("woocommerce_drip_campaign_webhook_call.*","engagement_table_id");
        $join2   = array('woocommerce_drip_campaign_webhook_call'=>"woocommerce_drip_campaign.id=woocommerce_drip_campaign_webhook_call.woocommerce_drip_campaign_id,right");   
        $table2 = "woocommerce_drip_campaign";
        $info2 = $this->basic->get_data($table2,$where2,$select2,$join2,$limit2='',$start2='',$order_by2='updated_at desc');
         
        $i=0;
        if(isset($info[$i]))
        {
          $info[$i]["domain_name"] = "<a data-toggle='tooltip' data-original-title='".$this->lang->line("Visit store")."' target='_BLANK' href='".addHttp($info[$i]["domain_name"])."'>".$info[$i]["domain_name"]."</a>";
          $info[$i]["page_name"] = "<a data-toggle='tooltip' data-original-title='".$this->lang->line('Visit Page')."' target='_BLANK' href='https://facebook.com/".$info[$i]["fb_page_id"]."'>".$info[$i]["page_name"]."</a>";

          $info[$i]['created_at'] = date('jS F y', strtotime($info[$i]['created_at']));
          $label_ids=$info[$i]["label_ids"];
          $info[$i]['download'] = '<a href="'.base_url("woocommerce_abandoned_cart/download_woocommerce_plugin/".$info[$i]["id"]).'" class="badge badge-status"><i class="fas fa-cloud-download-alt"></i> '.$this->lang->line('Download').'</a>';

          // $label_names="";
          // if($label_ids!="")
          // {
          //     $label_ids_array=explode(',', $label_ids);
          //     $label_data=$this->basic->get_data("messenger_bot_broadcast_contact_group",array("where_in"=>array("id"=>$label_ids_array)));
          //     $label_names_array=array_column($label_data, 'group_name');
          //     $label_names=implode(', ', $label_names_array);
          // }
          // $info[$i]["label_names"]=$label_names;        

          $info[$i]['actions'] = '<div style="min-width:100px;"><a class="btn btn-circle btn-outline-warning" href="'.base_url()."woocommerce_abandoned_cart/recovery_plugin_edit/".$info[$i]['id'].'" data-toggle="tooltip" title="'.$this->lang->line('Edit').'" campaign_id='.$info[$i]['id'].'><i class="fas fa-edit"></i></a> &nbsp;';
          $info[$i]['actions'] .= '<a class="btn btn-circle btn-outline-primary download" data-toggle="tooltip" href="#" title="'.$this->lang->line('download').'" campaign_id='.$info[$i]['id'].'><i class="fas fa-cloud-download-alt"></i></a> &nbsp;';
          $info[$i]['actions'] .= '<a class="btn btn-circle btn-outline-danger delete_campaign" data-toggle="tooltip" href="#" title="'.$this->lang->line('delete').'" campaign_id='.$info[$i]['id'].'><i class="fas fa-trash-alt"></i></a>';
        }       

        if(!isset($info[0]))  $data["plugin_info"] = array();
        else $data["plugin_info"] = $info[0];
        $data["webhook_info"] = $info2;
        $data['country_names'] = $this->get_country_names();
        $data['currency_icons'] = $this->currency_icon();

        $this->_viewcontroller($data);
    }


    public function webhook_data()
    {
      $this->ajax_check();
      $id = $this->input->post('webhook_id');

      $webhook_data = $this->basic->get_data("woocommerce_drip_campaign_webhook_call",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));

      if(!isset($webhook_data[0]))
      {
        echo '<div class="alert alert-danger text-center">'.$this->lang->line("Something went wrong, activity data not found.").'</div>';
        exit();
      }
      $webhook_data_final = $webhook_data[0];
      $country_names = $this->get_country_names();
      $currency_icons = $this->currency_icon();

      $product_list = json_decode($webhook_data_final['cart_info'],true);
      $checkout_info  = json_decode($webhook_data_final['checkout_info'],true);;

      $order_date = date("M j,Y H:i",strtotime($webhook_data_final['updated_at']));      
      $wc_first_name = $webhook_data_final['wc_first_name'];
      $confirmation_response = json_decode($webhook_data_final['confirmation_response'],true);
      $wc_last_name = $webhook_data_final['wc_last_name'];
      $checkout_currency = $webhook_data_final['checkout_currency'];
      $checkout_currency_icon = isset($currency_icons[$checkout_currency])?$currency_icons[$checkout_currency]:'';
      $wc_email_bill = $webhook_data_final['wc_email_bill'];
      if($wc_email_bill=='') $wc_email_bill = $webhook_data_final['wc_email'];  

      $subtotal=0;
      $table_data ='
      <div class="table-responsive">
      <table class="table table-striped table-hover table-md">
        <tbody>
        <tr>
          <th data-width="40">#</th>
          <th class="text-center">Thumbnail</th>
          <th>Item</th>
          <th class="text-center">Unit Price</th>
          <th class="text-center">Quantity</th>
          <th class="text-right">Price</th>
        </tr>';
      $i=0;
      foreach ($product_list as $key => $value) 
      {
        $currency_icon = isset($currency_icons[$value['currency']])?$currency_icons[$value['currency']]:'$';
        $item_total = $value['quantity']*$value['price'];
        $subtotal += $item_total;
        $item_total = number_format((float)$item_total, 2, '.', '');
        $title = isset($value['product_name']) ? $value['product_name'] : "";
        $quantity = isset($value['quantity']) ? $value['quantity'] : 1;
        $price = isset($value['price']) ? $value['price'] : 0;
        $price = number_format((float)$price, 2, '.', '');
        $currency = isset($value['currency']) ? $value['currency'] : "USD";
        if($checkout_currency_icon=='') $checkout_currency_icon = $currency_icon;
        $image_url = (isset($value['thumbnail']) && !empty($value['thumbnail'])) ? $value['thumbnail'] : "https://via.placeholder.com/80x80";        
        $permalink = isset($value['permalink']) ? $value['permalink'] : "";        

        $i++;

        $table_data .='
        <tr>
          <td data-width="40">'.$i.'</td>
          <td class="text-center"><img src="'.$image_url.'" style="width:80px; height:80px;" class="img-thumbnail" onerror="this.onerror=null;this.src=\'https://via.placeholder.com/80x80\';"></td>
          <td><a href="'.$permalink.'" target="_BLANK">'.$title.'</a></td>
          <td class="text-center">'.$currency_icon.$price.'</td>
          <td class="text-center">'.$quantity.'</td>
          <td class="text-right">'.$currency_icon.$item_total.'</td>
        </tr>';
      }
      $table_data .= '</tbody></table></div>';
    
    
      $address_bill_array = array
      (
        "street_1" => isset($checkout_info['billing']['address_1'])?$checkout_info['billing']['address_1']:"",
        "street_2" => isset($checkout_info['billing']['address_2'])?$checkout_info['billing']['address_2']:"",
        "city" => isset($checkout_info['billing']['city'])?$checkout_info['billing']['city']:"",
        "postal_code" => isset($checkout_info['billing']['postcode'])?$checkout_info['billing']['postcode']:"",
        "state" => isset($checkout_info['billing']['state'])?$checkout_info['billing']['state']:""
      );
      if($address_bill_array['street_2']=='') unset($address_bill_array['street_2']);
      $address_bill = implode('<br/>', $address_bill_array);

      $wc_first_name_bill = (isset($checkout_info['billing']['first_name']) && $checkout_info['billing']['first_name']!="") ? $checkout_info['billing']['first_name']:$webhook_data_final['wc_first_name'];
      $wc_last_name_bill = (isset($checkout_info['billing']['last_name']) && $checkout_info['billing']['last_name']!="") ? $checkout_info['billing']['last_name']:$webhook_data_final['wc_last_name'];
      $wc_buyer_bill = $wc_first_name_bill." ".$wc_last_name_bill;
      if(trim($wc_buyer_bill)=='') $wc_buyer_bill = $wc_first_name." ".$wc_last_name;
      $checkout_country_bill = isset($checkout_info['billing']['country'])?$checkout_info['billing']['country']:"";
      $checkout_country_bill = isset($country_names[strtoupper($checkout_country_bill)]) ? ucwords(strtolower($country_names[strtoupper($checkout_country_bill)])) : $checkout_country_bill;
      if($checkout_country_bill!="") $address_bill = $address_bill.", ".$checkout_country_bill;
      
      $address_ship_array = array
      (
        "street_1" => isset($checkout_info['shipping']['address_1'])?$checkout_info['shipping']['address_1']:"",
        "street_2" => isset($checkout_info['shipping']['address_2'])?$checkout_info['shipping']['address_2']:"",
        "city" => isset($checkout_info['shipping']['city'])?$checkout_info['shipping']['city']:"",
        "postal_code" => isset($checkout_info['shipping']['postcode'])?$checkout_info['shipping']['postcode']:"",
        "state" => isset($checkout_info['shipping']['state'])?$checkout_info['shipping']['state']:""
      );
      if($address_ship_array['street_2']=='') unset($address_ship_array['street_2']);
      if(!array_filter($address_ship_array)) $address_ship_array = $address_bill_array;
      $address_ship = implode('<br/>', $address_ship_array);

      $wc_first_name_ship = (isset($checkout_info['shipping']['first_name']) && $checkout_info['shipping']['first_name']!="") ? $checkout_info['shipping']['first_name']:$webhook_data_final['wc_first_name'];
      $wc_last_name_ship = (isset($checkout_info['shipping']['last_name']) && $checkout_info['shipping']['last_name']!="") ? $checkout_info['shipping']['last_name']:$webhook_data_final['wc_last_name'];
      $wc_buyer_ship = $wc_first_name_ship." ".$wc_last_name_ship;
      if(trim($wc_buyer_ship)=='') $wc_buyer_ship = $wc_buyer_bill;
      $checkout_country_ship = isset($checkout_info['shipping']['country'])?$checkout_info['shipping']['country']:"";
      $checkout_country_ship = isset($country_names[strtoupper($checkout_country_ship)]) ? ucwords(strtolower($country_names[strtoupper($checkout_country_ship)])) : $checkout_country_ship;
      if($checkout_country_ship=="") $checkout_country_ship = $checkout_country_bill;
      if($checkout_country_ship!="") $address_ship = $address_ship.", ".$checkout_country_ship;
         
      $shipping_cost = isset($checkout_info['shipping_total'])?$checkout_info['shipping_total']:0;
      $total_tax = isset($checkout_info['total_tax'])?$checkout_info['total_tax']:0;     
      $checkout_amount  = isset($checkout_info['total'])?$checkout_info['total']:$subtotal;

      $coupon_code = (isset($checkout_info['coupons']) && is_array($checkout_info['coupons'])) ? implode(',', $checkout_info['coupons']) : "";
      $coupon_amount = isset($checkout_info['discount_total']) ? $checkout_info['discount_total'] : 0;
      if($coupon_code=="") $coupon_info = "No Coupon";
      else $coupon_info = $coupon_code." (".$checkout_currency_icon.$coupon_amount.")";
     
      $payment_method = isset($checkout_info['payment_method_title']) ? $checkout_info['payment_method_title'] : '<span class="badge badge-danger">Incomplete Order</span>';  
      $order_no = isset($checkout_info['number']) ? "#".$checkout_info['number'] : '<span class="badge badge-danger">Incomplete Order</span>';     
      $order_url = isset($checkout_info['order_url']) ? $checkout_info['order_url'] : "";
      if($order_url!="") $order_no = '<a href="'.$order_url.'" target="_BLANK">'.$order_no.'</a>';

      $subtotal = number_format((float)$subtotal, 2, '.', '');
      $shipping_cost = number_format((float)$shipping_cost, 2, '.', '');
      $total_tax = number_format((float)$total_tax, 2, '.', '');
      $checkout_amount = number_format((float)$checkout_amount, 2, '.', '');
      $coupon_amount = number_format((float)$coupon_amount, 2, '.', '');    
 
      echo 
      '
        <section class="section">
          <div class="section-body">
            <div class="invoice" style="border:1px solid #dee2e6;">
              <div class="invoice-print">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="invoice-title">
                      <h4>Order</h4>
                      <div class="invoice-number">'.$order_no.'</div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>Billed To:</strong><br>
                           '.$wc_buyer_bill.'<br>
                           '.$address_bill.'
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>Deliver To:</strong><br>
                          '.$wc_buyer_ship.'<br>
                          '.$address_ship.'
                        </address>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <address>
                          <strong>Email:</strong><br>
                          '.$wc_email_bill.'
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address>
                          <strong>Order Date:</strong><br>
                          '.$order_date.'<br><br>
                        </address>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="section-title">Order Summary</div>
                    '.$table_data.'
                    <div class="row mt-4">
                      <div class="col-lg-8">
                        <div class="section-title">'.$this->lang->line("Coupon").'</div>
                        <p class="section-lead">'.$coupon_info.'</p>
                        <div class="section-title">'.$this->lang->line("Payment Method").'</div>
                        <p class="section-lead">'.$payment_method.'</p>                        
                      </div>
                      <div class="col-lg-4 text-right">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Subtotal</div>
                          <div class="invoice-detail-value">'.$checkout_currency_icon.$subtotal.'</div>
                        </div>
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Shipping</div>
                          <div class="invoice-detail-value">'.$checkout_currency_icon.$shipping_cost.'</div>
                        </div>
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Tax</div>
                          <div class="invoice-detail-value">'.$checkout_currency_icon.$total_tax.'</div>
                        </div>
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Total</div>
                          <div class="invoice-detail-value invoice-detail-value-lg">'.$checkout_currency_icon.$checkout_amount.'</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>              
            </div>
          </div>
        </section>
      ';

      if($webhook_data_final['action_type']=='checkout')
      {
        $messenger_confirmation_badge = '<span class="badge badge-light badge-pill">'.$this->lang->line("Unknown").'</span>';
        if(isset($confirmation_response['messenger']))
        {
          if(isset($confirmation_response['messenger']['status']) && $confirmation_response['messenger']['status']=='1') $messenger_confirmation_badge = '<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['messenger']['response']).'" class="badge badge-success badge-pill">'.$this->lang->line("Sent").'</span>';
          else if(isset($confirmation_response['messenger']['status']) && $confirmation_response['messenger']['status']=='0') $messenger_confirmation_badge = '<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['messenger']['response']).'" class="badge badge-danger badge-pill">'.$this->lang->line("Error").'</span>';
          else $messenger_confirmation_badge = '<span class="badge badge-dark badge-pill">'.$this->lang->line("Not Set").'</span>';
        }
        $messenger_li = '<li class="list-group-item d-flex justify-content-between align-items-center">
                          '.$this->lang->line("Messenger Confirmation").'
                          '.$messenger_confirmation_badge.'
                        </li>';

        $sms_li = $email_li = "";
        if($this->session->userdata('user_type') == 'Admin' || in_array(264,$this->module_access)) 
        {
          $sms_confirmation_badge = '<span class="badge badge-light badge-pill">'.$this->lang->line("Unknown").'</span>';
          if(isset($confirmation_response['sms']))
          {
            if(isset($confirmation_response['sms']['status']) && $confirmation_response['sms']['status']=='1') $sms_confirmation_badge = '<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['sms']['response']).'" class="badge badge-success badge-pill">'.$this->lang->line("Sent").'</span>';
            else if(isset($confirmation_response['sms']['status']) && $confirmation_response['sms']['status']=='0') $sms_confirmation_badge = '<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['sms']['response']).'" class="badge badge-danger badge-pill">'.$this->lang->line("Error").'</span>';
            else $sms_confirmation_badge = '<span class="badge badge-dark badge-pill">'.$this->lang->line("Not Set").'</span>';
          }
          $sms_li = '<li class="list-group-item d-flex justify-content-between align-items-center">
                      '.$this->lang->line("SMS Confirmation").'
                      '.$sms_confirmation_badge.'
                    </li>';
        }

        if($this->session->userdata('user_type') == 'Admin' || in_array(263,$this->module_access)) 
        {
          $email_confirmation_badge = '<span class="badge badge-light badge-pill">'.$this->lang->line("Unknown").'</span>';
          if(isset($confirmation_response['email']))
          {
            if(isset($confirmation_response['email']['status']) && $confirmation_response['email']['status']=='1') $email_confirmation_badge = '<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['email']['response']).'" class="badge badge-success badge-pill">'.$this->lang->line("Sent").'</span>';
            else if(isset($confirmation_response['email']['status']) && $confirmation_response['email']['status']=='0') $email_confirmation_badge ='<span data-toggle="tooltip" title="'.htmlspecialchars($confirmation_response['email']['response']).'" class="badge badge-danger badge-pill">'. $this->lang->line("Error").'</span>';
            else $email_confirmation_badge = '<span class="badge badge-dark badge-pill">'.$this->lang->line("Not Set").'</span>';
          }
          $email_li = '<li class="list-group-item d-flex justify-content-between align-items-center">
                        '.$this->lang->line("Email Confirmation").'
                        '.$email_confirmation_badge.'
                      </li>';
        }
        echo 
        '
          <section class="section">
            <div class="section-body">
              <div class="invoice" style="border:1px solid #dee2e6;">
                <div class="invoice-print">
                  <div class="row">
                    <div class="col-12">
                      <div class="invoice-title">
                        <h4>'.$this->lang->line("Checkout Confirmation").'</h4>
                        <div class="invoice-number"></div>
                      </div>
                      <hr>
                      <ul class="list-group">
                        '.$messenger_li.$sms_li.$email_li.'
                      </ul>
                    </div>
                  </div>              
                </div>
              </div>
            </div>
          </section>
        ';
        echo "<script>$('[data-toggle=\"tooltip\"]').tooltip();</script>";
      }
      
    }

    public function download_woocommerce_plugin_modal()
    {
      $this->ajax_check();
      if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access)) exit();
      $auto_id = $this->input->post("campaign_id");
      $join = array('messenger_bot_engagement_checkbox'=>"messenger_bot_engagement_checkbox.id=woocommerce_drip_campaign.engagement_table_id,left");
      $select = "messenger_bot_engagement_checkbox.id,domain_code,verification_code";
      $plugin_data = $this->basic->get_data("woocommerce_drip_campaign",array("where"=>array("engagement_table_id"=>$auto_id,"woocommerce_drip_campaign.user_id"=>$this->user_id)),$select,$join);
      if(!isset($plugin_data[0]))
      {
        echo json_encode(array('status'=>'0','message'=>$this->lang->line("Something went wrong. We could not find plugin data.")));
        exit();
      }

      $id = isset($plugin_data[0]['id']) ? $plugin_data[0]['id'] : "0";
      $domain_code = isset($plugin_data[0]['domain_code']) ? $plugin_data[0]['domain_code'] : "";
      $verification_code = isset($plugin_data[0]['verification_code']) ? $plugin_data[0]['verification_code'] : "";
      $download_url = base_url('woocommerce_abandoned_cart/download_woocommerce_plugin/'.$id);
      $webhook_url = base_url('woocommerce_abandoned_cart/webhook_callback');
      $script_url = base_url('woocommerce_abandoned_cart/messenger_checkbox_plugin.js?code='.$domain_code);

      $message='';
      $message .='<div class="card text-center" style="margin:0 auto; border:1px solid #ddd;"> <div class="card-body"><a href="'.$download_url.'"><i class="fas fa-cloud-download-alt" style="font-size: 50px;"></i></a></div> <div class="card-footer bg-whitesmoke"><a href="'.$download_url.'">'.$this->lang->line("Download Plugin").'</a></div></div>';
      $message .= '
      <br>
      <div class="list-group">
        <span href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">'.$this->lang->line("Verification Code").'</h6>
          </div>
          <p class="mb-1"><pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword">'.$verification_code.'</span></code></pre></p>
        </span>
        <span href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1"> '.$this->lang->line("Webhook Call URL").'</h6>
          </div>
          <p class="mb-1"><pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword">'.$webhook_url.'</span></code></pre></p>
        </span>
        <span href="#" class="list-group-item list-group-item-action flex-column align-items-start">
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1"> '.$this->lang->line("Checkbox Script URL").'</h6>
          </div>
          <p class="mb-1"><pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword">'.$script_url.'</span></code></pre></p>
        </span>      
      </div>';

      echo json_encode(array('status'=>'1','message'=>$message));
    }

    public function download_woocommerce_plugin($auto_id='0')
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access)) exit();

        if(!class_exists('ZipArchive'))
        {
           redirect(base_url('woocommerce_abandoned_cart/zip_error'),'location');
           exit();
        }

        $join = array('messenger_bot_engagement_checkbox'=>"messenger_bot_engagement_checkbox.id=woocommerce_drip_campaign.engagement_table_id,left");
        $select = "messenger_bot_engagement_checkbox.id,domain_code,verification_code";
        $plugin_data = $this->basic->get_data("woocommerce_drip_campaign",array("where"=>array("engagement_table_id"=>$auto_id,"woocommerce_drip_campaign.user_id"=>$this->user_id)),$select,$join);
        if(!isset($plugin_data[0]))
        {
           redirect(base_url('woocommerce_abandoned_cart/zip_error/1'),'location');
           exit();        
        }

        $domain_code = isset($plugin_data[0]['domain_code']) ? $plugin_data[0]['domain_code'] : "";
        $verification_code = isset($plugin_data[0]['verification_code']) ? $plugin_data[0]['verification_code'] : "";

        $webhook_url = base_url('woocommerce_abandoned_cart/webhook_callback');
        $api_key = $verification_code;
        $script_url = base_url('woocommerce_abandoned_cart/messenger_checkbox_plugin.js?code='.$domain_code);
        $zipFileName = "woocommerce-abandoned-cart-recovery-".$this->user_id; // only name of zip file
        $zipFolderPath = 'download/'.$zipFileName; // folder name which will be compressed
        $zipFilePath = $zipFolderPath.'.zip'; // final zip file path
        $fileReplacePath = $zipFolderPath.'/woocommerce-abandoned-cart-recovery/includes/class-woocommerce-abandoned-cart-recovery.php'; //file need to update before zip

        // Get real path of input/output folder
        $rootPath = FCPATH.'application/modules/woocommerce_abandoned_cart/plugin';
        $destPath = FCPATH.'download/'.$zipFileName;

        if(!file_exists($destPath)) mkdir($destPath, 0755, true); // create folder
        else // if somehow folder exists delete it and re-create
        {
          $this->delete_directory($destPath);
          mkdir($destPath, 0755, true);
        }
        $this->recurse_copy($rootPath,$destPath); // copy plugin files to newly created folder

        // updating plugin file
        $old_content=file_get_contents($fileReplacePath,true);
        $new_content = str_replace(array('XIT_WACR_WEBHOOK_CALL_URL','XIT_WACR_VERIFICATION_CODE','XIT_WACR_CHECKBOX_SCRIPT_URL'),array($webhook_url,$api_key,$script_url), $old_content);        
        file_put_contents($fileReplacePath, $new_content, LOCK_EX);

        // making final zip 
        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        // create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($destPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file)
        {
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($destPath) + 1);
               
                $zip->addFile($filePath, $relativePath); // Add current file to archive
            }
        }
        $zip->close();

        //delete temp copied folder
        $this->delete_directory($destPath);

        redirect($zipFilePath,'location');    
    }

    private function recurse_copy($src,$dst)
    {
      $dir = opendir($src);
      @mkdir($dst);
      while(false !== ( $file = readdir($dir)) ) {
          if (( $file != '.' ) && ( $file != '..' )) {
              if ( is_dir($src . '/' . $file) ) {
                  $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
              }
              else {
                  copy($src . '/' . $file,$dst . '/' . $file);
              }
          }
      }
      closedir($dir);
    }

    public function recovery_plugin_delete($campaign_id=0)
    {   
        $this->ajax_check();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access)) exit();

        $id = $this->input->post('campaign_id',true);
        $response = array();
        $this->db->trans_start();
        $xdata=$this->basic->get_data("messenger_bot_engagement_checkbox",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $domain_code=isset($xdata[0]['domain_code'])?$xdata[0]['domain_code']:"";

        if($domain_code=="")
        {
            $response['status'] = '0';
            $response['message'] = $this->lang->line('Something went wrong.');
        }
        
        $this->basic->delete_data('messenger_bot_engagement_checkbox',$where=array('id'=>$id,"user_id"=>$this->user_id));
        $drip_data = $this->basic->get_data("woocommerce_drip_campaign",array("where"=>array("engagement_table_id"=>$id,"user_id"=>$this->user_id)),"id");
        $woocommerce_drip_campaign_id = isset($drip_data[0]['id']) ? $drip_data[0]['id'] : 0;
        $this->basic->delete_data('woocommerce_drip_campaign',$where=array('engagement_table_id'=>$id,"user_id"=>$this->user_id));
        $this->basic->delete_data('woocommerce_drip_campaign_report',$where=array('woocommerce_drip_campaign_id'=>$woocommerce_drip_campaign_id,"user_id"=>$this->user_id));
        $this->basic->delete_data('woocommerce_drip_campaign_webhook_call',$where=array('woocommerce_drip_campaign_id'=>$woocommerce_drip_campaign_id,"user_id"=>$this->user_id));
        //******************************//
        // delete data to useges log table
        $this->_delete_usage_log($module_id=266,$request=1);   
        //******************************//

        $this->db->trans_complete();
        if($this->db->trans_status() === false) 
        {
          $response['status'] = '0';
          $response['message'] = $this->lang->line('Something went wrong.');
        } 
        else 
        {

          $response['status'] = '1';
          $response['message'] = $this->lang->line('Plugin has been deleted successfully.');
        }
        echo json_encode($response);
    }

    public function recovery_plugin_add()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'recovery_plugin_add';
        $data['page_title'] = $this->lang->line("Add WooCommerce Abandoned Cart Plugin");
        $data['page_info'] = $this->get_user_page();
        $data['sdk_list'] = $this->sdk_locale();
        $data['btn_sizes'] = $this->basic->get_enum_values("messenger_bot_engagement_checkbox","btn_size");
        
        $data['how_many_reminder'] = 3;
        $data['hours'] = $this->get_reminder_hour();

        $data['sms_option'] = $this->get_sms_api();
        $data['email_option'] = $this->get_email_api();

        $this->_viewcontroller($data);
    }


    public function recovery_plugin_add_action()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access)) exit();

        $status=$this->_check_usage($module_id=266,$request=1);
        if($status=="3")  //monthly limit is exceeded, can not create another campaign this month
        {
            echo json_encode(array("status" => "0", "message" =>$this->lang->line("limit has been exceeded. you can no longer use this feature.")));
            exit();
        }

        $post=$_POST;
        foreach ($post as $key => $value) 
        {
          $$key=$this->input->post($key,true);
        }
        
        $anything_found = false;
        $messenger_content = array();
        $sms_content = array();
        $email_content = array();
        $created_at = date("Y-m-d H:i:s");
        $insert_data2 = array("user_id"=>$this->user_id,"page_id"=>$page,"created_at"=>$created_at);
        
        foreach ($msg_reminder_time as $key => $value) 
        {
          $i=$key;
          $j=$i+1;
          if($value!="")
          {            
            $tmp_msg_reminder_text = isset($msg_reminder_text[$i]) ? $msg_reminder_text[$i] : "";
            $tmp_msg_reminder_btn_details = isset($msg_reminder_btn_details[$i]) ? strtoupper($msg_reminder_btn_details[$i]) : "VISIT DETAILS";
            $tmp_msg_reminder_text_checkout = isset($msg_reminder_text_checkout[$i]) ? $msg_reminder_text_checkout[$i] : "Stock limited, complete your order before it is out of stock.";
            $tmp_msg_reminder_btn_checkout = isset($msg_reminder_btn_checkout[$i]) ? strtoupper($msg_reminder_btn_checkout[$i]) : "CHECKOUT NOW";
            $messenger_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$tmp_msg_reminder_text,"reminder_btn_details"=>$tmp_msg_reminder_btn_details,"reminder_text_checkout"=>$tmp_msg_reminder_text_checkout,"reminder_btn_checkout"=>$tmp_msg_reminder_btn_checkout);
            $anything_found = true;
          }          
        }
        if($msg_reminder_text_checkout_next=="") $msg_reminder_text_checkout_next="There are many other exciting products you may like as well. Let's see what's there !";
        $messenger_content['checkout'] = array("checkout_text"=>$msg_checkout_text,"checkout_text_next"=>$msg_reminder_text_checkout_next,"checkout_btn_next"=>strtoupper($msg_checkout_btn_website));
        $insert_data2['messenger_content'] = json_encode($messenger_content);

        if($this->session->userdata('user_type') == 'Admin' || in_array(264,$this->module_access))
        {
          foreach ($sms_reminder_time as $key => $value) 
          {
            $i=$key;
            $j=$i+1;
            if($value!="")
            {           
              $temp_sms_reminder_text_checkout = isset($sms_reminder_text_checkout[$i]) ? $sms_reminder_text_checkout[$i] : "";
              $sms_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$temp_sms_reminder_text_checkout);
              $anything_found = true;
            }
            
          }
          $sms_content['checkout'] = array("checkout_text"=>$sms_reminder_text_checkout_next);
          if(isset($sms_api_id) && $sms_api_id!="")
          {
            $insert_data2['sms_api_id'] = $sms_api_id;
            $insert_data2['sms_content'] = json_encode($sms_content);
          }
        }

        if($this->session->userdata('user_type') == 'Admin' || in_array(263,$this->module_access))
        {
          foreach ($email_reminder_time as $key => $value) 
          {
            $i=$key;  
            $j=$i+1; 
            if($value!="")
            {           
              $tmp_email_reminder_text_checkout = isset($email_reminder_text_checkout[$i]) ? $email_reminder_text_checkout[$i] : "";
              $email_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$tmp_email_reminder_text_checkout);
              $anything_found = true;
            }
            
          }
          $email_content['checkout'] = array("checkout_text"=>$email_reminder_text_checkout_next);
          if(isset($email_api_id) && $email_api_id!="")
          {
            if($email_subject=="") $email_subject = "Abandoned Cart";
            $exp = explode('-', $email_api_id);
            $insert_data2['configure_email_table'] = isset($exp[0]) ? $exp[0] : '';
            $insert_data2['email_api_id'] = isset($exp[1]) ? $exp[1] : 0;
            $insert_data2['email_content'] = json_encode($email_content);
            $insert_data2['email_subject'] = $email_subject;
          }
        }
        
        if(!$anything_found)
        {
          echo json_encode(array('status'=>'0','message'=>$this->lang->line('You have not set any reminder.')));
          exit();
        }  

        $pageinfo=$this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("id"=>$page)));
        $access_token=isset($pageinfo[0]["page_access_token"])?$pageinfo[0]["page_access_token"]:"";
        
        if($access_token=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Facebook page not found.')));
            exit();
        }

        $this->load->library('fb_rx_login');
        $domain_whitelist=$this->fb_rx_login->domain_whitelist($access_token,$domain_name);
        if(!isset($domain_whitelist['status']) || $domain_whitelist['status']=='0')
        {
            $fb_login_button='';
            $error=$this->lang->line('Domain failed to white-list.');
            if(isset($domain_whitelist['error']['code']) && trim($domain_whitelist['error']['code'])=='230') //does not have page_messages permission, need to login again
            {
                // $redirect_url = base_url()."home/redirect_rx_link";
                // $fb_login_button = $this->fb_rx_login->login_for_user_access_token($redirect_url);
                // $fb_login_button="<br><br>".$fb_login_button;
                $error= $this->lang->line('Domain failed to white-list. Requires pages_messaging permission to perform this operation. You need to login with Facebook again clicking the button below and then you can continue.');
            }
            else if(isset($domain_whitelist['error']['message']))
            {
                $error=$domain_whitelist['error']['message'];
            }
            echo json_encode(array('status'=>'0','message'=>"".$error.$fb_login_button));
            exit();
        }

        $domain_code = $this->_random_number_generator(10);
        $verification_code = $this->_random_number_generator(10);
        // $reference=$reference."-".$domain_code;
        if($this->basic->is_exist("messenger_bot_engagement_checkbox",array("reference"=>$reference))) 
        {
            $unique_lang=$this->lang->line("is_unique");
            $unique_lang=str_replace('<b>%s</b>', $this->lang->line('reference'), $unique_lang);
            echo json_encode(array("status" => "0", "message" =>$unique_lang));
            exit();
        }

      
        $this->db->trans_start(); 

        if(!isset($label_ids)) $label_ids=array();
       
        $insert_data = array(
          "domain_code"=>$domain_code,
          "user_id"=>$this->user_id,
          "page_id"=>$page,
          "domain_name"=>$domain_name,
          "btn_size"=>$btn_size,
          "skin"=>$skin,
          "center_align"=>"false",
          "label_ids"=>implode(',',$label_ids),
          "reference"=>$reference,
          "language"=>$language,
          "button_click_success_message"=>$button_click_success_message,
          "created_at"=>$created_at,
          "for_woocommerce"=>'1'
        );
       
        $this->basic->insert_data("messenger_bot_engagement_checkbox",$insert_data);
        $insert_id = $this->db->insert_id();
        $insert_data2['engagement_table_id'] = $insert_id;
        $insert_data2['verification_code'] = $verification_code;
        $this->basic->insert_data("woocommerce_drip_campaign",$insert_data2);

        $this->_insert_usage_log($module_id=266,$request=1);
        $this->db->trans_complete();

        // $download_url=base_url('woocommerce_abandoned_cart/download_woocommerce_plugin/'.$insert_id);

        if($this->db->trans_status() === false)
        {
             echo json_encode(array('status'=>'0','message'=>"".$this->lang->line('Something went wrong, please try again.')));
             exit();
        }
        else
        {
            echo json_encode(array('status'=>'1','message'=>$this->lang->line('Almost done. The next step is to download plugin and install in your WordPress website.'),'id'=>$insert_id));
            exit();
        } 
    }

    public function recovery_plugin_edit($id=0)
    {
        if($id==0) exit();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'recovery_plugin_edit';
        $data['page_title'] = $this->lang->line("Edit WooCommerce Abandoned Cart Plugin");
        
        $data['page_info'] = $this->get_user_page();
        $data['sdk_list'] = $this->sdk_locale();
        $data['btn_sizes'] = $this->basic->get_enum_values("messenger_bot_engagement_checkbox","btn_size");
        
        $data['how_many_reminder'] = 3;
        $data['hours'] = $this->get_reminder_hour();

        $data['sms_option'] = $this->get_sms_api();
        $data['email_option'] = $this->get_email_api();

        $xdata=$this->basic->get_data("messenger_bot_engagement_checkbox",array("where"=>array("messenger_bot_engagement_checkbox.id"=>$id,"messenger_bot_engagement_checkbox.user_id"=>$this->user_id)),"messenger_bot_engagement_checkbox.*,messenger_content,sms_content,sms_api_id,email_content,email_api_id,email_subject,configure_email_table",array('woocommerce_drip_campaign'=>"messenger_bot_engagement_checkbox.id=woocommerce_drip_campaign.engagement_table_id,left"));
        if(!isset($xdata[0])) exit();
        $data['xdata']=$xdata[0];
        $this->_viewcontroller($data);
    }



    public function recovery_plugin_edit_action()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(266,$this->module_access)) exit();
        $post=$_POST;
        foreach ($post as $key => $value) 
        {
          $$key=$this->input->post($key,true);
        }
        
        $anything_found = false;
        $messenger_content = array();
        $sms_content = array();
        $email_content = array();
        $created_at = date("Y-m-d H:i:s");
        $insert_data2 = array();
        
        foreach ($msg_reminder_time as $key => $value) 
        {
          $i=$key;
          $j=$i+1;
          if($value!="")
          {            
            $tmp_msg_reminder_text = isset($msg_reminder_text[$i]) ? $msg_reminder_text[$i] : "";
            $tmp_msg_reminder_btn_details = isset($msg_reminder_btn_details[$i]) ? strtoupper($msg_reminder_btn_details[$i]) : "VISIT DETAILS";
            $tmp_msg_reminder_text_checkout = isset($msg_reminder_text_checkout[$i]) ? $msg_reminder_text_checkout[$i] : "Stock limited, complete your order before it is out of stock.";
            $tmp_msg_reminder_btn_checkout = isset($msg_reminder_btn_checkout[$i]) ? strtoupper($msg_reminder_btn_checkout[$i]) : "CHECKOUT NOW";
            $messenger_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$tmp_msg_reminder_text,"reminder_btn_details"=>$tmp_msg_reminder_btn_details,"reminder_text_checkout"=>$tmp_msg_reminder_text_checkout,"reminder_btn_checkout"=>$tmp_msg_reminder_btn_checkout);
            $anything_found = true;
          }          
        }
        if($msg_reminder_text_checkout_next=="") $msg_reminder_text_checkout_next="There are many other exciting products you may like as well. Let's see what's there !";
        $messenger_content['checkout'] = array("checkout_text"=>$msg_checkout_text,"checkout_text_next"=>$msg_reminder_text_checkout_next,"checkout_btn_next"=>strtoupper($msg_checkout_btn_website));
        $insert_data2['messenger_content'] = json_encode($messenger_content);

        if($this->session->userdata('user_type') == 'Admin' || in_array(264,$this->module_access))
        {
          foreach ($sms_reminder_time as $key => $value) 
          {
            $i=$key;
            $j=$i+1;
            if($value!="")
            {           
              $temp_sms_reminder_text_checkout = isset($sms_reminder_text_checkout[$i]) ? $sms_reminder_text_checkout[$i] : "";
              $sms_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$temp_sms_reminder_text_checkout);
              $anything_found = true;
            }
            
          }
          $sms_content['checkout'] = array("checkout_text"=>$sms_reminder_text_checkout_next);
          if(isset($sms_api_id) && $sms_api_id!="")
          {
            $insert_data2['sms_api_id'] = $sms_api_id;
            $insert_data2['sms_content'] = json_encode($sms_content);
          }
        }

        if($this->session->userdata('user_type') == 'Admin' || in_array(263,$this->module_access))
        {
          foreach ($email_reminder_time as $key => $value) 
          {
            $i=$key;  
            $j=$i+1; 
            if($value!="")
            {           
              $tmp_email_reminder_text_checkout = isset($email_reminder_text_checkout[$i]) ? $email_reminder_text_checkout[$i] : "";
              $email_content['reminder'][$j] = array('hour'=>$value,"reminder_text"=>$tmp_email_reminder_text_checkout);
              $anything_found = true;
            }
            
          }
          $email_content['checkout'] = array("checkout_text"=>$email_reminder_text_checkout_next);
          if(isset($email_api_id) && $email_api_id!="")
          {
            if($email_subject=="") $email_subject = "Abandoned Cart";
            $exp = explode('-', $email_api_id);
            $insert_data2['configure_email_table'] = isset($exp[0]) ? $exp[0] : '';
            $insert_data2['email_api_id'] = isset($exp[1]) ? $exp[1] : 0;
            $insert_data2['email_content'] = json_encode($email_content);
            $insert_data2['email_subject'] = $email_subject;
          }
        }
        
        if(!$anything_found)
        {
          echo json_encode(array('status'=>'0','message'=>$this->lang->line('You have not set any reminder.')));
          exit();
        }  
      
        $this->db->trans_start(); 

        if(!isset($label_ids)) $label_ids=array();
       
        $insert_data = array(
          "btn_size"=>$btn_size,
          "skin"=>$skin,
          "center_align"=>"false",
          "label_ids"=>implode(',',$label_ids),
          "language"=>$language,
          "button_click_success_message"=>$button_click_success_message
        );
       
        $this->basic->update_data("messenger_bot_engagement_checkbox",array("id"=>$engagement_table_id,"user_id"=>$this->user_id),$insert_data);
        $insert_data2['engagement_table_id'] = $engagement_table_id;
        $this->basic->update_data("woocommerce_drip_campaign",array("engagement_table_id"=>$engagement_table_id,"user_id"=>$this->user_id),$insert_data2);
        $this->db->trans_complete();

        // $download_url=base_url('woocommerce_abandoned_cart/download_woocommerce_plugin/'.$insert_id);

        if($this->db->trans_status() === false)
        {
             echo json_encode(array('status'=>'0','message'=>"".$this->lang->line('Something went wrong, please try again.')));
             exit();
        }
        else
        {
            echo json_encode(array('status'=>'1','message'=>$this->lang->line('Plugin has been updated successfully.'),'id'=>$engagement_table_id));
            exit();
        } 
    }

  


    public function campaign_sent_status_data()
    { 
        $this->ajax_check();

        $data_days = 30; 
        $from_date = $this->session->userdata("woocommerce_from_date");
        $to_date = $this->session->userdata("woocommerce_to_date");
        if($to_date=='') $to_date = date("Y-m-d");
        if($from_date=='') $from_date = date("Y-m-d",strtotime("$to_date - ".$data_days." days"));

        $search_value = $_POST['search']['value'];
        $page_id = $this->input->post("page_id");

        $drip_data = $this->basic->get_data("woocommerce_drip_campaign",array("where"=>array("engagement_table_id"=>$this->session->userdata("woocommerce_selected_plugin"))),"id");
        $woocommerce_drip_campaign_id = isset($drip_data[0]['id']) ? $drip_data[0]['id'] : 0;

        $display_columns = 
        array(
          "#",
          "CHECKBOX",
          'first_name',
          'last_name',
          'email',
          'subscribe_id',
          'last_completed_hour',
          'sent_at',
          'response',
        );
        $search_columns = array('first_name','last_name','email','subscribe_id');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 7;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'sent_at';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_custom="user_id = ".$this->user_id." AND woocommerce_drip_campaign_id = ".$woocommerce_drip_campaign_id." AND sent_at >= '".$from_date."' AND sent_at <= '".$to_date."'";      

        if ($search_value != '') 
        {
            foreach ($search_columns as $key => $value) 
            $temp[] = $value." LIKE "."'%$search_value%'";
            $imp = implode(" OR ", $temp);
            $where_custom .=" AND (".$imp.") ";
        }
     
        $this->db->where($where_custom);
        
        $table="woocommerce_drip_campaign_report";
        $info=$this->basic->get_data($table,$where='',$select='',$join='',$limit,$start,$order_by,$group_by='');
        // echo $this->db->last_query();

        $this->db->where($where_custom);
        $total_rows_array=$this->basic->count_row($table,$where='',$count=$table.".id",$join,$group_by='');

        $total_result=$total_rows_array[0]['total_rows'];

        foreach($info as $key => $value) 
        {
            if($info[$key]['is_sent']=='1' && $info[$key]['sent_at'] != "0000-00-00 00:00:00")
            $sent_time = date("M j, y H:i",strtotime($info[$key]['sent_at']));
            else $sent_time = '<span class="text-muted"><i class="fas fa-exclamation-circle"></i> '.$this->lang->line("Not Sent")."<span>";
            $info[$key]['sent_at'] =  $sent_time;
            
            $last_updated_at = date("M j, y H:i",strtotime($info[$key]['last_updated_at']));
            $info[$key]['last_updated_at'] =  $last_updated_at;

            $info[$key]['response'] =  "<a class='btn btn-sm btn-outline-primary woo_error_log' href='' data-id='".$info[$key]['id']."'><i class='fas fa-plug'></i> ".$this->lang->line('Response')."</a>";
        }
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");
        echo json_encode($data);
    }

    public function reminder_reponse()
    {
        $this->ajax_check();
        $id = $this->input->post('id',true);
        $getdata = $this->basic->get_data("woocommerce_drip_campaign_report",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)),"sent_response");
        $response = isset($getdata[0]['sent_response']) ? $getdata[0]['sent_response'] : '';

        if(is_array(json_decode($response,true))) 
        {
            echo "<div class='list-group'>";
            $response = json_decode($response,true);
            foreach ($response as $key => $value) 
            {
              if($key=="messenger")
              {
                foreach ($value as $key2 => $value2) 
                {                  

                  $tmp_heading = strtoupper($key)." : ".$key2;
                  $tmp_status = (isset($value2['status']) && $value2['status']=='1') ? '<small class="text-success">'."SUCCESS".'</small>' : '<small class="text-danger">'."ERROR".'</small>';
                  $tmp_response = isset($value2['response']) ? $value2['response'] : "";
                  echo '
                    <a  class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1" style="font-size: 1rem;">'.$tmp_heading.'</h5>
                      '.$tmp_status.'
                    </div>
                    <p class="mb-1 text-left" style="font-size: 12px;">'.$tmp_response.'</p>
                  </a>';
                }
              }
              else
              {
                  $tmp_heading = strtoupper($key);
                  $tmp_status = (isset($value['status']) && $value['status']=='1') ? '<small class="text-success">'."SUCCESS".'</small>' : '<small class="text-danger">'."ERROR".'</small>';
                  $tmp_response = isset($value['response']) ? $value['response'] : "";
                  echo '
                    <a  class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1" style="font-size: 1rem;">'.$tmp_heading.'</h5>
                      '.$tmp_status.'
                    </div>
                    <p class="mb-1 text-left" style="font-size: 12px;">'.$tmp_response.'</p>
                  </a>';
              }
            }
            echo "</div>";
        }
       
 

    }


    public function get_template_label_dropdown()
    {
        if(!$_POST) exit();
        $page_id=$this->input->post('page_id');// database id

        $label_list=$this->get_page_label($page_id);

        $dropdown=array();
        $js='<script>
              $("document").ready(function()  {
                $("#label_ids").select2();
              });


            </script>';
        $str='';
        foreach ($label_list as  $key=>$value)
        {            
            $str.=  "<option value='{$key}'>".$value."</option>";
        }
        echo json_encode(array('label_option'=>$str,"script"=>$js));
    }

    public function get_template_label_dropdown_edit()
    {
        if(!$_POST) exit();
        $page_id=$this->input->post('page_id');// database id
        $table_name="messenger_bot_engagement_checkbox";
        $id=$this->input->post('id');

        $xdata=$this->basic->get_data($table_name,array("where"=>array("id"=>$id)));
        $xlabel_ids=isset($xdata[0]["label_ids"])?$xdata[0]["label_ids"]:"";
        $xlabel_ids=explode(',', $xlabel_ids);
        $label_list=$this->get_page_label($page_id);

        $dropdown=array();
        $js='<script>
              $("document").ready(function()  {
                $("#label_ids").select2();
                $("#template_id").select2();
              });


            </script>';
        $str='';
        foreach ($label_list as  $key=>$value)
        {            
            if(in_array($key, $xlabel_ids)) $selected="selected";
            else $selected="";
            $str.=  "<option value='{$key}' {$selected}>".$value."</option>";
        }
       

        echo json_encode(array('label_option'=>$str,"script"=>$js));
    }

    private function get_page_label($page_id=0)
    {
        if($page_id==0) return array();  

        if(!$this->db->table_exists('messenger_bot_broadcast_contact_group')) return array();

        $label_data=$this->basic->get_data("messenger_bot_broadcast_contact_group",array("where"=>array("page_id"=>$page_id,"unsubscribe"=>"0","invisible"=>"0")),'','','',$start=NULL,$order_by="group_name ASC");
        $push_label=array();
        foreach ($label_data as $key => $value) 
        {    
            $push_label[$value['id']]=$value['group_name'].' ['.$value['label_id'].']';
        }
        return $push_label;
    }


    private function get_user_page()
    {
        $facebook_rx_fb_user_info = $this->session->userdata('facebook_rx_fb_user_info');

        $page_data=$this->basic->get_data("facebook_rx_fb_page_info",array("where"=>array("facebook_rx_fb_user_info_id"=>$facebook_rx_fb_user_info,"bot_enabled"=>"1")),'','','',$start=NULL,$order_by="page_name ASC");
        $push_page=array();
        foreach ($page_data as $key => $value) 
        {
            $push_page[$value['id']]=$value['page_name'];
        }
        return $push_page;
    }


    private function get_page_template($page_id=0)
    {
        if($page_id==0) return array();  

        $postback_data=$this->basic->get_data("messenger_bot_postback",array("where"=>array("page_id"=>$page_id,"is_template"=>"1","template_for"=>"reply_message")),'','','',$start=NULL,$order_by="template_name ASC");
        $push_postback=array();
        foreach ($postback_data as $key => $value) 
        {
            $push_postback[$value['id']]=$value['template_name'].' ['.$value['postback_id'].']';
        }
        return $push_postback;
    }


    public function zip_error($param=0)
    {
        if($param==1)
        echo "<h2 class='text-align:center;color:red;border:1px solid red;padding:20px;margin-top:30px;'>".$this->lang->line("WooCommerce Abandoned Cart Recover plugin is not found.");
        else echo "<h2 class='text-align:center;color:red;border:1px solid red;padding:20px;margin-top:30px;'>".$this->lang->line("WooCommerce Abandoned Cart Recover plugin can not be generated beacuse PHP ZipArchive class is not installed.");
    }

    private function get_sms_api()
    {
        $where = array("where" => array('user_id'=>$this->user_id,'status'=>'1'));
        $sms_api_config=$this->basic->get_data('sms_api_config', $where, $select='', $join='', $limit='', $start='', $order_by='phone_number ASC', $group_by='', $num_rows=0);
        $sms_api_config_option=array(''=>$this->lang->line("Select Sender"));
        foreach ($sms_api_config as $info) 
        {
            $id=$info['id'];

            if($info['phone_number'] !="") $sms_api_config_option[$id]=$info['gateway_name'].": ".$info['phone_number'];
            else $sms_api_config_option[$id]=$info['gateway_name'];
        }
        return $sms_api_config_option;
    }

    private function get_email_api()
    {
      
      if(!$this->basic->is_exist("modules",array("id"=>263))) return array();

      /***get smtp  option***/
      $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
      $smtp_info=$this->basic->get_data('email_smtp_config', $where, $select='', $join='', $limit='', $start='', $order_by='email_address ASC', $group_by='', $num_rows=0);
      
      $smtp_option=array(''=>$this->lang->line("Select Sender"));
      foreach ($smtp_info as $info) {
          $id="email_smtp_config-".$info['id'];
          $smtp_option[$id]="SMTP: ".$info['email_address'];
      }
      
      /***get mandrill option***/
      $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
      $smtp_info=$this->basic->get_data('email_mandrill_config', $where, $select='', $join='', $limit='', $start='', $order_by='email_address ASC', $group_by='', $num_rows=0);
      
      foreach ($smtp_info as $info) {
          $id="email_mandrill_config-".$info['id'];
          $smtp_option[$id]="Mandrill: ".$info['email_address'];
      }

      /***get sendgrid option***/
      $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
      $smtp_info=$this->basic->get_data('email_sendgrid_config', $where, $select='', $join='', $limit='', $start='', $order_by='email_address ASC', $group_by='', $num_rows=0);
      
      foreach ($smtp_info as $info) {
          $id="email_sendgrid_config-".$info['id'];
          $smtp_option[$id]="SendGrid: ".$info['email_address'];
      }

      /***get mailgun option***/
      $where=array("where"=>array('user_id'=>$this->user_id,'status'=>'1'));
      $smtp_info=$this->basic->get_data('email_mailgun_config', $where, $select='', $join='', $limit='', $start='', $order_by='email_address ASC', $group_by='', $num_rows=0);
      
      foreach ($smtp_info as $info) {
          $id="email_mailgun_config-".$info['id'];
          $smtp_option[$id]="Mailgun: ".$info['email_address'];
      }
      return $smtp_option;
    }

    private function get_reminder_hour()
    {
      return array(
          "" => "--".$this->lang->line("Do not send")."--",
          "1"=>$this->lang->line("After 1 hour"),
          "2"=>$this->lang->line("After 2 hours"),
          "3"=>$this->lang->line("After 3 hours"),
          "4"=>$this->lang->line("After 4 hours"),
          "5"=>$this->lang->line("After 5 hours"),
          "6"=>$this->lang->line("After 6 hours"),
          "7"=>$this->lang->line("After 7 hours"),
          "8"=>$this->lang->line("After 8 hours"),
          "9"=>$this->lang->line("After 9 hours"),
          "10"=>$this->lang->line("After 10 hours"),
          "11"=>$this->lang->line("After 11 hours"),
          "12"=>$this->lang->line("After 12 hours"),
          "13"=>$this->lang->line("After 13 hours"),
          "14"=>$this->lang->line("After 14 hours"),
          "15"=>$this->lang->line("After 15 hours"),
          "16"=>$this->lang->line("After 16 hours"),
          "17"=>$this->lang->line("After 17 hours"),
          "18"=>$this->lang->line("After 18 hours"),
          "19"=>$this->lang->line("After 19 hours"),
          "20"=>$this->lang->line("After 20 hours"),
          "21"=>$this->lang->line("After 21 hours"),
          "22"=>$this->lang->line("After 22 hours"),
          "23"=>$this->lang->line("After 23 hours"),
        );
    }
    

    public function activate()
    {
        $this->ajax_check();

        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]
        $purchase_code=$this->input->post('purchase_code');
        $this->addon_credential_check($purchase_code,strtolower($addon_controller_name)); // retuns json status,message if error
                  
        //this addon system support 2-level sidebar entry, to make sidebar entry you must provide 2D array like below
        $sidebar=array(); 
        // mysql raw query needed to run, it is an array, put each query in a seperate index, create table query must should IF NOT EXISTS
        $sql=array
        (
        0 => 
        "CREATE TABLE IF NOT EXISTS `woocommerce_drip_campaign` (
  			  `id` int(11) NOT NULL AUTO_INCREMENT,
  			  `page_id` int(11) NOT NULL,
  			  `user_id` int(11) NOT NULL,
  			  `verification_code` varchar(100) NOT NULL,
  			  `messenger_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  			  `sms_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  			  `sms_api_id` int(11) NOT NULL DEFAULT '0',
  			  `email_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  			  `email_api_id` int(11) NOT NULL DEFAULT '0',
  			  `email_subject` varchar(255) NOT NULL,
  			  `configure_email_table` varchar(255) NOT NULL,
  			  `created_at` datetime NOT NULL,
  			  `last_sent_at` datetime NOT NULL,
  			  `engagement_table_id` int(11) NOT NULL,
  			  PRIMARY KEY (`id`),
  			  UNIQUE KEY `verification_code` (`verification_code`),
  			  KEY `engagement_table_id` (`engagement_table_id`)
  			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
			1=>"
			CREATE TABLE IF NOT EXISTS `woocommerce_drip_campaign_report` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `woocommerce_drip_campaign_id` int(11) NOT NULL,
			  `page_id` int(11) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `subscribe_id` varchar(250) NOT NULL,
			  `first_name` varchar(250) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `last_name` varchar(250) NOT NULL,
			  `last_completed_hour` int(11) NOT NULL,
			  `is_sent` enum('0','1') NOT NULL DEFAULT '1',
			  `is_opened` enum('0','1') NOT NULL DEFAULT '0',
			  `is_delivered` enum('0','1') NOT NULL DEFAULT '0',
			  `sent_at` datetime NOT NULL,
			  `delivered_at` datetime NOT NULL,
			  `opened_at` datetime NOT NULL,
			  `sent_response` text NOT NULL,
			  `delivered_response` tinytext NOT NULL,
			  `last_updated_at` datetime NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `page_id` (`page_id`,`user_id`),
			  KEY `woocommerce_drip_campaign_id` (`woocommerce_drip_campaign_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
			2=>"
			CREATE TABLE IF NOT EXISTS `woocommerce_drip_campaign_webhook_call` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `page_table_id` int(11) NOT NULL,
			  `wc_user_id` varchar(50) NOT NULL,
			  `wc_session_unique_id` varchar(50) NOT NULL,
			  `woocommerce_drip_campaign_id` int(11) NOT NULL,
			  `cart_url` varchar(255) NOT NULL,
			  `checkout_url` varchar(255) NOT NULL,
			  `wc_first_name` varchar(255) NOT NULL,
			  `wc_first_name_bill` varchar(255) NOT NULL,
			  `wc_last_name` varchar(255) NOT NULL,
			  `wc_last_name_bill` varchar(255) NOT NULL,
			  `wc_email` varchar(255) NOT NULL,
			  `wc_email_bill` varchar(255) NOT NULL,
			  `wc_mobile` varchar(100) NOT NULL,
			  `wc_mobile_bill` varchar(100) NOT NULL,
			  `check_box_unique_id` varchar(50) NOT NULL,
			  `action_type` enum('add','remove','checkout') NOT NULL DEFAULT 'add',
			  `checkout_at` datetime NOT NULL,
			  `checkout_amount` float NOT NULL,
			  `checkout_currency` varchar(20) NOT NULL,
			  `checkout_country` varchar(50) NOT NULL,
			  `cart_info` longtext NOT NULL,
			  `confirmation_response` text NOT NULL,
			  `checkout_info` longtext NOT NULL,
			  `last_completed_hour` int(11) NOT NULL,
			  `is_totally_completed` enum('0','1') NOT NULL DEFAULT '0',
			  `last_sent_at` datetime NOT NULL,
			  `initial_date` datetime NOT NULL,
			  `last_processing_started_at` datetime NOT NULL,
			  `processing_status` enum('0','1') NOT NULL DEFAULT '0',
			  `updated_at` datetime NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `wc_session_unique_id` (`wc_session_unique_id`),
			  KEY `user_id` (`user_id`,`page_table_id`),
			  KEY `woocommerce_drip_campaign_id` (`woocommerce_drip_campaign_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );

        //send blank array if you does not need sidebar entry,send a blank array if your addon does not need any sql to run
        $this->register_addon($addon_controller_name,$sidebar,$sql,$purchase_code);
    }


    public function deactivate()
    {        
        $this->ajax_check();

        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]
        // only deletes add_ons,modules and menu, menu_child1 table entires and put install.txt back, it does not delete any files or custom sql
        $this->unregister_addon($addon_controller_name);         
    }

    public function delete()
    {        
        $this->ajax_check();

        $addon_controller_name=ucfirst($this->router->fetch_class()); // here addon_controller_name name is Comment [origianl file is Comment.php, put except .php]

        // mysql raw query needed to run, it is an array, put each query in a seperate index, drop table/column query should have IF EXISTS
        $sql=array
        (
          0 => "DELETE FROM messenger_bot_engagement_checkbox WHERE for_woocommerce='1';",
          1 => "DELETE FROM messenger_bot_engagement_checkbox_reply WHERE for_woocommerce='1';",
          2 => "DROP TABLE IF EXISTS `woocommerce_drip_campaign`;",
          3 => "DROP TABLE IF EXISTS `woocommerce_drip_campaign_report`;",
          4 => "DROP TABLE IF EXISTS `woocommerce_drip_campaign_webhook_call`;"
        );  
        
        // deletes add_ons,modules and menu, menu_child1 table ,custom sql as well as module folder, no need to send sql or send blank array if you does not need any sql to run on delete
        $this->delete_addon($addon_controller_name,$sql);         
    }



}