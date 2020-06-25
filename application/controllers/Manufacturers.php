<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manufacturers extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Manufacturers_model');
  $this->load->library('upload');
  $this->load->library('image_lib');
  $this->load->database();
  }
  
  public function index()
  {   

    $add = '';
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  

        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Manufacturers')
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
                    <li class='active'>Manage Manufacturers</li>
                    </ul>";

        $manufacturers_data = $this->Crud_model->GetData('manufacturers',"","",'',"name asc");
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '4' ,'ajax_manage_page' => site_url('Manufacturers/ajax_manage_page'),'heading' => 'Manage Manufacturers','manufacturers_data'=>$manufacturers_data,'addPermission'=>$add);


    $this->load->view('manufacturers/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }   
  }

  public function chkduplicate()
  {
          $condDuplication = "name='".$this->input->post('manu_name')."'";
          $duplication = $this->Crud_model->GetData('manufacturers','',$condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
            echo "0";
          }
  }

    public function ajax_manage_page()
    {
        $delete= '';
        $actstatus= '';
        $edit = '';
        $Data = $this->Manufacturers_model->get_datatables(); 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='Manufacturers')
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
           $status = '';
           /* if(!empty($actstatus)){
               if($row->status == 'Active')
               {
                  $status = '<label class="label label-success">Active</label>';
               }
               else
               {
                  $status = '<label class="label label-warning">Inactive</label>';
               }
            }*/

            $btn = '';
             if(!empty($edit)){
            $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');">
              <i class="ace-icon fa fa-pencil bigger-130"></i></a>');
          }

          if(!empty($delete)){
            $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            }

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
            $imagepath = "<img alt='image' src='".base_url('uploads/manufacturers/'.$row->image)."' width='80px' height='80px'/>";
            //print_r($imagepath);exit;
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->name;
            $nestedData[] = $imagepath;
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Manufacturers_model->count_all(),
                    "recordsFiltered" => $this->Manufacturers_model->count_filtered(),
                    "data" => $data,
                );
        echo json_encode($output);
    }

    public function create_action()
    {
      if($_POST)
      {       

        $getManu = $this->Crud_model->GetData('manufacturers',"","name='".$_POST['manu_name']."'");
        if(count($getManu) > 0)
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Manufacturer name already exist</span>');
            redirect('Manufacturers/index');
        }
        else
        {
             $image="";
         $config = array(
            'upload_path'   => getcwd().'/uploads/manufacturers',            
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
                          'name'=>$_POST['manu_name'],            
                          'image'=>$image,            
                          'status'=>'Active',            
                          'created'=>date('Y-m-d H:i:s'),            
                        );

            $this->Crud_model->SaveData("manufacturers",$data);      
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Manufacturer has been created successfully</span>');
            redirect('Manufacturers/index');
        }
      }
      else
      {
        redirect('Manufacturers/Create');
      } 
    }

    public function update($id)
    {
      $getManufacturersData = $this->Crud_model->GetData('manufacturers',"","id='".$id."'",'','','','row');
       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Update Branches</li>
                    </ul>";
    

      $data = array(
                      'action' =>site_url('Manufacturers/update_action/'.$id),
                      'getManufacturersData' => $getManufacturersData,
                      'manu_name' => $getManufacturersData->name,
                      'button' => 'Update',
                      'update_id' => $id,
                      'breadcrumbs' => $breadcrumbs, 
                       'heading' => 'Update Manufacturers',                                                     
                    );

      $this->load->view('manufacturers/form',$data);

    }

    public function update_action()
    {/*
       $getManu = $this->Crud_model->GetData('manufacturers',"","name='".$_POST['manu_name_edit']."'");
        if(count($getManu) > 0)
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Manufacturer name already exist</span>');
            redirect('Manufacturers/index');
        }
        else
        {*/
            if($_FILES['photo_edit']['name']!='')
            {
              $config = array(
              'upload_path'   => getcwd().'/uploads/manufacturers',           
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
                  unlink('uploads/manufacturers/'.$this->input->post('old_image'));
                  $image=$data['file_name'];
                }
                else
                {
                   $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Manufacturer has been updated successfully</span>');
                   redirect('Manufacturers/index');
                }
            }

          }
          else
          { 
              $image=$this->input->post('old_image');
          } 

          $data = array(
            'name'=>$_POST['manu_name_edit'],            
            'image'=>$image,                                 
            'modified'=>date('Y-m-d H:i:s'),     

            );        
            $this->Crud_model->SaveData("manufacturers",$data,"id='".$_POST['updateId']."'");      
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Manufacturer has been updated successfully</span>');
            redirect('Manufacturers/index');
       /* }*/
         
    }

    



    public function changeStatus(){       
        $getmanu_types = $this->Crud_model->GetData('manufacturers','',"id='".$_POST['id']."'",'','','','row');
        if($getmanu_types->status=='Active')
        {
            $this->Crud_model->SaveData('manufacturers',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('manufacturers',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Manufacturers'));
    }

      public function getUpdateName()
      {
        $rowManu = $this->Crud_model->GetData('manufacturers','',"id='".$_POST['id']."'",'','','','row');     
        if(!empty($rowManu))
        {
            $row['success'] = '1';
            $row['manu_id'] = $rowManu->id;
            $row['manu_name'] = $rowManu->name;
            $row['manu_image'] = $rowManu->image;            
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
        $getbrandData = $this->Crud_model->GetData('brands',"id,manufacturer_id","manufacturer_id in ('".$_POST['id']."')");

        if(!empty($getbrandData))
        {
           $this->session->set_flashdata('Manufacturer is already mapped with branch', 'success');
            redirect(site_url('Manufacturers'));
        }
        else
        {
          $this->Crud_model->DeleteData('manufacturers',$con);
          $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Manufacturer has been deleted successfully</span>');
          redirect(site_url('Manufacturers'));
        }       
        
      }

}
