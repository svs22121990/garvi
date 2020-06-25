<?php $this->load->view('common/header');
//print_r($_SESSION);exit;
 ?>

<!-- START X-NAVIGATION -->
<?php $this->load->view('common/left_panel'); ?>                    

<!-- START BREADCRUMB -->
<?= $breadcrumbs; ?>
<!-- END BREADCRUMB -->                       

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">

    <div class="col-md-12">
                                  
        <div class="panel panel-default">                                
            <div class="panel-body">
                <h2> <?php echo $heading; ?></h2>
                 <ul class="panel-controls">
                    <li><a onclick="return window.history.back();" ><span class="fa fa-arrow-left"></span></a></li>
                </ul>
                 <div class="gallery" id="links">
                        <?php  if(!empty($asset_multiple_images)) {  
                            foreach ($asset_multiple_images as $images ) { ?>     
                            <span class="gallery-item" >
                                <div class="image">                              
                                    <img src="<?php echo base_url(); ?>uploads/assets_images/<?php echo $images->image ?>" alt="Nature Image 1"/>                                        
                                    <ul class="gallery-item-controls">
                                        <li><span class="" data-toggle="modal" data-target="#myModal" onclick="return get_images_desc(<?php echo $images->id ?>)"><i class="fa fa-search"></i></span></li>
                                       
                                    </ul>                                                                    
                                </div>
                                                            
                            </span>
                        <?php } } ?>    
                     </div> 
             </div>
        </div>
    
    </div>
 </div>                    
</div>
<!-- END PAGE CONTENT WRAPPER -->


<?php $this->load->view('common/footer'); ?>
<script type="text/javascript">
  function get_images_desc(id)
  {
    var site_url = $("#site_url").val();
    var url = site_url+"/Dashboard/get_images_desc"; 
    $.ajax({
        type:"post",
        url:url,
        data : { id : id},
        cache:false,
        success:function(returndata)
        {
          var obj = JSON.parse(returndata);
          $("#value").html(obj.description);
       }
     });
  }
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Description</h4>
      </div>
      <div class="modal-body">
        <p id="value"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>      