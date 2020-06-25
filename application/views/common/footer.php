  </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
        
        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>                    
                        <p>Press No if you want to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="<?=site_url('Welcome/logout');?>" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->
       
        <!-- START PRELOADS -->
        <audio id="audio-alert" src="<?=base_url(); ?>assets/audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="<?=base_url(); ?>assets/audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->                  
        
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap.min.js"></script> 


         <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/scrolltotop/scrolltopcontrol.js"></script>

        <!-- END PLUGINS -->

        <!-- Select search -->
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/bootstrap/bootstrap-select.js"></script>
        <!-- Select search -->

        <!-- START THIS PAGE PLUGINS-->        
        
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/moment.min.js"></script>
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>


        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery-ui.css">
        <script src="<?= base_url(); ?>assets/js/jquery-ui.js"></script> 
        <!--<script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>-->
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
      
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
       <!--  <script type="text/javascript" src="<?=base_url(); ?>assets/js/settings.js"></script> -->
        
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?=base_url(); ?>assets/js/actions.js"></script>
        

        
        <!-- END TEMPLATE -->
        <script type="text/javascript" language="javascript" src="<?= base_url()?>assets/server_side/resources/syntax/shCore.js"></script>
        
         <script type="text/javascript">
            $(".loader").hide(); 
            setTimeout(function(){ $(".msghide").fadeOut(); },3000); 
            setTimeout(function(){ $(".form_error").fadeOut(); },3000); 
            function checkXcel() 
            {
                var excel_file = $("#excel_file").val();
                var list_id = $("#list_id").val();

                if(list_id=="0")
                {
                    $("#errorlist").fadeIn().html("Please select list");
                    setTimeout(function(){$("#errorlist").fadeOut();},5000);
                    $("#list_id").focus();
                    return false;   
                }

                if(excel_file=="")
                {
                    $("#errorexcel_file").fadeIn().html("Please select excel file in xls format");
                    setTimeout(function(){$("#errorexcel_file").fadeOut();},5000);
                    $("#excel_file").focus();
                    return false;   
                }

                var filetype = excel_file.split(".");
                ext = filetype[filetype.length-1];  
                if(!(ext=='xls'))
                {   
                    $("#errorexcel_file").fadeIn().html("Upload file in xls format only");
                    setTimeout(function(){$("#errorexcel_file").fadeOut();},5000);
                    $("#excel_file").focus();
                    return false;
                }        
            }

            function checkStatus(id)
            {
                
                $("#statusId").val(id);
                $("#deleteId").val(id);
            }
          
        </script>
<?php 
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
$value = $this->uri->segment(3);
if(!empty($controller) && !empty($function)){
  if($controller == "Assets" && $function=="getassetdetail" )
  {
    $show = "show";
    $len = 10;
  }else if( $controller == "Assets_request" && $function=="assets_request_details" )
  {
    $show = "show";
    $len = 10;
  }
  else if(  $controller == "Purchase_returns" && $function=="approveReturnReplace" && $value!='' )
  {
    $show = "show";
    $len = 10;
  }
  }else{
    $len = 10;
    $show = "";
    } ?>

   <!-- <script >
  $(document).ready(function() {
    $('.example_datatable3').DataTable({ 
                "oLanguage": {
                "sProcessing": "<img src='<?= base_url()?>assets/img/loaders/default.gif'>" 
          },
          "ajax" : url
        });
  });
