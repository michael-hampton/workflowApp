<table class="table table-striped">
    <thead>
        <tr>
            <th>Status</th>
            <th>Title</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Comments</th>
        </tr>
    </thead>
                                        
    <tbody>
        <?php foreach ($arrResults as $arrResult): ?>                
            <tr>
                <td>
                    <?php
                    switch ($arrResult['task_status']) {
                        case "S":
                            echo '<span action="finish" style="cursor:pointer;" startTask="'.$arrResult['task_id'].'" class="label label-danger actionTask">Finish</span>';
                            break;

                        case "C":
                            echo '<span class="label label-warning">Completed</span>';
                            break;

                        case "NS":
                            echo '<span action="start" startTask="'.$arrResult['task_id'].'" class="label label-primary actionTask">Start Task</span>';
                            break;

                        case 4:
                            echo '<span class="label">Rejected</span>';
                            break;

                        case 5:
                            echo '<span class="label label-success">Accepted</span>';
                            break;

                        case 6:
                            echo '<span class="label label-info">Assigned</span>';
                            break;
                    }
                    ?>

                </td>

                <td>
                   <?= $arrResult['task_name'] ?>
                </td>

                <td>
                    <?= $arrResult['date_started'] ?>
                </td>

                <td>
                    <?= $arrResult['date_due'] ?>
                </td>

                <td>
                    <p class="small">
                        <?= $arrResult['task_description'] ?>
                    </p>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
