<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Taxi Service</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?= base_url()?>assets/css/theme-default.css"/>
        <link rel="stylesheet" type="text/css" id="theme" href="<?= base_url()?>assets/css/login.css"/>
        <!-- EOF CSS INCLUDE -->                                    
    </head>
    <body>
        
        <!-- <div class="login-container"  style="background:url('<?= base_url('assets/images/Cab-Booking-App-features.jpg')?>');background-size: cover;background-position: center center;)"> -->
            <div class="login-container" style="background:url('<?= base_url('assets/images/taxi.jpg')?>');background-size: cover;background-position: center center;)">
        
            <div class="login-box animated fadeInDown pull-right" style="margin-right:4%">
              
                <div class="login-body col-md-12"  style="background: rgba(255,255,255,0.4);box-shadow: 5px 6px #a4736b;">
                    <div class="login-title text-center" style="color:#000;"><strong>Forgot Password</strong></div>
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-11">
                        <span class="error" style="margin: auto;color:red"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
                    </div>
                   
                    <form action="<?= $action?>" class="form-horizontal" method="post">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-1"><i class="fa fa-envelope fa-2x" style="color:#F3F2F2;"></i></div>
                            <div class="col-md-11">
                                <input type="text" name="email" class="form-control" placeholder="E-mail" style="color:#fff;font-weight: bold;" id="email" autocomplete="off" value="<?= $email?>"/>
                            </div>
                        </div>
                       <div class="col-md-12">&nbsp;</div>
                        <div class="form-group">
                             <div class="col-md-1">&nbsp;</div>
                             <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info btn-block" onclick="return validateLogin()">Submit</button>
                            </div>
                            <div class="col-md-5">
                                 <a href="<?= site_url('Login')?>" class="btn btn-danger btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            
        </div>
        <input type="hidden" name="site_url" id="site_url" value="<?= site_url()?>">
    </body>
</html>
<script type="text/javascript" src="<?= base_url()?>assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
    function validateLogin()
    {
        var email=$("#email").val();
        var pattern_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        
        if(email=='')
        {
             $(".error").html("Required").css('color','red').fadeIn();
            setTimeout(function(){$(".error").html("&nbsp;");},5000);
            $("#email").focus();
            return false;
        }
        else if(!pattern_email.test(email)) 
        {
            $(".error").html("Invalid email").css('color','red').fadeIn();
            setTimeout(function(){$(".error").html("&nbsp;");},5000);
            $("#email").focus();
            return false;
        }
    }
 setTimeout(function(){$(".error").html("&nbsp;");},5000);
</script>





