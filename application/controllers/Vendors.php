<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('image_lib');
    $this->image_lib->resize();  
    $this->load->database();
    $this->load->model(array('Vendors_model','VendorPurchaseOrders_model','VendorPayments_model','VehicleManagements_model'));
  }
  
  public function index($flag='')
  {

     $edit = ''; 
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';
    $act_import='';
    $export_button = '';
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    { 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Vendors')
              { 
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                if(!empty($menu->act_import)){ $act_import='1'; }else{ $act_import='0'; }
               
              }
          }
        }
        $controller_name = $this->uri->segment(1);
        $create =anchor(site_url($controller_name.'/create'),'<span title="Create Vendor" class="fa fa-plus"></span>');
        $import ='<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        $download =' <a  download="vendors.xls" style="color:black;" title="Download Format" href="'. base_url('uploads/vendors_demo_excel/vendors.xls').'"><span class="fa fa-download"></span></a>'; 
        $header = array('page_title'=>'Vendors');

        if(!empty($flag)){
          $heading='Manage Vendors Payments';
        }else{
          $heading='Manage Vendors';
        }

        $breadcrumbs='<ul class="breadcrumb">
        <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="'.site_url('Dashboard/index').'">Dashboard</a>
        </li>
        <li class="active">'.$heading.'</li>
        </ul>';
        $data = array(
          'heading'=>$heading,
          'breadcrumbs'=>$breadcrumbs,
          'createAction'=>site_url('Vendors/create'),
          'changeAction'=>site_url('Vendors/changeStatus'),
          'deleteAction'=>site_url('Vendors/delete'),
          'page_create' =>$create,
          'import' =>$import,
          'download' =>$download,
          'ajax_manage_page' =>site_url('Vendors/ajax_manage_page'),
          'actioncolumn' =>'7',
          'flag'=>$flag,
          'addPermission'=>$add,
          'act_import'=>$act_import,
        );
        $this->load->view('vendor/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }
  }
  public function ajax_manage_page()
  {        
    if(!empty($_GET))
    {
      if(!empty($_GET['flag']))
      {
        $flag = $_GET['flag'];
      } else {
        $flag = '';
      }
    } else {
      $flag = '';
    }  
     $edit = ''; 
    $delete= '';
    $actstatus= '';
    $add = '';
    $act_add_existing_stock = '';
    $act_log_details = '';
    $act_transfer = '';
    $edit = '';
    $view = '';
    $act_import='';
    $export_button = '';

    $AllData = $this->Vendors_model->get_datatables($flag);
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Vendors')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
            if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
            if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
          }
      }
    }
    $no = 0;        
    $data = array();        
    foreach ($AllData as $rowData) 
    {
      $btn = '';
      if(!empty($view)){
      $btn .= anchor(site_url('Vendors/read/'.$rowData->id),'<span title="View" class="btn btn-primary btn-circle btn-sm"><i class="fa fa-eye"></i></span>');
      }
      if(!empty($edit)){
      $btn.= ' | '.anchor(site_url('Vendors/update/'.$rowData->id),'<span title="Update" class="btn btn-info btn-circle btn-sm"><i class="fa fa-pencil"></i></span>');
      }
      $status = '';
      if(!empty($actstatus)){
        if($rowData->status=='Active')
        {
          $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$rowData->id.")'> Active </a>";    
        }
        else
        {
          $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$rowData->id.")'> Inactive </a>";
        }                    
      }  
      if(!empty($rowData->name)){ $name=$rowData->name; }else{ $name='N/A'; }
      if(!empty($rowData->email)){ $email=$rowData->email; }else{ $email='N/A'; }
      if(!empty($rowData->mobile)){ $mobile=$rowData->mobile; }else{ $mobile='N/A'; }

      $no++;
      $nestedData = array();
      $nestedData[] = $no; 
      $nestedData[] = ucwords($name);                      
      $nestedData[] = ucwords($rowData->shop_name);                      
      $nestedData[] = $email;
      $nestedData[] = $mobile;
      $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($rowData->bal,2);
      $nestedData[] = $status;
      $nestedData[] = $btn;
      $data[] = $nestedData;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Vendors_model->count_all($flag),
      "recordsFiltered" => $this->Vendors_model->count_filtered($flag),
      "data" => $data,
    );
        //output to json format
    echo json_encode($output);       
  }

