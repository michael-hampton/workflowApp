<!-- file upload -->
<form contentType="multipart/form-data" id="fileUploadForm" name="fileUploadForm">

    <div style="float: left; width:100%;">
        <div style="margin-top: 11px; margin-left: 13px;" class="TaskManaggerMiddleTitles">Attached Files</div>

        <div id="upfilewrapper" style="display: none;">
            <input type="file" class="TaskManagerButtonBig" name="file" id="file" multiple/>
        </div>
        
        <ul id="filelist"></ul>
    </div>


    <div style="display:none;" class="col-lg-12 pull-left alert alert-success" id="uploadSuccessWrapper">File has been uploaded successfully.</div>

    <div class="col-lg-12 ibox-content attachments-list">
        <?php
        foreach ($attachmnets as $jobCommentsResultKeys => $jobCommentsResultValues) :
            $strFile = str_replace ("C:/xampp/htdocs", "", $jobCommentsResultValues['filename']);
            $strFileName = substr (strrchr ($jobCommentsResultValues['filename'], "/"), 1);


            echo '<div class="file-box">
                                        <div class="file">
                                            <a href="/FormBuilder/attachments/download/' . $jobCommentsResultValues['id'] . '" target="_blank">
                                                <div class="icon">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                <div class="file-name">
                                                   ' . $jobCommentsResultValues['filename'] . '
                                                    <br>
                                                    <small>Added:' . $jobCommentsResultValues['uploaded_by'] . ' <br> ' . date ("M d, Y", strtotime ($jobCommentsResultValues["date_uploaded"])) . '</small>
                                                </div>
                                            </a>
                                        </div>
                                    </div>';
            ?>


        <?php endforeach; ?>
    </div>

</form>

<script>
    function getFile ()
    {
        $ ("#file").click ();
        $ ("#uploadSuccessWrapper").hide ();
    }

    function progressHandlingFunction (e)
    {
        if (e.lengthComputable)
        {
            $ ('#progress').attr ({value: e.loaded, max: e.total});
        }
    }

    /*$(".attachmentFile").click(function () {
     location = "/attachments/downloadAttachment/" + $(this).attr("fileId");
     
     $.ajax({
     url: SendUrl,
     type: 'GET',
     processData: false,
     contentType: false,
     data: null,
     async: false,
     success: function (response) {
     
     }, error: function (request, status, error) {
     location.reload();
     }
     });
     });*/

    $ ("#uploadfile").click (function (e)
    {

        $ ("form#fileUploadForm").submit ();

        e.preventDefault ();
        $ ("#Save").attr ("disabled", "disabled");
        $ ("#uploadfile").hide ();


        var SendUrl = "/FormBuilder/attachments/uploadAttachedFile/" + $ ("#projectId").val ();

        var formData = new FormData ($ (this)[0]);

        $.ajax ({
            url: SendUrl,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            async: false,
            success: function (response)
            {

                $ ("#fileCounter").html ($ ("#fileCounter").html () / 1 + 1);
                $ ("#uploadSuccessWrapper").slideDown ();
                $ ("#filelist").hide ();
                $ (".attachments-list").html (response);

            }
        });
    });

    $ ("form#fileUploadForm").on ("submit", function (e)
    {

        e.preventDefault ();
        $ ("#Save").attr ("disabled", "disabled");
        $ ("#uploadfile").hide ();


        SendUrl = "/FormBuilder/attachments/uploadAttachedFile/" + $ ("#projectId").val () + "/" + $ ("#documentType option:selected").text ();

        var formData = new FormData ($ (this)[0]);

        $.ajax ({
            url: SendUrl,
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            async: false,
            success: function (response)
            {
                $ ("#fileCounter").html ($ ("#fileCounter").html () / 1 + 1);
                $ ("#uploadSuccessWrapper").slideDown ();
                $ ("#filelist").hide ();

                var count = parseInt ($ (".openAttachments > span").text ());

                if (isNaN (count))
                {
                    $ (".openAttachments > span").text (1);
                }
                else
                {
                    $ (".openAttachments > span").text (parseInt ($ (".openAttachments > span").text ()) + 1);
                }

               $(".openAttachments").click();
               $("#SearchButton").click();

            }
        });
    });

    $ (':file').change (function ()
    {
        $ ("#filelist").html ("");

        var file = this.files[0];
        var Fname = file.name;
        var Fsize = file.size;
        var Ftype = file.type;

        if (Fsize < 16000000)
            $ ("#filelist").append ("<li style=\"padding: 5px; color:#808080;\"><i class=\"fa fa-file-image-o\"></i><span style=\"\">&nbsp;&nbsp;" + Fname + " - " + Math.floor (Fsize / 1048576 * 100) / 100 + "MB</span></li>");
        console.log (Fname);
        $ ("#Save").removeAttr ('disabled');
        $ ("#codes-amount").val ("").attr ("disabled", "disabled")
        $ ("#uploadfile").show ();
        $ ("#filelist").show ();

    });

</script>