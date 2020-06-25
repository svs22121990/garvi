<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Quotations_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*---------------------SERVER SIDE DATA TABLE FETCH STATE RECORD CODE----------------------------------*/
 
    
    var $column_order = array(null,'q.quotation_no','qr.request_no','a.asset_name','v.name','qr.remain_qty','q.total_quantity','qt.status',null); //set column field database for datatable orderable
    var $column_search = array('q.quotation_no','qr.request_no','a.asset_name','v.name','qr.remain_qty','q.total_quantity','qt.status'); //set column field database for datatable searchable
    var $order = array('q.id' => 'desc'); // default order
 
    private function _get_datatables_query()
    {
         
        $this->db->select('q.id,q.quotation_no,q.total_quantity,q.status,qt.vendor_id,qt.asset_id,group_concat(distinct(v.name)) as vendor_name,group_concat(distinct(a.asset_name)) as assets_name,qr.id as qr_id,qr.request_no,qr.remain_qty,qr.totalqty,');
        $this->db->from('quotations as q');
        $this->db->join('quotation_requests as qr',"qr.id=q.quotation_request_id",'left');
        $this->db->join('quotation_details as qt',"qt.quotation_id=q.id",'left');
        $this->db->join('mst_users as v',"qt.vendor_id=v.id",'left');
        $this->db->join('assets as a',"qt.asset_id=a.id",'left');
        $this->db->order_by('q.id desc');
        $this->db->group_by('q.id');
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
          
       $this->db->select('q.id,q.quotation_no,q.total_quantity,q.status,qt.vendor_id,qt.asset_id,group_concat(distinct(v.name)) as vendor_name,group_concat(distinct(a.asset_name)) as assets_name');
        $this->db->from('quotations as q');
        $this->db->join('quotation_details as qt',"qt.quotation_id=q.id",'left');
        $this->db->join('mst_users as v',"qt.vendor_id=v.id",'left');
        $this->db->join('assets as a',"qt.asset_id=a.id",'left');
        $this->db->order_by('q.id desc');
        $this->db->group_by('q.id');
        return $this->db->count_all_results();
    }

    public function GetVendorData($cond)
    {
        $this->db->select('vatm.user_id,v.id as user_id,v.name');
        $this->db->from('vendor_asset_type_map as vatm');
        $this->db->join('mst_users as v','v.id=vatm.user_id','left');
        $this->db->where($cond);
        return $this->db->get()->result();
    }

    public function GetQuotationData($cond)
    {
        $this->db->select('q.id,qr.request_no,q.quotation_no,qt.vendor_id,qt.status,qt.amount,v.name as vendor_name,q.quotation_request_id, qr.remain_qty,qr.totalqty');
        $this->db->from('quotations as q');
        $this->db->join('quotation_requests as qr','qr.id=q.quotation_request_id');
        $this->db->join('quotation_details as qt' ,'qt.quotation_id=q.id');
        $this->db->join('mst_users as v',"qt.vendor_id=v.id",'left');
        $this->db->where($cond);
        $this->db->group_by('qt.vendor_id');
        return $this->db->get()->result();
    }

    public function GetVendorQuotData($cond)
    {
        $this->db->select('qt.id,qt.asset_id,qt.vendor_id,qt.quantity,qt.origin_qty,qt.mrp,qt.amount,qt.per_unit_price,qt.mrp,qt.status,a.asset_name,qt.vendor_quote_copy,qt.vendor_quotation_no,qt.quotation_request_id,at.type,qt.asset_type_id,(qt.origin_qty-qt.quantity) as remaining_qty, qt.quotation_request_detail_id,');
        $this->db->from('quotation_details as qt');
        $this->db->join('assets as a','a.id=qt.asset_id','left');
        $this->db->join('mst_asset_types as at','at.id=qt.asset_type_id','left');
        $this->db->where($cond);
        return $this->db->get()->result();
    }

    public function GetVendorQuotrequestData($cond)
    {
        $this->db->select('qrt.id,qrt.quotation_request_id, qrt.asset_id,qrt.user_id ,qrt.quantity, qrt.remaining_qty, qrt.status,a.asset_name, at.type,qrt.assets_type_id');
        $this->db->from('quotation_request_details as qrt');
        $this->db->join('assets as a','a.id=qrt.asset_id','left');
        $this->db->join('mst_asset_types as at','at.id=qrt.assets_type_id','left');
        $this->db->where($cond);
        return $this->db->get()->result();
    }
 
    public function Getreqno($cond)
    {
        $this->db->select('qr.id,qr.remain_qty,qr.request_no ,sum(qrd.remaining_qty) as remainQty');
        $this->db->from('quotation_requests as qr');
        $this->db->join('quotation_request_details as qrd' , 'qrd.quotation_request_id=qr.id');
        $this->db->where($cond);
        $this->db->group_by('qr.id');
        $this->db->order_by('qr.request_no DESC');
        return $this->db->get()->result();
    }
     
}