<!-- Data picker -->
<link href="/FormBuilder/public/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>

            <h4 class="modal-title">Create New Case</h4>        
            <small class="font-bold">Create case.</small>           
        </div>

        <div class="modal-body">          

            <div id="successMsgWrapper" class="alert alert-info" style="display:none;">
                The concept has been saved successfully.
            </div>

            <div id="warningMsgWrapper" class="alert alert-danger" style="display: none;">
                Something's missing or wrong. See the details below.
            </div>

            <?= $html ?>
        </div>

        <div class="modal-footer">                  
            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>                
            <button id="saveNewChannel" type="button" class="btn btn-primary">Save changes</button>
        </div> 
    </div>
</div>

<script>
    $ ("#saveNewChannel").click (function ()
    {
        $ ("#AddNewForm").submit ();
    });

    $ (".workflow").on ("change click", function ()
    {
        if ($ (this).val () != 'null')
        {
            var filename = $ (".workflow option:selected").attr ("filename");
            $ (".template").removeClass ("hidden");
            var defHref = $ (".templatepath").attr ("defHref");
            var newUrl = defHref.replace ("[FILE]", filename);
            $ (".templatepath").attr ("href", newUrl);
        }
    });

    $ ("form#AddNewForm").submit (function ()
    {
        errorCounter = 0;

        $ ("#saveNewChannel").attr ("disabled", "disabled");
        $ (".TaskManagerFormInput").removeClass ("TaskManagerFormError");
        $ ("#successMsgWrapper").hide ();
        $ ("#warningMsgWrapper").hide ();
        $ (".alert").hide ();

        var formData = new FormData ($ (this)[0]);

        var SendUrl = "/FormBuilder/inbox/saveNewCase";

        $.ajax ({
            url: SendUrl,
            type: 'POST',
            data: formData,
            async: false,
            success: function (response)
            {

                if ($.trim (response) == "")
                {
                    $ ("#saveNewChannel").removeAttr ("disabled");
                    $ ("#successMsgWrapper").slideDown ().delay (3000).slideUp ();
                    $ ("#SearchButton").click ();

                }
                else
                {
                    var objResponse = $.parseJSON ($.trim (response));
                    $.each (objResponse["validation"], function (index, element)
                    {
                        $ ("#" + element["id"] + "Warning").slideDown ();
                    });

                    $ ("#warningMsgWrapper").slideDown ().delay (3000).slideUp ();
                    $ ("#saveNewChannel").removeAttr ("disabled");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });

        return false;
    });

</script>