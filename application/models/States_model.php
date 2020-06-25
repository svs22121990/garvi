<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class States_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'st.state_name','ct.country_name','st.status',null); //set column field database for datatable orderable
    var $column_search = array('st.state_name','ct.country_name','st.status'); //set column field database for datatable searchable
    var $order = array('st.id' => 'desc'); // default order
 
    private function _get_datatables_query()
    {
         
        $this->db->select('st.id,st.status,ct.country_name as country_name,st.state_name as state_name');
        $this->db->from('mst_states st');
        $this->db->join('mst_countries ct', 'ct.id=st.country_id', 'inner'); 
        $this->db->order_by("st.id desc");
 
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('st.*,ct.country_name as country_name,st.state_name as state_name');
        $this->db->from('mst_states st');
        $this->db->join('mst_countries ct', 'ct.id=st.country_id', 'inner'); 
        $this->db->order_by("st.id desc");
        return $this->db->count_all_results();
    }
 
     
}