<?php

use Phalcon\Mvc\View;

error_reporting (E_ALL);

class SchedulerController extends BaseController
{

    public function indexAction ()
    {
        
    }

    public function newAction ()
    {
        
    }

    public function demo1Action ()
    {
        
    }

    public function getEventsAction ()
    {
        $this->view->disable ();

        $dataSource = $_GET['dataSource'];

        $eventsArray[1] = array(
            'options' =>
            array(
                'timeslotsPerHour' => 4,
                'timeslotHeight' => 20,
            ),
            'events' =>
            array(
                0 =>
                array(
                    'id' => 1,
                    'start' => date ("Y-m-d H:i:s"),
                    'end' => date ("Y-m-d H:i:s", strtotime ("+2 hour")),
                    'title' => 'Lunch with Mike',
                    'userId' => 0,
                    'priority' => 1
                ),
                1 =>
                array(
                    'id' => 2,
                    'start' => date ("Y-m-d H:i:s", strtotime ("+4 hours")),
                    'end' => date ("Y-m-d H:i:s", strtotime ("+8 hours")),
                    'title' => 'Dev Meeting',
                    'userId' => 1,
                    'priority' => 2
                ),
                2 =>
                array(
                    'id' => 3,
                    'start' => '2017-02-13 18:00',
                    'end' => '2017-02-13 18:45:00',
                    'title' => 'Hair cut',
                    'userId' => 2,
                    'priority' => 3
                ),
                3 =>
                array(
                    'id' => 4,
                    'start' => '2017-02-14 08:00:00',
                    'end' => '2017-02-14 12:30:00',
                    'title' => 'Team breakfast',
                    'userId' => 3,
                    'priority' => 1
                ),
                4 =>
                array(
                    'id' => 5,
                    'start' => '2017-02-13 14:00:00',
                    'end' => '2017-02-13 15:00:00',
                    'title' => 'Product showcase',
                    'userId' => 4,
                    'priority' => 2
                ),
            ),
        );

        $arrTeams = array(
            0 => array(
                "name" => "Team 1",
                "color" => "FF0000"
            ),
            1 => array(
                "name" => "Team 2",
                "color" => "FF0000"
            )
        );

        $eventsArray[2] = array(
            'options' =>
            array(
                'timeslotsPerHour' => 3,
                'timeslotHeight' => 30,
            ),
            'events' =>
            array(
                0 =>
                array(
                    'id' => 1,
                    'start' => '2017-02-12T12:00:00.000Z',
                    'end' => '2017-02-12T13:00:00.000Z',
                    'title' => 'Lunch with Sarah',
                    'userId' => 0,
                    'priority' => 1
                ),
                1 =>
                array(
                    'id' => 2,
                    'start' => '2017-02-12T14:00:00.000Z',
                    'end' => '2017-02-12T14:40:00.000Z',
                    'title' => 'Team Meeting',
                    'userId' => 1,
                    'priority' => 2
                ),
                2 =>
                array(
                    'id' => 3,
                    'start' => '2017-02-13T18:00:00.000Z',
                    'end' => '2017-02-13T18:40:00.000Z',
                    'title' => 'Meet with Joe',
                    'userId' => 2,
                    'priority' => 3
                ),
                3 =>
                array(
                    'id' => 4,
                    'start' => '2017-02-14T08:00:00.000Z',
                    'end' => '2017-02-14T09:20:00.000Z',
                    'title' => 'Coffee with Alison',
                    'userId' => 3,
                    'priority' => 4
                ),
                4 =>
                array(
                    'id' => 5,
                    'start' => '2017-02-13T14:00:00.000Z',
                    'end' => '2017-02-13T15:00:00.000Z',
                    'title' => 'Product showcase',
                    'userId' => 4,
                    'priority' => 1
                ),
            ),
        );

        $start = $_GET['start'];
        $end = $_GET['end'];

        echo json_encode ($eventsArray[$dataSource]);
    }

