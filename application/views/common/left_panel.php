<?php  
error_reporting(0); // Disable all errors.
$segment=$this->uri->segment(1);
$row = $this->Crud_model->GetData('employees','',"id='".$_SESSION[SESSION_NAME]['id']."'",'','','','1');
$con="ard.status='Pending'";
$request_new_assets = $this->Crud_model->GetData('assets_requests','request_no,total_assets,branch_id,id',"status='Pending'","","id desc");
$count_req_assets=count($request_new_assets);
$image=base_url('uploads/profile/profile_default.png');
if($row->image!='')
{
    $image=base_url('uploads/employee_images/'.$row->image);
}

$notifications = $this->Crud_model->GetData('notifications','',"date!='0000-00-00' and date='".date('Y-m-d')."' and branch_id='0' and is_read='No' and asset_details_id not in (select asset_detail_id from asset_branch_mappings_details)");

$getDesignation = $this->Crud_model->GetData('mst_designations',"designation_name","id='".$_SESSION[SESSION_NAME]['designation_id']."'",'','','','row');

$con1="ard.status!='Pending' and ar.branch_id='".$_SESSION[SESSION_NAME]['branch_id']."' and ard.is_read='No'";
$request_new_assets1 = $this->Crud_model->aseets_request($con1);
$count_req_assets1=count($request_new_assets1);

$assets_scrap_notifications = $this->Crud_model->GetData('notifications','',"date!='0000-00-00' and is_read='No' and branch_id!='0' and type ='Scrap'");

$isbranchScrapRead = $this->Crud_model->GetData('notifications','',"is_branch_read='No' and type ='Scrap' and branch_id='".$_SESSION[SESSION_NAME]['branch_id']."' and is_read='Yes'");

$assets_issue_reqests = $this->Crud_model->GetData('assets_issue_reqests','employee_id,remark_type_id,branch_id,id,asset_detail_id',"status ='Pending'");
$count_issue=count($assets_issue_reqests);
?>
<style type="text/css">
    .scroll_box { height:280px;overflow-y: scroll;overflow-x: hidden;}
