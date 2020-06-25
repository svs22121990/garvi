<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_Transfer_View_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'barcode_number','price','image',null,null); //set column field database for datatable orderable
    var $column_search = array('barcode_number','price','image'); //set column field database for datatable searchable 
    var $order = array('abmd.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('ad.id,ad.barcode_number,ad.barcode_image,ad.image,ad.asset_id');
        $this->db->join(' asset_details ad','ad.id=abmd.asset_detail_id','left');
        $this->db->from('asset_branch_mappings_details abmd');
        $this->db->Where($con);
     
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
        $this->db->select('ad.barcode_number,ad.barcode_image');
        $this->db->join(' asset_details ad','ad.id=abmd.asset_detail_id','left');
        $this->db->from('asset_branch_mappings_details abmd');
        $this->db->Where($con);
        return $this->db->count_all_results();
    }

        
   
}