<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_returns_model extends CI_Model
{

    var $column_order = array(null,'po.order_number','a.asset_name','pr.quantity','pr.status',null,null);
    var $column_search = array('po.order_number','a.asset_name','pr.quantity','pr.status'); 
    var $order = array('pr.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('pr.*,po.order_number,group_concat(DISTINCT a.asset_name) as asset_name');
        $this->db->join('purchase_orders po','pr.purchase_order_id=po.id','left');
        $this->db->join('purchase_return_replace_details prd','prd.purchase_return_replace_id=pr.id','left');
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->from('purchase_return_replace pr');
        $this->db->group_by('pr.id');
		$this->db->where($con);
        
        $i = 0;
    
    if($_POST['search']['value']) // if datatable send POST for search
        {
            $explode_string = explode(' ', $_POST['search']['value']);
            foreach ($explode_string as $show_string) {
            $cond  = " ";
            $cond.=" (po.order_number LIKE '%".$show_string."%' ";
            $cond.=" OR a.asset_name LIKE '%".$show_string."%' ";
            $cond.=" OR pr.quantity LIKE '%".$show_string."%' ";
            $cond.=" OR pr.status LIKE '%".$show_string."%' ) ";
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
        $this->db->select('pr.*,po.order_number,group_concat(a.asset_name) as asset_name');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->join('purchase_return_replace_details prd','prd.purchase_return_replace_id=pr.id','left');
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->from('purchase_return_replace pr');
        $this->db->where($con);
        $this->db->group_by('pr.id');
        return $this->db->count_all_results();
    }

    function count_filtered($con)
    {        
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function GetProductData($con,$is_barcode='')
    {
        $is_barcode='Yes';
        $this->db->select('pod.purchase_order_id,pod.id as podid,pod.asset_id,u.unit,a.asset_name,u.unit,pod.quantity,if(po.branch_id!=0,b.branch_title,"") as branch,po.branch_id,po.order_number,a.asset_type_id');
        $this->db->join('purchase_orders po','po.id=pod.purchase_order_id','left');
        $this->db->join('assets a','a.id=pod.asset_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->join('branches b','b.id=po.branch_id','left');
        $this->db->where($con);
        if($is_barcode=='Yes'){
            $condn='pod.asset_id in(select asset_id from purchase_received_detail_barcode where is_returned="No" and is_replaced="No")';
            $this->db->where($condn);
        }

        return $this->db->get('purchase_order_details pod')->result();
    }

    function GetReturnAmt($con)
    {
        $this->db->select('po.id, prd.asset_id, prd.asset_type_id, sum(prd.rate) rate, sum(prd.received_qty) qty, pr.gst,
(sum(prd.rate)*(pr.gst/100)) actual_gst_amt, (sum(prd.rate) + (sum(prd.rate)*(pr.gst/100))) total_amount,
((sum(prd.rate) + (sum(prd.rate)*(pr.gst/100))) / sum(prd.received_qty)) price_per_qty');
        $this->db->join('purchase_received pr','pr.purchase_order_id=po.id','left');
        $this->db->join('purchase_received_details prd','prd.purchase_received_id=pr.id','left');
        $this->db->where($con);
        $this->db->group_by('prd.asset_id');
        //$this->db->group_by('prd.weight_price_id');
        return $this->db->get('purchase_orders po')->row();
    }

    function GetInvoicenumber($con)
    {
        $this->db->select('id, order_number, (select if(sum(quantity)!="",sum(quantity),0) from purchase_return_replace where purchase_order_id=purchase_orders.id and purchase_return_replace.status!="Rejected") receivedQty, (select count(id) from purchase_received_detail_barcode where purchase_order_id = purchase_orders.id) orderQty');
        $this->db->where($con);
        $this->db->having('receivedQty!=orderQty');
        return $this->db->get('purchase_orders')->result();
    }


    public function GetPrr($cond){
        $this->db->select('pr.*,po.order_number,po.purchase_date,v.name,v.shop_name');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->join('vendors v','v.id=pr.vendor_id','left');
        $this->db->from('purchase_return_replace pr');
        $this->db->where($cond);
         $query = $this->db->get();
         return $query->row();
    } 

    public function GetPrrd($con){
        $this->db->select('prd.*,a.asset_name,');
        $this->db->join('purchase_return_replace pr','pr.id=prd.purchase_return_replace_id','left');   
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->from('purchase_return_replace_details prd');
        $this->db->where($con);
        $query = $this->db->get();
        return $query->result();
    }


}