<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assets_issue_reqests_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'sc.status','b.branch_title','e.name','sc.remarks','a.barcode_number','r.type','as.asset_name',null); //set column field database for datatable orderable
    var $column_search = array('sc.status','b.branch_title','e.name','sc.remarks','a.barcode_number','r.type','as.asset_name');//set column field database for datatable searchable 
    var $order = array('sc.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query()
    {
        $this->db->select('sc.status,sc.remarks,sc.resolve_remark,b.branch_title,e.name,sc.id,a.barcode_number,r.type,sc.remarks,as.asset_name');
        $this->db->from('assets_issue_reqests sc');
        $this->db->join('branches b','sc.branch_id=b.id','left');
        $this->db->join('employees e','sc.employee_id=e.id','left');
        $this->db->join('asset_details a','sc.asset_detail_id=a.id','left');
        $this->db->join('remark_types r','sc.remark_type_id=r.id','left');
        $this->db->join('assets as','a.asset_id=as.id','left');
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
        $this->db->select('sc.status,sc.remarks,sc.resolve_remark,b.branch_title,e.name,sc.id,a.barcode_number,r.type');
        $this->db->from('assets_issue_reqests sc');
        $this->db->join('branches b','sc.branch_id=b.id','left');
        $this->db->join('employees e','sc.employee_id=e.id','left');
        $this->db->join('asset_details a','sc.asset_detail_id=a.id','left');
        $this->db->join('remark_types r','sc.remark_type_id=r.id','left');
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