<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_job extends CI_Controller {
  function __construct()
  {
    parent::__construct();
    $this->load->model('Crud_model');
    $this->load->library('upload');
    $this->load->library('image_lib');
    $this->image_lib->clear();   
    $this->load->helper(array('form','url','html'));
    $this->load->database();
  }

    function depreciation()
    {
        $assets = $this->Crud_model->GetData('assets','id,depreciated_rate',"depreciated_rate!=''");
        $diff = 0;
        foreach($assets as $row)
        {
           $asset_details = $this->Crud_model->GetData('asset_details','id,created,price',"price!='' and created!='' and asset_id='".$row->id."' and status='In_use'");
           foreach($asset_details as $details)
           {
           	  $currentdate = new DateTime(date('Y-m-d H:i:s'));
			        $created_date = new DateTime($details->created);
              $difference = $currentdate->diff($created_date);
              $days = $difference->d;
              if($days>=180)
              {
              	$depreciated_rate = $row->depreciated_rate;
                $depreciation_amount = ($details->price * $depreciated_rate)/100;
                $depreciation_amount =  $details->price - $depreciation_amount;
              }
              else if($days<180)
              {
              	$depreciated_rate = $row->depreciated_rate/2;
                $depreciation_amount = ($details->price * $depreciated_rate)/100;
                $depreciation_amount = $details->price - $depreciation_amount;
              }

              $data = array(
                'asset_id' => $row->id,
                'asset_detail_id' => $details->id,
                'depreciated_rate' => $depreciated_rate,
                'actual_amount' => $details->price,
                'depreciated_amount' => $depreciation_amount,
                'created' => date('Y-m-d H:i:s')
              );

              $this->Crud_model->SaveData('assets_depreciation_log',$data);
           }
        }
    }
}