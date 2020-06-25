<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_replace_model extends CI_Model
{

    var $column_order = array(null,'po.order_number','a.asset_name','pr.quantity','u.unit','pr.remark',null,null);
    var $column_search = array('po.order_number','a.asset_name','pr.quantity','pr.quantity','u.unit','pr.remark');
    var $order = array('pr.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('pr.*,po.id as pod,po.order_number,a.asset_name,u.unit');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->join('purchase_order_details pod','pod.id=pr.purchase_order_detail_id','left');
        $this->db->join('assets a','a.id=pr.asset_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        //$this->db->join('weight_price wp','wp.id=pod.weight_price_id','left');
        $this->db->from('purchase_replace pr');
        $this->db->group_by('pr.id');
		$this->db->where($con);
        
        $i = 0;
        /*
        if($_POST['search']['value']) // if datatable send POST for search
            {
                $explode_string = explode(' ', $_POST['search']['value']);
                foreach ($explode_string as $show_string) {
                $cond  = " ";
                $cond.=" (po.order_number LIKE '%".$show_string."%' ";
                $cond.=" OR p.prd_name LIKE '%".$show_string."%' ";
                $cond.=" OR wp.weight LIKE '%".$show_string."%' ";
                $cond.=" OR pr.quantity LIKE '%".$show_string."%' ";
                $cond.=" OR p.product_unit LIKE '%".$show_string."%' ";
                $cond.=" OR pr.remark LIKE '%".$show_string."%' ) ";
                $this->db->where($cond);
                }
            }
        */
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {                
                if($i===0) // first loop
                {
                    $this->db->group_start();
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

     public function count_all($con)
    {    
       $this->db->select('pr.*,po.id as pod,po.order_number,a.asset_name,u.unit');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->join('purchase_order_details pod','pod.id=pr.purchase_order_detail_id','left');
        $this->db->join('assets a','a.id=pr.asset_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        //$this->db->join('weight_price wp','wp.id=pod.weight_price_id','left');
        $this->db->from('purchase_replace pr');
        $this->db->group_by('pr.id');
        $this->db->where($con);
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
        /*$this->db->select('po.id as poid,pod.id as podid,pod.product_id,pod.weight_price_id,b.brand_name,p.prd_name,p.product_unit,wp.weight,pod.product_quantity');
        $this->db->join('purchase_orders po','po.id=pod.purchase_order_id','left');
        $this->db->join('products p','p.prd_id=pod.product_id','left');
        $this->db->join('brands b','b.id=p.brand_id','left');
        $this->db->join('weight_price wp','wp.id=pod.weight_price_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_order_details pod')->result();*/

        $this->db->select('pod.purchase_order_id,pod.id as podid,pod.asset_id,u.unit,a.asset_name,u.unit,pod.quantity,if(po.branch_id!=0,b.branch_title,"") as branch,po.branch_id,po.order_number,a.asset_type_id');
        $this->db->join('purchase_orders po','po.id=pod.purchase_order_id','left');
        $this->db->join('assets a','a.id=pod.asset_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->join('branches b','b.id=po.branch_id','left');
        $this->db->where($con);
        if($is_barcode=='Yes'){
            $condn='pod.asset_id in(select asset_id from purchase_received_detail_barcode where is_received="Yes" and is_returned="No" )';
            $this->db->where($condn);
        }

        return $this->db->get('purchase_order_details pod')->result();
    }

    function GetReturnAmt($con)
    {
        $this->db->select('po.id, prd.product_id, prd.weight_price_id, sum(prd.rate) rate, sum(prd.received_qty) qty, pr.gst,
        (sum(prd.rate)*(pr.gst/100)) actual_gst_amt, (sum(prd.rate) + (sum(prd.rate)*(pr.gst/100))) total_amount,
        ((sum(prd.rate) + (sum(prd.rate)*(pr.gst/100))) / sum(prd.received_qty)) price_per_qty');
        $this->db->join('purchase_received pr','pr.purchase_order_id=po.id','left');
        $this->db->join('purchase_received_details prd','prd.purchase_received_id=pr.id','left');
        $this->db->where($con);
        $this->db->group_by('prd.weight_price_id');
        return $this->db->get('purchase_orders po')->row();
    }

    public function getPurchaseOrderData($replaceId)
    {
        $this->db->select('pr.*,whs.name,v.shop_name,wdc.distrubution_name,po.order_number,po.purchase_date,po.status,wp.product_name,wp.weight,b.brand_name,p.prd_name,p.product_type,p.product_unit');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->join('warehouses whs','whs.id = po.warehouse_id','left');
        $this->db->join('vendors v','v.id = po.vendor_id','left');
        $this->db->join('warehouse_distribution_center wdc','wdc.id = po.distributor_id','left');
        $this->db->join('purchase_order_details pod','pod.id = pr.purchase_order_detail_id','left');
        $this->db->join('products p','p.prd_id = pod.product_id','left');
        $this->db->join('weight_price wp','wp.id = pod.weight_price_id','left');
        $this->db->join('brands b','b.id = p.brand_id','left');
        $this->db->where('pr.id',$replaceId);
        return $this->db->get('purchase_replace pr')->row();
    }

    function GetRemainQty($con)
    {
        $this->db->select("sum(rpl.remaining_quantity), if(p.product_type='Open' and (p.product_unit='kg' || p.product_unit='ltr' ),sum(rpl.remaining_quantity)/1000,sum(rpl.remaining_quantity)) replaceQuantity");
        $this->db->join('purchase_order_details pod','pod.id = rpl.purchase_order_detail_id','left');
        $this->db->join('products p','p.prd_id = pod.product_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_replace rpl')->row();
    }


    /*function GetPartyInvoice($con)
    {
        $this->db->select('party_invoice.id,invoice_no,final_amount ,
        (select sum(amount) from party_transactions where party_invoice_id=party_invoice.id) paid,
        (final_amount - (select sum(amount) from party_transactions where party_invoice_id=party_invoice.id)) bal');
        $this->db->join('party_transactions pt','pt.party_invoice_id=party_invoice.id','left');
        $this->db->where($con);
        $this->db->group_by('party_invoice.id');
        $this->db->having('bal!=','0');
        return $this->db->get('party_invoice')->result();
    }

    public function GetWarehouse($con)
    {
        $this->db->select('w.name, wdc.distrubution_name,p.invoice_no,p.invoice_date');
        $this->db->join('warehouses w','w.id=p.warehouse_id','left');
        $this->db->join('warehouse_distribution_center wdc','wdc.id=p.distributor_id','left');
        $this->db->where($con);
        return $this->db->get('party_invoice p')->row();
    }

    public function GetPayBtn($con)
    {
        $this->db->select('pi.id, pi.final_amount total,sum(pt.amount) paid, (pi.final_amount - sum(pt.amount)) bal');
        $this->db->join('party_transactions pt','pt.party_invoice_id=pi.id','left');
        $this->db->where($con);
        $this->db->group_by('pi.id');
        return $this->db->get('party_invoice pi')->row();
    }*/
}