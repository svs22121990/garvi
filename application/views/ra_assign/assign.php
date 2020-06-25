<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->          

<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->

<div class="page-content-wrap"> 

            <form method="post" action="<?php echo $action ?>">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <label> <h4><b>Select Role</b> <span style="color:red">*</span><span style="color:red" id="error_role_id"></span></h4></label>
                    <?php if(!empty($roles)){ ?>
                <select class="form-control" <?php if($button=='Update'){ ?> disabled <?php } ?> name="ra_designation_id" id="ra_designation_id">
                    <option value="0">--Select Role--</option>
                    <?php foreach($roles as $role_data){ ?>
                    <option value="<?php echo $role_data->id ?>" <?php if(!empty($roleId)) { if($roleId==$role_data->id) { ?> selected="selected" <?php } } ?> ><?php echo $role_data->designation_name ?></option>
                <?php } ?>
                </select>
                <?php } ?>
                </div>
                
                 <div class="col-md-1">
                </div>
             </div>
             <br>


         <?php if(!empty($ra_modules)){ foreach($ra_modules as $modules){

            $ra_menus=$this->Crud_model->GetData('ra_menus','',"ra_module_id='".$modules->id."' and status='Active'");

          ?>
        <div class="row">
            <div class="col-md-1">
            </div>
             <div class="col-md-10">

            <!-- START DEFAULT BUTTONS -->
            <div id="error_module_action" style="color:red"></div><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><input id="module_checkbox_id<?php echo $modules->id ?>" class="moduleName<?php echo $modules->id?>" type="checkbox" value="<?=$modules->id;?>" onclick="checkMenus(<?php echo $modules->id ?>)" name="ra_module_id[]" <?php if(!empty($module_id)) { if(in_array($modules->id, $module_id)) { ?> checked="checked" <?php } } ?>> <?php echo $modules->module_name; ?></h3>
                </div>
                <div class="panel-body"> 
                    <h4><b>Menus</b></h4>
                    <div class="form-group">

                        <?php if(!empty($ra_menus)){ foreach($ra_menus as $menus){ 

                            $ra_menu_action=$this->Crud_model->GetData('ra_menu_actions','',"ra_module_id='".$modules->id."' and ra_menu_id='".$menus->id."'",'','','','1');

                            ?>
                        <div class="col-md-6" style="border: 1px solid #e1e1e1">
                            <div class="">
                                <br>
                                <div class="left col-sm-1">
                                <input id="checkbox_id<?php echo $menus->id; ?>" class="moduleCheckbox checked<?php echo $modules->id ?> menuName<?php echo $modules->id ?><?php echo $menus->id; ?> valcheck" type="checkbox" onclick="checkMenusAction(<?php echo $modules->id ?>,<?php echo $menus->id; ?>)" name="ra_menu_id[]" value="<?=$menus->id;?>" <?php if(!empty($menuId)) { if(in_array($menus->id, $menuId)) { ?> checked="checked" <?php } } ?>>
                                </div>
                                <div class="rowvalue" style="font-size: 14px ; color:blue;"><?php echo $menus->menu_name; ?></div>
                                <h5>
                                <strong>Actions : </strong>
                                </h5>
                                <p style="margin-bottom: 15px ">
                                <span>
                                <?php if(!empty($ra_menu_action)){ ?>

                                <?php if($ra_menu_action->is_list=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?> check<?php echo $menus->id; ?>" <?php if(!empty($list)) { if(in_array($menus->id, $list)) { ?> checked="checked" <?php } } ?> value="list" type="checkbox" name="list_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                List
                                <?php } ?>

                                <?php if($ra_menu_action->is_add=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($add)) { if(in_array($menus->id, $add)) { ?> checked="checked" <?php } } ?> type="checkbox" value="add" name="add_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Add
                                <?php } ?>

                                <?php if($ra_menu_action->is_edit=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($edit)) { if(in_array($menus->id, $edit)) { ?> checked="checked" <?php } } ?> value="edit" type="checkbox" name="edit_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Edit
                                <?php } ?>

                                <?php if($ra_menu_action->is_delete=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($delete)) { if(in_array($menus->id, $delete)) { ?> checked="checked" <?php } } ?> value="delete" type="checkbox" name="delete_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Delete
                                <?php } ?>

                                 <?php if($ra_menu_action->is_view=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($view)) { if(in_array($menus->id, $view)) { ?> checked="checked" <?php } } ?> value="view" type="checkbox" name="view_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                View
                                <?php } ?>

                                <?php if($ra_menu_action->is_status=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($status)) { if(in_array($menus->id, $status)) { ?> checked="checked" <?php } } ?> value="status" type="checkbox" name="status_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Status
                                 <?php }  ?>

                                  <?php if($ra_menu_action->is_export=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($export)) { if(in_array($menus->id, $export)) { ?> checked="checked" <?php } } ?> value="export" type="checkbox" name="export_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Export
                                 <?php } ?>

                                  <?php if($ra_menu_action->is_import=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($import)) { if(in_array($menus->id, $import)) { ?> checked="checked" <?php } } ?> value="import" type="checkbox" name="import_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Import
                                <?php }  ?>  

                                <?php if($ra_menu_action->is_transfer=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($transfer)) { if(in_array($menus->id, $transfer)) { ?> checked="checked" <?php } } ?> value="transfer" type="checkbox" name="transfer_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Transfer
                                <?php }  ?> 

                                 <?php if($ra_menu_action->is_add_existing_stock=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($add_existing_stock)) { if(in_array($menus->id, $add_existing_stock)) { ?> checked="checked" <?php } } ?> value="add_existing_stock" type="checkbox" name="add_existing_stock_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Add Existing Stock
                                <?php }  ?> 

                                 <?php if($ra_menu_action->is_log_details=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($log_details)) { if(in_array($menus->id, $log_details)) { ?> checked="checked" <?php } } ?> value="log_details" type="checkbox" name="log_details_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Log Details
                                <?php }  ?> 

                                 <?php if($ra_menu_action->is_send_asset_for_maintenance=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($send_asset_for_maintenance)) { if(in_array($menus->id, $send_asset_for_maintenance)) { ?> checked="checked" <?php } } ?> value="send_asset_for_maintenance" type="checkbox" name="send_asset_for_maintenance_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                               Send Asset
                                <?php }  ?> 

                                <?php if($ra_menu_action->is_received=='Y'){ ?>
                                <input class="actioName<?php echo $menus->id ?> check<?php echo $menus->id; ?> checked<?php echo $modules->id ?> menu_check<?php echo $modules->id ?><?php echo $menus->id; ?>" <?php if(!empty($received)) { if(in_array($menus->id, $received)) { ?> checked="checked" <?php } } ?> value="received" type="checkbox" name="received_<?=$menus->id;?>" onclick="check_module(<?php echo $menus->id ?>,<?php echo $modules->id ?>)">
                                Received

                                 <?php } } ?>
                                </span>

                                </p>
                            </div>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
            <!-- END DEFAULT BUTTONS -->
        </div>
         <div class="col-md-1">
        </div>
    </div>

      <?php } } ?>
      <input type="hidden" name="button" value="<?php echo $button; ?>">

    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
        <div class="panel-footer">
            <button type="submit" onclick="return(addrole());" class="btn btn-success" type="button"><?php echo $button ?></button>&nbsp;
            <!-- <button type="submit" onclick="return(addrole());" class="btn btn btn-success pull-left">Assign</button> -->
        </div>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    <div>&nbsp;</div>

  </form>
