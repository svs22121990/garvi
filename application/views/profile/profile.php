        <ul class="breadcrumb">
            <li class="active"><i class="fa fa-user"></i> <?php echo $heading?></li>
        </ul>
        
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">   
                            <h2 class="panel-title"><b><?php echo $heading?></b></h2>                               
                        </div>
                        <div class="panel-body">
                            <form  id="formCreate" enctype="multipart/form-data" method="post" class="form-horizontal" action="<?php echo site_url('Welcome/update_action'); ?>">
                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12"><b>Profile Image</b></label>
                                            <center><?php if($loginInfo->image==''){ ?>
                                                <img src="<?= base_url('uploads/profile/profile_default.png')?>" height="100px">
                                            <?php } else {?>
                                               <img src="<?= base_url('uploads/employee_images/'.$loginInfo->image)?>" height="150px" width="180px;">
                                           <?php } ?>
                                           <div class="col-md-12">&nbsp;</div>
                                           <input type="file" class="form-control" name="image" id="driver_image" style="width:60%"/>
                                           <input type="hidden" class="form-control" name="old_image"  value="<?= $loginInfo->image;?>"/>
                                           <span style="color: blue"><b>Note:Image should be jpg,png,jpeg type only.</b></span><br>
                                           <span class="text-danger" id="error_driver_image" value="<?= $loginInfo->image; ?>" ></span> </center>  
                                       </div>
                                   </div>
                               </div>
                               <div class="col-md-8">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2"><b>Name</b><span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input type="text" id="first_name" class="form-control" name="name" placeholder="Name" value="<?= ucfirst($loginInfo->name); ?>"/>
                                            <span class="text-danger" id="error_first_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">&nbsp;</div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2"><b>Email</b><span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?= $loginInfo->email; ?>"/>
                                            <span class="text-danger" id="error_email"></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">&nbsp;</div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2"><b>Mobile No.</b><span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input type="text" name="mobile" id="mobile_number" class="form-control" placeholder="Mobile No." maxlength="10" onkeypress="return isNumberKey(event);" value="<?= $loginInfo->mobile; ?>"/>
                                            <span class="text-danger" id="error_mobile_number"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">&nbsp;</div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2"><b>Address</b><span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="address" id="address" style="resize: none;"/><?= $loginInfo->address; ?></textarea>
                                            <span class="text-danger" id="error_address"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">&nbsp;</div>
                            </div>


                            <input type="hidden" name="designation_id" value="<?php echo $_SESSION[SESSION_NAME]['designation_id'] ?>">

                            <div class="col-md-12">&nbsp;</div>

                            <div class="col-md-12">
                              <div class="text-center">
                                <button class="btn btn-info" type="submit" style="display:none;" id="submit" >Save</button>
                                <button class="btn btn-info" type="submit" name="submit" onclick="return validations()">Save</button>
                                <a href="<?=site_url('Dashboard');?>" class="btn btn-danger">Cancel</a>
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
        var first_name=$('#first_name').val();
        var email = $('#email').val();
        var mobile_number = $('#mobile_number').val();
        var address = $('#address').val();
        var image = $('#driver_image').val();

        if (first_name=='') 
        {
            $("#error_first_name").fadeIn().html("Required");
            $("#first_name").css("border-color","red");
            setTimeout(function(){$("#error_first_name").html("&nbsp;");$("#first_name").css("borderColor","#00A654")},5000)
            $("#first_name").focus();
            return false;
        }

        if (mobile_number=='') 
        {
            $("#error_mobile_number").fadeIn().html("Required");
            $("#mobile_number").css("border-color","red");
            setTimeout(function(){$("#error_mobile_number").html("&nbsp;");$("#mobile_number").css("borderColor","#00A654")},5000)
            $("#mobile_number").focus();
            return false;
        }

        if (email=='') 
        {
            $("#error_email").fadeIn().html("Required");
            $("#email").css("border-color","red");
            setTimeout(function(){$("#error_email").html("&nbsp;");$("#email").css("borderColor","#00A654")},5000)
            $("#email").focus();
            return false;
        }

        if (address=='') 
        {
            $("#error_address").fadeIn().html("Required");
            $("#address").css("border-color","red");
            setTimeout(function(){$("#error_address").html("&nbsp;");$("#address").css("borderColor","#00A654")},5000)
            $("#address").focus();
            return false;
        }

        var filetype = image.split(".");
        ext = filetype[filetype.length-1];  
        if($.trim(image)!="")
        {     
            if(!(ext=='jpg')  && !(ext=='JPG') && !(ext=='jpeg') && !(ext=='JPEG') && !(ext=='png') && !(ext=='PNG'))
            {   
                $("#error_driver_image").fadeIn().html("Please upload image of type jpg,png,jpeg");
                $("#driver_image").css("border-color","red");
                setTimeout(function(){$("#error_driver_image").html("&nbsp;");$("#driver_image").css("borderColor","#00A654")},5000)
                $("#driver_image").focus();
                return false;
            }
        }
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