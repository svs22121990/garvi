<?php $this->load->view('common/header'); ?>
<?php $this->load->view('common/left_panel'); ?>
<style>
table {
        display: block;
        overflow-x: auto;
    }

   
</style>
<ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="<?=site_url('Dashboard/index'); ?>">Dashboard</a>
                    </li>
                    <li class="active"><a href="<?=site_url('Vendors/index'); ?>">Manage Vendors</a></li>
                </ul>
<div class="page-content-wrap">
<!-- START RESPONSIVE TABLES -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title">Failed To Inserted Records&nbsp;</h3>
                 <ul class="panel-controls">
                        <li><a href="#"  onclick="window.history.back()" ><span class="fa fa-arrow-left"></span></a></li>
                    </ul>  
            </div>

            <div class="panel-body ">
                        
                                        
                                        <div class="row">
                                            <div class="col-sm-12" class="table-responsive">
                                                <table id="example123"  class="table table-bordered table-striped dataTable" style="width:auto">
                                                    <thead>
                                                        <tr>
                                                            <th>Shop Name</th>                                  
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Mobile</th>
                                                            <th>Address</th>
                                                            <th>State</th>
                                                            <th>City</th>
                                                            <th>Pincode</th>
                                                            <th>GST No.</th>
                                                            <th>Bank Account Number</th>
                                                            <th>Bank Name</th>
                                                            <th>Bank IFSC Code</th>
                                                            <th>Payment Term</th>
                                                            <th>Bank Account Holder name</th>
                                                            <th>Reason</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            <?php
                                                                foreach ($exitsall as $vendor){ //print_r($area);
                                                            ?>
                                                            <tr>
                                                                <td><?= $vendor->shop_name;?></td>
                                                                <td><?= $vendor->name;?></td>
                                                                <td><?= $vendor->email;?></td>
                                                                <td><?= $vendor->mobile;?></td>
                                                                <td><?= $vendor->address;?></td> 
                                                                <td><?= $vendor->state_state_id;?></td> 
                                                                <td><?= $vendor->city_city_id;?></td> 
                                                                <td><?= $vendor->pincode;?></td> 
                                                                <td><?= $vendor->gst_no;?></td> 
                                                                <td><?= $vendor->bank_account_no;?></td> 
                                                                <td><?= $vendor->bank_name;?></td> 
                                                                <td><?= $vendor->bank_ifsc_code?></td> 
                                                                <td><?= $vendor->payment_term;?></td> 
                                                                <td><?= $vendor->bank_account_name;?></td>  
                                                                <td><?= $vendor->type;?></td> 
                                                                           
                                                            </tr>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>                                       
                                    
                    </div>
                </div>
            </div>
        </div>
</div>
 

<script type="text/javascript">
    var url='';
    var actioncolumn='';
</script>
<?php $this->load->view('common/footer'); ?>

