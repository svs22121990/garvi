<?php $this->load->view('common/header');?>
<link href="<?php echo base_url()?>assets/js/prettify.css" type="text/css" rel="stylesheet" /> 
<link href="<?php echo base_url()?>assets/js/style.css" type="text/css" rel="stylesheet" />
<style>
   /*for customers view*/
   /* Profile container */
   .profile {
   margin: 20px 0;
   }
   /* Profile sidebar */
   .profile-sidebar {
   padding: 20px 0 10px 0;
   background: #fff;
   }
   .profile-userpic img {
   float: none;
   margin: 0 auto;
   width: 50%;
   height: 50%;
   -webkit-border-radius: 50% !important;
   -moz-border-radius: 50% !important;
   border-radius: 50% !important;
   }
   .profile-usertitle {
   text-align: center;
   margin-top: 20px;
   }
   .profile-usertitle-name {
   color: #5a7391;
   font-size: 16px;
   font-weight: 600;
   margin-bottom: 7px;
   }
   .profile-usertitle-job {
   text-transform: uppercase;
   color: #5b9bd1;
   font-size: 12px;
   font-weight: 600;
   margin-bottom: 15px;
   }
   .profile-userbuttons {
   text-align: center;
   margin-top: 10px;
   }
   .profile-userbuttons .btn {
   text-transform: uppercase;
   font-size: 11px;
   font-weight: 600;
   padding: 6px 15px;
   margin-right: 5px;
   }
   .profile-userbuttons .btn:last-child {
   margin-right: 0px;
   }
   .profile-usermenu {
   margin-top: 30px;
   }
   .profile-usermenu ul li {
   border-bottom: 1px solid #f0f4f7;
   }
   .profile-usermenu ul li:last-child {
   border-bottom: none;
   }
   .profile-usermenu ul li a {
   color: #93a3b5;
   font-size: 14px;
   font-weight: 400;href="#" onclick=eventnotify(); 
   }
   .profile-usermenu ul li a i {
   margin-right: 8px;
   font-size: 14px;
   }
   .profile-usermenu ul li a:hover {
   background-color: #fafcfd;
   color: #5b9bd1;
   }
   .profile-usermenu ul li.active {
   border-bottom: none;
   }
   .profile-usermenu ul li.active a {
   color: #5b9bd1;
   background-color: #f6f9fb;
   border-left: 2px solid #5b9bd1;
   margin-left: -2px;
   }
   /* Profile Content */
   .profile-content {
   padding: 20px;
   background: #fff;
   min-height:330px;
   }
   .container1 {
   width: 1244px;
   }
   /*customers view end*/
   /*for badge*/
   .left-side{
 left: 172px;
}
 @media (max-width: 640px)
{
  .left-side {
    left: 17px;
}

.ribbon-red span {
    background: rgba(0, 0, 0, 0) linear-gradient(#EB3D3C 0%, #EB3D3C 100%) repeat scroll 0 0;
    box-shadow: 0 3px 10px -5px rgb(0, 0, 0);
    color: #000000;
    display: block;
    font-size: 8px;
    font-weight: bold;
    line-height: 20px;
    position: absolute;
    right: -21px;
    text-align: center;
    text-transform: uppercase;
    top: 4px;
    transform: rotate(45deg);
    width: 75px;
}
.ribbon-green span {
    background: rgba(0, 0, 0, 0) linear-gradient(#9bc90d 0%, #79a70a 100%) repeat scroll 0 0;
    box-shadow: 0 3px 10px -5px rgb(0, 0, 0);
    color: #000000;
    display: block;
    font-size: 8px;
    font-weight: bold;
    line-height: 20px;
    position: absolute;
    right: -21px;
    text-align: center;
    text-transform: uppercase;
    top: 4px;
    transform: rotate(45deg);
    width: 75px;
}
  .ribbon-info span {
    background: rgba(0, 0, 0, 0) linear-gradient(#5BC0DE 0%, #5BC0DE 100%) repeat scroll 0 0;
    box-shadow: 0 3px 10px -5px rgb(0, 0, 0);
    color: #000000;
    display: block;
    font-size: 8px;
    font-weight: bold;
    line-height: 20px;
    position: absolute;
    right: -21px;
    text-align: center;
    text-transform: uppercase;
    top: 4px;
    transform: rotate(45deg);
    width: 75px;
}
  
  
}

</style>

<div class="courses_banner">
    <div class="container">
      <h3><?php echo $heading_banner ?></h3>
      </br>
      </br>
        <div class="breadcrumb1">
            <ul>
                <li class="icon6"><a href="<?= site_url('Dashboard/index')?>">Dashboard</a></li>
                <li class="current-page"><?php echo ucwords($name); ?></li>
            </ul>
        </div>
    </div>
  </div>
<div class="main-content">
   <div class="main-content-inner">
      <div class="breadcrumbs" id="breadcrumbs">
         <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
         </script>
         <?//php echo $breadcrumbs;?>
      </div>
      <div class="page-content">
         <div class="">
            <div class="container" >
               <div class="row profile" >
                  <div class="col-md-3" >
                     <div class="panel panel-warning">
                        <div class="panel-heading2"><?php echo strtoupper("Author Details");?></div>
                        <div class="panel-body">
                           <div class="profile-sidebar">
                              <!-- SIDEBAR USERPIC -->
                              <div class="profile-userpic">
                                 <center><img src="<?php echo base_url(); ?>/uploads/profile/<?php echo $image; ?>" style="height:160px; width:160px;" class="img-responsive" alt=""/></center>
                              </div>
                              <!-- END SIDEBAR USERPIC -->
                              <!-- SIDEBAR USER TITLE -->
                              <div class="profile-usertitle">
                                 <div class="profile-usertitle-name">
                                    <span><?php echo ucwords($name); ?></span>
                                 </div>
                              </div>
                              <div class="profile-usermenu">
                                 <ul class="nav">
                                    <li class="active">
                                       <a>
                                       <span>Name : <?php echo ucwords($name); ?></span> </a>
                                    </li>
                                    <li>
                                       <a>
                                       <span>Email : <?php echo ($email); ?></span> </a>
                                    </li>
                                    <li>
                                       <a>
                                       <span>Mobile : <?php echo ($mobile); ?></span> </a>
                                    </li>
                                  <li>
                                       <a>
                                       <span>Author Type : <?php echo ($type); ?></span> </a>
                                    </li>
                                 </ul>
                              </div>
                              <!-- END MENU -->
                           </div>

                        </div>

                     </div>
                      
              
                  </div>
                  <!-- <a class="shortcode_but small pull-right" href="#" target="_self" style="color:#ffffff; background-color:#4fbed6; ">Click Me</a> -->
                 
                  <div class="col-md-9">
                    
                      <div class="col-md-12 row" >
                        <div class="panel panel-warning">
                           <div class="panel-heading2"><?php echo strtoupper("AUTHOR Events DETAILS ");?>
                                
                           </div>

                           <div class="panel-body" id="testDiv">
                              <?php  foreach($events as $rows)  {  ?>
                              <div class="event-page ">
                                 <div class="row">
                                    <div class="col-xs-4 col-sm-4">
                                       
                                       <div class="">
                                          <a href="<?php echo site_url('Dashboard/GetEventsDetails/'.$rows->id); ?>"><img src="<?php echo base_url(); ?>uploads/events/<?php echo $rows->event_image; ?>" class="img-responsive" alt="image"/></a>
                                          <div class="over-image"></div>
                                       </div>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 event-desc">
                                    <a href="<?php echo site_url('Dashboard/GetEventsDetails/'.$rows->id); ?>">
                                       <h2 class="black"><?php echo ucwords($rows->event_title) ?></h2>
                                       <div class="event-info-text">
                                          <div class="event-info-middle">
                                          
                                             <p style="display:inline;"><span class="event-bold"></span></p>
                                             <p class="black"><span class="event-bold black">Event Date : &nbsp;</span class="black"><?php echo date($set_alldate,strtotime($rows->start_date)).' to '.date($set_alldate,strtotime($rows->end_date)); ?></p>
                                             <p class="black"><span class="event-bold black">Location : &nbsp;</span><?php echo $rows->venue.', '.$rows->city_name.', '.$rows->state_name.', '.$rows->country_name.', '.$rows->pincode; ?></p>
                                             <span>    
                                             </span>
                                             </a>
                                      
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <?php }  ?>
                           </div>
                        </div>
                     </div> 
                  </div>
               </div>
               <br>
               <br>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>  
<?php $this->load->view('common/footer');?>
<form method="POST" action="<?php echo site_url('Welcome/changeStatus') ?>" >
<div class="modal fade" id="getStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" style="width:430px;">
      <div class="modal-content">
         <div class="modal-body">
            <!-- <div id="returnDeleteData"></div> -->
            <div>
               <div style="font-size: medium;text-align:left">
                  Are you sure to change the status of event?
               </div>
            </div>
            <hr>
            <div>
            <input type="hidden" name="id" id="rowid" value="">
            <input type="hidden" name="is_published" id="is_published" value="">
               <button type="submit" class="btn btn-primary">Yes</button>
               <button type="button" name="cancel" id="cancel" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
         </div>
      </div>
   </div>
</div>
</form>
<script type="text/javascript">
  function changeStatus(id,is_published)
  {
    $("#rowid").val(id);
    $("#is_published").val(is_published);
  }
</script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/prettify.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript">
    $(function(){
      $('#testDiv').slimscroll({
        height: '475px'
      })

      $('#testDiv2').slimScroll({
          height: '400px',
          width: '300px'
      });

    });
</script>