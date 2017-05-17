<?php

class Tasks extends Request
{

    public function __construct ($projectId = null)
    {
        parent::__construct ($projectId);
    }

    public function updateStatus ($projectId, $status)
    {
        $data = $this->doSelect ("projects", ['step_data'], ["id" => $projectId]);
        $data = json_decode ($data[0]['step_data'], true);
        $currentStep = $this->getCurrentStep ($projectId);

        $data['steps'][$currentStep]['status'] = $status;

        echo '<pre>';
        print_r ($data);
        die;

        $this->doUpdate ("projects", ['step_data' => json_encode ($data)], array("id" => $projectId));
    }

    public function getTasks ($arrPostedData)
    {
        $sql = "SELECT tm.*, s.status_description 
                FROM task_manager tm
                INNER JOIN task_status s ON s.status_id = tm.task_status
                ";
        $arrParameters = array();

        if ( is_array ($arrPostedData) )
        {
            $sql .= " WHERE 1=1";

            if ( isset ($arrPostedData['project_id']) && is_numeric ($arrPostedData['project_id']) )
            {
                $sql .= " AND tm.project_id = ?";
                $arrParameters[] = $arrPostedData['project_id'];
            }
        }

        $sql .= " ORDER BY tm.date_due DESC";

        //die($this->parms ($sql, $arrParameters));
        $arrResultSet = $this->_query ($sql, $arrParameters);

        return $arrResultSet;
    }

    public function doUpdate ($table, $arrFields, $arrWhere)
    {
        $this->_update ($table, $arrFields, $arrWhere);
    }

    public function doInsert ($table, $arrFields)
    {
        $this->_insert ($table, $arrFields);
    }

    public function doSelect ($table, $arrFields, $arrWhere = array(), $arrOrderBy = array())
    {
        return $this->_select ($table, $arrFields, $arrWhere, $arrOrderBy);
    }

    public function getSteps ($workflow)
    {
        return $this->_select ("workflow_steps", array(), array("workflow_id" => $workflow));
    }

    public function getCurrentStep ($projectId)
    {
        return $this->objProjectData['step'];
    }

    public function getStepData ($projectId, $step, $identifier = null)
    {
        if(isset($this->data['steps'][$step]['data'][$identifier]) && !empty($this->data['steps'][$step]['data'][$identifier]))
        return $this->data['steps'][$step]['data'][$identifier];
    }

    public function saveStepData ($step, $projectId, $arrData, $status)
    {
        $objAudit = new Audit();
        $objTasks = new Tasks();

        $arrJSON = $objTasks->doSelect ("projects", array("step_data"), array("id" => $projectId));
        $arrJSON = json_decode ($arrJSON[0]['step_data'], TRUE);
        $arrJSON['steps'][$step]['data'] = $arrData['form'];
        $arrJSON['steps'][$step]['status'] = $status;
        $json = json_encode ($arrJSON);

        if ( $status == 1 )
        {
            $objAudit->saveAudit ($step, $projectId, 2);

            $this->setCompleted (1);
        }

        $this->doUpdate ("projects", array("step_data" => $json), array("id" => $projectId));
    }

    public function setWorkflowId ($workflow)
    {
        $this->setWorkflow ($workflow);
    }

}
