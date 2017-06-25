<?php

use Phalcon\Mvc\View;

class CommentsController extends BaseController
{

    public function getJobCommentsOnlyAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objComments = new Comments();
        $this->view->arrComments = $objComments->getAllComments ($projectId);
    }

    public function saveCommentsAction ()
    {
        $this->view->disable ();

        $objCases = new \BusinessModel\Cases();
        $objCases->saveCaseNote ($_POST['projectId'], $_SESSION['user']['username'], $_POST['comment'], true);
        die;
    }

    public function getCommentsAction ($projectId)
    {
        $this->view->projectId = $projectId;
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        $objCases = new \BusinessModel\Cases();
        $this->view->arrComments = $objCases->getCaseNotes ($projectId, $_SESSION['user']['username'], array(
            "start" => 0,
            "limit" => 30,
            "sort" => "datetime",
            "dir" => "DESC"
                )
        );


        $this->view->writePermission = true;
    }

}
