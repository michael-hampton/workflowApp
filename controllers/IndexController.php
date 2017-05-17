<?php

use Phalcon\Mvc\View;

class IndexController extends BaseController
{

    public $workflow = 3;
    public $step = 7;
    public $completeStatuses = array("complete", "abandoned");

    public function workflowAction ()
    {
        
    }

    public function indexAction ()
    {
        $objProjects = new Projects();
        $arrDepartments = $objProjects->getAllDepartments ();
        $this->view->arrProjects = $objProjects->getAllProjects ();
        $this->view->arrUsers = $objProjects->getAllUsers ();
        $this->view->arrPriorities = $objProjects->doSelect ("priority", array());

        $objWorkflowCollectionFactory = new WorkflowCollectionFactory();
        $collections = $objWorkflowCollectionFactory->getSystemWorkflowCollections ("central");

        $arrWorkflows = array();

        $intCount = 0;

        foreach ($collections as $collection) {

            if ( $collection->getParentId () == 0 )
            {
                $arrWorkflows[$collection->getDeptId ()][$intCount]['request_type'] = $collection->getName ();
                $arrWorkflows[$collection->getDeptId ()][$intCount]['dept_id'] = $collection->getDeptId ();
                $arrWorkflows[$collection->getDeptId ()][$intCount]['description'] = $collection->getDescription ();
                $arrWorkflows[$collection->getDeptId ()][$intCount]['request_id'] = $collection->getRequestId ();

                $intCount++;
            }
        }

        foreach ($arrDepartments as $key => $department) {
            if ( !isset ($arrWorkflows[$department['id']]) )
            {
                unset ($arrDepartments[$key]);
            }
        }

        $this->view->arrDepartments = $arrDepartments;

        $this->view->arrWorkflows = $arrWorkflows;
    }

    public function saveNewProjectAction ()
    {
        $this->view->disable ();
        $objSve = new Save();

        $_POST['form']['dueDate'] = date ("Y-m-d", strtotime ($_POST['form']['dueDate']));
        $_POST['form']['added_by'] = $_SESSION['user']['username'];
        $_POST['form']['date_created'] = date ("Y-m-d H:i:s");
        $_POST['form']['project_status'] = 1;
        $_POST['form']['status'] = "NEW PROJECT";
        $_POST['form']['dateCompleted'] = date ("Y-m-d H:i:s");
        $_POST['form']['claimed'] = $_SESSION['user']['username'];

        $objWorkflowCollection = new WorkflowCollection ($_POST['form']['workflow']);
        $objWorkflow = $objWorkflowCollection->getNextWorkflow ();
        $objStep = $objWorkflow->getNextStep ();
        $validation = $objStep->save ($objSve, $_POST['form']);

        if ( $validation === false )
        {
            $validate['validation'] = $objStep->getFieldValidation ();
            
            echo "<pre>";
        print_r($validate['validation']);
        die;
            
            echo json_encode ($validate);
            return false;
        }

        $id = $objSve->getId ();
        //echo $id;
    }

    public function getProjectHeaderAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objSave = new Save ($_SESSION['selectedRequest']);

        $objWorkflow = new Workflow (null, $objSave);

        $this->view->steps = $objWorkflow->getStepsForWorkflow ();
        $objStep = $objWorkflow->getNextStep ();

        if ( !empty ($objStep) && is_numeric ($objStep->getStepId ()) )
        {
            $this->view->statusId = $objStep->getStepId ();
        }

        $arrFields = $objStep->getFields ();

        $objFprmBuilder = new FormBuilder ("channelForm");

        $arrPriorities = $objSave->getPriorities ();

        $arrOptions = array();

        $intCount = 0;
        foreach ($arrPriorities as $arrPriority) {
            $arrOptions[$arrPriority['id']] = $arrPriority['name'];

            $intCount++;
        }

