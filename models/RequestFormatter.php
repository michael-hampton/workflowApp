<?php

class RequestFormatter extends BaseModel
{

    public $arrUsers = array();
    public $arrAllUsers = array();
    public $arrPriorities = array();
    public $objProjects;
    public $arrProjects = array();
    public $arrStatuses = array();
    public $arrTeams = array();

    public function __construct ($id = null)
    {
        parent::__construct ();
        $this->objProjects = new Projects();

        $this->arrUsers = $this->objProjects->getAllUsers ();
        $this->arrTeams = $this->objProjects->getAllTeams ();

        if ( $id != null )
        {
            $this->arrProjects = $this->objProjects->getAllProjects (array("id" => $id));
        }
        else
        {
            $this->arrProjects = $this->objProjects->getAllProjects (array());
        }

        $this->getUsers ();
        $this->getPriorities ();
        $this->getStatuses ();
    }

    public function getSteps ($requestId)
    {
        return $this->_query ("SELECT * from kanban_columns WHERE request_id = ?", [$requestId]);
    }

    private function getStatuses ()
    {
        $this->arrStatuses['ABANDONED'] = "label label-danger";
        $this->arrStatuses['ACCEPTED'] = "label label-primary";
        $this->arrStatuses['REJECTED'] = "label label-danger";
        $this->arrStatuses['NEW'] = "label label-success";
        $this->arrStatuses['IN PROGRESS'] = "label label-success";
        $this->arrStatuses['ASSIGNED'] = "label label-success";
        $this->arrStatuses['AUTO_ASSIGN'] = "label label-success";
        $this->arrStatuses['COMPLETE'] = "label label-info";
    }

    private function getUsers ()
    {
        $intCount = 0;
        foreach ($this->arrUsers as $arrUser) {

            if ( !empty ($arrUser['img_src']) )
            {
                $this->arrAllUsers[$arrUser['username']]['uid'] = $arrUser['usrid'];
                $this->arrAllUsers[$arrUser['username']]['name'] = $arrUser['username'];
                $this->arrAllUsers[$arrUser['username']]['src'] = $arrUser['img_src'];
                $this->arrAllUsers[$arrUser['username']]['teamId'] = $arrUser['team_id'];

                $intCount++;
            }
        }
    }

    private function getPriorities ()
    {
        $arrPriorities = $this->objProjects->doSelect ("priority", array());

        foreach ($arrPriorities as $arrPriority) {
            $this->arrPriorities[$arrPriority['id']]['id'] = $arrPriority['id'];
            $this->arrPriorities[$arrPriority['id']]['name'] = $arrPriority['name'];
            $this->arrPriorities[$arrPriority['id']]['label'] = "label " . $arrPriority['style'];
        }
    }

