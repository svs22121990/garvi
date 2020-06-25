<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assets_audit_remarks_model extends CI_Model
{
    public $table = 'assets_audit_remarks aar';
    var $column_order = array(null,'e.name','a.asset_name','ad.barcode_number','aar.remarks','b.branch_title','rt.type','aar.created',null); //set column field database for datatable orderable
    var $column_search = array('e.name','a.asset_name','ad.barcode_number','aar.remarks','b.branch_title','rt.type','aar.created');  //set column field database for datatable searchable 
    var $order = array('aar.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query()
    {
        $this->db->select('e.name,a.asset_name,ad.barcode_number,aar.remarks,b.branch_title,rt.type,aar.id,aar.created');
        $this->db->from('assets_audit_remarks aar');
        $this->db->join('employees e','e.id=aar.employee_id','left');
        $this->db->join('asset_details ad','ad.id=aar.asset_detail_id','left');
        $this->db->join('assets a','a.id=aar.asset_id','left');
        $this->db->join('branches b','b.id=aar.branch_id','left');
        $this->db->join('remark_types rt','rt.id=aar.remark_type_id','left');
       
     
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
        $this->db->select('e.name,a.asset_name,ad.barcode_number,aar.remarks,b.branch_title,rt.type,aar.id,aar.created');
        $this->db->from('assets_audit_remarks aar');
        $this->db->join('employees e','e.id=aar.employee_id','left');
        $this->db->join('asset_details ad','ad.id=aar.asset_detail_id','left');
        $this->db->join('assets a','a.id=aar.asset_id','left');
        $this->db->join('branches b','b.id=aar.branch_id','left');
        $this->db->join('remark_types rt','rt.id=aar.remark_type_id','left');
        return $this->db->count_all_results();
    }

       
   
}