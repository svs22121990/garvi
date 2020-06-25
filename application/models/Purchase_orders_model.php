<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_orders_model extends CI_Model
{

    var $column_order = array(null,'at.type','v.name','P.order_number','P.purchase_date','P.total_items','P.status',null);
    var $order = array('P.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con='')
    {
        /*(select sum(total_amount) from purchase_received where purchase_order_id=P.id) sum,(select sum(extra_vendor_charge+labour_charge) from vehicle_management where purchase_order_id=P.id ) transport*/
        $this->db->select('P.*,at.type,if(p.quotation_id!=0,qt.quotation_no,"-") as quotation_no,v.shop_name as vendorname,(select sum(total_amount) from purchase_received where purchase_order_id=P.id) sum,(select sum(extra_vendor_charge+labour_charge) from vehicle_management where purchase_order_id=P.id ) transport');
        $this->db->join('mst_asset_types at','at.id=P.asset_type_id','left');      
        $this->db->join('quotations qt','qt.id=P.quotation_id','left');      
		$this->db->join('vendors v','v.id=P.vendor_id','left');		 
        $this->db->from('purchase_orders P');
        $this->db->group_by('P.id');
		if($con!='')
		$this->db->where($con);
        
        $i = 0;    
        if($_POST['search']['value']) // if datatable send POST for search
            {
                $explode_string = explode(' ', $_POST['search']['value']);
                foreach ($explode_string as $show_string) 
                {  
                    $cond  = " ";
                    $cond.=" ( v.shop_name  LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR at.type LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR qt.quotation_no LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR P.order_number  LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR P.purchase_date  LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR P.total_items  LIKE '%".trim($show_string)."%' ";
                    $cond.=" OR P.status LIKE '%".trim($show_string)."%') ";
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

    function get_datatables($con='')
    {       
        $this->_get_datatables_query($con);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
		if($con!='')
			$this->db->where($con);
        $query = $this->db->get();
        return $query->result();
    }

     public function count_all()
    {    
        $this->db->select('P.*');
        $this->db->from('purchase_orders P');
        return $this->db->count_all_results();
    }


    function count_filtered()
    {        
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function GetBarcodeDetails($con)
    {
        $this->db->select('a.*,pc.title,s.sub_cat_title');
        $this->db->from('assets a');
        $this->db->join('categories pc','a.category_id = pc.id', 'left');
        $this->db->join('sub_categories s','a.subcategory_id = s.id', 'left');
       // $this->db->join('brands b','p.brand_id = b.id', 'left');
        $this->db->where($con);
        return $this->db->get()->row();
    }

    function GetPurchaseorders($con)
    {
        $this->db->select('p.*,at.type,v.shop_name,if(p.branch_id!=0,b.branch_title,"") as branch,if(p.quotation_id!=0,q.quotation_no,"") as quotation_no,at.is_barcode');
        $this->db->join('mst_asset_types at','at.id=p.asset_type_id','left');
        $this->db->join('vendors v','v.id=p.vendor_id','left');
        $this->db->join('branches b','b.id=p.branch_id','left');
        $this->db->join('quotations q','q.id=p.quotation_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_orders p')->row();
    }

    function GetPrdReceivedAmt($con)
    {
        $this->db->select('sum(rate) as amount,per_unit_price,price,cgst,sgst,fess');
        $this->db->where($con);
        return $this->db->get('purchase_received_details')->row();
    }

    function GetPOData($con)
    {
        $this->db->select('po.*,at.type,v.shop_name,if(po.branch_id!=0,b.branch_title,"") as branch,at.is_barcode,if(po.quotation_id!=0,q.quotation_no,"") as quotation_no,');
        $this->db->join('mst_asset_types at','at.id=po.asset_type_id','left');
        //$this->db->join('warehouses w','w.id=po.warehouse_id','left');
        //$this->db->join('warehouse_distribution_center wdc','wdc.id=po.distributor_id','left');
        $this->db->join('quotations q','q.id=po.quotation_id','left');
        $this->db->join('branches b','b.id=po.branch_id','left');
        $this->db->join('vendors v','v.id=po.vendor_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_orders po')->row();
    }

    function GetBillTransport($con)
    {
        $this->db->select('pr.id as prid,pr.*,vm.*');
        $this->db->join('vehicle_management vm','vm.purchase_received_id=pr.id','left');
        $this->db->where($con);
        return $this->db->get('purchase_received pr')->result();
    }

    function GetPurchaseorderdetails($con)
    {
        $this->db->select('pod.*,u.unit, a.asset_name,at.type,b.brand_name');
        $this->db->join('mst_asset_types at','at.id=pod.asset_type_id','left');
        $this->db->join('unit_types u','u.id=pod.unit_id','left');
        $this->db->join('assets a','a.id=pod.asset_id','left');
        $this->db->join('brands b','b.id=a.brand_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_order_details pod')->result();
    }

    function GetPurchaseorderdetails_single($con)
    {
        //if(pod.weight_price_id!=0,w.product_name,p.prd_name) as product_name
        $this->db->select('a.asset_name,at.type, pod.*,u.unit,b.brand_name');
        //$this->db->join('weight_price w','w.id=pod.weight_price_id','left');
        $this->db->join('mst_asset_types at','at.id=pod.asset_type_id','left');
        $this->db->join('assets a','a.id=pod.asset_id','left');
        $this->db->join('unit_types u','u.id=pod.unit_id','left');
        $this->db->join('brands b','b.id=a.brand_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_order_details pod')->row();
    }

    function GetPrdLotDetails($con)
    {
        //if(pod.weight_price_id!=0, wp.product_name, p.prd_name) as product_name, if(pod.weight_price_id!=0, wp.weight, "") as weight,
        $this->db->select('a.asset_name,a.quantity,u.unit,b.brand_name');
        $this->db->join('purchase_order_details pod','pod.asset_id=a.id','left');
        //$this->db->join('weight_price wp','wp.product_id=p.prd_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->join('brands b','b.id=a.brand_id','left');
        $this->db->where($con);
        return $this->db->get('assets a')->row();
    }

    function GetPrintLot($con)
    {
        //if(prd.weight_price_id!=0,wp.product_name,p.prd_name) as product_name
        $this->db->select('a.asset_name, pr.lot_no,pr.bill_no,vm.driver_name,vm.vehicle_number,vm.labour_charge,vm.extra_vendor_charge,prd.received_qty,prd.rate,prd.per_unit_price,prd.price,prd.cgst,prd.sgst,prd.cgstAmt,prd.sgstAmt,prd.fess,pr.amount,pr.total_amount,pr.created,po.order_number,po.purchase_date,v.shop_name,v.email,at.type,u.unit,if(prd.branch_id!=0,b.branch_title,"") as branch,prd.branch_id,if(po.quotation_id!=0,q.quotation_no,"") as quotation_no,po.quotation_id,ba.brand_name');
        //$this->db->join('weight_price wp','wp.id=prd.weight_price_id','left');
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->join('brands ba','ba.id=a.brand_id','left');
        $this->db->join('mst_asset_types at','at.id=a.asset_type_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->join('purchase_received pr','pr.id=prd.purchase_received_id','left');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
         $this->db->join('branches b','b.id=prd.branch_id','left');
        $this->db->join('vendors v','v.id=po.vendor_id','left');
        $this->db->join('quotations q','q.id=po.quotation_id','left');
        //$this->db->join('warehouses w','w.id=po.warehouse_id','left');
        $this->db->join('vehicle_management vm','vm.purchase_received_id=prd.purchase_received_id','left');
        $this->db->where($con);
        $this->db->group_by('prd.id');
        return $this->db->get('purchase_received_details prd')->result();
    }

	function ExportCSV($con)
	{ 
		$this->db->select('po.id,po.purchase_date,po.total_items,po.order_number,po.status ,v.shop_name,at.type,if(po.quotation_id!=0,qt.quotation_no,"-") as quotation_no,(select sum(total_amount) from purchase_received where purchase_order_id=po
            .id) sum ,(select sum(extra_vendor_charge+labour_charge) from vehicle_management where purchase_order_id=po.id ) transport');
        $this->db->join('vendors v', 'v.id = po.vendor_id','left');
        $this->db->join('mst_asset_types at', 'at.id = po.asset_type_id','left');
        $this->db->join('quotations qt','qt.id=po.quotation_id','left');
        $this->db->where($con);
        $this->db->order_by('po.id','DESC');
        $this->db->group_by('po.id');
        return $this->db->get('purchase_orders po')->result();      		
	}

    function podetails($con)
    {
        $this->db->select("po.order_number,po.purchase_date,v.shop_name,v.email,b.brand_name,p.prd_name,if(p.product_type='Open','',wp.weight) weight,pod.product_quantity");
        $this->db->join('purchase_order_details pod','pod.purchase_order_id=po.id','left');
        $this->db->join('vendors v','v.id=po.vendor_id','left');
        $this->db->join('products p','p.prd_id=pod.product_id','left');
        $this->db->join('weight_price wp','wp.id=pod.weight_price_id','left');
        $this->db->join('brands b','b.id=p.brand_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_orders po')->result();
    }

    function GetPrdCategory($con)
    {
        $this->db->select("*");
        $this->db->join('prd_categories p','p.prdcat_id=map.prd_category_id','left');
        $this->db->where($con);
        return $this->db->get('vendor_prd_category_map map')->result();
    }

    function GetMailData($con)
    {
        $this->db->select("po.id, po.purchase_date, pod.id, c.prdcat_name, s.prdsubcat_name, p.prd_name, if(wp.weight!='',wp.weight,'-') weight, pod.product_quantity,if(p.product_type='Open',pod.product_unit,'') product_unit");
        $this->db->join('purchase_orders po','po.id=pod.purchase_order_id','left');
        $this->db->join('prd_categories c','c.prdcat_id=pod.cat_id','left');
        $this->db->join('prd_subcategories s','s.prdsubcat_id=pod.subcat_id','left');
        $this->db->join('products p','p.prd_id=pod.product_id','left');
        $this->db->join('weight_price wp','wp.id=pod.weight_price_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_order_details pod')->result();
    }

    function GetPoReceivedMail($con)
    {
        $this->db->select("pr.id,a.asset_name,u.unit,ast.type as ast_type, prd.received_qty, pr.total_amount, po.order_number, po.purchase_date");
        $this->db->join('purchase_received_details prd','prd.purchase_received_id = pr.id','left');
        $this->db->join('assets a','a.id=prd.asset_id','left');
        $this->db->join('unit_types u','u.id=a.unit_id','left');
        $this->db->join('mst_asset_types ast','ast.id=prd.asset_type_id','left');
        $this->db->join('purchase_orders po','po.id=pr.purchase_order_id','left');
        $this->db->where($con);
        return $this->db->get('purchase_received pr')->result();
    }
    
    function updateData($tableName, $data, $condition  )
    {
        $this->db->where($condition);
        $this->db->update($tableName, $data);
    }

    function getQuotedata($con){
        $this->db->select("qd.id,qd.quantity,qd.amount,qd.asset_type_id,qd.asset_id,qd.quotation_id,ast.asset_name,c.title,s.sub_cat_title,u.unit,u.id as unit_id,s.id as subcat_id,c.id as cat_id,");
        $this->db->join('assets ast','qd.asset_id = ast.id','left');
        $this->db->join('categories c','c.id=ast.category_id','left');
        $this->db->join('sub_categories s','s.id=ast.subcategory_id','left');
        $this->db->join('unit_types u','u.id=ast.unit_id','left');
        $this->db->where($con);
        return $this->db->get('quotation_details qd')->result();
    }
  
   public function getCategoryData($con){
        $this->db->select("c.id,title");
        $this->db->join('assets ast','c.id=ast.category_id','left');
        $this->db->join('vendor_asset_type_map vat','vat.asset_type_id=ast.asset_type_id','left');
        $this->db->where($con);
        $this->db->order_by('c.title','ASC');
        $this->db->group_by('c.id');
        return $this->db->get('categories c')->result();
   }

   public function getReturnReplaceData($con){
    $this->db->select("pb.id,ast.asset_name, pb.barcode_number, pb.barcode_image, pb.parent_id,pb.purchase_received_detail_id,pb.quantity, pb.purchase_order_id, pb.asset_id, (select barcode_number from purchase_received_detail_barcode where id=pb.parent_id) parent_barcode,pb.is_returned,pb.is_replaced");
    $this->db->join('assets ast','pb.asset_id = ast.id','left');
    $this->db->where($con);
    return $this->db->get('purchase_received_detail_barcode pb')->result();
   }

}