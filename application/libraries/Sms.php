<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Sms 
{
    public $CI;
    public function __contruct($params =array())
    {
       
        $this->CI->config->item('base_url');
        $this->CI->load->helper('url');
        $this->CI->load->helper('form');
        $this->CI->load->helper('html');
        $this->CI->load->database();
        $this->CI =& get_instance();
        
    }    

    public function Send($Data='')
    {
        $Textmsg = $Data['message'];
        $Text = urlencode($Textmsg);
        $mobile = $Data['mobile_no'];
        $sendeid = "ECOM";
        $password = "password";
        $baseurl = "http://www.smsjust.com/sms/user/urlsms.php?";
        $url = $baseurl."username=gandhicard&pass=$password&senderid=$sendeid&dest_mobileno=$mobile&message=$Text&response=Y"; 
        // $ret = file($url);
    }    
}
	
  	
?>