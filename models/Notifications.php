<?php
class Notifications extends BaseModel
{
    private $arrProjects = array(
        "projectId" => "",
        "STATUS" => "",
        "author" => "",
        "ASSIGNED" => "",
        "ACCEPTED" => "",
        "COMPLETED" => "",
        "PROJECT_NAME" => "",
        "username" => "",
        "REJECTION_REASON" => "",
        "REJECTED" => "admin",
        "DATE_COMPLETED" => ""
    );
    
    private $arrFields = array("PROJECT_NAME" => "project_name",
        "ACCEPTED" => "accepted"
        );
    
    public $objNotifications;
    public $objProjects;
    
    public function onConstruct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/core/app/models/sendNotification.php';
        $this->objNotifications = new SendNotification();
        $this->objProjects = new Projects();
    }
    
    public function setDateCompleted($date)
    {
        $this->arrProjects['DATE_COMPLETED'] = $date;
    }


    public function setRejectionReason($reason)
    {
        $this->arrProjects['REJECTION_REASON'] = $reason;
    }

        public function setProjectId($projectId)
    {
        $this->arrProjects['projectId'] = $projectId;
    }
    
    public function setStatus($status)
    {
        $this->arrProjects['STATUS'] = $status;
    }
    
    public function setAuthor($author)
    {
        $this->arrProjects['author'] = $author;
    }
    
    public function setCompletedBy($completedBy)
    {
        $this->arrProjects['COMPLETED'] = $completedBy;
    }
    
    public function setAssignedBy($assignedBy)
    {
        $this->arrProjects['ASSIGNED'] = $assignedBy;
    }
    
    public function setAcceptedBy($accepted)
    {
        $this->arrProjects['ACCEPTED'] = $accepted;
    }

    public function setProjectName($projectName)
    {
        $this->arrProjects['PROJECT_NAME'] = $projectName;
    }
    
    public function setUsername($username)
    {
        $this->arrProjects['username'] = $username;
    }
    
    public function sendNotification($status, $projectId, $arrProject)
    {
        
        /*$arrStepData = json_decode($arrProject[0]['step_data'], TRUE);
        
        $this->setStatus($status);
        $this->setAcceptedBy($arrStepData['accepted_by']);
        $this->setProjectName($arrProject[0]['project_name']);
        if(isset($arrProject[0]['rejection_reason'])) {
            $this->setRejectionReason($arrProject[0]['rejection_reason']);
        }
        
        $this->setAssignedBy($arrStepData['assigned_for']);
        
        if(isset($arrProject[0]['status_description'])) {
            $this->setStatus($arrProject[0]['status_description']);
        }
        
        if(isset($arrStepData['completed_by'])) {
             $this->setCompletedBy($arrStepData['completed_by']);
        }
        
        if(isset($arrStepData['completed_date'])) {
            $this->setDateCompleted($arrStepData['completed_date']);
        }
       
        
        
//        foreach ($arrParameters as $arrParameter) {
//            
//        }
//        
        echo '<pre>';
        print_r($arrProject);*/
        
        require_once $_SERVER['DOCUMENT_ROOT'].'/core/app/models/sendNotification.php';
        $objNotifications = new SendNotification();
        $objNotifications->buildEmail($arrProject[0], $status);
    }
}
