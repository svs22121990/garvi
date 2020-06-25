<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Crud_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }


  public function GetData($table, $field = '', $condition = '', $group = '', $order = '', $limit = '', $result = '')
  {
    if ($field != '')
      $this->db->select($field);
    if ($condition != '')
      $this->db->where($condition);
    if ($order != '')
      $this->db->order_by($order);
    if ($limit != '')
      $this->db->limit($limit);
    if ($group != '')
      $this->db->group_by($group);
    if ($result != '') {
      $return =  $this->db->get($table)->row();
    } else {
      $return =  $this->db->get($table)->result();
    }
    return $return;
  }

  public function GetDataArr($table, $field = '', $condition = '', $group = '', $order = '', $limit = '', $result = '')
  {
    if ($field != '')
      $this->db->select($field);
    if ($condition != '')
      $this->db->where($condition);
    if ($order != '')
      $this->db->order_by($order);
    if ($limit != '')
      $this->db->limit($limit);
    if ($group != '')
      $this->db->group_by($group);
    if ($result != '') {
      $return =  $this->db->get($table)->row_array();
    } else {
      $return =  $this->db->get($table)->result_array();
    }
    return $return;
  }

  public function SaveData($table, $data, $condition = '')
  {
    $DataArray = array();
    if (!empty($data)) {
      if ($condition == '') {
        $data['created'] = date("Y-m-d H:i:s");
      } else {
        $data['modified'] = date("Y-m-d H:i:s");
      }
    }
    $table_fields = $this->db->list_fields($table);
    foreach ($data as $field => $value) {
      if (in_array($field, $table_fields)) {
        $DataArray[$field] = $value;
      }
    }
          // echo"<pre>"; print_r($DataArray);exit();

    if ($condition != '') {
      $this->db->where($condition);
      $this->db->update($table, $DataArray);
    } else {
      $this->db->insert($table, $DataArray);
    }
  }

  public function DeleteData($table, $condition = '', $limit = '')
  {
    if ($condition != '')
      $this->db->where($condition);
    if ($limit != '')
      $this->db->limit($limit);
    return $this->db->delete($table);
  }

  public function getCity()
  {
    $this->db->select('s.*,c.city_name');
    $this->db->from('shops s');
    $this->db->join('cities c', 's.city_id=c.id', 'INNER');
    //$this->db->where($con);
    $this->db->group_by('city_name');
    $query = $this->db->get();
    return $query->result();
  }
  public function get_locationfind($con, $latitude, $longitude, $hav)
  {

    $this->db->select("s.*,ROUND(( 3959 * acos( cos( radians('" . $latitude . "') ) * cos( radians( s.latitude ) ) * cos( radians(s.longitude )- radians('" . $longitude . "') ) + sin( radians('" . $latitude . "') ) * sin( radians( s.latitude ) ) ) ),2) as distance ");
    $this->db->from('shops s');
    $this->db->where($con);
    $this->db->having($hav);
    $this->db->limit("4");
    $query = $this->db->get();
    return $query->result();
  }
  public function getIpCity($con)
  {
    $this->db->select('ic.*,c.city_name');
    $this->db->from('ip_cities ic');
    $this->db->join('cities c', 'ic.city_id=c.id', 'left');
    $this->db->where($con);
    $query = $this->db->get();
    return $query->row();
  }
  public function topsearchCity($con)
  {
    $this->db->select('s.*,c.city_name');
    $this->db->from('shops s');
    $this->db->join('cities c', 's.city_id=c.id', 'INNER');
    $this->db->where($con);
    $this->db->group_by('s.city_id');
    $query = $this->db->get();
    return $query->result();
  }

  public function getScheduleData($con)
  {
    $this->db->select('service_packages.*,master_categories.title as cat_name,master_services.name as ser_name,master_services.service_description as ser_desc');
    $this->db->from('service_packages');
    $this->db->join('master_services', 'master_services.id=service_packages.service_id', 'left');
    $this->db->join('master_services', 'master_services.id=service_packages.service_id', 'left');
    $this->db->where($con);
    $query = $this->db->get();
    return $query->row();
  }

  function GetDataCatSubcat($con)
  {
    $this->db->select('sub_categories.sub_cat_title,categories.title');
    $this->db->from('sub_categories');
    $this->db->join('categories', 'categories.id=sub_categories.category_id', 'left');
    $this->db->where($con);
    $this->db->order_by('sub_cat_title asc');
    $query = $this->db->get();
    return $query->result();
  }

  function GetAssets($cond)
  {
    $this->db->select('assets.*,mst_asset_types.type');
    $this->db->from('assets');
    $this->db->join('mst_asset_types', 'mst_asset_types.id=assets.asset_type_id', 'left');
    $this->db->where($cond);
    $this->db->order_by('id desc');
    $this->db->limit('5');
    $query = $this->db->get();
    return $query->result();
  }

  function GetBranchAssets($cond)
  {
    /*$this->db->select('abm.asset_id,abm.asset_quantity ,assets.*,mst_asset_types.type');
      $this->db->from('asset_branch_mappings as abm');
      $this->db->join('assets','assets.id=abm.asset_id','left');
      $this->db->join('mst_asset_types','mst_asset_types.id=assets.asset_type_id','left');
      $this->db->where($cond);
      $this->db->order_by('id desc');
      $this->db->limit('5');
      $query = $this->db->get();
      return $query->result(); */
    $this->db->select('assets.*,mst_asset_types.type');
    //$this->db->from('asset_branch_mappings as abm');
    $this->db->from('assets');
    $this->db->join('mst_asset_types', 'mst_asset_types.id=assets.asset_type_id', 'left');
    $this->db->where($cond);
    $this->db->order_by('id desc');
    $this->db->limit('5');
    $query = $this->db->get();
    return $query->result();
  }

  function GetRoleAccess($cond)
  {
    $this->db->select('rm.module_name,r.menu_name,r.value');
    $this->db->from('ra_role_access ra');
    $this->db->join('ra_modules rm', 'rm.id=ra.ra_module_id', 'left');
    $this->db->join('ra_menus r', 'r.id=ra.ra_menu_id', 'left');
    $this->db->where($cond);
    $query = $this->db->get();
    return $query->result();
  }

  function Getassets_maintenance($con)
  {
    $this->db->select('assets_maintenance.*,mst_asset_types.type,asset_details.barcode_number,assets.asset_name,assets.photo');
    $this->db->from('assets_maintenance');
    $this->db->join('mst_asset_types', 'mst_asset_types.id=assets_maintenance.asset_type_id', 'left');
    $this->db->join('asset_details', 'asset_details.id=assets_maintenance.asset_details_id', 'left');
    $this->db->join('assets', 'assets.id=assets_maintenance.assets_id', 'left');
    $this->db->where($con);
    $this->db->order_by('id desc');
    $this->db->limit('5');
    $query = $this->db->get();
    return $query->result();
  }



  /*Get DashBord Asset Typewise Purchase Amount*/
  function getAssettypewiseCount($asset_type_id, $finyear)
  {

    $this->db->select('pod.asset_type_id,group_concat(pod.id) as poids,sum(po.total_amount) as totalpurchaseAmount,(select sum(quantity) as totqty from purchase_order_details where asset_type_id = pod.asset_type_id and status="Received") as totalQty');
    $this->db->where("pod.asset_type_id='" . $asset_type_id . "' and po.status='Received' and po.financial_year_id='" . $finyear . "'");
    $this->db->join('purchase_orders po', 'po.id=pod.purchase_order_id', 'left');
    $this->db->group_by('pod.asset_type_id', 'pod.purchase_order_id');
    return $this->db->get('purchase_order_details pod')->row();
  }
  /*Get DashBord Asset Typewise Purchase Amount*/


  function getRolePermissions($cond)
  {
    $this->db->select('ra.*,rm.module_name,rm.id as ra_module_id,r.menu_name,r.value,rm.id');
    $this->db->from('ra_role_access ra');
    $this->db->join('ra_modules rm', 'rm.id=ra.ra_module_id', 'left');
    $this->db->join('ra_menus r', 'r.id=ra.ra_menu_id', 'left');
    $this->db->group_by('rm.module_name');
    $this->db->where($cond);
    $this->db->order_by('rm.id asc');
    $query = $this->db->get();
    return $query->result();
  }
  function getRolePermissionSubMenu($cond)
  {
    $this->db->select('ra.*,r.menu_name,r.value,rm.module_name,r.id');
    $this->db->from('ra_role_access ra');
    $this->db->join('ra_modules rm', 'rm.id=ra.ra_module_id', 'left');
    $this->db->join('ra_menus r', 'r.id=ra.ra_menu_id', 'left');
    $this->db->where($cond);
    $this->db->order_by('r.id asc');
    $query = $this->db->get();
    return $query->result();
  }

  /*Get DashBord Asset Typewise Purchase Amount*/
  public function aseets_request($con)
  {
    $this->db->select('ar.* ');
    $this->db->from('assets_request_details ard');
    $this->db->join('assets_requests ar', 'ard.asset_request_id=ar.id', 'left');
    $this->db->where($con);
    $this->db->group_by('ar.request_no');
    $query = $this->db->get();
    return $query->result();
  }
  public function branch_title()
  {
    $this->db->select('b.branch_title');
    $this->db->from('asset_branch_mappings_details ard');
    $this->db->join('branches b', 'ard.branch_id=b.id', 'left');
    //  $this->db->where($con);
    $query = $this->db->get();
    return $query->row();
  }
}
