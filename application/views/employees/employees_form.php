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
            
            <form class="form-horizontal" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <ul class="panel-controls">
                        <li><a href="<?= site_url('Users/index');?>"><span class="fa fa-arrow-left"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">   
                        
                        <div class="col-md-12">
                            <?php if($_SESSION[SESSION_NAME]['type']=='Admin') { ?>  
                              <div class="col-md-6">
                                <div class="form-group" id="refresh">                                        
                                    <label class="col-md-10">Branch <span  style="color:red;">*</span> <span id="errbranch_id" style="color:red"></span><span class="pull-right"> <a href="#myModalBranch" data-toggle="modal" data-target="" title="Add new Branch" >(<i class="fa fa-plus"></i>)</a></span> <span id="successEntry" style="color:green"></span></label>
                                    <div class="col-md-10">
                                            <select class="form-control select"  name="branch_id" id="branch_id" data-live-search="true" onchange="get_designation_by_branch(this.value)">
                                              <option value="0">Select Branch</option>
                                              <?php 
                                                 foreach($branch as $row) { ?>
                                                   <option value="<?php echo $row->id ?>"<?php if($branch_id == $row->id){ echo "selected"; }?>><?php echo ucfirst($row->branch_title); ?></option>
                                              <?php } ?>
                                            </select>                                            
                                        </div>
                                    </div>
                                </div>
                              <?php } ?>
                              
                              <div class="col-md-6">
                                <div class="form-group" id="refreshDesignation">                                        
                                  <label class="col-md-10">Designation <span style="color:red;">*</span> <span id="errdesignation_id" style="color:red"></span><span class="pull-right"><a href="#myModalDesignation" data-toggle="modal" data-target="" title="Add new Designation">(<i class="fa fa-plus"></i>)</a></span> <span id="successEntryDesignation" style="color:green"></span></label>
                                  <div class="col-md-10">
                                    <select class="form-control select"  name="designation_id" id="designation_id" data-live-search="true">
                                      <option value="">Select Designation</option>
                                      <?php 
                                          foreach($designations as $row) { 
                                          ?>
                                           <option value="<?php echo $row->id ?>"<?php if($designation_id == $row->id){ echo "selected"; }?>><?php echo $row->designation_name; ?></option>
                                      <?php } ?>
                                    </select> 
                                  </div>
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Name <span style="color:red;">*</span> <span id="errname" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="<?php echo $name; ?>"/>
                                    </div> 
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Email <span style="color:red;">*</span> <span id="erremail" style="color:red"></span><span class="form_error" style="color:red"> <?php echo form_error('email') ?></span></label>
                                    <div class="col-md-10">  
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?php echo $email; ?>"/>
                                    </div> 
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Mobile <span style="color:red;">*</span> <span id="errmobile" style="color:red"></span><span class="form_error" style="color:red"> <?php echo form_error('mobile') ?></span></label>
                                    <div class="col-md-10">  
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile" maxlength="11" onkeypress="only_number(event)" value="<?php echo $mobile; ?>"/>
                                    </div>  
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">                                        
                                  <label class="col-md-12">Country <span style="color:red;">*</span> <span id="errcountry_id" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <select class="form-control select" name="country_id" id="country_id"  onchange="getStates(this.value);" data-live-search="true">
                                          <option value="0">Select Country</option>
                                          <?php 
                                             foreach($country as $row) { ?>
                                               <option value="<?php echo $row->id ?>"<?php if($country_id == $row->id){ echo "selected"; }?>><?php echo $row->country_name; ?></option>
                                          <?php } ?>
                                        </select>                                         
                                    </div>
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">                                        
                                  <label class="col-md-12">State <span style="color:red;">*</span> <span id="errstate_id" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <select class="form-control select" name="state_id" id="state_id" onchange="getCities(this.value);" data-live-search="true">
                                          <option value="0">Select State</option>
                                         <?php 
                                            if($button=='Update') {
                                             foreach($state as $row) { ?>
                                               <option value="<?php echo $row->id ?>"<?php if($state_id == $row->id){ echo "selected"; }?>><?php echo $row->state_name; ?></option>
                                          <?php } } ?> 
                                        </select>                                         
                                    </div>
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">                                        
                                  <label class="col-md-12">City <span style="color:red;">*</span> <span id="errcity_id" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <select class="form-control select"  name="city_id" id="city_id" data-live-search="true">
                                          <option value="0">Select City</option>
                                         <?php 
                                            if($button=='Update'){
                                             foreach($city as $row) { ?>
                                               <option value="<?php echo $row->id ?>"<?php if($city_id == $row->id){ echo "selected"; }?>><?php echo $row->city_name; ?></option>
                                          <?php } } ?>
                                        </select>                                          
                                    </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Address <span style="color:red;">*</span> <span id="erraddress" style="color:red"></span></label>
                                    <div class="col-md-10">                                            
                                      <textarea class="form-control" rows="5" name="address" id="address" placeholder="Enter Address"><?php echo $address; ?></textarea>
                                    </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Pincode <span style="color:red;">*</span> <span id="errpincode" style="color:red"></span></label>
                                    <div class="col-md-10">                                            
                                      <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Enter Pincode" maxlength="6" onkeypress="only_number(event)" value="<?php echo $pincode; ?>">
                                    </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Image <!-- <span style="color:red;">*</span> <span id="errorimage" style="color:red"></span><span id="msg" class="error"></span> --></label>
                                    <div class="col-md-10"> 
                                    <input class="form-control" type="file" name="image" id="image" value="<?php echo $image;?>" onclick="imageFile()">
                                      <span style="color:blue"><b>Note :</b>Required only JPEG, JPG, PNG file is allowed.</span> 
                                    <input type="hidden" name="" value="<?php echo $button; ?>" id="btnn">
                                   
                                  <?php if(!empty($image)){ ?>
                                  <div>
                                    
                                  <img  style="height:80px;width:80px;"  src="<?php echo base_url(); ?>/uploads/employee_images/<?php echo $image ?>"><br>   
                                  </div>
                                  <?php } ?>
                                  <span class= "color" id="errorimage">
                                     </span>&nbsp;
                                  <input type = "hidden" name ="oldimage" value="<?php echo $image; ?>" style = "display:none;">
                                  
                              </div>
                              </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Invoice Serial Number Series <span style="color:red;">*</span> <span id="errinvoice" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="invoice_serial_number_series" id="invoice_serial_number_series" placeholder="Enter Invoice Serial Number Series" value="<?php echo $invoice_serial_number_series; ?>"/>
                                    </div> 
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">Dispatch Note Serial Number Series <!-- <span style="color:red;">*</span> <span id="errdispatch" style="color:red"></span> --></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="dispatch_note_serial_number_series" id="dispatch_note_serial_number_series" placeholder="Enter Dispatch Note Serial Number Series" value="<?php echo $dispatch_note_serial_number_series; ?>"/>
                                    </div> 
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="col-md-12">GST Number <span style="color:red;">*</span> <span id="errgst" style="color:red"></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="Enter Name" value="<?php echo $gst_number; ?>"/>
                                    </div> 
                                </div>
                              </div>

                        </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" id="submit" onclick="return validation();"><?= $button;?></button>
                    <a href="<?=site_url('Users/index')?>"  class="btn btn-danger">Cancel</a>
                </div>
            </div>
            </form>
            
        </div>
    </div>                    
    
