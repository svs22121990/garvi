<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_logs extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('Crud_model');
    $this->load->model('Login_logs_model');
    $this->load->library('image_lib');
    $this->image_lib->resize();  
    $this->load->database();
  }
  
  public function index()
  {
    $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Login-logs</li>
                    </ul>";
    $data = array(
                  'heading' => 'Manage Login-logs',
                  'breadcrumbs' => $breadcrumbs,
                  'actioncolumn' => '6' ,
                  'ajax_manage_page' => site_url('Login_logs/ajax_manage_page') ,
                  );
    $this->load->view('login_logs/login_logs_list',$data);
  }

  public function ajax_manage_page()
  {
    $condition="";
    $getData = $this->Login_logs_model->get_datatables($condition);
    $no = 0;
    $data = array();    
    foreach ($getData as $Data) 
    {
        if($Data->status == 'Web')
       {
         $status="<b><span style='color:#1CAF9A'>".$Data->status."</span></b>";
       } 
       else if($Data->status == 'App')
       {
         $status="<b><span style='color:#762C59'>".$Data->status."</span></b>";
       } 

        $no++;
        $nestedData = array();
        $nestedData[] = $no;
        $nestedData[] = '<i class="fa fa-cube"></i> '.ucwords($Data->branch_title);
        $nestedData[] = '<i class="fa fa-user"></i> '.ucwords($Data->name);
        $nestedData[] = '<i class="fa fa-calendar"></i> '.date('Y-m-d H:i A',strtotime($Data->login_time));
        $nestedData[] = $Data->login_ip;
        $nestedData[] =$status;
        $data[] = $nestedData;
    }    
      $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Login_logs_model->count_all($condition),
          "recordsFiltered" => $this->Login_logs_model->count_filtered($condition),
          "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }




}