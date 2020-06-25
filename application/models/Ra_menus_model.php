<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ra_menus_model extends CI_Model
{
   public $table = 'ra_menus rm';
    var $column_order = array(null,'rm.menu_name','rm.value','m.module_name',null,null); //set column field database for datatable orderable
    var $column_search = array('rm.menu_name','rm.value','m.module_name'); //set column field database for datatable searchable 
    var $order = array('rm.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
       
        $this->db->select('rm.*,m.module_name');
        $this->db->from('ra_menus rm');
        $this->db->join('ra_modules m', 'm.id=rm.ra_module_id', 'left');
        $this->db->where($con);
        //$this->db->where($con);
     
        $i = 0;
        if(isset($_POST['search'])){
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
        if(isset($_POST['length']) && $_POST['length'] != -1)
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
           $this->db->select('rm.*,m.module_name');
        $this->db->from('ra_menus rm');
        $this->db->join('ra_modules m', 'm.id=rm.ra_module_id', 'left');
        $this->db->where($con);
       // $this->db->where($con);
        return $this->db->count_all_results();
    }

       
   
}