</div>


<div id="myModalBranch" class="modal fade" role="dialog xs">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Branch</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-success text-center" id="success_message"></div>
            <div class="row">
                <div class="col-md-12">
                  <label>Branch Name <span style="color:red;">*</span><span id="error_branch_title" style="color:red"></span></label>
                  <input type="text" class="form-control" name="branch_title" id="branch_title" placeholder="Branch Name">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="return validation_branch();">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>

<div id="myModalDesignation" class="modal fade" role="dialog xs">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Designation</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-success text-center" id="success_message"></div>
            <div class="row">
                <div class="col-md-12">
                  <label>Designation Name <span style="color:red;">*</span><span id="error_designation_title" style="color:red"></span></label>
                  <input type="text" class="form-control" name="designation_title" id="designation_title" placeholder="Designation Name">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="return validation_designation();">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>


<!-- END PAGE CONTENT WRAPPER -->  
<?php $this->load->view('common/footer'); ?>

<script src="<?= base_url() ?>assets/custom_js/employees.js" type="text/javascript"></script>

<script type="text/javascript">
function validation()
{ 
        var branch_id = $("#branch_id").val(); //alert(branch_id);return false;
        var designation_id = $("#designation_id").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var pattern_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i; 
        var mobile = $("#mobile").val();
        var pattern_mobile = /^[0-9]{10,15}$/i;
        var password = $("#password").val();
        var country_id = $("#country_id").val();
        var state_id = $("#state_id").val();  
        var city_id = $("#city_id").val();
        var address = $("#address").val().trim();
        var pincode = $("#pincode").val();
        var pattern_pincode = /^[0-9]{6}$/i;
        var image = $("#image").val();
        var invoice_serial_number_series = $('#invoice_serial_number_series').val();
        //var dispatch_note_serial_number_series = $('#dispatch_note_serial_number_series').val();
        var gst_number = $('#gst_number').val();
        var btnn= $("#btnn").val();
        
      if(branch_id=="0")
      {
        $("#errbranch_id").fadeIn().html("Please Select Branch");
        setTimeout(function(){$("#errbranch_id").html("&nbsp;");},5000)
        $("#branch_id").focus();
        return false;
      } 

      if(designation_id=="")
      {
        $("#errdesignation_id").fadeIn().html("Please Select Designation");
        setTimeout(function(){$("#errdesignation_id").html("&nbsp;");},5000)
        $("#designation_id").focus();
        return false;
      }
       
      if(name=="")
      {
        $("#errname").fadeIn().html("Please Enter Name");
        setTimeout(function(){$("#errname").html("&nbsp;");},5000)
        $("#name").focus();
        return false;
      }
       
      if(email=="")
      {
        $("#erremail").fadeIn().html("Please Enter Email");
        setTimeout(function(){$("#erremail").html("&nbsp;");},5000)
        $("#email").focus();
        return false;
      }

      else if(!pattern_email.test(email))
      {
        $("#erremail").fadeIn().html("Please Enter Valid Email");
        setTimeout(function(){$("#erremail").html("&nbsp;");},5000)
        $("#email").focus();
        return false;     
      }
         
      if(mobile=="")
      {
        $("#errmobile").fadeIn().html("Please Enter Mobile");
        setTimeout(function(){$("#errmobile").html("&nbsp;");},5000)
        $("#mobile").focus();
        return false;
      }

      else if(!pattern_mobile.test(mobile))
      {
        $("#errmobile").fadeIn().html("Please Enter Valid Mobile");
        setTimeout(function(){$("#errmobile").html("&nbsp;");},5000)
        $("#mobile").focus();
        return false;     
      }
           
      if(password=="")
      {
        $("#errpassword").fadeIn().html("Please Enter Password");
        setTimeout(function(){$("#errpassword").html("&nbsp;");},5000)
        $("#password").focus();
        return false;
      } 

      if(country_id=="0")
      {
        $("#errcountry_id").fadeIn().html("Please Select Country");
        setTimeout(function(){$("#errcountry_id").html("&nbsp;");},5000)
        $("#country_id").focus();
        return false;
      }
           
      if(state_id=="0")
      {
        $("#errstate_id").fadeIn().html("Please Select State");
        setTimeout(function(){$("#errstate_id").html("&nbsp;");},5000)
        $("#state_id").focus();
        return false;
      }
             
      if(city_id=="0")
      {
        $("#errcity_id").fadeIn().html("Please Select City");
        setTimeout(function(){$("#errcity_id").html("&nbsp;");},5000)
        $("#city_id").focus();
        return false;
      }
               
      if(address=="")
      {
        $("#erraddress").fadeIn().html("Please Enter Address");
        setTimeout(function(){$("#erraddress").html("&nbsp;");},5000)
        $("#address").focus();
        return false;
      }
               
      if(pincode=="")
      {
        $("#errpincode").fadeIn().html("Please Enter Pincode");
        setTimeout(function(){$("#errpincode").html("&nbsp;");},5000)
        $("#pincode").focus();
        return false;
      }
                  
      else if(!pattern_pincode.test(pincode))
      {
        $("#errpincode").fadeIn().html("Please Enter Valid Pincode");
        setTimeout(function(){$("#errpincode").html("&nbsp;");},5000)
        $("#pincode").focus();
        return false;
      }

      //if(btnn!='Update') { 
        /*if(image=='')
        {
          $("#errorimage").html("Required").css("color","red");
          setTimeout(function(){$("#errorimage").html('');},3000);
          $("#image").focus();
          var scrollPos = $("#image").offset().top;
          $(window).scrollTop(scrollPos);
          return false;
        }*/
     
        if(image!= "")
        {  
          
          var filetype = image.split("."); 
          ext = filetype[filetype.length-1];
          if(!(ext=='jpg') && !(ext=='png') && !(ext=='JPG') && !(ext=='PNG') && !(ext=='JPEG') && !(ext=='jpeg'))
           {
               $("#errorimage").html("<span style='color:red;'>Please select only JPEG, JPG and PNG type of file</span>").fadeIn();
               setTimeout(function(){$("#errorimage").fadeOut();},6000); 
                var scrollPos = $("#image").offset().top;
                $(window).scrollTop(scrollPos);
               return false; 
           }   
        }

        if(invoice_serial_number_series=="")
        {
          $("#errinvoice").fadeIn().html("Please enter invoice serial number series");
          setTimeout(function(){$("#errinvoice").html("&nbsp;");},5000)
          $("#invoice_serial_number_series").focus();
          return false;
        }
           
        /*if(dispatch_note_serial_number_series=="")
        {
          $("#errdispatch").fadeIn().html("Please enter dispatch note serial number series");
          setTimeout(function(){$("#errdispatch").html("&nbsp;");},5000)
          $("#dispatch_note_serial_number_series").focus();
          return false;
        }*/

        if(gst_number=="")
        {
          $("#errgst").fadeIn().html("Please enter GST number");
          setTimeout(function(){$("#errgst").html("&nbsp;");},5000)
          $("#gst_number").focus();
          return false;
        }

      //}
             
    }
