<?php
$labels = array(
    7 => "label label-primary",
    5 => "label label-success",
    4 => "label label-danger",
    3 => "label label-warning"
);

if ( !empty ($arrProjects) )
{
    foreach ($arrProjects as $arrProject):
        $arrJSON = json_decode ($arrProject['step_data'], true);

        $statusId = 7;
        $status = "NEW PROJECT";
        $claimed = "";
         if(isset($arrJSON['workflow_data']['elements'])) {
            foreach ($arrJSON['workflow_data']['elements'] as $key => $arrElement):

                if (isset($arrJSON['job']['completed_by'])) {
                    $status = "COMPLETED";
                    $statusId = 3;
                } elseif (isset($arrJSON['job']['rejected_by'])) {
                    $status = "REJECTED";
                    $statusId = 4;
                }

                if (isset($arrJSON['audit_data']['elements'][$key]['steps'])) {
                    foreach ($arrJSON['audit_data']['elements'][$key]['steps'] as $step) {
                        
                        if (isset($step['claimed']) && !empty($step['claimed'])) {
                            $claimed = $step['claimed'];

                            if($statusId != 4 && $statusId != 3) {
                                $status = "IN PROGRESS";
                                $statusId = 5;
                            }
                        }
                    }
                }

            endforeach;
        }

        ?>
        <li class="openProject col-lg-11" data-target="#viewProject" data-toggle="modal" id="<?= $arrProject['id'] ?>">
            <div class="">
                <div class="project-status col-lg-4 col-lg-4 col-xs-6 col-sm-6">
                    <h3><div id="2" href="project_detail.html"><?= $arrJSON['job']['name'] ?></div></h3>
                    <span class="<?= $labels[$statusId] ?>"><?= $status ?></span>

                    <span class="label label <?= $arrProject['style'] ?>"><?= $arrProject['priority_name'] ?></span>


                    <br><small>Created  <?= $arrJSON['job']['date_created'] ?></small>
                    <br><small>Due Date <?= $arrJSON['job']['dueDate'] ?></small>

                    <br><br>
                </div>


                <div class="row">
                    <div class="col-lg-2 col-lg-4 col-xs-6 col-sm-6  pull-right" style=" margin-bottom: 10px;">

                        <div class="col-lg-12 col-xs-4 col-sm-3 pull-right text-center" style="margin-bottom: 10px;">
                            <i class="fa fa-comments icon-medium"></i>

                            <?php if ( $arrProject['commentCount'] > 0 ): ?>
                                <span class="label label-warning label_small_icon"><?= $arrProject['commentCount'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-12 col-xs-4 col-sm-3 pull-right text-center">
                            <i class="fa fa fa-paperclip icon-medium"></i>

                            <?php if ( $arrProject['attachmentCount'] > 0 ): ?>
                                <span class="label label-warning label_small_icon"><?= $arrProject['attachmentCount'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="project-users col-lg-5 col-xs-12 col-sm-6 pull-right">
                        <div class="row">
                            <?php if ( !empty ($claimed) ) : ?>
                                <div class="project-requestor col-lg-4 col-xs-4 text-center">
                                    <div class="img-circle-wrapper-small" >
                                        <div class="backgound-image-trick-small" style="background-image : url('/FormBuilder/public/img/users/<?= $claimed ?>.jpeg')" ></div>
                                    </div>
                                    <small><?= $claimed ?> <br>Claimed</small>
                                </div>
                                <?php
                            endif;

                            if ( $statusId == 4 && isset ($arrJSON['job']['rejected_by']) ) :
                                ?>
                                <div class="project-requestor col-lg-4 col-xs-4 text-center">
                                    <div class="img-circle-wrapper-small">
                                        <div class="backgound-image-trick-small" style="background-image : url('/FormBuilder/public/img/users///<?= $arrJSON['job']['rejected_by'] ?>.jpeg')" ></div>
                                    </div>
                                    <small><?= $arrJSON['job']['rejected_by'] ?><br>Rejected</small>
                                </div>

                                <?php
                            endif;

                            if ( $statusId == 3 && isset ($arrJSON['job']['completed_by']) ) :
                                ?>
                                <div class="project-requestor col-lg-4 col-xs-4 text-center">
                                    <div class="img-circle-wrapper-small">
                                        <div class="backgound-image-trick-small" style="background-image : url('/FormBuilder/public/img/users///<?= $arrJSON['job']['completed_by'] ?>.jpeg')" ></div>
                                    </div>
                                    <small><?= $arrJSON['job']['completed_by'] ?><br>Completed</small>
                                </div>

                                <?php
                            endif;

                            if ( isset ($arrJSON['assigned_for']) ):
                                $assignedFor = explode (",", $arrJSON['assigned_for']);

                                if ( count ($assignedFor) == 1 )
                                {
                                    $Imageusername = $assignedFor[0];
                                    $username = $assignedFor[0];
                                }
                                else
                                {
                                    $username = "<i alt=\"multiple users\" class=\"fa fa-users\"></i> Multiple Users";
                                    $Imageusername = "multiuser";
                                }
//
                                if ( !empty ($arrJSON["assigned_for"]) ) :
                                    ?>
                                    <!--                                    <div class="project-requestor col-lg-4 col-xs-4 text-center">
                                                                            <div class="img-circle-wrapper-small">
                                                                                <div class="backgound-image-trick-small" style="background-image : url('/FormBuilder/public/img/users/////<?= $Imageusername ?>.jpeg')" ></div>
                                                                            </div>
                                                                            <small><?= $username ?><br>Assignee</small>
                                                                        </div>-->
                                    <?php
                                endif;
                            endif;
                            ?>
                        </div>
                    </div>

                </div>
                <div class="col-lg-12">
                    <br>
        <?= $arrJSON['job']['description'] ?>
                </div>
        </li>



        <?php
    endforeach;
}
else
{
    echo '<h2>No projects have been added';
}
?>
