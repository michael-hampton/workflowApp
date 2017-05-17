<?php

class Scheduler extends BaseModel
{

    protected $table = "teams";

    public function getAllTeams ($teamId = null, $deptId = null)
    {
        $arrParameters = array();

        $query = "SELECT t.*, u.* FROM teams t
                    INNER JOIN team_users tu ON tu.team_id = t.team_id
                    INNER JOIN user_management.poms_users u ON u.usrid = tu.user_id";

        if ( $teamId != NULL )
        {
            $query .= " WHERE team_id = ?";
            $arrParameters[] = $teamId;
        }

        if ( $deptId != NULL )
        {
            $query .= " AND dept_id = ?";
            $arrParameters[] = $deptId;
        }

        $query .= " ORDER BY team_id ASC";

        $results = $this->_query ($query, $arrParameters);
        
        return $results;
    }
    
    public function getData($type, $arrFilters = array())
    {
        switch ($type) {
            case "task_manager":
                $arrParameters = array();
                
                $query = "SELECT p.* FROM `projects` p
                WHERE 1=1";
                
                if(isset($arrFilters['deptId']) && $arrFilters['deptId'] != null) {
                    $query .= " AND p.department_id = ?";
                    $arrParameters[] = $arrFilters['deptId'];
                }
                
                if(isset($arrFilters['user']) && $arrFilters['user'] != NULL) {
                    $query .= " AND step_data LIKE ?";
                    $arrParameters[] = '%'.$arrFilters['user'].'%';
                }
                return $this->_query($query, $arrParameters);
            break;    
        }
        
    }
    
    public function setData()
    {
        
    }

}
