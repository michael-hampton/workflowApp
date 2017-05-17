<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Issue list</h2>
                    
        <ol class="breadcrumb">
            <li>
                <a href="index.html">Home</a>
            </li>
                        
            <li>
                <a>App views</a>
            </li>
                        
            <li class="active">
                <strong>Issue list</strong>
            </li>
        </ol>
    </div>
               
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Issue list</h5>
                            
                    <div class="ibox-tools">
                        <a href="" class="btn btn-primary btn-xs">Add new issue</a>
                    </div>
                </div>
                        
                <div class="ibox-content">

                    <div class="m-b-lg">

                        <div class="input-group">
                            <input type="text" placeholder="Search issue by name..." class=" form-control">
                                    
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-white"> Search</button>
                            </span>
                        </div>
                                
                        <div class="m-t-md">

                            <div class="pull-right">
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-comments"></i> </button>
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-user"></i> </button>
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-list"></i> </button>
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-pencil"></i> </button>
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-print"></i> </button>
                                <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-cogs"></i> </button>
                            </div>

                            <strong>Found 61 issues.</strong>
                        </div>
                    </div>

                    <div class="table-responsive searchResults">
                       
                    </div>
                        </div>

                    </div>
                </div>
            </div>
    
    
     <!-- Peity -->
    <script src="/FormBuilder/public/js/plugins/peity/jquery.peity.min.js"></script>
    
    <!-- Peity demo data -->
    <script src="/FormBuilder/public/js/plugins/peity/peity-demo.js"></script>
    
    <script>
        searchTasks();
        
        function searchTasks() {
            
            var SendUrl = "/FormBuilder/tasks/SearchTasks";
            $.ajax({
                type: "POST",
                url: SendUrl,
                success: function (response) {
                    $(".searchResults").html(response);
                    //$(".agile-list").html(response);
                    //rebindElements();
                }
            });
        }    
    </script>



