<?php

use Phalcon\Mvc\View;

class CalendarController extends BaseController
{

    public function taskSummaryAction ($taskId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();
        $task = $objProjects->getAllProjects (array("id" => $taskId));
        $arrJSON = json_decode ($task[0]['step_data'], TRUE);
        $this->view->task = $task[0];
        $this->view->arrJSON = $arrJSON;

        $objProjects = new Projects();
        $arrUsers = $objProjects->getAllUsers ();

        foreach ($arrUsers as $key => $arrUser) {
            $teams = $objProjects->doSelect ("team_users", array(), array("user_id" => $arrUser['usrid']));

            $team = $teams[0]['team_id'];
            if ( $team != $arrJSON['team_id'] )
            {
                unset ($arrUsers[$key]);
            }
        }

        $this->view->arrUsers = $arrUsers;
    }

    public function updateTeamMemberAction ($projectId, $username)
    {
        $this->view->disable ();
        $objProjects = new Projects();

        $objProjects->doUpdate (array("assigned_for" => $username), $projectId);
    }

    public function updateDayEventAction ($id, $start, $end)
    {
        $start = sprintf ('%02d', $start);
        $end = sprintf ('%02d', $end);

        $this->view->disable ();
        $objProjects = new Projects();
        $event = $objProjects->doSelect ("projects", array(), array("id" => $id));
        $arrJson = json_decode ($event[0]['step_data'], TRUE);

        $startTime = $start . ":00";
        $endTime = $end . ":00";

        $dateAdded = $event[0]['date_added'];
        $dateAdded = new DateTime ($dateAdded);
        $dateAdded = $dateAdded->format ('Y-m-d') . " " . $startTime;

        // Due Date
        $dueDate = $event[0]['due_date'];
        $dueDate = new DateTime ($dueDate);
        $dueDate = $dueDate->format ('Y-m-d') . " " . $endTime;

        // duration
        $start = explode (':', $startTime);
        $end = explode (':', $endTime);
        $total_hours = $end[0] - $start[0] - ($end[1] < $start[1]);

        $arrJson['date_added'] = $dateAdded;
        $arrJson['date_started'] = $dateAdded;
        $arrJson['due_date'] = $dueDate;
        $arrJson['duration'] = $total_hours;

        $json = json_encode ($arrJson);
        $objProjects->doUpdate (array("step_data" => $json), $id);

        $objNotifications = new Notifications();
        $objNotifications->sendNotification (100, $id, $event);
    }

    public function updateEventAction ($id, $date, $noOfDays, $teamMember = null, $team = null)
    {
        $this->view->disable ();
        echo $id . ' ' . $date . ' ' . $noOfDays, ' ' . $teamMember . ' ' . $team;

        if ( $teamMember != null && $noOfDays > 1 )
        {
            //$noOfDays -= 1;
        }


        if ( $noOfDays == 1 )
        {
            $key = 'day';
        }
        else
        {
            $key = 'days';
        }

        $finishTime = date ('Y-m-d', strtotime ($date . ' + ' . $noOfDays . ' ' . $key . ''));

        echo ' FINSIH TIME ' . $finishTime;



        $objProjects = new Projects();
        $arrData = $objProjects->doSelect ("projects", array(), array("id" => $id));
        $arrJson = json_decode ($arrData[0]['step_data'], TRUE);

        if ( $teamMember != null )
        {
            $arrJson['date_added'] = $date;
            $arrJson['date_started'] = $date;
            $arrJson['due_date'] = $finishTime;
            $arrJson['assigned_for'] = $teamMember;
            $arrJson['team_id'] = $team;
        }
        else
        {
            $arrJson['date_added'] = $date;
            $arrJson['due_date'] = $finishTime;
        }

        $json = json_encode ($arrJson);
        $objProjects->doUpdate (array("step_data" => $json), $id);

        $objNotifications = new Notifications();
        $objNotifications->sendNotification (100, $id, $arrData);
    }

    public function loadTeamMembersAction ($deptId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objScheduler = new Scheduler();
        $objProjects = new Projects();

        $arrTeams = $objScheduler->getAllTeams (NULL, $deptId);
        $this->view->departments = $objProjects->getAllDepartments ();
        $this->view->deptId = $deptId;

        $arrTeams2 = array();

        $intCount = 0;
        foreach ($arrTeams as $arrTeam) {
            $arrTeams2[$arrTeam['team_id']]['team_name'] = $arrTeam['team_name'];
            $arrTeams2[$arrTeam['team_id']]['users'][$intCount]['username'] = $arrTeam['username'];

            $intCount++;
        }

        $this->view->arrUsers = $arrTeams2;
    }

