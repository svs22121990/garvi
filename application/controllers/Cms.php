<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends CI_Controller {

function __construct()
{
parent::__construct();
$this->load->model('Cms_model');
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
          if($menu->value=='Unit_types')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
            if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
            if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
          }
      }
    }

    $breadcrumbs="<ol class='breadcrumb'>
       <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li>Manage Cms</li>
        </ol>";
    $data=array(
      'heading' => 'Manage Cms',
      'breadcrumbs'=>$breadcrumbs,
      'addPermission'=>$add
    );
    $this->load->view('cms/cms_list',$data);
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
    $getData = $this->Cms_model->get_datatables();
    foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
    { 
      foreach($row as $menu)
      { 
          if($menu->value=='Unit_types')
          { 
            if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
            if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
            if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
            if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
          }
      }
    }
    $data = array();    
    foreach ($getData as $Data) 
    {    
        $btn='';
          if(!empty($edit)){
        $btn ='<span class="action-buttons"><a href="'.site_url('Cms/update/'.base64_encode($Data->id)).'" title="Update"><i class="ace-icon fa fa-pencil bigger-130"></i></a></span>';
           }
          if(!empty($delete)){
        
        $btn .=' &nbsp;'.'<span class="action-buttons"> '.anchor(site_url('Cms/delete/'.base64_encode($Data->id)),'<i class="ace-icon fa fa-trash-o bigger-130 red"></i></span>','onclick="javasciprt: return confirm(\'Are you sure to delete the record?\')" title="Delete"'); 
          }
             $status ='';
             if(!empty($actstatus)){ 
  
         if($Data->status == 'Active')
         {
            $status = '<label class="label label-success arrowed-in arrowed-in-right">Active</label>';
         }
         else
         {
            $status = '<label class="label label-danger arrowed-in arrowed-in-right">Inactive</label>';
         }
           }

        if($Data->media_id != '')
        {
          $medias=$Data->media_id;
          $cond = "id='".$medias."'";
          $mediaimg = $this->Cms_model->GetData('medias','url',$cond,'','','','1');
        }
        else
        {
          $mediaimg='';
        }

        $nestedData = array();
        $nestedData[] = ucwords($Data->title);
        $nestedData[] = $status;
        $nestedData[] = $btn;
        $data[] = $nestedData;
    }

        $condition="";
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Cms_model->count_all('cms',$condition),
          "recordsFiltered" => $this->Cms_model->count_filtered('cms',$condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
}
public function view()
{ 
  $id = $this->input->post("id");
  $table="cms";
  $cond="id='".$id."'";
  $row = $this->Cms_model->GetData($table,'',$cond,'','','','1'); 
  $data = array('row' => $row );
  $this->load->view("cms/cms_view",$data);
}
public function viewData()
    {  
      $id = $this->input->post("id");
      $row = $this->Admin_model->GetData("medias","","id='".$id."'");
     
      $data = array('row' => $row,);
      $this->load->view("cms/image_url",$data);
    }

public function delete_all($id)
{   
   $id = base64_decode($id);

   $this->Cms_model->DeleteData("cms","id='".$id."'");
  
   $this->session->set_flashdata('message', 'Cms has been deleted successfully');
   redirect('Cms');
 
}