/*===CREATE===*/
  public function create()
  {   
    $assets_type=$this->Crud_model->GetData('mst_asset_types','',"status = 'Active' and is_delete ='No'",'','type');
    $countries=$this->Crud_model->GetData('mst_countries','',"status = 'Active'");
    $states=$this->Crud_model->GetData('mst_states','',"status = 'Active'");
    $cities=$this->Crud_model->GetData('mst_cities','',"status = 'Active'");
    
    $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
    <li class="active"><a >Create Vendor</a></li>
    </ul>';

    $data = array('heading'=>'Create Vendor',
            'subheading'=>'Create Vendor',
            'button'=>'Create',
            'breadcrumbs'=>$breadcrumbs, 
            'action'=>site_url('Vendors/create_action'),
            'id' =>set_value('id'),
            'name' =>set_value('name'),
            'shop_name' =>set_value('shop_name'),                    
            'email' =>set_value('email'),
            'mobile' =>set_value('mobile'),
            'address' =>set_value('address'),
            'countries'=>$countries,
            'states'=>$states,
            'cities'=> $cities,
            'pincode' =>set_value('pincode'),
            'gst_no' =>set_value('gst_no'),
            'bank_account_name' => set_value('bank_account_name'),
            'account_type' => set_value('account_type'),
            'bank_account_no' =>set_value('bank_account_no'),
            'bank_name' =>set_value('bank_name'),
            'bank_ifsc_code' =>set_value('bank_ifsc_code'),
            'payment_term' =>set_value('payment_term'),
            'country_id' =>set_value('country_id'),
            'state_id' =>set_value('state_id'),
            'city_id' =>set_value('city_id'),
            'assets_type' =>$assets_type,
        );        
      $this->load->view('vendor/form',$data);        
  }

  public function create_action()
  {
      
                    
          $data = array(      
              'name' => ucfirst($this->input->post('name',TRUE)),          
              'shop_name' => ucfirst($this->input->post('shop_name',TRUE)),          
              'email' => $this->input->post('email',TRUE),
              'mobile' => $this->input->post('mobile',TRUE),
              'address' => ucfirst($this->input->post('address',TRUE)),
              'city_id' =>$this->input->post('city_id',TRUE),
              'state_id' =>$this->input->post('state_id',TRUE),
              'country_id' =>$this->input->post('country_id',TRUE),
              'pincode' => $this->input->post('pincode',TRUE),
              'gst_no' => $this->input->post('gst_no',TRUE),
              'bank_account_name' =>$this->input->post('bank_account_name',TRUE),
              'account_type' =>$this->input->post('account_type',TRUE),
              'bank_account_no' =>$this->input->post('bank_account_no',TRUE),
              'bank_name' => ucfirst($this->input->post('bank_name',TRUE)),
              'bank_ifsc_code' => ucfirst($this->input->post('bank_ifsc_code',TRUE)),
              'payment_term' => ucfirst($this->input->post('payment_term',TRUE)),
          );           
          
          $this->Crud_model->SaveData('vendors',$data);

          $last_id = $this->db->insert_id();

          $count = count($this->input->post('category'));
          if(!empty($count))
          {
          for($i=0;$i<$count;$i++){
                  
                       $dataCat = array(
                          'vendor_id'=>$last_id,   
                          'asset_type_id'=>$_POST['category'][$i],            
                          'created'=>Date('Y-m-d H:i:s'),
                          'modified'=>Date('Y-m-d H:i:s'),
                      );
              $this->Crud_model->SaveData("vendor_asset_type_map",$dataCat);                    
          }
        }
          $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record created successfully</p></div>');
          redirect(site_url('Vendors/index'));  
     
  }

