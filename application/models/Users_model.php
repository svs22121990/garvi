<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'branches.branch_title','employees.name','mst_designations.designation_name','employees.email','employees.mobile','employees.image','employees.status',null); //set column field database for datatable orderable
    var $column_search = array('branches.branch_title','employees.name','mst_designations.designation_name','employees.email','employees.mobile','employees.status'); //set column field database for datatable searchable
    var $order = array('employees.id' => 'desc'); // default order
 
    private function _get_datatables_query($con)
    {
         
        $this->db->select('employees.*,branches.branch_title,mst_designations.designation_name');
        $this->db->from('employees');
        $this->db->join('branches', 'branches.id=employees.branch_id', 'left'); 
        $this->db->join('mst_designations', 'mst_designations.id=employees.designation_id', 'left'); 
        $this->db->order_by("employees.id desc");
        $this->db->Where($con);
 
        /*$i = 0;
     
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
        }*/
    }
 
    function get_datatables($con)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
        //$this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->select('employees.*,branches.branch_title,mst_designations.designation_name');
        $this->db->from('employees');
        $this->db->join('branches', 'branches.id=employees.branch_id', 'left'); 
        $this->db->join('mst_designations', 'mst_designations.id=employees.designation_id', 'left'); 
        $this->db->order_by("employees.id desc");
        $this->db->Where($con);
        return $this->db->count_all_results();
    }

    function ExportCSV($con)
    {   
        $this->db->select('employees.name,employees.email,employees.mobile,mst_designations.designation_name,branches.branch_title');
        $this->db->join('mst_designations', 'mst_designations.id = employees.designation_id','left');
        $this->db->join('branches','branches.id=employees.branch_id','left');
        $this->db->where($con);
        return $this->db->get('employees')->result();
    }
 
     
}