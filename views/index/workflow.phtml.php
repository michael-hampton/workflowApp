<link href="/FormBuilder/public/css/steps.css" rel="stylesheet" type="text/css">
<link href="/FormBuilder/public/css/timeline.css" rel="stylesheet" type="text/css">
  
     

      
    <script>
        function showSweetAlert() {
            swal({title: "Good job!",
                text: "I will close in 2 seconds.",   
                timer: 2000,   
                showConfirmButton: false ,
                type: "success"
            });
        }
        
        
         $(document).ready(function(){
            showSweetAlert();
        });
      
     
    </script>
    
    <script src="/FormBuilder/public/js/getTasks.js" type="text/javascript"></script>
    
    <script>
    <?php if($blComplete === true): ?>
        $("#Save").hide();
        $("#finish").hide();
        $("#Next").hide();
        $(".taskStatus").show();
        $(".rejectionDiv").show();
    <?php endif; ?>
</script>
   