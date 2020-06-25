<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Login_logs_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'branches.branch_title','employees.name','login_logs.login_time','login_logs.login_ip','login_logs.status',null); //set column field database for datatable orderable
    var $column_search = array('branches.branch_title','employees.name','login_logs.login_time','login_logs.login_ip','login_logs.status'); //set column field database for datatable searchable
    var $order = array('login_logs.id' => 'desc'); // default order
 
    private function _get_datatables_query()
    {
         
        $this->db->select('login_logs.*,employees.name,branches.branch_title');
        $this->db->from('login_logs');
        $this->db->join('employees', 'employees.id=login_logs.employee_id', 'left'); 
        $this->db->join('branches', 'branches.id=login_logs.branch_id', 'left'); 
        $this->db->order_by("login_logs.id desc");
        /*$this->db->select("*");
        $this->db->from("login_logs");*/
 
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
        $this->db->select('login_logs.*,employees.name,branches.branch_title');
        $this->db->from('login_logs');
        $this->db->join('employees', 'employees.id=login_logs.employee_id', 'left'); 
        $this->db->join('branches', 'branches.id=login_logs.branch_id', 'left'); 
        $this->db->order_by("login_logs.id desc");
        return $this->db->count_all_results();
    }
 
     
}