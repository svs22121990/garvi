<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Branches_model extends CI_Model
{
    public $table = 'branches';
    var $column_order = array(null,'branches.branch_title','branches.contact_no','branches.status','branches.is_delete',null); //set column field database for datatable orderable
    var $column_search = array('branches.branch_title','branches.contact_no','branches.status','branches.is_delete'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query()
    {
        $this->db->select("branches.*,employees.name,employees.email,employees.mobile,employees.emp_code");
        $this->db->from("branches");
        $this->db->join("employees","employees.id = branches.emp_id","left");
        $this->db->Where("is_delete","No");
       /* $this->db->Where("branches.status","Active");*/
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
            $this->db->order_by(key($order),$order[key($order)]);
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
        $this->db->from($this->table);
        $this->db->Where("is_delete","No");
        /* $this->db->Where("branches.status","Active");*/
        return $this->db->count_all_results();
    }

    public function getAllDetails($id)
    {
        $this->db->select('branches.*,mst_countries.country_name,mst_states.state_name,mst_cities.city_name,employees.name,employees.email,employees.mobile,employees.emp_code');       
        $this->db->join("mst_countries","mst_countries.id = branches.country_id","left");
        $this->db->join("mst_states","mst_states.id = branches.state_id","left");
        $this->db->join("mst_cities","mst_cities.id = branches.city_id","left"); 
         $this->db->join("employees","employees.id = branches.emp_id","left");             
        $this->db->where("branches.id",$id);     
        return $this->db->get('branches')->row();        
    }

    public function getAssetsDetails($BranchId)
    {
        $this->db->select('abm.*,assets.asset_name');       
        $this->db->join("assets","assets.id = abm.asset_id","left");                        
        $this->db->where("abm.branch_id",$BranchId);     
        return $this->db->get('asset_branch_mappings abm')->result();
    }


   
}