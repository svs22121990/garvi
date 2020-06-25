<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{
    public $table = 'warehouse';
    var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity',null,null);

    var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.quantity');
    var $order = array('id' => 'desc');
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($con)
    {
        $this->db->select('p.id,p.dn_number,p.warehouse_date,p.sent_to,e.name as employee_name,(select SUM(price) from warehouse_details where warehouse_id=p.id) as sum_amount');
        $this->db->from("warehouse p");
        $this->db->join("employees e","e.id = p.sent_to","left");
        $this->db->join("warehouse_details de","de.warehouse_id = p.id","right");
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
            $this->db->where('p.warehouse_date >=', $date1);
            $this->db->where('p.warehouse_date <=', $date2);
        }
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
        $this->db->select('p.id,p.dn_number,p.warehouse_date,p.sent_to,e.name as employee_name,(select SUM(price) from warehouse_details where warehouse_id=p.id) as sum_amount');
        $this->db->from("warehouse p");
        $this->db->join("employees e","e.id = p.sent_to","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }


    public function getAllDetails($id)
    {
        $this->db->select('a.asset_name,ast.id,ast.price,ast.quantity,ast.gst_percent,ast.lf_no');
        $this->db->where("ast.warehouse_id='".$id."'");
        $this->db->join("warehouse d","d.id = ast.warehouse_id","left");
        $this->db->join("assets a","a.id = ast.product_id","left");
        return $this->db->get('warehouse_details ast')->result();
    }

    function ExportCSV($con)
    {
        $this->db->select('a.asset_name,(select sum(price) from warehouse_details where warehouse_id=po
            .id) sum,dd.gst_percent,dd.lf_no,dd.status,dd.quantity');
        $this->db->join('warehouse_details dd', 'po.id = dd.warehouse_id','left');
        $this->db->join('assets a', 'a.id = dd.product_id','left');
        /*$this->db->join('quotations qt','qt.id=po.quotation_id','left');*/
        $this->db->where($con);
        $this->db->order_by('po.id','DESC');
        $this->db->group_by('po.id');
        return $this->db->get('warehouse po')->result();
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