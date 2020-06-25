<?php
if(empty($_SESSION[SESSION_NAME]['id']))
{ 
    redirect('Welcome');
}
/*$allow_ips = $this->Crud_model->GetData("mst_allow_ips",'',"status='active' and ip_address='".$_SERVER['REMOTE_ADDR']."'",'','','','single');
if(empty($allow_ips))
{ 
    redirect('Welcome');
}*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <!-- META SECTION -->
        <title>Billing & Inventory Application || Admin</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="<?=base_url(); ?>assets/favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        <style type="text/css">
          #my-modal {display:block;}
         
      </style>
        <noscript>
          <div class="modal fade in" id="my-modal" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Disable Javascript</h4>
                </div>
                <div class="modal-body">
                  <p>Your systems javascript file is disable.<!--  Please visit this site&nbsp;(ctrl + &nbsp;<a target="_blank" href="about:config">Click here</a>). <br/>Enable your javascript file. --></p>
                </div>
              </div>
            </div>
          </div>

          
      </noscript>
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?=base_url(); ?>assets/css/theme-default.css"/>
		<link href="<?= base_url(); ?>assets/select2/css/select2.min.css" rel="stylesheet" />
		<!-- date range picker-->
		<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/date_r_picker/daterangepicker.css" />
        <!-- EOF CSS INCLUDE -->  
        <style type="text/css">
            .loader 
            {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('<?php echo base_url('assets/img/loaders/default.gif')?>') 50% 50% no-repeat rgba(0, 0, 0, 0.22);
            }
        </style> 
        <script type="text/javascript">
            var url="";
            var actioncolumn="";
        </script>                                 
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="loader"></div> 

        <div class="page-container">
            
            


            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
            