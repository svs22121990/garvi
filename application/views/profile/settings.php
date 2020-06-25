        <ul class="breadcrumb">
            <li class="active"><a href="<?= site_url('Dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp;Dashboard</a></li>
            <li class="active"><?php echo $heading?></li>
        </ul>
        
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">   
                            <h2 class="panel-title"><b><?php echo $heading?></b></h2>                               
                        </div>
                        <div class="panel-body">
                            <form  id="formCreate" enctype="multipart/form-data" method="post" class="form-horizontal" action="<?php echo site_url('Settings/update_action'); ?>">
                                 <input type="hidden" name="id" value="<?php if(!empty($Settings)){ echo $Settings->id; } ?>">
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-12">
                                                <label class="col-md-1">&nbsp;</label>
                                                    <b>Logo</b>
                                                </label>
                                                <center><?php if(!empty($Settings)){ if($Settings->logo==''){ ?>
                                                   
                                                    <img src="<?= base_url('assets/images/profile_default.png')?>" height="100px">
                                                <?php } else {?>
                                                     <img src="<?= base_url('assets/images/logo/'.$Settings->logo)?>" height="80px" width="100px;">
                                                <?php } ?>
                                                <input type="hidden" class="form-control" name="old_image"  value="<?php if(!empty($Settings)){ echo $Settings->logo; } ?>"/>
                                                <?php } else{?>
                                                <input type="hidden" class="form-control" name="old_image" id="old_image" style="width:60%"/>
                                                    <?php } ?>
                                                <div class="col-md-12">&nbsp;</div>
                                                    <input type="file" class="form-control" name="image" id="image" style="width:60%"/>
                                                    <span style="color: blue"><b>Note:Image should be jpg,png,jpeg type only.</b></span><br>
                                                   
                                                    <span class="text-danger" id="error_image"></span></center>  
                                            </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <!-- <div class="col-md-12">
                                        
                                    </div> -->
                                </div>
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Title</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <input type="text" id="title" class="form-control" name="title" placeholder="Title" value="<?php if(!empty($Settings)){ echo ucfirst($Settings->title); } ?>"/>
                                                <span class="text-danger" id="error_title"></span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Email 1</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email" name="email1" id="email" value="<?php if(!empty($Settings)){ echo $Settings->email1; } ?>"/>
                                                <span class="text-danger" id="error_email"></span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>
                                     <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Email 2</b></span></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Email" name="email2" id="email2" value="<?php if(!empty($Settings)){ echo $Settings->email2;} ?>"/>
                                                <span class="text-danger" id="error_email2"></span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Mobile 1</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="mobile" id="mobile_number" class="form-control" placeholder="Mobile No." maxlength="10" onkeypress="return isNumberKey(event);" value="<?php if(!empty($Settings)){ echo $Settings->mobile1; }  ?>"/>
                                                <span class="text-danger" id="error_mobile_number"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Mobile 2.</b><span class="text-danger" id="error_mobile_number"></span></span></label>
                                            <div class="col-md-9">
                                                <input type="text" name="mobile2" id="mobile_number2" class="form-control" placeholder="Mobile No." maxlength="10" onkeypress="return isNumberKey(event);" value="<?php if(!empty($Settings)){ echo $Settings->mobile2; } ?>"/>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Date Format</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="date_format" id="date_format">
                                                    <option value="">Select Date Format</option>
                                                    <option value="d-m-Y"<?php if($Settings->date_format =='d-m-Y'){ echo 'selected'; }?>>d-m-Y</option>
                                                    <option value="Y-m-d"<?php if($Settings->date_format =='Y-m-d'){ echo 'selected'; }?>>Y-m-d</option>    
                                                </select> 
                                                <span class="text-danger" id="error_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Time Format</b><span class="text-danger">*</span></span></label>
                                            <div class="col-md-9">
                                               <select class="form-control" name="time_format" id="time_format">
                                                    <option value="">Select Time Format</option>
                                                    <option value="H:i"<?php if($Settings->time_format == 'H:i') { echo 'selected';}?>>H:i</option>
                                                    <option value="H:i A"<?php if($Settings->time_format == 'H:i A') { echo 'selected';}?>>H:i A</option>
                                                </select>  
                                                <span class="text-danger" id="error_time"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Distance Unit</b><span class="text-danger">*</span></span></label>
                                            <div class="col-md-9">
                                               <select class="form-control" name="distance_unit" id="distance_unit">
                                                    <option value="">Select Distance Unit</option>
                                                    <option value="Km" <?php if($Settings->distance_unit == 'Km') { echo 'selected';} ?>>Km</option>
                                                    <option value="Miles"<?php if($Settings->distance_unit == 'Miles'){ echo 'selected'; } ?>>Miles</option>
                                                </select>  
                                                <span class="text-danger" id="error_distance"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Currency</b><span class="text-danger">*</span></span></label>
                                            <div class="col-md-9">
                                               <select class="form-control" name="currency" id="currency">
                                                    <option value="">Select Currency</option>
                                                    <option value="Rs"<?php if($Settings->currency == 'Rs'){ echo 'selected'; } ?>>Rs</option>
                                                    <option value="$J"<?php if($Settings->currency == '$J'){ echo 'selected'; } ?>>$J</option>
                                                </select>
                                                <span class="text-danger" id="error_currency"></span>                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Address</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="address" id="address" style="resize: none;"/> <?php if(!empty($Settings)){ echo trim($Settings->address); } ?></textarea>
                                                <span class="text-danger" id="error_address"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><span class="pull-right"><b>Footer</b><span class="text-danger"> *</span></span></label>
                                            <div class="col-md-9">
                                                <input class="form-control" name="footer" id="footer" style="resize: none;" value="<?php if(!empty($Settings)){ echo trim($Settings->footer); } ?>"/> 
                                                <span class="text-danger" id="error_footer"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><b>Android Icon</b><span class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input type="file" class="form-control" name="android_icon" id="android_icon" />
                                                <input type="hidden" class="form-control" name="old_android" value="<?= $Settings->android_icon;?>"/>
                                                <img src="<?= base_url('assets/images/logo/'.$Settings->android_icon)?>" height="50px" width="50px">
                                                <span style="color: blue"><b>Note:Image should be jpg,png,jpeg type only.</b></span><br>
                                                <span class="text-danger" id="error_android_icon" value="<?= $Settings->android_icon; ?>" ></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">&nbsp;</div>
                                </div>

                                <div class="col-md-12">&nbsp;</div>

                                <div class="col-md-12">
                                  <div class="text-center">
                                            <button class="btn btn-info pull-right" type="submit" style="display:none;" id="submit" >Save</button>
                                            <button class="btn btn-info" type="submit" name="submit" onclick="return validations()">Save</button>
                                            
                                   </div>
                                </div>

                            </form>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
                                       
    </div>            
