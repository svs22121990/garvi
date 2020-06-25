<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule_categories_view_model extends CI_Model
{
    public $table = 'asset_details';
    var $column_order = array(null,'a.asset_name','ad.barcode_number','e.name','ad.barcode_image','sc.created','sc.remarks','sc.status',null); //set column field database for datatable orderable
    var $column_search = array('a.asset_name','ad.barcode_number','e.name','ad.barcode_image','sc.created','sc.remarks','sc.status'); //set column field database for datatable searchable 
    var $order = array('sc.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('a.asset_name,ad.barcode_number,ad.barcode_image,e.name,sc.schedule_category_id,sc.created,sc.remarks,sc.status,sc.id');
        $this->db->from('schedule_category_assets sc');
        $this->db->join('assets a','sc.asset_id=a.id','left');
        $this->db->join('employees e','sc.scan_by=e.id','left');
        $this->db->join('asset_details ad','sc.asset_detail_id=ad.id','left');
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
        $this->db->select('a.asset_name,ad.barcode_number,barcode_image,e.name,sc.remarks,sc.status,sc.created,sc.id');
        $this->db->from('schedule_category_assets sc');
        $this->db->join('assets a','sc.asset_id=a.id','left');
        $this->db->join('employees e','sc.scan_by=e.id','left');
        $this->db->join('asset_details ad','sc.asset_detail_id=ad.id','left');
        $this->db->where($con);
        return $this->db->count_all_results();
    }

  
   
}