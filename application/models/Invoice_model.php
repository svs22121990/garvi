<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_model extends CI_Model
{
    public $table = 'invoice';                            
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = array('i.id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }

     private function _get_datatables_query($con)
    {
        $this->db->select(
            'i.*,
            (select SUM(net_amount) from invoice_details where invoice_id=i.id) as sumAmount,
            (select label from sales_type where id=i.invoice_sales_type) as salesType,
            (select type from payment_types where id=i.payment_mode) as paymentMode
            ');
        $this->db->from("invoice i");
        $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
        //$this->db->join("invoice_details ide","ide.invoice_id = i.id","left");
        //$this->db->join("assets a","ide.product_id = a.id","left");
        /*$this->db->where($con);
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
        }*/
    }
 
    function get_datatables($con,$date)
    {
        $this->_get_datatables_query($con);
        //if($_POST['length'] != -1)
        //$this->db->limit($_POST['length'], $_POST['start']);
		if($date!=0){
				$dates = explode("_",$date);
				$date1 = date("Y-m-d", strtotime($dates[0]));
				$date2 = date("Y-m-d", strtotime($dates[1]));
				$this->db->where('i.date_of_invoice >=', $date1);
				$this->db->where('i.date_of_invoice <=', $date2);
		}
        $query = $this->db->get();
        return $query->result();
    }
	
	function get_limitRecord()
    {
        
        $this->db->select('i.*,(select SUM(net_amount) from invoice_details where invoice_id=i.id) as sumAmount');
        $this->db->from("invoice i");
        $this->db->order_by("id", "desc");
        $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
        $this->db->limit(5);
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
        $this->db->select('i.*,ide.*,a.*');
        $this->db->from("invoice i");
        $this->db->join("invoice_details ide","ide.invoice_id = i.id","left");
        $this->db->join("assets a","ide.product_id = a.id","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('ast.asset_name,ast.id,ast.category_id,ast.asset_type_id,ast.cgst_asset,ast.sgst_asset,ast.final_amount,ast.product_mrp,ast.discount_amount,ast.quantity,cat.title,mat.type,ast.warranty_type,ast.warranty_from_date,ast.warranty_to_date,ast.warranty_description,ast.sku,ast.hsn,ast.gst_percent,ast.lf_no');        
        $this->db->join("categories cat","cat.id = ast.category_id","left");
        $this->db->join("mst_asset_types mat","mat.id = ast.asset_type_id","left");
        $this->db->where("ast.product_id='".$id."'");     
        return $this->db->get('assets ast')->row();        
    }


    public function getAssetsDetails($assetId)
    {
        $this->db->select('abm.*,assets.asset_name,branches.branch_title');       
        $this->db->join("assets","assets.id = abm.asset_id","left");                        
        $this->db->join("branches","branches.id = abm.branch_id","left");                        
        $this->db->where("abm.asset_id='".$assetId."'");     
        return $this->db->get('asset_branch_mappings abm')->result();
    }


    function ExportCSV($con)
    { 
        $this->db->select('i.*,(select SUM(net_amount) from invoice_details where invoice_id=i.id) as sumAmount');
        $this->db->from("invoice i");
        $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
        $query = $this->db->get();
        return $query->result();              
    }
	
	public function getProduct()
	{
		$query = $this->db
                      ->select('a.asset_name,a.barcode_number,a.quantity,a.product_mrp,a.purchase_date,a.id,cat.title,siz.title as size,col.title as color,fab.title as fabric,cra.title as craft,')
                      ->join("size siz","siz.id = a.size_id","left")
                    ->join("color col","col.id = a.color_id","left")
                    ->join("fabric fab","fab.id = a.fabric_id","left")
                    ->join("craft cra","cra.id = a.craft_id","left")
                    ->join("categories cat","cat.id = a.category_id","left")
                      ->from('assets as a')
                      ->join('products as p','p.id=a.product_id')
					  ->where('a.created_by',$_SESSION[SESSION_NAME]['id'])
					  ->where('a.quantity>',0)
                      ->get();
		return $products = $query->result();
	}

   
}