</div>
<script>

    function validations()
    { 
        var title=$('#title').val();
        var email = $('#email').val();
        var email2 = $('#email2').val();
        var mobile_number = $('#mobile_number').val();
        var date_format = $('#date_format').val();
        var time_format = $('#time_format').val();
        var distance_unit = $('#distance_unit').val();
        var currency = $('#currency').val();
        var address = $('#address').val();
        var footer = $('#footer').val();
        var image = $('#image').val();

        var pattern_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;


         var filetype = image.split(".");
          ext = filetype[filetype.length-1];  
           if($.trim(image)!="")
          {     
             if(!(ext=='jpg')  && !(ext=='JPG') && !(ext=='jpeg') && !(ext=='JPEG') && !(ext=='png') && !(ext=='PNG') && !(ext=='img') && !(ext=='IMG'))
             {   
                $("#error_image").fadeIn().html("Please upload image of type jpg,png,jpeg");
                $("#image").css("border-color","red");
                setTimeout(function(){$("#error_image").html("&nbsp;");$("#image").css("borderColor","#00A654")},5000)
                $("#image").focus();
                return false;
             }
          }

        if(title=='') 
        {
            $("#error_title").fadeIn().html("Required");
            $("#title").css("border-color","red");
            setTimeout(function(){$("#error_title").html("&nbsp;");$("#title").css("borderColor","#00A654")},5000)
            $("#title").focus();
            return false;
        }
        if(email=='') 
        {
            $("#error_email").fadeIn().html("Required");
            $("#email").css("border-color","red");
            setTimeout(function(){$("#error_email").html("&nbsp;");$("#email").css("borderColor","#00A654")},5000)
            $("#email").focus();
            return false;
        }

        else if(!pattern_email.test(email.trim()))
        {
            $("#error_email").fadeIn().html("Invalid");
            $("#email").css("border-color","red");
            setTimeout(function(){$("#error_email").html("&nbsp;");$("#email").css("borderColor","#00A654")},5000)
            $("#email").focus();
            return false;
        } 
        if(email2 != "")
        {
            if(!pattern_email.test(email2.trim()))
            {
                $("#error_email2").fadeIn().html("Invalid");
                $("#email2").css("border-color","red");
                setTimeout(function(){$("#error_email2").html("&nbsp;");$("#email").css("borderColor","#00A654")},5000)
                $("#email2").focus();
                return false;
            }
        }
        if(mobile_number=='') 
        {
            $("#error_mobile_number").fadeIn().html("Required");
            $("#mobile_number").css("border-color","red");
            setTimeout(function(){$("#error_mobile_number").html("&nbsp;");$("#mobile_number").css("borderColor","#00A654")},5000)
            $("#mobile_number").focus();
            return false;
        }
        if(date_format=='') 
        {
            $("#error_date").fadeIn().html("Required");
            $("#date_format").css("border-color","red");
            setTimeout(function(){$("#error_date").html("&nbsp;");$("#date_format").css("borderColor","#00A654")},5000)
            $("#date_format").focus();
            return false;
        }
         if(time_format=='') 
        {
            $("#error_time").fadeIn().html("Required");
            $("#time_format").css("border-color","red");
            setTimeout(function(){$("#error_time").html("&nbsp;");$("#time_format").css("borderColor","#00A654")},5000)
            $("#time_format").focus();
            return false;
        }
        if(distance_unit=='') 
        {
            $("#error_distance").fadeIn().html("Required");
            $("#distance_unit").css("border-color","red");
            setTimeout(function(){$("#error_distance").html("&nbsp;");$("#distance_unit").css("borderColor","#00A654")},5000)
            $("#distance_unit").focus();
            return false;
        }
        if(currency=='') 
        {
            $("#error_currency").fadeIn().html("Required");
            $("#currency").css("border-color","red");
            setTimeout(function(){$("#error_currency").html("&nbsp;");$("#currency").css("borderColor","#00A654")},5000)
            $("#currency").focus();
            return false;
        }
        if(address=='') 
        {
            $("#error_address").fadeIn().html("Required");
            $("#address").css("border-color","red");
            setTimeout(function(){$("#error_address").html("&nbsp;");$("#address").css("borderColor","#00A654")},5000)
            $("#address").focus();
            return false;
        }

        if(footer=='') 
        {
            $("#error_footer").fadeIn().html("Required");
            $("#footer").css("border-color","red");
            setTimeout(function(){$("#error_footer").html("&nbsp;");$("#footer").css("borderColor","#00A654")},5000)
            $("#footer").focus();
            return false;
        }

       


       /* var filetype = image.split(".");
        ext = filetype[filetype.length-1];  
        if($.trim(image)!="")
        {     
            if(!(ext=='jpg') && !(ext=='JPG') && !(ext=='jpeg') && !(ext=='JPEG') && !(ext=='png') && !(ext=='PNG'))
            {  
                $("#error_driver_image").fadeIn().html("Please upload image of type jpg,png,jpeg");
                $("#image").css("border-color","red");
                setTimeout(function(){$("#error_driver_image").html("&nbsp;");$("#image").css("borderColor","#00A654")},5000)
                $("#image").focus();
                return false;
            }
        }*/
        
    }


//ONLY NUMBER IN MOBILE FIELD
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 45 || charCode > 57))
    return false;
    return true;
}
function goBack() 
{
    window.history.back();
}

</script>