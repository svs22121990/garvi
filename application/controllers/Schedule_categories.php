<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Schedule_categories extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Schedule_categories_model');
  $this->load->model('Schedule_categories_view_model');
  $this->load->database();
  }
  public function index()
  {   
    if(!empty($_SESSION[SESSION_NAME]['getMenus']))
    {   
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
        { 
          foreach($row as $menu)
          { 
              if($menu->value=='Schedule_categories')
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
                          <li class='active'>Manage Schedule Categories</li>
                        </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '5' ,'ajax_manage_page' => site_url('Schedule_categories/ajax_manage_page') , 'heading' => 'Manage Schedule Categories','addPermission'=>$add);
        $this->load->view('schedule_categories/list',$data);
    }
    else
    {
      redirect('Dashboard');
    }

  }

 public function ajax_manage_page()
    {
      foreach($_SESSION[SESSION_NAME]['getMenus'] as $row)
      { 
        foreach($row as $menu)
        { 
            if($menu->value=='Schedule_categories')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }

      if(!empty($_SESSION[SESSION_NAME]['branch_id']))
      {
        $con="sc.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'";
      }  
      else
      {
        $con="1=1";
      }  

        $Data = $this->Schedule_categories_model->get_datatables($con); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
           $status = '';
           
          $btn = ('<a href='.site_url('Schedule_categories/view_scan_barcode/'.$row->id).' title="View Scan Barcode" class="btn btn-primary btn-circle btn-sm" >
              <i class="ace-icon fa fa-eye bigger-130"></i></a>');
        

          if(!empty($delete)){
              $btn .='&nbsp;|&nbsp;'.'<a href="#deleteData" data-toggle="modal" title="Delete" class="btn btn-danger btn-circle btn-sm" onclick="checkStatus('.$row->id.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
            }  

            if(!empty($actstatus)){
              if($row->status=='Pending')
              {
                  $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-warning label' onclick='checkStatus(".$row->id.")'> Pending </a>";            
              }
              else
              {
                  $status .=  "<span  class='label-success label' > Completed </span>";
              }
            }
          
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->branch_title;
            $nestedData[] = $row->name;
            $nestedData[] = $row->title;
            $nestedData[] = $status;            
            $nestedData[] = $btn;            
            
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Schedule_categories_model->count_all($con),
                    "recordsFiltered" => $this->Schedule_categories_model->count_filtered($con),
                    "data" => $data,
                );
        
       
        echo json_encode($output);
    }

  public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('schedule_categories','status',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Pending')
        {
            $this->Crud_model->SaveData('schedule_categories',array('status'=>'Completed'),"id='".$_POST['id']."'");
        }
        
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Schedule_categories'));
    }
     public function create()
  {   
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class='active'><a href='".site_url('Schedule_categories')."'>Manage Schedule Categories</a></li>
                    <li class='active'>Add Schedule Category</li>
                    </ul>";
      $branches = $this->Crud_model->GetData('branches',"branch_title,id","is_delete='No' and status='Active'");            
       

       $action =  site_url("Schedule_categories/create_action");                 
       $data = array(
        'breadcrumbs' => $breadcrumbs ,
        'heading' => 'Add Schedule Category', 
        'button'=>'Add', 
        'action' =>$action,
        'branches' => $branches ,
        'date' => set_value('date') ,
        'branch_id' => set_value('branch_id') ,
        'employee_id' => set_value('employee_id') ,
        'category_id' => set_value('category_id') ,
        'status' => set_value('status') ,
       
        );

    $this->load->view('schedule_categories/form',$data);
  }

    public function create_action()
    {
         $id = '0';
          $this->_rules($id);
          if($this->form_validation->run() == FALSE) 
          {  
            $this->create();
           
          } 
          else
          {
              $data = array(
                'date' => $this->input->post('date'),
                'branch_id' => $this->input->post('branch_id'),
                'employee_id' => $this->input->post('employee_id'),
                'category_id' => $this->input->post('category_id'),
                'status' => 'Pending',
                
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('schedule_categories', $data);
              $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Schedule Category Created Successfully</span>');
               redirect('Schedule_categories');
          }  
    }

    public function get_employee()
    {
      if($this->input->post('branch_id') !='0')
      {   
          $id = $this->input->post('branch_id'); 
          $cond = "branch_id ='".$id."'";
          $employees = $this->Crud_model->GetData('employees','name,id',$cond,'',"","name");

          if(!empty($employees))
          {
               $html="<option value='0'>-- Select Employee--</option>";
                foreach($employees as $e)
                 {
                    $html.="<option value=".$e->id." >".$e->name."</option>";          
                 }
          }
          else
          {
              $html="<option value='0'>-- Select Employee--</option>";
          }
           echo $html;
      } 
      
    }
  public function get_category()
    {
      if($this->input->post('employee_id') !='0')
      {   
          $id = $this->input->post('employee_id'); 
          $employees = $this->Crud_model->GetData('employees',"branch_id,id","id='".$id."'","","","","1");            
      

          $cond = "ab.branch_id ='".$employees->branch_id."'  and a.category_id not in (select category_id from schedule_categories where branch_id='".$employees->branch_id."' and employee_id='".$employees->id."' and status='Pending')";
          $get_category = $this->Schedule_categories_model->get_category($cond);

          if(!empty($get_category))
          {
               $html="<option value='0'>-- Select Category--</option>";
                foreach($get_category as $e)
                 {
                    $html.="<option value=".$e->id." >".$e->title."</option>";          
                 }
          }
          else
          {
              $html="<option value='0'>-- Select Category--</option>";
          }
           echo $html;
      } 
      
    }

public function view_scan_barcode($id)
{
  if(!empty($id))
  {  
        $row = $this->Crud_model->GetData('schedule_categories','category_id',"id='".$id."'",'','','','1');
        if(!empty($row))
        {  
          $schedule_category_assets = $this->Crud_model->GetData('schedule_category_assets','id',"schedule_category_id='".$id."'");
          $categories = $this->Crud_model->GetData('categories','title',"id='".$row->category_id."'",'','','','1');

          $heading="View Scan barcode of ".$categories->title;

          $breadcrumbs = "<ul class='breadcrumb'>
                            <li>
                                <i class='ace-icon fa fa-home home-icon'></i>
                                <a href='".site_url('Dashboard')."'>Dashboard</a>
                            </li>
                            <li>
                              
                                <a href='".site_url('Schedule_categories')."'>Manage Schedule Categories</a>
                            </li>
                            <li class='active'>".$heading."</li>
                          </ul>";
          $data = array(
            'breadcrumbs' => $breadcrumbs ,
            'actioncolumn' => '6' ,
            'ajax_manage_page' => site_url('Schedule_categories/ajax_view_manage_page/'.$id) , 
            'heading' => $heading,
            'schedule_category_assets' => count($schedule_category_assets),

          );

          $this->load->view('schedule_categories/view',$data);
        }
        else{
          redirect('Schedule_categories');
        }  
  }
  else
  {
    redirect('Schedule_categories');
  }      
   
}

 public function ajax_view_manage_page($id)
{
        $con="sc.schedule_category_id='".$id."'";
        $Data = $this->Schedule_categories_view_model->get_datatables($con); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  

          $barcode_image='<img src="'.base_url("assets/purchaseOrder_barcode/").$row->barcode_image.'" title="" width="100px"/>';
          $date= date('Y-m-d',strtotime($row->created));

          if($row->status=='Verified')
          {
              $status =  "<span  class='label-success label'> Verified </span>";            
          }
          else
          {
              $status =  "<span  class='label-warning label' >Not Verified </span>";
          }

          if(!empty($row->remarks))
            {
                if(strlen($row->remarks)>50) 
                { 
                    $remarks=substr($row->remarks, 0, 50).'....<a style="cursor:pointer" data-toggle="modal" data-target="#viewModal" onclick="return get_desc('.$row->id.')">Read More</a>';
                } 
                else 
                {
                    $remarks=$row->remarks; 
                }
            } 
            else
            {
               $remarks='-';
            } 

            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->asset_name;
            $nestedData[] = $row->barcode_number;
            $nestedData[] = $barcode_image;
            $nestedData[] = $row->name;
            $nestedData[] = $date;
            $nestedData[] = $status;
            $nestedData[] = $remarks;
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Schedule_categories_view_model->count_all($con),
                    "recordsFiltered" => $this->Schedule_categories_view_model->count_filtered($con),
                    "data" => $data,
                );
        
       
        echo json_encode($output);
 }

public function add_to_stock($id)
{
  if(!empty($id))
  {  
    //$heading="Add "
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('Categories')."'>Manage Categories</a></li>
                    <li class='active'>Update Asset</li>
                    </ul>";
        
        $category_wise_barcodes = $this->Crud_model->GetData('category_wise_barcodes',"category_id","id='".$id."'",'','',"","1");                
        $category_data = $this->Crud_model->GetData('categories',"","id='".$category_wise_barcodes->category_id."'",'','',"","1");                
        $assets = $this->Crud_model->GetData('assets',"asset_name,id","category_id='".$category_data->id."'");                
                 
        $action =  site_url("Schedule_categories/update_action/".$id);  
                                       
        $data = array(
                      'breadcrumbs' => $breadcrumbs,
                      'id' => $id,
                      'heading' => 'Update Asset',
                      'button'=>'Update',                       
                      'category_data'=>$category_data, 
                      'action'=>$action, 
                      'assets'=>$assets, 
                      'id'=>$id, 
                    
                      /*'subCatData'=>$subCatData, 
                      'catData'=>$catData,*/
                      );
          $this->load->view('categories/add_to_stock',$data);
        }
        else{
          redirect('Categories');
        }  
  }
  
  
public function update_action($id)
{
      $category_wise_barcodes = $this->Crud_model->GetData('category_wise_barcodes',"barcode_number,barcode_image,category_id","id='".$_POST['id']."'",'','',"","1"); 
        

          if($this->input->post('is_existing') == 'Yes')
          {
                 if($_FILES['photo']['name']!='')
                {
                      $file_element_name = 'photo';    
                      $_POST['photo']= 'AT_'.rand(0000,9999).$_FILES['photo']['name'];
                      $config2['image_library'] = 'gd2';
                      $config2['source_image'] =  $_FILES['photo']['tmp_name'];
                      $config2['new_image'] =   getcwd().'/uploads/assetimages/'.$_POST['photo'];
                      $config2['upload_path'] =  getcwd().'/uploads/assetimages/'.$_POST['photo'];
                      $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
                      $config2['maintain_ratio'] = TRUE;
                      $config2['max_size'] = '1024';
                      $config2['width'] = "200";
                      $config2['height'] = "300";

                      $this->image_lib->initialize($config2);
                      if(!$this->image_lib->resize())
                      {
                        echo('<pre>');
                        echo ($this->image_lib->display_errors());
                      }
                      else
                      { 
                        $image= $_POST['photo'];
                      }
                }      
                else
                {
                  $image= '';
                }  

                $asset_id=$_POST['asset_id'];  

                $warranty_from= date('Y-m-d',strtotime($_POST['warranty_from']));
                $warranty_to= date('Y-m-d',strtotime($_POST['warranty_to']));

                $data = array(
                          'asset_id'=>$_POST['asset_id'],
                          'barcode_number'=>$category_wise_barcodes->barcode_number,
                          'barcode_image'=>$category_wise_barcodes->barcode_image,
                          'quantity'=>'1',
                          'price'=>$_POST['price'],
                          'short_desc'=>$_POST['short_description'],
                          'long_desc'=>$_POST['long_description'],          
                          'warranty_type'=>$_POST['warranty_type'],          
                          'warranty_from_date'=>$warranty_from,          
                          'warranty_to_date'=>$warranty_to,          
                          'warranty_description'=>$_POST['warranty_desc'],          
                          'type'=>'From_category',          
                          'status'=>'In_use',          
                           'created_by'=>$_SESSION[SESSION_NAME]['id'],
                          'image'=>$image,
                     );
              $this->Crud_model->SaveData("asset_details",$data);
              $asset_detail_id=$this->db->insert_id();

        $branch_id='1';
        $asset_branch_mappings = $this->Crud_model->GetData('asset_branch_mappings',"asset_quantity,id","asset_id='".$asset_id."' and branch_id='".$branch_id."' ",'','',"","1");  

        $asset_quantity=$asset_branch_mappings->asset_quantity +1;   

        $data=array(
          'asset_quantity' =>$asset_quantity,
        );
         $this->Crud_model->SaveData("asset_branch_mappings",$data,"id='".$asset_branch_mappings->id."'");

         $data=array(
           'asset_id'=>$_POST['asset_id'],
           'branch_id'=>$branch_id,
           'asset_branch_mappings_id'=>$asset_branch_mappings->id,
           'asset_detail_id'=>$asset_detail_id,
           'mode_of_transport'=>'test',
           'transport_detail'=>'test',
           /*'mode_of_transport'=>$_POST['mode_of_transport'],
           'transport_detail'=>$_POST['transport_detail'],*/
        );
         $this->Crud_model->SaveData("asset_branch_mappings_details",$data);


            }
            else
            {
                    if($_FILES['photo']['name']!='')
                    {
                          $file_element_name = 'photo';    
                          $_POST['photo']= 'AT_'.rand(0000,9999).$_FILES['photo']['name'];
                          $config2['image_library'] = 'gd2';
                          $config2['source_image'] =  $_FILES['photo']['tmp_name'];
                          $config2['new_image'] =   getcwd().'/uploads/assetimages/'.$_POST['photo'];
                          $config2['upload_path'] =  getcwd().'/uploads/assetimages/'.$_POST['photo'];
                          $config2['allowed_types'] = 'JPG|PNG|jpg|png|jpeg|JPEG';
                          $config2['maintain_ratio'] = TRUE;
                          $config2['max_size'] = '1024';
                          $config2['width'] = "200";
                          $config2['height'] = "300";

                          $this->image_lib->initialize($config2);
                          if(!$this->image_lib->resize())
                          {
                            echo('<pre>');
                            echo ($this->image_lib->display_errors());
                          }
                          else
                          { 
                            $image= $_POST['photo'];
                          }
                    }      
                    else
                    {
                      $image= '';
                    }      

                    $data = array(
                     'category_id'=>$category_wise_barcodes->category_id,
                      'subcategory_id'=>'9',
                      'asset_type_id'=>'4',
                      'brand_id'=>'6',
                      'unit_id'=>'2',
                      'asset_name'=>'TEST',
                      'product_mrp'=>'200',
                      'description'=>'TEXT',          
                      'created_by'=>$_SESSION[SESSION_NAME]['id'],
                      'photo'=>$image,
                      'created'=> date('Y-m-d H:i:s'),                
                    ); /*$data = array(
                     'category_id'=>$category_wise_barcodes->category_id,
                      'subcategory_id'=>$_POST['subcategory_id'],
                      'asset_type_id'=>$_POST['asset_type_id'],
                      'brand_id'=>$_POST['brand_id'],
                      'unit_id'=>$_POST['unit_id'],
                      'asset_name'=>$_POST['asset_name'],
                      'product_mrp'=>$_POST['price'],
                      'description'=>$_POST['description'],          
                      'created_by'=>$_SESSION[SESSION_NAME]['id'],
                      'photo'=>$image,
                      'created'=> date('Y-m-d H:i:s'),                
                    );*/
                  $this->Crud_model->SaveData("assets",$data);   
                  $asset_id=$this->db->insert_id();  


                  $data = array(
                        'asset_id'=>$asset_id,
                        'barcode_number'=>$category_wise_barcodes->barcode_number,
                        'barcode_image'=>$category_wise_barcodes->barcode_image,
                        'quantity'=>'1',
                        'price'=>'200',
                        'short_desc'=>'TEXT',          
                        'type'=>'From_category',          
                        'status'=>'In_use',          
                        'created_by'=>$_SESSION[SESSION_NAME]['id'],
                        'image'=>$image,
                   );/* $data = array(
                        'asset_id'=>$asset_id,
                        'barcode_number'=>$category_wise_barcodes->barcode_number,
                        'barcode_image'=>$category_wise_barcodes->barcode_image,
                        'quantity'=>'1',
                        'price'=>$_POST['price'],
                        'short_desc'=>$_POST['description'],          
                        'type'=>'From_category',          
                        'status'=>'In_use',          
                        'created_by'=>$_SESSION[SESSION_NAME]['id'],
                        'image'=>$image,
                   );*/
                  $this->Crud_model->SaveData("asset_details",$data);
                  $asset_detail_id=$this->db->insert_id();
                  $asset_quantity='1';
                  $branch_id='1';

            $data=array(
                     'asset_id'=>$asset_id,
                 'branch_id'=>$branch_id,
                'asset_quantity' =>$asset_quantity,
          );
           $this->Crud_model->SaveData("asset_branch_mappings",$data);
           $asset_branch_mappings_id=$this->db->insert_id();


           $data=array(
             'asset_id'=>$asset_id,
             'branch_id'=>$branch_id,
             'asset_branch_mappings_id'=>$asset_branch_mappings_id,
             'asset_detail_id'=>$asset_detail_id,
             'mode_of_transport'=>'text',
             'transport_detail'=>'text',
             /*'mode_of_transport'=>$_POST['mode_of_transport'],
             'transport_detail'=>$_POST['transport_detail'],*/
          );
           $this->Crud_model->SaveData("asset_branch_mappings_details",$data);
      }  
            $assets = $this->Crud_model->GetData('assets',"quantity","id='".$asset_id."'",'','',"","1");   
            $quantity=$assets->quantity + 1;

             $data = array(
                'quantity' => $quantity,
                'modified' => date('Y-m-d H:i:s'),
              );

          $this->Crud_model->SaveData('assets',$data,"id='".$asset_id."'");

           $data = array(
                  'is_used' => 'Yes',
                  'asset_id'=>$asset_id,
                  'modified' => date('Y-m-d H:i:s'),
                );

          $this->Crud_model->SaveData('category_wise_barcodes',$data,"id='".$id."'");

          $financial_years = $this->Crud_model->GetData('financial_years',"id","status='Active'",'','',"","1");
      $categories = $this->Crud_model->GetData('categories',"title","id='".$category_wise_barcodes->category_id."'",'','',"","1");
          $employees = $this->Crud_model->GetData('employees',"name","id='".$_SESSION[SESSION_NAME]['id']."'",'','',"","1");

          $desc="Receive 1 quantity against ".$categories->title." scan by ".$employees->name;

          $data=array(
           'financial_year_id'=>$financial_years->id,
           'asset_id'=>$asset_id,
           'branch_id'=>$branch_id,
           'asset_detail_id'=>$asset_detail_id,
           'quantity'=>'1',
           'available_quantity'=>$asset_quantity,
           'type'=>'OpenStock',
           'description'=>$desc,
           'date'=>date('Y-m-d'),
        );
      $this->Crud_model->SaveData("stock_logs",$data);
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Asset has been updated successfully</span>');
            redirect('Assets/index');
 }

public function asset_detail()
{
    $id = $this->input->post("id"); 

    $row = $this->Crud_model->GetData("assets","product_mrp","id='".$id."'","","","","1");

    $data = array('product_mrp' => $row->product_mrp, );
    echo json_encode($data);
}
public function get_desc()
 {
    $id = $this->input->post("id"); 
    $row = $this->Crud_model->GetData("schedule_category_assets","remarks","id='".$id."'");
    $data = array('description' => $row[0]->remarks, );
    echo json_encode($data);
 }
public function _rules($id)
{
       $cond = "employee_id='".$this->input->post('employee_id')."' and category_id='".$this->input->post('category_id')."' and status='Pending' and id!='$id'";
        $row = $this->Crud_model->GetData('schedule_categories','',$cond,'','','','1');
        $count = count($row); 
        if($count==0)
        {
            $is_unique = "";
        }
        else 
        {
            $is_unique = "|is_unique[schedule_categories.category_id]";

        } 
        $this->form_validation->set_rules('category_id', 'Banner Title', 'trim|required'.$is_unique,
        array(
            'required'      => 'Required',
            'is_unique'     => 'This category already assign to this employee'
          )); 
        

    $this->form_validation->set_rules('id', 'id', 'trim');
    $this->form_validation->set_error_delimiters('<span style="color: red">', '</span>');   
}
    
}
