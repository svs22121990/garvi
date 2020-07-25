<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bank_Reconcillation_model extends CI_Model
{
    public $table = 'invoice';                            
    var $column_order = array(null,'cat.title','prdt.lable','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','prdt.lable','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = '';//array('i.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }

     private function _get_datatables_query($con)
    {
        $this->db->select(
            'i.*,
            i.id as iid,
            (select label from sales_type where id=i.invoice_sales_type) as salesType,
            (select type from payment_types where id=i.payment_mode) as paymentMode,
            ide.id as id,
            ide.rate_per_item as rate_per_item,
            ide.total as total,
            ide.discount_1 as discount_1,
            ide.discount_2 as discount_2,
            ide.discount_3 as discount_3,
            ide.taxable as taxable,
            ide.adjustment_plus as adjustment_plus,
            ide.adjustment_minus as adjustment_minus,
            ide.quantity as invoice_quantity,
            ide.amount_after_gst as amount_after_gst,
            ide.shipping_charges as shipping_charges,
            ide.net_amount as net_amount,
            a.asset_name as asset_name,
            a.hsn as hsn,
            (select label from product_type where id=a.product_type_id) as productType,
            (select type from mst_asset_types where id=a.asset_type_id) as assetType,
            ide.cgst_rate,
            ide.cgst_amount,
            ide.sgst_rate,
            ide.igst_rate,
            ide.sgst_amount,
            ide.igst_amount,
            b.*,
            ide.gst_rate,
            ide.gst_amount
            ');
        $this->db->from("invoice i");
        $this->db->join("invoice_details ide","i.id = ide.invoice_id","left");
        $this->db->join("bank_reconcillation b","b.invoice_id = i.id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->join("mst_asset_types mat","mat.id = a.asset_type_id","left");
        $this->db->join("product_type prdt","prdt.id = a.product_type_id","left");
        $this->db->where($con);
        $this->db->where('i.status','Active');
        $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
       /* $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }*/
         
        /*if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }*/
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
        $this->db->from("invoice i");
        $this->db->join("invoice_details ide","i.id = ide.invoice_id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('ast.asset_name,ast.id,ast.category_id,ast.asset_type_id,ast.cgst_asset,ast.sgst_asset,ast.final_amount,ast.product_mrp,ast.discount_amount,ast.quantity,cat.title,mat.type,ast.warranty_type,ast.warranty_from_date,ast.warranty_to_date,ast.warranty_description,ast.sku,ast.hsn,ast.gst_percent,ast.lf_no');        
        $this->db->join("categories cat","cat.id = ast.category_id","left");
        $this->db->join("mst_asset_types mat","mat.id = ast.asset_type_id","left");
        $this->db->join("product_type prdt","prdt.id = ast.product_type_id","left");
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
        $this->db->select('i.*,ide.*, a.*, br.*');
        $this->db->from("invoice i");
        $this->db->join("invoice_details ide","i.id = ide.invoice_id","left");
        $this->db->join("bank_reconcillation br","i.id = br.invoice_id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->where($con);
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get();
        return $query->result();              
    }

   
}