<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function dd($data){
	echo"<pre>";
	print_r($data);
	echo"</pre>";
	exit();
}
function cNumberFormat($number){
	return 'RS. '.number_format($number,2);
}


if ( ! function_exists('checkEmptyHelp'))
{
 
	 function checkEmptyHelp($data)
		{
			if($data =='' || $data ==null)
			{
				return 'N.A';
			}else{
				return $data;
			}
		}
}


if ( ! function_exists('selected'))
{
	function selected($data1,&$data2)
		{
			if($data1 == $data2)
			{
				return "selected";
				
			}else{
				return "";
			}
		}
}

if ( ! function_exists('checked'))
{
 
	 function checked($data1,$data2)
		{
			if($data1 == $data2)
			{
				return "checked";
				
			}else{
				return "";
			}
		}
}



if ( ! function_exists('dbDateConvert'))
{
    function dbDateConvert($date,$params='')
		{
			if($date=='' && $params!='')
			{
				return $params;
			}
			else if($date=='')
			{
			   return "";
			}
			else
			{
			   	return date("Y-m-d",strtotime($date));
			}
		}  
}


if ( ! function_exists('userDateConvert'))
{
    function userDateConvert($date,$params='')
		{
			if(($date=='' && $params!='')||($date=='0000-00-00' && $params!=''))
			{
				return  $params;
			}else if($date==''){
			   return "";
			}else{
			   	return date("d-m-Y",strtotime($date));
			}
		}  
}



/*paralimit added by asp */


		function paraLimit($text, $limit=0)
		{
				
				   if (strlen($text) > $limit) {
					  
					  $text = substr($text, 0, $limit) . '...';
				  }
				 return $text;
		}
		
		
	
		function checkImageExist($path,$filename)
		{
				$file_path  = FCPATH.$path;
				
					if( file_exists( $file_path.$filename ))  
					{
						return base_url($path.$filename);
					}
					else
					{
						return  base_url('/img/image_placeholder.png');
					}
		} 

		function fullName($f="",$m="",$l="")
		{
			if($m!="")
			{
				return $f." ".$m." ".$l;
			}else{
				return $f." ".$l;
			}
		}

