<?php 
    if(!empty($getaction)) {

          if($getaction->is_list=='Y'){ $isChkList='checked';$is_list=$getaction->is_list; $list_function=$getaction->list_function; $listreadonly=''; $isReqlist='required';}else{
            $isChkList='';$is_list='N'; $list_function=''; $listreadonly='readonly'; $isReqlist='';} 
          
          if($getaction->is_add=='Y'){ $isChkAdd='checked';$is_add=$getaction->is_add;$add_function=$getaction->add_function; $addreadonly=''; $isReqadd='required';} else {
            $isChkAdd=''; $is_add='N'; $add_function=''; $addreadonly='readonly'; $isReqadd='';} 
         
          if($getaction->is_edit=='Y'){ $isChkEdit='checked';$is_edit=$getaction->is_edit;$edit_function=$getaction->edit_function; $editreadonly=''; $isReqedit='required';  } else{
            $isChkEdit='';$is_edit='N'; $edit_function=''; $editreadonly='readonly'; $isReqedit='';} 
         
          if($getaction->is_delete=='Y'){ $isChkDelete='checked';$is_delete=$getaction->is_delete;$delete_function=$getaction->delete_function; $deletereadonly=''; $isReqdelete='required'; } else{
            $isChkDelete='';$is_delete='N'; $delete_function=''; $deletereadonly='readonly'; $isReqdelete='';} 
         
          if($getaction->is_view=='Y'){ $isChkView='checked';$is_view=$getaction->is_view;$view_function=$getaction->view_function; $viewreadonly=''; $isReqview='required';  } else{
            $isChkView='';$is_view='N'; $view_function=''; $viewreadonly='readonly'; $isReqview=''; } 
         
          if($getaction->is_status=='Y'){ $isChkStatus='checked';$is_status=$getaction->is_status;$status_function=$getaction->status_function; $statusreadonly='';  $isReqstatus='required'; } else{
            $isChkStatus='';$is_status='N'; $status_function=''; $statusreadonly='readonly'; $isReqstatus=''; } 
        
          if($getaction->is_export=='Y'){ $isChkExport='checked';$is_export=$getaction->is_export;$export_function=$getaction->export_function; $exportreadonly='';  $isReqexport='required'; }else{
            $isChkExport='';$is_export='N'; $export_function=''; $exportreadonly='readonly'; $isReqexport='';} 
         
          if($getaction->is_import=='Y'){ $isChkImport='checked';$is_import=$getaction->is_import;$import_function=$getaction->import_function; $importreadonly=''; $isReqimport='required'; }else{
            $isChkImport='';$is_import='N'; $import_function=''; $importreadonly='readonly'; $isReqimport=''; } 

          if($getaction->is_transfer=='Y'){ $isChkTransfer='checked';$is_transfer=$getaction->is_transfer;$transfer_function=$getaction->transfer_function; $transferreadonly=''; $isReqtransfer='required'; }else{
            $isChkTransfer='';$is_transfer='N'; $transfer_function=''; $transferreadonly='readonly'; $isReqtransfer=''; } 

          if($getaction->is_add_existing_stock=='Y'){ $isChkexisting_stock='checked';$is_add_existing_stock=$getaction->is_add_existing_stock;$existing_stock_function=$getaction->existing_stock_function; $existingStockreadonly=''; $isReqexistingStock='required'; }else{
            $isChkexisting_stock='';$is_add_existing_stock='N'; $existing_stock_function=''; $existingStockreadonly='readonly'; $is_add_existing_stock=''; }  

          if($getaction->is_log_details=='Y'){ $isChkLogDetail='checked';$is_log_details=$getaction->is_log_details;$log_detail_function=$getaction->log_detail_function; $LogDetailreadonly=''; $isReqLogDetail='required'; }else{
          $isChkLogDetail='';$is_log_details='N'; $log_detail_function=''; $LogDetailreadonly='readonly'; $isReqLogDetail=''; }

          if($getaction->is_send_asset_for_maintenance=='Y'){ $isChkSendAsset='checked';$is_send_asset_for_maintenance=$getaction->is_send_asset_for_maintenance;$send_asset_for_maintenance_function=$getaction->send_asset_for_maintenance_function; $SendAssetreadonly=''; $isReqsend_asset_for_maintenance='required'; }else{
          $isChkSendAsset='';$is_send_asset_for_maintenance='N'; $send_asset_for_maintenance_function=''; $SendAssetreadonly='readonly'; $isReqsend_asset_for_maintenance=''; } 

          if($getaction->is_received=='Y'){ $isChkreceived='checked';$is_received=$getaction->is_received;$received_function=$getaction->received_function; $Sendreceivedreadonly=''; $isReqsendreceived='required'; }else{
          $isChkreceived='';$is_received='N'; $received_function=''; $Sendreceivedreadonly='readonly'; $isReqsendreceived=''; } 

           
              
?>

        <table class="table table-hover table-striped " id="tableBodyScroll" width="100%">
                <thead>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <th  width="10%"> Check </th>
                    <th width="20%"> Action </th>
                    <th> Function name </th>
                   
                  </tr>
                </thead>
                <tbody>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_list" class="checkBox" <?=$isChkList; ?>>
                      <input type="hidden"  name="is_list"  value="<?=$is_list; ?>" id="is_list_val">
                    </td>
                    <td width="20%">List</td>
                    <td><input <?=$listreadonly; ?> <?=$isReqlist; ?>  value="<?=$list_function; ?>" type="text" name="list_function" placeholder="Enter list function" id="list_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox"  id="is_add" class="checkBox" <?=$isChkAdd; ?>>
                     <input type="hidden"  name="is_add" value="<?=$is_add; ?>" id="is_add_val"></td>
                    <td width="20%">Add</td>
                    <td><input <?=$addreadonly; ?> <?=$isReqadd; ?> value="<?=$add_function; ?>" type="text" name="add_function" placeholder="Enter add function" id="add_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_edit" class="checkBox" <?=$isChkEdit; ?> >
                     <input type="hidden"   name="is_edit" value="<?=$is_edit; ?>" id="is_edit_val"></td>
                    <td width="20%">Edit</td>
                    <td><input <?=$editreadonly; ?> <?=$isReqedit; ?> value="<?=$edit_function; ?>" type="text" name="edit_function" placeholder="Enter edit function" id="edit_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_view" class="checkBox" <?=$isChkView; ?>>
                     <input type="hidden"  name="is_view"  value="<?=$is_view; ?>" id="is_view_val"></td>
                    <td width="20%">View</td>
                    <td><input <?=$viewreadonly; ?> <?=$isReqview; ?> value="<?=$view_function; ?>" type="text" name="view_function" placeholder="Enter view function" id="view_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_delete" class="checkBox" <?=$isChkDelete; ?>>
                     <input type="hidden" name="is_delete"   value="<?=$is_delete; ?>" id="is_delete_val"></td>
                    <td width="20%">Delete</td>
                    <td><input <?=$deletereadonly; ?> <?=$isReqdelete; ?> value="<?=$delete_function; ?>" type="text" name="delete_function" placeholder="Enter delete function" id="delete_function" class="form-control"></td>
                   
                  </tr>
                   <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_status" class="checkBox" <?=$isChkStatus; ?>>
                     <input type="hidden" name="is_status"   value="<?=$is_status; ?>" id="is_status_val"></td>
                    <td width="20%">Status</td>
                    <td><input <?=$statusreadonly; ?> <?=$isReqstatus; ?> value="<?=$status_function; ?>" type="text" name="status_function" placeholder="Enter status function" id="status_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox"  id="is_export" class="checkBox" <?=$isChkExport; ?>>
                     <input type="hidden" name="is_export" value="<?=$is_export; ?>" id="is_export_val"></td>
                    <td width="20%">Export</td>
                    <td><input <?=$exportreadonly; ?> <?=$isReqexport; ?> value="<?=$export_function; ?>" type="text" name="export_function" placeholder="Enter export function" id="export_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_import" class="checkBox" <?=$isChkImport; ?>>
                     <input type="hidden"  name="is_import"  value="<?=$is_import; ?>" id="is_import_val"></td>
                    <td width="20%">Import</td>
                    <td><input <?=$importreadonly; ?> <?=$isReqimport; ?> value="<?=$import_function; ?>"  type="text" name="import_function" placeholder="Enter import function" id="import_function" class="form-control"></td>
                   
                  </tr>
                 <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_transfer" class="checkBox" <?=$isChkTransfer; ?>>
                     <input type="hidden"  name="is_transfer"  value="<?=$is_transfer; ?>" id="is_transfer_val"></td>
                    <td width="20%">Transfer</td>
                    <td><input <?=$transferreadonly; ?> <?=$isReqtransfer; ?> value="<?=$transfer_function; ?>"  type="text" name="transfer_function" placeholder="Enter Transfer function" id="transfer_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_add_existing_stock" class="checkBox" <?=$isChkexisting_stock; ?>>
                     <input type="hidden"  name="is_add_existing_stock"  value="<?=$is_add_existing_stock; ?>" id="is_existing_stock_val"></td>
                    <td width="20%">Add Existing Stock</td>
                    <td><input <?=$existingStockreadonly; ?> <?=$is_add_existing_stock; ?> value="<?=$existing_stock_function; ?>"  type="text" name="existing_stock_function" placeholder="Enter Add Existing Stock function" id="existing_stock_function" class="form-control"></td>
                   
                  </tr>
                     <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_log_details" class="checkBox" <?=$isChkLogDetail; ?>>
                     <input type="hidden"  name="is_log_details"  value="<?=$is_log_details; ?>" id="is_log_detail_val"></td>
                    <td width="20%">Log Details</td>
                    <td><input <?=$LogDetailreadonly; ?> <?=$isReqLogDetail; ?> value="<?=$log_detail_function; ?>"  type="text" name="log_detail_function" placeholder="Enter Log Detail function" id="log_detail_function" class="form-control"></td>
                 </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_send_asset_for_maintenance" class="checkBox" <?=$isChkSendAsset; ?>>
                     <input type="hidden"  name="is_send_asset_for_maintenance"  value="<?=$is_send_asset_for_maintenance; ?>" id="is_send_asset_for_maintenance_val"></td>
                    <td width="20%">Send Asset For Maintenance</td>
                    <td><input <?=$SendAssetreadonly; ?> <?=$isReqsend_asset_for_maintenance; ?> value="<?=$send_asset_for_maintenance_function; ?>"  type="text" name="send_asset_for_maintenance_function" placeholder="Enter Send Asset For Maintenance" id="send_asset_for_maintenance_function" class="form-control"></td>
                   </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_received" class="checkBox" <?=$isChkreceived; ?>>
                     <input type="hidden"  name="is_received"  value="<?=$is_received; ?>" id="is_received_val"></td>
                    <td width="20%">Received</td>
                    <td><input <?=$Sendreceivedreadonly; ?> <?=$isReqsendreceived; ?> value="<?=$received_function; ?>"  type="text" name="received_function" placeholder="Enter Received function" id="received_function" class="form-control"></td>
                  </tr> 
                </tbody>
              </table>
              <?php } else { ?>

              <table class="table table-hover table-striped " id="tableBodyScroll" width="100%">
                <thead>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <th  width="10%"> Check </th>
                    <th width="20%"> Action </th>
                    <th> Function name </th>
                   
                  </tr>
                </thead>
                <tbody>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_list" class="checkBox">
                      <input type="hidden"  name="is_list"  value="N" id="is_list_val">
                    </td>
                    <td width="20%">List</td>
                    <td><input readonly type="text" name="list_function" placeholder="Enter list function" id="list_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox"  id="is_add" class="checkBox">
                     <input type="hidden"  name="is_add" value="N" id="is_add_val"></td>
                    <td width="20%">Add</td>
                    <td><input readonly type="text" name="add_function" placeholder="Enter add function" id="add_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_edit" class="checkBox">
                     <input type="hidden"   name="is_edit" value="N" id="is_edit_val"></td>
                    <td width="20%">Edit</td>
                    <td><input readonly type="text" name="edit_function" placeholder="Enter edit function" id="edit_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_view" class="checkBox">
                     <input type="hidden"  name="is_view"  value="N" id="is_view_val"></td>
                    <td width="20%">View</td>
                    <td><input readonly type="text" name="view_function" placeholder="Enter view function" id="view_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_delete" class="checkBox">
                     <input type="hidden" name="is_delete"   value="N" id="is_delete_val"></td>
                    <td width="20%">Delete</td>
                    <td><input readonly type="text" name="delete_function" placeholder="Enter delete function" id="delete_function" class="form-control"></td>
                   
                  </tr>
                   <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_status" class="checkBox">
                     <input type="hidden" name="is_status"   value="N" id="is_status_val"></td>
                    <td width="20%">Status</td>
                    <td><input readonly type="text" name="status_function" placeholder="Enter status function" id="status_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox"  id="is_export" class="checkBox">
                     <input type="hidden" name="is_export"  value="N" id="is_export_val"></td>
                    <td width="20%">Export</td>
                    <td><input readonly type="text" name="export_function" placeholder="Enter export function" id="export_function" class="form-control"></td>
                   
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_import" class="checkBox">
                     <input type="hidden"  name="is_import"  value="N" id="is_import_val"></td>
                    <td width="20%">Import</td>
                    <td><input readonly type="text" name="import_function" placeholder="Enter import function" id="import_function" class="form-control"></td>
                  </tr> 
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_transfer" class="checkBox">
                     <input type="hidden"  name="is_transfer"  value="N" id="is_transfer_val"></td>
                    <td width="20%">Transfer</td>
                    <td><input readonly type="text" name="transfer_function" placeholder="Enter Transfer function" id="transfer_function" class="form-control"></td>
                  </tr> 
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_add_existing_stock" class="checkBox">
                     <input type="hidden"  name="is_add_existing_stock"  value="N" id="is_existing_stock_val"></td>
                    <td width="20%">Add Existing Stock</td>
                    <td><input readonly type="text" name="existing_stock_function" placeholder="Enter Add Existing Stock" id="existing_stock_function" class="form-control"></td>
                  </tr>
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_log_details" class="checkBox">
                     <input type="hidden"  name="is_log_details"  value="N" id="is_log_detail_val"></td>
                    <td width="20%">Log Detail  </td>
                    <td><input readonly type="text" name="log_detail_function" placeholder="Enter Log Detail" id="log_detail_function" class="form-control"></td>
                  </tr> 
                  <tr style="table-layout:fixed;display:table;width:100%;">
                    <td width="10%"><input type="Checkbox" id="is_received" class="checkBox">
                     <input type="hidden"  name="is_received"  value="N" id="is_received_val"></td>
                    <td width="20%">Received</td>
                    <td><input readonly type="text" name="received_function" placeholder="Enter Received" id="received_function" class="form-control"></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>
              <script type="text/javascript">
                 $(function(){
                      $('.checkBox').click(function(){
                       
                        if($(this).is(':checked')){


                        var inpChk=$(':input:eq(' + ($(':input').index(this) + 1) + ')');
                        inpChk.val('Y');
                        var inp=$(':input:eq(' + ($(':input').index(this) + 2) + ')');
                        inp.removeAttr('readonly');
                        inp.attr('required',true);
                       }else{
                            
                            var inpChk=$(':input:eq(' + ($(':input').index(this) + 1) + ')');
                            inpChk.val('N');
                            var inp=$(':input:eq(' + ($(':input').index(this) + 2) + ')');
                            inp.val('');
                            inp.attr('readonly',true);
                            inp.removeAttr('required');
                       }
                      });
                    });
                      $(function(){
                        $("#myform").submit(function(){

                            var valid=0;
                            $(this).find('input[type=text]').each(function(){
                                if($(this).val() != "" ) valid+=1;
                            });

                            if(valid){
                        
                                return true;
                            }
                            else {
                                alert("Error: Please Check atleast one check box and  must fill value in input");
                                $('#is_list').focus();
                                $('#list_function').focus().css('border-color','orange');
                                return false;
                            }
                        });
                    });
                  </script>