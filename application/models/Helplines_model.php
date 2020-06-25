<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Helplines_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'helpline_types.helpline_type','helplines.contact_person','helplines.contact_number','helplines.email','helplines.address','helplines.status',null); //set column field database for datatable orderable
    var $column_search = array('helpline_types.helpline_type','helplines.contact_person','helplines.contact_number','helplines.email','helplines.address','helplines.status'); //set column field database for datatable searchable
    var $order = array('helplines.id' => 'desc'); // default order
 
    private function _get_datatables_query()
    {
         
        $this->db->select('helplines.*,helpline_types.helpline_type');
        $this->db->from('helplines');
        $this->db->join('helpline_types', 'helpline_types.id=helplines.helpline_type_id', 'left'); 
        $this->db->order_by("helplines.id desc");
 
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
        $this->db->select('helplines.*,helpline_types.helpline_type');
        $this->db->from('helplines');
        $this->db->join('helpline_types', 'helpline_types.id=helplines.helpline_type_id', 'left'); 
        $this->db->order_by("helplines.id desc");
 
        return $this->db->count_all_results();
    }
 
     
}