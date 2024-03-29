<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Assets_maintenance_scheduling_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'assets.asset_name','mst_asset_types.type','asset_details.barcode_image','vendors.name','ams.date',null); //set column field database for datatable orderable
    var $column_search = array('assets.asset_name','mst_asset_types.type','asset_details.barcode_image','asset_details.image','vendors.name','ams.date'); //set column field database for datatable searchable
    var $order = array('ams.id' => 'desc'); // default order
 
    private function _get_datatables_query($cond)
    {
         
        $this->db->select('ams.*,assets.asset_name,mst_asset_types.type,asset_details.barcode_image,vendors.name');
        $this->db->from('assets_maintenance_schedule ams');
        $this->db->join('mst_asset_types', 'mst_asset_types.id=ams.asset_type_id', 'left'); 
        $this->db->join('assets', 'assets.id=ams.asset_id', 'left'); 
        $this->db->join('asset_details','asset_details.id=ams.asset_detail_id','left');
        $this->db->join('vendors','vendors.id=ams.vendor_id','left');
        $this->db->where($cond);
        $this->db->order_by("ams.id desc");
 
        $i = 0;
     
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
        }
    }
 
    function get_datatables($cond)
    {
        $this->_get_datatables_query($cond);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($cond)
    {
        $this->_get_datatables_query($cond);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($cond)
    {
        $this->db->select('ams.*,mst_asset_types.type,assets.asset_name,asset_details.barcode_number,asset_details.barcode_image,vendors.name');
        $this->db->from('assets_maintenance_schedule ams');
        $this->db->join('mst_asset_types', 'mst_asset_types.id=ams.asset_type_id', 'left'); 
        $this->db->join('assets', 'assets.id=ams.asset_id', 'left'); 
        $this->db->join('asset_details','asset_details.id=ams.asset_detail_id','left');
        $this->db->join('vendors','vendors.id=ams.vendor_id','left');
        $this->db->where($cond);
        $this->db->order_by("ams.id desc");
        return $this->db->count_all_results();
    }

    public function GetAssetTypeVendor($cond)
    {
        $this->db->select('vatm.*,v.id,v.name,v.shop_name');
        $this->db->from('vendor_asset_type_map vatm');
        $this->db->join('vendors v','v.id=vatm.vendor_id', 'left'); 
        $this->db->where($cond); 
        return $this->db->get()->result(); 
    }

    public function GetScheduleData($cond)
    {
        $this->db->select('ams.*,mst_asset_types.type,assets.asset_name,asset_details.barcode_number,asset_details.barcode_image,asset_details.barcode_number,vendors.name');
        $this->db->from('assets_maintenance_schedule ams');
        $this->db->join('mst_asset_types', 'mst_asset_types.id=ams.asset_type_id', 'left'); 
        $this->db->join('assets', 'assets.id=ams.asset_id', 'left'); 
        $this->db->join('asset_details','asset_details.id=ams.asset_detail_id','left');
        $this->db->join('vendors','vendors.id=ams.vendor_id','left');
        $this->db->where($cond);
        return $this->db->get()->row(); 
    }
 
     
}