/*===CREATE===*/
/*===Update===*/
  public function update($id)
  {   
    $sql_checked = $this->db->query("select mst_asset_types.* from mst_asset_types where id in (select asset_type_id from vendor_asset_type_map where vendor_id='".$id."') and mst_asset_types.status = 'Active'  and is_delete ='No'");
    $categories_checked = $sql_checked->result();
      //print_r($categories_checked);exit;
    $assets_type=$this->Crud_model->GetData('mst_asset_types','',"status = 'Active' and is_delete ='No'",'','type');

     $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
    <li class="active"><a >Update Vendor</a></li>
    </ul>';

    $vendor = $this->Crud_model->GetData('vendors','',"id='".$id."'",'','','','single');
    $countries=$this->Crud_model->GetData('mst_countries','',"status = 'Active'");
    $states=$this->Crud_model->GetData('mst_states','',"status = 'Active' and country_id='".$vendor->country_id."'",'','');
     //  print_r($vendor);exit;
    $cities=$this->Crud_model->GetData('mst_cities','',"status = 'Active' and state_id='".$vendor->state_id."'",'','');        
      $data = array(
          'heading'=>'Update Vendor',
          'subheading'=>'Update Vendor',
          'button'=>'Update',
          'breadcrumbs'=>$breadcrumbs, 
          'action'=>site_url('Vendors/update_action/'.$id),
          'id' => $id,
          'name' => $vendor->name,
          'shop_name' => $vendor->shop_name,
          'email' => $vendor->email,
          'mobile' => $vendor->mobile,
          'address' => $vendor->address,
          'state_id' => $vendor->state_id,
          'country_id' => $vendor->country_id,
          'city_id' => $vendor->city_id,
          'pincode' => $vendor->pincode,
          'gst_no' => $vendor->gst_no,
          'bank_account_name' => $vendor->bank_account_name,
          'account_type' => $vendor->account_type,
          'bank_account_no' => $vendor->bank_account_no,
          'bank_name' => $vendor->bank_name,
          'bank_ifsc_code' => $vendor->bank_ifsc_code,
          'payment_term' => $vendor->payment_term,
          'countries'=>$countries,
          'states'=>$states,
          'cities'=>$cities,    
          'assets_type' =>$assets_type,                           
          //'categories_checked' => $categories_checked
      );        
      $this->load->view('vendor/form',$data);        
  }

  public function update_action($id)
  {     
  

        $data = array(      
              'name' => ucfirst($this->input->post('name',TRUE)),          
              'shop_name' => ucfirst($this->input->post('shop_name',TRUE)),          
              'email' => $this->input->post('email',TRUE),
              'mobile' => $this->input->post('mobile',TRUE),
              'address' => ucfirst($this->input->post('address',TRUE)),
              'state_id' => $this->input->post('state_id',TRUE),
              'city_id' => $this->input->post('city_id',TRUE),
              'country_id' =>$this->input->post('country_id',TRUE),
              'pincode' => $this->input->post('pincode',TRUE),
              'gst_no' => $this->input->post('gst_no',TRUE),
              'bank_account_name' => ucfirst($this->input->post('bank_account_name',TRUE)),
              'account_type' => ucfirst($this->input->post('account_type',TRUE)),
              'bank_account_no' => $this->input->post('bank_account_no',TRUE),
              'bank_name' => ucfirst($this->input->post('bank_name',TRUE)),
              'bank_ifsc_code' => $this->input->post('bank_ifsc_code',TRUE),
              'payment_term' => ucfirst($this->input->post('payment_term',TRUE)),
              'modified'=> date('Y-m-d H:i:s'),
          ); 
      
        $this->Crud_model->SaveData('vendors',$data,"id='".$id."'");
        $this->Crud_model->DeleteData('vendor_asset_type_map',"vendor_id='".$id."'");
        $count = count($this->input->post('category'));      
        for($i=0;$i<$count;$i++)
        {
          $dataCat = array(
            'vendor_id'=>$id,   
            'asset_type_id'=>$_POST['category'][$i],            
            'created'=>Date('Y-m-d H:i:s'),
            'modified'=>Date('Y-m-d H:i:s'),
            );
          $this->Crud_model->SaveData("vendor_asset_type_map",$dataCat);                    
        }

        $this->session->set_flashdata('message', '<div class="label label-success text-center" style="margin-bottom:0px;"><p>Record updated successfully</p></div>');
        redirect(site_url('Vendors/index'));        
    
  }
/*===Update===*/
/*===Read===*/
public function read($id)
  {
    $assetTypeData = $this->Vendors_model->assetTypeData($id);
    $row = $this->Vendors_model->alldata("v.id='".$id."'"); 
    $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
    <li class="active"><a >Vendor Details</a></li>
    </ul>';
    if(empty($row)){
    redirect(site_url('Vendors/index'));
    }
        $data=array(
        'heading'=>'Vendor Details',
        'subheading'=>'View Vendors',
        'breadcrumbs'=>$breadcrumbs, 
        'id'=>$row->id,             
        'shop_name'=>ucwords($row->shop_name),
        'name'=>$row->name,
        'email'=>$row->email,
        'mobile'=>$row->mobile,             
        'address'=>$row->address, 
        'country'=>$row->country_name, 
        'state'=>$row->state_name, 
        'city'=>$row->city_name,    
        'pincode'=>$row->pincode,   
        'assetTypeData' => $assetTypeData,
        'gst_no'=>$row->gst_no, 
        'bank_account_name'=>$row->bank_account_name,
        'account_type'=>$row->account_type,
        'bank_account_no'=>$row->bank_account_no,   
        'bank_name'=>$row->bank_name,   
        'bank_ifsc_code'=>$row->bank_ifsc_code, 
        'payment_term'=>$row->payment_term,
          );
        $this->load->view('vendor/view',$data);           
    }
