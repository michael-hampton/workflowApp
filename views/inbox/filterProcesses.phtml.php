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

            <li workflowid="<?= $arrWorkflow['workflow_id'] ?>" class="list-group-item fist-item" style="border-bottom: 1px dotted #CCC;">
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