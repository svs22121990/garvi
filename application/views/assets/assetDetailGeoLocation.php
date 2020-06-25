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
          <h3><?php echo $heading; ?></h3>
           
           
            
          <div class="col-md-12" id="map" style="height:500px;">
                           </div>
           
           
            <div class="col-md-2">&nbsp;</div>
        </div>
    </div>
    
    </div>
 </div>                    
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script async defer
   src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVNitSwVJ5IoV9bQFx0IQ6UDtNcNDy6OM&callback=initMap"></script>
<script>
   function initMap() {
     var map = new google.maps.Map(document.getElementById('map'), {
       zoom: 8,
       center: {lat: 20.5937, lng: 78.9629}
     });
   var image = '<?php echo $image ?>';

   <?php $sr=1;  foreach($asset_geo_locations as $row1){ 
   $date=date('Y-m-d',strtotime($row1->created));

    ?>
        var beachmarker4 = new google.maps.Marker({
        position: {lat:<?= $row1->latitude; ?> , lng:<?= $row1->longitude; ?>},
        <?php  if($count_geo == $sr) { ?>
        icon:  image,
         map: map,
        title: 'Current Location.  Scan Date <?= $date; ?>'
        <?php   } else{ ?> 
        map: map,
        title: 'Scan Date <?= $date; ?>'
        <?php }  ?>
       
     });
   beachmarker4.addListener('click', function() {
       map.setZoom(8);
       map.setCenter(beachmarker4.getPosition());
     });
   <?php $sr++; } ?>
   
   
   }
</script>

<?php $this->load->view('common/footer'); ?>
