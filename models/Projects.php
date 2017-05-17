<?php

class Projects extends BaseModel
{

    protected $table = "projects";
    public $id;
    public $object;

    public function searchDepartments ($searchText)
    {
        $sql = "SELECT * FROM departments WHERE department LIKE ? ORDER BY department ASC";
        $arrParameters = array('%' . $searchText . '%');
        $arrResultSet = $this->_query ($sql, $arrParameters);

        return $arrResultSet;
    }

    public function updateStatus ($projectId, $userId, $statusTo)
    {
        if ( $statusTo == 3 )
        {
            $arrParameters = array("project_status" => $statusTo, "completed_by" => $userId, "completed_date" => date ('Y-m-d H:i:s'));
        }
        elseif ( $statusTo == 5 )
        {
            $arrParameters = array("project_status" => $statusTo);
        }
        elseif ( $statusTo == 6 )
        {
            $arrParameters = array("assigned_by" => $userId, "project_status" => $statusTo);
        }
        else
        {
            $arrParameters = array("project_status" => $statusTo, "date_started" => date ('Y-m-d H:i:s'));
        }

        $this->_update ($this->table, $arrParameters, array("id" => $projectId));
    }

    public function doUpdate ($arrFields, $projectId)
    {
        $this->_update ($this->table, $arrFields, array("id" => $projectId));
    }

    public function getAllProjects ($arrSearch = '')
    {
        $arrParameters = array();

        $sql = "SELECT p.*, 
                        pri.name AS priority_name, 
                        pri.style, 
                        COUNT(a.id) AS attachmentCount, 
                        COUNT(c.id) AS commentCount 
                FROM projects p 
                INNER JOIN priority pri ON pri.id = p.priority
                LEFT JOIN attachments a ON a.source_id = p.id
                INNER JOIN departments d ON d.id = p.department_id
                LEFT JOIN comments c ON c.source_id = p.id";

        if ( is_array ($arrSearch) )
        {
            $sql .= " WHERE 1=1 ";

            if ( isset ($arrSearch['id']) && !empty ($arrSearch['id']) && $arrSearch['id'] != null )
            {
                $sql .= " AND p.id = ?";
                $arrParameters[] = $arrSearch['id'];
            }

            if ( isset ($arrSearch['status']) && !empty ($arrSearch['status']) )
            {
                $qMarks = str_repeat ('?,', count ($arrSearch['status']) - 1) . '?';
                $sql .= " AND p.project_status IN(" . $qMarks . ")";
                foreach ($arrSearch['status'] as $status) {
                    $arrParameters[] = $status;
                }
            }

            if ( isset ($arrSearch['priority']) && !empty ($arrSearch['priority']) )
            {
                $sql .= " AND p.priority = ?";
                $arrParameters[] = $arrSearch['priority'];
            }

//            if(isset($arrSearch['assigned_to']) && !empty($arrSearch['assigned_to'])) {
//                $sql .= " AND step_data LIKE ?";
//                $arrParameters[] = '%'.$arrSearch['assigned_to'].'%';
//            }

            if ( isset ($arrSearch['dept']) && is_numeric ($arrSearch['dept']) )
            {
                $sql .= " AND p.department_id = ?";
                $arrParameters[] = $arrSearch['dept'];
            }

//            if(isset($arrSearch['workflow']) && is_numeric ($arrSearch['workflow'])) {
//                $sql .= " AND p.workflow = ?";
//                $arrParameters[] = $arrSearch['workflow'];
//            }
        }

        $sql .= " GROUP BY p.id";
        $sql .= " ORDER BY id DESC";

        //die($this->parms($sql, $arrParameters));
        $arrResultSet = $this->_query ($sql, $arrParameters);
        
        foreach ($arrResultSet as $key => $arrResult) {
            
            $stepData = json_decode($arrResult['step_data'], true);
            
            $arrWorkflowData = $this->_select("workflow.workflow_data", array(), array("object_id" => $arrResult['id']));
            
            $stepData['workflow_data'] = json_decode($arrWorkflowData[0]['workflow_data'], true);
            $stepData['audit_data'] = json_decode($arrWorkflowData[0]['audit_data'], true);
            
            $arrResultSet[$key]['step_data'] = json_encode($stepData);
        }

        return $arrResultSet;
    }

    public function getAllTeams ()
    {
        $sql = "SELECT * FROM user_management.teams ORDER BY team_id ASC";
        $arrResultSet = $this->_query ($sql);

        return $arrResultSet;
    }

    public function getAllUsers ()
    {
        $sql = "SELECT * FROM user_management.poms_users ORDER BY team_id, username ASC";
        $arrResultSet = $this->_query ($sql);

        return $arrResultSet;
    }

    public function doSelect ($table, $arrFields, $arrWhere = array(), $arrOrderBy = array())
    {
        return $this->_select ($table, $arrFields, $arrWhere, $arrOrderBy);
    }

    public function saveNewProject ($arrData, $file = '')
    {
        $arrJson = array(
            "date_added" => date ('Y-m-d H:i:s'),
            "added_by" => $_SESSION['user']['username'],
            "due_date" => date ("Y-m-d", strtotime ($arrData['dueDate'])),
            "priority" => $arrData['priority']
        );

        $arrParameters = array(
            "department_id" => $arrData['deptId'],
            "project_name" => $arrData['projectName'],
            "description" => $arrData['projectDescription'],
            "project_status" => 0,
            "request_type_id" => $arrData['workflow'],
            "priority" => $arrData['priority'],
            "step_data" => json_encode ($arrJson)
        );

        $id = $this->_insert ($this->table, $arrParameters);

        if ( !empty ($file) )
        {
            $this->_update ($this->table, array("file_uploaded" => $file), array("id" => $id));
        }
    }

    public function getAllDepartments ()
    {
        return $this->_select ("departments", array(), array(), array("department" => "ASC"));
    }

    public function getAllWorkflows ()
    {
        return $this->_select ("workflows", array(), array(), array("id" => "ASC"));
    }

    public function setId ($id)
    {
        $this->id = $id;
        $arrProject = $this->getAllProjects (array("id" => $id));
        $this->object = json_decode ($arrProject[0]['step_data'], TRUE);
    }

}
