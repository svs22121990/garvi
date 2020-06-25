<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule_categories_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'sc.status','b.branch_title','e.name','c.title',null); //set column field database for datatable orderable
    var $column_search = array('sc.status','b.branch_title','e.name','c.title'); //set column field database for datatable searchable 
    var $order = array('sc.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('sc.status,b.branch_title,e.name,c.title,sc.id');
        $this->db->from('schedule_categories sc');
        $this->db->join('branches b','sc.branch_id=b.id','left');
        $this->db->join('employees e','sc.employee_id=e.id','left');
        $this->db->join('categories c','sc.category_id=c.id','left');
        $this->db->where($con);
     
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
        $this->db->select('sc.status,b.branch_title,e.name,c.title,sc.id');
        $this->db->from('schedule_categories sc');
        $this->db->join('branches b','sc.branch_id=b.id','left');
        $this->db->join('employees e','sc.employee_id=e.id','left');
        $this->db->join('categories c','sc.category_id=c.id','left');
        $this->db->where($con);
        return $this->db->count_all_results();
    }

    public function get_category($con)
    {
            $this->db->select('c.title,c.id');
          $this->db->from('asset_branch_mappings_details ab');
          $this->db->join('assets a','a.id=ab.asset_id','left');
          $this->db->join('categories c','c.id=a.category_id','left');
          $this->db->where($con);
          $this->db->group_by('c.title');
         $query = $this->db->get();
          return $query->result(); 
    }  
   
}