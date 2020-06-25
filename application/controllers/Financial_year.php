<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financial_year extends CI_Controller {

  function __construct()
  {
      parent::__construct(); 
      $this->load->model('Financial_year_model');
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
              if($menu->value=='Financial_year')
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
                    <li class='active'>Manage Financial Year</li>
                    </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '2' ,'ajax_manage_page' => site_url('Financial_year/ajax_manage_page') , 'heading' => 'Manage Financial Year', 'addPermission'=>$add);
      $this->load->view('financial_year/list',$data);
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
        $export_button = '';
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='Financial_year')
              { 
                if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
              }
          }
        }
        $Data = $this->Financial_year_model->get_datatables(); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
           if($row->status == 'Active')
           {
              $status = '<label class="label label-success">Active</label>';
           }
           else
           {
              $status = '<label class="label label-warning">Inactive</label>';
           }

           $btn = '';
           if(!empty($edit)){
            $btn .= ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');">
              <i class="ace-icon fa fa-pencil"></i></a>');
            }

            /*$btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="red" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';*/

            $status = '';
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
            
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->financial_year;           
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Financial_year_model->count_all(),
                    "recordsFiltered" => $this->Financial_year_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }

  /*public function changeStatus()
  {       
    $getasset_types = $this->Crud_model->GetData('financial_years','',"id='".$_POST['id']."'",'','','','row');

      if($getasset_types->status=='Active')
      {
          $this->Crud_model->SaveData('financial_years',array('status'=>'Inactive'),"id='".$_POST['id']."'");
      }
      else
      {
          $this->Crud_model->SaveData('financial_years',array('status'=>'Active'),"id='".$_POST['id']."'");
      }
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
      redirect(site_url('Financial_year'));
  }*/

  public function changeStatus()
  {  
    $row = $this->Crud_model->GetData('financial_years');
    for($i=0;$i<=count($row);$i++)
    {
      $this->Crud_model->SaveData('financial_years',array('status'=>'Inactive'),"id='".$row[$i]->id."'");
    }

    $this->Crud_model->SaveData('financial_years',array('status'=>'Active'),"id='".$_POST['id']."'");
    $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
    redirect(site_url('Financial_year'));
  }
 

    public function addData()
      {
        //print_r("fiii");exit;
          $condDuplication = "financial_year='".$this->input->post('name')."'";
          $duplication = $this->Crud_model->GetData('financial_years','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'financial_year' => $this->input->post('name'),
                'status' => 'Inactive',               
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('financial_years', $data);
              $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Financial Year has been created successfully</span>');

              echo "2";
          }  
          
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('financial_years','',"id='".$_POST['id']."'",'','','','row');       
        print_r(trim($row->financial_year));
      }

      public function updateData()
      {
          $condDuplication = "financial_year='".$this->input->post('name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('financial_years','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                'financial_year' => $this->input->post('name')                
              );

              $this->Crud_model->SaveData('financial_years', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }


       /*public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $data = array('is_delete' =>'Yes',);
        $this->Crud_model->SaveData('financial_years',$data,$con);

        $this->session->set_flashdata('message', 'success');
        redirect(site_url('Assets_type'));
      }*/

     
      
    }

