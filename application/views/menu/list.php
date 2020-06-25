<?php 
$this->load->view('common/header');
$this->load->view('common/left_panel');
?>
<style type="text/css">
  .scroll_box { height:350px;overflow-y: scroll;overflow-x: hidden;}
</style>
<!-- START BREADCRUMB -->
   <?= $breadcrumbs ?>
<!-- END BREADCRUMB -->          

<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap"> 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">                                
                    <h3 class="panel-title"><strong><?php echo $heading; ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span id="successCountryEntry"></span></h3>
                    <ul class="panel-controls">
                        <li><a href="#" data-toggle="modal" data-target="#myModal" ><span class="fa fa-plus"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        
                    </ul>
              
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable ">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Module Name</th>
                                <th>Menu Name</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END DEFAULT DATATABLE -->
<input type="hidden" name="ra_module_id" id="ra_module_id1" value="<?php echo $ra_module_id; ?>">
<script type="text/javascript">
    var actioncolumn="5";
     var url="<?= site_url('Ra_menus/ajax_manage_page/'.$ra_module_id); ?>";

</script>

<?php $this->load->view('common/footer');?>
<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">   
            <form method="post" action="<?= site_url('Ra_menus/changeStatus') ?>">       
                <div class="modal-body" style="height: 100px;padding-top: 10%">
                    <center>
                        <input type="hidden" name="id" id="statusId" style="display: none;">
                        <span style="font-size: 16px">Are you sure to change the status?</span>
                    </center>
                </div>
                <div class="modal-footer" >
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 

<div class="modal fade" id="deleteData" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">   
            <form method="post" action="<?= site_url('Ra_menus/delete') ?>">       
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px"> 
                           
                        <br>Are you sure want to delete this record? </span>
                    </center>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-primary btn-sm">Ok</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Menu </strong><span id="successEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
                <label>Menu Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="menu_nameError" style="color:red"></span></label>
                <input type="text" name="menu_name" id="menu_name" value="" class="form-control" placeholder="Menu Name" size="35"/>
                <br>
                <label>Value:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="valueError" style="color:red"></span></label>
                <input type="text" name="value" id="value" value="" class="form-control" placeholder="Value" size="35"/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="saveData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="myModaledit" role="dialog">
    <div class="modal-dialog">     
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Edit Menu </strong><span id="successEditEntry" style="color:green"></span></h4>
        </div>
        <div class="modal-body">
         <form method="post">
            <label>Menu Name:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="menu_nameError1" style="color:red"></span></label>
            <input type="text" name="menu_name" id="titlemenu_name" value="" class="form-control" placeholder="Menu Name" size="35"/>
            <br>
            <label>Value:<span style="color:red;">*</span>&nbsp;&nbsp;&nbsp;<span id="valueError1" style="color:red"></span></label>
            <input type="text" name="value" id="titlevalue" value="" class="form-control" placeholder="Value" size="35"/> 
            
          </form>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" id="updateId">
          <button type="button" class="btn btn-round btn-success" id="statusEdiBtn" onclick="updateData()">Submit</button>
          <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


<form method="post" action="<?php echo site_url('Ra_menus/create_menu_action_values'); ?>" id="myform">
  <div class="modal fade" id="AssignActionData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 id="myModalLabel" class="action_title semi-bold"><strong>Assign Menu Actions</strong></h4>
        </div>
        <div class="modal-body scroll_box">
          <div class="row clearfix">
            <div class="col-md-12">
              <div class="col-md-12">
                <div id="error_msg" class="error_msg"></div>
              </div>
            </div>
            <div class="col-md-12" id="AllActions">
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
         <input type="hidden" name="ra_module_id" id="ra_module_id" value="<?php echo $ra_module_id; ?>">
          
          <input type="hidden" name="ra_menu_id" id="ra_menu_id" value="">
         
          <button type="submit" id="submit_action_data" class="btn btn-primary" style="">Done</button>
          <button type="button" class="btn btn-danger" style="" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- End -->
<script type="text/javascript">
      function checkStatus(id)
      {
        $("#statusId").val(id);
        $("#deleteId").val(id);
      }

      $(document).ready(function(){
        $(".preloader").show();
      })
