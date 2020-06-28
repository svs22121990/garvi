<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WarehouseBarcodes_model extends CI_Model
{
    public $table = 'warehouse_barcodes';
    //var $column_order = array(null,'cat.title','mat.type','ast.sku','ast.asset_name','ast.total_quantity',null,null);

    //var $column_search = array('cat.title','mat.type','ast.sku','ast.asset_name','ast.total_quantity');
    //var $order = array('id' => 'desc');
    function __construct()
    {
        parent::__construct();
    }

    public function getBarcodes($id)
    {
        $this->db->select('barcode_number, barcode_image, status');
        $this->db->where("warehouse_id='".$id."'");
        return $this->db->get('warehouse_barcodes')->result();
    }





}