<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assets_issue_request extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Assets_issue_reqests_model');
  $this->load->database();
  }
  public function index()
  {   
  
        $breadcrumbs = "<ul class='breadcrumb'>
                          <li>
                              <i class='ace-icon fa fa-home home-icon'></i>
                              <a href='".site_url('Dashboard')."'>Dashboard</a>
                          </li>
                          <li class='active'>Manage Assets Issue Reqests </li>
                        </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '8' ,'ajax_manage_page' => site_url('Assets_issue_request/ajax_manage_page') , 'heading' => 'Manage Assets Issue Reqests ');
        $this->load->view('assets_issue_reqests/list',$data);
    
   

  }

 public function ajax_manage_page()
    {
    	foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Assets_issue_reqests')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }

        $Data = $this->Assets_issue_reqests_model->get_datatables(); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
           $status = '';
           
       		$btn = '';
       	

           
              if($row->status=='Pending')
              {
                 // $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Pending </a>";            
                  $status .=  "<a data-toggle='modal' data-target='#myModal' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Pending </a>";            
              }
              else
              {
                  $status .=  "<span  class='label-success label' > Resolve </span>";
              }
            
          
            if(!empty($row->resolve_remark))
            {
                if(strlen($row->resolve_remark)>50) 
                { 
                    $resolve_remark=substr($row->resolve_remark, 0, 50).'....<a style="cursor:pointer" data-toggle="modal" data-target="#viewModal" onclick="return get_desc('.$row->id.')">Read More</a>';
                } 
                else 
                {
                    $resolve_remark=$row->resolve_remark; 
                }
            } 
            else
            {
               $resolve_remark='-';
            } 


            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->branch_title;
            $nestedData[] = $row->name;
            $nestedData[] = $row->asset_name;
            $nestedData[] = $row->barcode_number;
            $nestedData[] = $row->type;
            $nestedData[] = $row->remarks;
            $nestedData[] = $resolve_remark;
            $nestedData[] = $status;            
            
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Assets_issue_reqests_model->count_all(),
                    "recordsFiltered" => $this->Assets_issue_reqests_model->count_filtered(),
                    "data" => $data,
                );
        
       
        echo json_encode($output);
    }

  public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('assets_issue_reqests','status',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Pending')
        {
            $this->Crud_model->SaveData('assets_issue_reqests',
              array(
              'status'=>'Resolve','resolve_remark' =>$this->input->post('resolve'),
              'resolve_by' =>$_SESSION[SESSION_NAME]['id'],
              'modified' =>date('Y-m-d H:i:s')),
              "id='".$_POST['id']."'");
        }

         $count_isuue = count($this->Crud_model->GetData('assets_issue_reqests','status',"status='Pending'"));

         if($count_isuue !='0')
         {
            $count_isuue=$count_isuue;
         }
         else
         {
            $count_isuue='';
         }
           $data = array('count_isuue' => $count_isuue, );
      
        echo json_encode($data);
    }

    public function get_desc()
 {
    $id = $this->input->post("id"); 
    $row = $this->Crud_model->GetData("assets_issue_reqests","resolve_remark","id='".$id."'");
    $data = array('description' => $row[0]->resolve_remark, );
    echo json_encode($data);
 }
   
}