        foreach ($arrFields as $arrField) {

            $value = isset ($objSave->object['step_data']['job'][$arrField->getFieldId ()]) ? $objSave->object['step_data']['job'][$arrField->getFieldId ()] : '';

            if ( $arrField->getFieldId () == "priority" )
            {
                $objFprmBuilder->addElement (array("type" => $arrField->getFieldType (), "label" => $arrField->getLabel (), "name" => $arrField->getFieldName (), "id" => $arrField->getFieldId (), "options" => json_encode ($arrOptions), "is_disabled" => 1, "value" => $value));
            }
            else
            {
                $objFprmBuilder->addElement (array("type" => $arrField->getFieldType (), "label" => $arrField->getLabel (), "name" => $arrField->getFieldName (), "id" => $arrField->getFieldId (), "is_disabled" => 1, "value" => $value));
            }
        }

        $html = $objFprmBuilder->render ();

        if ( isset ($objProjects->object['job']['rejection_reason']) )
        {
            $html .= '<div class="form-group">
                <label class="col-lg-2 control-label">Reason For Rejection</label>
                
               <div class="col-lg-10">
                    <textarea disabled="disabled" class="form-control">' . $objProjects->object['job']['rejection_reason'] . '</textarea>
                </div>
            </div>';
        }

        $this->view->html = $html;

        $this->view->blComplete = $this->checkToMove ();

        if ( isset ($objSave->object['step_data']['job']['completed_by']) )
        {
            $this->view->blComplete = false;
            $this->view->statusId = 9;
        }
        elseif ( isset ($objSave->object['step_data']['job']['rejected_by']) )
        {
            $this->view->blComplete = false;
            $this->view->statusId = 9;
        }


