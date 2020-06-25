<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/mpdf/vendor/autoload.php');
class Sales_return extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('sales_return_model');  
  $this->image_lib->clear();   
  $this->load->helper(array('form','url','html'));
  $this->load->database();
  }
  public function search()
  {
    if($this->input->post())
    {
      $date = $this->input->post('daterange'); 
	  $date = str_replace("-","_",$date);
	  $date = str_replace("/","-",$date);
	  $date = str_replace(" ","",$date);
      
      //$newDate = date("Y-m-d", strtotime($date));
	  $strUrl = site_url('sales_return/ajax_manage_page/'.$date);
      $this->common_view($strUrl,$date); 
	  
    }
    else
    {
       return redirect('sales_return');
    }
       
  }
  public function index()
  {   
    
    $this->common_view(site_url('sales_return/ajax_manage_page'));
  }
  
  public function common_view($action,$date=0)
  {   
    // print_r($_SESSION[SESSION_NAME]);exit;
    $import = '';
    $add = '';
  	if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  
	    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
	    { 
	      foreach($row as $menu)
	      { 
	          if($menu->value=='sales_return')
	          { 
	            
              if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              if(!empty($menu->act_export)){ $exportbutton='1'; }else{ $exportbutton='0'; }
              if(!empty($menu->act_import)){ $import='1'; }else{ $import='0'; }
	          }
	      }
	    }

	    $breadcrumbs = "<ul class='breadcrumb'>
	                <li>
	                    <i class='ace-icon fa fa-home home-icon'></i>
	                    <a href='".site_url('Dashboard')."'>Dashboard</a>
	                </li>
	                <li class='active'>Manage Sales Return</li>
	                </ul>";

	    $importaction ='<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
      $export ='<a href="javascript:void(0)" onclick="return clickSubmit()"><span title="Export" class="fa fa-file-excel-o"></span></a>'; 
	    $download =' <a  download="assets.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/assets_demo_excel/assets.xls').'"><span class="fa fa-download"></span></a>'; 

	    $branch_data = $this->Crud_model->GetData('branches',"","is_delete='No' and status='Active'",'','branch_title');
	    $data = array('dateinfo'=>$date,'breadcrumbs' => $breadcrumbs ,'actioncolumn' => '5' ,'ajax_manage_page' => $action,'heading' => 'Manage sales/Return','branch_data'=>$branch_data,'import' =>$import,'importaction' =>$importaction,'download' =>$download,'addPermission'=>$add,'export' =>$export);

	    $this->load->view('sales_return/list',$data);
	}
	else
	{
		redirect('Dashboard');
	}
  }

  public function ajax_manage_page($date = 0)
  {
    $con="p.id<>''";
    if(!empty($_SESSION[SESSION_NAME]['branch_id'])){
      $con.=" and ast.id in (select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";
    }

    $Data = $this->sales_return_model->get_datatables($con,$date);
    //print_r($this->db->last_query());exit;
    $edit = ''; 
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $view = '';

    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='sales_return')
          { 
            
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                if(!empty($menu->act_add_existing_stock)){ $act_add_existing_stock='1'; }else{ $act_add_existing_stock='0'; }
                if(!empty($menu->act_log_details)){ $act_log_details='1'; }else{ $act_log_details='0'; }
                if(!empty($menu->act_transfer)){ $act_transfer='1'; }else{ $act_transfer='0'; }
                if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
          }
      }
    }

    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
        $btn = '';
        
        $btn .='<a href='.site_url("sales_return/view/".$row->id).' title="Details" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-eye"></i></a>';

        $url = base_url().'index.php/sales_return/delete/'.$row->id;
        $btn .='&nbsp;|&nbsp;'.'<a href="'.$url.'" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
        
        if(!empty($avlquantity))
        {
        	$avl_quantity = $avlquantity;
        }
        else
        {
        	$avl_quantity = '-';
        }
        $no++;
        $data[] = array(
          'no' => $no,
          'dn_number' => $row->dn_number,
          'return_date' => date('d-m-Y', strtotime($row->return_date)),
          'sum_amount' => number_format($row->sum_amount,2),
          'r_from' => $row->r_from,
          'btn' => $btn,
        );
    }

    $output = array("data" => $data);
   
    echo json_encode($output);
  }
  
  //===================================summery pdf=========================

  public function listpdf($date = 0)
  {
      $con="p.id<>''";
      if(!empty($_SESSION[SESSION_NAME]['branch_id'])){
      $con.=" and ast.id in (select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";
      }

      $data['rows'] = $this->sales_return_model->get_datatables($con,$date);
      $html = $this->load->view('sales_return/pdf_sales_return_list',$data,true);
      $mpdf = new \Mpdf\Mpdf();
      $mpdf->WriteHTML($html);
      $mpdf->Output();
  }

  //===================================/summery pdf========================

  //===================================export summery pdf=========================

  public function export_dispatch1($date = 0)
  {
      $con="p.id<>''";
      if(!empty($_SESSION[SESSION_NAME]['branch_id'])){
      $con.=" and ast.id in (select asset_id from asset_branch_mappings where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."')";
      }

      $results = $this->sales_return_model->get_datatables($con,$date);
      //$results = $this->sales_return_model->ExportCSV($con);
        
            $FileTitle='sales_return Summary Report';
                
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Report Sheet');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'Sales Return Summary Details ');
            $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
            $this->excel->getActiveSheet()->setCellValue('C3', 'DN No');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Date');
            $this->excel->getActiveSheet()->setCellValue('D3', 'Amount');
            $this->excel->getActiveSheet()->setCellValue('E3', 'Customer');
            
            $a='4'; $sr = 1;   $sum_amount = 0;  
            //print_r($results);exit;
            foreach ($results as $result) 
            {       
                $date = date('d-m-Y', strtotime($result->return_date));
                $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
                $this->excel->getActiveSheet()->setCellValue('C'.$a, $result->dn_number);
                $this->excel->getActiveSheet()->setCellValue('B'.$a, $date);
                $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($result->sum_amount,2));                
                $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->r_from);
                //$this->excel->getActiveSheet()->setCellValue('F'.$a, $result->hsn);
                //$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
                //$this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
                $sr++; $a++;      
                $sum_amount += $result->sum_amount;          
            }
            $this->excel->getActiveSheet()->setCellValue('D'.$a, "Rs. ".number_format($sum_amount,2));
            $this->excel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            ob_clean();
                        
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
  }

  //===================================/summery pdf========================

    public function create()
    {    
      //print_r($_SESSION);exit;
      $breadcrumbs = "<ul class='breadcrumb'>
                  <li>
                      <i class='ace-icon fa fa-home home-icon'></i>
                      <a href='".site_url('Dashboard')."'>Dashboard</a>
                  </li>
                   <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                  <li class='active'>Create sales return</li>
                  </ul>";
	  $where = array('created_by'=>$_SESSION[SESSION_NAME]['id']);
      //$products = $this->Crud_model->GetData("assets","",$where); 
      $this->load->model('Invoice_model');
      $products = $this->Invoice_model->getProduct();
	  
      $users = $this->Crud_model->GetData("employees","","id!='".$_SESSION['ASSETSTRACKING']['id']."' and type='User'");           
      $action =  site_url("sales_return/create_action");    

      $data = array(
        'breadcrumbs' => $breadcrumbs,
        'heading' => 'Create Sales Return',
        'button'=>'Create',                       
        'products' => $products, 
        'users' => $users,
        'action'=>$action, 
      );
      $this->load->view('sales_return/form',$data);
    }
	public function save_next($id)
	{
		$condition = array('id'=>$id);
		$arrsales_return = $this->Crud_model->GetData('sales_return','',$condition,'','','',1);
		$condition2 = array('d.return_id'=>$id);
		//$arrsales_returnData = $this->Crud_model->GetData('sales_return_details','',$condition2);
		$query = $this->db
						->select('a.asset_name as name,d.quantity,d.price,d.gst_percent,d.lf_no,d.id')
						->from('sales_return_details as d')
						->join('assets as a','a.id=d.product_id')
						->where($condition2)
						->get();
		$arrsales_returnData = $query->result();
		
		$breadcrumbs = "<ul class='breadcrumb'>
                  <li>
                      <i class='ace-icon fa fa-home home-icon'></i>
                      <a href='".site_url('Dashboard')."'>Dashboard</a>
                  </li>
                   <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                  <li class='active'>Create Sales Return</li>
                  </ul>";
	  $where = array('created_by'=>$_SESSION[SESSION_NAME]['id']);
      $products = $this->Crud_model->GetData("assets","",$where); 
	  
      $users = $this->Crud_model->GetData("employees","","id!='".$_SESSION['ASSETSTRACKING']['id']."' and type='User'");           
      $action =  site_url("sales_return/create_action");    

      $data = array(
        'breadcrumbs' => $breadcrumbs,
        'heading' => 'Create Sales Return',
        'button'=>'Create',                       
        'products' => $products, 
        'users' => $users,
        'action'=>$action, 
		'dispatch' => $arrsales_return,
		'dispatchData' => $arrsales_returnData
      );
      $this->load->view('sales_return/form',$data);
	}

    public function create_action($finish = null) { 
	   
      if($_POST) {
        $strDate = date('Y-m-d', strtotime($_POST['date']));
       /* $product_id = $_POST['asset_name'];
        $qty = $this->input->post('quantity');
        $this->db->select('*');
        $this->db->where('id',$product_id);
        $query = $this->db->get('assets');
        $product = $query->row();
        if($qty<=$product->quantity){*/
                $dispatchdate = str_replace('/', '-', $_POST['date']);
                //$user_id = $this->Crud_model->GetData("employees","id","name='".$_POST['sent_to']."'","","","","1");
                //print_r(); exit;
                
                $data_array = array(
                  'return_date' => $strDate,
                  'dn_number' => $_POST['dn_no'],
                  'r_from' => $_POST['r_from'],
                  'status' => 'Active',
                  'created' => date('Y-m-d H:i:s'),
                );
                //print_r($data_array);exit;
        		if($did = $this->input->post('sales_return_id')){
        			$condition = array('id'=>$did);
        			$this->Crud_model->SaveData("sales_return",$data_array,$condition);
        			$last_id = $did;
        		}
        		else{
        			$this->Crud_model->SaveData("sales_return",$data_array);
        			$last_id = $this->db->insert_id();
        		}
        		$data = array(
                      'return_id' => $last_id,
                      'product_id' => $_POST['asset_name'],
                      'quantity' => $_POST['quantity'],
                      'price'=>$_POST['product_mrp'],
                      'gst_percent'=>$_POST['gst_percent'],
                      'lf_no'=>$_POST['lf_no'],
                      'created_by'=>$_SESSION[SESSION_NAME]['id'],
                      'status' => 'Active',
                      'created'=>date('Y-m-d H:i:s'),
                    );
        			$condition = array('id'=>$_POST['asset_name']);
        			$arrProduct = $this->Crud_model->GetData('assets','',$condition,'','','',1);
        			if($this->input->post('dis_id')){
        				$arrCondition = array('id'=>$this->input->post('dis_id'));
        				$this->Crud_model->SaveData("sales_return_details",$data,$arrCondition);
        				$quantity = $arrProduct->quantity-$_POST['actual_qty']+$_POST['quantity'];
        				//$total_quantity = $arrProduct->total_quantity+$_POST['actual_qty']-$_POST['quantity'];
        			}
        			else{
        				$this->Crud_model->SaveData("sales_return_details",$data);
        				$quantity = $arrProduct->quantity+$_POST['quantity'];
        				//$total_quantity = $arrProduct->total_quantity-$_POST['quantity'];
        			}
        		  $arrData = array('quantity'=>$quantity);
        		  $this->Crud_model->SaveData('assets',$arrData,$condition);
                if($finish != 'finish') {
                    return redirect('sales_return/save_next/'.$last_id);
                } else {
        		  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">sales Return has been created successfully</span>');
        			redirect('sales_return/index');
        		  
                }

                /*
              } else {
                 $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Please select avilable quntity!</span>');
                if($did = $this->input->post('sales_return_id')){
                    return redirect('sales_return/save_next/'.$did);
                }
                else{
                   return redirect('sales_return/create/');
                }



              }*/
      } else {
        redirect('sales_return/Create');
      }
    }
	public function edit_action($id,$last_id)
	{
		//print_r($_SESSION);exit;
      $breadcrumbs = "<ul class='breadcrumb'>
                  <li>
                      <i class='ace-icon fa fa-home home-icon'></i>
                      <a href='".site_url('Dashboard')."'>Dashboard</a>
                  </li>
                   <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                  <li class='active'>Create sales_return</li>
                  </ul>";
	  $where = array('created_by'=>$_SESSION[SESSION_NAME]['id']);
      $products = $this->Crud_model->GetData("assets","",$where); 
	  
      $users = $this->Crud_model->GetData("employees","","id!='".$_SESSION['ASSETSTRACKING']['id']."' and type='User'");           
      $action =  site_url("sales_return/create_action/edit");    
	  $condition = array('id'=>$id);
		$objDis = $this->Crud_model->GetData('sales_return_details','',$condition,'','','',1);
		$condition2 = array('id'=>$last_id);
		$arrsales_returnData = $this->Crud_model->GetData('sales_return','',$condition2,'','','',1);
		
      $data = array(
        'breadcrumbs' => $breadcrumbs,
        'heading' => 'Create Sales Return',
        'button'=>'Create',                       
        'products' => $products, 
        'users' => $users,
        'action'=>$action,
		'dispatch' => $arrsales_returnData, 
		'dispatchData' => $objDis,
		'view' => 'edit',
      );
      
	  //echo"<pre>";
	 //print_r($data);
	  //exit();
      $this->load->view('sales_return/form',$data);
	}
	public function remove($id,$last_id)
	{
		$condition = array('id'=>$id);
		$objDis = $this->Crud_model->GetData('sales_return_details','',$condition,'','','',1);
		$this->Crud_model->DeleteData('sales_return_details',$condition);
		
		$condition2 = array('id'=>$objDis->product_id);
		  $arrProduct = $this->Crud_model->GetData('assets','',$condition2,'','','',1);
		  $quantity = $arrProduct->quantity - $objDis->quantity;
		  //$total_quantity = $arrProduct->total_quantity + $objDis->quantity;
		  $arrData = array('quantity'=>$quantity);
		  $this->Crud_model->SaveData('assets',$arrData,$condition2);  
		//$this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">sales_return/Return has been removed successfully</span>');
			redirect('sales_return/save_next/'.$last_id); 
	}

    public function getAssetdataAjax($id) {
      /*if(!empty($_SESSION[SESSION_NAME]['branch_id'])){
        $con = "id in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_SESSION[SESSION_NAME]['branch_id']."' and asset_id='".$id."') and id not in(select asset_details_id from assets_maintenance where status = 'Send' and asset_id='".$id."') and status='In_use'";
      } else{*/
        $con="assets.id='".$id."'";
      /*}*/

      $Data = $this->Asset_details_model->get_datatables($con); 
      //print_r($this->db->last_query());exit;
      $data = array();
      $no=0; 

      foreach($Data as $row) {


        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        //$nestedData[] = $row->barcode_number;
        $nestedData[] = $row->sku;                  
        $nestedData[] = $row->product_mrp;                  
        //$nestedData[] = $photo;                  
        $nestedData[] = ucfirst($row->type);      
        $nestedData[] = '';//$btn;       
        $data[] = $nestedData;
        $selected = '';
      }

      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->Asset_details_model->count_all($con),
        "recordsFiltered" => $this->Asset_details_model->count_filtered($con),
        "data" => $data,
      );

      echo json_encode($output);
    }

    public function assetDetails($ast_id,$id,$flag)
    {
      if($ast_id=='' || $id=='' || $flag=='')
      {
        redirect('sales_return/index');
      }
      else
      {
        $heading="Add details";
        $button="Add details";
        if($flag=='update')
        {
          $heading="Update details";
          $button="Update details";
        }

       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>

                    </li>
                     <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                    <li class='active'>".$heading."</li>
                    </ul>";
        $getAssetData = $this->sales_return_model->getAllDetails($ast_id);
        $getassetDetails = $this->Crud_model->GetData('asset_details',"","asset_id='".$ast_id."' and id='".$id."'",'','','','1');
        //print_r($getassetDetails);exit;
        $all_update = 'all_update';
        if($getassetDetails->commanforall == 'No'){ $all_update = 'no_update'; }
        $action=site_url('sales_return/addDetailsaction/'.$ast_id.'/'.$id.'/'.$flag);
        $data = array(
                          'breadcrumbs' => $breadcrumbs,
                          'heading' => $heading,
                          'getAssetData'=>$getAssetData,                                                                                         
                          'getassetDetails'=>$getassetDetails,                                             
                          'ast_id'=>$ast_id,                                             
                          'id'=>$id,                                             
                          'flag'=>$flag,                                             
                          'button'=>$button,                                             
                          'action'=>$action,
                          'all_update' => $all_update,                                            
                          );

           $this->load->view('assets/addstockdetail',$data); 
      }
    }

    public function view($id) {
      if($id =='') {
        redirect('sales_return/index');
      } else {
        $breadcrumbs = "<ul class='breadcrumb'>
          <li>
            <i class='ace-icon fa fa-home home-icon'></i>
            <a href='".site_url('Dashboard')."'>Dashboard</a>
          </li>
          <li class=''> 
            <a href='".site_url('sales_return')."'>Manage Sales Return</a>
          </li>
          <li class='active'>View Sales Return Details</li>
        </ul>";

        $getAssetData = $this->sales_return_model->getAllDetails($id);
        //print_r($getAssetData);exit;
        //$getassetDetails = $this->Crud_model->GetData('asset_details',"","asset_id='".$id."' and status='In_use'",'','','','');

              
        $data = array(
          'breadcrumbs' => $breadcrumbs,
          'heading' => 'View Sales Return',
          'getAssetData'=>$getAssetData,       
          //'getassetDetails'=>$getassetDetails,                                           
          'id'=>$id,
        );
        $this->load->view('sales_return/view',$data); 
      }
    }

    public function update($id)
    {
       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                    <li class='active'>Update Asset</li>
                    </ul>";

        $getAssetData = $this->Crud_model->GetData('sales_return',"","id='".$id."'",'','','','row');
        $products = $this->Crud_model->GetData('sales_return_details',"","sales_return_id='".$id."'",'','','','row');
        
        $action =  site_url("sales_return/update_action/".$id);  
                                       
        $data = array(
          'breadcrumbs' => $breadcrumbs,
          'id' => $id,
          'heading' => 'Update Sales Return',
          'button'=>'Update',                        
          'action' => $action, 
          'getAssetData' => $getAssetData, 
          'products' => $products, 
        );

      $this->load->view('sales_return/update_form',$data);

    }

    public function update_action($id)
    {
      //print_r($_POST);exit;
      if($_POST)
      {
        $category = $this->Crud_model->GetData('categories','',"id='".$_POST['category_id']."'",'','','','1');
        //print_r($this->db->last_query());exit;
        if(!empty($category))
        {
          $data = array(
            'category_id'=>$_POST['category_id'],
            'asset_type_id'=>$_POST['asset_type_id'],
            'sku' => $_POST['sku'],
            'asset_name'=>$_POST['asset_name'],
            'quantity'=>$_POST['quantity'],
            'product_mrp'=>$_POST['product_mrp'],
            'gst_percent'=>$_POST['gst_percent'],          
            'hsn'=>$_POST['hsn'],          
            'lf_no'=>$_POST['lf_no'],          
            //'created_by'=>$_POST['created_by'],
            'modified'=> date('Y-m-d H:i:s'),                
          );
          $this->Crud_model->SaveData("assets",$data,"id='".$id."'");
          $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Product has been updated successfully</span>');
          redirect('sales_return/index');
        }
        else
        {
          $this->session->set_flashdata('message', '<span style="color:red;">Invalid</span>');
          redirect('sales_return/update/'.$id);
        }
      }
    }

    public function changeStatus()
    {       
        $getasset_types = $this->Crud_model->GetData('branches','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('branches',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('branches',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Status has been changed successfully</span>');
        redirect(site_url('Branches'));
    }

   
      public function delete($id)
      {
        $con = "id='".$id."'";      
        $this->Crud_model->DeleteData('sales_return',$con);
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Asset has been deleted successfully</span>');
        redirect(site_url('sales_return'));
      }



      public function getassetdetail($id)
       {
        if(!empty($id))
        {  
          $rowasset= $this->Crud_model->GetData('assets','',"id='".$id."'",'','','','row');

          $getassetavilquant = $this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as totalQuantity","asset_id='".$id."'",'','','','row');
           if(!empty($getassetavilquant))
          {
            $totalQuantity  = $rowasset->quantity-$getassetavilquant->totalQuantity;   
          }
          else
          {
             $totalQuantity  = $rowasset->quantity;
          }
          

          if(!empty($rowasset))
          {
              $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                    <li class='active'>Product Transfer</li>
                    </ul>";

              $getassetDetails = $this->Crud_model->GetData('asset_details',"","asset_id='".$id."' and status='In_use'",'','','',''); 
              $branch_data = $this->Crud_model->GetData('branches',"","is_delete='No' and status='Active'",'','branch_title');   

              $rowassetData['breadcrumbs'] = $breadcrumbs;             
              $rowassetData['asset_id'] = $rowasset->id;             
              $rowassetData['asset_name'] = $rowasset->asset_name;                     
              $rowassetData['asset_quantity_count'] = $totalQuantity;                     
              $rowassetData['getassetDetails'] = $getassetDetails;    
              $rowassetData['branch_data'] = $branch_data;    

              $this->load->view('assets/assets_detail',$rowassetData);

          }
          else
          {
              redirect('sales_return');
          }       

        }
        else
        {
          redirect('sales_return');
        }
      }

      public function getAssetTransferAjax($id)
    {


       $con="asset_id='".$id."' and id not in(select asset_detail_id from asset_branch_mappings_details) and id not in(select asset_details_id from assets_maintenance where status = 'Send') and status='In_use'";
      /* if(isset($_POST['SearchData2'])){
        $con.=" and id not in(select asset_detail_id from asset_branch_mappings_details where branch_id='".$_POST['SearchData2']."')";
       }*/


       $Data = $this->Asset_Transfer_model->get_datatables($con); 
      // print_r($this->db->last_query());exit;
       if(empty($_POST['SearchData1']))
        {
            $client_ids = array();
        }else{
            $client_ids = explode(',', $_POST['SearchData1']);     
        }
       $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
            $img ='<img src="'.base_url("assets/purchaseOrder_barcode/").$row->barcode_image.'" title="" width="100px"/>';
            $photo='-';
            if($row->image!='')
            {
              $photo ='<img src="'.base_url("uploads/assetimages/").$row->image.'" title="" width="100px" />';
            }
            $price='-';
            if($row->price!='0'){
              $price =$row->price;
            }
           if($_POST['select_all']=="true")
            {
                $chked = "checked";
            }else if(in_array($row->id, $client_ids)){
                $chked = "checked";
            }else{

                $chked = "";
            }


             $chk = '<input type="checkbox" name="client_id" id="client_id_'.$row->id.'" '.$chked.' onchange="checkbox_all('.$row->id.');" class="client_id client_id_'.$row->id.'" value="'.$row->id.'">';
            $no++;
            $nestedData = array();
            $nestedData[] = $chk ;
            $nestedData[] = $no ;
            $nestedData[] = $row->barcode_number;
           // $nestedData[] = $img;                  
            $nestedData[] = $photo;                  
            $data[] = $nestedData;
            $selected = '';
        }
         $filter = $this->Asset_Transfer_model->count_client_filtered($con);
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Asset_Transfer_model->count_all($con),
                    "recordsFiltered" => $this->Asset_Transfer_model->count_filtered($con),
                    "data" => $data,
                    "ids" => $filter->ids,
                );
       
        echo json_encode($output);
      
    }

    public function assetstransfer_action($id)
    {
      
      $asset_details_id = $_POST['asset_details_id'];
        $explode_asset_details_id = explode(',', $asset_details_id);
      $count_asset_quantity=count($explode_asset_details_id);
    
      $asset_branch_mappings = $this->Crud_model->GetData('asset_branch_mappings','',"branch_id='".$_POST['branch_id']."' and asset_id='".$id."'","","","","1");

      if(empty($asset_branch_mappings))
      { 
        $avlquantity=$count_asset_quantity;
       $data = array(
                      'branch_id'=>$_POST['branch_id'],
                      'asset_id'=>$id,
                      'asset_quantity'=>$count_asset_quantity,
                      'created'=> date('Y-m-d H:i:s'),          
            );
            $this->Crud_model->SaveData("asset_branch_mappings",$data);  
            $last_id=$this->db->insert_id();
        }
        else
        {
          $avlquantity= $asset_branch_mappings->asset_quantity +  $count_asset_quantity;
          $data = array(
                     'asset_quantity'=>$avlquantity,
            );
            $this->Crud_model->SaveData("asset_branch_mappings",$data,"id='".$asset_branch_mappings->id."'"); 
            $last_id=$asset_branch_mappings->id;
        }    

      for($i=0;$i<$count_asset_quantity;$i++)
            {
              if($explode_asset_details_id[$i]!='')
              {
                 $datadetail = array(
                  'asset_detail_id'=>$explode_asset_details_id[$i], 
                  'mode_of_transport' => $this->input->post('mode_of_transport'),
                  'transport_detail' => $this->input->post('transport_detail'),
                  'asset_branch_mappings_id' => $last_id,
                  'asset_id'=>$id,
                  'branch_id'=>$_POST['branch_id'],
                  'created'=> date('Y-m-d H:i:s'),
                
                );
                $this->Crud_model->SaveData("asset_branch_mappings_details",$datadetail);
              }
            }

       $financial_years = $this->Crud_model->GetData('financial_years','id',"status='Active'","","","","1");
       $assets = $this->Crud_model->GetData('assets','asset_type_id',"id='".$id."'","","","","1");
       $branches = $this->Crud_model->GetData('branches','branch_title',"status='Active' and id='".$_POST['branch_id']."'","","","","1");
       $descriptionTransfer="Transfer ".$count_asset_quantity." quantity to ".$branches->branch_title;

         $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$id."'");
             $Getasset = $this->Crud_model->GetData('assets','',"id = '".$id."'",'','','','single');
             $adminavlquantity= $Getasset->quantity - $getMappingQtyfasset[0]->quantity;

        $dataTransfer = array(
                  'asset_id'=>$id,
                  'financial_year_id'=>$financial_years->id,
                  'asset_type_id'=>$assets->asset_type_id,
                  'branch_id'=>"0",
                  'quantity'=>$count_asset_quantity,
                  'available_quantity'=>$adminavlquantity,
                  'description'=>$descriptionTransfer,
                  'transfer_to'=>$_POST['branch_id'],
                  'created_by'=>$_SESSION[SESSION_NAME]['id'],
                  'type'=>"Transfered",
                  'date'=> date('Y-m-d'),
                  'created'=> date('Y-m-d H:i:s'),
                );

                $this->Crud_model->SaveData("stock_logs",$dataTransfer);

                $descriptionRecieve="Received ".$count_asset_quantity." quantity transfered  by admin";
                $dataRecieve = array(
                  'asset_id'=>$id,
                  'financial_year_id'=>$financial_years->id,
                  'asset_type_id'=>$assets->asset_type_id,
                  'branch_id'=>$_POST['branch_id'],
                  'received_qty'=>$count_asset_quantity,
                  'available_quantity'=>$avlquantity,
                  'description'=>$descriptionRecieve,
                  'transfer_to'=>$_POST['branch_id'],
                  'created_by'=>$_SESSION[SESSION_NAME]['id'],
                  'type'=>"TReceived",
                  'date'=> date('Y-m-d'),
                  'created'=> date('Y-m-d H:i:s'),
                );
                $this->Crud_model->SaveData("stock_logs",$dataRecieve);
       $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Asset has been transfered successfully</span>');
            redirect('sales_return/index');
    }

    public function assetData()
    {
      $branch_id = $_POST['branch_id'];
      $asset_branch_mappings = $this->Crud_model->GetData('asset_branch_mappings','',"branch_id='".$branch_id."'","","","","1");

      $data = array(
                  'asset_branch_mappings'=>$asset_branch_mappings,
                 
                  );
      $this->load->view('assets/append_assetData',$data);
    }

      public function transferAssetsAction()
      {        
         if($_POST)
          {
            $GetBranch = $this->Crud_model->GetData('branches','',"id = '".$_POST['branch_id']."'",'','','','single');
            $getassetmapping = $this->Crud_model->GetData('asset_branch_mappings',"","branch_id='".$_POST['branch_id']."' and asset_id='".$_POST['assettransid']."'",'','','','row');
            
            $getassetdetails = $this->Crud_model->GetData('assets',"","id='".$_POST['assettransid']."'",'','','','row');
            $fin_year_id = $this->Crud_model->GetData('financial_years',"id","status='Active'",'','','','row');
            $total_cal_amt_for_stock_log = $_POST['quantity'] * $getassetdetails->final_amount;


            if(!empty($getassetmapping))
            {

              $asset_quantity_existing = $getassetmapping->asset_quantity+$_POST['quantity'];
              

              $data = array(
                            'branch_id'=>$_POST['branch_id'],
                            'asset_id'=>$_POST['assettransid'],
                            'asset_quantity'=>$asset_quantity_existing,          
                            'created'=>date('Y-m-d H:i:s'),            
                          );

              $this->Crud_model->SaveData("asset_branch_mappings",$data,"id='".$getassetmapping->id."'"); 
              $data1 = array(                
                'financial_year_id'=>$fin_year_id->id,
                'asset_id'=>$getassetdetails->id,            
                'branch_id'=>$_POST['branch_id'],            
                'asset_type_id'=>$getassetdetails->asset_type_id,   
                'transfer_to'=>$_POST['branch_id'],         
                'received_qty'=>$_POST['quantity'],            
                'available_quantity'=>$asset_quantity_existing,                                   
                'total_amount'=>$total_cal_amt_for_stock_log,                      
                'date'=>date('Y-m-d'),
                'description' => "Received ".$_POST['quantity']." quantity  of asset  transfer by Admin.",            
                'created_by'=>$_SESSION[SESSION_NAME]['id'],            
                'type'=>'TReceived',            
                'created'=>date('Y-m-d H:i:s'),            
                );
              //print_r($data1);exit;
              $this->Crud_model->SaveData("stock_logs",$data1);
            }
            else
            {
                   $data = array(
                                  'branch_id'=>$_POST['branch_id'],
                                  'asset_id'=>$_POST['assettransid'],
                                  'asset_quantity'=>$_POST['quantity'],          
                                  'modified'=>date('Y-m-d H:i:s'),            
                                );
                 $this->Crud_model->SaveData("asset_branch_mappings",$data); 
                 $insert_id=$this->db->insert_id();
                
                $data1 = array(                
                'financial_year_id'=>$fin_year_id->id,
                'asset_id'=>$getassetdetails->id,            
                'branch_id'=>$_POST['branch_id'],            
                'asset_type_id'=>$getassetdetails->asset_type_id,   
                'transfer_to'=>$_POST['branch_id'],         
                'received_qty'=>$_POST['quantity'],            
                'available_quantity'=>$_POST['quantity'],                                   
                'total_amount'=>$total_cal_amt_for_stock_log,                      
                'date'=>date('Y-m-d'),
                'description' => "Received ".$_POST['quantity']." quantity of asset transfer by Admin.",            
                'created_by'=>$_SESSION[SESSION_NAME]['id'],            
                'type'=>'TReceived',            
                'created'=>date('Y-m-d H:i:s'),            
                );
              //print_r($data1);exit;
              $this->Crud_model->SaveData("stock_logs",$data1);
               
                 
            }

            
              $data = array(                
                'branch_id'=>$_POST['branch_id'],
                'asset_id'=>$_POST['assettransid'],
                'asset_quantity'=>$_POST['quantity'],          
                'transfer_date'=>date('Y-m-d'),            
                'created'=>date('Y-m-d H:i:s'),            
                );
              $this->Crud_model->SaveData("asset_history_transfers",$data); 

             $getMappingQtyfasset=$this->Crud_model->GetData('asset_branch_mappings',"sum(asset_quantity) as quantity","asset_id = '".$_POST['assettransid']."'");
             $Getasset = $this->Crud_model->GetData('assets','',"id = '".$_POST['assettransid']."'",'','','','single');
             $avlquantity= $Getasset->quantity - $getMappingQtyfasset[0]->quantity;

              $data1 = array(                
                'financial_year_id'=>$fin_year_id->id,
                'asset_id'=>$getassetdetails->id,            
                'branch_id'=>'0',            
                'asset_type_id'=>$getassetdetails->asset_type_id,   
                'transfer_to'=>$_POST['branch_id'],         
                'quantity'=>$_POST['quantity'],            
                'available_quantity'=>$avlquantity,                                   
                'total_amount'=>$total_cal_amt_for_stock_log,                      
                'date'=>date('Y-m-d'),
                'description' => "Transfer of  quantity ".$_POST['quantity']." to ".$GetBranch->branch_title." branch",            
                'created_by'=>$_SESSION[SESSION_NAME]['id'],            
                'type'=>'Transfer',            
                'created'=>date('Y-m-d H:i:s'),            
                );
              //print_r($data1);exit;
              $this->Crud_model->SaveData("stock_logs",$data1);


              $this->session->set_flashdata('message', '<lable class="label label-success">Asset Transfer successfully</label>');
              redirect('sales_return/index');
          }
          else
          {
            redirect('sales_return/index');
          }
      }

  public function log_details($id,$branch_id='')
  {
    $controller_name = $this->uri->segment(1);
    $assets_name = $this->Crud_model->GetData('assets',"","id='".$id."'",'','','','1');
    $export =anchor(site_url($controller_name.'/export'),'<span title="Export" class="fa fa-file-excel-o"></span>');

    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li><a href='".site_url('sales_return/index')."'>Manage Sales Return</a></li>
                    <li class='active'>Log Details</li>
                    </ul>";

    if(!empty($branch_id))
    {
      $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                      <li><a href='".site_url('sales_return/index')."'>Manage Sales Return</a></li>
                    <li class='active'>Log Details</li>
                    </ul>";
    }

    $data = array(
                  'assets_name' => $assets_name,
                  'breadcrumbs' => $breadcrumbs,
                  'export' => $export,
                  'id' => $id,
                  'branch_id' => $branch_id,
                  'actioncolumns' => '8' ,
                  'ajax_manage_pages' => site_url('sales_return/ajax_manage_pages/'.$id.'/'.$branch_id),
                  );

    $this->load->view('assets/log_details',$data);
  }

  public function ajax_manage_pages($id,$branch_id='')
    {   
       $controller_name = $this->uri->segment(1);
        /* ----- Condition for Search start ----- */   
        $type = $_POST['SearchData5'];
       // print_r($type);exit;

        if(!empty($type))
        {
             if(empty($branch_id))
              {
                $con = "stock_logs.asset_id='".$id."' and stock_logs.branch_id='0' and stock_logs.type LIKE '%".$type."%'";
              }
              else
              {
                $con = "stock_logs.asset_id='".$id."' and stock_logs.branch_id='".$branch_id."' and stock_logs.type LIKE '%".$type."%'";
              }        
        }
        else
        {
            if(empty($branch_id))
            {
              $con = "stock_logs.asset_id='".$id."' and stock_logs.branch_id='0'";
            }
            else
            {
              $con = "stock_logs.asset_id='".$id."' and stock_logs.branch_id='".$branch_id."'";
            }
        }

      
        $Data = $this->Assets_logs_model->get_datatables($con); 
        //print_r($this->db->last_query());exit;
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        { 
           

           
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            if($row->type == 'Received')
            {
              $type = '<b class="text-success">Purchased</b>';
              $avl_qty = $row->available_quantity;
                $branch_title_from ='';
                $branch_title_to ='';
            }else if($row->type == 'TReceived'){
              $type = '<b style="color:#1CB099">Received</b>';
              $avl_qty = $row->available_quantity;
              $branch_title_from = $this->Crud_model->GetData('branches',"","id='".$row->transfer_from."'",'','','','1');
              $branch_title_to = $this->Crud_model->GetData('branches',"","id='".$row->transfer_to."'",'','','','1');

              if(!empty($branch_title_from)){$branch_title_from = $branch_title_from->branch_title; }else{ $branch_title_from = '-'; }
              if(!empty($branch_title_to)){$branch_title_to = $branch_title_to->branch_title; }else{ $branch_title_to = '-'; }
            }
            else
            {
              $type = '<b class="text-danger">'.ucwords($row->type).'</b>';
              $avl_qty = $row->available_quantity;
               $branch_title_from = $this->Crud_model->GetData('branches',"","id='".$row->transfer_from."'",'','','','1');
               $branch_title_to = $this->Crud_model->GetData('branches',"","id='".$row->transfer_to."'",'','','','1');
               if(!empty($branch_title_from)){$branch_title_from = $branch_title_from->branch_title; }else{ $branch_title_from = '-'; }
               if(!empty($branch_title_to)){$branch_title_to = $branch_title_to->branch_title; }else{ $branch_title_to = '-'; }

            }

            if($row->is_barcode == 'Yes')
            {
              $src = 'Barcode';
              
            }
            else
            {
             $src = "Direct";
             
            }

            //print_r($branch_title_from);exit;
      
      $nestedData[] = $type;
            if($row->type == 'Received')
            {
              $quantity = $row->received_qty;
            }
            else
            {
              $quantity = $row->quantity;
            }
            $nestedData[] = $quantity;
            $nestedData[] = $avl_qty;
            $nestedData[] = $row->description;
            $nestedData[] = $branch_title_from;
            $nestedData[] = $branch_title_to;
            $nestedData[] = $src;
            $nestedData[] = $row->date;
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Assets_logs_model->count_all($con),
                    "recordsFiltered" => $this->Assets_logs_model->count_filtered($con),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }

    public function addDataUnit()
      {        
          $condDuplication = "unit='".$this->input->post('name')."'";
          $duplication = $this->Crud_model->GetData('unit_types','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'unit' => $this->input->post('name'),
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('unit_types', $data);
              $insert_id = $this->db->insert_id();
              $getlastrow = $this->Crud_model->GetData('unit_types',"","id='".$insert_id."'",'','','','row');
              $this->session->set_flashdata('message', 'success');

              $html='';
              $html.="<option value=".$getlastrow->id.">".$getlastrow->unit."</option>";
              echo $html;
          }            
      }


      public function addDataAssetType()
      {        
          $condDuplication = "type='".$this->input->post('nametype')."'";
          $duplication = $this->Crud_model->GetData('mst_asset_types','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'type' => $this->input->post('nametype'),
                
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('mst_asset_types', $data);
              $insert_idat = $this->db->insert_id();
              $getlastrowat = $this->Crud_model->GetData('mst_asset_types',"","status='Active'");
              $this->session->set_flashdata('message', 'success');

              $html='<option value="">Select Product Type Name</option>';
              if(!empty($getlastrowat)) {
                foreach ($getlastrowat as $row) {
                  if($insert_idat==$row->id) {
                    $selected = 'selected="selected"';
                  } else {
                    $selected = '';
                  }
                  $html.="<option value=".$row->id." ".$selected.">".$row->type."</option>";
                }
              }
              echo $html;             
          }  
          
      }

      /* ----- Export functionality start ----- */
    public function export_sales_return()
    {
        //print_r($_POST);exit;
        $dn_no = $this->input->post('dn_no');
        $date = $this->input->post('date');
        $sent_to = $this->input->post('sent_to');
        //$quotation_id = $this->input->post('quotation_id');
        //$distributor_id = $this->input->post('distributor_id');
        
        $con = "po.id<>0";
        /*if($todate != '')
        {
            $con .= " and po.purchase_date <='".date('Y-m-d',strtotime($todate))."'";
        }
        if($fromdate != '')
        {
            $con .= " and po.purchase_date >='".date('Y-m-d',strtotime($fromdate))."'";
        }
        if($vendor_id != '')
        {
            $con .= " and po.vendor_id= '".$vendor_id."'";
        }
        if($quotation_id != '')
        {
            $con .= " and po.quotation_id= '".$quotation_id."'";
        }*/
        /*if($distributor_id != '')
        {
            $con .= " and po.distributor_id= '".$distributor_id."'";
        }*/

            $results = $this->sales_return_model->ExportCSV($con);
        
            $FileTitle='sales_return Summary Report';
                
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Report Sheet');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'sales_return Summary Details ');
            $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
            $this->excel->getActiveSheet()->setCellValue('C3', 'Product Name');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Quantity');
            $this->excel->getActiveSheet()->setCellValue('D3', 'Price');
            $this->excel->getActiveSheet()->setCellValue('E3', 'GST %');
            $this->excel->getActiveSheet()->setCellValue('F3', 'LF No.');
            /*$this->excel->getActiveSheet()->setCellValue('G3', 'Status');*/
            //$this->excel->getActiveSheet()->setCellValue('G3', 'Amount');
            
            $a='4'; $sr = 1;    
            //print_r($results);exit;
            foreach ($results as $result) 
            {       
                
                /*$total = $result->sum + $result->transport ;
                $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$result->id."'",'','','','single');
                $total = $total - $returnAmt->return_amount;*/
                
                $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
                $this->excel->getActiveSheet()->setCellValue('C'.$a, $result->asset_name);
                $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->quantity);
                $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->price);                
                $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->gst_percent);
                $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->hsn);
                //$this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
                //$this->excel->getActiveSheet()->setCellValue('H'.$a, $total);
                $sr++; $a++;                
            }
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
                //$this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            ob_clean();
                        
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');   
    }
        /* ----- Export functionality end ----- */

      /* ----- Export functionality start ----- */
    public function export($id,$branch_id='')
    {

       // print_r($_POST);exit;
        $type = $_POST['type'];       
       /* //$asset_id = $_POST['asset_id'];       
        $con = "sl.id<>0";        
        if($type != '')
        {
            $con .= " and sl.type ='".$type."' and asset_id='".$id."'";
        } */
        if(!empty($type))
        {
             if(empty($branch_id))
              {
                $con = "sl.asset_id='".$id."' and sl.branch_id='0' and sl.type LIKE '%".$type."%'";
              }
              else
              {
                $con = "sl.asset_id='".$id."' and sl.branch_id='".$branch_id."' and sl.type LIKE '%".$type."%'";
              }        
        }
        else
        {
            if(empty($branch_id))
            {
              $con = "sl.asset_id='".$id."' and sl.branch_id='0'";
            }
            else
            {
              $con = "sl.asset_id='".$id."' and sl.branch_id='".$branch_id."'";
            }
        }




            $results = $this->Assets_logs_model->ExportCSV($con);   
           // print_r($this->db->last_query());exit;        
            $FileTitle='Product log Report';                
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Report Sheet');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'Assets log  Details ');
            $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
            $this->excel->getActiveSheet()->setCellValue('C3', 'Type');
            $this->excel->getActiveSheet()->setCellValue('B3', 'Quantity');
            $this->excel->getActiveSheet()->setCellValue('D3', 'Available Quantity');
            $this->excel->getActiveSheet()->setCellValue('E3', 'Description');
            $this->excel->getActiveSheet()->setCellValue('F3', 'Transfer From');
            $this->excel->getActiveSheet()->setCellValue('G3', 'Transfer To');
            $this->excel->getActiveSheet()->setCellValue('H3', 'Source');
            $this->excel->getActiveSheet()->setCellValue('I3', 'Date');
            $this->excel->getActiveSheet()->setCellValue('J3', 'Financial Year');
            $this->excel->getActiveSheet()->setCellValue('K3', 'Asset Name');
            
            
            $a='4'; $sr = 1;    
            //print_r($results);exit;
            foreach ($results as $result) 
            {      

              $branch_title_from = $this->Crud_model->GetData('branches',"","id='".$result->transfer_from."'",'','','','1');
              if(!empty($branch_title_from))
              {
                  $branch_title_from = $branch_title_from->branch_title;
              }
              else
              {
                $branch_title_from='-';
              }
              $branch_title_to = $this->Crud_model->GetData('branches',"","id='".$result->transfer_to."'",'','','','1');

              if(!empty($branch_title_to))
              {
                  $branch_title_to = $branch_title_to->branch_title;
              }
              else
              {
                $branch_title_to='-';
              }

              if($result->is_barcode == 'Yes')
              {
                $src = 'Barcode';
              }
              else
              {
                $src = "Direct";
              }   

                if($result->type == 'Received')
                {
                  $quantity = $result->received_qty;
                  $type='Purchased';
                }else if($result->type == 'TReceived'){
                   $quantity = $result->received_qty;
                   $type='Received';
                }
                else
                {
                  $quantity = $result->quantity;
                  $type=$result->type;
                }

                $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
                $this->excel->getActiveSheet()->setCellValue('C'.$a, $type);
                $this->excel->getActiveSheet()->setCellValue('B'.$a, $quantity);
                $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->available_quantity);                
                $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->description);               
                $this->excel->getActiveSheet()->setCellValue('F'.$a, $branch_title_from);
                $this->excel->getActiveSheet()->setCellValue('G'.$a, $branch_title_to);
                $this->excel->getActiveSheet()->setCellValue('H'.$a, $src);
                $this->excel->getActiveSheet()->setCellValue('I'.$a, date('d-m-Y',strtotime($result->date)));
                $this->excel->getActiveSheet()->setCellValue('J'.$a, $result->financial_year);
                $this->excel->getActiveSheet()->setCellValue('K'.$a, $result->asset_name);               
                $sr++; $a++;                
            }
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->mergeCells('A1:K1');
                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            ob_clean();      
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');   
    }
        /* ----- Export functionality end ----- */


