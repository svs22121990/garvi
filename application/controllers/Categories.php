<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

  function __construct()
  {
  parent::__construct();
  $this->load->model('Categories_model');
  $this->load->model('Category_barcode_model');
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
              if($menu->value=='Categories')
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
                    <li class='active'>Manage Categories</li>
                    </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '2' ,'ajax_manage_page' => site_url('Categories/ajax_manage_page') , 'heading' => 'Manage Categories','addPermission'=>$add,'import' =>$import_buton,'importPermission' =>$import);


    $this->load->view('categories/list',$data);
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
        $Data = $this->Categories_model->get_datatables(); 
        foreach($_SESSION[SESSION_NAME]['getMenus'] as $menu)
        { 
          foreach($menu as $row)
          {

              if($row->value=='Categories')
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
          if(!empty($act_add_existing_stock)){
            $getCountofGenB=count($this->Crud_model->GetData('category_wise_barcodes','',"category_id='".$row->id."'",'','','',''));
            if($getCountofGenB > 0){
              $tite="View Generated Barcodes";
              $fa="fa fa-eye";
            }else{ 
              $tite="Generate Barcodes";
              $fa="fa fa-barcode";

            }

              //$btn .='&nbsp;|&nbsp;'.'<a href="'.site_url('Categories/generateBarcode/').$row->id.'" title="'.$tite.'" class="btn btn-primary btn-circle btn-sm"><i class="ace-icon '.$fa.' bigger-130"></i></a>';
            
            

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
                    "recordsTotal" => $this->Categories_model->count_all(),
                    "recordsFiltered" => $this->Categories_model->count_filtered(),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }

    public function generateBarcode($cat_id,$flag='')
    {
      $getBarcodes=$this->Crud_model->GetData('category_wise_barcodes','',"category_id='".$cat_id."'",'','','','row');
      $row = $this->Crud_model->GetData('categories','',"id='".$cat_id."'",'','','','row');       
      if(empty($row)){
        redirect('Categories/index');
      } 
      if(empty($getBarcodes) || $flag!=''){
        $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('Categories')."'>Manage Categories</a></li>
                    <li class='active'>Generate Barcodes</li>
                    </ul>";
        $data = array('breadcrumbs' => $breadcrumbs , 'heading' => 'Generate Barcode','category_name'=> $row->title,'id'=>$cat_id );
        $this->load->view('categories/barcode_form',$data);
      }else{

          $breadcrumbs = "<ul class='breadcrumb'>
                    <li>
                        <i class='ace-icon fa fa-home home-icon'></i>
                        <a href='".site_url('Dashboard')."'>Dashboard</a>
                    </li>
                    <li class=''> <a href='".site_url('Categories')."'>Manage Categories</a></li>
                    <li class='active'>Generated Barcodes</li>
                    </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '5' ,'ajax_manage_page' => site_url('Categories/ajax_barcode_manage_page/'.$cat_id) , 'heading' => 'Generated Barcode List','category_name'=> $row->title,'id'=>$cat_id);


       $this->load->view('categories/barcode_list',$data);

      }
    }
     private function set_barcode($code)
      {
        //load library
        $this->load->library('Zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        $newCode = $code;
       
        $file = Zend_Barcode::draw('code128', 'image', array('text' => $newCode,'drawText' =>true), array());
        $code = time().$code;
        $store_image = imagepng($file,"../admin/assets/purchaseOrder_barcode/{$code}.png");
        return $code.'.png';
      }
   
    public function getstickerData($cat_id)
    { 
      $getOldData=$this->Crud_model->GetData('temp_category_wise_barcode',"","category_id='".$cat_id."'");
      if(!empty($getOldData)){
        foreach ($getOldData as $key ) {
          unlink('../admin/assets/purchaseOrder_barcode/'.$key->barcode_image);
          $this->Crud_model->DeleteData('temp_category_wise_barcode',"id='".$key->id."'");
        }
      }
      for ($j=0; $j < $_POST['qty']; $j++) 
      { 
        $barcodeData = array(
          'category_id'=>$cat_id,
          'quantity'=>1,
        );
        $this->Crud_model->SaveData('temp_category_wise_barcode',$barcodeData);
        $barcodeId = $this->db->insert_id(); 
        $barcode_number = $barcodeId.'020'.rand('1111','9999');
        $barcodeImg = $this->set_barcode($barcode_number);
        $barcodeData = array(
          'barcode_image'=>$barcodeImg,
          'barcode_no'=>$barcode_number,
        );

        $this->Crud_model->SaveData('temp_category_wise_barcode',$barcodeData,"id='".$barcodeId."'");
      }

      $getData=$this->Crud_model->GetData('temp_category_wise_barcode',"","category_id='".$cat_id."'");

      $html="<table class='table table-bordered table-striped' ><thead>
      <tr>
      <th>Sr. No</th>
      <th>Barcode No</th>
      <th>Barcode Sticker</th>
      </tr>
      </thead>
      <tbody id='barcodeTbody'>";
      $sr=1;
      foreach ($getData as $key ) {
        $html.="<tr>
        <td>".$sr."</td>
        <td>".$key->barcode_no."</td>
        <td><img src=".base_url('../admin/assets/purchaseOrder_barcode/'.$key->barcode_image)." width='120px'></td>
        </tr>";
        $sr++;   }
        $html.="</tbody>
        </table>";
        echo($html);

      }

    public function saveBarcode($cat_id){
       $getData=$this->Crud_model->GetData('temp_category_wise_barcode',"","category_id='".$cat_id."'");
       $this->Crud_model->DeleteData('temp_category_wise_barcode',"category_id='".$cat_id."'");
        foreach($getData as $row)
        { 
            $barcodeData = array(
                    'category_id'=>$cat_id,
                    'barcode_number'=>$row->barcode_no,
                    'barcode_image'=>$row->barcode_image,
                    'quantity'=>1,
                    'created'=>date('Y-m-d H:i:s'),
                );
          //print_r($barcodeData);exit;
            $this->Crud_model->SaveData('category_wise_barcodes',$barcodeData);
          
        }

      $msg=$_POST['qty']." quantity of category ".$_POST['category_name']." has been generated successfully.";
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">'.$msg.'</span>');
      //redirect('Categories/generateBarcode/'.$cat_id);
    }

    public function ajax_barcode_manage_page($cat_id)
    {
        $con="category_id='".$cat_id."'";
        $Data = $this->Category_barcode_model->get_datatables($con); 
       
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
          $btn ='';
          if($row->is_used=='No'){

            $btn ='<a href="'.site_url('Categories/DeletegeneratedBarcode/').$row->id.'/'.$cat_id.'" title="Generate Barcodes" class="btn btn-danger btn-circle btn-sm" onclick="return confirm(&#34;Are you sure ??&#34;)"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>';
          }
            
              if($row->status=='Active')
              {
                  $status =  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
              }
              else
              {
                  $status =  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
              }
            
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->barcode_number;
            $nestedData[] = "<img src=".base_url('../admin/assets/purchaseOrder_barcode/'.$row->barcode_image)." width='120px'>";            
            $nestedData[] = $row->is_used;       
            $nestedData[] = $status;       
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Category_barcode_model->count_all($con),
                    "recordsFiltered" => $this->Category_barcode_model->count_filtered($con),
                    "data" => $data,
                );
       
        echo json_encode($output);
    }

    public function printgenerateBarcode($cat_id)
    {
      $getBarcodes=$this->Crud_model->GetData('category_wise_barcodes','',"category_id='".$cat_id."' and is_used='No'",'','','','');
      $row = $this->Crud_model->GetData('categories','',"id='".$cat_id."'",'','','','row');  
      
      $data=array(
        'category_name'=> $row->title,
        'barcodes'=> $getBarcodes
      );
      $this->load->view('categories/barcode_print',$data);
    }

    public function DeletegeneratedBarcode($barcodeid,$cat_id){
      if(!empty($key)){


      $this->Crud_model->DeleteData('category_wise_barcodes',"id='".$barcodeid."'");
      unlink('../admin/assets/purchaseOrder_barcode/'.$key->barcode_image);
      $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Record has been deleted successfully</span>');
      }else{
        
      $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px;">Something went wrong</span>');
      }
      redirect(site_url('Categories/generateBarcode/'.$cat_id));
    }

    public function changeBarcodeStatus($cat_id){       
        $getbarCodes = $this->Crud_model->GetData('category_wise_barcodes','',"id='".$_POST['id']."'",'','','','row');
        if($getbarCodes->status=='Active')
        {
            $this->Crud_model->SaveData('category_wise_barcodes',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('category_wise_barcodes',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Categories/generateBarcode/'.$cat_id));
    } 

    public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('categories','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('categories',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('categories',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Categories'));
    }

    public function addData()
      {
        //print_r("fiii");exit;
          $condDuplication = "title='".$this->input->post('name')."'";
          $duplication = $this->Crud_model->GetData('categories','', $condDuplication);

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

              $this->Crud_model->SaveData('categories', $data);
              $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }

      public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('categories','',"id='".$_POST['id']."'",'','','','row');       
        print_r(trim($row->title));
      }

      public function updateData()
      {
          $condDuplication = "title='".$this->input->post('name')."' and id !='".$this->input->post('id')."'";
          $duplication = $this->Crud_model->GetData('categories','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(                
                'title' => $this->input->post('name')                
              );

              $this->Crud_model->SaveData('categories', $data, "id='".$this->input->post('id')."'");
             $this->session->set_flashdata('message', 'success');

              echo "2";
          }  
          
      }


       public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $getcatdata = $this->Crud_model->GetData('assets','id,category_id',"category_id in ('".$_POST['id']."')");
        if(!empty($getcatdata))
        {
          $this->session->set_flashdata('message', '<span class="label label-danger" style="margin-bottom:0px">Category already mapped with assets</span>');
          redirect(site_url('Categories'));
        }
        else
        {
          $data = array('is_delete' =>'Yes',);
          $this->Crud_model->DeleteData('categories',$con);
          $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Category has been deleted successfully</span>');
          redirect(site_url('Categories'));
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
            redirect(site_url('Categories/index'));            
        } 
        $data = $fields_fun;
        if(count($val) == '1')
        {
            $exists = 0;
            foreach ($data as $val) 
            {  
                if(($val[0]!=''))
                {
                      if($val[0]!='')
                      {
                        $getCategories= $this->Crud_model->GetData('categories','title',"title='".$val[0]."'",'','','','single'); 
                        /*for already exist check*/
                        
                        if(empty($getCategories))
                        {
                            $data = array(
                                          'title' => $val[0],
                                          );
                            $SaveAssets = $this->Crud_model->SaveData('categories',$data);
                        }
                        else
                        { 
                            $existAssets[]=array($val[0],'Category Name already exist');
                        }
                   
                      }
                      else
                      {
                          $existAssets[]=array($val[0],'Mandatory fields empty');
                      }
                } 
            }
          }
        else
        {
            $this->session->set_flashdata('message', '<span class="label label-danger text-center" style="margin-bottom:0px">Invalid file selected</span>');
            
            redirect(site_url('Categories/index'));  
        }
        if(empty($existAssets))
        {
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Category has been imported successfully</span>');
            redirect('Categories/index');
        }
        else{
            $data = array('existAssets' => $existAssets);
            $this->load->view('categories/duplicateCat',$data);
        } 
}

}
