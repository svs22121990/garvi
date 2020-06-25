<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendors_model extends CI_Model
{

    var $column_order = array(null,'V.name','V.shop_name','V.email','V.mobile',null,'V.status',null);
    var $order = array('V.id' => 'DESC'); 

    function __construct()
    {
        parent::__construct();
    }
    
    private function _get_datatables_query($flag='')
    {
        $this->db->select('V.*,(select balance from vendor_transactions where vendor_id=V.id order by id desc limit 1) bal ');
        if(!empty($flag))
        $this->db->having('bal!=','0');
        $this->db->from('vendors V');
        
        $i = 0;    
        if($_POST['search']['value']) // if datatable send POST for search
        {
            $explode_string = explode(' ', $_POST['search']['value']);
            foreach ($explode_string as $show_string) 
            {  
                $cond  = " ";
                $cond.=" ( V.name  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR V.shop_name  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR V.email  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR V.mobile  LIKE '%".trim($show_string)."%' ";
                $cond.=" OR V.status LIKE '%".trim($show_string)."%') ";
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

    function get_datatables($flag='')
    {       
        $this->_get_datatables_query($flag);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

     public function count_all($flag='')
    {    
        $this->db->select('V.*,');
       
        $this->db->from('vendors V');
        return $this->db->count_all_results();
    }


    function count_filtered($flag='')
    {        
        $this->_get_datatables_query($flag);
        $query = $this->db->get();
        return $query->num_rows();
    }

     public function alldata($cond)
    {    
        $this->db->select("v.*, s.state_name,c.city_name,co.country_name");
        $this->db->from('vendors as v');
        $this->db->join('mst_states as s','s.id = v.state_id','left');
        $this->db->join('mst_cities c','c.id = v.city_id','left');
        $this->db->join('mst_countries co','co.id = v.country_id','left');
        $this->db->where($cond);   
        return $this->db->get()->row();
    }

     public function assetTypeData($Vendor_id)
    {    
        $this->db->select("pc.*");
        $this->db->from('vendor_asset_type_map as vpcm');
        $this->db->join('mst_asset_types as pc','vpcm.asset_type_id = pc.id','left');      
        $this->db->where("vpcm.vendor_id",$Vendor_id);   
        return $this->db->get()->result();
    }


    public function export_outstanding($flag)
    {
        $this->db->select('V.*,(select balance from vendor_transactions where vendor_id=V.id order by id desc limit 1) bal ');
        if(!empty($flag))
        $this->db->having('bal!=','0');
        $this->db->from('vendors V');
        return $this->db->get()->result();
    }

}