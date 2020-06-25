<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assets_logs_model extends CI_Model
{
    public $table = 'stock_logs';                            
    var $column_order = array(null,'stock_logs.type','stock_logs.quantity','stock_logs.available_quantity','stock_logs.description','stock_logs.transfer_from','stock_logs.transfer_to',null,null,null,null,null,null);

    var $column_search = array('stock_logs.type');  

    var $order = array('id' => 'desc'); 

    function __construct()
    {
        parent::__construct();
    }
    
     private function _get_datatables_query($con)
    {
        $this->db->select('stock_logs.*');
        $this->db->from("stock_logs");
        //$this->db->join("unit_types","unit_types.id = stock_logs.unit_id","left");
        $this->db->where($con);

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
 
                if(count($this->column_search) - 1 == $i) //lstock_logs loop
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
 
    function count_filtered($con)
    {
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($con)
    {
        $this->db->select('stock_logs.*,');
        $this->db->from("stock_logs");
        //$this->db->join("unit_types","unit_types.id = stock_logs.unit_id","left");
        $this->db->where($con);
        return $this->db->count_all_results();
    }

    function ExportCSV($con="")
    {
        $this->db->select('sl.*,fy.financial_year,br.branch_title,mat.type as ast_type,ast.asset_name');      
        $this->db->join('financial_years fy','fy.id=sl.financial_year_id','left');       
        $this->db->join('assets ast','ast.id=sl.asset_id','left');       
        $this->db->join('branches br','br.id=sl.branch_id','left');       
        $this->db->join('mst_asset_types mat','mat.id=sl.asset_type_id','left');       
        $this->db->where($con);
        return $this->db->get('stock_logs sl')->result();
    }   
}