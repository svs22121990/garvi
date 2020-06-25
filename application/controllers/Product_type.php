<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_type extends CI_Controller {
  function __construct()
  {
  parent::__construct();
  $this->load->model('Asset_types_model');
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
              if($menu->value=='Product_type')
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
                          <li class='active'>Manage Product Type</li>
                        </ul>";
        $data = array('breadcrumbs' => $breadcrumbs ,'actioncolumn' => '3' ,'ajax_manage_page' => site_url('Product_type/ajax_manage_page') , 'heading' => 'Manage Product Type','addPermission'=>$add);
        $this->load->view('assets_type/list',$data);
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
            if($menu->value=='Product_type')
            { 
              if(!empty($menu->act_edit)){ $edit='1'; }else{ $edit='0'; }
                if(!empty($menu->act_delete)){ $delete='1'; }else{ $delete='0'; }
                if(!empty($menu->act_status)){  $actstatus='1';}else{ $actstatus='0';}
                if(!empty($menu->act_add)){ $add='1'; }else{ $add='0'; }
            }
        }
      }

        $Data = $this->Asset_types_model->get_datatables(); 
        $data = array();       
        $no=0; 
        foreach($Data as $row) 
        {  
           $status = '';
           
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
                  $status .=  "<a href='#checkStatus' data-toggle='modal' class='label-success label' onclick='checkStatus(".$row->id.")'> Active </a>";            
              }
              else
              {
                  $status .=  "<a href='#checkStatus' data-toggle='modal'  class='label-warning label' onclick='checkStatus(".$row->id.")'> Inactive </a>";
              }
            }
            //print_r($actstatus);exit;
            
            $no++;
            $nestedData = array();
            $nestedData[] = $no ;
            $nestedData[] = $row->type;
            /*$nestedData[] = $row->is_sellable;
            $nestedData[] = $row->is_barcode;*/
            $nestedData[] = $status;            
            $nestedData[] = $btn;       
            $data[] = $nestedData;
            $selected = '';
        }

        $output = array(
                    //"draw" => $_POST['draw'],
                    "recordsTotal" => $this->Asset_types_model->count_all(),
                    "recordsFiltered" => $this->Asset_types_model->count_filtered(),
                    "data" => $data,
                );
        
       
        echo json_encode($output);
    }


    public function changeStatus(){       
        $getasset_types = $this->Crud_model->GetData('mst_asset_types','',"id='".$_POST['id']."'",'','','','row');
        if($getasset_types->status=='Active')
        {
            $this->Crud_model->SaveData('mst_asset_types',array('status'=>'Inactive'),"id='".$_POST['id']."'");
        }
        else
        {
            $this->Crud_model->SaveData('mst_asset_types',array('status'=>'Active'),"id='".$_POST['id']."'");
        }
        $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px;">Status has been changed successfully</span>');
        redirect(site_url('Product_type'));
    }

    public function addData()
      {
        //print_r($_POST);exit;
          $condDuplication = "type='".$this->input->post('name')."'";
          $duplication = $this->Crud_model->GetData('mst_asset_types','', $condDuplication);

          if(count($duplication) > 0 )
          {
            echo "1";
          }
          else
          {
              $data = array(
                'type' => $this->input->post('name'),
                /*'is_sellable' => $this->input->post('salable'),
                'is_barcode' => $this->input->post('barcode'),*/
                'status' => 'Active',
                'is_delete' => 'No',
                'created' => date('Y-m-d H:i:s'),
              );

              $this->Crud_model->SaveData('mst_asset_types', $data);
              $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Product type has been added successfully</span>');

              echo "2";
          }  
          
      }

      /*public function getUpdateName()
      {
        $row = $this->Crud_model->GetData('mst_asset_types','',"id='".$_POST['id']."'",'','','','row');       
        print_r(trim($row->type));
      }*/

      public function getUpdateName()
      {
        $rowassettype= $this->Crud_model->GetData('mst_asset_types','',"id='".$_POST['id']."'",'','','','row');  
        $getpurchaseOrder = $this->Crud_model->GetData('purchase_orders',"","asset_type_id='".$_POST['id']."'");
        if(count($getpurchaseOrder) > 0)
        {
          $disabled = 'disabled';
        }  
        else
        {
          $disabled = '';
        } 
        if(!empty($rowassettype))
        {
            $rowassetTypeData['success'] = '1';
            $rowassetTypeData['type'] = $rowassettype->type;                     
            $rowassetTypeData['asset_type_id'] = $rowassettype->id;                     
            /*$rowassetTypeData['is_sellable'] = $rowassettype->is_sellable;                     
            $rowassetTypeData['is_barcode'] = $rowassettype->is_barcode; */                    
            $rowassetTypeData['disabled'] =  $disabled;                     
        }
        else
        {
            $rowassetTypeData['success'] = '0';
        }       
        echo json_encode($rowassetTypeData);
      }

    public function update_action()
    {     

        $data = array(
            'type'=>$_POST['nameEdit'],            
            /*'is_sellable'=>$_POST['saleableEdit'],                                                                    
            'is_barcode'=>$_POST['barcodeEdit'],*/                                                                    
            'modified'=>date('Y-m-d H:i:s'),            
            );                  
            $this->Crud_model->SaveData("mst_asset_types",$data,"id='".$_POST['updateId']."'");      
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Product type has been updated successfully</span>');
            redirect('Product_type/index');
     
    }

      public function updateData()
      {
        //print_r($_POST);exit;
          $condDuplication = "type='".$this->input->post('name')."' and id !='".$this->input->post('id')."' and is_delete='No'";
          $duplication = $this->Crud_model->GetData('mst_asset_types','', $condDuplication);
          if(count($duplication) > 0 )
          {
            echo "1";exit;
          }
          else
          {
              $data = array(                
                'type' => $this->input->post('name'),
                /*'is_sellable' => $this->input->post('salableEdit'),
                'is_barcode' => $this->input->post('barcodeEdit'),*/                
                'modified' => date('Y-m-d H:i:s'),                
              );

            $this->Crud_model->SaveData('mst_asset_types', $data, "id='".$this->input->post('id')."'");
            $this->session->set_flashdata('message', 'success');

              echo "2";
          }            
      }


       public function delete()
      {
        $con = "id='".$_POST['id']."'";
        $getassettypeData = $this->Crud_model->GetData('assets',"","asset_type_id in ('".$_POST['id']."')");
        //print_r($getassettypeData);exit;
        if(!empty($getassettypeData))
        {
        	//print_r("expression");exit;
            $this->session->set_flashdata('errormessage', '<span class="label label-danger" style="margin-bottom:0px">Product type already mapped with product</span>');
            redirect(site_url('Product_type/index'));
        }
        else
        {
        	//print_r("hi");exit;
            $data = array('is_delete' =>'Yes',);
            $this->Crud_model->SaveData('mst_asset_types',$data,$con);
            $this->session->set_flashdata('message', '<span class="label label-success text-center" style="margin-bottom:0px">Product type has been deleted successfully</span>');
            redirect(site_url('Product_type/index'));
        }
        
      }

    
}