public function viewAssetDetails($asset_id,$id)
{
  if(($asset_id=='' || $id=='') || ($asset_id=='' && $id==''))
  {
    redirect('sales_return/index');
  }
  else
  {
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>

                    </li>
                     <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                     <li class=''> <a href='".site_url('sales_return/view/'.$asset_id)."'>Manage Product View</a></li>
                    <li class='active'>View Product Details</li>
                    </ul>";
    $asset_details = $this->Crud_model->GetData('asset_details','',"asset_id='".$asset_id."' and id='".$id."'",'','','','1');
    $replaceDetails = '';
    if(!empty($asset_details->parent_id)){
      $replaceDetails = $this->Crud_model->GetData('asset_details','',"asset_id='".$asset_id."' and id='".$asset_details->parent_id."'",'','','','1');
    }
    $assets = $this->Crud_model->GetData('assets','',"id='".$asset_id."'",'','','','1');
    $data = array(
                  'breadcrumbs' => $breadcrumbs,
                  'asset_details' => $asset_details,
                  'asset_id' => $asset_id,
                  'assets' => $assets,
                  'replaceDetails' => $replaceDetails,
                  );

    $this->load->view('assets/viewAssetDetails',$data);
  }
}

public function asset_transfer_detail($id)
{
  if(!empty($id))
  {
      $asset_branch_mappings = $this->Crud_model->GetData('asset_branch_mappings','',"id='".$id."'","","","","1");

      if(!empty($asset_branch_mappings))
      {  
        
        $assets = $this->Crud_model->GetData('assets','asset_name',"id='".$asset_branch_mappings->asset_id."'","","","","1");
        $assetsDetails = $this->Crud_model->GetData('asset_details','sum(quantity) as astQty',"asset_id='".$asset_branch_mappings->asset_id."' and id in(select asset_detail_id from asset_branch_mappings_details where asset_id='".$asset_branch_mappings->asset_id."' and asset_branch_mappings_id='".$id."') and status='In_use'","","","","1");
        $branches = $this->Crud_model->GetData('branches','branch_title',"id='".$asset_branch_mappings->branch_id."'","","","","1");
        $astQty='';
        if(!empty($assetsDetails)){
          $astQty=$assetsDetails->astQty;
        }
        $breadcrumbs = "<ul class='breadcrumb'>
                <li>
                    <i class='ace-icon fa fa-home home-icon'></i>
                    <a href='".site_url('Dashboard')."'>Dashboard</a>
                </li>
                <li class=''> <a href='".site_url('sales_return')."'>Manage Sales Return</a></li>
                <li class='active'>Stock </li>
                </ul>";


        $data=array(
          'assets' => $assets,
          'breadcrumbs' => $breadcrumbs,
          'branch_title' => $branches->branch_title,
          'heading' => 'Stock',
          'asset_branch_mappings' => $asset_branch_mappings,
          'asset_branch_mapping_id' => $asset_branch_mappings->id,
          'astQty' => $astQty,
          );


        $this->load->view('assets/asset_transfer_detail',$data);
      }
      else
      {
          redirect('sales_return');
      }  
  } 
  else
  {
    redirect('sales_return');
  } 
}

