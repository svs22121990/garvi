<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payments_model extends CI_Model
{
    var $column_order = array(null,'v.shop_name','po.order_number','vt.description','vt.payment_date','vt.inward','vt.balance','vt.status',null); 
    var $order = array('vt.id' => 'DESC');

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('v.shop_name,po.order_number,vt.description,vt.payment_date,vt.inward,vt.balance,vt.status');
        $this->db->join('vendors v','v.id=vt.vendor_id','left');
        $this->db->join('purchase_orders po','po.id=vt.purchase_order_id','left');
        $this->db->from('vendor_transactions vt');
        $this->db->where($con);
        
        $i = 0;    
        if($_POST['search']['value']) // if datatable send POST for search
        {
            $explode_string = explode(' ', $_POST['search']['value']);
            foreach ($explode_string as $show_string) 
            {  
                $cond  = " ";
                $cond.=" ( v.shop_name  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR po.order_number  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR vt.description  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR vt.payment_date  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR vt.inward  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR vt.balance  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR vt.status LIKE '%".trim($show_string)."%') ";
                $this->db->where($cond);
            }
        }
        $i++;
        
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
        $this->db->select('v.shop_name,po.order_number,vt.description,vt.payment_date,vt.amount,vt.balance,vt.status');
        $this->db->join('vendors v','v.id=vt.vendor_id','left');
        $this->db->join('purchase_orders po','po.id=vt.purchase_order_id','left');
        $this->db->from('vendor_transactions vt');
        $this->db->where($con);
        return $this->db->count_all_results();
    }

    function count_filtered($con)
    {        
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function GetVendorDetail($con)
    {
        $this->db->select('vt.balance,v.*');
        $this->db->join('vendors v','v.id=vt.vendor_id','left');
        $this->db->where($con);
        $this->db->order_by('vt.id desc');
        $this->db->limit('1');
        return $this->db->get('vendor_transactions vt')->row();
    }

	function ExportCSV($con)
	{ 	
        $this->db->select('v.shop_name,po.order_number,vt.description,vt.payment_date,vt.inward,vt.balance,vt.status');
        $this->db->join('vendors v', 'v.id = vt.vendor_id','left');
        $this->db->join('purchase_orders po','po.id=vt.purchase_order_id','left');
        $this->db->where($con);
        return $this->db->get('vendor_transactions vt')->result();
	}
}