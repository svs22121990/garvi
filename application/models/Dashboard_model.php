<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public $table = 'notes';
    var $column_order = array(null,'notes.title','notes.content','notes.status',null); //set column field database for datatable orderable
    var $column_search = array('notes.title','notes.content','notes.status'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); 
    function __construct()
    {
        parent::__construct();
    }
    
     private function _get_datatables_query()
    {
        

        $this->db->from($this->table);
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
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    public function getMedias($page)
    { 
        $offset = $page; 
        $limit = 7; 
        $sql = "select * from medias Where status = 'Active' order by id desc limit $offset ,$limit"; 
        $result = $this->db->query($sql)->result();
         return $result;
    }

    function getAllData($tablename,$condition='',$order='',$limit='')
    {
        if($condition !='')
        $this->db->where($condition);
        if($order !='')     
        $this->db->order_by($order);
        if($limit !='')     
        $this->db->limit($limit);       
        return $this->db->get($tablename)->result();
    }
    
public function GetData($table,$field='',$condition='',$group='',$order='',$limit='10',$result='')
    {
        if($field != '')
        $this->db->select($field);
        if($condition != '')
        $this->db->where($condition);
        if($order != '')
        $this->db->order_by($order);
        if($limit != '')
        $this->db->limit($limit);
        if($group != '')
        $this->db->group_by($group);
        if($result != '')
        {
            $return =  $this->db->get($table)->row();
        }else{
            $return =  $this->db->get($table)->result();
        }
        return $return;
    }
    
    public function GetInvoiceTotalSales(){
        $sql = "select i.created_by, sum(invd.total) as total_sales, me.name from invoice i 
        inner join invoice_details invd on i.id=invd.invoice_id
        inner join employees me on me.id = i.created_by
        group by i.created_by"; 
        $result = $this->db->query($sql)->result();
         return $result;
    }
	
	public function salecount($where)
    {
        $query = $this->db->select('i.amount_after_gst,in.date_of_invoice')
                        ->from('invoice_details as i')
                        ->join('invoice as in','in.id=i.invoice_id')
                        ->where('in.created_by',$_SESSION['ASSETSTRACKING']['id'])
                        ->where($where)
                        ->get();
        $invoices = $query->result_array();
        $countMonth = 0;
        foreach($invoices as $m)
        {
            $countMonth +=$m['amount_after_gst'];
        }
        return $countMonth;

    }

}