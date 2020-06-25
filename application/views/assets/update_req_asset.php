 <?php  $this->load->view('common/header');
$this->load->view('common/left_panel'); ?>
<?php $created_by = $_SESSION[SESSION_NAME]['id']; ?> <!-- START BREADCRUMB -->
 <?= $breadcrumbs ?> <!-- END BREADCRUMB --> <!-- PAGE TITLE --> 
 <div class="page-title">                         <!-- <h3 class="panel-title"><?= $heading
?></h3> --> </div>  <!-- PAGE CONTENT WRAPPER -->            
     <div class="page-content-wrap">                
                    <div class="row">
                        <div class="col-md-12">                            
                            <form class="form-horizontal" method="post" action="<?php echo $action;?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong><?= $heading ?></h3>
                                    <ul class="panel-controls">
                                         <li><a href="<?= site_url('Assets/req_new_asset_list')?>" ><span class="fa fa-arrow-left"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                              <table class="table table-bordered" id="purchaseTableclone123" >
                                                <thead>
                                                  <tr>
                                                    <th class="col-xs-4"><h5><b>Asset Name</b><span style="color:red;">*</span><span style="color:red;font-size:12px" id="error1" class="error"></span> <span id="attrname_error" style="color:red;font-size:12px"></span></h5></th>
                                                    <th class="col-xs-3"><h5><b>Quantity </b><span style="color:red;">*</span><span style="color:red;font-size:12px" id="error2" class="error"></span></h5></th>
                                                    <th class="col-xs-3"><h5><b>Description </b></h5></th>

                                                    <th class="col-xs-1">
                                                        <div>
                                                            <button title="Add row" type="button" onclick="addrow_feedback()" style="background-color: green;color: white" class="btn bg-green waves-effect"><b><i class="fa fa-plus"></i></b></button>
                                                        </div>
                                                    </th>
                                                   </tr>
                                                </thead>
                                                <tbody id="clonetable_feedback">
                                                 <?php  $sr=1; foreach ($assets_request_details as $at)  {    ?>
                                                  <tr class="rows">
                                                    <td>
                                                      <input type="text"  value="<?php echo $at->asset_name; ?>" name="asset_name[]" id="attributename<?php echo $sr; ?>" class="col-sm-12 attrname blkclr form-control" placeholder="Asset Name"/>
                                                      <div class="searchbox"></div> 
                                                    </td>
                                                    <td>
                                                       <input type="text"  value="<?php echo $at->quantity; ?>" name="quantity[]" id="value<?php echo $sr; ?>" class="col-sm-12 blkclr form-control" placeholder="Quantity" maxlength="6" onkeypress="only_number(event)"/>
                                                      
                                                    </td> 
                                                    <td>
                                                       <textarea class="form-control" placeholder="Description" id="description"  name="description[]"><?php echo $at->description; ?></textarea>
                                                      
                                                    </td>
                                                    <td>
                                                      <button title="Delete row" type="button" onclick="deleteRow_feedback(this)" style="background-color: red;color: white" class="btn  waves-effect"><b><i class="fa fa-minus"></i></b>
                                                      </button>
                                                    </td>
                                                <?php ++$sr;  } ?>    
                                                  </tr>
                                               </tbody>
                                              </table>
                                           </div>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="panel-footer">   
                                 <button class="btn btn-success" type="submit" onclick="return validateinfo()"><?= $button;?></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->
<?php $this->load->view('common/footer');?>
<script>
    function auto_search_assets(){
     var count = $('#clonetable_feedback tr').length;
      for(var i=1; i <= count;  i++)
     {
         src = '<?= site_url('Assets/get_asset_name'); ?>';
          $("#attributename"+i).autocomplete({
           
            appendTo: "#searchbox"+i,
            source: function(request, response) {
           
              $(".ui-autocomplete").html('<img src="<?= base_url('../admin/assets/default.gif'); ?>" alt="">');
               $.getJSON(src, {search : request.term}, 
                response);
            },
          });
      }     
    }
auto_search_assets();

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
</script> 
 

<script type="text/javascript">
    function validateinfo() 
        {  

             value=[];  
                $('.attrname').each(function(){ value.push(($(this).val().trim()));}); 
                var chk_duble = checkDuplicate(value);  

                if(chk_duble == true)
                {
                  $("#attrname_error").html("Already exist").fadeIn();
                  setTimeout(function(){$("#attrname_error").fadeOut();$(".attrname").css("borderColor","#00A654") },8000)
                   $(".attrname").focus()
                  ;
                  return false;
                }


            var count = $('#clonetable_feedback tr').length;

              //alert(count);
              for(var i=1; i <= count;  i++)
              {
                var product_name1=$("#attributename"+i).val();
                var value1=$("#value"+i).val();
               // alert(product_name1);
                 if($.trim(product_name1)=="")
                {
                  $("#error1").html("Required").fadeIn();
                  setTimeout(function(){$("#error1").fadeOut()},3000);
                  $("#attributename"+i).focus();
                  return false;
                }
                if($.trim(value1)=="")
                {
                  $("#error2").html("Required").fadeIn();
                  setTimeout(function(){$("#error2").fadeOut()},3000);
                  $("#value"+i).focus();
                  return false;
                }
                if($.trim(value1)==0)
                {
                  $("#error2").html("Quantity should be greater than zero").fadeIn();
                  setTimeout(function(){$("#error2").fadeOut()},3000);
                  $("#value"+i).focus();
                  return false;
                }
              }   
     }


     function addrow_feedback()
   {   
       var y=document.getElementById('clonetable_feedback');
       var new_row = y.rows[0].cloneNode(true);
       var len = y.rows.length; 
       new_number=Math.round(Math.exp(Math.random()*Math.log(10000000-0+1)))+0;
                 
       var inp2 = new_row.cells[0].getElementsByTagName('input')[0];
       inp2.value = '';
       inp2.id = 'attributename'+(len+1);
      
       var inp3 = new_row.cells[1].getElementsByTagName('input')[0];
        inp3.value = '';
        inp3.id = 'value'+(len+1); 

        var inp4 = new_row.cells[2].getElementsByTagName('textarea')[0];
        inp4.value = '';
        inp4.id = 'description'+(len+2);
       y.appendChild(new_row);   
       auto_search_assets();         
   }
   
   //delete adde row 
   function deleteRow_feedback(row)
   {
       var y=document.getElementById('purchaseTableclone123');
       var len = y.rows.length;
       if(len>2)
       {
           var i= (len-1);
           document.getElementById('purchaseTableclone123').deleteRow(i);
       }
   } 

function checkDuplicate(name)
{ 
  var name_array = name.sort(); 
  var name_duplicate = [];
  for (var i = 0; i < name_array.length - 1; i++) 
  {
      if (name_array[i + 1] == name_array[i]) 
     {
         name_duplicate.push(name_array[i]);
     }
  }
  isValid = false;
  if(name_duplicate!='')
  {
    isValid = true;
  }
  return isValid;
}
</script>
 
