<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');


class SendMail 
{
	public $CI;
	public function __contruct($params =array())
	{
		
		$this->CI->config->item('base_url');
		$this->CI->load->helper('url');
		$this->CI->load->library('email');
		$this->CI->load->database();
		$this->CI =& get_instance();
	}    

	public function Send($Data)
	{
		$CI =& get_instance();
		$config['protocol']    = 'smtp';
		$config['smtp_host']    = 'ssl://smtp.gmail.com';
		$config['smtp_port']    = '465';
		$config['smtp_timeout'] = '17';
		$config['smtp_user']    = 'tbsitplraja@gmail.com';
		$config['smtp_pass']    = 'ljcsnlzakvlmolfj';
		$config['charset']    = 'utf-8';
		$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      
		$CI->email->initialize($config);
		$CI->email->from('gcw@tbsind.com', 'Assets Tracking');
		$CI->email->to($Data['mailoutbox_to']);
		$CI->email->subject($Data['mailoutbox_subject']);
		$CI->email->message($Data['mailoutbox_body']);
		//$CI->email->send();
		if($CI->email->send())
		{
			$status =1;
		}
		else
		{
			$status =0;
		}
		$CI->email->print_debugger();
		return $status;
	}    
}