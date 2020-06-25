<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Size_model extends CI_Model
{
    public $table = 'size';
    var $column_order = array(null,'size.title','size.status',null); //set column field database for datatable orderable
    var $column_search = array('size.title','size.status'); //set column field database for datatable searchable
    var $order = array('id' => 'desc');
    function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->order_by("id","desc");

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
        $this->db->from($this->table);
        /* $this->db->Where("status","Active");   */
        return $this->db->count_all_results();
    }



}