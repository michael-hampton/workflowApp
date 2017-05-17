<?php
foreach ($attachmnets as $jobCommentsResultKeys => $jobCommentsResultValues) : ?>
    <div style="width: 100%; float: left; border-top: 1px solid rgb(221, 221, 221); padding-top: 11px;">

        <div style="width: 46%; float: left; padding: 3px; font-weight: bold;">
            <a style=" font-size: 12px; font-weight: normal;" href="/index/downloadAttachment/<?= $jobCommentsResultValues["source_id"] ?>">
                <div style="float: left; margin: 0px 3px 7px; background-color: rgb(66, 139, 202); color: rgb(255, 255, 255); padding: 2px; border: 1px solid rgb(66, 139, 202); border-radius: 5px;">
                    <?= $jobCommentsResultValues["filename"] . " (pdf) " ?>
                </div>
            </a>
        </div>

        <div style="width: 25%; float: left; padding: 3px;  font-weight: bold; text-align: right;">
            <?= $jobCommentsResultValues["uploaded_by"] ?>
        </div>

        <div style="width: 25%; float: left; padding: 3px;">
            <?= $jobCommentsResultValues["date_uploaded"] ?>
        </div>
    </div>
<?php endforeach; ?>

