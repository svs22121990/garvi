<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assets_audit_remarks extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Assets_audit_remarks_model');
  $this->load->database();
  }
  public function index()
  { 
      $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Asset Audit Remarks</li>
                    </ul>";

       
        $data = array('breadcrumbs' => $breadcrumbs ,
          'heading' => 'Manage Asset Audit Remarks',
          'actioncolumn' => '6' ,'ajax_manage_page' => site_url('Assets_audit_remarks/ajax_manage_page'),
          );

        $this->load->view('assets/assets_audit_remarks',$data);
   
  }

  public function ajax_manage_page()
  {
    $Data = $this->Assets_audit_remarks_model->get_datatables(); 

    $data = array();       
    $no=0; 
    foreach($Data as $row) 
    {  
       if(strlen($row->remarks)>125) 
        { 
            $remarks=substr($row->remarks, 0, 125).'....<a style="cursor:pointer" data-toggle="modal" data-target="#myModal" onclick="return get_desc('.$row->id.')">Read More</a>';
        } 
        else 
        {
            $remarks=$row->remarks; 
        }

        $date=date('Y-m-d',strtotime($row->created));

        $no++;
        $nestedData = array();
        $nestedData[] = $no ;
        $nestedData[] = $row->branch_title;
        $nestedData[] = $row->name;
        $nestedData[] = $row->asset_name;
        $nestedData[] = $row->barcode_number;
        $nestedData[] = $row->type;
        $nestedData[] = $date;
        $nestedData[] = $remarks;
        $data[] = $nestedData;
        $selected = '';
    }

    $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Assets_audit_remarks_model->count_all(),
                "recordsFiltered" => $this->Assets_audit_remarks_model->count_filtered(),
                "data" => $data,
            );
   
    echo json_encode($output);
  }

 public function get_desc()
 {
    $id = $this->input->post("id"); 
    $row = $this->Crud_model->GetData("assets_audit_remarks","","id='".$id."'");
    $data = array('description' => $row[0]->remarks, );
    echo json_encode($data);
 }
 
}
