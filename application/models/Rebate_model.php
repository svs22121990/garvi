<?php

if(!defined('BASEPATH'))exit('No direct script access allowed');

class Rebate_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
   
    /*-------------------------GETTTING CITIES DATA SERVER SIDE MODEL CODE----------------*/
     var $column_order = array(null,'r.rebate_percent','r.status',null); //set column field database for datatable orderable
     var $column_search = array('r.rebate_percent','r.state_name'); //set column field database for datatable searchable
     var $order = array('r.id' => 'desc'); // default order 
     private function _get_datatables_query()
     {

        $this->db->select('r.*');
        $this->db->from('mst_rebate r');
        $this->db->order_by("r.id desc");
    
 
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        //if($_POST['length'] != -1)
        //$this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->select('r.*,mat.type');
        $this->db->from('mst_rebate r');
        $this->db->join('mst_asset_types mat', 'mat.id=r.product_type_id', 'inner');
        $this->db->order_by("r.id desc");
        return $this->db->count_all_results();
    }

    public function update($id,$data)
    {
        $this->db->where('id',$id);
        $this->db->update('mst_rebate',$data);
    }

    public function insert($table,$data)
    {
        $this->db->insert($table,$data);
    }
    
    public function getData($table,$con="",$type="")
    {
        if($con!="")
        {
            $this->db->where($con);
        }
        if($type!="")
        {
            return $this->db->get($table)->row();
        }else{
            return $this->db->get($table)->result();
        }
    }
     public function GetFieldData($table,$field='',$condition='',$group='',$order='',$limit='',$result='',$having='')
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
        if($having != '')
        $this->db->having($having);
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
        }
        if(!empty($condition))
        {
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

    //get save and update
    public function save($table,$data,$condition='')
    {
        $DataArray = array();
        if(!empty($data))
        {
            $data['modified']=date("Y-m-d H:i:s");
        }
        if(empty($condition))
        {
            $data['created']=date("Y-m-d H:i:s");
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

}
?>