<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends CI_Controller {

  function __construct()
  {
	  parent::__construct();
	  $this->load->model('Brand_model');
	  $this->load->library('upload');
    $this->load->library('image_lib');
	  $this->load->database();
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
  if(!empty($_SESSION[SESSION_NAME]['getMenus']))
  {   
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Brands')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                 if(!empty($menu->act_import)){ $import='1'; }else{ $import='0'; }
          }
      }
    }
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                      <i class='ace-icon fa fa-home home-icon'></i>
                      <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Brands</li>
                    </ul>";
    $import_buton ='<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';                

    $manu_data = $this->Crud_model->GetData('manufacturers',"","status='Active'",'',"name asc");
    $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '4' ,'ajax_manage_page' => site_url('Brands/ajax_manage_page'),'heading' => 'Manage Brands','manu_data'=>$manu_data,'addPermission'=>$add,'import' =>$import_buton,'importPermission' =>$import);
    $this->load->view('brands/list',$data);
  }
  else
  {
    redirect('Dashboard');
  }
}

public function ajax_manage_page()
{
  $delete= '';
  $actstatus= '';
  $add = '';
  $act_add_existing_stock = '';
  $act_log_details = '';
  $act_transfer = '';
  $edit = '';
  $view = '';
  $Data = $this->Brand_model->get_datatables(); 
  foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        {
            if($menu->value=='Brands')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }
  $data = array();       
  $no=0; 
  foreach($Data as $row) 
  {  
   
    $btn='';
    if(!empty($edit)){
    $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');"> <i class="ace-icon fa fa-pencil bigger-130"></i></a>');
    }
    if(!empty($delete)){
    $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
    }
    $status ='';
    if(!empty($actstatus)){ 
      if($row->status=='Active')
      {
        $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
      }
      else
      {
        $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
      }
    }
    if(!empty($row->image)) {
      $imagepath = "<img alt='image' src='".base_url('uploads/brands/'.$row->image)."' width='50px' height='50px'/>";
    }else{
      $imagepath = "<img src=".base_url('uploads/employee_images/default.jpg')." width='50px' height='50px'>";
    }

    if(!empty($row->name))
    {
      $name = $row->name;
    }
    else
    {
      $name = '-';
    }

    $no++;
    $nestedData = array();
    $nestedData[] = $no ;
    $nestedData[] = $name;
    $nestedData[] = $row->brand_name;
    $nestedData[] = $imagepath;
    $nestedData[] = $status;            
    $nestedData[] = $btn;       
    $data[] = $nestedData;
    $selected = '';
  }

  $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->Brand_model->count_all(),
              "recordsFiltered" => $this->Brand_model->count_filtered(),
              "data" => $data,
                );
 
  echo json_encode($output);
}

public function create_action()
{
  if($_POST)
  { 

  	$getbrandData = $this->Crud_model->GetData('brands',"","manufacturer_id='".$_POST['manufacturer_id']."' and brand_name='".$_POST['brand_name']."'");
  	//print_r(count($getbrandData));exit;
  	if(!empty($getbrandData))
  	{
  		 $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Brand Name is already exist </span>');
    	redirect('Brands/index');
  	}
  	else
  	{
  			$image="";
    		$config = array(
				            'upload_path'   => getcwd().'/uploads/brands',            
				            'allowed_types' => '*',
				            'overwrite'     => 1,
				            'encrypt_name' => FALSE, 
				              );
           
		    $this->load->library('upload', $config);
		    if(!empty($_FILES['photo']['name']))   
		    {  
		      $fileName = rand(0000,9999) .'_'. $_FILES['photo']['name'];
		      $config['file_name'] = $fileName; 
		      $this->upload->initialize($config);

		      if($this->upload->do_upload('photo')) 
		      { 
		        $data = $this->upload->data();
		        $image=$data['file_name'];
		      }
		      else
		      {
		         $this->session->set_flashdata('message', '<span class="alert alert-success" style="color:red">Error while uploading file</span>');
		      }
		    }

		    $data = array(
		                  'manufacturer_id'=>$_POST['manufacturer_id'],            
		                  'brand_name'=>$_POST['brand_name'],            
		                  'image'=>$image,            
		                  'status'=>'Active',            
		                  'created'=>date('Y-m-d H:i:s'),            
		                );

		    $this->Crud_model->SaveData("brands",$data);      
		    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Brand has been created successfully</span>');
		    redirect('Brands/index');
  	}
   
  }
  else
  {
    redirect('Brands/Create');
  } 
}

public function update_action()
{ 
  if($_FILES['photo_edit']['name']!='')
  {
    $config = array(
    'upload_path'   => getcwd().'/uploads/brands',           
    'allowed_types' => '*',
    'overwrite'     => 1,
    'encrypt_name' => FALSE, 
    );

    $this->load->library('upload', $config);
    if(!empty($_FILES['photo_edit']['name']))   
    {  
      $fileName = rand(0000,9999) .'_'. $_FILES['photo_edit']['name'];
      $config['file_name'] = $fileName; 
      $this->upload->initialize($config);

      if($this->upload->do_upload('photo_edit')) 
      { 
        $data = $this->upload->data();
        unlink('uploads/brands/'.$this->input->post('old_image'));
        $image=$data['file_name'];
      }
      else
      {
         $this->session->set_flashdata('message', '<span class="alert alert-success" style="color:red">Error while uploading file</span>');
      }
    }
  }
  else
  { 
      $image=$this->input->post('old_image');
  } 

  $data = array(
              'manufacturer_id'=>$_POST['manu_id_edit'],            
              'brand_name'=>$_POST['brand_name_edit'],            
              'image'=>$image,                                             
              'modified'=>date('Y-m-d H:i:s'),            
              );                  
  $this->Crud_model->SaveData("brands",$data,"id='".$_POST['updateId']."'");      
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Brand has been updated successfully</span>');
  redirect('Brands/index');
}