        $this->view->resource_allocator = true;
        $this->view->inDepartment = true;
    }

    public function checkToMove ()
    {
        $objSave = new Save ($_SESSION['selectedRequest']);

        echo "<pre>";
        print_r ($objSave->object['audit_data']['elements']);

        if ( isset ($objSave->object['audit_data']) )
        {
            $arrElements = $objSave->object['audit_data']['elements'];
            $blIncomplete = 0;

            $count = count ($arrElements);

            foreach ($arrElements as $elementId => $arrElement) {

                $currentStep = $objSave->object['workflow_data']['elements'][$elementId]['current_step'];

                $objSteps = new WorkflowStep ($currentStep);

                if ( $objSteps->getNextStepId () == 0 )
                {
                    $blIncomplete++;
                }
            }

            if ( $blIncomplete != $count || $count == 0 )
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function getProjectListAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();

        $arrFormData = array();

        if ( !empty ($_REQUEST['projectPriority']) && $_REQUEST['projectPriority'] != "null" )
        {
            $arrFormData['priority'] = $_REQUEST['projectPriority'];
        }

        if ( isset ($_REQUEST['status']) && !empty ($_REQUEST['status']) )
        {
            $arrFormData['status'] = $_REQUEST['status'];
        }

        if ( isset ($_REQUEST['dept']) && is_numeric ($_REQUEST['dept']) )
        {
            $arrFormData['dept'] = $_REQUEST['dept'];
        }

        if ( isset ($_REQUEST['workflow']) && $_REQUEST['workflow'] != "null" )
        {
            $arrFormData['workflow'] = $_REQUEST['workflow'];
        }

        if ( !empty ($_REQUEST['projectAssigned']) && $_REQUEST['projectAssigned'] != "null" )
        {
            $arrFormData['assigned_to'] = $_REQUEST['projectAssigned'];
        }
        $this->view->arrProjects = $objProjects->getAllProjects ($arrFormData);
    }

    public function searchDepartmentsAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();

        $this->view->arrDepartments = $objProjects->searchDepartments ($_REQUEST['freeText']);

        $arrDeptWorkflows = $objProjects->doSelect ("workflows", array(), array(), array("id" => "ASC"));
        $arrWorkflows = array();

        foreach ($arrDeptWorkflows as $arrDeptWorkflow) {
            $arrWorkflows[$arrDeptWorkflow['dept_id']][] = $arrDeptWorkflow;
        }

        $this->view->arrWorkflows = $arrWorkflows;
    }

    public function addProjectAction ($dept = null, $workflow = null)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();

        $objWorkflowCollection = new WorkflowCollection ($workflow);
        $objWorkflow = $objWorkflowCollection->getNextWorkflow ();

        $objStep = $objWorkflow->getNextStep ();
        $stepId = $objStep->getStepId();
        
        $objForm = new Form($stepId);
        $arrFields = $objForm->getFields();
        
        $objFprmBuilder = new FormBuilder ("channelForm");
        $html = $objFprmBuilder->buildForm($arrFields);

        $html .= '<input type="hidden" id="deptId" name="form[deptId]" value="' . $dept . '">';
        $html .= '<input type="hidden" id="workflow" name="form[workflow]" value="' . $workflow . '">';

        $this->view->html = $html;
    }

    public function changeStatusAction ($projectId, $moveTo)
    {
        $this->view->disable ();
        $objSave = new Save ($projectId);
        $arrData = array();

        $arrErrors = array();

        if ( $moveTo == 4 )
        {
            if ( empty ($this->request->getPost ("reason", "string")) )
            {
                $arrErrors[] = "reasonWarning";
            }
        }

//        if($moveTo == 5) {
//            if(empty($_REQUEST["priority"]) || $_REQUEST['priority'] == "null") {
//                 $arrErrors[] = "priorityWarning";
//            }
//        }

        if ( $moveTo == 6 )
        {
            if ( empty ($_REQUEST['assignedTo']) )
            {
                $arrErrors[] = "assignWarning";
            }
        }

        if ( !empty ($arrErrors) )
        {
            echo json_encode ($arrErrors);
            return FALSE;
        }

        if ( $moveTo == 5 )
        {
            $arrData['completed_by'] = $_SESSION['user']['username'];
            $arrData['completed_date'] = date ("Y-m-d H:i:s");
            $arrData['project_status'] = 3;
            $status = "COMPLETED";
        }

        if ( $moveTo == 4 )
        {
            $arrData['rejection_reason'] = $this->request->getPost ("reason", "string");
            $arrData['project_status'] = 4;
            $arrData['date_rejected'] = date ("Y-m-d H:i:s");
            $arrData['rejected_by'] = $_SESSION['user']['username'];
            $status = "REJECTED";
        }

        if ( $moveTo == 6 )
        {
            $arrData['assigned_for'] = implode (",", $_REQUEST['assignedTo']);
        }

        $arrData['name'] = $objSave->object['step_data']['job']['name'];
        $arrData['priority'] = $objSave->object['step_data']['job']['priority'];
        $arrData['dueDate'] = $objSave->object['step_data']['job']['dueDate'];

        $objStep = new WorkflowStep (null, $objSave);
        $objStep->save ($objSave, $arrData);
        $objStep->complete ($objSave, array(
            "dateCompleted" => date ("Y-m-d H:i:s"),
            "status" => $status,
            "claimed" => $_SESSION['user']['username']
                )
        );
    }

    public function getProjectAction ($projectId, $openTab = 'project')
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Projects();
        $objProjects->setId ($projectId);
        $arrPriorities = $objProjects->doSelect ("priority", array());
        $priorites = array();

        foreach ($arrPriorities as $arrPriority) {
            $priorites[$arrPriority['id']]['style'] = $arrPriority['style'];
            $priorites[$arrPriority['id']]['name'] = $arrPriority['name'];
        }

        $objAttachments = new Attachments();
        $this->view->attachmentCount = count ($objAttachments->getAllAttachments ($projectId));

        $this->view->arrPriorities = $priorites;

        $_SESSION['selectedRequest'] = $projectId;

        $this->view->JSON = $objProjects->object;

        $this->view->projectId = $projectId;
        $this->view->openTab = $openTab;

        $this->view->blComplete = $this->checkToMove ();

        if ( isset ($objProjects->object['job']['completed_by']) )
        {
            $statusId = 3;
            $this->view->blComplete = false;
        }

        if ( isset ($objProjects->object['job']['date_rejected']) )
        {
            $statusId = 4;
            $this->view->blComplete = false;
        }
    }

}
