<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Size extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Size_model');
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
                    if($menu->value=='Size')
                    {

                        if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                        if(!empty($menu->act_import)){ $import='1'; }else{ $import='0'; }
                    }
                }
            }

            $import_buton ='<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
            $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Size</li>
                    </ul>";
            $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '2' ,'ajax_manage_page' => site_url('Size/ajax_manage_page') , 'heading' => 'Manage Size','addPermission'=>$add,'import' =>$import_buton,'importPermission' =>$import);


            $this->load->view('size/index',$data);
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
        $Data = $this->Size_model->get_datatables();
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        {
            foreach($menu as $row)
            {

                if($row->value=='Size')
                {
                    //print_r($row);
                    if(!empty($row->act_edit)){ $edit='1'; }else{ $edit='0'; }
                    if(!empty($row->act_delete)){ $delete='1'; }else{ $delete='0'; }
                    if(!empty($row->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                    if(!empty($row->act_add_existing_stock)){  $act_add_existing_stock='1';}else{ $act_add_existing_stock='0';}
                }
            }
        }
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
            $btn='';
            if(!empty($edit)){
                $btn = ('<a href="#myModaledit" title="Edit"  data-toggle="modal" data-target="" class="btn btn-info btn-circle btn-sm" onclick="getEditvalue('.$row->id.');">
              <i class="ace-icon fa fa-pencil"></i></a>');
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

            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->title;
            $nestedData[] = $status;
            $nestedData[] = $btn;
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
            //"draw" => $_POST['draw'],
            "recordsTotal" => $this->Size_model->count_all(),
            "recordsFiltered" => $this->Size_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }



    public function changeStatus(){
        $getasset_types = $this->Crud_model->GetData('size','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('size',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('size',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Size'));
    }

    public function addData()
    {

        $condDuplication = "title='".$this->input->post('name')."'";
        $duplication = $this->Crud_model->GetData('size','', $condDuplication);

        if(count($duplication) > 0 )
        {
            echo "1";
        }
        else
        {
            $data = array(
                'title' => $this->input->post('name'),
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
            );

            $this->Crud_model->SaveData('size', $data);
            $this->session->set_flashdata('message', 'success');

            echo "2";
        }

    }

    public function getUpdateName()
    {
        $row = $this->Crud_model->GetData('size','',"id='".$_POST['id']."'",'','','','row');
        print_r(trim($row->title));
    }

    public function updateData()
    {
        $condDuplication = "title='".$this->input->post('name')."' and id !='".$this->input->post('id')."'";
        $duplication = $this->Crud_model->GetData('size','', $condDuplication);
        if(count($duplication) > 0 )
        {
            echo "1";
        }
        else
        {
            $data = array(
                'title' => $this->input->post('name')
            );

            $this->Crud_model->SaveData('size', $data, "id='".$this->input->post('id')."'");
            $this->session->set_flashdata('message', 'success');

            echo "2";
        }

    }


    public function delete()
    {
        $con = "id='".$_POST['id']."'";
        $this->Crud_model->DeleteData('size',$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record deleted successfully</span>');
        redirect('Size/index');
    }




}