/*===Read===*/




  public function import()
    {   
          $this->db->truncate('temp_vendors');
          $file = $_FILES['excel_file']['tmp_name'];
          $this->load->library('excel');

          $objPHPExcel = PHPExcel_IOFactory::load($file);
          $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true);
          $arrayCount = count($allDataInSheet);

          $i = 1;
          foreach ($allDataInSheet as $val) 
          {
            if ($i == 1) 
            {
            } else {
              $fields_fun[] = $val;
            }
            $i++;
          }  
          if(!isset($fields_fun))
          {

            $this->session->set_flashdata('message', '<div class="alert alert-block alert-danger text-center" style="margin-bottom:0px;"><p>Excel Sheet is blank</p></div>');
            redirect(site_url('Vendors/index'));

          }      
          $data = $fields_fun;

          $exists = 0;
          $blank= '';


          foreach ($data as $val) 
          {

            if(isset($val[0]) && isset($val[1]) && isset($val[3]) && isset($val[4]) && isset($val[5])&& isset($val[6]) && isset($val[7])  && isset($val[8])&& isset($val[9])&& isset($val[10]) && isset($val[11]) && isset($val[12]) && isset($val[13]))
            {

              $getVendorsEmail = $this->Crud_model->GetData('vendors','',"email='".$val[2]."'",'','','','single');

              if(!preg_match("/^[A-Za-z' ]*$/",$val[1]) || !filter_var($val[2],FILTER_VALIDATE_EMAIL) || !preg_match("/^[0-9]*$/",$val[3]) || !preg_match("/^[0-9]*$/",$val[7]) || !preg_match("/^[0-9]*$/",$val[9]) || !preg_match("/^[A-Za-z' ]*$/",$val[13]))
              {
                $type = "Invalid Entry";
                $DuplicateVendor = array(
                  'name' => ucfirst($val[1]),          
                  'shop_name' => ucfirst($val[0]),          
                  'email' => $val[2],
                  'mobile' => $val[3],
                  'address' => ucfirst($val[4]),
                  'city_city_id' =>$val[6],
                  'state_state_id' =>$val[5],
                  'pincode' => $val[7],
                  'gst_no' => $val[8],
                  'bank_account_no' =>$val[9],
                  'bank_name' => $val[10],
                  'bank_ifsc_code' => $val[11],
                  'payment_term' => $val[12],
                  'bank_account_name' => $val[13],
                  'type'=>$type,
                );
                $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor);

              } 
              else
              {


                if(empty($getVendorsEmail) )
                { 
                  $saveEmail = $val[2];

                }
                else
                {
                 $type = "Duplicate Email";
                 $DuplicateVendor = array(
                  'name' => ucfirst($val[1]),          
                  'shop_name' => ucfirst($val[0]),          
                  'email' => $val[2],
                  'mobile' => $val[3],
                  'address' => ucfirst($val[4]),
                  'city_city_id' =>$val[6],
                  'state_state_id' =>$val[5],
                  'pincode' => $val[7],
                  'gst_no' => $val[8],
                  'bank_account_no' =>$val[9],
                  'bank_name' => $val[10],
                  'bank_ifsc_code' => $val[11],
                  'payment_term' => $val[12],
                  'bank_account_name' => $val[13],
                  'type'=>$type,
                );
                 $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor);               
               }

               $getVendorsMobile = $this->Crud_model->GetData('vendors','',"mobile='".$val[3]."' ",'','','','single');

               if(empty($getVendorsMobile))
               { 
                $saveMobile =  $val[3];              
              }else{ 
               $type = "Duplicate Mobile";
               $DuplicateVendor = array(
                'name' => ucfirst($val[1]),          
                'shop_name' => ucfirst($val[0]),          
                'email' => $val[2],
                'mobile' => $val[3],
                'address' => ucfirst($val[4]),
                'city_city_id' =>$val[6],
                'state_state_id' =>$val[5],
                'pincode' => $val[7],
                'gst_no' => $val[8],
                'bank_account_no' =>$val[9],
                'bank_name' => $val[10],
                'bank_ifsc_code' => $val[11],
                'payment_term' => $val[12],
                'bank_account_name' => $val[13],
                'type'=>$type,
              );
               $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor); 
             }

             $getState123 = $this->db->query("select * from mst_states where state_name='".trim($val[5])."'")->row();

             if(empty($getState123) )
             { 
               $type = "Invalid State";
               $DuplicateVendor = array(
                'name' => ucfirst($val[1]),          
                'shop_name' => ucfirst($val[0]),          
                'email' => $val[2],
                'mobile' => $val[3],
                'address' => ucfirst($val[4]),
                'city_city_id' =>$val[6],
                'state_state_id' =>$val[5],
                'pincode' => $val[7],
                'gst_no' => $val[8],
                'bank_account_no' =>$val[9],
                'bank_name' => $val[10],
                'bank_ifsc_code' => $val[11],
                'payment_term' => $val[12],
                'bank_account_name' => $val[13],
                'type'=>$type,
              );
               $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor); 
             }else{  
              $saveState = $getState123->id;
              $savecountry = $getState123->country_id;
              $getcity = $this->Crud_model->GetData('mst_cities','',"city_name='".$val[6]."' and state_id='".$getState123->id."' ",'','','','single');

              if(empty($getcity))
              { 
                $type = "Invalid City";
                $DuplicateVendor = array(
                  'name' => ucfirst($val[1]),          
                  'shop_name' => ucfirst($val[0]),          
                  'email' => $val[2],
                  'mobile' => $val[3],
                  'address' => ucfirst($val[4]),
                  'city_city_id' =>$val[6],
                  'state_state_id' =>$val[5],
                  'pincode' => $val[7],
                  'gst_no' => $val[8],
                  'bank_account_no' =>$val[9],
                  'bank_name' => $val[10],
                  'bank_ifsc_code' => $val[11],
                  'payment_term' => $val[12],
                  'bank_account_name' => $val[13],
                  'type'=>$type,
                );
                $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor); 
              }else{ 
                $saveCity = $getcity->id;
              }                  
            } 
          }

          if(empty($DuplicateVendor))
          {            
            $dataVendor = array(
              'name' => ucfirst($val[1]),          
              'shop_name' => ucfirst($val[0]),          
              'email' => $val[2],
              'mobile' => $val[3],
              'address' => ucfirst($val[4]),
              'city_id' =>$saveCity,
              'country_id' =>$savecountry,
              'state_id' =>$saveState,
              'pincode' => $val[7],
              'gst_no' => $val[8],
              'bank_account_no' =>$val[9],
              'bank_name' => ucfirst($val[10]),
              'bank_ifsc_code' => ucfirst($val[11]),
              'payment_term' => ucfirst($val[12]),
              'bank_account_name' => ucfirst($val[13]),
            );

            $this->Crud_model->SaveData('vendors',$dataVendor);
          }
        }
        else
        {

          if(isset($val[0]) && !empty($val[0]))
          {
            $val[0] = $val[0];
          }
          else
          {
            $val[0]='';
          }
          if(isset($val[1]) && !empty($val[1]))
          {
            $val[1] = $val[1];
          }
          else
          {
            $val[1]='';
          }
          if(isset($val[2]) && !empty($val[2]))
          {
            $val[2] = $val[2];
          }
          else
          {
            $val[2]='';
          }
          if(isset($val[3]) && !empty($val[3]))
          {
            $val[3] = $val[3];
          }
          else
          {
            $val[3]='';
          }
          if(isset($val[4]) && !empty($val[4]))
          {
            $val[4] = $val[4];
          }
          else
          {
            $val[4]='';
          }
          if(isset($val[5]) && !empty($val[5]))
          {
            $val[5] = $val[5];
          }
          else
          {
            $val[5]='';
          }
          if(isset($val[6]) && !empty($val[6]))
          {
            $val[6] = $val[6];
          }
          else
          {
            $val[6]='';
          }
          if(isset($val[7]) && !empty($val[7]))
          {
            $val[7] = $val[7];
          }
          else
          {
            $val[7]='';
          }
          if(isset($val[8]) && !empty($val[8]))
          {
            $val[8] = $val[8];
          }
          else
          {
            $val[8]='';
          }
          if(isset($val[9]) && !empty($val[9]))
          {
            $val[9] = $val[9];
          }
          else
          {
            $val[9]='';
          }
          if(isset($val[10]) && !empty($val[10]))
          {
            $val[10] = $val[10];
          }
          else
          {
            $val[10]='';
          }
          if(isset($val[11]) && !empty($val[11]))
          {
            $val[11] = $val[11];
          }
          else
          {
            $val[11]='';
          }
          if(isset($val[12]) && !empty($val[12]))
          {
            $val[12] = $val[12];
          }
          else
          {
            $val[12]='';
          }
          if(isset($val[13]) && !empty($val[13]))
          {
            $val[13] = $val[13];
          }
          else
          {
            $val[13]='';
          }

          $type = "Invalid Entry";
          $DuplicateVendor = array(
            'name' => $val[1],          
            'shop_name' => $val[0],          
            'email' => $val[2],
            'mobile' => $val[3],
            'address' => $val[4],
            'city_city_id' =>$val[5],
            'state_state_id' =>$val[6],
            'pincode' => $val[7],
            'gst_no' => $val[8],
            'bank_account_no' =>$val[9],
            'bank_name' => $val[10],
            'bank_ifsc_code' => $val[11],
            'payment_term' => $val[12],
            'bank_account_name' => $val[13],
            'type'=>$type,
          );
          $this->Crud_model->SaveData('temp_vendors',$DuplicateVendor);


        }

      }    

      $All= $this->Crud_model->GetData("temp_vendors");

      if(empty($All))
      {
        $this->session->set_flashdata('message', '<sapn class="label label-success text-center" style="margin-bottom:0px;">Record imported successfully</span>');
        redirect(site_url('Vendors'));
      }
      else
      {
        $data = array('exitsall' =>$All);
        $this->load->view('vendor/duplicateVendors',$data);
      }       
  }

  public function changeStatus()
  {           
      $row = $this->Crud_model->GetData('vendors','',"id='".$_POST['id']."'",'','','','single');        
      if($row->status=='Active')
      {
          $this->Crud_model->SaveData('vendors',array('status'=>'Inactive'),"id='".$_POST['id']."'");
      }
      else
      {
          $this->Crud_model->SaveData('vendors',array('status'=>'Active'),"id='".$_POST['id']."'");
      }        
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
      redirect(site_url('Vendors/index'));
  }



  public function purchase_order($vid)
  {   
    $vendor = $this->Crud_model->GetData('vendors','',"id='".$vid."'",'','','','single');
    $assets_type = $this->Crud_model->GetData('mst_asset_types','type,id',"id in (select asset_type_id from vendor_asset_type_map where vendor_id='".$vid."') and status='Active' and is_delete='No'");
     $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li ><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
    <li class="active">Vendor Purchase Order</li>
    </ul>';
    $data = array('heading'=>'Vendor Purchase Order','id'=>$vid,'vendor'=>$vendor,'assets_type'=>$assets_type,'breadcrumbs'=>$breadcrumbs);
    $this->load->view('vendor/purchase_order_list',$data);
  }

  public function ajax_purchaseOrder($vid)
  { 
    /* ----- Implemented by shubham chandrhas ----- */
    /* ----- Changes by Prashant (29-7-17) ----- */
    /* ----- Condition for Search start ----- */
    $fromdate = $_POST['SearchData2'];
    $todate = $_POST['SearchData3'];
    $asset_type_id = $_POST['SearchData1'];

    $con = "purchase_orders.vendor_id='".$vid."'";
    if($asset_type_id!='')
    {
        $con .=" and purchase_orders.asset_type_id = '".$asset_type_id."' ";
    }
    
    if($fromdate!='')
    {
        $con .=" and purchase_orders.purchase_date >= '".date('Y-m-d',strtotime($fromdate))."' ";
    }
    if($todate!='')
    {
        $con .=" and purchase_orders.purchase_date <= '".date('Y-m-d',strtotime($todate))."' ";
    }
    /* ----- Condition for Search end ----- */
    
        $AllData = $this->VendorPurchaseOrders_model->get_datatables($con);
        $no = 0;        
        $data = array();        
        foreach ($AllData as $rowData) 
        {
            $btn ='&nbsp;'.anchor(site_url('Vendors/purchase_detail/'.$vid.'/'.$rowData->id),'<button title="Product details" class="btn btn-info btn-circle btn-sm"><i class="fa fa-eye"></i></button>');

            $total = $rowData->sum + $rowData->transport;
            $returnAmt = $this->Crud_model->GetData('purchase_returns','sum(return_amount) as return_amount',"purchase_order_id='".$rowData->id."'",'','','','single');
            $total = $total - $returnAmt->return_amount;
          

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = ucwords($rowData->type);   
            //$nestedData[] = $dist;   
            $nestedData[] = $rowData->order_number;   
            $nestedData[] = $rowData->count;   
            $nestedData[] = date('d-m-Y',strtotime($rowData->purchase_date));
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($total,2);   
            $nestedData[] = ucwords($rowData->status);   
            $nestedData[] = $btn;
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->VendorPurchaseOrders_model->count_all($con),
                    "recordsFiltered" => $this->VendorPurchaseOrders_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);       
  }

  public function purchase_detail($vid,$pid)
  {   
       $breadcrumbs='<ul class="breadcrumb">
    <li>
    <i class="ace-icon fa fa-home home-icon"></i>
    <a href="'.site_url('Dashboard/index').'">Dashboard</a>
    </li>
    <li ><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
    <li class="active">Vendor Purchase datails</li>
    </ul>';
      $po = $this->VendorPurchaseOrders_model->GetPOData("po.id='".$pid."'");
      //print_r($po);exit;
      $purchaseReceived = $this->VendorPurchaseOrders_model->GetBillTransport("pr.purchase_order_id='".$pid."' and vm.purchase_order_id='".$pid."'");        
      $data = array('heading'=>'Vendor Purchase Order','id'=>$pid,'po'=>$po,'purchaseReceived'=>$purchaseReceived,'breadcrumbs'=>$breadcrumbs);
      $this->load->view('vendor/purchase_detail',$data);
  }
  
  public function export_purchaseOrder($id)
  { 
    $to_date = $this->input->post('to-date');
    $from_date = $this->input->post('from-date');
    $asset_type_id = $this->input->post('asset_type_id');
   
    $con = "po.vendor_id='".$id."'";
    if(!empty($asset_type_id)){
      $con .= " and po.asset_type_id='".$asset_type_id."'";
    }
    
    if(!empty($from_date)){
      $con .= " and po.purchase_date >= '".date('Y-m-d',strtotime($from_date))."'";
    }
    if(!empty($to_date)){
      $con .= " and po.purchase_date <= '".date('Y-m-d',strtotime($to_date))."'";
    }
    
    $results = $this->VendorPurchaseOrders_model->ExportCSV($con); 
    //print_r($this->db->last_query()); exit;
    $FileTitle='Purchase Order Report';
        
      $this->load->library('excel');      
      $this->excel->setActiveSheetIndex(0);     
      $this->excel->getActiveSheet()->setTitle('Report Sheet');     
      $this->excel->getActiveSheet()->setCellValue('A1', 'Purchase Order Of : '.ucwords($results[0]->shop_name));
      //$this->excel->getActiveSheet()->setCellValue('A2', 'Warehouse Name : '.$results[0]->warehouse);
      
      $from = date('d-m-Y',strtotime($from_date));
      $to = date('d-m-Y',strtotime($to_date));
      if($from_date!=''  && $to_date!='')
      {
        $this->excel->getActiveSheet()->setCellValue('A3', 'From Date : '.$from.' To Date : '.$to);
      }
      else if($from_date!='')
      {
        $this->excel->getActiveSheet()->setCellValue('A3', 'From Date : '.$from);
      }
      else if($to_date!='')
      {
        $this->excel->getActiveSheet()->setCellValue('A3', 'Till Date : '.$to);
      }

      $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
      $this->excel->getActiveSheet()->setCellValue('B3', 'Assets Type');
      $this->excel->getActiveSheet()->setCellValue('C3', 'Total Item');
      $this->excel->getActiveSheet()->setCellValue('D3', 'PO Number');      
      $this->excel->getActiveSheet()->setCellValue('E3', 'Purchase Date');
      $this->excel->getActiveSheet()->setCellValue('F3', 'Status');
      
      $a='4'; $sr = 1;  
      foreach ($results as $result) 
      {
        $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
        $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->type);
        $this->excel->getActiveSheet()->setCellValue('C'.$a, $result->total_items);
        $this->excel->getActiveSheet()->setCellValue('D'.$a, $result->order_number);        
        $this->excel->getActiveSheet()->setCellValue('E'.$a, date('d-m-Y',strtotime($result->purchase_date)));
        $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->status);        
        $sr++; $a++;        
      }           
          
          $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);         
          $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);         
          $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);         
          $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);         
          $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);         
          $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);         
          $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(12);         
          $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
          $this->excel->getActiveSheet()->mergeCells('A1:G1');
          $this->excel->getActiveSheet()->mergeCells('A2:G2');
          
          $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);          
      $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache
      ob_clean();             
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
      //force user to download the Excel file without writing it to server's HD
      $objWriter->save('php://output'); 
  }
    
  

  public function vendorPayment($vid)
  {   
        $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li ><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
            <li class="active">Vendor Purchase Order</li>
            </ul>';
      $vendor = $this->Crud_model->GetData('vendors','',"id='".$vid."'",'','','','single');
      if(empty($vendor)){ redirect('Vendors'); }
      $data = array('heading'=>'Vendor Payments ','id'=>$vid,'vendor'=>$vendor,'breadcrumbs'=>$breadcrumbs,);
      $this->load->view('vendor/payment_list',$data);
  }

  public function ajax_vendorPayments($vid)
  {  
    /* ----- Implemented by shubham chandrhas ----- */
    /* ----- Changes by Prashant (29-7-17)----- */
    /* ----- Condition for Search start ----- */
    $type = $_POST['SearchData1'];
    $fromdate = $_POST['SearchData2'];
    $todate = $_POST['SearchData3'];
    
    $con = "v.id='".$vid."'";

    if($_POST['SearchData1']!='')
    {
      $con .=" and vt.status = '".$type."' ";
    }
    if($_POST['SearchData2']!='')
    {
      $con .=" and vt.payment_date >= '".date('Y-m-d',strtotime($fromdate))."' ";
    }
    if($_POST['SearchData3']!='')
    {
      $con .=" and vt.payment_date <= '".date('Y-m-d',strtotime($todate))."' ";
    }
    /* ----- Condition for Search end ----- */

    $AllData = $this->VendorPayments_model->get_datatables($con);  
    
        $no = 0;        
        $data = array();        
        foreach ($AllData as $rowData) 
        {
            if(!empty($rowData->order_number)){
                $order = $rowData->order_number;
            } else { 
                $order = '-';
            }

            $no++;
            $nestedData = array();
            $nestedData[] = $no; 
            $nestedData[] = $order;
            $nestedData[] = ucwords($rowData->description);   
            $nestedData[] = date('d-m-Y',strtotime($rowData->payment_date));   
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($rowData->inward,2);
            $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($rowData->balance,2);
            $nestedData[] = ucwords($rowData->status);   
            $data[] = $nestedData;
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->VendorPayments_model->count_all($con),
                    "recordsFiltered" => $this->VendorPayments_model->count_filtered($con),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);     
  }

  public function payment($vid)
  {
      $stk = $this->Crud_model->GetData('vendor_transactions','',"vendor_id='".$vid."'",'','id desc','','single');
      $vendor = $this->Crud_model->GetData('vendors','',"id='".$vid."'",'','','','single');
      $data = array('id'=>$vid,'heading'=>'Vendor Payment','vendor'=>$vendor,'stk'=>$stk,'bread'=>'Create');
      $this->layouts->view('vendors/payment_form',$data);
  }

  public function make_confirm_payment()
  {
      $paymentData = array(
          'vendor_id' => $_POST['vendor_id'],
          'payment_date' => date('Y-m-d'),
          'payment_type' => $_POST['payment_type'],
          'cheque_no' => $_POST['cheque_no'],
          'cheque_date' => $_POST['cheque_date'],
          'bank_name' => $_POST['bank_name'],
          'amount' => $_POST['amount'],
          'status' => 'Paid',
          );            
      $this->Crud_model->SaveData('vendor_payments',$paymentData);
      $PaymentId = $this->db->insert_id();

      $balance = $_POST['balance'] - $_POST['amount'];
      $data = array(
          'payment_id'=>$PaymentId,
          //'purchase_order_id'=>$poid,
          'vendor_id'=>$_POST['vendor_id'],
          'payment_date'=>date('Y-m-d'),
          'description'=>'To '.$_POST['payment_type'],
          'inward'=>'',
          'payment'=>$_POST['amount'],
          'balance'=>$balance,
          'status'=>'Payment',
          );
      $this->Crud_model->SaveData('vendor_transactions',$data);
      redirect('VendorPayments/index/'.$_POST['vendor_id']);
  }

  public function export_vendorPayment($id)
  {
    $from = $this->input->post('from-date');
    $to = $this->input->post('to-date');
    $type = $this->input->post('type');

    $con = "v.id='".$id."'";    
    if($type!='')
    {
      $con .= " and vt.status='".$type."'";
    }
    if($from!='')
    {
      $con .= " and vt.payment_date >='".$from."'";
    }
    if($to!='')
    {
      $con .= " and vt.payment_date <='".$to."'";
    }

    $results = $this->VendorPayments_model->ExportCSV($con);
    $vendor = $this->Crud_model->GetData('vendors','',"id='".$id."'",'','','','single');
      
    $FileTitle='Vendor Payment Report';     
    $this->load->library('excel');
    //activate worksheet number 1
    $this->excel->setActiveSheetIndex(0);
    //name the worksheet
    $this->excel->getActiveSheet()->setTitle('Report Sheet');
    //set cell A1 content with some text
    $this->excel->getActiveSheet()->setCellValue('A1', 'Vendor Payment of '.$vendor->shop_name);    
    $this->excel->getActiveSheet()->setCellValue('A3', 'Sr. No');
    $this->excel->getActiveSheet()->setCellValue('B3', 'PO Number');
    $this->excel->getActiveSheet()->setCellValue('C3', 'Description');
    $this->excel->getActiveSheet()->setCellValue('D3', 'Date');
    $this->excel->getActiveSheet()->setCellValue('E3', 'Amount (Rs.)');
    $this->excel->getActiveSheet()->setCellValue('F3', 'Balance (Rs.)');
    $this->excel->getActiveSheet()->setCellValue('G3', 'Type');     
    
    $a='4'; $sr = 1;  
    foreach ($results as $result) 
    {   
      $this->excel->getActiveSheet()->setCellValue('A'.$a, $sr);
      $this->excel->getActiveSheet()->setCellValue('B'.$a, $result->order_number);
      $this->excel->getActiveSheet()->setCellValue('C'.$a, $result->description);
      $this->excel->getActiveSheet()->setCellValue('D'.$a, date('d-m-Y',strtotime($result->payment_date)));
      $this->excel->getActiveSheet()->setCellValue('E'.$a, $result->inward);
      $this->excel->getActiveSheet()->setCellValue('F'.$a, $result->balance);
      $this->excel->getActiveSheet()->setCellValue('G'.$a, $result->status);
      $sr++; $a++;      
    }
      
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(13);
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(12);
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $filename=''.$FileTitle.'.xls'; //save our workbook as this file name
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    ob_clean();             
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
    $objWriter->save('php://output'); 
  }

  public function Vehicle($vid)
  {   
    $breadcrumbs='<ul class="breadcrumb">
            <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="'.site_url('Dashboard/index').'">Dashboard</a>
            </li>
            <li ><a href="'.site_url('Vendors/index').'">Manage Vendors</a></li>
            <li class="active">Vendor Purchase Order</li>
            </ul>';

    $vendor = $this->Crud_model->GetData('vendors','',"id='".$vid."'",'','','','single');
    $data = array('heading'=>'Vehicle Management ','id'=>$vid,'vendor'=>$vendor,'breadcrumbs'=>$breadcrumbs);
    $this->load->view('vendor/vehicle_list',$data);
  }

  public function ajax_vehicleManagement($vid)
  {   
      $con = "po.vendor_id='".$vid."'";
      $AllData = $this->VehicleManagements_model->get_datatables($con);  
  
      $no = 0;        
      $data = array();        
      foreach ($AllData as $row) 
      {
          $no++;
          $nestedData = array();
          $nestedData[] = $no; 
          $nestedData[] = $row->order_number;   
          $nestedData[] = $row->vehicle_number;   
          $nestedData[] = ucwords($row->driver_name);   
          $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($row->labour_charge,2);
          $nestedData[] = '<i class="fa fa-inr">&nbsp;</i>'.number_format($row->extra_vendor_charge,2);
          $nestedData[] = date('d-m-Y',strtotime($row->created));   
          $data[] = $nestedData;
      }

      $output = array(
                  "draw" => $_POST['draw'],
                  "recordsTotal" => $this->VehicleManagements_model->count_all($con),
                  "recordsFiltered" => $this->VehicleManagements_model->count_filtered($con),
                  "data" => $data,
              );
      //output to json format
      echo json_encode($output);     
  }

  public function GetDistributionCenter()
  {
    $wid = $this->input->post('id');
    $warehouse = $this->Crud_model->GetData('warehouse_distribution_center','',"warehouse_id='".$wid."'");
    $response = '<option value="">Select Distribution Center</option>';
    foreach($warehouse as $row)
    {
      $response .= '<option value="'.$row->id.'">'.$row->distrubution_name.'</option>';
    }  
    echo $response; exit;
  }

}