</script>

<script type="text/javascript">
  function imageFile()
  { 
    $('#image').change(function () {  
    var files = this.files;   
    var reader = new FileReader();
    name=this.value;    
    //validation for photo upload type    
    var filetype = name.split(".");
  ext = filetype[filetype.length-1];  //alert(ext);return false;
    if(!(ext=='jpg') && !(ext=='png') && !(ext=='PNG') && !(ext=='jpeg') && !(ext=='img') && !(ext=='JPEG') && !(ext=='JPG'))
    { 
    $("#errorimage").html("Please select proper type like jpg, png, jpeg image");   
    setTimeout(function(){$("#errorimage").html("&nbsp;")},3000);
    $("#image").val("");
    //return false;
    }
    reader.readAsDataURL(files[0]);
    });
  }
  
</script>

<script type="text/javascript">
 function validation_branch()
 {
      var branch_title = $("#branch_title").val();
      var site_url = $("#site_url").val(); 
      if(branch_title=="") 
      {
          $("#error_branch_title").fadeIn().html("Please enter branch name");
          setTimeout(function(){$("#error_branch_title").fadeOut();},3000);
          $("#branch_title").focus();
          return false;
      }
      var site_url = $('#site_url').val();
      var url = site_url+"/Users/savemyBranch";
      var dataString = "branch_title="+branch_title;
      $.post(url,dataString,function(returndata)
      {
        if(returndata==0) 
        {
          $("#error_branch_title").fadeIn().html("Branch already exist");
           setTimeout(function(){$("#error_branch_title").fadeOut();},3000);
          $("#branch_title").focus();
          return false;
        }
        else
        {
          $("#branch_title").val('');
          $("#myModalBranch").modal("hide");
          $('#branch_id').html(returndata).selectpicker('refresh');
          $("#successEntry").fadeIn().html("<span> Designation created successfully</span>");
          setTimeout(function(){$("#successEntry").fadeOut();},3000);
        }
      });
  }
   