</script>


 <script type="text/javascript">
    function saveData()
    {
        var menu_name = $("#menu_name").val(); 
        var value = $("#value").val();
        var ra_module_id = $("#ra_module_id1").val();
        //var country_name1 = /^[a-zA-Z -]+$/;

        if($.trim(menu_name) == "")
        {
              $("#menu_nameError").fadeIn().html("Please enter menu name");
              setTimeout(function(){$("#menu_nameError").fadeOut();},4000);
              $("#menu_name").focus();
              return false;
        }

        if($.trim(value) == "")
        {
              $("#valueError").fadeIn().html("Please enter value");
              setTimeout(function(){$("#valueError").fadeOut();},4000);
              $("#value").focus();
              return false;
        }

      
        $('.loader').show();
        var datastring  = "menu_name="+menu_name+"&value="+value+"&ra_module_id="+ra_module_id;
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Ra_menus/addData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            {
              $('.loader').hide();
              $("#menu_nameError").fadeIn().html("Menu name already exist");
              setTimeout(function(){$("#menu_nameError").fadeOut();},8000);
            }
            else
            {
              $('.loader').hide();
             $(".close").click(); 
            $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Menu has been Added successfully</span>");
            $("#myModal").modal("hide"); 
            table.draw();
       
            }
           
          }
        });
    }
</script>

<!--EDIT DATA VIA AJAX STARTS-->
<script type="text/javascript">

function getEditvalue(rowid)
    {     
        $("#updateId").val(rowid);
        //alert(rowid);
        $.ajax({
          type: "POST",
          url: "<?= site_url('Ra_menus/getUpdateName'); ?>",
          data: {id:rowid},
          cache: false,       
          success: function(result)
          { 
            var obj = $.parseJSON(result);
             $("#titlemenu_name").val(obj.menu_name);
            $("#titlevalue").val(obj.value);
          }             
        });



    }

    function updateData()
    {
        var menu_name = $("#titlemenu_name").val();
        var value = $("#titlevalue").val();
        var updateId = $("#updateId").val();
       // var country_name2 = /^[a-zA-Z -]+$/;

        if($.trim(menu_name) == "")
        {
              $("#menu_nameError1").fadeIn().html("Please enter menu name");
              setTimeout(function(){$("#menu_nameError1").fadeOut();},8000);
              $("#titlemenu_name").focus();
              return false;
        }

        if($.trim(value) == "")
        {
              $("#valueError1").fadeIn().html("Please enter value");
              setTimeout(function(){$("#valueError1").fadeOut();},8000);
              $("#titlevalue").focus();
              return false;
        }

         $('.loader').show();
        var datastring  = "menu_name="+menu_name+"&id="+updateId+"&value="+value;
       
        $.ajax({
          type : "post",
          url : "<?php echo site_url('Ra_menus/updateData') ?>",
          data : datastring,
          success : function(response)
          {
            if(response == 1)
            { $('.loader').hide();
              $("#menu_nameError1").fadeIn().html("Menu name already exist");
              setTimeout(function(){$("#menu_nameError1").fadeOut();},8000);
            }
            else
            {
               $('.loader').hide();
              $(".close").click(); 
              $("#successCountryEntry").fadeIn().html("<span class='label label-success'> Menu has been updated successfully</span>");
              $("#myModaledit").modal("hide"); 
              table.draw();
            }
           
          }
        });
    }

</script>
<script type="text/javascript">
  function assignIDs(ra_menu_id, ra_module_id){
     $('#ra_module_id1').val(ra_module_id);
  
    $('#ra_menu_id').val(ra_menu_id);

    $('.loader').show();
    var site_url=$('#site_url').val();
    $.ajax({
          type:"POST",
          url:site_url+'/Ra_menus/getAllActions',
          data:{ra_menu_id:ra_menu_id,ra_module_id:ra_module_id},
          cache:false,                    
          success:function(returndata)
          { 
           $('.loader').hide();
           $('#AllActions').html(returndata);
          }
        });
    

  }
 $(function(){
  $('.checkBox').click(function(){
   // alert("Hi");
    if($(this).is(':checked')){


    var inpChk=$(':input:eq(' + ($(':input').index(this) + 1) + ')');
    inpChk.val('Y');
    var inp=$(':input:eq(' + ($(':input').index(this) + 2) + ')');
    inp.removeAttr('readonly');
    inp.attr('required',true);
   }else{
        
        var inpChk=$(':input:eq(' + ($(':input').index(this) + 1) + ')');
        inpChk.val('N');
        var inp=$(':input:eq(' + ($(':input').index(this) + 2) + ')');
        inp.val('');
        inp.attr('readonly',true);
        inp.removeAttr('required');
   }
  });
});
  $(function(){
    $("#myform").submit(function(){

        var valid=0;
        $(this).find('input[type=text]').each(function(){
            if($(this).val() != "" ) valid+=1;
        });

        if(valid){
    
            return true;
        }
        else {
            alert("Error: Please Check atleast one check box and  must fill value in input");
            $('#is_list').focus();
            $('#list_function').focus().css('border-color','orange');
            return false;
        }
    });
});
</script>