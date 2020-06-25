<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branches extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Branches_model');
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
              if($menu->value=='Branches')
              { 
                if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
              }
          }
        }


        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'>Manage Branches</li>
                    </ul>";

        $country_data = $this->Crud_model->GetData('mst_countries',"","","","country_name asc");
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '3' ,'ajax_manage_page' => site_url('Branches/ajax_manage_page'),'heading' => 'Manage Branches','country_data'=>$country_data,'addPermission'=>$add);
        $this->load->view('branches/list',$data);
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
      $Data = $this->Branches_model->get_datatables(); 
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Branches')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
                if(!empty($menu->act_view)){ $view='1'; }else{ $view='0'; }
               // print_r($view);exit;
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
            if(!empty($view)){
            $btn ='<a href='.site_url("Branches/view/".$row->id).' data-toggle="modal" title="View" class="btn btn-primary btn-circle btn-sm"><i class="ace-icon fa fa-eye"></i></a>';
            }
             if(!empty($edit)){
            $btn .= ('&nbsp;|&nbsp;'.'<a href='.site_url("Branches/update/".$row->id).' class="btn btn-info btn-circle btn-sm" ><i class="ace-icon fa fa-pencil bigger-130"></i></a>');
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
            $nestedData[] = $no;
            $nestedData[] = ucfirst($row->branch_title);
            //$nestedData[] = $row->name;
            if(!empty($row->contact_no))
            {
              $contact_no = $row->contact_no;
            }
            else
            {
              $contact_no = 'N/A';
            }
            $nestedData[] = $contact_no;
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Branches_model->count_all(),
                    "recordsFiltered" => $this->Branches_model->count_filtered(),
                    "data" => $data,
                    );       
        echo json_encode($output);
    }

     public function get_state()
      {    
        $id = $this->input->post('id'); 
        $cond = "country_id ='".$id."'";
        $getstate = $this->Crud_model->GetData('mst_states','',$cond,"","state_name asc");
        //print_r( $getstate);exit;
        $html="<option value=''>-- Select State--</option>";
        
        foreach($getstate as $states) {
            $html.="<option value=".$states->id.">".$states->state_name."</option>";
            }
        echo $html;
      }


     public function get_city()
      {    
        $id = $this->input->post('id'); 
        $cond = "state_id ='".$id."'";
        $getcity = $this->Crud_model->GetData('mst_cities','',$cond,"","city_name asc");
        //print_r( $getstate);exit;
        $html="<option value=''>-- Select city--</option>";
        foreach($getcity as $city)
         {
          //print_r($states);
          $html.="<option value=".$city->id.">".$city->city_name."</option>";
          
         }
         echo $html;
     }

    public function create()
    {
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                     <li class=''> <a href='".site_url('Branches')."'>Manage Branches</a></li>
                    <li class='active'>Create Branch</li>
                    </ul>";
        $country_data = $this->Crud_model->GetData('mst_countries',"","status='Active'");                  
        $employeeData = $this->Crud_model->GetData('employees',"id,name","status='Active' and type='Employee'");                  
        $data = array('breadcrumbs' => $breadcrumbs,
                      'heading' => 'Create Branch',
                      'country_data'=>$country_data, 
                      'employeeData'=>$employeeData, 
                      'button'=>'Create',                       
                      );

       $this->load->view('branches/form',$data);
    }

    public function create_action()
    {
      if($_POST)
      {
            $country = $this->Crud_model->GetData('mst_countries',"country_name","id='".$_POST['country_id']."'",'','','','row');
            $states = $this->Crud_model->GetData('mst_states',"state_name","id='".$_POST['state_id']."'",'','','','row');
            $cities = $this->Crud_model->GetData('mst_cities',"city_name","id='".$_POST['city_id']."'",'','','','row');

            $address=$_POST['address'].','.$cities->city_name.','.$states->state_name.','.$country->country_name;
           
            $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
            $geo = json_decode($geo, true);
            $latitude = $geo['results'][0]['geometry']['location']['lat'];
            $longitude = $geo['results'][0]['geometry']['location']['lng'];


          $data = array(
            'branch_title'=>$_POST['branch_title'],
            'country_id'=>$_POST['country_id'],
            'state_id'=>$_POST['state_id'],
            'city_id'=>$_POST['city_id'],
            'address'=>$_POST['address'],
            'pincode'=>$_POST['pincode'],
            'contact_no'=>$_POST['mobile'],
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'status'=>'Active',
            'is_delete'=>'No',
            'created'=>date('Y-m-d H:i:s'),            
            );

            $this->Crud_model->SaveData("branches",$data);
      
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Branch has been created successfully</span>');
            redirect('Branches/index');
      }
      else
      {
        redirect('Branches/Create');
      }
       
    }

    public function update($id)
    {
      $getBranchData = $this->Crud_model->GetData('branches',"","id='".$id."'",'','','','row');
       $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('Branches')."'>Manage Branches</a></li>
                    <li class='active'>Update Branch</li>
                    </ul>";
      $country_data = $this->Crud_model->GetData('mst_countries',"","status='Active'","","country_name asc");  
      $state_data = $this->Crud_model->GetData('mst_states',"","country_id='".$getBranchData->country_id."'");  
      $city_data = $this->Crud_model->GetData('mst_cities',"","state_id='".$getBranchData->state_id."'");  
      $empupdate_data = $this->Crud_model->GetData('employees',"","status='Active' and type='Employee'");  

      $data = array(
                      'getBranchData' => $getBranchData,
                      'button' => 'Update',
                      'update_id' => $id,
                      'breadcrumbs' => $breadcrumbs, 
                       'heading' => 'Update Branch',                   
                       'country_data' => $country_data,                   
                       'state_data' => $state_data,                   
                       'city_data' => $city_data,                   
                       'empupdate_data' => $empupdate_data,                   
                    );

      $this->load->view('branches/update_form',$data);

    }

    public function update_action()
    {
      if($_POST['update_id'] !='')
      {
            $country = $this->Crud_model->GetData('mst_countries',"country_name","id='".$_POST['country_id']."'",'','','','row');
            $states = $this->Crud_model->GetData('mst_states',"state_name","id='".$_POST['state_id']."'",'','','','row');
            $cities = $this->Crud_model->GetData('mst_cities',"city_name","id='".$_POST['city_id']."'",'','','','row');

            $address=$_POST['address'].','.$cities->city_name.','.$states->state_name.','.$country->country_name;
           
            $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
            $geo = json_decode($geo, true);
            $latitude = $geo['results'][0]['geometry']['location']['lat'];
            $longitude = $geo['results'][0]['geometry']['location']['lng'];
            

          $data = array(
            'branch_title'=>$_POST['branch_title'],
            'country_id'=>$_POST['country_id'],
            'state_id'=>$_POST['state_id'],
            'city_id'=>$_POST['city_id'],
            'address'=>$_POST['address'],
            'pincode'=>$_POST['pincode'],
            'contact_no'=>$_POST['mobile'],
            'latitude'=>$latitude,
            'longitude'=>$longitude,
            'status'=>'Active',
            'is_delete'=>'No',
            'created'=>date('Y-m-d H:i:s'),            
            );

             $this->Crud_model->SaveData("branches",$data,"id='".$_POST['update_id']."'");      
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Branch has been updated successfully</span>');
            redirect('Branches/index');
      }
      else
      {
        redirect('Branches');
      }
    }


    public function view($id)
    {
      if($id =='')
      {
        redirect('Branches/index');

      }
      else
      {
           $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                     <li class=''> <a href='".site_url('Branches')."'>Manage Branches</a></li>
                    <li class='active'>View Branche Details</li>
                    </ul>";
      
      
          $getBranchData = $this->Branches_model->getAllDetails($id);
          $getEmployees = $this->Crud_model->GetData('employees','',"branch_id='".$id."' and status='Active' and type='Employee'");
          $data = array(
                          'breadcrumbs' => $breadcrumbs,
                          'heading' => 'Manage Branches',
                          'getBranchData'=>$getBranchData,                                             
                          'getEmployees'=>$getEmployees,                                             
                          );

           $this->load->view('branches/view',$data); 
      }
    }

    public function changeStatus(){       
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

      public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $data = array('is_delete' =>'Yes',);
        $this->Crud_model->SaveData('branches',$data,$con);

        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Branch has been deleted successfully</span>');
        redirect(site_url('Branches'));
      }

    public function chkName()
    {
      if(isset($_POST['id']))
      {
        $con = "branch_title='".$this->input->post('branch_title')."' and id!='".$_POST['id']."'";
      }
      else
      {
        $con = "branch_title='".$this->input->post('branch_title')."'";
      }
      $chkdupliasset = $this->Crud_model->GetData('branches',"",$con);
        if(count($chkdupliasset) > 0 )
          {
            echo "1";
          }
          else
          {
            
          }
    }

    //VALIDATIONS
    public function _rules($id)
    {
        $table = 'countries';
        $cond = "country_name='".$this->input->post('country_name',TRUE)."' and id!='".$id."' ";
        $row = $this->Crud_model->GetData($table,'',$cond,'','','','1');  
        $count = count($row); 

        if($count==0)
        {
            $is_unique = "";
        }
        else {
            $is_unique = "|is_unique[countries.country_name]";
            
        }
        $this->form_validation->set_rules('country_name', 'country name', 'trim|required'.$is_unique,
            array(
                    'required'      => 'Please enter %s.',
                    'is_unique'     => 'This %s already exists',
                 ));

        
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
    }

  /*  public function savemyEmployee()
   {
      //print_r($_POST);exit;
        $data = array(
          'name' => $_POST['employee_title'],
          'status' => "Active",
          'created' => date("Y-m-d H:i:s"),
         );
        $this->Crud_model->SaveData("employees",$data);  

        echo "1";exit;
   }*/


}