public function delete($id)
{ 
  $id = base64_decode($id);
  $table="cms";
  $cond="id='".$id."'";
  $row = $this->Cms_model->GetData($table,'',$cond,'','','','1'); 

  $this->Cms_model->DeleteData("cms","id='".$id."'");
  $this->session->set_flashdata('message', 'Cms has been deleted successfully');
  redirect('Cms');
}
public function deleteImage($id)
{ 
  $id = base64_decode($id);
  $data = array('media_id' => '' );
  $this->Admin_model->SaveData("cms",$data,"id='".$id."'"); 
  $this->session->set_flashdata('message', 'Image has been deleted successfully');
  redirect('Cms/update/'.base64_encode($id));
}
public function create()
{  

   $breadcrumbs="<ol class='breadcrumb'>
       <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li><a href='".site_url('Cms/index')."'>Manage Cms</a></li>
        <li class='active'>Create Cms</li>
        </ol>";

   
    $medias = $this->Admin_model->GetData("medias","","status='Active' and type='image'","","id desc");
    $get_media_id = $this->Admin_model->GetData("medias","id","","","","");
    $data = array(
              'heading'=>'Create Cms',
              'button'=>'Create',
              'breadcrumbs'=>$breadcrumbs,
              'canBtn'=>'Cancel',
              'action'=>site_url('Cms/create_action'),
              'media_id' =>$get_media_id,
              'title' =>set_value('title'),
              'content' =>set_value('content'),
              'featured_image' =>set_value('featured_image'),
              'template' =>set_value('template'),
              'status' =>  set_value('status',$this->input->post('status')),
              'id' =>set_value('id'),
             
              'medias' => $medias
          );

    $this->load->view('cms/cms_form',$data);
}

   public function create_action() 
   { 
      $settings = $this->Admin_model->GetData("settings");
     
      $small_image_dimensions = $settings[0]->small_image_dimensions;
      $explode_small_image_dimensions = explode('_', $small_image_dimensions);
      $small_image_dimensions_width = $explode_small_image_dimensions[0];
      $small_image_dimensions_height = $explode_small_image_dimensions[1]; 

      $thumbnail_image_dimensions = $settings[0]->thumbnail_image_dimensions;
      $explode_thumbnail_image_dimensions = explode('_', $thumbnail_image_dimensions);
      $thumbnail_image_dimensions_width = $explode_thumbnail_image_dimensions[0];
      $thumbnail_image_dimensions_height = $explode_thumbnail_image_dimensions[1];


      $large_image_dimensions = $settings[0]->large_image_dimensions;
      $explode_large_image_dimensions = explode('_', $large_image_dimensions);
      $large_image_dimensions_width = $explode_large_image_dimensions[0];
      $large_image_dimensions_height = $explode_large_image_dimensions[1];


    if($this->input->post('media_id'))
    {
      $media_id = $this->input->post('media_id');
    }
    else
    {
      $media_id= '0';
    }
   
    $id = '0';
    $this->_rules($id);
    if($this->form_validation->run() == FALSE) 
    {  
      $this->create();
  
    } 
    else
    {  
      if(!empty($this->input->post("media_id")))
        {
          $data = array(
                'user_id' => $_SESSION['userdata']['id'],                              
                'title' => $this->input->post('title',TRUE),                              
                'content' => $this->input->post('content',TRUE),                               
                'media_id' => $this->input->post('media_id'),                         
                'template' => $this->input->post('template',TRUE),                               
                'created' => date("Y-m-d H:i:s"),
                 );    
        }
        else
        {
          if($_FILES['featured_image']['name']!='')
          {
             
            $_POST['featured_image']= rand(0000,9999)."_".$large_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $large_image_dimensions_width ;
            $config1['height'] = $large_image_dimensions_height ;
           
           
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else {   
               $featured_image  = $_POST['featured_image'];   
               
           }
          
            $_POST['featured_image']= rand(0000,9999)."_".$thumbnail_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/small_url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $thumbnail_image_dimensions_width;
            $config1['height'] = $thumbnail_image_dimensions_height;
          
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else
            {   
               $featured_image1  = $_POST['featured_image'];  
            } 

            $_POST['featured_image']= rand(0000,9999)."_".$small_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/medium_url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $small_image_dimensions_width;
            $config1['height'] = $small_image_dimensions_height;
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else 
            { 
               $featured_image2  = $_POST['featured_image']; 
            }
             $datamedia = array(
                'url' => $featured_image,
                'small_url' => $featured_image1,
                'medium_url' => $featured_image2, 
                'type' => 'image', 

                );

              $tablename = "medias";
              $this->Admin_model->SaveData($tablename,$datamedia);

              $last_id=$this->db->insert_id();
            
              $data = array(
               'user_id' => $_SESSION['userdata']['id'],                              
               'title' => $this->input->post('title',TRUE),                              
               'content' => $this->input->post('content',TRUE),                               
               'media_id' => $last_id,                          
                'template' => $this->input->post('template',TRUE),                               
               'featured_image' => $featured_image,
               'created'=> date('Y-m-d H:i:s'),
              );  
         
          }
          else
          { 
              $data = array(
                'user_id' => $_SESSION['userdata']['id'],                              
                'title' => $this->input->post('title',TRUE),                              
                'content' => $this->input->post('content',TRUE),                               
                'media_id' => $media_id,                             
                'template' => $this->input->post('template',TRUE),                               
               'created'=> date('Y-m-d H:i:s'),
              ); 
            }
           
          }
      
      $this->Admin_model->SaveData("cms",$data);
      $this->session->set_flashdata('message', 'Cms has been created successfully');
      redirect('Cms/create');

    }
}