public function getAssetTransferViewAjax($id)
{
  $con="abmd.asset_branch_mappings_id='".$id."' and ad.status='In_use'";
  $Data = $this->Asset_Transfer_View_model->get_datatables($con); 
   
   $data = array();       
    $no=0; 
    foreach($Data as $row) 
    { 
      
      $photo='<img src="'.base_url("../admin/uploads/employee_images/default.jpg").'" title="" width="100px" />';
      if($row->image!='')
      {
        $photo ='<img src="'.base_url("../admin/uploads/assetimages/").$row->image.'" title="" width="100px" />';
      }
     $barcode_image='-';
      if($row->barcode_image!='')
      {
        $barcode_image ='<img src="'.base_url("../admin/assets/purchaseOrder_barcode/").$row->barcode_image.'" title="" width="100px" />';
      }
      $btn ='<a href='.site_url("sales_return/viewAssetDetails/".$row->asset_id.'/'.$row->id).' title="Details" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></a>';

      $asset_geo_locations= $this->Crud_model->GetData('asset_geo_locations','',"asset_detail_id='".$row->id."'",'','','','1'); 

      if(!empty($asset_geo_locations))
      {

         $btn .=' | <a href="'.site_url("sales_return/assetDetailGeoLocation/").$row->asset_id.'/'.$row->id.'"  class="btn btn-danger btn-circle btn-sm"><i class="fa fa-map-marker"></i></a>';
      }  
          
       
       


     
      $no++;
      $nestedData = array();
      $nestedData[] = $no ;
      $nestedData[] = $barcode_image;
      $nestedData[] = $row->barcode_number;
      $nestedData[] = $photo;                  
      $nestedData[] = $btn;                  
      $data[] = $nestedData;
      $selected = '';
    }
   
    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Asset_Transfer_View_model->count_all($con),
                "recordsFiltered" => $this->Asset_Transfer_View_model->count_all($con),
                "data" => $data,
            );
   
    echo json_encode($output);
}