</div>
<!-- END DEFAULT DATATABLE -->


<?php $this->load->view('common/footer');?>

<script>
    function checkMenus(id)
    { 
        var checked = $(".moduleName"+id).is(':checked');
        if(checked==true)
        {
            $(".checked"+id).prop('checked', true);
        }
        else
        {
            $(".checked"+id).prop('checked', false);
        }
    }

    function checkMenusAction(module_id,meun_id)
    { 
        var checked = $("#checkbox_id"+meun_id).is(':checked');
        if(checked==true)
        {
            $(".check"+meun_id).prop('checked', true);
            $("#module_checkbox_id"+module_id).prop('checked', true);
        }
       /* else
        {
            $(".menu_check"+module_id+meun_id).prop('checked', false);
            $("#module_checkbox_id"+module_id).prop('checked', false);
        }*/
        else
        {

          $(".check"+meun_id).prop('checked', false);
          var length = $('.checked'+module_id+':checked').length;
           if(length==0)
           {
             $(".moduleName"+module_id).prop('checked', false);
           }
         }
    }

    function check_module(menu_id,module_id)
    {
        var checked = $(".actioName"+menu_id).is(':checked');
        if(checked==true)
        {
            $("#checkbox_id"+menu_id).prop('checked', true);
            $("#module_checkbox_id"+module_id).prop('checked', true);
        }
        else
        {
            $("#checkbox_id"+menu_id).prop('checked', false);
            $("#module_checkbox_id"+module_id).prop('checked', false);
        }
    }
</script>
<script>
function addrole()
{
    var ra_designation_id=$('#ra_designation_id').val();
    var length = $('.valcheck:checked').length;

    if(ra_designation_id.trim()==0)
    {
      $("#error_role_id").html("Please select role.");
      setTimeout(function(){$("#error_role_id").html('');},10000);
      $("#ra_designation_id").focus();    
      return false;
    }

    if(length==0)
    {
      $("#error_module_action").html("Please select action.");
      setTimeout(function(){$("#error_module_action").html('');},10000);
      $("#length").focus();    
      return false;
    }
}

</script>


