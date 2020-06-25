<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Urlcheck
{
   /*public $CI;
    public function __contruct()
    {
        $this->CI =& get_instance();
        $this->CI->config->item('base_url');
        $this->CI->load->helper('url');
        $this->CI->load->database();
        $this->CI->load->library('session');
        $this->CI->load->model('Crud_model');
      
    }*/    
    private $CI;

    function __construct() {

        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->model('Crud_model');
    }
    /* ----- Develop By Priyanka Thool----- */
    /*------ function for Url Security Start-------*/
 function urlch($contname,$subm_id,$Crud_model)
 { //print_r($Crud_model);exit;
    if($contname != 'Welcome' and $contname != 'Dashboard' and isset($_SESSION[SESSION_NAME]['role_id']) and $subm_id!='ajax_manage_page')
    { 
        if($_SESSION[SESSION_NAME]['is_superadmin']!='1')
        {
          //print_r($contname); exit;
          $submenu =$Crud_model->GetData('ra_application_menus',"","controller_name ='".$contname."'","","",'',"single");
         // print_r($this->db->last_query()); exit;
          $conPermiss = "role_id='".$_SESSION[SESSION_NAME]['role_id']."' and application_menu_id='".$submenu->id."' and access_user_id='".$_SESSION[SESSION_NAME]['id']."'";
        //   print_r($conPermiss); exit;
          $getdata =$Crud_model->getuserpermission($conPermiss);
          //print_r($this->CI->db->last_query());
          //echo "<pre>";
         //print_r($getdata); exit;
         //echo "<pre>"; 
          if(count($getdata)>0)
          {
             if($subm_id=='index'){ $var = $getdata[0]->page_list; }
            else if($subm_id=='create' || $subm_id=='create_action' ){ $var = $getdata[0]->page_create; }
            else if($subm_id=='create_action' ){ $var = $getdata[0]->page_create_action; }
            else if($subm_id=='update' || $subm_id=='update_action' ){ $var = $getdata[0]->page_update; }
            else if($subm_id=='update_action' ){ $var = $getdata[0]->page_update_action; }
            else if($subm_id=='read' || $subm_id=='read_action'){ $var = $getdata[0]->page_view; }
            else if($subm_id=='delete'){ $var = $getdata[0]->page_delete; }
            else if($subm_id=='import'){ $var = $getdata[0]->page_import_to_excel; }
            else if($subm_id=='export'){ $var = $getdata[0]->page_export_to_excel; }
            else if($subm_id=='download_format'){ $var = $getdata[0]->page_download_format; }
            else if($subm_id=='generate_pdf'){ $var = $getdata[0]->page_generate_pdf; }
            else if($subm_id=='changeStatus'){ $var = $getdata[0]->page_record_status; }
            else if($subm_id=='checkTitle'){ $var = $getdata[0]->page_checkTitle; }
            else if($subm_id=='user_update' || $subm_id=='user_update_action'){ $var = $getdata[0]->page_user_update; }
            else if($subm_id=='role_edit' || $subm_id=='roleEdit_action'){ $var = $getdata[0]->page_role_edit; }
            else if($subm_id=='shipping_details'){ $var = $getdata[0]->page_shipping_details; }
            else if($subm_id=='ajax_shipping'){ $var = $getdata[0]->page_ajax_shipping; }
            else if($subm_id=='cart' || $subm_id=='ajax_cart'){ $var = $getdata[0]->page_cart; }
            else if($subm_id=='validation'){ $var = $getdata[0]->page_validation; }
            else if($subm_id=='ship_view'){ $var = $getdata[0]->page_ship_view; }
            else if($subm_id=='orders' || $subm_id=='ajax_orders'){ $var = $getdata[0]->page_orders; }
            else if($subm_id=='invoice' || $subm_id=='create_action'){ $var = $getdata[0]->page_invoice; }
            else if($subm_id=='activeCustomerDetails' || $subm_id=='ajax_manage_page_activeCustomerDetails'){ $var = $getdata[0]->page_activeCustomerDetails; }
            else if($subm_id=='products_details' || $subm_id=='ajax_manage_page_productDetails'){ $var = $getdata[0]->page_products_details; }
            else if($subm_id=='cities_by_states'){ $var = $getdata[0]->page_cities_by_states; }
            else if($subm_id=='addManufacturer'){ $var = $getdata[0]->page_addManufacturer; }
            else if($subm_id=='temp_brand'){ $var = $getdata[0]->page_temp_brand; }
            else if($subm_id=='update_tag' || $subm_id=='update_tagaction'){ $var = $getdata[0]->page_update_tag; }
            else if($subm_id=='GetVendorData'){ $var = $getdata[0]->page_GetVendorData; }
            else if($subm_id=='makePayment'){ $var = $getdata[0]->page_makePayment; }
            else if($subm_id=='make_confirm_payment'){ $var = $getdata[0]->page_make_confirm_payment; }
             else if($subm_id=='addwight' || $subm_id=='create_weight_action'){ $var = $getdata[0]->page_addwight; }
            else if($subm_id=='getsubcategory'){ $var = $getdata[0]->page_getsubcategory; }
            else if($subm_id=='create_brand'){ $var = $getdata[0]->page_create_brand; }
            else if($subm_id=='create_offer'){ $var = $getdata[0]->page_create_offer; }
            else if($subm_id=='deletedata'){ $var = $getdata[0]->page_deletedata; }
            else if($subm_id=='changestatusweight'){ $var = $getdata[0]->page_changestatusweight; }
            else if($subm_id=='stock' || $subm_id=='ajax_stock'){ $var = $getdata[0]->page_stock; }
            else if($subm_id=='ProductStock' || $subm_id=='ajax_productStock' ){ $var = $getdata[0]->page_ProductStock; }
            else if($subm_id=='changeDefault'){ $var = $getdata[0]->page_changeDefault; }
            else if($subm_id=='getcity'){ $var = $getdata[0]->page_getcity; }
            else if($subm_id=='getarea'){ $var = $getdata[0]->page_getarea; }
            else if($subm_id=='save_receive_order'){ $var = $getdata[0]->page_save_receive_order; }
            else if($subm_id=='GetSubcategory'){ $var = $getdata[0]->page_GetSubcategory; }
            else if($subm_id=='GetProduct'){ $var = $getdata[0]->page_GetProduct; }
            else if($subm_id=='GetVariant'){ $var = $getdata[0]->page_GetVariant; }
            else if($subm_id=='LotTracking'){ $var = $getdata[0]->page_LotTracking; }
            else if($subm_id=='purchase_detail'){ $var = $getdata[0]->page_purchase_detail; }
            else if($subm_id=='print_lot'){ $var = $getdata[0]->page_print_lot; }
            else if($subm_id=='LotTrackingDetails'){ $var = $getdata[0]->page_LotTrackingDetails; }
            else if($subm_id=='loginDetails'){ $var = $getdata[0]->page_loginDetails; }
            else if($subm_id=='change_password' | $subm_id=='change_password_action' ){ $var = $getdata[0]->page_change_password; }
            else if($subm_id=='GetWarehouseProduct'){ $var = $getdata[0]->page_GetWarehouseProduct; }
            else if($subm_id=='GetQty'){ $var = $getdata[0]->page_GetQty; }
            else if($subm_id=='OrderDetails'){ $var = $getdata[0]->page_OrderDetails; }
            else if($subm_id=='returned'){ $var = $getdata[0]->page_returned; }
            else if($subm_id=='process_order'){ $var = $getdata[0]->page_process_order; }
            else if($subm_id=='deleteMenu'){ $var = $getdata[0]->page_deleteMenu; }
            else if($subm_id=='subscription'){ $var = $getdata[0]->page_subscription; }
            else if($subm_id=='wishlist'){ $var = $getdata[0]->page_wishlist; }
            else if($subm_id=='addMenus' | $subm_id=='addMenus_action'){ $var = $getdata[0]->page_addMenus; }
            else if($subm_id=='checkEmail'){ $var = $getdata[0]->page_checkEmail; }
            else if($subm_id=='checkMobile'){ $var = $getdata[0]->page_checkMobile; }
            else if($subm_id=='distributionCenterList'){ $var = $getdata[0]->page_distributionCenterList; }
            else if($subm_id=='indexdistributionCenter' | $subm_id=='ajax_manage_distributionCenter' ){ $var = $getdata[0]->page_indexdistributionCenter; }
            else if($subm_id=='createDistributionCenter' | $subm_id=='createDistributionCenter_action' ){ $var = $getdata[0]->page_createDistributionCenter; }
            else if($subm_id=='updateDistributionCenter' | $subm_id=='updateDistributionCenter_action' ){ $var = $getdata[0]->page_updateDistributionCenter; }
            else if($subm_id=='changeStatusDistributionCenter'){ $var = $getdata[0]->page_changeStatusDistributionCenter; }
            else if($subm_id=='deleteDistributionCenter'){ $var = $getdata[0]->page_deleteDistributionCenter; }

            if($var !='1')
            {
              redirect('Welcome/Locks');
            }
          }
          else
          {//print_r("hello"); exit;
            redirect('Welcome/Locks');
           }
      }
    }
 }  
     /*------ function for Url Security End-------*/

   public function getFunctions($subMenuId)
   {
      $conPermiss = "role_id='".$_SESSION[SESSION_NAME]['role_id']."' and application_menu_id='".$subMenuId."' and access_user_id='".$_SESSION[SESSION_NAME]['id']."'";
      $getAllPermissions = $this->CI->Crud_model->getuserpermission($conPermiss); 
      //echo "<pre>";
     //print_r($getAllPermissions); exit;
      return $getAllPermissions;
   }
}

?>