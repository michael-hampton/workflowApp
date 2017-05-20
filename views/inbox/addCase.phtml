<div class="col-lg-12 pull-left">
    <?= $pagination ?> 
</div>


<div class="client-detail" style="padding: 26px;">
    <?php
    $requestId = null;
    foreach ($arrWorkflows as $arrWorkflow) {
        ?>

        <?php
        if ( $requestId === null || $requestId !== $arrWorkflow['request_id'] )
        {
            echo '</ul><ul class="list-group clear-list">'
            . '<strong>' . $arrWorkflow['PRO_CATEGORY_LABEL'] . '</strong>';

            $end = true;
        }
        ?>

        <li class="list-group-item fist-item" style="border-bottom: 1px dotted #CCC;">
            <span class="pull-right"> 
                <button workflowid="<?= $arrWorkflow['workflow_id'] ?>" type="button" class="btn btn-primary btn-xs addNewCase">+</button> 
            </span>
            <?= $arrWorkflow['workflow_name'] ?>
        </li>

        <?php
        $requestId = $arrWorkflow['request_id'];
        $end = false;
    }
    ?>
</div>

<div class="modal inmodal fade" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
</div>

<script>
    $ (".addNewCase").on ("click", function ()
    {
        var workflowid = $ (this).attr ("workflowid");

        $.ajax ({
            type: "GET",
            url: "/FormBuilder/inbox/addNewCase/" + workflowid,
            success: function (response)
            {
               $ ("#myModal6").html(response).modal ("show");
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });

        

        alert (workflowid);
    });
</script>