</script>

<script type="text/javascript">
 function validation_designation()
 {
      var designation_title = $("#designation_title").val();
      var site_url = $("#site_url").val(); 
      if(designation_title=="") 
      {
          $("#error_designation_title").fadeIn().html("Please enter designation name");
          setTimeout(function(){$("#error_designation_title").fadeOut();},3000);
          $("#designation_title").focus();
          return false;
      }
      var site_url = $('#site_url').val();
      var url = site_url+"/Users/savemyDesignation";
      var dataString = "designation_title="+designation_title;
      $.post(url,dataString,function(returndata)
      {
        if(returndata==0) 
        {
          $("#error_designation_title").fadeIn().html("Designation already exist");
           setTimeout(function(){$("#error_designation_title").fadeOut();},3000);
          $("#designation_title").focus();
          return false;
        }

        else
        {
          $("#designation_title").val('');
          $("#myModalDesignation").modal("hide");
          $('#designation_id').html(returndata).selectpicker('refresh');
          $("#successEntryDesignation").fadeIn().html("<span> Designation created successfully</span>");
          setTimeout(function(){$("#successEntryDesignation").fadeOut();},3000);
        }
      });
  }
   
</script>
<script type="text/javascript">
    function get_designation_by_branch(id)
    { 
      //alert(id);
      var datastring = "id="+id;

      $.ajax({
        type:"post",
        url:"<?php echo site_url('Users/get_designation'); ?>",
        data:datastring,
        success:function(returndata)
        { 
          //alert(returndata);
          $('#designation_id').html(returndata).selectpicker('refresh');
        }
      });
    }
</script>