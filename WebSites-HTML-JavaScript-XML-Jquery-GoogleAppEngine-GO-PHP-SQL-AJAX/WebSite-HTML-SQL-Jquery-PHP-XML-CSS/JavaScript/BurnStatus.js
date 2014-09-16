
   var xml =[];

   function startAjax(){
     try{
	
        $.ajax({
          url:"../xml/burnStatus.xml",
          success:callbackFunction,
          error:errorFunction
        });

     } catch(err){
        errText = "Error in startAjax() \n\n"+err.message;
        alert(errText);
     }
   }


   function errorFunction(data,info){
      $("#content").text("error occurred:"+info);
   }

   function callbackFunction(data,info){
      <!-- adds catagories to the menu with the id of cat+catagory -->
      var date = $(data).find("date").text();
      var status = $(data).find("status").text();
      var county = $(data).find("county").text();
      $("#content").append("<div>As of "+date+" there are "+status+" in "+county+" county."</div>");
       
      });

   $(document).ready(function() {
      startAjax();

   });