public function savemyCategory()
{
  $record= $this->Crud_model->GetData('categories','',"title='".$_POST['category_id_title']."'",'','','','1');
  //$category_data = $this->Crud_model->GetData('categories',"","status='Active'",'','title asc'); 
  if(count($record) > 0)
  {
     echo "0";
  }
  else
  {
    $data = array(
      'title' => ucwords($_POST['category_id_title']),
      //'category_id' => ucwords($_POST['category_id_title']),
      'status' => "Active",
      //'category_data' => $category_data,
      'created' => date("Y-m-d H:i:s"),
     );
    $this->Crud_model->SaveData("categories",$data); 
    $last_id = $this->db->insert_id(); 
    $subcategory = $this->Crud_model->GetData("categories",'',"status='Active'","","title asc");
 
    $response = '<option value="0">Select Category</option>';
    foreach ($subcategory as $row) 
    {
      if($last_id==$row->id) {
        $selected = 'selected="selected"';
      } else {
        $selected = '';
      }
      $response .= '<option value="'.$row->id.'" '.$selected.'>'.ucfirst($row->title).'</option>';
    }
      echo $response ;exit; 
  }
}

public function getSubcategory()
{
  $subcategoryData = $this->Crud_model->GetDataCatSubcat("sub_categories.sub_cat_title like'".$_REQUEST['search']."%' AND sub_categories.status='Active'");

    $json=array();

            foreach($subcategoryData as $key){
                $json[]=array('label'=>$key->sub_cat_title.' - '.$key->title,'sub_cat_title'=>$key->sub_cat_title);
            }
        
        echo json_encode($json);exit;  
}

