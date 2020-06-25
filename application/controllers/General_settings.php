<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_settings extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('General_settings_model');
    $this->load->helper("file");
    $this->load->library('upload');        
    $this->load->library('image_lib'); 
    $this->load->database();
  }
  
  public function index()
  {
    $breadcrumbs="<ol class='breadcrumb'>
        <li><a href='".site_url('Dashboard')."'><i class='ace-icon fa fa-home home-icon'></i>Dashboard</a></li>
        <li class='active'>General Settings</li>
        </ol>";

  

    $row = $this->General_settings_model->GetData("settings");
   
    $data = array(
      'heading'=>'General Settings',
      'button'=>'Update',
      'breadcrumbs'=>$breadcrumbs,
      'action'=>site_url('General_settings/update_action'),
      'heading'=>'General Settings',
      'id'=>set_value('id',$row[0]->id),
       'sitetitle' =>set_value('sitetitle',$row[0]->sitetitle),
      'tagline' =>set_value('tagline',$row[0]->tagline),
      'phone' =>set_value('phone',$row[0]->phone),
      'email' =>set_value('email',$row[0]->email),
      'website' =>set_value('website',$row[0]->website),
      'footer' =>set_value('footer',$row[0]->footer),
      'working_hours' =>set_value('working_hours',$row[0]->working_hours),
      'view_viewer' =>set_value('view_viewer',$row[0]->view_viewer),
      'favicon' =>set_value('favicon',$row[0]->favicon), 
      'footer_logo' =>set_value('footer_logo',$row[0]->footer_logo),
      'header_logo' =>set_value('header_logo',$row[0]->header_logo),
      'js_metal_logo' =>set_value('js_metal_logo',$row[0]->js_metal_logo),
      'housing_development_logo' =>set_value('housing_development_logo',$row[0]->housing_development_logo),
      'address' =>set_value('address',$row[0]->address),
     
      ); 
    //print_r($data);exit;
    $this->load->view('general_settings/general_settings_form',$data);
  }

