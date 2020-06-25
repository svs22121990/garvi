<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General_settings_model extends CI_Model
{
    public $table = 'settings';
    var $column_order = array(null,'settings.currency_id','settings.sitetitle','settings.tagline','settings.timezone','settings.dateformat','settings.timeformat','settings.base_location','settings.phone','settings.email','settings.website','settings.store_notice',null); //set column field database for datatable orderable
    var $column_search = array('settings.currency_id','settings.sitetitle','settings.tagline','settings.timezone','settings.dateformat','settings.timeformat','settings.base_location','settings.phone','settings.email','settings.website','settings.store_notice'); //set column field database for datatable searchable 
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

   public function GetData($table,$field='',$condition='',$group='',$order='',$limit='',$result='')
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
     public function SaveData($table,$data,$condition='')
    {
        $DataArray = array();
        if(!empty($data))
        {
            $data['created']=date("Y-m-d H:i:s");
            $data['modified']=date("Y-m-d H:i:s");
        }
        $table_fields = $this->db->list_fields($table);
        foreach($data as $field=>$value)
        {
            if(in_array($field,$table_fields))
            {
                $DataArray[$field]= $value;
            }
        }
       
        if($condition != '')
        {
            $this->db->where($condition);
            $this->db->update($table, $DataArray);
        }else{
            $this->db->insert($table, $DataArray);
        }
    }

    public function DeleteData($table,$condition='',$limit='')
    {
       if($condition != '')
        $this->db->where($condition);
        if($limit != '')
        $this->db->limit($limit);
        $this->db->delete($table);
    }
    public function currencies___()
    {
        $this->db->select('cs.*,countries.country_name');
        $this->db->from('currencies cs');
        $this->db->join('countries', 'cs.country_id=countries.id', 'left'); 
        $query = $this->db->get();
        return $query->row();
        $this->db->order_by("cs.id desc");
    } 

    public function currencies()
    {
       $this->db->select("currencies.*,countries.country_name");
       $this->db->from("currencies");
       $this->db->join("countries","countries.id=currencies.country_id","left");
       return $this->db->get()->result();
    }


   
}