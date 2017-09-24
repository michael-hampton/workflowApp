<?php

use Phalcon\Mvc\View;

class InboxController extends BaseController
{

    public function onConstruct ()
    {
        define ("PRODUCTS_PAGE_LIMIT", 20);
    }

    public function inboxAction ()
    {
        $objNotifications = new \BusinessModel\NotificationsFactory();

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $arrParameters = array("user" => $_SESSION['user']['user_email'], "status" => 1, "is_important" => 0);

        $this->view->arrNotifications = $objNotifications->getNotifications ($arrParameters);

        $objLists = new \BusinessModel\Lists();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);
        $objLists->loadList ("inbox", $objUser, array("userId" => $_SESSION['user']['usrid']));

        $objUsers = new \BusinessModel\UsersFactory ($_SESSION['user']['usrid']);
        $arrUser = $objUsers->getUsers ();

        $this->view->arrCounters = $objLists->getCounters ($arrUser[0]);
    }

    public function searchMessagesAction ($status, $page = 0, $strOrderBy = "ns.APP_MSG_SEND_DATE", $strOrderDir = "DESC")
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objNotifications = new \BusinessModel\NotificationsFactory();

        $isImportant = $status == 8 ? 1 : 0;
        $status = $isImportant === 1 ? null : $status;


        $arrParameters = array("user" => $_SESSION['user']['user_email'], "status" => $status, "is_important" => $isImportant);

        if ( (int) $status === 2 )
        {
            $arrParameters['has_read'] = 1;
            $arrParameters['status'] = null;
        }

        if ( isset ($_POST['searchText']) && !empty ($_POST['searchText']) )
        {
            $arrParameters['searchText'] = $_POST['searchText'];
        }

        $arrNotifications = $objNotifications->getNotifications ($arrParameters, PRODUCTS_PAGE_LIMIT, $page, $strOrderBy, $strOrderDir);

        $this->view->pagination = $this->getPagination ("jumpToPage", $arrNotifications['counts']);
        unset ($arrNotifications['counts']);

        $this->view->arrNotifications = $arrNotifications;
    }

    public function filterProjectsAction ($filter, $page)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        $objLists = new \BusinessModel\Lists();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $this->view->arrCases = $objLists->loadList ($filter, $objUser, array("userId" => $_SESSION['user']['usrid'], "page" => $page, "page_limit" => PRODUCTS_PAGE_LIMIT));


        $this->view->pagination = $this->getPagination ("projectsPage", $this->view->arrCases['count']);
    }

    public function updateStatusAction ($status)
    {
        $this->view->disable ();
        $objNotifications = new Notification();
        $arrUpdate = [];

        switch ($status) {
            case 4:
                $arrUpdate['APP_MSG_SHOW_MESSAGE'] = 4;
                // trash
                break;

            case 5:
                // read
                $arrUpdate['HAS_READ'] = 1;
                break;

            case 6:
                // important
                $arrUpdate['IS_IMPORTANT'] = 1;
                break;
        }

        foreach ($_POST['selected'] as $id) {
            $objNotifications->update ($arrUpdate, $id);
        }
    }

    public function getMessageAction ($id)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objNotifications = new \BusinessModel\NotificationsFactory();
        $arrParameters = array("user" => $_SESSION['user']['user_email'], "status" => null, "is_important" => 0, "has_read" => 0, "id" => $id);

        $this->view->arrNotifications = $objNotifications->getNotifications ($arrParameters);

        $arrParameters['id'] = null;
        $arrParameters['parent_id'] = $id;

        $this->view->arrReplies = $objNotifications->getNotifications ($arrParameters);
    }

    public function composeAction ($parentId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $this->view->parentId = $parentId;
    }

    public function sendMailAction ()
    {
        $this->view->disable ();
        $objNotifications = new Notifications();

        $_POST['sentByUser'] = 1;

        $objNotifications->loadObject ($_POST);
        $objNotifications->saveNewMessage ();
    }

    public function addCaseAction ($page = 0)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $arrWorkflows = (new Workflow())->getAllProcesses ($page, PRODUCTS_PAGE_LIMIT);

        $workflowPermissions = (new \BusinessModel\ProcessPermission (null))->getAllProcessPermissions ();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $teamId = $objUser->getTeam_id ();
        $userId = $objUser->getUserId ();

        foreach ($arrWorkflows['data'] as $key => $objWorkflow) {

            $blHasPermission = false;

            if ( isset ($workflowPermissions[$objWorkflow->getId ()]) )
            {
                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['user']) && in_array ($userId, $workflowPermissions[$objWorkflow->getId ()]['user']) )
                {
                    $blHasPermission = true;
                }

                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['team']) && in_array ($teamId, $workflowPermissions[$objWorkflow->getId ()]['team']) )
                {
                    $blHasPermission = true;
                }
            }

            if ( $blHasPermission === false )
            {
                unset ($arrWorkflows[$key]);
            }
        }

        $this->view->arrWorkflows = $arrWorkflows['data'];
        $this->view->pagination = $this->getPagination ("processPagination", $arrWorkflows['count']);
    }

    public function addNewCaseAction ($workflowId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objCase = new \BusinessModel\Cases();
        $objWorkflow = new Workflow ($workflowId);
        $this->view->html = $objCase->startCase ($objWorkflow);
        $this->view->html .= '<input type="hidden" id="workflowid" name="workflowid" value="' . $workflowId . '">';
    }

    public function saveNewCaseAction ()
    {
        $this->view->disable ();

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objCases = new \BusinessModel\Cases();
        $arrFiles = isset ($_FILES['fileUpload']) ? $_FILES : array();
        $objUser = (new \BusinessModel\UsersFactory)->getUser ($_SESSION['user']['usrid']);
        $objWorkflow = new Workflow ($_POST['workflowid']);

        $objCases->addCase ($objWorkflow, $objUser, $_POST, $arrFiles);
    }

    public function filterCasesAction ($workflowId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objCases = new \BusinessModel\Cases();
        $this->view->arrLists = $objCases->getList (array("process" => $workflowId, "limit" => PRODUCTS_PAGE_LIMIT, "start" => 0, "userId" => $_SESSION['user']['username']));
    }

    public function filterProcessesAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        $arrWorkflows = (new Workflow())->getAllProcesses (0, 25, 'request_id', 'ASC', null, $_POST['searchText']);

        $workflowPermissions = (new \BusinessModel\ProcessPermission (null))->getAllProcessPermissions ();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $teamId = $objUser->getTeam_id ();
        $userId = $objUser->getUserId ();

        foreach ($arrWorkflows['data'] as $key => $objWorkflow) {

            $blHasPermission = false;

            if ( isset ($workflowPermissions[$objWorkflow->getId ()]) )
            {
                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['user']) && in_array ($userId, $workflowPermissions[$objWorkflow->getId ()]['user']) )
                {
                    $blHasPermission = true;
                }

                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['team']) && in_array ($teamId, $workflowPermissions[$objWorkflow->getId ()]['team']) )
                {
                    $blHasPermission = true;
                }
            }

            if ( $blHasPermission === false )
            {
                unset ($arrWorkflows[$key]);
            }
        }

        $this->view->arrWorkflows = $arrWorkflows;
    }

    public function advancedSearchAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_ALLCASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objWorkflowFctory = new \BusinessModel\WorkflowCollectionFactory();
        $this->view->arrCategories = $objWorkflowFctory->getCategories (null, "request_type", "asc");

        $arrWorkflows = (new Workflow())->getAllProcesses (0, 25, 'workflow_name', 'ASC');

        $workflowPermissions = (new \BusinessModel\ProcessPermission (null))->getAllProcessPermissions ();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $teamId = $objUser->getTeam_id ();
        $userId = $objUser->getUserId ();

        foreach ($arrWorkflows['data'] as $key => $objWorkflow) {

            $blHasPermission = false;

            if ( isset ($workflowPermissions[$objWorkflow->getId ()]) )
            {
                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['user']) && in_array ($userId, $workflowPermissions[$objWorkflow->getId ()]['user']) )
                {
                    $blHasPermission = true;
                }

                if ( isset ($workflowPermissions[$objWorkflow->getId ()]['team']) && in_array ($teamId, $workflowPermissions[$objWorkflow->getId ()]['team']) )
                {
                    $blHasPermission = true;
                }
            }

            if ( $blHasPermission === false )
            {
                unset ($arrWorkflows[$key]);
            }
        }

        $this->view->arrWorkflows = $arrWorkflows['data'];

        //$arrayWhere = null, $sortField = null, $sortDir = null, $start = null, $limit = null
        $objUsers = new \BusinessModel\UsersFactory();
        $this->view->arrUsers = $objUsers->getUsers (array(), "u.username", "ASC", 0, PRODUCTS_PAGE_LIMIT);
    }

    public function searchCasesAction ($page, $orderBy = '', $orderDir = 'ASC', $searchText = '')
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_ALLCASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $process = empty ($_POST['process']) ? null : $_POST['process'];
        $category = empty ($_POST['category']) ? null : $_POST['category'];
        $user = empty ($_POST['user']) ? null : $_POST['user'];
        $status = empty ($_POST['status']) ? null : $_POST['status'];
        $search = empty ($searchText) ? null : $searchText;

        $objCases = new \BusinessModel\Cases();

        $this->view->arrLists = $objCases->getList (
                array(
                    "user" => $user,
                    "status" => $status,
                    "process" => $process,
                    "category" => $category,
                    "limit" => PRODUCTS_PAGE_LIMIT,
                    "start" => $page,
                    "userId" => $_SESSION['user']['username'],
                    "action" => "search",
                    "search" => $search
                )
        );

        $this->view->pagination = $this->getPagination ("casePagination", $this->view->arrLists['count']);
    }

    public function openSummaryAction ($app_uid)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objCase = (new \BusinessModel\Cases())->getCaseInfo ($app_uid);

        $this->view->objCase = $objCase;
        $this->view->audit = $objCase->getAudit ()['elements'][1]['steps'][$objCase->getCurrentStepId ()];
    }

    public function reassignUsersAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        $result = (new Mysql2())->_query ("SELECT t.* FROM workflow.status_mapping m
                            INNER JOIN workflow.task t ON t.TAS_UID = m.TAS_UID
                            WHERE id = ?", [$_POST['taskUid']]);

        if ( !isset ($result[0]) || empty ($result[0]) )
        {
            throw new Exception ("Failed to find task");
        }

        $objTask = new Task ($result[0]['TAS_UID']);
        $objUser = (new \BusinessModel\UsersFactory)->getUser ($_SESSION['user']['usrid']);

        $search = $_POST['search'];
        $pageSize = $_POST['pageSize'];
        $sortField = (isset ($_POST['sort'])) ? $_POST['sort'] : '';
        $sortDir = (isset ($_POST['dir'])) ? $_POST['dir'] : '';
        $start = (isset ($_POST['start'])) ? $_POST['start'] : 0;
        $limit = (isset ($_POST['limit'])) ? $_POST['limit'] : $pageSize;

        $response = [];
        try {
            $case = new \BusinessModel\Cases();
            $result2 = $case->getUsersToReassign ($objUser, $objTask, ['filter' => $search], $sortField, $sortDir, $start, $limit);

            $response['status'] = 'OK';
            $response['success'] = true;
            $response['resultTotal'] = $result2['total'];
            $response['resultRoot'] = $result2['data'];
            $this->view->appUid = $projectId;
            $this->view->taskId = $result[0]['TAS_UID'];
            $this->view->response = $response;
        } catch (Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = $e->getMessage ();
        }
    }

    public function saveReassignUserAction ()
    {
        $this->view->disable ();

        try {
            $APP_UID = $_POST["APP_UID"];
            $DEL_INDEX = $_POST["DEL_INDEX"];
            $cases = new \BusinessModel\Cases();

            $objCase = $cases->getCaseInfo ($APP_UID);
            $caseData = $objCase->getAudit ()['elements'][1]['steps'];

            $flagReassign = true;
            $flagHasUser = false;

            $objUser = (new \BusinessModel\UsersFactory())->getUser ($_POST['userId']);

            foreach ($caseData as $data) {

                if ( trim ($data['claimed']) === trim ($objUser->getUsername ()) )
                {
                    $flagReassign = false;
                }

                $flagHasUser = true;
            }

            if ( $flagHasUser === false )
            {
                throw new Exception ("User could not be reassigned");
            }

            //If the currentUser is diferent to nextUser, create the thread
            if ( $flagReassign )
            {
                $cases->reassignCase ($APP_UID, new Task ($DEL_INDEX), $objUser);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