    public function getRequestTypes ()
    {
        return $this->_query ("SELECT  
                                    k.`request_id`, r.request_type 
                                FROM workflow.request_types r
                                INNER JOIN task_manager.kanban_columns k ON k.request_id = r.request_id
                                GROUP BY k.request_id");
    }

    public function format ($type, $arrData, $requestType)
    {
        $arrTasks = array();
        $intCount = 0;

        $prevDate = null;
        switch ($type) {
            case "task_manager":

                if ( $requestType == "assigned" )
                {
                    foreach ($arrData as $task) {

                        $stepData = json_decode ($task['step_data'], true);

                        if ( $stepData['date_added'] != '0000-00-00 00:00:00' && !empty ($stepData['team_id']) )
                        {
                            if ( strpos ($stepData['assigned_for'], ',') !== false )
                            {
                                $user = explode (',', $stepData['assigned_for']);
                                $user = $user[0];
                            }
                            else
                            {
                                $user = $stepData['assigned_for'];
                            }


                            $teamMember = $this->getTeam ($user);

                            $date_added = date ('Y-m-d', strtotime ($stepData['date_added']));
                            $date_added = strtotime ($date_added);

                            $due_date = date ('Y-m-d', strtotime ($stepData['due_date']));
                            $due_date = strtotime ($due_date);


                            $timeDiff = abs ($due_date - $date_added);

                            $days = $timeDiff / 86400;  // 86400 seconds in one day
                            // and you might want to convert to integer
                            $days = intval ($days);

                            if ( !empty ($teamMember) )
                            {

                                $key = date ("Y-m-d", strtotime ($stepData['date_added']));
                                // Start Time

                                $startTime = date ("H:i:s", strtotime ($stepData['date_added']));
                                $startTime = $startTime == "00:00:00" ? $startTime = "08:00" : date ("H:i", strtotime ($startTime));

                                $endTime = date ("H:i:s", strtotime ($stepData['due_date']));
                                $endTime = $endTime == "00:00:00" ? $endTime = "17:00" : date ("H:i", strtotime ($endTime));

                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['title'] = $task['project_name'];
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['days'] = $days;
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['id'] = $task['id'];
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['start_time'] = $startTime;
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['end_time'] = $endTime;
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['class'] = $task['priority'];
                                $arrTasks[$key][$teamMember[0]['team_name']][$intCount]['assigned_for'] = $stepData['assigned_for'];

                                //$arrTasks[$task['date_started']][$teamMember[0]['team_name']][$intCount]['end_time'] = $task['id'];

                                $team = $teamMember[0]['team_name'];
                            }


                            //if(!empty($teamMember) && $teamMember[0]['team_name'] != $team) {
                            $intCount++;
                            //}
                        }
                    }
                }
                else
                {
                    foreach ($arrData as $task) {
                        $stepData = json_decode ($task['step_data'], true);

                        if ( empty ($stepData['team_id']) )
                        {

                            $date_added = date ('Y-m-d', strtotime ($stepData['date_added']));
                            $date_added = strtotime ($date_added);

                            $due_date = date ('Y-m-d', strtotime ($stepData['due_date']));
                            $due_date = strtotime ($due_date);


                            $timeDiff = abs ($due_date - $date_added);

                            $days = $timeDiff / 86400;  // 86400 seconds in one day

                            $days = 1;

                            $acceptedBy = isset ($stepData['accepted_by']) ? $stepData['accepted_by'] : '';

                            $arrTasks[$intCount]['title'] = $task['project_name'];
                            $arrTasks[$intCount]['days'] = $days;
                            $arrTasks[$intCount]['id'] = $task['id'];
                            $arrTasks[$intCount]['class'] = $task['priority'];
                            $arrTasks[$intCount]['accepted_by'] = $acceptedBy;

                            $intCount++;
                        }
                    }
                }

                break;
        }

        return $arrTasks;
    }

    private function getTeam ($user)
    {
        $sql = "SELECT t.* FROM user_management.poms_users u 
                INNER JOIN user_management.teams t ON t.team_id = u.team_id
                WHERE u.username = ?";

        return $this->_query ($sql, array($user));
    }

    private function buildUserList ($arrData, $deptId = null)
    {
        $arrKanbanUsers = array();
        $intCount = 0;

        if ( $deptId !== null )
        {
            foreach ($this->arrUsers as $arrUser) {

                $arrTeam = $this->getTeam ($arrUser['username']);

                if ( isset ($arrTeam[0]['dept_id']) && $deptId == $arrTeam[0]['dept_id'] )
                {

                    if ( $arrUser['img_src'] != "" )
                    {

                        $arrKanbanUsers[$intCount]['uid'] = $arrUser['usrid'];
                        $arrKanbanUsers[$intCount]['name'] = $arrUser['username'];
                        $arrKanbanUsers[$intCount]['src'] = $arrUser['img_src'];
                        $arrKanbanUsers[$intCount]['teamId'] = $arrUser['team_id'];

                        if ( isset ($arrData['user']) && isset ($arrData['filter']) )
                        {
                            if ( in_array ($arrUser['usrid'], $arrData['user']) )
                            {
                                $arrKanbanUsers[$intCount]['class'] = "selected";
                            }
                        }

                        $intCount++;
                    }
                }
            }

            return $arrKanbanUsers;
        }

        foreach ($this->arrUsers as $arrUser) {
            if ( $arrUser['img_src'] != "" )
            {
                $arrKanbanUsers[$intCount]['uid'] = $arrUser['usrid'];
                $arrKanbanUsers[$intCount]['name'] = $arrUser['username'];
                $arrKanbanUsers[$intCount]['src'] = $arrUser['img_src'];
                $arrKanbanUsers[$intCount]['teamId'] = $arrUser['team_id'];

                if ( isset ($arrData['user']) && isset ($arrData['filter']) )
                {
                    if ( in_array ($arrUser['usrid'], $arrData['user']) )
                    {
                        $arrKanbanUsers[$intCount]['class'] = "selected";
                    }
                }

                $intCount++;
            }
        }

        return $arrKanbanUsers;
    }

    private function getProjectStatus ($arrProject)
    {
        $status = 7;

        if ( isset ($arrProject['elements']) )
        {
            foreach ($arrProject['elements'] as $arrElements) {
                foreach ($arrElements as $arrElement) {
                    if ( isset ($arrElement['steps']) && !empty ($arrElement['steps']) )
                    {
                        foreach ($arrElement['steps'] as $arrStep) {
                            if ( isset ($arrStep['claimed']) )
                            {
                                $status = 8;
                            }
                        }
                    }
                }
            }
        }

        if ( isset ($arrProject['job']['completed_by']) )
        {
            $status = 9;
        }

        if ( isset ($arrProject['job']['date_rejected']) )
        {
            $status = 9;
        }

        return $status;
    }

    public function formatKanbanData ($arrData, $type = null, $system = null)
    {
        $arrKanbanUsers = array();
        $arrAllUsers = array();
        $arrUsedUsers = array();
        $deptId = null;

        /*         * ******************* Teams ************************************** */
        $intCount = 0;
        $deptId = null;

        if ( isset ($arrData['requestType']) )
        {
            $objWorkflowCollectionFactory = new \BusinessModel\WorkflowCollectionFactory();
            $result = $objWorkflowCollectionFactory->getCategory ($arrData['requestType']);
            $deptId = $result->getDeptId ();
        }

        $arrKanbanUsers['users'] = $this->buildUserList ($arrData, $deptId);

        if ( $deptId !== null )
        {
            foreach ($this->arrTeams as $teamKey => $arrTeam) {
                if ( $arrTeam['dept_id'] != $deptId )
                {
                    unset ($this->arrTeams[$teamKey]);
                }
            }
        }

        $arrKanbanUsers['teams'] = $this->arrTeams;

        foreach ($this->arrProjects as $key => $arrProject) {

            $intSkipCount = 0;

            $data = json_decode ($arrProject['step_data'], true);

            $workflowData = $this->_select ("workflow.workflow_data", array(), array("object_id" => $arrProject['id']));

            if ( !isset ($workflowData[0]['workflow_data']) || empty ($workflowData[0]['workflow_data']) )
            {
                $intSkipCount++;
            }
            else
            {
                $arrWorkflowData = json_decode ($workflowData[0]['workflow_data'], true);
                $arrAuditData = json_decode ($workflowData[0]['audit_data'], true);

                if ( empty ($arrWorkflowData) )
                {
                    $intSkipCount++;
                }

                $arrValues = array_values ($arrWorkflowData['elements']);
                $parent = array_shift ($arrValues);

                $status = $parent['current_step'];
            }

            $userCount = 0;

            /*             * ************************ Assigned Users ************************** */
            $arrUserIds = array();
            $oCase = new \BusinessModel\Cases();


            if ( isset ($data['scheduler']['backlogs']) && !empty ($data['scheduler']['backlogs']) )
            {
                foreach ($data['scheduler']['backlogs'] as $backlogId => $backlog) {

                    $participants = $oCase->getUsersParticipatedInCase ($arrProject['id']);

                    foreach ($participants as $participant) {
                        $arrUserIds[] = $this->arrAllUsers[$participant]['uid'];
                    }
                }
            }

            $arrCases = array();

            $arrUserIds[] = $this->arrAllUsers[$data['scheduler']['added_by']]['uid'];

            if ( $type == "task" )
            {
                foreach ($arrKanbanUsers['users'] as $key => $arrKanbanUser) {
                    if ( in_array ($arrKanbanUser['uid'], $arrUserIds) )
                    {
                        $arrKanbanUsers['users'][$key]['class'] = "selected";
                    }
                }
            }

            /**             * ************************ Filters **************************** */
            if ( isset ($arrData['filter']) && $arrData['filter'] == "true" )
            {
                $intUserHasSprint = 0;

                if ( isset ($arrData['user']) && is_array ($arrData['user']) )
                {
                    foreach ($arrData['user'] as $userId) {
                        if ( is_numeric ($userId) && !in_array ($userId, $arrUserIds) )
                        {
                            $intUserHasSprint++;
                        }
                    }

                    if ( $intUserHasSprint > 0 && $intUserHasSprint == count ($arrData['user']) )
                    {
                        $intSkipCount++;
                    }
                }

                if ( isset ($arrData['priority']) && is_numeric ($arrData['priority']) && $data['scheduler']['priority'] != $arrData['priority'] )
                {
                    $intSkipCount++;
                }

                if ( isset ($arrData['requestType']) && is_numeric ($arrData['requestType']) )
                {
                    if ( isset ($data['elements']) )
                    {
                        $arrKanbanUsers['steps'] = $this->getSteps ($arrData['requestType']);

                        foreach ($data['elements'] as $elementId => $element) {
                            $objCaseData = $oCase->getCaseInfo ($arrProject['id'], $elementId);
                            $data['scheduler']['backlogs'][$elementId]['case'] = $objCaseData;

                            $arrCases[$arrProject['id'][$elementId]] = $objCaseData;

                            if ( $objCaseData instanceof Elements )
                            {
                                $currentStep = $objCaseData->getCurrentStepId ();
                                $requestId = $objCaseData->getRequestId ();

                                if ( !is_numeric ($requestId) || trim ($requestId) == "" || $requestId != $arrData['requestType'] )
                                {
                                    $intSkipCount++;
                                }
                            }

                            unset ($objCaseData);
                        }
                    }
                }
            }

            if ( $intSkipCount == 0 )
            {
                $arrKanbanUsers['id'] = $arrProject['id'];
                $arrKanbanUsers['title'] = $data['job']['name'];

                /*                 * ***************** Priorities ************************************ */
                $arrKanbanUsers['priority']['id'] = $data['scheduler']['priority'];
                $arrKanbanUsers['priority']['name'] = $this->arrPriorities[$data['scheduler']['priority']]['name'];
                $arrKanbanUsers['priority']['label'] = $this->arrPriorities[$data['scheduler']['priority']]['label'];

                $arrKanbanUsers['url'] = "test.com";
                $arrKanbanUsers['body'] = $data['job']['description'];

                /*                 * ********************** Comments ************************** */
                $arrComments = $oCase->getCaseNotes ($arrProject['id'], $_SESSION['user']['usrid']);

                $commentCount = 0;
                if ( !empty ($arrComments) )
                {
                    foreach ($arrComments['data'] as $arrComment) {

                        $arrKanbanUsers['comments'][$commentCount]['body'] = $arrComment['note_content'];
                        $arrKanbanUsers['comments'][$commentCount]['datetime'] = $arrComment['note_date'];
                        $arrKanbanUsers['comments'][$commentCount]['user'] = $arrComment['usr_uid'];

                        $commentCount++;
                    }
                }

                //$arrKanbanUsers['tags'][0] = array("test tag");
                $arrKanbanUsers['owner'] = $data['scheduler']['added_by'];

                /*                 * *************** Columns ************************************* */
                $count = isset ($arrKanbanUsers['column_' . $status]) ? count ($arrKanbanUsers['column_' . $status]) + 1 : 0;
                $arrKanbanUsers['column_' . $status][$count]['type'] = 1;
                $arrKanbanUsers['column_' . $status][$count]['id'] = $arrProject['id'];
                $arrKanbanUsers['column_' . $status][$count]['title'] = $data['job']['name'];
                $arrKanbanUsers['column_' . $status][$count]['priority']['id'] = $data['scheduler']['priority'];
                $arrKanbanUsers['column_' . $status][$count]['priority']['name'] = $this->arrPriorities[$data['scheduler']['priority']]['name'];
                $arrKanbanUsers['column_' . $status][$count]['priority']['label'] = $this->arrPriorities[$data['scheduler']['priority']]['label'];
                $arrKanbanUsers['column_' . $status][$count]['team'] = 1;
                $arrKanbanUsers['column_' . $status][$count]['dueDate'] = $data['job']['dueDate'];
                $arrKanbanUsers['column_' . $status][$count]['users'][$userCount] = $this->arrAllUsers[$data['scheduler']['added_by']];

                $arrUsedUsers[] = $data['scheduler']['added_by'];

                $userCount++;
                $backlogCount = 0;

                /*                 * ************* Backlogs ************************************** */

                if ( isset ($data['scheduler']['backlogs']) && !empty ($data['scheduler']['backlogs']) )
                {
                    foreach ($data['scheduler']['backlogs'] as $backlogId => $backlog) {

                        if ( !empty ($backlog['case']) && $backlog['case'] instanceof Elements )
                        {
                            $backlogStatus = $backlog['case']->getCurrentStepId ();
                            $statusName = $backlog['case']->getCurrent_step ();
                            $backlog['claimed'] = $backlog['case']->getCurrent_user ();
                            $backlog['dateCompleted'] = $backlog['case']->getDateCompleted ();

                            $arrKanbanUsers['column_' . $status][$count]['backlogs'][$backlogCount]['id'] = $backlogId;
                            $arrKanbanUsers['column_' . $status][$count]['backlogs'][$backlogCount]['title'] = $backlog['case']->getName ();
                            $arrKanbanUsers['column_' . $status][$count]['backlogs'][$backlogCount]['status']['label'] = "label label-primary";
                            $arrKanbanUsers['column_' . $status][$count]['backlogs'][$backlogCount]['status']['status'] = strtoupper ($statusName);

                            if ( isset ($backlog['claimed']) )
                            {
                                $arrKanbanUsers['column_' . $status][$count]['backlogs'][$backlogCount]['users'][$userCount] = $this->arrAllUsers[$backlog['claimed']];
                            }

                            if ( isset ($backlog['claimed']) && !in_array ($backlog['claimed'], $arrUsedUsers) )
                            {
                                $arrKanbanUsers['column_' . $status][$count]['users'][$userCount] = $arrAllUsers[$backlog['claimed']];
                            }

                            $arrKanbanUsers['backlogs'][$backlogCount]['id'] = $backlogId;
                            $arrKanbanUsers['backlogs'][$backlogCount]['Title'] = "test mike";
                            $arrKanbanUsers['backlogs'][$backlogCount]['status'] = $status;

                            $userCount++;


                            $backlogCount++;
                        }
                    }
                }
            }
        }

        return $arrKanbanUsers;
    }

}