public function changeStatus()
{       
  $getbrand_types = $this->Crud_model->GetData('brands','',"id='".$_POST['id']."'",'','','','row');
  if($getbrand_types->status=='Active')
  {
      $this->Crud_model->SaveData('brands',array('status'=>'Inactive'),"id='".$_POST['id']."'");
  }
  else
  {
      $this->Crud_model->SaveData('brands',array('status'=>'Active'),"id='".$_POST['id']."'");
  }
  $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
  redirect(site_url('Brands'));
}

public function getUpdateName()
{
  $rowBrand = $this->Crud_model->GetData('brands','',"id='".$_POST['id']."'",'','','','row'); 
  if(!empty($rowBrand))
  {
    $row['success'] = '1';
    $row['manu_id'] = $rowBrand->manufacturer_id ;
    $row['brand_id'] = $rowBrand->id;
    $row['brand_name'] = $rowBrand->brand_name;
    $row['brand_image'] = $rowBrand->image;            
  }
  else
  {
    $row['success'] = '0';
  }               
  echo json_encode($row);
}

public function delete()
{
  $con = "id='".$_POST['id']."'";              
  $getAssetbrandData = $this->Crud_model->GetData('assets',"id,brand_id","brand_id in ('".$_POST['id']."')");

  if(!empty($getAssetbrandData))
  {
      $this->session->set_flashdata('message', '<span class="label label-danger" style="margin-bottom:0px">Brand already mapped with assets</span>');
      redirect(site_url('Brands'));
  }
  else
  {
      $this->Crud_model->DeleteData('brands',$con);
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Brand has been deleted successfully</span>');
      redirect(site_url('Brands'));
  }
}


 public function savemanufaccturer()
    {
      //print_r($_FILES);exit;
      if($_POST)
      {     
      //print_r($_POST);exit;  
            $image="";
            $config = array(
            'upload_path'   => getcwd().'/uploads/manufacturers',            
            'allowed_types' => '*',
            'overwrite'     => 1,
            'encrypt_name' => FALSE, 
            );
           
            $this->load->library('upload', $config);
            if(!empty($_FILES['photo_modal']['name']))   
            {  
              $fileName = rand(0000,9999) .'_'. $_FILES['photo_modal']['name'];
              $config['file_name'] = $fileName; 
              $this->upload->initialize($config);

              if($this->upload->do_upload('photo_modal')) 
              { 
                $data = $this->upload->data();
                $image=$data['file_name'];
              }
              else
              {
                 $this->session->set_flashdata('message', '<span class="alert alert-success" style="color:red">Error while uploading file</span>');
              }
            }

        $condDuplicationmanu = "name='".$this->input->post('name')."'";
        $duplication = $this->Crud_model->GetData('manufacturers','', $condDuplicationmanu);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
           // print_r($image);exit;
              $data = array(
                          'name'=>$_POST['manu_name_modal'],            
                          'image'=>$image,            
                          'status'=>'Active',            
                          'created'=>date('Y-m-d H:i:s'),            
                        );

            $this->Crud_model->SaveData("manufacturers",$data);   
            $insert_id = $this->db->insert_id();   
            $getlastrow = $this->Crud_model->GetData('manufacturers',"id,name","id='".$insert_id."'",'','','','row');
            $html='';
            $html.="<option value=".$getlastrow->id.">".$getlastrow->name."</option>";
            echo $html;
          }

          

            //$this->session->set_flashdata('message', 'success');
            //redirect('Manufacturers/index');
    }
      else
      {
        //redirect('Manufacturers/Create');
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
            redirect(site_url('Brands/index'));            
        } 
        $data = $fields_fun;
        if(count($val) == '2')
        {
            $exists = 0;
            foreach ($data as $val) 
            {  
                
                      if($val[0]!='' && ($val[1]!=''))
                      {

                         if($val[0]!='')
                        {
                            $getmanufacturers = $this->Crud_model->GetData('manufacturers','',"name='".$val[0]."'",'','','','single'); 
                            if(empty($getmanufacturers))
                            {
                                $getmanufacturers = $this->Crud_model->SaveData('manufacturers',array("name"=>$val[0]));
                                $manuId = $this->db->insert_id();
                            }else{
                                $manuId = $getmanufacturers->id;
                            }
                        }
                        else
                        {
                            $manuId = '';
                        }



                        $brands= $this->Crud_model->GetData('brands','brand_name',"brand_name='".$val[1]."'",'','','','single'); 
                        /*for already exist check*/
                        
                        if(empty($brands))
                        {
                            $data = array(
                                          'brand_name' => $val[1],
                                          'manufacturer_id'=>$manuId,
                                          );
                            $SaveAssets = $this->Crud_model->SaveData('brands',$data);
                        }
                        else
                        { 
                            $existAssets[]=array($val[0],$val[1],'Brand Name already exist');
                        }
                   
                      }
                      else
                      {
                          $existAssets[]=array($val[0],$val[1],'Mandatory fields empty');
                      }
               
            }
          }
        else
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Invalid file selected</span>');
            
            redirect(site_url('Brands/index'));  
        }
        if(empty($existAssets))
        {
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Brand has been imported successfully</span>');
            redirect('Brands/index');
        }
        else{
            $data = array('existAssets' => $existAssets);
            $this->load->view('brands/duplicatBrand',$data);
        } 
}  
}
