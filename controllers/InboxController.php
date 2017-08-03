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

    public function searchMessagesAction ($status, $page = 0, $strOrderBy = "ns.date_sent", $strOrderDir = "DESC")
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        if ( !$this->checkPermissions ("EASYFLOW_CASES") )
        {
            header ("Location: /FormBuilder/errors/error403");
            die;
        }

        $objNotifications = new \BusinessModel\NotificationsFactory();

        $isImportant = $status == 8 ? 1 : 0;

        $arrParameters = array("user" => $_SESSION['user']['user_email'], "status" => $status, "is_important" => $isImportant);

        if ( isset ($_POST['searchText']) && !empty ($_POST['searchText']) )
        {
            $arrParameters['searchText'] = $_POST['searchText'];
        }

        $this->view->arrNotifications = $objNotifications->getNotifications ($arrParameters, PRODUCTS_PAGE_LIMIT, $page, $strOrderBy, $strOrderDir);
        $this->view->pagination = $this->getPagination ();
    }

    public function filterProjectsAction ($filter, $page)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objLists = new \BusinessModel\Lists();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);
        $this->view->arrCases = $objLists->loadList ($filter, $objUser, array("userId" => $_SESSION['user']['usrid'], "page" => $page, "page_limit" => PRODUCTS_PAGE_LIMIT));
        $this->view->pagination = $this->getPagination ("projectsPage");
    }

    public function updateStatusAction ($status)
    {
        $this->view->disable ();
        $objNotifications = new Notifications();
        $arrUpdate = [];

        switch ($status) {
            case 4:
                $arrUpdate['status'] = 4;
                // trash
                break;

            case 5:
                // read
                $arrUpdate['has_read'] = 1;
                break;

            case 6:
                // important
                $arrUpdate['is_important'] = 1;
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

    public function getPagination ($strFunction = "jumpToPage")
    {

        /*         * ************************************************************************* */
        /*                      PAGINGATION BEGIN
         * *************************************************************************** */

        $currentPageorders = $_SESSION["pagination"]["total_counter"] - (PRODUCTS_PAGE_LIMIT * $_SESSION["pagination"]["current_page"]);
        if ( $currentPageorders > PRODUCTS_PAGE_LIMIT )
            $currentPageorders = PRODUCTS_PAGE_LIMIT;

        $html_pagination = "<ul class=\"pagination pull-right\">";

        $html_pagination .= "<li onclick=\"" . $strFunction . "(0)\" class=\"footable-page-arrow\">
            <a data-page=\"first\" href=\"#first\">«</a>
        </li>";

        if ( $_SESSION["pagination"]["current_page"] > 0 )
        {

            $html_pagination .= "<li onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["current_page"] - 1) . ")\" class=\"footable-page-arrow\">
                <a data-page=\"prev\" href=\"#prev\">‹</a>
            </li>";
        }
        else
        {
            $html_pagination .= "<li class=\"footable-page-arrow disabled\">
                <a data-page=\"prev\" href=\"#prev\">«</a>
            </li>";
        }

        //$html_pagination .= "<div style=\" float: left; width: 81%; \">&nbsp;";
        if ( $_SESSION["pagination"]["total_pages"] < 10 )
        {
            for ($i = 0; $i <= $_SESSION["pagination"]["total_pages"] - 1; $i++) {

                if ( $i == $_SESSION["pagination"]["current_page"] )
                {
                    $class = "active";
                }
                else
                {
                    $class = "";
                }

                $html_pagination .= "<li class=\"footable-page " . $class . "\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }
        else
        {

            /* pages more then 11 */

            if ( $_SESSION["pagination"]["current_page"] <= 5 )
            {
                $intStartWidth = 0;
                $endStartWidth = 9;
                //echo "stage 2";
            }

            if ( $_SESSION["pagination"]["current_page"] > 5 )
            {
                $intStartWidth = $_SESSION["pagination"]["current_page"] - 5;
                $endStartWidth = $_SESSION["pagination"]["current_page"] + 5;
                //echo "stage 3";
            }

            if ( ($_SESSION["pagination"]["current_page"] + 5) >= $_SESSION["pagination"]["total_pages"] )
            {
                $intStartWidth = $_SESSION["pagination"]["total_pages"] - 10;
                $endStartWidth = $_SESSION["pagination"]["total_pages"] - 1;
                //echo "stage 4";
            }

            for ($i = $intStartWidth; $i <= $endStartWidth; $i++) {

                if ( $i == $_SESSION["pagination"]["current_page"] )
                {
                    $class = 'active';
                }
                else
                {
                    $class = '';
                }

                $html_pagination .= "<li class=\"footable-page " . $class . "\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }

        //$html_pagination.=  " <div style=\"float: left; margin-top: 7px; width: 50px;\" > <img style=\" display:none;\" id=\"ajax-loader-pic\" alt=\"loading\" src=\"/images/ajax-loader.gif\"></div>";

        if ( ($_SESSION["pagination"]["current_page"] + 1) < $_SESSION["pagination"]["total_pages"] )
        {

            $html_pagination .= "<li class=\"footable-page\" onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["current_page"] + 1) . ")\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }
        else
        {

            $html_pagination .= "<li class=\"footable-page disabled\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }

        $html_pagination .= "<li class=\"footable-page-arrow\" onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["total_pages"] - 1) . ")\">
           <a data-page=\"last\" href=\"#last\">»</a>
        </li>";

        $html_pagination .= "</ul>";

        $htmlResult = $html_pagination;

        return $htmlResult;
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

        foreach ($arrWorkflows as $key => $objWorkflow) {

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
        $this->view->pagination = $this->getPagination ("processPagination");
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

        foreach ($arrWorkflows as $key => $objWorkflow) {

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

        foreach ($arrWorkflows as $key => $objWorkflow) {

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

        //$arrayWhere = null, $sortField = null, $sortDir = null, $start = null, $limit = null
        $objUsers = new \BusinessModel\UsersFactory();
        $this->view->arrUsers = $objUsers->getUsers (array(), "u.username", "ASC", 0, 25);
    }

    public function searchCasesAction ($page, $orderBy, $orderDir)
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

        $objCases = new \BusinessModel\Cases();

        $this->view->arrLists = $objCases->getList (
                array(
                    "user" => $user,
                    "status" => $status,
                    "process" => $process,
                    "category" => $category,
                    "limit" => PRODUCTS_PAGE_LIMIT,
                    "start" => 0,
                    "userId" => $_SESSION['user']['username'],
                    "action" => "search"
                )
        );

        $this->view->pagination = $this->getPagination ("casePagination");
    }

}
