<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Billing & Inventory Application</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="<?=base_url();?>assets/favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?=base_url();?>assets/css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->   
		<style type="text/css">
            .login-container.lightmode{
                /*background: url("<?= base_url(); ?>images/bg.jpg") left top / cover no-repeat;*/
                background: linear-gradient(
                     rgba(20,20,20, .6), 
                     rgba(20,20,20, .6)),
                     url("<?= base_url(); ?>images/bg.jpg") left top / cover no-repeat;

            }
            .login-container .login-box {
                padding-top: 30px;
            }
            @media only screen and (min-width: 600px) {
                .logo-image {
                    max-width: 600px;
                }
            }

        </style>
    </head>
    <body>
        
        <div class="login-container lightmode">
            <h3 class="text-center" style="color: #fff; font-weight: bold;padding-top:70px;">
                <center>
                    <img class="img-responsive logo-image" src="<?php echo base_url(); ?>images/logo.jpg" style="">
                </center>
            </h3>
            <div class="login-box animated fadeInDown" id="login-box" >
                <!-- <div class="login-logo"></div> -->
                
                <div class="login-body" style="background:white;">
                    <div class=""><strong>Log In</strong> to your account</div>
                    <div><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span><span id="errorMsgLogin" style="color:red;">&nbsp;</span></div>
                    <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control"  id="login_email" name="email" style="border:1px solid lightgray"  placeholder="E-mail"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" class="form-control" style="border:1px solid lightgray" id="login_password" name="password" placeholder="Password"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <a href="javascript:void(0)" class="btn btn-link btn-block" style="color:black;">Forgot your password?</a>
                        </div>
                        <div class="col-md-6">
                             <input type="hidden" id="site_url" value="<?= site_url() ?>">
                            <button type="button" class="btn btn-info btn-block" id="signin" onclick="return get_login(this.value);">Log In</button>
                        </div>
                    </div>
                   
                    </form>
                </div>
              
            </div>
            <div class="login-box forgot-box animated fadeInDown" id="forgot-box" style="display: none">
                <div class="login-logo"></div>
                <div class="login-body">
                    <div class="login-title"><strong>Retrieve </strong> Password</div>
                    <div><span id="forgotError" style="color:red;">&nbsp;</span>
                        <span id="scccessError" style="color:green;">&nbsp;</span>
                    </div>
                    <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control"  id="forgotemail" name="email"  placeholder="E-mail"/>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-link btn-block"  id="login-box-btn" class="back-to-login-link">Back to login</button>
                        </div>
                        <div class="col-md-6">
                             <input type="hidden" id="site_url" value="<?= site_url() ?>">
                         
                            <button type="button" class="btn btn-info btn-block"  onclick="checkForgotPassword()">Send Me!</button>
                        </div>
                    </div>
                   
                    </form>
                </div>
              
            </div>
            
        </div>
    </body>
<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
      $(document).keydown(function(e){
        var c = e.which;
        e.stopPropagation();
        if(c==13){
            e.preventDefault();
            $("#signin").trigger("click");
        }
    });

    $("#forgot-box-btn").click(function(){
        $('#login-box').hide();
        $('#forgot-box').show();
    });
     $("#login-box-btn").click(function(){
        $('#forgot-box').hide();
        $('#login-box').show();
    });
</script>
<script>
function get_login()
{
  var email = $.trim($("#login_email").val());
  var password = $.trim($("#login_password").val());
  var site_url = $("#site_url").val();
  var url = site_url+"/Welcome/login";
  var filter = /^[a-z0-9._-]+@[a-z]+.[a-z]{2,5}$/i;

    if(email == "" )
    {
        $("#errorMsgLogin").fadeIn().html("Please enter email");
        $("#login_email").css("border-color", "red");
        setTimeout(function(){$("#errorMsgLogin").html("&nbsp;");$("#login_email").css("border-color", "#ccc");},5000);
        $("#login_email").focus();
        return false;    
    }
    else if(!filter.test(email))
    {
        $("#errorMsgLogin").fadeIn().html("Please enter valid email ");
        $("#login_email").css("border-color", "red");
       setTimeout(function(){$("#errorMsgLogin").html("&nbsp;");$("#login_email").css("border-color", "#ccc");},5000);
        $("#login_email").focus();
        return false;       
    }
    if(password == "" )
    {
        $("#errorMsgLogin").fadeIn().html("Please enter password");
        $("#login_password").css("border-color", "red");
        setTimeout(function(){$("#errorMsgLogin").html("&nbsp;");$("#login_password").css("border-color", "#ccc");},5000);
        $("#login_password").focus();
        return false;    
    }

$.ajax({
    type:"post",
    url:url,
    data:{email:email,password:password},
        cache:false,
        success:function(returndata)
        {
            //alert(returndata);return false;
          
           if(returndata==1){
               $("#errorMsgLogin").fadeIn().html("Invalid login credential!");
               setTimeout(function(){$("#errorMsgLogin").html("&nbsp;");},5000)
               $("#login_password").focus();
               return false;       
           }
           else if(returndata==2){
                var url = site_url+"/Warehouse";                    
                window.location.href =url;     
           }
          else{
                var url = site_url+"/Dashboard/index";                    
                window.location.href =url;
            }

         }
   });
    }
</script>
<script>
function checkForgotPassword(){

    var email = $("#forgotemail").val();
    var pattern_email = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    var site_url = $("#site_url").val();
   // alert(site_url);
    if($.trim(email)=="")
    {
        $("#forgotError").fadeIn().html("Please enter email");
        $("#forgotemail").css("border-color", "red");
        setTimeout(function(){$("#forgotError").html('&nbsp;');$("#forgotemail").css("border-color", "#ccc");},5000);
        $("#forgotemail").focus();          
        return false;         
    }
    else if(!pattern_email.test(email))
    {
        $("#forgotError").fadeIn().html("Please enter valid email");
        $("#forgotemail").css("border-color", "red");
        setTimeout(function(){$("#forgotError").html('&nbsp;');$("#forgotemail").css("border-color", "#ccc");},5000);
        $("#forgotemail").focus();           
        return false; 
    }
    var dataString = "email="+email;
   
    $.ajax({
        type:"post",
        url: site_url+'/Welcome/forgotPass',
        data:dataString,
        cache:false,
        success:function(returndata)
        {
            //alert(returndata);return false;
            if(returndata==0){
                $("#forgotError").fadeIn().html("Entered email is not registered.");
                $("#forgotemail").css("border-color", "red");
                setTimeout(function(){$("#forgotError").html('&nbsp;');$("#forgotemail").css("border-color", "#ccc");},5000);
                $("#forgotemail").focus();        
                return false;
                
            }
            else{
                 
                 $("#forgotemail").val('');
                 $("#scccessError").fadeIn().html("Password has been changed successfully.");
                setTimeout(function(){$("#scccessError").fadeOut();$(".close").click();},5000);            
                return false;
            } 
                   
        }
    });
}
</script>
</html>






