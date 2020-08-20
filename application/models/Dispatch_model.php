<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatch_model extends CI_Model
{
    public $table = 'dispatch';                            
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = array('id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }

     private function _get_datatables_query($con)
    {
        $this->db->select('p.id,p.dn_number,p.dispatch_date,p.sent_to,e.name as employee_name,(select SUM(price) from dispatch_details where dispatch_id=p.id) as sum_amount');
        $this->db->from("dispatch p");
        $this->db->join("employees e","e.id = p.sent_to","left");
        $this->db->join("dispatch_details de","de.dispatch_id = p.id","right");
        $this->db->where($con);
        $this->db->where('de.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->distinct();
        /*$i = 0;
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
        }*/
    }
 
    function get_datatables($con,$date)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
       // $this->db->limit($_POST['length'], $_POST['start']);
		if($date!=0){
			$dates = explode("_",$date);
			$date1 = date("Y-m-d", strtotime($dates[0]));
			$date2 = date("Y-m-d", strtotime($dates[1]));
			$this->db->where('p.dispatch_date >=', $date1);
			$this->db->where('p.dispatch_date <=', $date2);
		}
        $query = $this->db->get();
        return $query->result();
    }

    function warehouse_get_datatables($con,$date)
    {
        $this->warehouse_get_datatables_query($con);

		if($date!=0){
			$dates = explode("_",$date);
			$date1 = date("Y-m-d", strtotime($dates[0]));
			$date2 = date("Y-m-d", strtotime($dates[1]));
			$this->db->where('p.dispatch_date >=', $date1);
			$this->db->where('p.dispatch_date <=', $date2);
		}
        $query = $this->db->get();
        return $query->result();
    }

    private function warehouse_get_datatables_query($con)
    {
        $this->db->select('p.id,p.dn_number,p.dispatch_date,p.sent_to,p.status,e.name as employee_name,(select SUM(price) from warehouse_dispatch_details where dispatch_id=p.id) as sum_amount, (select SUM(quantity) from warehouse_dispatch_details where dispatch_id=p.id) as sum_quantity, (select SUM(quantity * price * gst_percent) from warehouse_dispatch_details where dispatch_id=p.id) as gst');
        $this->db->from("warehouse_dispatch p");
        $this->db->join("employees e","e.id = p.sent_to","left");
        //$this->db->join("warehouse_dispatch_details de","de.dispatch_id = p.id","right");
        $this->db->where($con);
        $this->db->distinct();
    }
 
    function count_filtered($con)
    {
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($con)
    {
        $this->db->select('p.id,p.dn_number,p.dispatch_date,p.sent_to,e.name as employee_name,(select SUM(price) from dispatch_details where dispatch_id=p.id) as sum_amount');
        $this->db->from("dispatch p");
        $this->db->join("employees e","e.id = p.sent_to","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('a.asset_name,ast.id,ast.price,ast.quantity,ast.gst_percent,ast.lf_no');
        $this->db->where("ast.dispatch_id='".$id."'"); 
        $this->db->join("dispatch d","d.id = ast.dispatch_id","left");    
        $this->db->join("assets a","a.id = ast.product_id","left");    
        return $this->db->get('dispatch_details ast')->result();        
    }

    public function warehouse_getAllDetails($id)
    {
        $this->db->select('a.asset_name,a.purchase_date,d.dn_number,mat.type,pro.label,d.dispatch_date,e.name as employee_name,e.state_id,ast.id,a.price,ast.price as product_mrp,a.hsn,a.markup_percent,
        ast.quantity,ast.gst_percent,cat.title,siz.title as size,col.title as color,
        fab.title as fabric,cra.title as craft,a.sp_total,a.cost_total,ast.barcode_number,ast.barcode_image');
        $this->db->where("ast.dispatch_id='".$id."'"); 
        $this->db->join("warehouse_dispatch d","d.id = ast.dispatch_id","left");    
        $this->db->join("warehouse_details a","a.id = ast.product_id","left");
        $this->db->join("size siz","siz.id = a.size_id","left");
        $this->db->join("color col","col.id = a.color_id","left");
        $this->db->join("fabric fab","fab.id = a.fabric_id","left");
        $this->db->join("craft cra","cra.id = a.craft_id","left");
        $this->db->join("categories cat","cat.id = a.category_id","left");  
        $this->db->join("mst_asset_types mat","mat.id = a.asset_type_id","left");
        $this->db->join("product_type pro","pro.id = a.product_type_id","left");
        $this->db->join("employees e","e.id = d.sent_to","left");
        // $this->db->join("warehouse_details wd","wd.id = ast.dispatch_id","left");
        return $this->db->get('warehouse_dispatch_details ast')->result();        
    }

    function ExportCSV($con)
    { 
        $this->db->select('a.asset_name,(select sum(price) from dispatch_details where dispatch_id=po
            .id) sum,dd.gst_percent,dd.lf_no,dd.status,dd.quantity');
        $this->db->join('dispatch_details dd', 'po.id = dd.dispatch_id','left');
        $this->db->join('assets a', 'a.id = dd.product_id','left');
        /*$this->db->join('quotations qt','qt.id=po.quotation_id','left');*/
        $this->db->where($con);
        $this->db->order_by('po.id','DESC');
        $this->db->group_by('po.id');
        return $this->db->get('dispatch po')->result();              
    }

    public function getAssetsDetails($assetId)
    {
        $this->db->select('abm.*,assets.asset_name,branches.branch_title');       
        $this->db->join("assets","assets.id = abm.asset_id","left");                        
        $this->db->join("branches","branches.id = abm.branch_id","left");                        
        $this->db->where("abm.asset_id='".$assetId."'");     
        return $this->db->get('asset_branch_mappings abm')->result();
    }




   
}