public function checkSubcat()
{
  $subcategory = $this->Crud_model->GetData('sub_categories','',"sub_cat_title='".$_POST['subcategory_id']."'",'','','','1');
  if(empty($subcategory))
  {
    echo '1';
  }
  else
  {
    echo '0';
  }
}

public function import()
{
  $file = $_FILES['excel_file']['tmp_name'];
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true);
        $arrayCount = count($allDataInSheet);
        $i = 1;

        foreach ($allDataInSheet as $val) 
        {
            if ($i <= 1) 
            {
                
            }
            else
            {
                $fields_fun[] = $val;
            }
            $i++;
        }
        
        if(!isset($fields_fun))
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Excel Sheet is blank</span>');
            
            redirect(site_url('sales_return/index'));            
        } 
        $data = $fields_fun;
        if(count($val) == '8')
        {
            $exists = 0;
            foreach ($data as $val) 
            {  
                if(($val[0]!='') && ($val[1]!='') && ($val[2]!='') && ($val[3]!='') && ($val[4]!='') && ($val[5]!='') && ($val[6]!='') && ($val[7]!=''))
                {
                    if(!preg_match("/^[0-9]*$/",$val[5]))
                    {
                        $existAssets[]=array($val[0],$val[1],$val[2],$val[3],$val[4],$val[5],$val[6],$val[7],'Invalid price');
                    }    
                    else
                    { 
                        if($val[0]!='')
                        {
                            $getAssetType = $this->Crud_model->GetData('mst_asset_types','',"type='".$val[0]."' and is_delete='No' and status='Active'" ,'','','','single'); 
                            if(empty($getAssetType))
                            {
                                $saveAssetType = $this->Crud_model->SaveData('mst_asset_types',array("type"=>$val[0]));
                                $AssetTypeId = $this->db->insert_id();
                            }
                            else
                            {
                                $AssetTypeId = $getAssetType->id;
                            }
                        }
                        else
                        {
                            $AssetTypeId = '';
                        }

                        if($val[1]!='')
                        {
                            $getCategory = $this->Crud_model->GetData('categories','',"title='".$val[1]."'",'','','','single'); 
                            if(empty($getCategory))
                            {
                                $saveCategory = $this->Crud_model->SaveData('categories',array("title"=>$val[1]));
                                $CategoryId = $this->db->insert_id();
                            }else{
                                $CategoryId = $getCategory->id;
                            }
                        }
                        else
                        {
                            $CategoryId = '';
                        }

                        if($val[1]!='' and $val[2]!='')
                        {
                            $getSubCategory = $this->Crud_model->GetData('sub_categories','',"sub_cat_title='".$val[2]."' and category_id='".$CategoryId."'",'','','','single'); 
                            if(empty($getSubCategory))
                            {
                                $saveSubCategory = $this->Crud_model->SaveData('sub_categories',array('sub_cat_title'=>$val[2],'category_id'=>$CategoryId));
                                $SubCategoryId = $this->db->insert_id();
                            }else{
                                $SubCategoryId = $getSubCategory->id;
                            }
                        }
                        else
                        {
                            $SubCategoryId = '';
                        }
                        
                        if($val[4]!='')
                        {
                            $getBrand = $this->Crud_model->GetData('brands','',"brand_name='".$val[4]."'",'','','','single'); 
                            if(empty($getBrand))
                            {
                                $saveBrand = $this->Crud_model->SaveData('brands',array('brand_name'=>$val[4]));
                                $BrandId = $this->db->insert_id();
                            }else{
                                $BrandId = $getBrand->id;
                            }
                        }
                        else
                        {
                            $BrandId = '';
                        }
                        
                        if($val[7]!='')
                        {
                            $getUnit = $this->Crud_model->GetData('unit_types','',"unit='".$val[7]."'",'','','','single'); 
                            if(empty($getUnit))
                            {
                                $saveUnit = $this->Crud_model->SaveData('unit_types',array('unit'=>$val[7]));
                                $UnitId = $this->db->insert_id();
                            }else{
                                $UnitId = $getUnit->id;
                            }
                        }
                        else
                        {
                            $UnitId = '';
                        }

                        $getAssetType = $this->Crud_model->GetData('assets','asset_name',"asset_name='".$val[3]."'",'','','','single'); 
                        /*for already exist check*/
                        
                        if(empty($getAssetType))
                        {
                            $data = array(
                                          'asset_type_id'=>$AssetTypeId,
                                          'category_id'=>$CategoryId,
                                          'subcategory_id'=>$SubCategoryId,
                                          'asset_name' => $val[3],
                                          'brand_id' => $BrandId,
                                          'product_mrp' => $val[5],
                                          'description' => $val[6],
                                          'unit_id' => $UnitId,
                                          );
                            $SaveAssets = $this->Crud_model->SaveData('assets',$data);
                        }
                        else
                        { 
                            $existAssets[]=array($val[0],$val[1],$val[2],$val[3],$val[4],$val[5],$val[6],$val[7],'Asset Name already exist');
                        }
                    }
                }
                else
                {
                    $existAssets[]=array($val[0],$val[1],$val[2],$val[3],$val[4],$val[5],$val[6],$val[7],'Mandatory fields empty');
                }
            } 
        }
        else
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Invalid file selected</span>');
            
            redirect(site_url('sales_return/index'));  
        }
        if(empty($existAssets))
        {
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Asset has been imported successfully</span>');
            redirect('sales_return/index');
        }else{
            $data = array('existAssets' => $existAssets);
            $this->load->view('assets/duplicateAssets',$data);
        } 
}


