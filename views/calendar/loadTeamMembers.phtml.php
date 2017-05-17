<?php
$i = 0;
echo '<div class="row">'
 . '<div class="col-lg-12 pull-left">';
foreach ($arrUsers as $arrTeam) {
    echo '<div class="col-md-4">';
    ?>
    <h3><?= $arrTeam['team_name'] ?></h3>

    <div class="row">

        <?php
        $intCount2 = 0;
        foreach ($arrTeam['users'] as $arrUser):
            ?>

            <div class="col-md-4">
                <div class="project-requestor col-lg-4 col-xs-4 text-center">
                    <div class="img-circle-wrapper-small" user="<?= $arrUser['username'] ?>">
                        <div class="backgound-image-trick-small" style="background-image : url('/FormBuilder/public/img/users/ <?= $arrUser['username'] ?>.jpeg')" ></div>
                    </div>

                    <small><?= $arrUser["username"] ?><br>Manager</small>
                </div>
            </div>
            <?php
            $intCount2++;

            if ( $intCount2 % 4 == 0 )
            {
                echo '</div><div class="row">';
            }


        endforeach;
        ?>
    </div>

    </div>
    <?php
    $i++;
    if ( $i % 3 == 0 )
    {
        echo '</div></div><div class="row"><div class="col-lg-12 pull-left">';
    }
}
?>
</div></div><br/>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="order_id">Department</label>
            <select id="departmentId" name="departmentId" class="form-control">
                <option value="">Select One</option>
                <?php
                foreach ($departments as $department):
                    $selected = $deptId == $department['id'] ? 'selected="selected"' : '';
                    ?>
                    <option value="<?= $department['id'] ?>" <?= $selected ?>><?= $department['department'] ?></option>

<?php endforeach; ?>
            </select>
        </div>
    </div>
</div>