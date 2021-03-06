<?php $this->load->view('common/header');
//print_r($_SESSION);exit;
 ?>

<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>                    

<!-- START BREADCRUMB -->
<ul class='breadcrumb'>
	<li>
		<i class='fa fa-home'></i>
		<a href='".site_url('Dashboard')."'>Dashboard</a>
	</li>
	<li class='active'> Manager Users</li></ul>
<!-- END BREADCRUMB --> 

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
	<div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-default">

            	<div class="panel-heading">
                	<h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                	<h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                	<h3><span id="successStateEntry"></span></h3>
                	<ul class="panel-controls">
                  		<?php if($addPermission=='1'){?>
                    	<li><a href="<?php echo site_url('Users/create'); ?>" title="Add User"><span class="fa fa-plus"></span></a></li>
                  		<?php }?>
                    	<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                	</ul>  
            	</div>

            	<div class="panel-body ">

	                <div class="table-responsive">
	                    <table class="table table-bordered table-striped table-actions example_datatable ">
	                        <thead>
	                            <tr>
	                                <th>Sr no.</th>
	                                <th>Name</th>
	                                <th>Invoice Serial Number Series</th>
	                                <th>Dispatch Note Serial Number Series</th>
	                                <th>GST Number</th>
	                                <th>Status</th>
	                                <th>Actions</th>
	                            </tr>
	                        </thead>
	                    </table>
	                </div>

	            </div>
	        </div>                                                
	    </div>
	</div>                   
</div>
<!-- END PAGE CONTENT WRAPPER -->

<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Users/changeStatus') ?>">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 

<div class="modal fade" id="deleteData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">   
            <form method="post" action="<?= site_url('Users/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                          If you want to delete this record,all associated records will be deleted permanently from the Database. 
                        <br>Are you sure? </span>
                    </center>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>
<?php $this->load->view('common/footer'); ?>