<link rel="stylesheet" href="/FormBuilder/public/css/gantt/screen.css" />
  <link rel="stylesheet" href="/FormBuilder/public/css/gantt/gantti.css" />

<?php
echo $gantti;
?>
  
<div class="modal inmodal" id="taskModal" tabindex="-1" role="dialog" aria-hidden="true">
    
</div>
  
<script>
    $( document ).ready(function() {
        $(".gantt-label").off("click");
        $( ".gantt-label" ).on( "click", function() {
            
            $("#taskModal").html("");
            
            var projectId = $(this).attr("projectid").split("-")[0];
            var step = $(this).attr("projectid").split("-")[1];
           
            $.ajax({
                type: "GET",
                url: "/FormBuilder/tasks/getTaskWindow/" + projectId + "/" + step,
                success: function (response) {
                    $("#taskModal").html(response);
                }
            });
        });
    });
          
      
  </script>



