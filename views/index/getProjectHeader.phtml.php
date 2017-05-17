<link href="/FormBuilder/public/css/timeline.css" rel="stylesheet" type="text/css">


<section class="cd-horizontal-timeline">
    <div class="timeline pull-left" style="border-bottom: 1px dashed #1ab394;">
        <div class="events-wrapper">
            <div class="events">
                <ol>
                    <?php
                    $intCount = 0;
                    $date = date ("Y-m-d");
                    $class = '';
                    $completed = '';
                    $audit = '';
                    $arrUsed = array();

                    foreach ($steps as $arrStep) {

                        if ( $arrStep['step_id'] == $statusId )
                        {
                            $class = 'selected';
                        }
                        else
                        {
                            $class = 'disabledClass';
                        }

                        if ( $arrStep['step_id'] < $statusId )
                        {
                            $class .= ' older-event';
                        }

                        if ( !in_array ($arrStep['step_id'], $arrUsed) )
                        {
                            echo '<li><a title="' . $audit . '" href="#0" step-id="' . $arrStep['step_id'] . '" data-date="' . $date . '" class="' . $class . ' ' . $completed . ' projectFlow">' . $arrStep['step_name'] . '</a></li>';
                        }

                        $intCount++;

                        $arrUsed[] = $arrStep['step_id'];
                    }
                    ?>
                </ol>

                <span class="filling-line" aria-hidden="true"></span>
            </div>
        </div>

        <ul class="cd-timeline-navigation">
            <li><a href="#0" class="prev inactive">Prev</a></li>
            <li><a href="#0" class="next">Next</a></li>
        </ul>

    </div>
</section>

<div class="row text-center m-t-md pull-left col-lg-12">
    <?= $html ?>

</div>

<div class="form-group" style="display: none;">
    <label class="col-sm-2 control-label">Assign to</label>

    <div class="col-sm-10">
        <select multiple class="form-control user multipleSelect" name="form[user]">
            <option value="null">Choose One</option>
            <?php foreach ($users as $arrayKey => $arrayValues): ?>
                <option value="<?= $arrayValues["username"] ?>"> <?= $arrayValues["username"] ?></option>
            <?php endforeach; ?>
        </select>

        <div class="formValidation user fullwidth"> You have to assign the project to a person.</div>
    </div>
</div>

<?php if ( $blComplete === true && $resource_allocator && $inDepartment ): ?>
    <div class="form-group">
        <label class="col-sm-2 control-label">Reason of rejection</label>

        <div class="col-sm-10">
            <textarea placeholder="Fill it in only if you reject the request" class="form-control reason" type="text" name="form[reason]"></textarea>

            <div class="formValidation rejection fullwidth"> You have to specify the reason of the rejection.</div>
        </div>
    </div>
<?php endif; ?>

<?php if ( isset ($project['data_json']['job']['rejected_by']) ): ?>
    <div class="form-group">
        <label class="col-sm-2 control-label">Reason of rejection</label>

        <div class="col-sm-10">
            <div class="col-sm-10  full-width " style="padding-top: 7px;">
                <div class="full-width" style="max-height:60px; overflow: hidden">
                    <?= $project['data_json']['job']['reject_reason'] ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="/FormBuilder/public/js/getTasks.js" type="text/javascript"></script>


<script>

    setTimeout (function ()
    {
        $ (".events").eq (0).children ().children ().children ("a.selected").click ();

    }, 200);

<?php if ( $blComplete === true ): ?>
        $ (".changeStatus").show ();
<?php endif; ?>

<?php if ( $blComplete !== true ): ?>
        $ (".changeStatus").hide ();
<?php endif; ?>
</script>