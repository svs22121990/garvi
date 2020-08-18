<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{
    public $table = 'warehouse_details';
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.total_quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.total_quantity');
    var $order = array('id' => 'desc');
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($con)
    {
        $eid = $_SESSION[SESSION_NAME]['id'];
        $this->db->select('
                p.id,
                p.dn_number,
                p.warehouse_date,
                p.received_from,
                e.name as employee_name,
                (select SUM(cost_total) from warehouse_details where warehouse_id=p.id) as cost_total,
                (select SUM(sp_total) from warehouse_details as a where warehouse_id=p.id) as sp_total,
                (select SUM(sp_total_2) from warehouse_details as a where warehouse_id=p.id) as sp_total_2,
                (select SUM(sp_total * gst_percent / 100) from warehouse_details as a where warehouse_id=p.id) as gst,
            ');
        $this->db->from("warehouse p");
        $this->db->join("employees e","e.id = p.received_from","left");
        //$this->db->join("warehouse_details d","d.warehouse_id = p.id");
//        $this->db->where('warehouse_details.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->where($con);
        $this->db->distinct();
        //$this->db->where('a.created_by',);

    }

    function get_datatables($con,$date)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
        // $this->db->limit($_POST['length'], $_POST['start']);
        if($date!=0){
            $dates = explode("_",$date);
            $date1 = date("Y-m-d", strtotime($dates[0]));
            $date2 = date("Y-m-d", strtotime($dates[1]));
            $this->db->where('p.warehouse_date >=', $date1);
            $this->db->where('p.warehouse_date <=', $date2);
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
        $this->db->select('p.id,p.dn_number,p.warehouse_date,p.received_from,e.name as employee_name');
        $this->db->from("warehouse p");
        $this->db->join("employees e","e.id = p.received_from","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('
            ast.asset_name,
            ast.id,
            ast.category_id,
            ast.asset_type_id,
            ast.product_type_id,
            ast.product_mrp,
            ast.product_mrp_2,
            ast.sp_total,
             ast.sp_total_2,
            ast.price,
            ast.quantity,
            ast.size_id,
            ast.color_id,
            ast.fabric_id,
            ast.craft_id,
            cat.title,
            mat.type,
            pro.label,
            siz.title as size,
            col.title as color,
            fab.title as fabric,
            cra.title as craft,
            cat.title,
            ast.hsn,
            ast.gst_percent,
            ast.cost_total,
            ast.purchase_date,
            ast.markup_percent,
            ast.markup_percent2,
            p.dn_number,
            p.warehouse_date,
            p.received_from,
            e.name as employee_name,'
        );
        $this->db->join("size siz","siz.id = ast.size_id","left");
        $this->db->join("color col","col.id = ast.color_id","left");
        $this->db->join("fabric fab","fab.id = ast.fabric_id","left");
        $this->db->join("craft cra","cra.id = ast.craft_id","left");
        $this->db->join("categories cat","cat.id = ast.category_id","left");
        $this->db->join("mst_asset_types mat","mat.id = ast.asset_type_id","left");
        $this->db->join("product_type pro","pro.id = ast.product_type_id","left");
        $this->db->join("warehouse p", "p.id = ast.warehouse_id", "left");
        $this->db->join("employees e","e.id = p.received_from","left");
        $this->db->where("ast.warehouse_id='".$id."'");
        return $this->db->get('warehouse_details ast')->result();
    }

    public function getSingleDetails($id)
    {
        $this->db->select('
            ast.asset_name,
            ast.id,
            ast.category_id,
            ast.asset_type_id,
            ast.product_type_id,
            ast.product_mrp,
            ast.product_mrp_2,
            ast.sp_total,
            ast.sp_total_2,
            ast.price,
            ast.quantity,
            ast.available_qty,
            ast.barcode_number,
            ast.size_id,
            ast.color_id,
            ast.fabric_id,
            ast.craft_id,
            cat.title,
            mat.type,
            pro.label,
            siz.title as size,
            col.title as color,
            fab.title as fabric,
            cra.title as craft,
            cat.title,
            ast.hsn,
            ast.gst_percent,
            ast.cost_total,
            ast.purchase_date,
            ast.markup_percent,
            ast.markup_percent2,
            p.dn_number,
            p.warehouse_date,
            p.received_from,
            e.name as employee_name,'
        );
        $this->db->join("size siz","siz.id = ast.size_id","left");
        $this->db->join("color col","col.id = ast.color_id","left");
        $this->db->join("fabric fab","fab.id = ast.fabric_id","left");
        $this->db->join("craft cra","cra.id = ast.craft_id","left");
        $this->db->join("categories cat","cat.id = ast.category_id","left");
        $this->db->join("mst_asset_types mat","mat.id = ast.asset_type_id","left");
        $this->db->join("product_type pro","pro.id = ast.product_type_id","left");
        $this->db->join("warehouse p", "p.id = ast.warehouse_id", "left");
        $this->db->join("employees e","e.id = p.received_from","left");
        $this->db->where("ast.id='".$id."'");
        return $this->db->get('warehouse_details ast')->result();
    }


    public function getAssetsDetails($assetId)
    {
        $this->db->select('abm.*,assets.asset_name,branches.branch_title');
        $this->db->join("assets","assets.id = abm.asset_id","left");
        $this->db->join("branches","branches.id = abm.branch_id","left");
        $this->db->where("abm.asset_id='".$assetId."'");
        return $this->db->get('asset_branch_mappings abm')->result();
    }





}