<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assets_scrap_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'at.type','a.asset_name','ad.barcode_number','ass.sale','ass.price','ass.status',null); //set column field database for datatable orderable
    var $column_search = array('at.type','a.asset_name','ad.barcode_number','ass.sale','ass.price','ass.status'); //set column field database for datatable searchable 
    var $order = array('ass.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($cond)
    {
        $this->db->select('at.type,a.asset_name,ad.barcode_number,ass.sale,ass.id,ass.price,ass.status');
        $this->db->from('assets_scrap ass');
        $this->db->join('assets a','a.id=ass.asset_id','left');
        $this->db->join('asset_details ad','ad.id=ass.asset_detail_id','left');
        $this->db->join('mst_asset_types at','at.id=ass.asset_type_id','left');
        $this->db->where($cond);
     
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
        $this->db->select('at.type,a.asset_name,ad.barcode_number,ass.sale');
        $this->db->from('assets_scrap ass');
        $this->db->join('assets a','a.id=ass.asset_id','left');
        $this->db->join('asset_details ad','ad.id=ass.asset_detail_id','left');
       $this->db->where($cond);
        return $this->db->count_all_results();
    }

       
   
}