</style>
<ul class="x-navigation">
        <li class="xn-logo">
            <a href="<?php echo site_url('Dashboard/index');?>" style="padding: 0;"><img src="<?php echo base_url(); ?>assets/img/Logo.jpg" style="height: 50px;width: 220px;"></a>
            <a href="#" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <img src="<?= $image; ?>" alt=""/>
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="<?= $image; ?>" alt=""/>
                </div>
                <div class="profile-data">
                    <div class="profile-data-name"><?php echo ucwords($row->name); ?></div>
                    <div class="profile-data-title"><?php if(!empty($getDesignation)){ if($row->type == 'SuperAdmin') { echo 'Super Admin'; } else { echo 'User'; } } else { echo 'N/A'; } ?></div>
                </div>
                <div class="profile-controls">
                    <a href="<?= site_url('Welcome/profile'); ?>" class="profile-control-left"><span class="fa fa-user"></span></a>
                    <a href="<?= site_url('Welcome/changePassword'); ?>" class="profile-control-right"><span class="fa fa-lock"></span></a>
                </div>
            </div>                                                                        
        </li>
        <li class="xn-search">
            <form role="form">
                <input type="text" id="filter" name="search" placeholder="Search..."/>
            </form>
        </li>  

        <?php if($row->type=='SuperAdmin')  { ?>
            <!-- <?php if($row->type!='SuperAdmin')  { ?>
            <li <?php if ($segment=='Dashboard')echo " class='active'"; ?>>
            <a href="<?php echo site_url('Dashboard/index');?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>
            <?php }?>                        
            </li> 
            <li>
                <a href="<?php echo site_url('Users/index'); ?>"><span class="fa fa-user"></span> <span class="xn-text">Manager Users</span></a>
            </li> 
            <li class="xn-openable <?php if ($segment=='Ra_modules' ||$segment=='Sub_category' ||$segment=='Assets_type' ||$segment=='Assets' || $segment=='Assets_Maintenance')echo " active"; ?>">
                <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Manage Role Access</span></a>
                <ul>

                    <li><a href="<?php echo site_url('Ra_modules/index');?>"><span class="fa fa-dedent"></span> Manage Modules</a></li>
                    <li><a href="<?php echo site_url('Ra_assign/index');?>"><span class="fa fa-dedent"></span> Manage Assign Modules</a></li>
                </ul>
            </li> -->
            <li <?php if ($segment=='Dashboard')echo " class='active'"; ?>>
                <a href="<?php echo site_url('Dashboard/index');?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>                        
            </li>
            <span class="searchbleDiv"> 
                <?php  
                if(!empty($_SESSION[SESSION_NAME]['action_list'])) { 
                    foreach ($_SESSION[SESSION_NAME]['action_list'] as $main ) { 
                        $cond="r.status='Active' and rm.module_name='".$main."' and ra.ra_designation_id='".$row->designation_id."'";
                        $getMenus=$this->Crud_model->getRolePermissionSubMenu($cond);
                        $ra_modules = $this->Crud_model->GetData('ra_modules','id',"module_name='".$main."'",'','','','1');  
                        $ra_menus =$this->Crud_model->GetData('ra_menus','',"ra_module_id='".$ra_modules->id."'");    
                        $count=count($ra_menus);

                        if($count =='1') {
                ?>
                        <li <?php if ($segment==$ra_menus[0]->value) echo "class='active'"; ?>>
                            <a href="<?php echo site_url($ra_menus[0]->value);?>"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $ra_menus[0]->menu_name; ?></span></a>
                        </li>   
                        <?php } else { 
                            $value=array();
                            foreach ($getMenus as $key) {
                                $value[]=$key->value;
                            }
                        ?>
                        <li class="xn-openable <?php if (in_array($segment, $value))echo " active"; ?>">
                            <a href="#"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $main; ?></span></a>
                            <ul>
                                <?php 
                                if(!empty($getMenus)) { 
                                    foreach ($getMenus as $sub ) {
                                ?>
                                <li class="<?php if($sub->value==$segment){ echo 'active'; }?>"><a href="<?php echo site_url($sub->value); ?>"><i class="fa fa-angle-right"></i>&nbsp; <?php echo $sub->menu_name; ?></a></li>
                                <?php  
                                    } 
                                } 
                                ?>
                            </ul>
                        </li> 
                        <?php } ?>   
                <?php 
                    } 
                }  
                ?>
            </span>
            <li class="xn-openable <?php if ($segment=='Ra_modules' ||$segment=='Sub_category' ||$segment=='Assets_type' ||$segment=='Assets' || $segment=='Assets_Maintenance')echo " active"; ?>">
                <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Manage Role Access</span></a>
                <ul>

                    <li><a href="<?php echo site_url('Ra_modules/index');?>"><span class="fa fa-dedent"></span> Manage Modules</a></li>
                    <li><a href="<?php echo site_url('Ra_assign/index');?>"><span class="fa fa-dedent"></span> Manage Assign Modules</a></li>
                </ul>
            </li>

        <?php } else {  ?>
        <li <?php if ($segment=='Dashboard')echo " class='active'"; ?>>
            <a href="<?php echo site_url('Dashboard/index');?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>                        
        </li>  
        <span class="searchbleDiv"> 
            <?php  if(!empty($_SESSION[SESSION_NAME]['action_list'])) { 
            foreach ($_SESSION[SESSION_NAME]['action_list'] as $main ) { 
            $cond="r.status='Active' and rm.module_name='".$main."' and ra.ra_designation_id='".$row->designation_id."'";
            $getMenus=$this->Crud_model->getRolePermissionSubMenu($cond);
            $ra_modules = $this->Crud_model->GetData('ra_modules','id',"module_name='".$main."'",'','','','1');  
            $ra_menus =$this->Crud_model->GetData('ra_menus','',"ra_module_id='".$ra_modules->id."'");    
            $count=count($ra_menus);

            if($count =='1') {
            ?>
            <li <?php if ($segment==$ra_menus[0]->value) echo "class='active'"; ?>>
            <a href="<?php echo site_url($ra_menus[0]->value);?>"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $ra_menus[0]->menu_name; ?></span></a>
            </li>   
            <?php } else { 
                $value=array();

                foreach ($getMenus as $key) {
                   $value[]=$key->value;
                } if($main){ ?>

             <li class="xn-openable <?php if (in_array($segment, $value))echo " active"; ?>">
                <a href="#"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $main; ?></span></a>
                <ul>
                    <?php if(!empty($getMenus)) { foreach ($getMenus as $sub ) {?>
                    <li class="<?php if($sub->value==$segment){ echo 'active'; }?>"><a href="<?php echo site_url($sub->value); ?>"><i class="fa fa-angle-right"></i>&nbsp; <?php echo $sub->menu_name; ?></a></li>
                    <?php  } } ?>
                </ul>
                </li> 
             <?php }  } ?>   
            <?php } }  ?>
                

        <?php } ?>
        </span>
        
