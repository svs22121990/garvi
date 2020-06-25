<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fabric_model extends CI_Model
{
    public $table = 'fabric';
    var $column_order = array(null,'fabric.title','fabric.status',null); //set column field database for datatable orderable
    var $column_search = array('fabric.title','fabric.status'); //set column field database for datatable searchable
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