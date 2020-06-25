<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Sub_categories_model');
  $this->load->database();
  }
  
  public function index()
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
    $export_button = '';
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {  

        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Sub_category')
              { 
                
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
                    <li class='active'>Manage SubCategories</li>
                    </ul>";
        $category_data = $this->Crud_model->GetData('categories','','status="Active"','','title asc');

         $import_buton ='<a data-target="#uploadData" style="cursor:pointer;color:black;" title="Upload Excel" data-backdrop="static" data-keyboard="false" data-toggle="modal" ><span class="fa fa-file-excel-o"></span></a>';
        //print_r($this->db->last_query());exit;
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '2' ,'ajax_manage_page' => site_url('Sub_category/ajax_manage_page') , 'heading' => 'Manage SubCategories','category_data'=>$category_data,'addPermission'=>$add,'import' =>$import_buton,'importPermission' =>$import);

    $this->load->view('sub_categories/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }  
  }
    public function ajax_manage_page()
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
    $export_button = '';
        $Data = $this->Sub_categories_model->get_datatables(); 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {
              if($row->value=='Sub_category')
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
              <i class="ace-icon fa fa-pencil bigger-130"></i></a>');
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
            $nestedData[] = $row->sub_cat_title;
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Sub_categories_model->count_all(),
                    "recordsFiltered" => $this->Sub_categories_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }


    public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('sub_categories','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('sub_categories',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('sub_categories',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Sub_category'));
    }

    public function addData()
      {       
          $condDuplication = "sub_cat_title='".$this->input->post('name')."' and category_id='".$this->input->post('sub_cat_id')."'";
          $duplication = $this->Crud_model->GetData('sub_categories','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'category_id' => $this->input->post('sub_cat_id'),
                'sub_cat_title' => $this->input->post('name'),
                'status' => 'Active',              
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('sub_categories', $data);
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

     public function getUpdateName()
      {
        $rowsubcat= $this->Crud_model->GetData('sub_categories','',"id='".$_POST['id']."'",'','','','row');     
        if(!empty($rowsubcat))
        {
            $rowsubcatData['success'] = '1';
            $rowsubcatData['sub_cat_id'] = $rowsubcat->id;
            $rowsubcatData['cat_id'] = $rowsubcat->category_id;
            $rowsubcatData['sub_cat_title'] = $rowsubcat->sub_cat_title;                     
        }
        else
        {
            $rowsubcatData['success'] = '0';
        }       
        echo json_encode($rowsubcatData);
      }

      public function updateData()
      {
           $condDuplication = "category_id='".$this->input->post('sub_cat_id_edit')."' and sub_cat_title='".$this->input->post('titleNameedit')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('sub_categories','', $condDuplication);
          //print_r($this->db->last_query());exit;
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                        'category_id'=>$_POST['sub_cat_id_edit'],            
                        'sub_cat_title'=>$_POST['titleNameedit'],                                                                    
                        'modified'=>date('Y-m-d H:i:s'),            
                        );                  
            $this->Crud_model->SaveData("sub_categories",$data,"id='".$_POST['id']."'");      
            $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }


      public function update_action()
    {     
      // print_r($_POST);exit;
      $condDuplication = "category_id='".$this->input->post('sub_cat_id_edit')."' and category_id='".$this->input->post('sub_cat_title')."' and id !='".$this->input->post('id')."'";
      $duplication = $this->Crud_model->GetData('sub_categories','', $condDuplication);

        $data = array(
            'category_id'=>$_POST['sub_cat_id_edit'],            
            'sub_cat_title'=>$_POST['titleNameedit'],                                                                    
            'modified'=>date('Y-m-d H:i:s'),            
            );                  
            $this->Crud_model->SaveData("sub_categories",$data,"id='".$_POST['updateId']."'");      
            $this->session->set_flashdata('message', 'success');
            redirect('Sub_category/index');
     
    }


      public function delete()
      {
        $con = "id='".$_POST['id']."'";    
        $getsubcatData = $this->Crud_model->GetData('assets','id,subcategory_id',"subcategory_id in ('".$_POST['id']."')");
        if(!empty($getsubcatData))
        {
           $this->session->set_flashdata('message', '<span class="label label-danger" style="margin-bottom:0px">SubCategory already mapped with asset</span>');
           redirect(site_url('Sub_category'));
        } 
        else
        {
          $this->Crud_model->DeleteData('sub_categories',$con);
          $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">SubCategory has been deleted successfully</span>');
          redirect(site_url('Sub_category'));
        }   
        
      }



       public function addNewCat()
      {
        //print_r("fiii");exit;
          $condDuplication = "title='".$this->input->post('namecat')."'";
          $duplication = $this->Crud_model->GetData('categories','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'title' => $this->input->post('namecat'),
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('categories', $data);              
              $insert_id = $this->db->insert_id();
              //print_r($insert_id);exit;

              $this->session->set_flashdata('message', 'success');
              $getlastrow = $this->Crud_model->GetData('categories',"","id='".$insert_id."'",'','','','row');

              //print_r($this->db->last_query());exit;
              /*$html="<option value=''>-- Select category--</option>";*/
              $html='';
              $html.="<option value=".$getlastrow->id.">".$getlastrow->title."</option>";
              echo $html;

              //echo "2";
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
            redirect(site_url('Sub Category/index'));            
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
                            $getCategory = $this->Crud_model->GetData('categories','',"title='".$val[0]."'",'','','','single'); 
                            if(empty($getCategory))
                            {
                                $saveCategory = $this->Crud_model->SaveData('categories',array("title"=>$val[0]));
                                $CategoryId = $this->db->insert_id();
                            }else{
                                $CategoryId = $getCategory->id;
                            }
                        }
                        else
                        {
                            $CategoryId = '';
                        }



                        $getCategories= $this->Crud_model->GetData('sub_categories','sub_cat_title',"sub_cat_title='".$val[1]."'",'','','','single'); 
                        /*for already exist check*/
                        
                        if(empty($getCategories))
                        {
                            $data = array(
                                          'sub_cat_title' => $val[1],
                                          'category_id'=>$CategoryId,
                                          );
                            $SaveAssets = $this->Crud_model->SaveData('sub_categories',$data);
                        }
                        else
                        { 
                            $existAssets[]=array($val[0],$val[1],'Sub Category Name already exist');
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
            
            redirect(site_url('Sub_category/index'));  
        }
        if(empty($existAssets))
        {
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Sub Category has been imported successfully</span>');
            redirect('Sub_category/index');
        }
        else{
            $data = array('existAssets' => $existAssets);
            $this->load->view('sub_categories/duplicateCat',$data);
        } 
}

}