    public function loadCalendarAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objCalendar = new Calendar();

        isset ($_REQUEST['deptId']) ? $_REQUEST['deptId'] : $_REQUEST['deptId'] = null;
        isset ($_REQUEST['user']) ? $_REQUEST['user'] : $_REQUEST['user'] = null;

        $year = isset ($_GET['year']) ? $_GET['year'] : NULL;
        $month = isset ($_GET['month']) ? $_GET['month'] : NULL;
        $type = isset ($_GET['type']) ? $_GET['type'] : 'month';

        $objScheduler = new Scheduler();
        $objRequestFormatter = new RequestFormatter();

        $tasks = $objScheduler->getData ("task_manager", array("deptId" => $_REQUEST['deptId'], "user" => $_REQUEST['user']));
        $tasks = $objRequestFormatter->format ("task_manager", $tasks, "assigned");

        $calendar = $objCalendar->show ($year, $month, $type, $tasks);
        $calendar .= '</td>'
                . '</tr>'
                . '</tbody>'
                . '</table> '
                . '<div class="fc-popover fc-more-popover" style="top: 129px; left: 0px;">'
                . '<div class="fc-header fc-widget-header">'
                . '<span class="fc-close fc-icon fc-icon-x"></span>'
                . '<span class="fc-title">Sunday, September 4</span>'
                . '<div class="fc-clear"></div>'
                . '</div>'
                . '<div class="fc-body fc-widget-content">'
                . '<div class="fc-event-container">'
                . '</div>'
                . '</div>';

        echo $calendar;
        //echo $objCalendar->buildSummary ();
        //die;
    }

    public function indexAction ()
    {
        $objProjects = new Projects();
        $arrUsers = $objProjects->getAllUsers ();
        $arrTeams = array();
        $arrTeams2 = array();

        $objScheduler = new Scheduler();
        $arrTeams = $objScheduler->getAllTeams ();
        
        $objScheduler = new Scheduler();
        $objRequestFormatter = new RequestFormatter();

        $tasks = $objScheduler->getData ("task_manager");
        $depts = [];
        
        foreach ($tasks as $task) {
            $depts[] = $task['department_id'];
        }
        
        foreach ($arrTeams as $key => $arrTeam) {
            if(!in_array ($arrTeam['dept_id'], $depts)) {
                unset($arrTeams[$key]);
            }
        }

        $this->view->arrAllUsers = $arrTeams;

        $intCount = 0;
        foreach ($arrTeams as $arrTeam) {
            $arrTeams2[$arrTeam['team_id']]['team_name'] = $arrTeam['team_name'];
            $arrTeams2[$arrTeam['team_id']]['users'][$intCount]['username'] = $arrTeam['username'];

            $intCount++;
        }
        
        $this->view->arrUsers = $arrTeams2;

        $objProjects = new Projects();
        $departments = $objProjects->getAllDepartments ();
        $this->view->departments = $departments;

        foreach ($departments as $key => $department) {
            if(!in_array ($department['id'], $depts)) {
                unset($departments[$key]);
            }
        }
        
        $this->view->departments = $departments;
        $externalEvents = $objRequestFormatter->format ("task_manager", $tasks, "unassigned");
        $this->view->externalEvents = $externalEvents;
    }

    public function refreshUnassignedTasksAction ()
    {
        $this->view->disable();

        $objScheduler = new Scheduler();
        $objRequestFormatter = new RequestFormatter();
        $objCalendar = new CalendarFuncs();
        
        $tasks = $objScheduler->getData ("task_manager");
        $externalEvents = $objRequestFormatter->format ("task_manager", $tasks, "unassigned");

        foreach ($externalEvents as $externalEvent):

            if($externalEvent['accepted_by'] == "") $class = 'disable';

            echo '<div data-title="'. $externalEvent['title'] .'" days="'. $externalEvent['days'] .'" id="'. $externalEvent['id'] .'" class="external-event '. $class .' navy-bg" style="position: relative; background-color:'. $objCalendar->colours[$externalEvent['class']] .'">
                <div class="fc-content">
                    <span class="fc-title">
                        <div class="teamLabel" style="float:left; width:100%;"></div>
                        <div style="width:100%;"> '.$externalEvent['title'] .'
                            <span style="margin-left:2%;"> '.$objCalendar->priorities[$externalEvent['class']] .'</span>
                        </div>
                    </span>
                </div>
            </div>';
        endforeach; 
}

public function showDayAction ()
{
$this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
}

public function testAction ()
{

}

}