public function update($id)
{ 

if(!empty($id))
 {  
  $id=base64_decode($id);
  $table = 'cms';
  $cond = "id='".$id."'";
  $row = $this->Cms_model->GetData($table,'',$cond,'','','','1');
  if(!empty($row))
  {  
  if($row->media_id!='0')
  {
     $rowMedia = $this->Admin_model->GetData("medias","","id='".$row->media_id."'");  
  }
  else
  {
    $rowMedia = "";  
  }

  $medias = $this->Admin_model->GetData("medias","","status='Active' and type='image'","","id desc");
  $breadcrumbs="<ol class='breadcrumb'>
        <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li><a href='".site_url('Cms/index')."'>Manage Cms</a></li>
        <li class='active'>Update Cms</li>
        </ol>";
  
  $data = array(
            'heading'=>'Update Cms',
            'button'=>'Update',
            'breadcrumbs'=>$breadcrumbs,
            'canBtn'=>'Cancel',
            'action'=>site_url('Cms/update_action/'.base64_encode($id)),
            'id' =>set_value('id',$row->id),
            'title' =>set_value('title',$row->title),
            'content' =>set_value('content',$row->content),
             'status' =>set_value('status',$row->status),
             'medias' => $medias,
            'rowMedia' => $rowMedia,
             ); 
    $this->load->view('cms/cms_form',$data);
 }

else
{
  redirect('Cms');
}
}
  else
  {
     redirect('Cms');
  }
}

public function update_action($id)
{

  $settings = $this->Admin_model->GetData("settings");
     
  $small_image_dimensions = $settings[0]->small_image_dimensions;
  $explode_small_image_dimensions = explode('_', $small_image_dimensions);
  $small_image_dimensions_width = $explode_small_image_dimensions[0];
  $small_image_dimensions_height = $explode_small_image_dimensions[1]; 

  $thumbnail_image_dimensions = $settings[0]->thumbnail_image_dimensions;
  $explode_thumbnail_image_dimensions = explode('_', $thumbnail_image_dimensions);
  $thumbnail_image_dimensions_width = $explode_thumbnail_image_dimensions[0];
  $thumbnail_image_dimensions_height = $explode_thumbnail_image_dimensions[1];


  $large_image_dimensions = $settings[0]->large_image_dimensions;
  $explode_large_image_dimensions = explode('_', $large_image_dimensions);
  $large_image_dimensions_width = $explode_large_image_dimensions[0];
  $large_image_dimensions_height = $explode_large_image_dimensions[1];


  $id=base64_decode($id);
 
  $this->_rules($id);
  if ($this->form_validation->run() == FALSE)
  {  
     $this->update(base64_encode($id));
  }
  else 
  {  
    if($_FILES['featured_image']['name']!='')
    {
         
            $_POST['featured_image']= rand(0000,9999)."_".$large_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $large_image_dimensions_width;
            $config1['height'] = $large_image_dimensions_height;
           
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else {   
               $featured_image  = $_POST['featured_image'];   
               
           }
          
            $_POST['featured_image']= rand(0000,9999)."_".$thumbnail_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/small_url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $thumbnail_image_dimensions_width;
            $config1['height'] = $thumbnail_image_dimensions_height;
          
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else
            {   
               $featured_image1  = $_POST['featured_image'];  
            } 

            $_POST['featured_image']= rand(0000,9999)."_".$small_image_dimensions."_".$_FILES['featured_image']['name'];
            $config1['image_library'] = 'gd2';
            $config1['source_image'] =  $_FILES['featured_image']['tmp_name'];
            $config1['new_image'] =   getcwd().'/uploads/medias/medium_url/'.$_POST['featured_image'];
            $config1['allowed_types'] = 'JPG|PNG|jpg|png|JPEG|jpeg';
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $small_image_dimensions_width;
            $config1['height'] = $small_image_dimensions_height;
            $this->image_lib->initialize($config1);
            if(!$this->image_lib->resize())
            { 
            $this->session->set_flashdata('image_error', '<span style="color:red">This file type is not allowed</span>');
            $this->create();
            return;
            }
            else 
            { 
               $featured_image2  = $_POST['featured_image']; 
            }
             $datamedia = array(
                'url' => $featured_image,
                'small_url' => $featured_image1,
                'medium_url' => $featured_image2, 
                 'type' => 'image', 

                );

              $tablename = "medias";
              $this->Admin_model->SaveData($tablename,$datamedia);

              $last_id=$this->db->insert_id();
           
              $data = array(
                'user_id' => $_SESSION['userdata']['id'],                              
                'title' => $this->input->post('title',TRUE),                              
                'content' => $this->input->post('content',TRUE),                               
                'media_id' => $last_id,                          
                'template' => $this->input->post('template',TRUE),                               
                 'status' => $this->input->post('status',TRUE),
                'modified'=> date('Y-m-d H:i:s'),
              );  
    }
    else 
    {     if(!empty($this->input->post('media_id')))
          {
            $data = array(
              'user_id' => $_SESSION['userdata']['id'],                              
              'title' => $this->input->post('title',TRUE),                              
              'content' => $this->input->post('content',TRUE),                               
              'media_id' => $this->input->post('media_id'),                    
              'template' => $this->input->post('template',TRUE),                               
             'status' => $this->input->post('status',TRUE),
              'modified'=> date('Y-m-d H:i:s'),
            ); 
          }
          else
          {
            $data = array(
            'user_id' => $_SESSION['userdata']['id'],                              
            'title' => $this->input->post('title',TRUE),                              
            'content' => $this->input->post('content',TRUE),                               
            'template' => $this->input->post('template',TRUE),  
             'status' => $this->input->post('status',TRUE),
            'modified'=> date('Y-m-d H:i:s'),
          );  
          }
    }
        $tablename = "cms";
        $cond = "id='".$id."'";
        $this->Cms_model->SaveData($tablename,$data,$cond);   
        $this->session->set_flashdata('message', 'Cms has been updated successfully');
         redirect(site_url('Cms/update/'.base64_encode($id)));   
  }
}

