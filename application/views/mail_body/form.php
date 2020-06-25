 <?php 
 $this->load->view('common/header');
 $this->load->view('common/left_panel');
 ?>
 <!-- START BREADCRUMB -->
 <?= $breadcrumbs ?>
 <!-- END BREADCRUMB -->
 <!-- PAGE TITLE -->
 <div class="page-title">                    
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" method="post" action="<?= $action; ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong><?= $heading ?></h3>
                            <ul class="panel-controls">
                               <li><a href="<?= site_url('Mail_body/index')?>" ><span class="fa fa-arrow-left"></span></a></li>
                           </ul>
                       </div>
                       <div class="panel-body">
                        <div class="row">

                            <div class="col-md-12">

                                  <div class="form-group">
                                    <label class="col-md-12">Type <span id="type_error" style="color: red"></span></label>
                                    <div class="col-md-12"> 
                                      <input type="text" class="form-control" name="type" id="type" value="<?= $type; ?>" readonly/>
                                    </div>
                                  </div> 

                                  <div class="form-group">
                                    <label class="col-md-12">Mail Subject<span style="color: red">*</span>&nbsp;<span id="mail_subject_error" style="color: red"></span></label>
                                    <div class="col-md-12">  
                                        <input type="text" class="form-control" name="mail_subject" id="mail_subject" value="<?= $mail_subject; ?>" />                   

                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-md-12">Mail Body<span style="color: red">*</span>&nbsp;<span id="mail_body_error" style="color: red"></span></label>
                                    <div class="col-xs-12">                                            
                                        <textarea class="form-control class="summernote"" rows="5" name="mail_body" id="mail_body"><?= $mail_body; ?></textarea>
                                        
                                    </div>
                                  </div>

                            </div>                                        
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button class="btn btn-success " type="submit" onclick="return validateinfo()"><?= $button;?></button>
                        <a class="btn btn-danger " href="<?=site_url('Mail_body');?>">Cancel</a>
                    </div>
                </div>
            </form>

        </div>
    </div>                    

</div>
<!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('common/footer');?>

<script type="text/javascript" src="<?php echo base_url()?>assets/ckeditor/ckeditor.js"></script>   
    <script type="text/javascript">
         CKEDITOR.replace('mail_body');
         
    </script>
<script type="text/javascript">
    function validateinfo() 
    {  
        var type = $("#type").val();
        var mail_subject = $("#mail_subject").val();
        var mail_body = CKEDITOR.instances.mail_body.getData();                           

        if(type=='')
        {
           $("#type_error").html("Required").fadeIn();
           setTimeout(function(){$("#type_error").fadeOut()},5000);          
           return false;
       }
       if(mail_subject=='')
       {
           $("#mail_subject_error").html("Required").fadeIn();
           setTimeout(function(){$("#mail_body_error").fadeOut()},5000);          
           return false;
       }
       if(mail_body=='')
       {
           $("#mail_body_error").html("Required").fadeIn();
           setTimeout(function(){$("#mail_body_error").fadeOut()},5000);          
           return false;
       }
   }
</script>