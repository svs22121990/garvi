<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_model extends CI_Model
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
        $this->db->select('*');
        $this->db->from("employees e");
        $this->db->where('type','User');
        $this->db->where($con);
        /*$i = 0;
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
 
    function get_datatables($con)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
        //$this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->select('*');
        $this->db->from("employees e");
        $this->db->where('type','User');
        $this->db->where($con);
        //$this->db->group_by("a.created_by");
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

    public function count_inventory($where){
        $this->db->select('*');
        $this->db->from('assets as a');
        $this->db->join('products as p','p.id=a.product_id','left');
        $this->db->where($where);
        $query = $this->db->get();
        $result = $query->result_array();
        $sum = 0;
        foreach($result as $r){
            $sum += $r['quantity'] * $r['product_mrp'];
        }
        return $sum;
    }

   
}