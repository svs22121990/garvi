<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_Summary_model extends CI_Model
{
    public $table = 'assets';                            
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = array('p.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }

     private function _get_datatables_query($con)
    {
        $this->db->select('
            p.purchase_date, 
            a.asset_name,
            a.asset_type_id,
            a.product_type_id,
            a.product_mrp, 
            a.total_quantity, 
            a.quantity,
            a.gst_percent, 
            a.damage_qty, 
            a.id,
            a.hsn,
            a.barcode_number,
            siz.title as size,
            col.title as color,
            fab.title as fabric,
            cra.title as craft,
            a.purchase_date as product_purchase_date, 
            (select label from product_type where id=a.product_type_id) as product_type,
            c.title, 
            t.type
            ');
        $this->db->from("products p");
        $this->db->join("assets a","a.product_id = p.id","left");
        $this->db->join("categories c","a.category_id = c.id","left");
        $this->db->join("mst_asset_types t","t.id = a.asset_type_id","left");
        $this->db->join("size siz","siz.id = a.size_id","left");
        $this->db->join("color col","col.id = a.color_id","left");
        $this->db->join("fabric fab","fab.id = a.fabric_id","left");
        $this->db->join("craft cra","cra.id = a.craft_id","left");
        $this->db->where($con);
        $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
        
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
        }
         
        if(isset($_POST['order'])) // here order processing
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
			$this->db->where('a.purchase_date >=', $date1);
			$this->db->where('a.purchase_date <=', $date2);
		}else{
			$this->db->where('a.purchase_date >=', $date);
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
        $this->db->select('p.*,a.*,c.*');
        $this->db->from("products p");
        $this->db->join("assets a","a.product_id = p.id","left");
        $this->db->join("categories c","a.category_id = c.id","left");
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
        $this->db->select('p.*,a.*,c.*');
        $this->db->from("products p");
        $this->db->join("assets a","a.product_id = p.id","left");
        $this->db->join("categories c","a.category_id = c.id","left");
        $this->db->where('a.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->where($con);
        $this->db->order_by('p.id','DESC');
        $query = $this->db->get();
        return $query->result();              
    }

   
}