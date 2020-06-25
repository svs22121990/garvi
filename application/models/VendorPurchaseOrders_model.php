<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class VendorPurchaseOrders_model extends CI_Model
{
    var $column_order = array(null,'at.type','purchase_orders.order_number',null,'purchase_orders.purchase_date',null,'purchase_orders.status',null);
    var $order = array('purchase_orders.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('purchase_orders.* ,at.type , (select count(*) from purchase_order_details where purchase_order_id=purchase_orders.id) as count,(select sum(total_amount) from purchase_received where purchase_order_id=purchase_orders.id) sum,(select sum(extra_vendor_charge+labour_charge) from vehicle_management where purchase_order_id=purchase_orders.id ) transport');
        $this->db->join('mst_asset_types at','at.id=purchase_orders.asset_type_id','left');
        //$this->db->join('warehouse_distribution_center','warehouse_distribution_center.id=purchase_orders.distributor_id','left');
        $this->db->where($con);
        $this->db->from('purchase_orders');
        
        $i = 0;    
        if($_POST['search']['value']) // if datatable send POST for search
        {
            $explode_string = explode(' ', $_POST['search']['value']);
            foreach ($explode_string as $show_string) 
            {  
                $cond  = " ";
                $cond.=" ( at.type  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR purchase_orders.order_number  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR purchase_orders.purchase_date  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR purchase_orders.status LIKE '%".trim($show_string)."%') ";
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
        $this->db->select('purchase_orders.* ,at.type , (select count(*) from purchase_order_details where purchase_order_id=purchase_orders.id) as count');
       $this->db->join('mst_asset_types at','at.id=purchase_orders.asset_type_id','left');        
        $this->db->where($con);
        $this->db->from('purchase_orders');
        return $this->db->count_all_results();
    }

    function count_filtered($con)
    {        
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function GetPOData($con)
    {
        $this->db->select('po.*,at.type,v.shop_name');
        $this->db->join('mst_asset_types at','at.id=po.asset_type_id','left');    
        $this->db->join('vendors v','v.id=po.vendor_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_orders po')->row();
    }

    function GetBillTransport($con)
    {
        $this->db->select('pr.*,vm.driver_name,vm.purchase_received_id,vm.vehicle_number,vm.labour_charge,vm.extra_vendor_charge');
        $this->db->join('vehicle_management vm','vm.purchase_received_id=pr.id','left');
        $this->db->where($con);
        return $this->db->get('purchase_received pr')->result();
    }

    function GetPrdLotDetails($con)
    {
      /*  $this->db->select('if(pod.weight_price_id!=0, wp.product_name, p.prd_name) as product_name, if(pod.weight_price_id!=0, wp.weight, "") as weight,b.brand_name');
        $this->db->join('purchase_order_details pod','pod.product_id=p.prd_id','left');
        $this->db->join('weight_price wp','wp.product_id=p.prd_id','left');
        $this->db->join('brands b','b.id=p.brand_id','left');
        $this->db->where($con);
        return $this->db->get('products p')->row();*/
        //if(pod.weight_price_id!=0, wp.product_name, p.prd_name) as product_name, if(pod.weight_price_id!=0, wp.weight, "") as weight,
        $this->db->select('a.asset_name,a.quantity,u.unit');
        $this->db->join('purchase_order_details pod','pod.asset_id=a.id','left');
        //$this->db->join('weight_price wp','wp.product_id=p.prd_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->where($con);
        return $this->db->get('assets a')->row();
    }

	function ExportCSV($con)
	{ 
		/*$this->db->select('po.purchase_date,po.total_items,po.order_number,po.status ,v.name,v.shop_name,w.name as warehouse,wdc.distrubution_name');
        $this->db->join('vendors v', 'v.id = po.vendor_id','left');
        $this->db->join('warehouses w', 'w.id = po.warehouse_id','left');
        $this->db->join('warehouse_distribution_center wdc', 'wdc.id = po.distributor_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_orders po')->result();      */

        $this->db->select('po.* ,at.type , (select count(*) from purchase_order_details where purchase_order_id=po.id) as count,(select sum(total_amount) from purchase_received where purchase_order_id=po.id) sum,(select sum(extra_vendor_charge+labour_charge) from vehicle_management where purchase_order_id=po.id ) transport,v.shop_name');
        $this->db->join('vendors v', 'v.id = po.vendor_id','left');
         $this->db->join('mst_asset_types at','at.id=po.asset_type_id','left');  
        $this->db->where($con);
        return $this->db->get('purchase_orders po')->result();   
	}
}