<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_details_model extends CI_Model
{
    public $table = 'assets';
    var $column_order = array(null,'price','type',null,null); //set column field database for datatable orderable
    var $column_search = array('price','type'); //set column field database for datatable searchable 
    var $order = array('assets.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->from($this->table);
        $this->db->join("mst_asset_types mat","mat.id=assets.asset_type_id","inner");
        $this->db->Where($con);
        /*$this->db->Where("status","Active");*/
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
        $this->db->from($this->table);
        $this->db->Where($con);
        return $this->db->count_all_results();
    }

        function count_client_filtered($con)
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000000');
        $this->_get_datatables_query($con);
        $this->db->select('count(*) as total_count,group_concat(id) as ids');   
        //$this->db->where($con);
        $query = $this->db->get();
        return $query->row();
    }
   
}