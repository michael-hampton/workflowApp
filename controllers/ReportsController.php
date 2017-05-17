<?php
use Phalcon\Mvc\View;
class ReportsController extends BaseController
{  
    private $maxPagesBoxesUpAndDown = 5;
    private $objTasks;
    
    public function onConstruct()
    {
        define ( "PRODUCTS_PAGE_LIMIT", 20 );
        session_start ();
        require_once $_SERVER['DOCUMENT_ROOT'].'/FormBuilder/app/libraries/gantt/gantti.php';
        $this->objTasks = new Tasks();
    }
    
    public function indexAction()
    {
        
        $arrTasks = $this->objTasks->getFullAudit();
        $arrAllTasks = array();
        $formattedArray = array();
        $arrRejected = array();
        $arrAccepted = array();
        
        foreach ($arrTasks as $arrTask) {
            $arrAllTasks[$arrTask['project_id']][$arrTask['step_completed']]['label'] = $arrTask['project_name'] . " - " . $arrTask['step_name'];
            $arrAllTasks[$arrTask['project_id']][$arrTask['step_completed']]['id'] = $arrTask['project_id'] . "-". $arrTask['step_completed'];

            switch($arrTask['audit_type']) {
                case "1":
                    $arrAllTasks[$arrTask['project_id']][$arrTask['step_completed']]['start'] = $arrTask['audit_date'];
                break;

                case "2":
                    $arrAllTasks[$arrTask['project_id']][$arrTask['step_completed']]['end'] = $arrTask['audit_date'];

                break;

                case "3":
                    $arrRejected[] = $arrTask['project_id'];
                break;

                case "4":
                    $arrAccepted[] = $arrTask['project_id'];
                break;
            }
        }

        foreach ($arrAllTasks as $key => $arrAllTask)
        {
            foreach ($arrAllTask as $k => $v) {
                if(  in_array ($key, $arrRejected)) {
                    $v['class'] = "urgent";
                }
                
                if(  in_array ($key, $arrAccepted)) {
                    $v['class'] = "important";
                }
                
                if($k != 0) {
                     $formattedArray[] = $v;
                }
            }
        }
  
        $this->view->gantti = new Gantti($formattedArray, array(
            'title'      => 'Demo',
            'cellwidth'  => 25,
            'cellheight' => 35,
            'today'      => true
          ));

    }
}