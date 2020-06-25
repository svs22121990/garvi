        <ul class="breadcrumb">
            <li class="active"><span class="fa fa-lock"></span>&nbsp;<?php echo $heading?></li>
        </ul>
        
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">   
                            <h2 class="panel-title"><b><?php echo $heading?></b></h2>                               
                        </div>
                        <div class="panel-body">
                               <div class="col-md-12 form-group pull-left">
                                    <span style="color: blue"><b>If you want to change your password. Your session will be expired and you have to login again.<span>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><b>Old Password</b><span class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input type="password" id="old_password" class="form-control" name="old_password" placeholder="Old Password" value="" autocomplete="off" /> 
                                                <span class="text-danger" id="error_old_password"></span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-12">&nbsp;</div>
                                  <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><b>New Password</b><span class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New Password" value="" autocomplete="off" /> 
                                                <span class="text-danger" id="error_new_password"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">&nbsp;</div>
                                  <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2"><b>Confirm Password</b><span class="text-danger"> *</span></label>
                                            <div class="col-md-9">
                                                <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm Password" value="" autocomplete="off" /> 
                                                <span class="text-danger" id="error_confirm_password"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">&nbsp;</div>

                                <div class="col-md-4">
                                  <div class="text-center">
                                            <button class="btn btn-info" type="button" id="submit"  onclick="return validations();">Save</button>
                                            <button onclick="goBack()" class="btn btn-danger" type="button">Cancel</button>
                                   </div>
                                </div>
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
        var old_password=$('#old_password').val();
        var new_password=$('#new_password').val();
        var confirm_password=$('#confirm_password').val();
        
       
        if (old_password=='') 
        {
            $("#error_old_password").fadeIn().html("Required");
            $("#old_password").css("border-color","red");
            setTimeout(function(){$("#error_old_password").html("&nbsp;");$("#old_password").css("borderColor","#00A654")},5000)
            $("#old_password").focus();
            return false;
        }
      
        if (new_password=='') 
        {
            $("#error_new_password").fadeIn().html("Required");
            $("#new_password").css("border-color","red");
            setTimeout(function(){$("#error_new_password").html("&nbsp;");$("#new_password").css("borderColor","#00A654")},5000)
            $("#new_password").focus();
            return false;
        }
       
        if (confirm_password=='') 
        {
            $("#error_confirm_password").fadeIn().html("Required");
            $("#confirm_password").css("border-color","red");
            setTimeout(function(){$("#error_confirm_password").html("&nbsp;");$("#confirm_password").css("borderColor","#00A654")},5000)
            $("#confirm_password").focus();
            return false;
        }

        if(new_password!=confirm_password)
        {
            $("#error_new_password").fadeIn().html("New password and Confirm password should be same");
            $("#new_password").css("border-color","red");
            setTimeout(function(){$("#error_new_password").html("&nbsp;");$("#new_password").css("borderColor","#00A654")},5000)
            $("#new_password").focus();
            return false;

        }       
      
        var site_url = $("#site_url").val();
        var url = site_url+"/Welcome/change_password";
        var datastring = "old_password="+old_password+"&new_password="+new_password+"&confirm_password="+confirm_password;
        $.post(url,datastring,function(returndata)
        { 
        
             if(returndata==1)
            {
                $("#error_old_password").fadeIn().html("Invalid Password");
                $("#old_password").css("border-color","red");
                setTimeout(function(){$("#error_old_password").html("&nbsp;");$("#old_password").css("borderColor","#00A654")},5000)
                $("#old_password").focus();
                return false;
            }
            else
            {
                window.location=site_url+"/Welcome/logout";
            }
        });
        
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