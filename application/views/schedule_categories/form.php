<?php $this->load->view('common/header'); ?>
<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>
<!-- START BREADCRUMB -->
<?php echo $breadcrumbs; ?>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="post" action="<?php echo $action; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Schedule_categories/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                           
                        <div class="col-md-12">
                             <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Schedule  Date<span style="color:red;">*</span> <span id="errdate" style="color:red"></span></label>
                                  <div class="col-md-10">
                                     <input type="text" readonly="" name="date" id="date" class="form-control date" placeholder="Schedule date">     
                                    </div>
                                  </div>
                              </div> 
                              <div class="col-md-4">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Branch<span style="color:red;">*</span> <span id="errbranch_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="branch_id" id="branch_id" data-live-search="true" onchange="get_employee(this.value)">
                                            <option value="0">--Select Branch--</option>
                                            <?php 
                                               foreach($branches as $row) { ?>
                                                 <option value="<?php echo $row->id ?>" <?php if($branch_id == $row->id){ echo "selected"; }?>><?php echo $row->branch_title; ?></option>
                                            <?php } ?>
                                          </select>      
                                          
                                      </div>
                                  </div>
                              </div>
                              
                              <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Employee <span style="color:red;">*</span> <span id="erremployee_id" style="color:red"></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="employee_id" id="employee_id" data-live-search="true" onchange="return get_category(this.value)">
                                            <option value="0">--Select Employee--</option>
                                         
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>   
                              <div class="col-md-4">
                              <div class="form-group">                                        
                                  <label class="col-md-12">Category <span style="color:red;">*</span> <span id="errcategory_id" style="color:red"></span><span class="msghide" > <?php echo form_error('category_id') ?></span></label>
                                  <div class="col-md-10">
                                          <select class="form-control select"  name="category_id" id="category_id" data-live-search="true" >
                                            <option value="0">--Select Category--</option>
                                         
                                          </select>  
                                                                                    
                                      </div>
                                  </div>
                              </div>
                     <div class="clearfix">&nbsp;</div>
                   </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                    <button  type="button" onclick="window.history.back()"  class="btn btn-danger">Cancel</button>
                </div>
            </div>
            <input type="hidden" name="btnType" id="btnType" value="<?php echo $button; ?>">
            </form>
            
        </div>
    </div>                    
    
</div>
<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
  function get_employee(branch_id)
  {
      var branch_id = $("#branch_id").val();  
      if(branch_id !='0')
      {
          $('.loader').fadeIn();             
          var datastring = "branch_id="+branch_id;                
          $.ajax({
              type: "post",
              url: "<?php echo site_url('Schedule_categories/get_employee'); ?>",
              data: datastring,
              success: function(returndata) {   
              //alert(returndata);                    
                  $('#employee_id').html(returndata).selectpicker('refresh');   
                  $('#category_id').val('');   
                  $('.loader').fadeOut();
              }
          });
      }
 }
 function get_category(employee_id)
  {
      var employee_id = $("#employee_id").val();  
      if(employee_id !='0')
      {
          $('.loader').fadeIn();             
          var datastring = "employee_id="+employee_id;                
          $.ajax({
              type: "post",
              url: "<?php echo site_url('Schedule_categories/get_category'); ?>",
              data: datastring,
              success: function(returndata) {   
              //alert(returndata);                    
                  $('#category_id').html(returndata).selectpicker('refresh');   
                  $('.loader').fadeOut();
              }
          });
      }
 }

  
</script>

<script type="text/javascript">

function validation()
{       
      var date = $("#date").val(); 
      var branch_id = $("#branch_id").val(); 
      var employee_id = $("#employee_id").val(); 
      var category_id = $('#category_id').val();
       
        
       if(date=="")
      {
        $("#errdate").fadeIn().html("Please Select date");
        setTimeout(function(){$("#errdate").html("&nbsp;");},5000)
        $("#date").focus();
        return false;
      }  

      if(branch_id=="0")
      {
        $("#errbranch_id").fadeIn().html("Please Select Branch");
        setTimeout(function(){$("#errbranch_id").html("&nbsp;");},5000)
        $("#branch_id").focus();
        return false;
      } 


      if(employee_id=="0")
      {
        $("#erremployee_id").fadeIn().html("Please Select employee");
        setTimeout(function(){$("#erremployee_id").html("&nbsp;");},5000)
        $("#employee_id").focus();
        return false;
      } 

      if(category_id=="0")
      {
        $("#errcategory_id").fadeIn().html("Please Select Category");
        setTimeout(function(){$("#errcategory_id").html("&nbsp;");},5000)
        $("#category_id").focus();
        return false;
      } 
  }
</script>




