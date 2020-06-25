 //GET STATES
   function getStates(country_id)
   {
     $("#state_id").val('0');
     $("#city_id").val('0');
     var site_url = $('#site_url').val(); 
     var url = site_url+"/Users/getState";
     var dataString = "country_id="+country_id;
     $.post(url,dataString,function(returndata){
      
       $('#state_id').html(returndata).selectpicker('refresh');

     });
   }


   //GET CITIES
   function getCities(state_id)
   {
     var site_url = $('#site_url').val();
     var country_id = $('#country_id').val();
     var url = site_url+"/Users/getCities";
     var dataString = "state_id="+state_id;
   
     $.post(url,dataString,function(returndata){
      //alert(returndata);return false;
       $('#city_id').html(returndata).selectpicker('refresh');
     });
   }

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

