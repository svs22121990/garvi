<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_approve_return_model extends CI_Model
{

    var $column_order = array(null,'a.asset_name','prd.barcode_number','prd.remark','prd.status','prd.type');
    var $column_search = array('a.asset_name','prd.barcode_number','prd.remark','prd.status','prd.type'); 
    var $order = array('prd.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('prd.*,a.asset_name,');
        $this->db->join('purchase_return_replace pr','pr.id=prd.purchase_return_replace_id','left');   
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->from('purchase_return_replace_details prd');;
		$this->db->where($con);
        
        $i = 0;
    
    if($_POST['search']['value']) // if datatable send POST for search
        {
            $explode_string = explode(' ', $_POST['search']['value']);
            foreach ($explode_string as $show_string) {
            $cond  = " ";
            $cond.=" (a.asset_name LIKE '%".$show_string."%' ";
            $cond.=" OR prd.barcode_number LIKE '%".$show_string."%' ";
            $cond.=" OR prd.remark LIKE '%".$show_string."%' ";
            $cond.=" OR prd.status LIKE '%".$show_string."%' ) ";
            $this->db->where($cond);
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
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

     public function count_all($con)
    {    
        $this->db->select('prd.*,a.asset_name,');
        $this->db->join('purchase_return_replace pr','pr.id=prd.purchase_return_replace_id','left');   
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->from('purchase_return_replace_details prd');;
        $this->db->where($con);
        return $this->db->count_all_results();
    }

    function count_filtered($con)
    {        
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }

       function count_r_filtered($con)
    {
        $this->db->simple_query('SET SESSION group_concat_max_len=1000000000');
        $this->_get_datatables_query($con);
        $this->db->select('count(*) as total_count,group_concat(prd.id) as ids');   
        $query = $this->db->get();
        return $query->row();
    }
   
}