public function warranty_read($assets_id,$asset_details_id)
{
  $data = array(
              'is_read' => 'Yes',
              'modified' => date('Y-m-d H:i:s'),   
                );
  $this->Crud_model->SaveData('notifications',$data,"assets_id='".$assets_id."'");
  redirect('sales_return/viewAssetDetails/'.$assets_id.'/'.$asset_details_id);
}

public function createAssetImages($asset_id='',$id='')
{
  if(!empty($id))
  {
  $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>

                    </li>
                     <li class=''> <a href='".site_url('sales_return')."'>Manage sales_return</a></li>
                     <li class=''> <a href='".site_url('sales_return/view/'.$asset_id)."'>Manage Product View</a></li>
                     <li class=''> <a href='".site_url('sales_return/assetImagesList/'.$asset_id.'/'.$id)."'>Manage Product Images List</a></li>
                    <li class='active'>Add Product Images</li>
                    </ul>";
  $data = array(
              'heading' => 'Add Product Images',
              'button' => 'Create',
              'breadcrumbs' => $breadcrumbs,
              'date' =>  set_value('date',$this->input->post('date')),   
              'description' =>  set_value('description',$this->input->post('description')), 
              'image' =>  set_value('image',$this->input->post('image')),
              'action' =>  site_url('sales_return/create_actionAssetImages/'.$asset_id.'/'.$id),  
                );
  $this->load->view('assets/createAssetImages',$data);
  }
  else
  {
  	redirect('sales_return/index');
  }
}

public function create_actionAssetImages($asset_id,$id)
{ 
  if(!empty($id)){
  $count = count($_FILES['image']['name']); 
  if($count > 0)
  { 
  for($i=0;$i<$count;$i++)
  { 
    $path=getcwd().'/uploads/'; 
    $folder = "assets_images"; 

    $folder2 = date("Y");
    if(!is_dir($path.$folder."/".$folder2))
    {
       mkdir($path.$folder."/".$folder2);
    }

    $folder3 = date("m");
    if(!is_dir($path.$folder."/".$folder2."/".$folder3))
    {
       mkdir($path.$folder."/".$folder2."/".$folder3);
    }

    $file_element_name = 'image'; 
    $_POST['image']= $folder2."/".$folder3.'/AT_'.rand(0000,9999).$_FILES['image']['name'][$i];
    $config2['image_library'] = 'gd2';
    $config2['source_image'] =  $_FILES['image']['tmp_name'][$i];
    $config2['new_image'] =   getcwd().'/uploads/assets_images/'.$_POST['image'];
    $config2['upload_path'] =  getcwd().'/uploads/assets_images/'.$_POST['image'];
    $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
    $config2['maintain_ratio'] = TRUE;
    $config2['max_size'] = '1024';
    $config2['width'] = "200";
    $config2['height'] = "300";

    $this->image_lib->initialize($config2);
    if(!$this->image_lib->resize())
    {
      echo('<pre>');
      echo ($this->image_lib->display_errors());
    }
    else
    { 
      $image= $_POST['image'];
    }
    $data = array(
              'asset_id' => $asset_id,
              'asset_details_id' => $id,
              'date' => $this->input->post('date'),
              'description' => $this->input->post('description'),
              'image' =>$image,
              'created' => date("Y-m-d H:i:s"),
              );
    //print_r($data);exit;
    $this->Crud_model->SaveData('asset_multiple_images',$data);
  }  
    $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record created successfully</p></div>');
    redirect('sales_return/AssetImagesList/'.$asset_id.'/'.$id);
  }
  else
  {
    $this->session->set_flashdata('message', '<div class="label label-danger text-center" style="margin-bottom:0px;"><p>Something went wrong</p></div>');
    redirect('sales_return/AssetImagesList/'.$asset_id.'/'.$id);
  }
  }
  else
  {
  	redirect('sales_return/index');
  }
}

public function AssetImagesList($asset_id,$id)
{
  if(!empty($id)){
  $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>

                    </li>
                     <li class=''> <a href='".site_url('sales_return')."'>Manage sales_return</a></li>
                     <li class=''> <a href='".site_url('sales_return/view/'.$asset_id)."'>Manage sales_return View</a></li>
                    <li class='active'>View Asset Images</li>
                    </ul>";
  $data = array(
              'action' =>  site_url('sales_return/create_actionAssetImages/'.$asset_id.'/'.$id), 
              'actioncolumn' => '4',
              'breadcrumbs' => $breadcrumbs,
              'heading' => 'Manage Product Images',
              'ajax_manage_page' => site_url('sales_return/ajax_manage_page_assets_images/'.$asset_id.'/'.$id) ,
                );

  $this->load->view('assets/assetImagesList',$data);
  }
  else{
  	redirect('sales_return/index');
  }
}

