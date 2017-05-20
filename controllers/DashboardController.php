<?php

use Phalcon\Mvc\View;

class DashboardController extends BaseController
{

    private $maxPagesBoxesUpAndDown = 5;

    public function onConstruct ()
    {
        define ("PRODUCTS_PAGE_LIMIT", 20);
        session_start ();
    }

    public function test2Action ()
    {
        $objDashboard = new DashboardFactory();
        $objProjects = new Projects();
        $arrProjects = $objProjects->getAllProjects (array());
        $html = $objDashboard->loadDashboards ($_SESSION['user']['usrid'], $arrProjects);
        $this->view->html = $html;
    }

    public function indexAction ()
    {
        $objProjects = new Projects();
        $arrProjects = $objProjects->getAllProjects (array());
        $this->view->arrProjects = $arrProjects;
        $objReports = new DashboardInstance($arrProjects);

        $this->view->arrProjectsForUser = $objReports->getProjectsForUser ();
        $this->view->arrCompletedProjects = $objReports->getCompletedProjects ();
        $this->view->incompleteProjects = $objReports->getIncompleteProjects ();
        $this->view->arrAllProjects = $objReports->getAllProjects ();
        $this->view->arrLateProjects = $objReports->getLateProjects ();
        $this->view->arrUsersWithProjects = $objReports->getUsersWithProjects ();
        $this->view->arrOnTime = $objReports->getOnTimeProjects ();
    }

}