    public function updateEventAction ()
    {
        $this->view->disable ();

        $startTime = str_replace ("am", "AM", $_POST['timestamp1']);
        $startTime = str_replace ("pm", "PM", $startTime);
        $endTime = str_replace ("am", "AM", $_POST['timestamp2']);
        $endTime = str_replace ("pm", "PM", $endTime);
        $startDate = $_POST['startDate'] . " " . $startTime;

        $startDate = strtr ($startDate, '/', '-');
        $startDate = date ("Y-m-d H:i:s", strtotime ($startDate));

        $endDate = $_POST['startDate'] . " " . $endTime;
        $endDate = strtr ($endDate, '/', '-');
        $endDate = date ("Y-m-d H:i:s", strtotime ($endDate));

        echo $startDate . " " . $endDate;

        echo '<pre>';
        print_r ($_POST);
        die;

        // return id
        echo 200;
    }

    public function saveUserAction ()
    {
        $this->view->disable ();
    }

    private function randColor ()
    {
        return '#' . str_pad (dechex (mt_rand (0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function kanbanAction ($requestType = null)
    {
        if ( !$this->checkPermissions ("EASYFLOW_SCHEDULER") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objRequestFormatter = new RequestFormatter();
        $objProjects = new Projects();

        $arrRequestTypes = array();
        $arrColumns = array();
        $arrTeams = array();

        /*         * ***************** Build Request Types ****************************** */
        $arrAllRequestTypes = $objRequestFormatter->getRequestTypes ();

        foreach ($arrAllRequestTypes as $requestTypes) {
            $arrRequestTypes[$requestTypes['request_id']] = $requestTypes;

            /*             * ************************ Build Steps ************************** */
            $arrSteps = $objRequestFormatter->getSteps ($requestTypes['request_id']);

            foreach ($arrSteps as $stepKey => $arrStep) {

                $arrColumns[$requestTypes['request_id']][$stepKey]['name'] = $arrStep['name'];
                $arrColumns[$requestTypes['request_id']][$stepKey]['id'] = $arrStep['step_id'];
                $arrColumns[$requestTypes['request_id']][$stepKey]['add_ikon'] = '+';
                $arrColumns[$requestTypes['request_id']][$stepKey]['ikon'] = '⊕';
                $arrColumns[$requestTypes['request_id']][$stepKey]['color'] = $this->randColor ();
            }

            /*             * ************************ Build Teams ***************************** */
            $arrTeam = $objProjects->doSelect (
                    "user_management.teams", array(), array(
                "dept_id" => $requestTypes['dept_id']
                    ), array(
                "team_id" => "ASC"
                    )
            );

            $arrTeams[$requestTypes['request_id']] = $arrTeam;
        }

        $arrAllTeams = $objProjects->doSelect ("user_management.teams", array());

        /*         * ************ Parent Id ***************** */
        if ( !empty ($arrRequestTypes[$requestType]['parent_id']) )
        {
            $parentId = $arrRequestTypes[$requestType]['parent_id'];
        }

        echo '<pre>';
        echo "COLUMNS===============================";
        print_r ($arrColumns);
        echo "ALL TEAMS===============================";
        print_r ($arrAllTeams);
        echo "TEAMS===============================";
        print_r ($arrTeams[$requestType]);
        //die;

        if ( $requestType === null && $requestType !== "null" )
        {
            $this->view->columns = json_encode ($arrColumns[3]);
            $this->view->teams = json_encode ($arrAllTeams);
        }
        else
        {
            $this->view->teams = json_encode ($arrTeams[$requestType]);

            //if ( isset ($parentId) && is_numeric ($parentId) )
            // {
            //$this->view->columns = json_encode ($arrColumns[$parentId]);
            //}
            //else
            //{
            //die("Here");
            $this->view->columns = json_encode ($arrColumns[$requestType]);
            //}
        }

        $this->view->arrColumns = $arrColumns;

        $this->view->requestType = $requestType;

        $this->view->arrDeptWorkflows = $arrAllRequestTypes;
    }

    public function getUsersAction ()
    {
        $this->view->disable ();
    }

    public function updateAction ()
    {
        $this->view->disable ();

        $users = array(
            1 => array(
                "uid" => 1,
                "name" => "michael.hampton",
                "src" => "michael.hampton.jpeg",
                "teamId" => 1
            ),
            2 => array(
                "uid" => 2,
                "name" => "lexi.hampton",
                "src" => "lexi.hampton.jpg",
                "teamId" => 2
            ),
            3 => array(
                "uid" => 3,
                "name" => "uan.hampton",
                "src" => "uan.hampton.jpg",
                "teamId" => 3
            )
        );

        switch ($_POST['request']) {
            case "add_description":

                break;

            case "add_tag":

                break;

            case "move":

                break;

            case "drop_tag":


                break;

            case "add_user":
                echo json_encode ($users[$_POST['user']]);
                break;

            case "drop_user":

                break;

            case "set_priority":

                break;

            case "resolve":

                break;
        }
    }

    public function getTaskAction ()
    {
        $this->view->disable ();

        if ( isset ($_POST['userid']) && is_numeric ($_POST['userid']) )
        {
            
        }

        $objRequestFormatter = new RequestFormatter ($_POST['id']);
        $data = $objRequestFormatter->formatKanbanData ($_POST, "task");
        echo json_encode ($data);
    }

    public function saveCommentsAction ()
    {
        $this->view->disable ();
    }

    public function saveNewTaskAction ()
    {
        $this->view->disable ();

        echo json_encode (array(
            "id" => 1,
            "type" => 1,
            "title" => "Test Mike",
            "users" => array(
                0 => array(
                    "uid" => 1,
                    "name" => "michael.hampton",
                    "src" => "michael.hampton.jpeg"
                )
            ),
            "priority" => array(
                "id" => 1,
                "name" => "High",
                "label" => "label label-danger"
            ),
            "url" => "test.com",
            "body" => "Task Desc",
            "comments" => array(
                0 => array(
                    "body" => "Test Comment"
                )
            ),
            "tags" => array(
                0 => array()
            )
            , "owner" => "michael.hampton"
                )
        );
    }

    public function getKanbanDataAction ()
    {
        $this->view->disable ();

        $objRequestFormatter = new RequestFormatter();
        $data = $objRequestFormatter->formatKanbanData ($_POST);
        echo json_encode ($data);
    }

    public function nameGeneratorAction ()
    {
        $objTeams = new Teams();

        //PHP array containing forenames.
        $names = array(
            'Christopher',
            'Ryan',
            'Ethan',
            'John',
            'Zoey',
            'Sarah',
            'Michelle',
            'Samantha',
        );

//PHP array containing surnames.
        $surnames = array(
            'Walker',
            'Thompson',
            'Anderson',
            'Johnson',
            'Tremblay',
            'Peltier',
            'Cunningham',
            'Simpson',
            'Mercado',
            'Sellers'
        );

//Generate a random forename.

        for ($i = 0; $i < 10; $i++) {
            $random_name = $names[mt_rand (0, sizeof ($names) - 1)];

//Generate a random surname.
            $random_surname = $surnames[mt_rand (0, sizeof ($surnames) - 1)];

            //$objTeams->insertUser($random_name, $random_surname, strtolower($random_name).".".strtolower($random_surname)."@yahoo.com", strtolower($random_name).".".strtolower($random_surname));
//Combine them together and print out the result.
            echo $random_name . ' ' . $random_surname . " /r/n/";
            echo strtolower ($random_name) . "." . strtolower ($random_surname);
            echo strtolower ($random_name) . "." . strtolower ($random_surname) . "@yahoo.com";
        }
    }

    public function deleteTeamAction ()
    {
        $this->view->disable ();
        $objTeams = new Teams();
        $objTeams->deleteTeam ($_POST['teamid']);
    }

    public function updateTeamsAction ()
    {
        $this->view->disable ();
        $objTeams = new Teams();
        $objTeams->updateTeamMember ($_POST['teamid'], $_POST['userid']);
    }

    public function editTeamsAction ($requestType)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();
        $arrUsers = $objProjects->getAllUsers ();
        $arrAllTeams = $objProjects->getAllTeams ();

        $arrUnclassified = array();
        $arrTeams = array();

        foreach ($arrUsers as $arrUser) {
            if ( empty ($arrUser['team_id']) || $arrUser['team_id'] == 0 )
            {
                $arrUnclassified[] = $arrUser;
            }
            else
            {
                $arrTeams[$arrUser['team_id']][] = $arrUser;
            }
        }

//        echo '<pre>';
//        echo "ALL TEAMS===========================";
//        print_r($arrAllTeams);
//        echo "UNCLASSIFIED=============================";
//        print_r($arrUnclassified);
//        echo "TEAMS======================================";
//        print_r($arrTeams);
//        die;

        $this->view->arrAllTeams = $arrAllTeams;
        $this->view->arrUnclassified = $arrUnclassified;
        $this->view->arrTeams = $arrTeams;

        if ( $requestType == "null" )
        {
            
        }
        else
        {
            
        }
    }

}