public function ajax_manage_page_assets_images($asset_id,$id)
{
  $condition="asset_multiple_images.asset_details_id='".$id."'";
      $getData = $this->Asset_images_model->get_datatables($condition);

        $data = array();
        if(empty($_POST['start']))
            {
                $no =0;   
            }
            else
            {
                $no =$_POST['start'];
            }
        foreach ($getData as $Data) {

          $btn ='&nbsp; '. '<a style="color:#fff" title="Update" href="'.site_url('sales_return/updateAssetImages/'.$Data->id.'/'.$asset_id.'/'.$id).'"><span class="btn btn-info btn-circle btn-sm"><i class="ace-icon fa fa-pencil bigger-130"></i></a></span>';

          $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$Data->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';

        if(!empty($Data->image))
        {
          $img="<img src=".base_url('uploads/assets_images/'.$Data->image)." width='50px' height='50px'>";
        }
        else
        {
          $img="<img src=".base_url('uploads/employee_images/default.jpg')." width='50px' height='50px'>";
        }

        if(!empty($Data->description))
        {
          $description = $Data->description;
        }
        else
        {
          $description = '-';
        }

            $no++;   
            $row = array();
            $row[] = $no;
            $row[] = $Data->date;
            $row[] = $description;
            $row[] = $img;
            //$row[] = $status;
            $row[] = $btn;
            $data[] = $row;
            $selected = '';
        }
 
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Asset_images_model->count_all($condition),
          "recordsFiltered" => $this->Asset_images_model->count_filtered($condition),
          "data" => $data,
        );
        echo json_encode($output);
}

public function updateAssetImages($id,$asset_id,$asset_details_id)
{
  $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>

                    </li>
                     <li class=''> <a href='".site_url('sales_return')."'>Manage sales_return</a></li>
                     <li class=''> <a href='".site_url('sales_return/view/'.$asset_id)."'>Manage Product View</a></li>
                     <li class=''> <a href='".site_url('sales_return/assetImagesList/'.$asset_id.'/'.$id)."'>Manage Product Images List</a></li>
                    <li class='active'>Product Image Update</li>
                    </ul>";
  $asset_multiple_images = $this->Crud_model->GetData('asset_multiple_images','',"id='".$id."'",'','','','1');
  $data = array(
              'heading' => 'Update Product Images',
              'button' => 'Update',
              'breadcrumbs' => $breadcrumbs,
              'asset_multiple_images' => $asset_multiple_images,
              'date' =>  $asset_multiple_images->date,   
              'description' => $asset_multiple_images->description, 
              'image' => $asset_multiple_images->image,
              'action' => site_url('sales_return/update_actionAssetImages/'.$id.'/'.$asset_id.'/'.$asset_details_id),
              );

  $this->load->view('assets/createAssetImages',$data);
}

public function update_actionAssetImages($id,$asset_id,$asset_details_id)
{
  $con = "id='".$id."'";
  if($_FILES['image']['name']!='')
  {
    /*For making dynamic folders*/
    $path=getcwd().'/uploads/'; 
    $folder = "assets_images"; 

    $folder2 = date("Y");
    if(!is_dir($path.$folder."/".$folder2))
    {
       mkdir($path.$folder."/".$folder2);
    }

    $folder3 = date("m");
    if(!is_dir($path.$folder."/".$folder2."/".$folder3))
    {
       mkdir($path.$folder."/".$folder2."/".$folder3);
    }
    /*For making dynamic folders end*/

    $file_element_name = 'image';    
    $_POST['image']= $folder2."/".$folder3.'/AT_'.rand(0000,9999).$_FILES['image']['name'];
    $config2['image_library'] = 'gd2';
    $config2['source_image'] =  $_FILES['image']['tmp_name'];
    $config2['new_image'] =   getcwd().'/uploads/assets_images/'.$_POST['image'];
    $config2['upload_path'] =  getcwd().'/uploads/assets_images/'.$_POST['image'];
    $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
    $config2['maintain_ratio'] = TRUE;
    $config2['max_size'] = '1024';
    $config2['width'] = "200";
    $config2['height'] = "300";

    $this->image_lib->initialize($config2);
    if(!$this->image_lib->resize())
    {
      echo('<pre>');
      echo ($this->image_lib->display_errors());
    }
    else
    { 
      $image= $_POST['image'];
    }
    $data = array(
              'date' => ucfirst($this->input->post('date',TRUE)),
              'description' =>$this->input->post('description',TRUE),
              'image' => $image,
              'modified'=> date('Y-m-d H:i:s'),                
            );
    $this->Crud_model->SaveData("asset_multiple_images",$data,"id='".$id."'");
    unlink('uploads/assets_images/'.$_POST['oldimage']); 
  }
  else
  {
    $image = $this->input->post('oldimage');
  }
  $data = array(
                  'date' => ucfirst($this->input->post('date',TRUE)),
                  'description' =>$this->input->post('description',TRUE),
                  'image' => $image,
                  'modified'=> date('Y-m-d H:i:s'),       
                );
  //print_r($data);exit;
  $this->Crud_model->SaveData('asset_multiple_images',$data,$con);
  if($_FILES['image']['error']==0)
  {
    unlink('uploads/assets_images/'.$_POST['oldimage']); 
  } 
  $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record updated successfully</p></div>');
  redirect('sales_return/AssetImagesList/'.$asset_id.'/'.$asset_details_id);
 
}

