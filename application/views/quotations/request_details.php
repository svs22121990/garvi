                    <?php 
                      if(!empty($detail_data)){
                        $no=1;
                      foreach($detail_data as $row){
                       ?>
                                <div class="panel panel-primary acc" id="acc_<?=$row->user_id; ?>">
                                    <input type="hidden" class="vids" name="user_id[]" value="<?=$row->user_id?>">
                                    <div class="panel-heading">
                                        <h4 class="panel-title col-md-5">
                                            <a href="javascript:void(0)" onclick="return toggleAcc('<?=$row->user_id; ?>')">
                                                <span id="userName_<?=$row->user_id?>"></span>

                                            </a>
                                        </h4>
                                       
                                            <div class="text col-md-6" id="totaldiv<?=$row->user_id; ?>" style="color:black">
                                                <label class="col-md-3"> Required Qty :</label>
                                                <input type="text" readonly="" value="<?php echo $remain_qty; ?>" id="requredQty<?=$row->user_id; ?>" class="requredQty col-md-3">
                                                <label class="col-md-3"> Total Qty :</label>
                                                <input type="text" readonly="" name="totalQtyAll" value="<?php echo $remain_qty; ?>"  class="totalQtyAll col-md-3">

                                                <input type="hidden" name="requredQty" value="<?php echo $remain_qty; ?>" id="orgtotalqty">
                                                 <div class="col-md-12">
                                                     
                                                </div>
                                            </div>
                                       
                                    </div>                                
                                    <div class="panel-body panel-body-open" id="accTwoCol<?=$row->user_id?>">
                                    <div class="col-md-12 no-padding">
                                    <div class="col-md-12">    
                                        <span class="errQty">&nbsp;</span>    
                                    </div>
                                    <div class="col-md-4">
                                        <label > User Quotation No.</label>
                                        <input type="text" name="<?=$row->user_id?>_vendor_quotation_no" class="form-control"> 
                                    </div>
                                    <div class="col-md-4">
                                        <label > User Quotation copy <span id="errorimage<?=$row->user_id?>">&nbsp;</span></label>
                                        <input type="file" id="vendor_quote_copy_<?=$row->user_id?>" name="<?=$row->user_id?>_vendor_quote_copy" class="form-control" onchange="onlyimage('<?=$row->user_id?>');">
                                        <p><small class="text-danger">File as type of Image(jpg,png)/Pdf.</small></p>
                                    </div>
                                          
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                     <table class="table table-bordered table-responsive">
                                        <thead>
                                            <th>Sr</th>
                                            <th>Asset Type</th>
                                            <th>Assets</th>
                                            <th>Quantity <span style="color:red">*</span><span style="color:red" id="qerror2<?=$row->user_id?>"></span></th>
                                            <th>Per Unit MRP <span style="color:red">*</span><span style="color:red" id="merror2<?=$row->user_id?>"></span></th>
                                            <th>Per Unit Price <span style="color:red">*</span><span style="color:red" id="perror3<?=$row->user_id?>"></span></th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="clonetable_feedback_<?=$row->user_id?>">
                                        <?php 
                                        $cond="qrt.user_id='".$row->user_id."' and qrt.quotation_request_id='".$row->quotation_request_id."' and qrt.remaining_qty > 0";
                                        $vendor_data=$this->Quotations_model->GetVendorQuotrequestData($cond);
                                        $ttlQty=0;
                                         $sr=1;
                                        foreach ($vendor_data as $data ) {
                                            $getpreviousData=$this->Crud_model->getData('quotation_details','sum(quantity) as exqty',"quotation_request_id='".$data->quotation_request_id."' and asset_id='".$data->asset_id."'");
                                       // print_r($getpreviousData[0]->exqty);echo "<br/>";
                                       // print_r($data);
                                        if(!empty($getpreviousData[0]->exqty)){
                                            $exqty=$getpreviousData[0]->exqty;
                                        }else{
                                            $exqty=0;
                                        }
                                        if($exqty <  $data->quantity){
                                            $ttlQty=$ttlQty+$data->remaining_qty;
                                        
                                        ?>
                                         <tr>
                                                <td><?php echo $sr; ?></td>
                                                <td><?php echo $data->type ?>
                                                    <input type="hidden" name="<?=$row->user_id?>_assets_type_id[]" value="<?=$data->assets_type_id; ?>">
                                                </td>
                                                <td><?php echo ucfirst($data->asset_name); ?>
                                                    <input type="hidden" name="<?=$row->user_id?>_asset_id[]" value="<?=$data->asset_id; ?>">
                                                    
                                                </td>
                                                <td>
                                                   <input name="<?=$row->user_id?>_quantity[]" type="text" onkeypress="only_number(event)" id="qunatity_<?php echo $data->id ?>"  value="<?php echo $data->remaining_qty ?>" class="form-control qunatity_<?php echo $data->user_id ?> qunatity_<?=$data->asset_id.'-'.$sr; ?>" oninput="return getRemainingqty('<?php echo $data->id; ?>','<?=$row->user_id?>','<?=$data->asset_id; ?>','<?= $sr; ?>')">
                                                   <input type="hidden" name="<?=$row->user_id?>_originqty[]" id="originqty<?=$data->id; ?>" value="<?=$data->remaining_qty?>" class="originqty_<?=$data->asset_id?>">
                                                <!--   <small>Remaining Qty<span class="text-success" id="remainQty<?=$data->id; ?>"> <b>(0)</b></span></small> -->
                                                </td>
                                                <td>
                                                    
                                                    <input class="form-control mrp_<?php echo $data->user_id ?>" onkeypress="only_number(event)" id="mrp_<?php echo $data->id; ?>" type="text" name="<?=$row->user_id?>_mrp[]" maxlength="5" placeholder="MRP">
                                                </td>
                                                <td>
                                                     <input onkeypress="only_number(event)" class="per_unit_price_<?php echo $row->user_id ?> form-control" id="per_unit_price_<?php echo $data->id; ?>" type="text" name="<?=$row->user_id?>_per_unit_price[]" maxlength="5" placeholder="Per Unit Price" oninput="return amount('<?php echo $data->id; ?>','<?=$row->user_id?>')">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="<?php echo $data->id ?>" name="<?=$row->user_id?>_quotation_request_detail_id[]">
                                                    <input  class="amount_<?php echo $data->id ?> form-control" onkeypress="only_number(event)" id="amount_<?php echo $data->id; ?>" type="text" name="<?=$row->user_id?>_amount[]" maxlength="5" value="" placeholder="Amount" readonly>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="remove_tr($(this).closest('tr').index(),'<?php echo $data->id; ?>','<?=$row->user_id?>','<?=$data->asset_id; ?>','<?= $sr; ?>')" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                </td>
                                            </tr>


                                       <?php } $sr++; }  ?>
                                       <tr>
                                           <td colspan="3">&nbsp;</td>
                                           <td>Total Qty : <span id="ttlqty<?=$row->user_id?>"><?=$ttlQty; ?></span>
                                            <input type="hidden" id="ttlqtyval<?=$row->user_id?>" value="<?=$ttlQty; ?>" class="ttlqtyval" name="ttlqtyval[]">
                                           </td>
                                           <td colspan="4">&nbsp;</td>
                                       </tr>
                                   </tbody>
                               </table>
                                    </div>                                
                                </div>
                            <?php $no++; } }else{ ?>
                                <div class="panel panel-primary acc" id="acc_<?=$user_id; ?>">
                                           <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a href="javascript:void(0)" onclick="return toggleAcc('<?=$user_id; ?>')">
                                                <span id="userName_<?=$user_id?>"></span>
                                            </a>
                                        </h4>
                                    </div>                                
                                    <div class="panel-body panel-body-open" id="accTwoCol<?=$user_id?>">
                                        <p> No Quantity Available.</p>
                                    </div>

                                </div>


                           <?php } ?>
                           