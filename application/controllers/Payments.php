<?php
/* all controller functionality developed by Prashant */
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

    function __construct()
    {
        parent::__construct();      
        $this->load->database();
        $this->load->library(array('session','form_validation'));
        $this->load->model(array('Crud_model','Payments_model'));   
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library(array('Urlcheck'));
    	$this->Urlcheck = new Urlcheck();  
    	 $this->load->library(array('SendMail'));
        $this->SendMail = new SendMail();
    }

    public function index()
    {   
    	$delete= '';
	    $actstatus= '';
	    $add = '';
	    $act_add_existing_stock = '';
	    $act_log_details = '';
	    $act_transfer = '';
	    $edit = '';
	    $view = '';
	    $export_button = '';
    	if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    	{ 
	    	foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
	        { 
	          foreach($row as $menu)
	          { 
	              if($menu->value=='Payments')
	              { 
	                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
	                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
	                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
	                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
	                if(!empty($menu->act_export)){ $exportbutton='1'; }else{ $exportbutton='0'; }
	              }
	          }
	        }

          $controller_name = $this->uri->segment(1);
      	  $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Payment</li>
                    </ul>";

          $create =anchor(site_url($controller_name.'/makePayment'),'<span class="fa fa-plus" title="Create"></span>');
          
          $export =anchor(site_url($controller_name.'/export'),'<span title="Export" title="Export" class="fa fa-file-export "></span>');

        $vendors = $this->Crud_model->GetData('vendors','','','','shop_name'); 
        $data = array('heading'=>'Payments','createAction'=>site_url('Payments/makePayment'),'page_create' =>$create,'vendors' =>$vendors,'export' =>$export,'breadcrumbs'=>$breadcrumbs, 'addPermission'=>$add,'exportPermission'=>$exportbutton);
        $this->load->view('payments/list',$data);
        }
    else
    {
      redirect('Dashboard');
    } 
    }
   
    public function ajax_manage_page()
    {    
    	/* ----- Condition for superadmin Start----- */
       /* if($_SESSION[SESSION_NAME]['is_superadmin']!='1')
        {
            $controller_name = $this->uri->segment(1);
            $submenu = $this->Crud_model->GetData("ra_application_menus",'',"controller_name='".$this->uri->segment(1)."'",'','','','single');
            $menuId=$submenu->id;
            $getPermissions = $this->Urlcheck->getFunctions($menuId);
        }
        else
        {
            $controller_name = $this->uri->segment(1);
            $getPermissions = '';
        }*/
        /* ----- Condition for superadmin End----- */

		$vendor = $_POST['SearchData1'];
		$type = $_POST['SearchData2'];
		$from = $_POST['SearchData3'];
		$to = $_POST['SearchData4'];

		$con ="vt.id<>'0'";
		if($vendor!='')
		{
			$con .= " and v.id='".$vendor."'";
		}
		if($type!='')
		{
			$con .=" and vt.status= '".$type."'";
		}
		if($from!='')
		{
			$con .=" and vt.payment_date >= '".date('Y-m-d',strtotime($from))."'";
		}
		if($to!='')
		{
			$con .=" and vt.payment_date <= '".date('Y-m-d',strtotime($to))."'";
		}
		
        $AllData = $this->Payments_model->get_datatables($con);
        
        $no = 0;        
        $data = array();        
        foreach ($AllData as $rowData) 
        {
        	if(!empty($rowData->order_number))
        	{
        		$poNumber = $rowData->order_number;
        	} else {
        		$poNumber = '-';
        	}
            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = ucwords($rowData->shop_name); 
            $nestedData[] = $poNumber; 
            $nestedData[] = ucwords($rowData->description); 
            $nestedData[] = date('d-m-Y',strtotime($rowData->payment_date)); 
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($rowData->inward,2); 
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($rowData->balance,2); 
            $nestedData[] = ucwords($rowData->status); 
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Payments_model->count_all($con),
                    "recordsFiltered" => $this->Payments_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function makePayment()
    {	
    	 $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li> <a href='".site_url('Payments')."'>Manage Payment</a></li>
                    <li class='active'>Make Payment</li>
                    </ul>";
        $vendor = $this->Crud_model->GetData('vendors');
        $types = $this->Crud_model->GetData('payment_types','',"status='Active'");
        $data = array('heading'=>'Make Payment','vendor'=>$vendor,'types'=>$types,'breadcrumbs'=>$breadcrumbs,);
        $this->load->view('payments/payment_form',$data);
    }

    public function GetVendorData()
    {
        $vendor = $this->Payments_model->GetVendorDetail("vendor_id='".$_POST['id']."'"); 
        if(!empty($vendor)){
            $response['shop_name']=$vendor->shop_name;
            $response['balance']=$vendor->balance;
            $response['bank_account_name']=$vendor->bank_account_name;
            $response['bank_account_no']=$vendor->bank_account_no;
            $response['bank_name']=$vendor->bank_name;
            $response['bank_ifsc_code']=$vendor->bank_ifsc_code;
        } else {
            $response['shop_name']='';
            $response['balance']='0';
            $response['bank_account_no']='';
            $response['bank_account_name']='';
            $response['bank_name']='';
            $response['bank_ifsc_code']='';
        }
        echo json_encode($response); exit; 
    }

    public function make_confirm_payment()
    {   
		$id = 0;
		$this->validation_rules($id);
		 if ($this->form_validation->run() == FALSE) {
            $this->makePayment();
        } 
        else
		{		
			$financialYear = $this->Crud_model->GetData('financial_years','',"status='Active'",'','','','single');
			$data = array(
				'financial_year_id'=>$financialYear->id,
				'vendor_id'=>$_POST['vendor_id'],
				'payment_date'=>date('Y-m-d'),
				'payment_type'=>$_POST['payment_type'],
				'amount'=>$_POST['amount'],
				'paid_by'=>$_SESSION[SESSION_NAME]['id'],
				'status'=>'Paid',
				);
			if($_POST['payment_type']=='Cheque')
			{
				$data['cheque_no'] = $_POST['cheque_no'];
				$data['bank_name'] = $_POST['bank_name'];
				$data['cheque_date'] = $_POST['cheque_date'];           
			}
			else if($_POST['payment_type']=='NEFT')
			{
				$data['account_name'] = $_POST['account_name'];
				$data['bank_name'] = $_POST['neft_bank_name'];
				$data['bank_account_no'] = $_POST['bank_account_no'];
				$data['bank_ifsc_code'] = $_POST['bank_ifsc_code'];
			}
			$this->Crud_model->SaveData('vendor_payments',$data);
			$LastId = $this->db->insert_id();

			$ven = $this->Crud_model->GetData('vendor_transactions','',"vendor_id='".$_POST['vendor_id']."'",'','id desc','1','single');
			$balance = $ven->balance - $_POST['amount'];
			$transData = array(
				'financial_year_id'=>$financialYear->id,
				'payment_id'=>$LastId,
				'vendor_id'=>$_POST['vendor_id'],
				'payment_date'=>date('Y-m-d'),
				'description'=>'By '.$_POST['payment_type'],
				'inward'=>$_POST['amount'],
				'balance'=>$balance,
				'status'=>'Payment',
				);
			$this->Crud_model->SaveData('vendor_transactions',$transData);	

			$vendor = $this->Crud_model->GetData('vendors','',"id='".$_POST['vendor_id']."'",'','','','single');
			$mail_body = $this->Crud_model->GetData("mst_mail_body",'',"type='vendor_payment'",'','','','single');
	        $mail_body->mail_body=str_replace("{vendor_name}",ucfirst($vendor->shop_name),$mail_body->mail_body);
	        $mail_body->mail_body=str_replace("{payment_type}",ucfirst($_POST['payment_type']),$mail_body->mail_body);
	        $mail_body->mail_body=str_replace("{payable_amount}",number_format($_POST['amount'],2),$mail_body->mail_body);
	        $mail_body->mail_body=str_replace("{payment_date}",date('d-m-Y'),$mail_body->mail_body);
	        $mail_body->mail_body=str_replace("{balance}",number_format($balance,2),$mail_body->mail_body);
			
			$subject=$mail_body->mail_subject;
            $body=$mail_body->mail_body;
            $MailData = array('mailoutbox_to'=>$vendor->email,'mailoutbox_subject'=>$subject,'mailoutbox_body'=>$body,'mail_type'=>$mail_body->type);
            //print_r($MailData);exit;
            //$Send=$this->SendMail->Send($MailData);
			redirect(site_url('Payments/index'));
		}
    }

	public function export()
	{
		$to = $this->input->post('to-date');
		$from = $this->input->post('from-date');
		$vendor = $this->input->post('vendor_id');
		$type = $this->input->post('type');

		$con ="vt.id<>'0'";
		if($vendor!='')
		{
			$con .= " and v.id='".$vendor."'";
		}
		if($type!='')
		{
			$con .=" and vt.status= '".$type."'";
		}
		if($from!='')
		{
			$con .=" and vt.payment_date >= '".date('Y-m-d',strtotime($from))."'";
		}
		if($to!='')
		{
			$con .=" and vt.payment_date <= '".date('Y-m-d',strtotime($to))."'";
		}

		$results = $this->Payments_model->ExportCSV($con);		
		
		$FileTitle='Vendor Payment Report';
		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Report Sheet');
		
		$this->excel->getActiveSheet()->setCellValue('A1', 'Vendor Payments');
		$this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
		$this->excel->getActiveSheet()->setCellValue('B3', 'Vendor');			
		$this->excel->getActiveSheet()->setCellValue('C3', 'PO Number');
		$this->excel->getActiveSheet()->setCellValue('D3', 'Description');
		$this->excel->getActiveSheet()->setCellValue('E3', 'Date');
		$this->excel->getActiveSheet()->setCellValue('F3', 'Amount (Rs.)');;
		$this->excel->getActiveSheet()->setCellValue('G3', 'Balance (Rs.)');
		$this->excel->getActiveSheet()->setCellValue('H3', 'Type');
		
		$a='4';	$sr = '1';		
		foreach ($results as $result) 
		{
			if(!empty($result->order_number))
        	{
        		$order_number = $result->order_number;
        	} else {
        		$order_number = ' - ';
        	}	
			$this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
			$this->excel->getActiveSheet()->setCellValue('B'.$a, $result->shop_name);
			$this->excel->getActiveSheet()->setCellValue('C'.$a, $order_number);
			$this->excel->getActiveSheet()->setCellValue('D'.$a, $result->description);
			$this->excel->getActiveSheet()->setCellValue('E'.$a, date('d-m-Y',strtotime($result->payment_date)));
			$this->excel->getActiveSheet()->setCellValue('F'.$a, $result->inward);
			$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->balance);
			$this->excel->getActiveSheet()->setCellValue('H'.$a, $result->status);
			$sr++; $a++;
		}
		
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(12);
		$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setSize(12);

		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
		
		$this->excel->getActiveSheet()->mergeCells('A1:H1');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$filename=''.$FileTitle.'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		ob_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		$objWriter->save('php://output');	
	}

	public function validation_rules($id) 
	{	
				
				$this->form_validation->set_rules('vendor_id', 'vendor name', 'trim|required',
					array(
							'required'      => 'Please enter %s',
							
						 ));
				$this->form_validation->set_rules('payment_type', 'payment type', 'trim|required',
					array(
							'required'      => 'Please enter %s',
							
						 ));
				$this->form_validation->set_rules('amount', 'amount', 'trim|required|is_numeric',
					array(
							'required'      => 'Please enter %s',
							'is_numeric'=>'Please enter only numbers'
						 ));
				
		$this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');
	}
}

?>