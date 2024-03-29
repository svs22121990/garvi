<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assets_request_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'ar.request_no','b.branch_title','e.name','ar.total_quantity','ar.total_quantity','ar.approved_quantity','ar.status',null); //set column field database for datatable orderable
    var $column_search = array('b.branch_title','e.name','ar.total_quantity','ar.total_quantity','ar.approved_quantity','ar.request_no','ar.status'); //set column field database for datatable searchable 
    var $order = array('ar.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query()
    {
        $this->db->select('ar.id,ar.status,ar.total_assets,b.branch_title,e.name,ar.branch_id,ar.total_quantity,ar.approved_quantity,ar.request_no');
        $this->db->from('assets_requests ar');
        $this->db->join('branches b','ar.branch_id=b.id','left');
        $this->db->join('employees e','ar.created_by=e.id','left');
        //$this->db->where($con);
     
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
            $this->db->select('ar.id,ar.total_assets,b.branch_title,e.name,ar.branch_id');
        $this->db->from('assets_requests ar');
        $this->db->join('branches b','ar.branch_id=b.id','left');
        $this->db->join('employees e','ar.created_by=e.id','left');
       // $this->db->where($con);
        return $this->db->count_all_results();
    }

       
   
}