</ul>
</div>
<!-- END PAGE SIDEBAR -->

<!-- PAGE CONTENT -->
<div class="page-content">
    
    <!-- START X-NAVIGATION VERTICAL -->
    <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
        <!-- TOGGLE NAVIGATION -->
        <li class="xn-icon-button">
            <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
        </li>
        <!-- END TOGGLE NAVIGATION -->
        <!-- SIGN OUT -->
        <li class="xn-icon-button pull-right">
            <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>                        
        </li> 
        <!-- END SIGN OUT -->
        <!-- MESSAGES -->
      <?php if($_SESSION[SESSION_NAME]['type']=='Admin'){?>
      <li class="xn-icon-button pull-right">
            <a href="#" title="Asset Issue Request"><span class="fa fa-bell"></span></a>
            <div class="informer informer-danger" id="count_isuue"><?php if($count_issue !='0') { echo $count_issue; } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title">Asset Issue Request</h3>
                     <?php if($count_issue !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-danger"><?php echo $count_issue ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll_box"  <?php if(!empty($assets_issue_reqests))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($assets_issue_reqests))  { 
                    foreach ($assets_issue_reqests as $reqests) { 
                    $employees = $this->Crud_model->GetData('employees','name,id',"id='".$reqests->employee_id."'",'','','','1');
                    $branches = $this->Crud_model->GetData('branches','branch_title,id',"id='".$reqests->branch_id."'",'','','','1');
                    $asset_details = $this->Crud_model->GetData('asset_details','barcode_number,id',"id='".$reqests->asset_detail_id."'",'','','','1');
                    ?>  
                    <a href="<?php echo site_url('Assets_issue_request/index') ?>" class="list-group-item">
                        <span class="contacts-title"><?php echo $employees->name; ?> - (<?php if(!empty($branches->branch_title)){ echo $branches->branch_title; } else { echo '-'; } ?>)</span>
                          <p><?php echo $asset_details->barcode_number; ?></p>
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   
                   <?php } ?>    
                    
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Assets_issue_request/index') ?>">Show all asset issue requests</a>
                </div>                            
            </div>                        
        </li> 
        <li class="xn-icon-button pull-right">
            <a href="#" title="New Asset Request"><span class="fa fa-comments"></span></a>
            <div class="informer informer-danger"><?php if($count_req_assets !='0') { echo $count_req_assets; } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title"> New Asset Request</h3>
                     <?php if($count_req_assets !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-danger"><?php echo $count_req_assets ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll scroll_box"  <?php if(!empty($request_new_assets))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($request_new_assets))  { 
                    foreach ($request_new_assets as $new_assets) { 
                    $branches = $this->Crud_model->GetData('branches','branch_title,id',"id='".$new_assets->branch_id."'",'','','','1');
                    ?>  
                    <a href="<?php echo site_url('Assets_request/assets_request_details/'.$new_assets->id) ?>" class="list-group-item">
                        <div class="list-group-status status-online"></div>
                      
                        <span class="contacts-title"><?php echo $new_assets->request_no; ?> - (<?php if(!empty($branches->branch_title)){ echo $branches->branch_title; } else { echo '-'; } ?>)</span>
                        <p>Total Asset Request<span class="badge badge-info"><?php echo $new_assets->total_assets; ?></span>  </p>
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   
                   <?php } ?>    
                   
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Assets_request/index') ?>">Show all asset requests</a>
                </div>                            
            </div>                        
        </li> 
          <li class="xn-icon-button pull-right">
            <a href="#" title="Request For Scrap Assets"><span class="fa fa-recycle"></span></a>
            <div class="informer informer-danger"><?php if(count($assets_scrap_notifications) !='0') { echo count($assets_scrap_notifications); } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title">Request For Scrap Assets</h3>                                
                    <?php if(count($assets_scrap_notifications) !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-danger"><?php echo count($assets_scrap_notifications) ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll_box"  <?php if(!empty($assets_scrap_notifications))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($assets_scrap_notifications))  { 
                    foreach ($assets_scrap_notifications as $row) { 
                       
                    $assets = $this->Crud_model->GetData('assets','',"id='".$row->assets_id."'",'','','','1');
                    $asset_details2 = $this->Crud_model->GetData('asset_details','barcode_number',"id='".$row->asset_details_id."'",'','','','1');
                    //print_r($this->db->last_query());exit;
                    $branchName = $this->Crud_model->GetData('branches','',"id='".$row->branch_id."'",'','','','1');
                    ?>  
                    <a href="<?php echo site_url('Scrap_assets/pending_scrap_assets'); ?>" class="list-group-item">
                        <div class="list-group-status"></div>
                        <?php if(!empty($assets->photo)) { ?>
                        <img src="<?php echo base_url();?>uploads/assetimages/<?php echo $assets->photo; ?>" class="pull-left" height="50%"/>
                        <?php } else { ?>
                        <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" class="pull-left" height="50%">   
                        <?php } ?>
                        <span class="contacts-title"><?php echo $assets->asset_name; ?>&nbsp;(<?php if(!empty($asset_details2->barcode_number)) { echo $asset_details2->barcode_number; } ?>)</span>
                        <span class="pull-right" style="color:red;">Scrap</span>
                        <p style="color:blue"><?php echo $branchName->branch_title; ?></p>
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   <?php } ?>
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Scrap_assets/pending_scrap_assets') ?>">Show all scrap assets</a>
                </div>                         
            </div>                        
        </li> 
           <li class="xn-icon-button pull-right">
            <a href="#" title="Notifications"><span class="fa fa-tasks"></span></a>
            <div class="informer informer-danger"><?php if(count($notifications) !='0') { echo count($notifications); } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title">Notifications</h3>                                
                    <?php if(count($notifications) !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-danger"><?php echo count($notifications) ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll_box"  <?php if(!empty($notifications))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($notifications))  { 
                    foreach ($notifications as $row) { 
                       
                    $assets = $this->Crud_model->GetData('assets','',"id='".$row->assets_id."'",'','','','1');
                    $asset_details1 = $this->Crud_model->GetData('asset_details','barcode_number',"id='".$row->asset_details_id."'",'','','','1');
                    ?>  
                    <a href="<?php echo site_url('Assets/warranty_read/'.$row->assets_id.'/'.$row->asset_details_id); ?>" class="list-group-item">
                        <div class="list-group-status"></div>
                        <?php if(!empty($assets->photo)) { ?>
                        <img src="<?php echo base_url();?>uploads/assetimages/<?php echo $assets->photo; ?>" class="pull-left" height="50%"/>
                        <?php } else { ?>
                        <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" class="pull-left" height="50%">   
                        <?php } ?>
                        <span class="contacts-title"><?php echo $assets->asset_name; ?>&nbsp; (<?php echo $asset_details1->barcode_number; ?>)</span>
                        <span class="pull-right" style="color:green;"><?php echo $row->type; ?></span>
                        <p style="color:red">Expired on <?php echo $row->date; ?></p>
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   
                   <?php } ?>    
                    
                </div>     
                <div class="panel-footer text-center">
                    &nbsp;
                </div>                            
            </div>                        
        </li> 
    <?php }
        if(!empty($getDesignation)){
    if($getDesignation->designation_name=='Branch Admin'){?>
        <li class="xn-icon-button pull-right">
            <a href="#" title="Approved / Rejected Asset Request"><span class="fa fa-comments"></span></a>
            <div class="informer informer-danger"><?php if($count_req_assets1 !='0') { echo $count_req_assets1; } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title"> Approved / Rejected  Asset Request</h3>  
                    <?php if($count_req_assets1 !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-danger"><?php echo $count_req_assets1 ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll_box"   <?php if(!empty($request_new_assets1))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($request_new_assets1))  { 
                    foreach ($request_new_assets1 as $new_assets) { 
                    $con="status!='Pending' and asset_request_id='".$new_assets->id."'";
                    $assets_request_details = $this->Crud_model->GetData('assets_request_details','id',$con);

                   ?>  
                    <a href="<?php echo site_url('Assets/assets_request_details/'.$new_assets->id) ?>" class="list-group-item">
                        <div class="list-group-status status-online"></div>
                      
                        <span class="contacts-title"><?php echo $new_assets->request_no; ?> </span>
                        <p>Total Asset Request : <span class="badge badge-info"><?php echo count($assets_request_details); ?></span> </p>
                    </a>
                   
                  <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   
                   <?php } ?>  
                    
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Assets/req_new_asset_list') ?>">Show all approved / rejected requests</a>
                </div>                            
            </div>                        
        </li>

          <li class="xn-icon-button pull-right">
            <a href="#" title="Notification For Scrap Assets"><span class="fa fa-recycle"></span></a>
            <div class="informer informer-danger"><?php if(count($isbranchScrapRead) !='0') { echo count($isbranchScrapRead); } ?></div>
            <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging">
                <div class="panel-heading">
                    <h3 class="panel-title">Notification For Scrap Assets</h3>                                
                    <?php if(count($isbranchScrapRead) !='0') { ?>                              
                    <div class="pull-right">
                        <span class="label label-warning"><?php echo count($isbranchScrapRead) ?> new</span>
                    </div>
                    <?php } ?>
                </div>
                <div class="panel-body list-group list-group-contacts scroll_box"  <?php if(!empty($isbranchScrapRead))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($isbranchScrapRead))  { 
                    foreach ($isbranchScrapRead as $branchread) { 
                       
                    $assets = $this->Crud_model->GetData('assets','',"id='".$branchread->assets_id."'",'','','','1');
                    $asset_details2 = $this->Crud_model->GetData('asset_details','barcode_number',"id='".$branchread->asset_details_id."'",'','','','1');
                    $assets_scrap = $this->Crud_model->GetData('assets_scrap','status',"asset_detail_id='".$branchread->asset_details_id."' and asset_id='".$branchread->assets_id."' and branch_id='".$_SESSION[SESSION_NAME]['branch_id']."'",'','','','1');
                   
                    ?>  
                    <a href="<?php echo site_url('Scrap_assets/index'); ?>" class="list-group-item">
                        <div class="list-group-status"></div>
                        <?php if(!empty($assets->photo)) { ?>
                        <img src="<?php echo base_url();?>uploads/assetimages/<?php echo $assets->photo; ?>" class="pull-left" height="50%"/>
                        <?php } else { ?>
                        <img src="<?php echo base_url(); ?>uploads/employee_images/default.jpg" class="pull-left" height="50%">   
                        <?php } ?>
                        <span class="contacts-title"><?php echo $assets->asset_name; ?>&nbsp;(<?php if(!empty($asset_details2->barcode_number)) { echo $asset_details2->barcode_number; } ?>)</span>
                        <?php if($assets_scrap->status == 'Approved') { ?>   <span class="pull-right label label-success"><?php echo $assets_scrap->status; ?></span><?php } else { ?>

                     
                        <span class="pull-right label label-danger"><?php echo $assets_scrap->status; ?></span>
                        <?php } ?>
                       
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   <?php } ?>
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Scrap_assets/index') ?>">Show all scrap assets</a>
                </div>                         
            </div>                        
        </li> 
   <?php }}?>
</ul>