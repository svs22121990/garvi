<?php  
$segment=$this->uri->segment(1);
$row = $this->Crud_model->GetData('employees','',"id='".$_SESSION[SESSION_NAME]['id']."'",'','','','1');
$con="ard.status='Pending'";
$request_new_assets = $this->Crud_model->GetData('assets_requests','request_no,total_assets,branch_id,id',"status='Pending'");
$count_req_assets=count($request_new_assets);
$image=base_url('uploads/profile/profile_default.png');
if($row->image!='')
{
    $image=base_url('uploads/profile/'.$row->image);
}

$notifications = $this->Crud_model->GetData('notifications','',"date!='0000-00-00' and date='".date('Y-m-d')."' and is_read='No' and asset_details_id not in (select asset_detail_id from asset_branch_mappings_details)");

$getDesignation = $this->Crud_model->GetData('mst_designations',"designation_name","id='".$_SESSION[SESSION_NAME]['designation_id']."'",'','','','row');
?>
<ul class="x-navigation">
        <li class="xn-logo">
            <a href="<?php echo site_url('Dashboard/index');?>"><?php echo ucwords($row->type); ?></a>
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
                    <div class="profile-data-title"><?php if($row->type == 'SuperAdmin') { echo 'Super Admin'; } else { echo ucwords($getDesignation->designation_name); } ?></div>
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
            <li <?php if ($segment=='Dashboard')echo " class='active'"; ?>>
            <a href="<?php echo site_url('Dashboard/index');?>"><span class="fa fa-desktop"></span> <span class="xn-text">Dashboard</span></a>                        
            </li>  
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
            <?php  if($row->type !='Admin') { if(!empty($_SESSION[SESSION_NAME]['action_list'])) { 
            foreach ($_SESSION[SESSION_NAME]['action_list'] as $main ) { 
            $cond="r.status='Active' and rm.module_name='".$main."'";
            $getMenus=$this->Crud_model->getRolePermissionSubMenu($cond);

            $ra_modules = $this->Crud_model->GetData('ra_modules','id',"module_name='".$main."'",'','','','1');  
            $ra_menus =$this->Crud_model->GetData('ra_menus','',"ra_module_id='".$ra_modules->id."'");    
            $count=count($ra_menus);

            if($count =='1') {
            ?>
            <li <?php //if ($segment==$ra_menus[0]->value) echo "class='active'"; ?>>
            <a href="<?php echo site_url($ra_menus[0]->value);?>"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $ra_menus[0]->menu_name; ?></span></a>
            </li>   
            <?php } else { ?>
             <li class="xn-openable <?php //if ($segment=='Purchase_orders' || $segment=='Purchase_returns' || $segment=='Purchase_replace')echo " active"; ?>">
                <a href="#"><span class="fa fa-bars"></span> <span class="xn-text"><?php echo $main; ?></span></a>
                <ul>
                    <?php if(!empty($getMenus)) { foreach ($getMenus as $sub ) {?>
                    <li><a href="<?php echo site_url($sub->value); ?>"><i class="fa fa-angle-right"></i>&nbsp; <?php echo $sub->menu_name; ?></a></li>
                    <?php  } } ?>
                </ul>
                </li> 
             <?php } ?>   
            <?php } } } else { ?>
                <li class="xn-openable  <?php //if($segment=='Countries' || $segment=='States' || $segment=='Cities' || $segment=='Designations'  || $segment=='Financial_year' || $segment=='Unit_types' || $segment=='Categories' || $segment=='Sub_category' || $segment=='Manufacturers' || $segment=='Brands' || $segment=='Mail_body')echo " active"; ?>">
                <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Masters</span></a>
                <ul>
                    <li><a href="<?php echo site_url('Countries/index');?>"><span class="fa fa-map-marker"></span> Manage Countries</a></li>
                    <li><a href="<?php echo site_url('States/index');?>"><span class="fa fa-map-marker"></span> Manage States</a></li>
                    <li><a href="<?php echo site_url('Cities/index');?>"><span class="fa fa-map-marker"></span> Manage Cities</a></li>  
                    <!-- <li><a href="<?php echo site_url('Assets_type/index');?>"><span class="fa fa-briefcase"></span> Manage Assets Types</a></li> -->
                    <li><a href="<?php echo site_url('Financial_year/index');?>"><span class="fa fa-calendar-o"></span> Manage Financial year</a></li>
                    <li><a href="<?php echo site_url('Designations/index');?>"><span class="fa  fa-thumb-tack"></span> Manage Designations</a></li>                
                    
                    <li><a href="<?php echo site_url('Unit_types/index');?>"><span class="fa fa-building-o"></span> Manage Unit Types</a></li>
                    <li><a href="<?php echo site_url('Manufacturers/index');?>"><span class="fa fa-folder-open-o"></span> Manage Manufacturers</a></li>
                    <li><a href="<?php echo site_url('Brands/index');?>"><span class="fa fa-bars"></span> Manage Brands</a></li>
                    <li><a href="<?php echo site_url('Mail_body/index');?>"><span class="fa fa-envelope"></span> Manage Mail Body</a></li>
                  <!--   <li class="xn-openable">
                        <a href="#"><span class="fa fa-clock-o"></span> Timeline</a>
                        <ul>
                            <li><a href="pages-timeline.html"><span class="fa fa-align-center"></span> Default</a></li>
                            <li><a href="pages-timeline-simple.html"><span class="fa fa-align-justify"></span> Full Width</a></li>
                        </ul>
                    </li> -->
                                       
                </ul>
            </li>

        <li class="xn-openable <?php if ($segment=='Categories' ||$segment=='Sub_category' ||$segment=='Assets_type' ||$segment=='Assets' || $segment=='Assets_Maintenance')echo " active"; ?>">
            <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Manage Assets</span></a>
            <ul>

                <li><a href="<?php echo site_url('Categories/index');?>"><span class="fa fa-dedent"></span> Manage Categories</a></li>
                <li><a href="<?php echo site_url('Sub_category/index');?>"><span class="fa fa-dedent"></span> Manage Sub Categories</a></li>
                <li><a href="<?php echo site_url('Assets_type/index');?>"><span class="fa fa-briefcase"></span> Manage Asset Types</a></li>
                <li><a href="<?php echo site_url('Assets/index');?>"><span class="fa fa-briefcase"></span> Manage Assets</a></li>
                <li><a href="<?php echo site_url('Assets_Maintenance/index');?>"><span class="fa fa-briefcase"></span> Manage Asset Maintenance</a></li>
                <li><a href="<?php echo site_url('Assets_request/index');?>"><span class="fa fa-briefcase"></span> Manage Assets Request</a></li>
                <li><a href="<?php echo site_url('Maintenance_scheduling/index');?>"><span class="fa fa-briefcase"></span> Manage Assets Scheduling</a></li>
                <li><a href="<?php echo site_url('Scrap_assets/index');?>"><span class="fa fa-briefcase"></span> Manage Scrap Assets</a></li>
                <li><a href="<?php echo site_url('Depreciation/index');?>"><span class="fa fa-briefcase"></span> Manage Depreciation</a></li>
            </ul>
        </li>

        <li class="xn-openable <?php if ($segment=='Vendors')echo " active"; ?>">
            <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Manage Vendors</span></a>
            <ul>
                <li><a href="<?php echo site_url('Vendors/index');?>"><span class="fa fa-users"></span> Vendors</a></li>
                <li><a href="<?php echo site_url('Vendors/index/outstanding');?>"><span class="fa fa-money"></span> Vendors Pending Payments</a></li>
            </ul>
        </li>

         <li <?php if ($segment=='Quotation_request') echo "class='active'"; ?>>
            <a href="<?php echo site_url('Quotation_request/index');?>"><span class="fa fa-file-o"></span> <span class="xn-text">Quotation Requests</span></a>
        </li>

        <li <?php if ($segment=='Quotations') echo "class='active'"; ?>>
            <a href="<?php echo site_url('Quotations/index');?>"><span class="fa fa-file-o"></span> <span class="xn-text">Manage Quotations</span></a>
        </li>

        <li class="xn-openable <?php if ($segment=='Purchase_orders' || $segment=='Purchase_returns' || $segment=='Purchase_replace')echo " active"; ?>">
            <a href="#"><span class="fa fa-bars"></span> <span class="xn-text">Manage Purchase</span></a>
            <ul>
                <li><a href="<?php echo site_url('Purchase_orders/index');?>"><span class="fa fa-shopping-cart"></span> Purchase Orders</a></li>
                <li><a href="<?php echo site_url('Purchase_returns/index');?>"><span class="fa fa-undo"></span> Purchase Return</a></li>
                <li><a href="<?php echo '#';?>"><span class="fa fa-exchange"></span> Purchase Replace</a></li>
                <!-- site_url('Purchase_replace/index') site_url('Purchase_returns/index')-->
            </ul>
        </li>  
         <li <?php if ($segment=='Payments') echo "class='active'"; ?>>
            <a href="<?php echo site_url('Payments/index');?>"><span class="fa fa-money"></span> <span class="xn-text">Manage Payments</span></a>
        </li>
        
        
        <li <?php if ($segment=='Employees') echo "class='active'"; ?>>
            <a href="<?php echo site_url('Employees/index');?>"><span class="fa fa-users"></span> <span class="xn-text">Manage Employees</span></a>
        </li>

        <li <?php if ($segment=='Branches') echo "class='active'"; ?>>
            <a href="<?php echo site_url('Branches/index');?>"><span class="fa fa-building-o"></span> <span class="xn-text">Manage Branches</span></a>
        </li>  

        <li <?php if ($segment=='Login_logs')echo "class='active'"; ?>>
            <a href="<?php echo site_url('Login_logs/index');?>"><span class="fa fa-clock-o"></span> <span class="xn-text">Manage Login-logs</span></a>
        </li>

        <li <?php if ($segment=='Helplines')echo "class='active'"; ?>>
            <a href="<?php echo site_url('Helplines/index');?>"><span class="fa fa-phone"></span> <span class="xn-text">Manage Helplines</span></a>
        </li>


                 <?php } ?>
          

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
                <div class="panel-body list-group list-group-contacts scroll"  <?php if(!empty($request_new_assets))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

                  <?php if(!empty($request_new_assets))  { 
                    foreach ($request_new_assets as $new_assets) { 
                    $branches = $this->Crud_model->GetData('branches','branch_title,id',"id='".$new_assets->branch_id."'",'','','','1');
                    ?>  
                    <a href="<?php echo site_url('Assets_request/assets_request_details/'.$new_assets->id) ?>" class="list-group-item">
                        <div class="list-group-status status-online"></div>
                      
                        <span class="contacts-title"><?php echo $new_assets->request_no; ?> - (<?php echo $branches->branch_title ?>)</span>
                        <p><span class="badge badge-info"><?php echo $new_assets->total_assets; ?></span>  Asset Request</p>
                    </a>
                   
                 <?php } } else { ?>
                  <br>
                    <center><span>No Record Found</span></center>
                   
                   <?php } ?>    
                    
                </div>     
                <div class="panel-footer text-center">
                    <a href="<?php echo site_url('Assets_request/index') ?>">Show all requests</a>
                </div>                            
            </div>                        
        </li> 
        <!-- END MESSAGES -->



          <!-- MESSAGES FOR ASSETS EXPIRED -->
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
                <div class="panel-body list-group list-group-contacts scroll"  <?php if(!empty($request_new_assets))  { ?> style="height: 200px;" <?php } else { ?>  style="height: 50px;"<?php } ?>>

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
        <!-- END MESSAGES FOR ASSETS EXPIRED -->





    </ul>