public function update_action()
{ 
 if($this->input->post('store_notice_is_selected') == 'No')
 {
    $store_notice = '';
 }
 else
 {
   $store_notice = $this->input->post('store_notice');
 }

  
        if($_FILES['header_logo']['name']!='')
        {
            $_POST['header_logo']= rand(0000,9999)."_".$_FILES['header_logo']['name'];
            $config2['image_library'] = 'gd2';
            $config2['source_image'] =  $_FILES['header_logo']['tmp_name'];
            $config2['new_image'] =   getcwd().'/uploads/logo/'.$_POST['header_logo'];
            $config2['allowed_types'] = 'JPG|PNG|jpg|png|gif|GIF|JPEG|jpeg';
            $config2['width'] = '254px';
            $config2['height'] = '64px';
            $config2['maintain_ratio'] = FALSE;
       
            $this->image_lib->initialize($config2);
            if(!$this->image_lib->resize())
            {
                $this->session->set_flashdata('image_error', 'This file type is not allowed');
                $this->index();
                return;
            }
         
              
            else
            {
                 $header_logo  = $_POST['header_logo'];
            }
        }else
        {
           $header_logo  = $_POST['old_header'];
        }

        if($_FILES['footer_logo']['name']!='')
        {
            $_POST['footer_logo']= rand(0000,9999)."_".$_FILES['footer_logo']['name'];
            $config2['image_library'] = 'gd2';
            $config2['source_image'] =  $_FILES['footer_logo']['tmp_name'];
            $config2['new_image'] =   getcwd().'/uploads/logo/'.$_POST['footer_logo'];
            $config2['allowed_types'] = 'JPG|PNG|jpg|png|gif|GIF|JPEG|jpeg';
            $config2['width'] = '254px';
            $config2['height'] = '64px';
            $config2['maintain_ratio'] = FALSE;
       
            $this->image_lib->initialize($config2);
            if(!$this->image_lib->resize())
            {
                $this->session->set_flashdata('image_error', 'This file type is not allowed');
                $this->index();
                return;
            }
         
              
            else
            {
                 $footer_logo  = $_POST['footer_logo'];
            }
        }else
        {
           $footer_logo  = $_POST['old_footer'];
        }
        if($_FILES['favicon']['name']!='')
        {
            $_POST['favicon']= rand(0000,9999)."_".$_FILES['favicon']['name'];
            $config2['image_library'] = 'gd2';
            $config2['source_image'] =  $_FILES['favicon']['tmp_name'];
            $config2['new_image'] =   getcwd().'/uploads/logo/'.$_POST['favicon'];
            $config2['allowed_types'] = 'JPG|PNG|jpg|png|gif|GIF|JPEG|jpeg';
            $config2['width'] = '150px';
            $config2['height'] = '150px';
            $config2['maintain_ratio'] = FALSE;
       
            $this->image_lib->initialize($config2);
            if(!$this->image_lib->resize())
            {
                $this->session->set_flashdata('image_error', 'This file type is not allowed');
                $this->index();
                return;
            }
           else
            {
                unlink('uploads/logo/'.$this->input->post('old_favicon'));
                 $favicon  = $_POST['favicon'];
            }
        }else
        {
           $favicon  = $_POST['old_favicon'];
        }
        if($_FILES['js_metal_logo']['name']!='')
        {
            $_POST['js_metal_logo']= rand(0000,9999)."_".$_FILES['js_metal_logo']['name'];
            $config2['image_library'] = 'gd2';
            $config2['source_image'] =  $_FILES['js_metal_logo']['tmp_name'];
            $config2['new_image'] =   getcwd().'/uploads/logo/'.$_POST['js_metal_logo'];
            $config2['allowed_types'] = 'JPG|PNG|jpg|png|gif|GIF|JPEG|jpeg';
            $config2['width'] = '150px';
            $config2['height'] = '150px';
            $config2['maintain_ratio'] = FALSE;
       
            $this->image_lib->initialize($config2);
            if(!$this->image_lib->resize())
            {
                $this->session->set_flashdata('image_error', 'This file type is not allowed');
                $this->index();
                return;
            }
           else
            {
                unlink('uploads/logo/'.$this->input->post('old_js_metal_logo'));
                 $js_metal_logo  = $_POST['js_metal_logo'];
            }
        }else
        {
           $js_metal_logo  = $_POST['old_js_metal_logo'];
        }
      if($_FILES['housing_development_logo']['name']!='')
        {
            $_POST['housing_development_logo']= rand(0000,9999)."_".$_FILES['housing_development_logo']['name'];
            $config2['image_library'] = 'gd2';
            $config2['source_image'] =  $_FILES['housing_development_logo']['tmp_name'];
            $config2['new_image'] =   getcwd().'/uploads/logo/'.$_POST['housing_development_logo'];
            $config2['allowed_types'] = 'JPG|PNG|jpg|png|gif|GIF|JPEG|jpeg';
            $config2['width'] = '150px';
            $config2['height'] = '150px';
            $config2['maintain_ratio'] = FALSE;
       
            $this->image_lib->initialize($config2);
            if(!$this->image_lib->resize())
            {
                $this->session->set_flashdata('image_error', 'This file type is not allowed');
                $this->index();
                return;
            }
           else
            {
                unlink('uploads/logo/'.$this->input->post('old_housing_development_logo'));
                 $housing_development_logo  = $_POST['housing_development_logo'];
            }
        }else
        {
           $housing_development_logo  = $_POST['old_housing_development_logo'];
        }
   
    $data = array(
          'currency_id' => $this->input->post('currency_id'), 
          'sitetitle' => $this->input->post('sitetitle'),
          'tagline' => $this->input->post('tagline'),
          'phone' => $this->input->post('phone'),
          'email' => $this->input->post('email'),
          'website' => $this->input->post('website'),
          'footer' => $this->input->post('footer'),
          'working_hours' => $this->input->post('working_hours'),
          'view_viewer' => $this->input->post('view_viewer'),
          'address' => $this->input->post('address'),
          'footer_logo' => $footer_logo,
          'header_logo' => $header_logo,
          'favicon' => $favicon,
          'js_metal_logo' => $js_metal_logo,
          'housing_development_logo' => $housing_development_logo,
          'modified' => date("Y-m-d h:i:s"),
      );  
    //print_r($data);exit;
    $id=$this->input->post('id');
    $this->Admin_model->SaveData("settings",$data,"id='".$id."'");   
  
    $this->session->set_flashdata('message', '<p>Settings has been updated successfully<p>  ');
    redirect('General_settings/index');
  
}





 
}
