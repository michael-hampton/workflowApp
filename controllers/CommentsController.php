<?php
use Phalcon\Mvc\View;
class CommentsController extends BaseController
{ 
    public function getJobCommentsOnlyAction($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objComments = new Comments();
        $this->view->arrComments = $objComments->getAllComments($projectId);
    }
    
    public function saveCommentsAction()
    {
        $this->view->disable();
        $objComments = new Comments();
        
        $comment = $this->request->getPost("comment", "string");
        $projectId = $this->request->getPost("projectId", "int");
        
        $arrComment = array(
            "source_id" => $projectId, 
            "comment" => $comment, 
            "username" => $_SESSION['user']['username'], 
            "datetime" => date("Y-m-d H:i:s")
            );
        
        
        if(empty($comment)) {
            echo 'ERROR';
            return false;
        }
        
        $objComments->loadObject($arrComment);
        $objComments->save();
    }
    
    public function getCommentsAction($projectId)
    {
        $this->view->projectId = $projectId;
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objComments = new Comments();
        $this->view->arrComments = $objComments->getAllComments($projectId);
        $this->view->writePermission = true;
        
    }
}