public function delete_assets_images($asset_id,$asset_details_id)
{
  $con = "id='".$_POST['id']."'";
  $row = $this->Crud_model->GetData('asset_multiple_images','',$con,'','','','1'); 
  unlink('uploads/assets_images/'.$row->image);
  $this->Crud_model->DeleteData('asset_multiple_images',$con);
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
  redirect('sales_return/assetImagesList/'.$asset_id.'/'.$asset_details_id);
}

  public function req_new_asset_list()
    {
      // print_r($_SESSION[SESSION_NAME]['getMenus']);exit;
        if(!empty($_SESSION[SESSION_NAME]['getMenus']))
          {  

              foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
              { 
                foreach($row as $menu)
                { 
                    if($menu->value=='Assets_request')
                    { 
                      
                      if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                    }
                }
              }  

               $breadcrumbs = "<ul class='breadcrumb'>
                          <li>
                              <i class='ace-icon fa fa-home home-icon'></i>
                              <a href='".site_url('Dashboard')."'>Dashboard</a>
                          </li>
                         
                          <li class='active'>Request New Asset List</li>
                          </ul>";

                $data=array(
                  'heading' => 'Request New Asset List',
                  'breadcrumbs' => $breadcrumbs,
                   'addPermission'=>$add
                
                  );

              $this->load->view('assets/req_new_asset_list',$data); 
            }
            else
            {
              redirect('Dashboard/index');
            }            

    }  
     public function req_new_asset_listAjax()
    {


      $con="branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'";
      $Data = $this->Req_new_assets_list_model->get_datatables($con); 

      foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
      { 
        foreach($menu as $row)
        {

            if($row->value=='Assets_request')
            { 
              if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
              if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
              if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            }
        }
      }
       
       $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
           $status ='';
           if(!empty($actstatus))
           { 
             if($row->status=='Pending')
              {
                $status =  "<span  class='label-warning label'> Pending </span>";            
              }
              elseif($row->status=='Approved')
              {
                $status =  "<span class='label-success label'> Approved </span>";
              }elseif($row->status=='Rejected')
              {
                $status =  "<span class='label-danger label'> Rejected </span>";
              }
            }  

            $total_assets="<a href=".site_url('sales_return/assets_request_details/'.$row->id)."><span class='label-info label'>".$row->total_assets."</span></a>";


           if($row->status=='Pending')    
           {  $btn='';
               if(!empty($edit)){
                 $btn = ('<a href='.site_url("sales_return/update_asset_request/".$row->id).' class="btn btn-info btn-circle btn-sm" title="Edit"><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
                }
               if(!empty($delete)){
                $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
               } 
            }
            else
            {
              $btn='-';
            }  

            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->request_no;
            $nestedData[] = $total_assets;
            $nestedData[] = $status;
            $nestedData[] = $btn;
            $data[] = $nestedData;
            $selected = '';
        }
       
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Req_new_assets_list_model->count_all($con),
                    "recordsFiltered" => $this->Req_new_assets_list_model->count_all($con),
                    "data" => $data,
                    
                );
       
        echo json_encode($output);
      
    } 

     public function req_new_asset()
    {
       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li>
                        <a href='".site_url('sales_return/req_new_asset_list')."'>Request New Asset List</a>
                    </li>
                    <li class='active'>Request New Asset</li>
                    </ul>";
          $action =  site_url("sales_return/new_asset_action");             
          $data=array(
            'heading' => 'Request New Asset',
            'breadcrumbs' => $breadcrumbs,
            'action' => $action,
            'button'   => 'Request New Asset',
            );

      $this->load->view('assets/req_new_asset',$data);
    }    
    public function new_asset_action()
    {
        $nyr = date('y')+1;
        $no = 0;
        $no = ++$no;
        $assets_requests = $this->Crud_model->GetData("assets_requests",'',"","","id desc");
        if(!empty($assets_requests))
        {
          $cno = explode("/", $assets_requests[0]->request_no);
        }

        if(!empty($assets_requests[0]->request_no))
        {

            $request_no = 'AR/'.date('y').'-'.$nyr.'/000'.($cno[2]+1); 
        }
        else
        {
            $no = 0;
            $no = ++$no;
            $request_no = 'AR/'.date('y').'-'.$nyr.'/000'.$no; 
        }

        $asset_name_count = count($this->input->post('asset_name[]'));

        $quantity=$this->input->post('quantity[]');
       

        $quantity_sum=array_sum($quantity);

         $data = array(                
          'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
          'request_no'=>$request_no,          
          'total_assets'=>$asset_name_count,          
          'total_quantity'=>$quantity_sum,          
          'created_by'=>$_SESSION[SESSION_NAME]['id'],  
          'status'=>'Pending',     
          'approved_quantity' => '0',     
          'created'=>date('Y-m-d H:i:s'),            
          );
     
      $this->Crud_model->SaveData("assets_requests",$data);
      $last_id=$this->db->insert_id();

      
       for($i=0;$i<$asset_name_count;$i++)
      {
        if($_POST['asset_name'][$i]!='')
        {
          $assets = $this->Crud_model->GetData('assets',"id","asset_name='".$_POST['asset_name'][$i]."'",'','','','1');

            if(empty($assets))
            {
              $asset_id='0';
            }  
            else
            {
               $asset_id=$assets->id;
            }  

            if($_POST['description'][$i] =='')
            {
              $description ='';
            } 
            else
            {
              $description =$_POST['description'][$i];
            } 

          $datadetail = array(
            'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
            'asset_id'=>$asset_id,
            'description'=>$description,
            'status'=>'Pending',
            'asset_name'=>$_POST['asset_name'][$i],          
            'quantity'=>$_POST['quantity'][$i],          
            'created_by'=>$_SESSION[SESSION_NAME]['id'],            
            'asset_request_id' => $last_id,
           
            'created'=> date('Y-m-d H:i:s'),
          
          );
          $this->Crud_model->SaveData("assets_request_details",$datadetail);
        }
      }
        $this->session->set_flashdata('message', '<span class="label label-success">Request send successfully</span>');
        redirect('sales_return/req_new_asset_list');
    }

       public function assets_request_details($request_id)
  {
    if(!empty($request_id))
    {  
      $assets_requests = $this->Crud_model->GetData('assets_requests','branch_id,request_no',"id='".$request_id."'",'','','','1');
      $assets_request_details = $this->Crud_model->GetData('assets_request_details','id',"asset_request_id='".$request_id."' and status!='Pending'");
      if(!empty($assets_request_details))
      {  

        foreach ($assets_request_details as $req ) {
          $data = array(
              'is_read' => 'Yes',
          );
        $this->Crud_model->SaveData("assets_request_details",$data,"id='".$req->id."'");
        }
      }  

      if(!empty($assets_requests))
      {  
          $heading=  'sales_return Request of '.$assets_requests->request_no;   
          $breadcrumbs = "<ul class='breadcrumb'>
                        <li>
                            <i class='ace-icon fa fa-home home-icon'></i>
                            <a href='".site_url('Dashboard')."'>Dashboard</a>
                        </li>
                        <li class='active'><a href='".site_url('sales_return/req_new_asset_list')."'>Request New Product List</a></li>
                        <li class='active'>".$heading."</li>
                        </ul>";              
                    
           
          $data = array('breadcrumbs' => $breadcrumbs ,'heading' => $heading,'request_id' => $request_id);

          $this->load->view('assets/assets_request_detail1',$data);
      }
      else
      {
        redirect('sales_return/req_new_asset_list');
      }    
    }
    else 
    {
       redirect('sales_return/req_new_asset_list');
    }  
  }
  public function ajax_request_detail($request_id)
  {
    $con="ard.asset_request_id='".$request_id."'";
    $Data = $this->Assets_request_details_model1->get_datatables($con); 
    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
       $no++;
      $quantity="<span class='badge badge-info'>".$row->quantity."</span>";
       if($row->status=='Pending')
      {
                  
          $status =  "<span  class='label-warning label'> Pending </span>";            
      }
      else if($row->status=='Approved')
      {
          $status =  "<span class='label-success label'> Approved </span>";
      }else if($row->status=='Rejected')
      {
          $status =  "<span class='label-danger label'> Rejected </span>";
      }
     

     if($row->asset_id == '0')
      {
          $asset_name=$row->asset_name.'&nbsp;&nbsp;'.'<span class="label label-danger">New</span>';
      }  
      else
      {
        $asset_name=$row->asset_name;
      }       

      if($row->approved_quantity=='0')
      {
        $approved_quantity='-';
      } 
      else
      {
        $approved_quantity='<span class="label label-success">'.$row->approved_quantity.'</span>';
      } 


      if(!empty($row->description))
      {
          if(strlen($row->description)>25) 
          { 
            $description=substr($row->description, 0, 20).'<div data-toggle="modal" data-target="#myModal" style="cursor:pointer;color:lightblue" onclick="return getData('.$row->id.')">...read more</div>';
          } 
          else 
          {
            $description=$row->description; 
          }
      }  
      else
      {
        $description='-';
      }  

       
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = ucwords($asset_name);
        $nestedData[] = $quantity;
        $nestedData[] = $approved_quantity;
        $nestedData[] = $description;
        $nestedData[] = $status;
        $data[] = $nestedData;
        $selected = '';
    }
  
    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Assets_request_details_model1->count_all($con),
                "recordsFiltered" => $this->Assets_request_details_model1->count_filtered($con),
                "data" => $data,
               
            );
   
    echo json_encode($output);
  }

   public function update_asset_request($request_id)
    {
      if(!empty($request_id))
      {
        $assets_requests = $this->Crud_model->GetData('assets_requests',"","id='".$request_id."'",'','','','1');
        if(!empty($assets_requests))
        {

          $assets_request_details = $this->Crud_model->GetData('assets_request_details',"","asset_request_id='".$request_id."' and status='Pending'");


            $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li>
                        <a href='".site_url('sales_return/req_new_asset_list')."'>Request New Asset List</a>
                    </li>
                    <li class='active'>Update New Asset</li>
                    </ul>";
                $action =  site_url("sales_return/update_asset_action/".$request_id);             
                $data=array(
                  'heading' => 'Update Product Request',
                  'breadcrumbs' => $breadcrumbs,
                  'assets_request_details' => $assets_request_details,
                  'action' => $action,
                  'button'   => 'Update Product Request',
                  );

            $this->load->view('assets/update_req_asset',$data);
        } 
        else
        {
          redirect('sales_return');
        }  

      } 
      else
      {
        redirect('sales_return');
      } 
    }
    public function update_asset_action($request_id)
    {
        $asset_name_count = count($this->input->post('asset_name[]'));
        $quantity=$this->input->post('quantity[]');
        $quantity_sum=array_sum($quantity);

         $data = array(                
          'total_assets'=>$asset_name_count,          
          'total_quantity'=>$quantity_sum,          
          'status'=>'Pending',     
          'approved_quantity' => '0',     
          'created'=>date('Y-m-d H:i:s'),            
          );
     
      $this->Crud_model->SaveData("assets_requests",$data,"id='".$request_id."'");
      

      $con  = "asset_request_id = '".$request_id."'";
      $this->Crud_model->DeleteData('assets_request_details',$con);

      
       for($i=0;$i<$asset_name_count;$i++)
      {
        if($_POST['asset_name'][$i]!='')
        {
          $assets = $this->Crud_model->GetData('assets',"id","asset_name='".$_POST['asset_name'][$i]."'",'','','','1');

            if(empty($assets))
            {
              $asset_id='0';
            }  
            else
            {
               $asset_id=$assets->id;
            }  

            if($_POST['description'][$i] =='')
            {
              $description ='';
            } 
            else
            {
              $description =$_POST['description'][$i];
            } 

          $datadetail = array(
            'branch_id'=>$_SESSION[SESSION_NAME]['branch_id'],
            'asset_id'=>$asset_id,
            'description'=>$description,
            'status'=>'Pending',
            'asset_name'=>$_POST['asset_name'][$i],          
            'quantity'=>$_POST['quantity'][$i],          
            'created_by'=>$_SESSION[SESSION_NAME]['id'],            
            'asset_request_id' => $request_id,
           
            'created'=> date('Y-m-d H:i:s'),
          
          );
          $this->Crud_model->SaveData("assets_request_details",$datadetail);
        }
      }
        $this->session->set_flashdata('message', '<span class="label label-success">Request send successfully</span>');
        redirect('sales_return/req_new_asset_list');
    }

    public function assets_req_delete()
      {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('assets_requests',$con);
        $con = "asset_request_id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('assets_request_details',$con);

        $this->session->set_flashdata('message', 'Request deleted successfully');
        redirect('sales_return/req_new_asset_list');
      }
       public function get_asset_name()
    {
      $assets = $this->Crud_model->GetData('assets','',"asset_name like'".$_REQUEST['search']."%'",'','asset_name ASC','','');

     $json=array();

            foreach($assets as $key){
                $json[]=array('label'=>$key->asset_name);
            }
        
        echo json_encode($json);exit;  
    }  

    public function assetDetailGeoLocation($asset_id,$asset_detail_id)
    {
      if(!empty($asset_id) && !empty($asset_detail_id))
      {
         
          $asset_geo_locations = $this->Crud_model->GetData('asset_geo_locations',"latitude,longitude,asset_detail_id,created","asset_detail_id='".$asset_detail_id."'");
          $count_geo=count($asset_geo_locations);
          
          $asset_details = $this->Crud_model->GetData('asset_details',"barcode_number,asset_id","id='".$asset_geo_locations[0]->asset_detail_id."'",'','','','1');

           $assets = $this->Crud_model->GetData('assets',"asset_name","id='".$asset_details->asset_id."'",'','','','1');
           $image=base_url().'/uploads/marker.png';

          $heading="Location of ".$asset_details->barcode_number ."&nbsp;(&nbsp;".$assets->asset_name."&nbsp;)&nbsp;";

           $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li>
                        <a href='".site_url('sales_return')."'>Manage sales_return</a>
                    </li> 
                    <li>
                        <a href='".site_url('sales_return/view/'.$asset_details->asset_id)."'>View Asset Details</a>
                    </li>
                    <li class='active'>".$heading."</li>
                    </ul>";

          
          
        if(!empty($asset_geo_locations))         
          {  
             $data=array(
             'breadcrumbs' => $breadcrumbs,
             'heading' => $heading,
             'count_geo' => $count_geo,
             'image' => $image,
             'asset_geo_locations' => $asset_geo_locations,
             'barcode_number' => $asset_details->barcode_number,
             );         


            $this->load->view('assets/assetDetailGeoLocation',$data);
        }
        else
         {
          //print_r("expression");exit;
           redirect('sales_return');
         } 
      } 
      else
      { //print_r("expression1");exit;
        redirect('sales_return');
      } 
    }


    public function getGST() {
      $product = $this->input->post('product_id');
      $where = array('id'=> $product);
      $select = $this->Crud_model->GetData("assets","",$where,"","","","1");
      
      if(!empty($select)) {
        $response['success'] = '1';
        $response['gst_percent'] = $select->gst_percent;
        $response['price'] = $select->product_mrp;
        //$response['hsn'] = $select->hsn;
      } else {
        $response['success'] = '0';
      }

      echo json_encode($response);exit;
    }
}
