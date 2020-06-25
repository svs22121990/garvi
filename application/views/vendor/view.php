 <?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<style type="text/css">
    .bordered{
        margin-bottom: 20px;
        margin-top: 20px;
        border:1px solid #eee;
        width: 100%;
    }
</style>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
 <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                          <?php $this->load->view('vendor/common') ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading."-<sapn style='color:green'>".$shop_name.'</span>' ?></h3>
                                    <ul class="panel-controls">
                                       <li><a href="<?=site_url('Vendors/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                               
                                <div class="panel-body">                                                                        
                                   <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Personal Details</h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Firm Name  </label>
                                                    <div class="col-md-6 col-xs-6"><?php echo $shop_name; ?> </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Name </label>
                                                    <div class="col-md-6 col-xs-6"> <?php if(!empty($name)){  echo $name;}else{ echo "N/A"; }  ?>   </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Email     </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($email)){  echo $email;}else{ echo "N/A"; }  ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Mobile </label>
                                                    <div class="col-md-6 col-xs-6" ><?php if(!empty($mobile)){  echo $mobile;}else{ echo "N/A"; }  ?></div>
                                                </div> 
                                            </div>

                                        </div>
                                       <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Address Details</h4>
                                           
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6"> Contry </label>
                                                    <div class="col-md-6 col-xs-6">   <?php if(!empty($country)){  echo $country;}else{ echo "N/A"; }  ?> </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">State</label>
                                                    <div class="col-md-6 col-xs-6"> <?php  if(!empty($state)){ echo $state;}else{ echo "N/A"; }  ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">City</label>
                                                     <div class="col-md-6 col-xs-6">   <?php if(!empty($city)){  echo $city;}else{ echo "N/A"; }  ?> </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Pincode                                              
                                                    </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($pincode)){  echo $pincode;}else{ echo "N/A"; }  ?></div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Address
                                                    </label>
                                                    <div class="col-md-6 col-xs-6"> <?php  if(!empty($address)){ echo $address;}else{ echo "N/A"; }  ?></div>
                                                 </div>
                                             </div>
                                             
                                        </div>
                                       <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Vendor's Account Details</h4>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">GST No.     
                                                    </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($gst_no)){ echo  $gst_no;}else{ echo "N/A"; } ?> </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                         <span class="" ></span>
                                                    <label class="col-md-6 col-xs-6">Account Type </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($account_type)){ echo $account_type; }else{ echo "N/A"; } ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Account Holder Name  </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($bank_account_name)){ echo $bank_account_name; }else{ echo "N/A"; } ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Bank Account Number</label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($bank_account_no)){ echo  $bank_account_no; }else{ echo "N/A"; } ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Bank Name      
                                                    </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($bank_name)){ echo $bank_name; }else{ echo "N/A"; } ?> </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Bank IFSC Code      
                                                    </label>
                                                    <div class="col-md-6 col-xs-6"><?php if(!empty($bank_ifsc_code)){ echo $bank_ifsc_code; }else{ echo "N/A"; } ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-6 col-xs-6">Payment Term</label>
                                                    <div class="col-md-6 col-xs-6"> <?php if(!empty($payment_term)){ echo  $payment_term ; }else{ echo "N/A"; }?></div>
                                                </div>
                                            </div>

                                        </div>
                                    <?php
                                     if(count($assetTypeData) > 0)
                                     { ?>
                                        <p class="col-md-12 bordered"></p>
                                        <div class="col-md-12">
                                            <h4 class="text-left">Assign Dealing Assets types</h4>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                <?php $sr=1;
                                                    foreach ($assetTypeData as $row){  ?>
                                                <div class="col-md-3"> <?= $sr.") ".$row->type; ?> </div>
                                                <?php $sr++; } ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                 </div>
                                <div class="panel-footer">
                                    <a class="btn btn-default" href="<?=site_url('Vendors/index');?>">Back</a>                                    
                                  
                                </div>
                            </div>
                            
                        </div>
                    </div>                    
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript">
    var url="";
    var actioncolumn="";
</script>

<?php $this->load->view('common/footer');?>
