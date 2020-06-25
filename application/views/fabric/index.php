<?php
$this->load->view('common/header');
$this->load->view('common/left_panel');
//print_r(site_url());exit;
?>
<!-- START BREADCRUMB -->
<?= $breadcrumbs ?>
<!-- END BREADCRUMB -->
<!-- PAGE TITLE -->
<div class="page-title">
    <!-- <h3 class="panel-title"><?= $heading ?></h3> -->
</div>
<!-- END PAGE TITLE -->

<!-- PAGE CONTENT WRAPPER -->
<!-- START DEFAULT DATATABLE -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong><?= $heading ?></strong></h3>
                    <h3 class="panel-title"><span class="msghide"><?= $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span></h3>
                    <h3><span class="label label-success text-center" style="margin-bottom:0px; display: none" id="successCatEntry"></span></h3>
                    <ul class="panel-controls">
                        <?php if($importPermission=='1'){ ?>
                            <li>
                                <?php if(!empty($import)) { ?>
                                    <?php  echo  $import; ?>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        <?php if($addPermission=='1'){?>
                            <li><a href="#" data-toggle="modal" data-target="#myModal" onclick="blankval()"><span class="fa fa-plus"></span></a></li>
                        <?php }?>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <!-- <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-actions example_datatable">
                        <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Fabric Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Add New Fabric </strong><span id="successEntry" style="color:green"></span></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="cat" onsubmit="return saveData()">
                    <label>Fabric Name:</label><span style="color:red">*</span>  <span id="nameError" style="color:red"></span><br>
                    <input type="text" name="name"  class="form-control" id="name" value="" autocomplete="off" size="35"/> &nbsp;


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-round btn-success" id="statusSubBtn" onclick="return saveData()">Submit</button>
                <button type="button" class="btn btn-round btn-danger"  data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="checkStatus" data-modal-color="lightblue" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
            <form method="post" action="<?= site_url('Fabric/changeStatus') ?>">
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
            <form method="post" action="<?= site_url('Fabric/delete') ?>">
                <div class="modal-body" style="height: 120px;padding-top: 3%">
                    <center>
                        <input type="hidden" name="id" id="deleteId" style="display: none;">
                        <span style="font-size: 16px">
                          You want to delete this record.
                        <br>Are you sure? </span>
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


<div class="modal fade" id="myModaledit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><strong>Edit Fabric </strong><span id="successEditEntry" style="color:green"></span></h4>
            </div>
            <div class="modal-body">
                <form method="post" id="catedit" onsubmit="return updateData()">
                    <label>Fabric Name:</label><span style="color:red">*</span>  <span id="titleError" style="color:red"></span><br>
                    <input type="text" name="name" id="titleName" value="" class="form-control" size="35"/> &nbsp;

                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="updateId">
                <button type="button" class="btn btn-round btn-success" id="statusEdiBtn" onclick="return updateData()">Submit</button>
                <button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- END DEFAULT DATATABLE -->
<!--IMport strart-->
<!--IMport strart-->

<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<?php $this->load->view('common/footer');?>
<script type="text/javascript">
    function checkStatus(id)
    {
        $("#statusId").val(id);
        $("#deleteId").val(id);
    }
</script>


<script type="text/javascript">
    function blankval()
    {
        $("#name").val("");
        $("#myModal").click();
    }
</script>


<script type="text/javascript">
    var url="<?= $ajax_manage_page; ?>";
    var actioncolumn="<?= $actioncolumn; ?>";
</script>

<script type="text/javascript">
    $('#cat').submit(function(event)
    {
        event.preventDefault();
    });
    $('#catedit').submit(function(event)
    {
        event.preventDefault();
    });
    function saveData()
    {
        //alert();

        var name = $("#name").val();
        /*var name1 = /^[a-zA-Z -]+$/;*/

        if($.trim(name) == "")
        {
            $("#nameError").fadeIn().html("Please enter Fabric");
            setTimeout(function(){$("#nameError").fadeOut();},4000);
            $("#name").focus();
            return false;
            //$('.loader').hide();
        }

        $('.loader').show();
        var datastring  = "name="+name;
        var table = $('.example_datatable').DataTable();
        $.ajax({
            type : "post",
            url : "<?php echo site_url('Fabric/addData'); ?>",
            data : datastring,
            success : function(response)
            {
                //alert(response);return false;
                if(response == 1)
                {
                    $("#nameError").fadeIn().html("Fabric name already exist");
                    setTimeout(function(){$("#nameError").fadeOut();},8000);
                }
                else
                {
                    $(".close").click();
                    $('.loader').hide();
                    $("#successCountryEntry").fadeIn().html("Fabric has been Added successfully");
                    //table.draw();
                    table.ajax.reload();
                    $("#successCatEntry").fadeIn().html(" Fabric has been Added successfully");
                    setTimeout(function() { $("#successCatEntry").fadeOut(); }, 2000);
                    /*setTimeout(function(){ window.location.reload(); },100);*/
                }

            }
        });
    }
</script>

<script type="text/javascript">

    function getEditvalue(rowid)
    {
        $('.loader').show();

        $("#updateId").val(rowid);
        //alert(rowid);
        $.ajax({
            type: "POST",
            url: "<?= site_url('Fabric/getUpdateName'); ?>",
            data: {id:rowid},
            cache: false,
            success: function(result)
            {
                $("#titleName").val($.trim(result));
                $('.loader').hide();
            }
        });

//$("#titleName").val(rowid);

    }

    function updateData()
    {
        var name = $("#titleName").val();
        var updateId = $("#updateId").val();
        /*var name2 = /^[a-zA-Z -]+$/;*/

        if($.trim(name) == "")
        {
            $("#titleError").fadeIn().html("Please enter Fabric");
            setTimeout(function(){$("#titleError").fadeOut();},8000);
            $("#titleName").focus();
            return false;
        }

        /* else if(!name2.test(name))
         {
         alert("Nickname can have only alphabets and numbers.");
         $("#titleError").fadeIn().html("Name can have only alphabets (@,&,*,$,#,! are not allowed)");
         setTimeout(function(){$("#titleError").fadeOut();},4000);
         $("#titleName").focus();
         return false;
         }*/

        var datastring  = "name="+name+"&id="+updateId;
        var table = $('.example_datatable').DataTable();
        $.ajax({
            type : "post",
            url : "<?php echo site_url('Fabric/updateData') ?>",
            data : datastring,
            success : function(response)
            {
                if(response == 1)
                {
                    $("#titleError").fadeIn().html("Fabric name already exist");
                    setTimeout(function(){$("#titleError").fadeOut();},8000);
                }
                else
                {
                    $(".close").click();
                    $("#successCountryEntry").fadeIn().html("Fabric has been updated successfully");
                    //table.draw();
                    table.ajax.reload();
                    $("#successCatEntry").fadeIn().html("Fabric has been updated successfully");
                    //setTimeout(function() { $("#successCatEntry").fadeOut(); }, 2000);

                }
                //location.reload();
            }
        });
    }

</script>





//