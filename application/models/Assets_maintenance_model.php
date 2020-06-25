<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Assets_maintenance_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'assets.asset_name','asset_details.barcode_number','asset_details.image','assets_maintenance.description','assets_maintenance.price','assets_maintenance.date','assets_maintenance.status',null); //set column field database for datatable orderable
    var $column_search = array('assets.asset_name','asset_details.barcode_number','asset_details.image','assets_maintenance.description','assets_maintenance.price','assets_maintenance.date','assets_maintenance.status'); //set column field database for datatable searchable
    var $order = array('assets_maintenance.id' => 'desc'); // default order
 
    private function _get_datatables_query($con)
    {
         
        $this->db->select('assets_maintenance.*,assets.asset_name,asset_details.barcode_number,asset_details.image');
        $this->db->from('assets_maintenance');
        $this->db->join('assets', 'assets.id=assets_maintenance.assets_id', 'left'); 
        $this->db->join('asset_details', 'asset_details.id=assets_maintenance.asset_details_id', 'left'); 
        $this->db->where($con); 
        //$this->db->join('branches', 'branches.id=assets_maintenance.branch_id', 'left'); 
        $this->db->order_by("assets_maintenance.id desc");
 
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
 
    function get_datatables($con)
    {
        $this->_get_datatables_query($con);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->select('assets_maintenance.*,assets.asset_name,asset_details.barcode_number,asset_details.image');
        $this->db->from('assets_maintenance');
        $this->db->join('assets', 'assets.id=assets_maintenance.assets_id', 'left'); 
        $this->db->join('asset_details', 'asset_details.id=assets_maintenance.asset_details_id', 'left'); 
        $this->db->where($con); 
        //$this->db->join('branches', 'branches.id=assets_maintenance.branch_id', 'left'); 
        $this->db->order_by("assets_maintenance.id desc");
        return $this->db->count_all_results();
    }

    public function joinDataAsset($asset_type_id="",$branch_id="")
    {
        $this->db->select('assets.id,assets.asset_name');
        $this->db->from('assets');
        $this->db->join('asset_branch_mappings abm','abm.asset_id=assets.id', 'left'); 
        $this->db->join('branches br','br.id=abm.branch_id','left'); 
        $this->db->join('mst_asset_types mat','mat.id=assets.asset_type_id', 'left'); 
        $this->db->where("abm.branch_id",$branch_id); 
        $this->db->where("mat.id",$asset_type_id); 
        return $this->db->get()->result(); 
    }
 
     
}