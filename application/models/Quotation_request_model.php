<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Quotation_request_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'r.request_no','a.asset_name','v.name','r.totalqty','qr.status',null); //set column field database for datatable orderable
    var $column_search = array('r.request_no','a.asset_name','v.name','qr.status'); //set column field database for datatable searchable
    var $order = array('r.id' => 'desc'); // default order
 
    private function _get_datatables_query()
    {
         
        $this->db->select('r.id,qr.id as quotation_request_id,r.request_no,r.totalqty,qr.status,qr.user_id,qr.asset_id,group_concat(distinct(v.name)) as vendor_name,group_concat(distinct(a.asset_name)) as assets_name');
        $this->db->from('quotation_requests as r');
        $this->db->join('quotation_request_details as qr',"qr.quotation_request_id=r.id",'left');
        $this->db->join('mst_users as v',"qr.user_id=v.id",'left');
        $this->db->join('assets as a',"qr.asset_id=a.id",'left');
        $this->db->order_by('r.id desc');
        $this->db->group_by('r.id');
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
          
        $this->db->select('r.id, qr.id as quotation_request_id,r.request_no,r.totalqty,qr.status,qr.user_id,qr.asset_id,group_concat(distinct(v.name)) as vendor_name,group_concat(distinct(a.asset_name)) as assets_name');
        $this->db->from('quotation_requests as r');
        $this->db->join('quotation_request_details as qr',"qr.quotation_request_id=r.id",'left');
        $this->db->join('mst_users as v',"qr.user_id=v.id",'left');
        $this->db->join('assets as a',"qr.asset_id=a.id",'left');
        $this->db->order_by('r.id desc');
        $this->db->group_by('r.id');
        return $this->db->count_all_results();
    }

    public function getAssetData($vandorIds,$count)
    {
        $query = "select count(at.id) as totalType,at.* from mst_asset_types at inner join vendor_asset_type_map vam on at.id=vam.asset_type_id where vam.vendor_id in (".$vandorIds.") group by at.id "; //having totalType='".$count."'
        $result = $this->db->query($query);
        return $result->result();

    }
}