public function getMediaImage()
{ 
  $id = $this->input->post("id");
  $row = $this->Admin_model->GetData("medias","","id='".$id."'"); 
  $image = $row[0]->url;

  $input = '<input class="form-control" id="appendImageName" type="text" value="'.$image.'" readonly><strong id="cross" class="pointer" title="Remove" style="float:right;color:red" onclick="return removeData()">X</strong>';
 
  $data = array('input' => $input,'media_id'=>$id);   
  echo json_encode($data);
}

public function getMediaImage1()
{ 
  $id = $this->input->post("id");
  $row = $this->Admin_model->GetData("medias","","id='".$id."'");
  
  $image = $row[0]->url;
  $small_url = $row[0]->small_url;

  $input = '<input class="form-control" id="appendImageName" type="text" value="'.$image.'" readonly><strong id="cross" class="pointer" title="Remove" style="float:right;color:red" onclick="return removeData1()">X</strong>';
  $inputurl = '<input class="form-control" id="appendUrl" type="text" value="'.$small_url.'" readonly><strong id="cross" class="pointer" title="Remove" style="float:right;color:red" onclick="return removeData1()">X</strong>';
 
  $data = array(
    'input' => $input,
    'inputurl' => $inputurl,
    'media_id'=>$id

    );   
  //print_r($data);exit;
  echo json_encode($data);
}

public function edit_slug_data()
{
  $slug =$this->input->post('slug');
  $cms_id =$this->input->post('cms_id');
  $cms = $this->Crud_model->GetData("cms","slug","slug='".$slug."' and id!='".$cms_id."'",'','','','1');
 

  if(!empty($cms))
  {
      echo "1";exit();
  }  
  else
  {
    echo "0";exit;
  }
}


public function _rules($id) 
{   
      $table = 'cms';
      $cond = "title='".$this->input->post('title',TRUE)."' and id!='".$id."' ";
      $row = $this->Cms_model->GetData($table,'',$cond,'','','','1');   
        $count = count($row); 

        if($count==0)
        {
            $is_unique = "";
        }
        else {
            $is_unique = "|is_unique[cms.title]";
            
        }

        

        

        $this->form_validation->set_rules('title', 'title', 'trim|required'.$is_unique,
            array(
                    'required'      => 'Required',
                    'is_unique'     => 'Already exists',
                 ));
      
        $this->form_validation->set_rules('content', ' content', 'trim|required',
            array(
                    'required'      => 'Required',
                 ));

      
      
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');
 }  
 
}
