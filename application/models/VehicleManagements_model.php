<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class VehicleManagements_model extends CI_Model
{
    var $column_order = array(null,'po.order_number','vm.vehicle_number','vm.driver_name','vm.labour_charge','vm.extra_vendor_charge','vm.created'); //set column field database for datatable orderable
    var $column_search = array('po.order_number','vm.vehicle_number','vm.driver_name','vm.labour_charge','vm.extra_vendor_charge','vm.created'); //set column field database for datatable searchable 
    var $order = array('vm.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($con)
    {
        $this->db->select('po.order_number,vm.vehicle_number,vm.driver_name,vm.labour_charge,vm.extra_vendor_charge,vm.created');      
        $this->db->from('vehicle_management vm');
        $this->db->join('purchase_orders po','po.id=vm.purchase_order_id','left');
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
        $this->db->select('po.order_number,vm.vehicle_number,vm.driver_name,vm.labour_charge,vm.extra_vendor_charge,vm.created');      
        $this->db->from('vehicle_management vm');
        $this->db->join('purchase_orders po','po.id=vm.purchase_order_id','left');
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