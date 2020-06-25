<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Party_invoice_model extends CI_Model
{

    var $column_order = array(null,'party_invoice.invoice_no','party_invoice.invoice_date','party_invoice.amount','party_invoice.per_discount','party_invoice.per_gst','party_invoice.final_amount','party_invoice.status',null); //set column field database for datatable orderable
    var $column_search = array('party_invoice.invoice_no','party_invoice.invoice_date','party_invoice.amount','party_invoice.per_discount','party_invoice.per_gst','party_invoice.final_amount','party_invoice.status'); //set column field database for datatable searchable 
    var $order = array('party_invoice.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('*');
        $this->db->from('party_invoice');
		$this->db->where($con);
        
        $i = 0;
    
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
        $this->db->select('*');
        $this->db->from('party_invoice');
        $this->db->where($con);
        return $this->db->count_all_results();
    }

    function count_filtered($con)
    {        
        $this->_get_datatables_query($con);
        $query = $this->db->get();
        return $query->num_rows();
    }
}