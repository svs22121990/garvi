<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class GST_Summary_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*-------------------------GETTTING CITIES DATA SERVER SIDE MODEL CODE----------------*/
     var $column_order = array(null,null,null,null,null,null,null,null,null); //set column field database for datatable orderable
     var $column_search = array('ide.gst_rate','ide.gst_amount'); //set column field database for datatable searchable
     var $order = array('i.id' => 'ASC'); // default order 
     private function _get_datatables_query()
     {

        $this->db->select('ide.gst_rate');
        $this->db->from('invoice i');
        $this->db->join('invoice_details ide', 'i.id=ide.invoice_id', 'inner');
        $this->db->distinct();
        //$this->db->order_by("i.id desc");
        $this->db->where('i.created_by',$_SESSION[SESSION_NAME]['id']);
    
 
       /* $i = 0;
     
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
 
    function get_datatables($date)
    {
        $this->_get_datatables_query();
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
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
  
    public function count_all()
    {
        $this->db->select('i.*,ide.*');
        $this->db->from('invoice i');
        $this->db->join('invoice_details ide', 'i.id=ide.invoice_id', 'inner');
        $this->db->order_by("i.id desc");
        return $this->db->count_all_results();
    }

    function ExportCSV($con)
    { 
        $this->db->select('i.*,ide.*, a.*');
        $this->db->from("invoice i");
        $this->db->join("invoice_details ide","i.id = ide.invoice_id","left");
        $this->db->join("assets a","a.id = ide.product_id","left");
        $this->db->where($con);
        $this->db->order_by('i.id','DESC');
        $query = $this->db->get();
        return $query->result();              
    }
}
?>