</script>-->

        <script type="text/javascript" language="javascript" class="init">
			
            $(document).ready(function() {
              $('.example_datatable').DataTable({ 
                      "oLanguage": {
                      "sProcessing": "<img src='<?= base_url()?>assets/img/loaders/default.gif'>" 
                },
                "ajax" : url,
                "ordering": false,
              });
              /*$('.example_datatable2').DataTable( {
                  ajax: url,
				  "ordering": false
              
              } );*/
             /* $.fn.dataTable.ext.errMode = 'none';
                  $("#filter").keyup(function(){
         
                // Retrieve the input field text and reset the count to zero
                var filter = $(this).val(), count = 0;
         
                // Loop through the comment list
                $(".searchbleDiv li").each(function(){
         
                    // If the list item does not contain the text phrase fade it out
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).fadeOut();
         
                    // Show the list item if the phrase matches and increase the count by 1
                    } else {
                        $(this).show();
                        count++;
                    }
                });
         
                // Update the count
                var numberItems = count;
                //$(".searchbleDiv li").text("No. of Record = "+count);
            });*/




    //$(".msghide").fadeOut(8000);
       /* table = $('.example_datatable3').DataTable({ 
              "oLanguage": {
              "sProcessing": "<img src='<?= base_url()?>assets/img/loaders/default.gif'>" 
        },
        
           
            //"processing": true, //Feature control the processing indicator.
            //"serverSide": false, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            //"lengthMenu" : [[10,25, 100,200,500,1000,2000], [10,25, 100,200,500,1000,2000 ]],"pageLength" : 10,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url2,
                "type": "POST",
                 "data": function(d) {
                        d.select_all = $(".select_all").is(":checked");
                        d.SearchData = $(".filter_search_data").val();
                        d.SearchData1 = $(".filter_search_data1").val();
                        d.SearchData2 = $(".filter_search_data2").val();
                        d.SearchData3 = $(".filter_search_data3").val();
                        d.SearchData4 = $(".filter_search_data4").val();
                        d.SearchData5 = $(".filter_search_data5").val();
                        d.SearchData6 = $(".filter_search_data6").val();
                        d.SearchData7 = $(".filter_search_data7").val();
                        d.SearchData8 = $(".filter_search_data8").val();
                        d.SearchData9 = $(".filter_search_data9").val();
                        d.SearchData10 = $(".filter_search_data10").val();
                        d.SearchData11 = $(".filter_search_data11").val();
                        d.SearchData12 = $(".filter_search_data12").val();
                        d.SearchString = $(".search_string_val").val();
                        d.FormData = $(".filter_data_form").serializeArray();
                    }
                     
            },

           //Set column definition initialisation properties.
            "columnDefs": [
            { 
                "targets": [ 0,actioncolumn ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
            
            <?php if(!empty($show)){ ?>
                    "fnDrawCallback": function() {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(".append_ids").val(json.ids);
                    uni_array(); 
                 
                  },
                  <?php } ?> 
                  
        });
        $(".filter_search_data1").change(function(){
                     table
                    .draw();    
        });  
        $(".filter_search_data2").change(function(){
                     table
                    .draw();    
        });
        $(".filter_search_data4").change(function(){
                         table
                        .draw();  
        });
        $(".filter_search_data3").change(function(){
                         table
                        .draw();  
        });
        $(".filter_search_data5").change(function(){
                         table
                        .draw();  
        });*/
        
        
    });
	
function only_number(event)
{

    var x = event.which || event.keyCode;
    console.log(x);
    if((x >= 48 ) && (x <= 57 ) || x == 8 | x == 9 || x == 13 )
    {
        return;
    }else{
        event.preventDefault();
    }    
}

        $(document).ready(function () {
    //alert();
        $(".numbers").keypress(function (e) 
        {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
            {
                return false;
            }
        });
        });

        $(document).ready(function() {

    $("#from-date").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      maxDate:0,
      onClose: function( selectedDate ) {
        $( "#to-date" ).datepicker( "option", "minDate", selectedDate );
      }
    });

    $("#to-date").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      maxDate: 0,
      
    });

    $(".date").datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      minDate: 0,
      
    }); 

    /*$("#from_date").datepicker({
      //defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      
    });

    $("#to_date").datepicker({
      //defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      //maxDate: "+3M",
      
    });*/


  $( function() {
    var dateFormat = "yy-mm-dd",
      from = $( "#from_date" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          changeYear: true,
          minDate: 0,
          dateFormat:"yy-mm-dd",
         // numberOfMonths: 3
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to_date" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        dateFormat : "yy-mm-dd",
        //numberOfMonths: 3
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  });
  

    $(".date123").datepicker({
     // defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      //maxDate: "+12M",
    });

});

</script>

<input type="hidden" name="site_url" id="site_url" value="<?= site_url(); ?>"/>
    <!-- END SCRIPTS -->         
    </body>
</html>