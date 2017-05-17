<br>
<br>
<center>
    <i style="font-size: 72px; color : #ed5565" class=" fa fa-exclamation-circle"></i>
</center>

<div class="row">
    <p>

    <div class="col-md-8 col-md-offset-2 text-center">
        <h2 style=" color : #ed5565; margin-top: 0;">
            Permission Denied
        </h2>
        <?= $message["message"] ?><br><br>
       

        <br>


        </h3>
    </div>	

    <p>
</div>

</center>
<br>


<script>

    $ (document).ready (function ()
    {
        $ ("#finish").hide ();
        $ ("#Save").hide ();
        $("#testForm").hide();
        $('.cd-horizontal-timeline.loaded').css('opacity', '0.6');
        $(".events > ol > li > a").prop("disabled", true);

    })

</script>