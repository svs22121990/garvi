<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Daily_Sales_Summary_model extends CI_Model
{
    public $table = 'dispatch';                            
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = '';//array('i.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }

     private function _get_datatables_query($con)
    {
        $this->db->select('ide.invoice_id');
        $this->db->from("invoice_details ide");
        $this->db->join("assets a","a.id = ide.product_id","right");
        $this->db->join("invoice i","i.id = ide.invoice_id","right");
        $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->where($con);
        $query = $this->db->get();
        $arr = array_column($query->result_array(),"invoice_id");

        //$this->db->select('i.*,ide.*, a.*');
        $this->db->select(
            'i.id,
            i.invoice_no,
            i.date_of_invoice,
            (select sum(net_amount) from invoice_details where invoice_id=i.id) as net_amount,
            (select sum(quantity) from invoice_details where invoice_id=i.id) as quantity,
            (select sum(rate_per_item) from invoice_details where invoice_id=i.id) as rate_per_item,
            (select sum(total) from invoice_details where invoice_id=i.id) as total,
            (select sum(discount_1 * rate_per_item / 100) from invoice_details where invoice_id=i.id) as discount_1,
            (select sum(discount_2 * rate_per_item / 100) from invoice_details where invoice_id=i.id) as discount_2,
            (select sum(discount_3 * rate_per_item / 100) from invoice_details where invoice_id=i.id) as discount_3,
            (select sum(discount_amount) from invoice_details where invoice_id=i.id) as discount_amount,
            (select sum(taxable) from invoice_details where invoice_id=i.id) as taxable,
            (select sum(cgst_amount) from invoice_details where invoice_id=i.id) as cgst_amount,
            (select sum(sgst_amount) from invoice_details where invoice_id=i.id) as sgst_amount,
            (select sum(igst_amount) from invoice_details where invoice_id=i.id) as igst_amount,
            (select label from sales_type where id=i.invoice_sales_type) as salesType,
            (select type from payment_types where id=i.payment_mode) as paymentMode,

            ');
        $this->db->from("invoice i");
        //$this->db->join("invoice_details ide","i.id = ide.invoice_id","right");
        //$this->db->join("assets a","a.id = ide.product_id","right");
        $this->db->order_by('i.id', "asc");
        //$this->db->group_by('i.date_of_invoice');
        if(count($arr) > 0)
            $this->db->where_in('i.id',$arr);
        else
            $this->db->where('i.id',0);
        //$this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
        
    }
 
    function get_datatables($con,$date)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
        //$this->db->limit($_POST['length'], $_POST['start']);
		if($date!=0){
			$dates = explode("_",$date);
			$date1 = date("Y-m-d", strtotime($dates[0]));
			$date2 = date("Y-m-d", strtotime($dates[1]));
			$this->db->where('i.date_of_invoice >=', $date1);
            $this->db->where('i.date_of_invoice <=', $date2);
		}
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($con)
    {
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($con)
    {
        $this->db->select('i.*,ide.*, a.*');
        $this->db->from("dispatch i");
        $this->db->join("dispatch_details ide","i.id = ide.dispatch_id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('ast.asset_name,ast.id,ast.category_id,ast.asset_type_id,ast.cgst_asset,ast.sgst_asset,ast.final_amount,ast.product_mrp,ast.discount_amount,ast.quantity,cat.title,mat.type,ast.warranty_type,ast.warranty_from_date,ast.warranty_to_date,ast.warranty_description,ast.sku,ast.hsn,ast.gst_percent,ast.lf_no');        
        $this->db->join("categories cat","cat.id = ast.category_id","left");
        $this->db->join("mst_asset_types mat","mat.id = ast.asset_type_id","left");
        $this->db->where("ast.product_id='".$id."'");     
        return $this->db->get('assets ast')->row();        
    }


    public function getAssetsDetails($assetId)
    {
        $this->db->select('abm.*,assets.asset_name,branches.branch_title');       
        $this->db->join("assets","assets.id = abm.asset_id","left");                        
        $this->db->join("branches","branches.id = abm.branch_id","left");                        
        $this->db->where("abm.asset_id='".$assetId."'");     
        return $this->db->get('asset_branch_mappings abm')->result();
    }


    function ExportCSV($con)
    { 
        $this->db->select('i.*,ide.*, a.*');
        $this->db->from("dispatch i");
        $this->db->join("dispatch_details ide","i.id = ide.dispatch_id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->where($con);
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get();
        return $query->result();              
    }

   
}