<?php
class Audit extends BaseModel
{
    protected $table = "audit";
    
    public function getAudit ($projectId, $auditType)
    {
        return $this->_select ($this->table, array(), array("project_id" => $projectId, "audit_type" => $auditType));
    }
    
    public function saveAudit ($step, $projectId, $auditType)
    {
        $this->_insert ($this->table, array("step_completed" => $step, "project_id" => $projectId, "audit_date" => date ('Y-m-d H:i:s'), "username" => $_SESSION['user']['username'], "audit_type" => $auditType));
    }
    
    public function getFullAudit ($projectId = NULL, $step = NULL)
    {
        $arrParameters = array();

        $query = "SELECT a.*, p.project_name, ws.step_name, sd.* 
                    FROM audit a
                    INNER JOIN projects p ON p.id = a.project_id
                    LEFT JOIN workflow_steps ws ON ws.step_id = a.step_completed
                    LEFT JOIN step_data sd ON sd.step_id = a.step_completed AND sd.project_id = a.project_id
                    WHERE 1=1";

        if ( $projectId != NULL )
        {
            $query .= " AND a.project_id = ?";
            $arrParameters[] = $projectId;
        }

        if ( $step != NULL )
        {
            $query .= " AND a.step_completed = ?";
            $arrParameters[] = $step;
        }

        $query .= " ORDER BY audit_date DESC";

        return $this->_query ($query, $arrParameters);
    }
    
    public function stepComplete ($step, $workflow, $projectId)
    {
        $arrResult = $this->_select ($this->table, array(), array("step_completed" => $step, "audit_type" => 2, "project_id" => $projectId));

        if ( !empty ($arrResult) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}

