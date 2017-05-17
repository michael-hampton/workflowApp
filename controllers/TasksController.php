<?php

use Phalcon\Mvc\View;

class TasksController extends BaseController
{

    private $maxPagesBoxesUpAndDown = 5;
    public $completeStatuses = array("complete", "abandoned", "workflow complete");

    public function onConstruct ()
    {
        define ("PRODUCTS_PAGE_LIMIT", 20);
        session_start ();
    }

    public function indexAction ()
    {
        $this->view->disable ();
    }

    public function ClaimItAction ($workflow, $id)
    {
        $this->view->disable ();
        $objElements = new Elements ($_SESSION['selectedRequest'], $id);

        $arrStepData = array(
            'claimed' => $_SESSION["user"]["username"],
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "CLAIMED"
        );

        $objStep = new WorkflowStep (null, $objElements);
        $objStep->assignUserToStep ($objElements, $arrStepData);
    }

    public function moveOnAction ($workflow, $id)
    {
        $this->view->disable ();
        $objElement = new Elements ($_SESSION['selectedRequest'], $id);

        $arrStepData = array(
            'claimed' => $_SESSION["user"]["username"],
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "COMPLETE"
        );

        $objSteps = new WorkflowStep (null, $objElement);
        $objSteps->complete ($objElement, $arrStepData);
    }

    public function AssignAction ($user, $workflow, $id)
    {
        $this->view->disable ();
        $objElements = new Elements ($_SESSION['selectedRequest'], $id);

        $arrStepData = array(
            'claimed' => $user,
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "CLAIMED"
        );

        $objStep = new WorkflowStep (null, $objElements);
        $objStep->assignUserToStep ($objElements, $arrStepData);

        //if($_SESSION["user"]["username"] == $user) {
        //$objStep->complete($objElements, $arrStepData);
        //}
    }

    public function giveAChanceAction ($projectId, $step)
    {
        $this->view->disable ();
        $objTasks = new Tasks ($projectId);
        $objTasks->saveStepData ($step, $projectId, $_REQUEST, 1);
    }

    public function abandonAction ($workflow, $id)
    {
        $this->view->disable ();

        $arrStepData = array(
            'claimed' => $_SESSION["user"]["username"],
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "ABANDONED",
        );

        $objElements = new Elements ($_SESSION['selectedRequest'], $id);

        $objStep = new WorkflowStep (null, $objElements);
        $objStep->save ($objElements, $arrStepData);
    }

    public function holdAction ($workflow, $id)
    {
        $this->view->disable ();

        $arrStepData = array(
            'claimed' => $_SESSION["user"]["username"],
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "HELD",
        );

        $objElements = new Elements ($_SESSION['selectedRequest'], $id);
        $objStep = new WorkflowStep (null, $objElements);
        $objStep->save ($objElements, $arrStepData);
    }

    public function rejectAction ($workflow, $id)
    {
        $this->view->disable ();

        $arrStepData = array(
            'claimed' => $_SESSION["user"]["username"],
            "dateCompleted" => date ("Y-m-d H:i;s"),
            "status" => "REJECT",
            "rejectionReason" => $_POST['reason']
        );

        $objElement = new Elements ($_SESSION['selectedRequest'], $id);
        $objStep = new WorkflowStep (null, $objElement);
        $objStep->save ($objElement, $arrStepData);
    }

    public function getTasksAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Save ($projectId);

        $_SESSION['workflow'] = $objProjects->object['workflow_data']['request_id'];

        $objWorkflowCollection = new WorkflowCollection ($_SESSION['workflow']);

        //load workflow engine

        $arrAllWorkflows = $objWorkflowCollection->getMappedWorkflows ();

        $arrAll = array();

        foreach ($arrAllWorkflows as $arrAllWorkflow) {
            if ( !array_key_exists ($arrAllWorkflow['workflow_id'], $arrAll) )
            {
                $arrAll[$arrAllWorkflow['workflow_id']] = $arrAllWorkflow['workflow_name'];
            }
        }

        $this->view->arrAllWorkflows = $arrAll;
        $this->view->requestStatus = $objProjects->object['step_data']['job']['project_status'];

        $arrWorkflows = array();
        $arrUsed = array();
        $orderedjobStatusAudit = array();

        /* Do department check here for auto assign */
        $this->view->hasPermission = true;

        if ( isset ($objProjects->object['step_data']['elements']) && !empty ($objProjects->object['step_data']['elements']) )
        {
            foreach ($objProjects->object['step_data']['elements'] as $workflowId => $workflows) {

                $objElements = new Elements ($projectId, $workflowId);

                $objWorkflow = new Workflow (null, $objElements);
                $objStep = $objWorkflow->getNextStep ();
                $workflowStepId = $objStep->getWorkflowStepId ();
                $workflowKey = $objWorkflow->getWorkflowId ();

                $arrWorkflows[$workflowId][$workflowKey]['data']['current_status'] = isset ($objProjects->object['audit_data']['elements'][$workflowId]['steps'][$workflowStepId]['status']) ? strtoupper ($objProjects->object['audit_data']['elements'][$workflowId]['steps'][$workflowStepId]['status']) : 'IN PROGRESS';

                if ( $arrWorkflows[$workflowId][$workflowKey]['data']['current_status'] == "AUTO_ASSIGN" )
                {
                    $arrWorkflows[$workflowId][$workflowKey]['data']['current_status'] = "IN PROGRESS";
                }

                $steps = $objWorkflow->getStepsForWorkflow ($workflowKey);

                foreach ($steps as $stepKey => $arrStep) {

                    if ( !in_array ($arrStep['step_id'], $arrUsed) )
                    {
                        $arrSteps[$workflowKey][$stepKey]['step_id'] = $arrStep['step_id'];
                        $arrSteps[$workflowKey][$stepKey]['step_name'] = $arrStep['step_name'];
                        $arrSteps[$workflowKey][$stepKey]['completed'] = 0;
                        $arrSteps[$workflowKey][$stepKey]['conditions'] = $arrStep['step_condition'];
                        $arrSteps[$workflowKey][$stepKey]['first_step'] = $arrStep['first_step'];
                        $arrSteps[$workflowKey][$stepKey]['next_step'] = $arrStep['step_to'];
                        $arrSteps[$workflowKey][$stepKey]['id'] = $arrStep['id'];

                        if ( isset ($workflow['status']) && in_array ($workflow['status'], $this->completeStatuses) )
                        {
                            $arrSteps[$workflowKey][$stepKey]['completed'] = 1;
                        }

                        if ( isset ($objProjects->object['audit_data']['elements'][$workflowId]['steps'][$arrStep['id']]) && isset ($objProjects->object['audit_data']['elements'][$workflowId]['steps'][$arrStep['id']]['claimed']) )
                        {
                            $orderedjobStatusAudit[$workflowId][$arrStep['step_id']] = array(
                                "user_name" => $objProjects->object['audit_data']['elements'][$workflowId]['steps'][$arrStep['id']]['claimed'],
                                "datetime" => $objProjects->object['audit_data']['elements'][$workflowId]['steps'][$arrStep['id']]['dateCompleted']
                            );
                        }

                        $arrUsed[] = $arrStep['step_id'];
                    }
                }


                $stepId = $objStep->getStepId ();

                $step = $stepId;

                $_SESSION['current_step'] = $stepId;

                $this->view->assignUser = false;


                $arrWorkflowData = $steps[$workflowStepId];

                $arrWorkflows[$workflowId][$workflowKey]['data']['goldenSample'] = isset ($workflows[$workflowKey]['steps'][$workflows[$workflowKey]['current_step']]['data']['goldenSample']) && $workflows[$workflowKey]['steps'][$workflows[$workflowKey]['current_step']]['data']['goldenSample'] == "true" ? "Y" : "N";

                $arrWorkflows[$workflowId][$workflowKey]['data']['nextStatus'] = $arrWorkflowData['step_to'];
                $arrWorkflows[$workflowId][$workflowKey]['data']['currentStep'] = $step;
                $arrWorkflows[$workflowId][$workflowKey]['data']['initial_step'] = $steps[0]['step_id'];
                $arrWorkflows[$workflowId][$workflowKey]['data']['id'] = $workflowId;
                if ( isset ($objProjects->object['step_data']['elements'][$workflowId][$workflowKey]['id']) )
                {
                    $arrWorkflows[$workflowId][$workflowKey]['data']['element_id'] = $workflows[$workflowKey]['id'];
                }
                else
                {
                    $arrWorkflows[$workflowId][$workflowKey]['data']['element_id'] = str_pad ($workflowId, 8, '0', STR_PAD_LEFT);
                }

                //if there is condition on the step
                if ( isset ($arrWorkflowData["step_condition"]) && !empty ($arrWorkflowData["step_condition"]) )
                {

                    //claim step conditions from the workflow engine respose
                    $conditions = json_decode ($arrWorkflowData["step_condition"], true);
                }
            }

            $this->view->arrSteps = $arrSteps;
            $this->view->arrWorkflows = $arrWorkflows;
            $this->view->complete = false;

            $this->view->step = $step;

            $this->view->jobStatusAudit = $orderedjobStatusAudit;
        }

        if ( isset ($objProjects->object['step_data']['job']['completed_by']) || isset ($objProjects->object['step_data']['job']['date_rejected']) )
        {
            $this->view->showButton = false;
        }
        else
        {
            $this->view->showButton = true;
        }
    }

    public function completeStepAction ($id, $workflow, $completeStep, $step)
    {
        $this->view->disable ();

        $objElement = new Elements ($_SESSION['selectedRequest'], $id);
        $objStep = new WorkflowStep (null, $objElement);

        if ( isset ($_FILES['fileUpload']) )
        {
            if ( isset ($_FILES['fileUpload']['name'][0]) && !empty ($_FILES['fileUpload']['name'][0]) )
            {
                foreach ($_FILES['fileUpload']['name'] as $key => $value) {
                    $fileContent = file_get_contents ($_FILES['fileUpload']['tmp_name'][$key]);

                    $arrData = array(
                        "source_id" => $_SESSION['selectedRequest'],
                        "filename" => $value,
                        "date_uploaded" => date ("Y-m-d H:i:s"),
                        "uploaded_by" => $_SESSION['user']['username'],
                        "contents" => $fileContent,
                        "files" => $_FILES,
                        "file_type" => $_POST['document_type'],
                        "step" => $objStep
                    );

                    $objAttachments = new Attachments();
                    $objAttachments->loadObject ($arrData);
                    $fileId = $objAttachments->save ();
                    $arrFiles[] = $fileId;
                }
            }
            else
            {
                if ( $completeStep == 1 )
                {
                    $arrErrors[] = "file";
                }
            }
        }

        $arrStepData['claimed'] = $_SESSION["user"]["username"];
        $arrStepData["dateCompleted"] = date ("Y-m-d H:i;s");
        $arrStepData['status'] = "SAVED";


        if ( isset ($arrFiles) && !empty ($arrFiles) )
        {
            $_POST['form']['file2'] = implode (",", $arrFiles);
        }

        $validation = $objStep->save ($objElement, $_POST['form']);

        if ( $validation === false )
        {
            $validate['validation'] = $objStep->getFieldValidation ();
            echo json_encode ($validate);
            return false;
        }

        if ( $completeStep == 1 )
        {
            $nextStep = $objStep->complete ($objElement, $arrStepData);
        }

        if ( is_numeric ($nextStep->getStepId ()) && $nextStep->getStepId () != 0 )
        {
            echo json_encode (array("validation" => "OK",
                "next_step" => $nextStep->getStepId ()));
        }
        else
        {
            echo json_encode (array("validation" => "OK",
                "next_step" => ""));
        }
    }

    public function completeMessageAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
    }

    public function getStepContentAction ($id, $step, $workflow)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objElement = new Elements ($_SESSION['selectedRequest'], $id);
        $objProject = new Save ($_SESSION['selectedRequest']);
        $this->view->step = $step;
        $this->view->requestRejected = false;

        $objWorkflow = new Workflow (null, $objElement);
        $objStep = $objWorkflow->getNextStep ();

        $nextStep = $objStep->getWorkflowStepId ();
        $arrSteps = $objWorkflow->getStepsForWorkflow ();
        $arrWorkflowData = $arrSteps[$nextStep];
        $currentStepId = $objStep->getStepId ();

        if ( $currentStepId != $step )
        {
            $blShowPrevious = true;
        }
        else
        {
            $blShowPrevious = false;
        }

        if ( $arrWorkflowData['first_step'] == 1 )
        {
            $this->view->firstStep = true;
        }
        else
        {
            $this->view->firstStep = false;
        }

        $this->view->previousStepName = $arrWorkflowData['step_name'];

        $showClaimed = false;
        $canReject = false;
        $blIsClaimed = false;
        $blHasPermission = true;
        $status = "";
        $blCompleted = false;
        $this->view->review = false;
        $blCompletedStep = false;

        $objUsers = new UsersFactory ($_SESSION['user']['usrid']);
        $objUsersArr = $objUsers->getUsers ();

        if ( empty ($objUsersArr) )
        {
            die ("BAD USER");
        }

        $objStepPermissions = new StepPermissions ($step);
        $blHasPermission = $objStepPermissions->validateUserPermissions ($objUsersArr[0]);

        if ( !$blHasPermission )
        {
            $this->view->message = array(
                "type" => "nopermission",
                "message" => "You have no permission to complete this step."
            );

            $blHasPermission = false;
        }

        if ( $blHasPermission === 1 )
        {
            // write access
            $this->view->canSave = true;
        }
        elseif ( $blHasPermission === 2 )
        {
            // read only access
            $this->view->canSave = false;
        }


        if ( $step < $currentStepId )
        {
            $blCompletedStep = true;
        }

        // may need to change
        if ( isset ($objProject->object['workflow_data']['elements'][$id]['status']) && in_array (strtolower ($objProject->object['workflow_data']['elements'][$id]['status']), $this->completeStatuses) )
        {
            $blCompleted = true;
        }

        $arrConditions = $objStep->getConditions ();

        /*         * ******************** Check workflow conditions. Condition determines the view ************************************** */

        if ( isset ($arrConditions['reject']) && $arrConditions['reject'] == "Yes" )
        {
            $canReject = true;
        }

        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$step]['status']) &&
                $objProject->object['audit_data']['elements'][$id]['steps'][$step]['status'] == "IN REVIEW" )
        {
            $this->view->review = true;
        }

        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status']) &&
                $objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status'] == "ABANDONED" )
        {
            $status = "ABANDONED";
        }

        if ( isset ($arrConditions['hold']) && $arrConditions['hold'] == "Yes" && $blCompletedStep === false )
        {
            $status = "HOLD";
        }

        if ( isset ($arrConditions['claimStep']) && $arrConditions['claimStep'] == "Yes" &&
                !isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['claimed']) &&
                $step >= $currentStepId )
        {
            $status = "CLAIM";
        }


        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status']) &&
                $objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status'] == "REJECT" )
        {
            $status = "REJECTED";
        }

        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status']) &&
                $objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status'] == "HELD" )
        {
            $status = "HELD";
        }



        if ( isset ($arrConditions["doAllocation"]) && $arrConditions["doAllocation"] == "Yes" )
        {
            //was the stop alredy assigned
            if ( !empty ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]["claimed"]) )
            {
                //it was

                if ( trim ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]["claimed"]) != trim ($_SESSION["user"]["username"]) )
                {

                    //DONE
                    $this->view->message = array(
                        "type" => "somebodyelse",
                        "message" => "You have no permission to complete this step.<br>
														This step has already been assigned to ",
                        "user" => $objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]["claimed"]
                    );

                    $blIsClaimed = true;
                    $blHasPermission = false;
                }
            }
            else
            {
                //it was not yet
                //$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
                //$this->view->resource_allocator = $this->hasPermission(RESOURCE_ALLOCATOR);
                $this->view->resource_allocator = true;
                $status = "ASSIGN";
                $this->view->requiredRole = $arrConditions["permissionId"];
            }
        }
        else
        {
            
        }

        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$step]["claimed"]) &&
                !empty ($objProject->object['audit_data']['elements'][$id]['steps'][$step][$step]) &&
                trim ($objProject->object['audit_data']['elements'][$id]['steps'][$step]["claimed"]) != $_SESSION["user"]["username"]
        )
        {

            $this->view->message = array(
                "type" => "someoneClaimed",
                "message" => "This step has been already claimed by",
                "user" => $objProject->object['audit_data']['elements'][$id]['steps'][$step]["claimed"]
            );

            $blHasPermission = false;
        }

        //check if the request was rejected

        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status']) &&
                $objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status'] == "REJECT" )
        {
            $requestRejected = true;
        }

        if ( isset ($arrConditions['autoAssign']) &&
                $arrConditions['autoAssign'] == "Yes" &&
                $blHasPermission !== false )
        {
            if ( !isset ($objProject->object['audit_data']['elements'][$id]['steps'][$step]['claimed']) )
            {
                $currentStatus = $currentStepId;

                $arrStepData = array(
                    'claimed' => $_SESSION["user"]["username"],
                    "dateCompleted" => date ("Y-m-d H:i;s"),
                    "status" => "AUTO_ASSIGN"
                );

                $objElements = new Elements ($_SESSION['selectedRequest'], $id);
                //$objStep->complete($objElements, $arrStepData);

                if ( !isset ($objProject->object['audit_data']['elements'][$id]['steps'][$step]) &&
                        $step > $currentStatus )
                {
                    $arrRequest[$workflow]['steps'][$step] = $arrStepData;

                    $objStep->complete ($objElements, $arrStepData);
                }
                else
                {
                    
                }

                if ( isset ($objProjects->object['elements'][$id][$workflow]['steps'][$step]['claimed']) )
                {
                    $showClaimed = true;
                    $this->view->assignedTo = $objProjects->object['elements'][$id][$workflow]['steps'][$step]['claimed'];
                }
            }
            else
            {
                //if ( isset ($arrWorkflowData[0]['first_step']) && $arrWorkflowData[0]['first_step'] != 1 )
                //{
                $showClaimed = true;
                $this->view->assignedTo = $objProject->object['audit_data']['elements'][$id]['steps'][$step]['claimed'];

                $this->view->previousStepName = $arrWorkflowData['step_name'];
                //}
            }
        }

        /*         * ***************************** Display a view based on condition checks above ************************** */
        $this->view->canMoveOn = $workflow == 8 ? false : true;
        $this->view->held = false;
        $this->view->blCompletedStep = $blCompletedStep;

        $this->view->rejectionMessage = isset ($arrRequest[$workflow]['steps'][$step]['rejectionMessage']) ? $arrRequest[$workflow]['steps'][$step]['rejectionMessage'] : '';

        $arrCurrentData = array();
        //$validate = $objWorkflow->complete (array("step" => array()));


        foreach ($arrSteps as $arrStep) {
            if ( $arrStep['step_from'] == $step )
            {
                $stepFrom = $arrStep['id'];
            }
        }

        $this->view->summary = $this->buildSummaryAction ($workflow, $id);
        if ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$stepFrom]['claimed']) )
        {
            $this->view->assignedTo = $objProject->object['audit_data']['elements'][$id]['steps'][$stepFrom]['claimed'];
        }

        if ( $blCompleted === true )
        {
            if ( $blShowPrevious === true )
            {
                $this->view->partial ("tasks/assignedTo");
                return true;
            }
            else
            {
                $this->view->partial ("tasks/completeMessage");
                return true;
            }

            return;
        }
        elseif ( $status == "CLAIM" )
        {
            $this->view->partial ("tasks/claimStep");
            return;
        }
        elseif ( $status == "ASSIGN" )
        {
            $this->view->partial ("tasks/assignUser");
            return;
        }

        if ( $blHasPermission === false )
        {
            if ( isset ($this->view->message) && $this->view->message['type'] == "somebodyelse" )
            {
                $this->view->partial ("tasks/somebodyelse");
                return;
            }
            elseif ( isset ($this->view->message) && $this->view->message['type'] == "someoneClaimed" )
            {
                $this->view->pick ("steps/someoneClaimed");
            }
            elseif ( isset ($this->view->message) && $this->view->message['type'] == "nopermission" )
            {
                $this->view->partial ("tasks/nopermission");
                return false;
            }
        }

        $this->view->workflow = $workflow;
        $this->view->id = $id;

        if ( $status == "REJECTED" )
        {
            if ( $workflow == 7 )
            {

                if ( $blShowPrevious === true )
                {
                    $this->view->pick ("steps/assignedTo");
                    return;
                }
                else
                {
                    $this->view->message = array(
                        "type" => "someoneClaimed",
                        "message" => "This sample has failed testing"
                    );

                    $this->view->partial ("tasks/rejected");
                    return;
                }
            }
            elseif ( $blCompletedStep === false )
            {
                $this->view->partial ("tasks/rejectionOverride");
                return;
            }
        }
        elseif ( isset ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status']) &&
                ($objProject->object['audit_data']['elements'][$id]['steps'][$arrWorkflowData['id']]['status'] == "ABANDONED" ||
                (isset ($requestRejected) && $requestRejected === true)) )
        {
            $this->view->message = array(
                "type" => "someoneClaimed",
                "message" => "This request has been abandoned or rejected."
            );

            $this->view->partial ("tasks/rejected");
        }
        elseif ( $status == "HELD" )
        {
            $this->view->held = true;
            $this->view->partial ("tasks/rejectionOverride");
        }
        elseif ( $status == "HOLD" )
        {
            $this->view->partial ("tasks/hold");
            return;
        }
        elseif ( $canReject === true && $blCompletedStep === false )
        {
            $this->view->partial ("tasks/manualRejection");
            return;
        }
        elseif ( $showClaimed === true && empty ($validate['validation']) )
        {
            $this->view->partial ("tasks/assignedTo");
            return;
        }

        $this->view->nextStatus = $arrWorkflowData['step_to'];

        $this->view->currentStep = $step;

        /*         * ****************** Build fields to display on screen ********************* */

        if ( $blCompletedStep !== true )
        {

            $objFprmBuilder = new FormBuilder ("BaseData");

            $arrFields = $objStep->getFields ();

            $this->view->showButton = false;

            $objForm = new Form ($currentStepId);
            $arrForm = $objForm->getFields ();
            $arrDocs = $objForm->getInputDocuments ();
            $html = $objFprmBuilder->buildForm ($arrForm, $arrDocs);

            $html .= '<button style="display: none;" type="button" class="btn btn-primary pull-right" id="Resubmit">Resubmit</button>';
            $html .= "</form>";

            //if($step == 200 && $workflow == 120) {
            $html .= '<a refno="' . $id . '" class="pull-right generateBarcode">Print Barcode</a>';
            //}

            $this->view->html = $html;

            $this->view->partial ("tasks/getStepContent");
            return;
        }
        elseif ( $blCompletedStep === true )
        {
            $this->view->showAssigned = true;
        }

        $this->view->partial ("tasks/getStepContent");
        return;
    }

    public function buildSummaryAction ($workflow, $id)
    {
        $this->view->disable ();
        $objElement = new Elements ($_SESSION['selectedRequest'], $id);

        $objWorkflow = new Workflow ($workflow, null);
        $objStep = $objWorkflow->getNextStep ();

        $arrFields = $objStep->getFields ();

        $objFprmBuilder = new FormBuilder ("BaseData");

        foreach ($arrFields as $arrField) {

            if ( $arrField->getFieldId () != "file2" )
            {
                $value = isset ($objElement->arrElement[$arrField->getFieldId ()]) ? $objElement->arrElement[$arrField->getFieldId ()] : '';

                $objFprmBuilder->addElement (array("type" => $arrField->getFieldType (), "label" => $arrField->getLabel (), "name" => $arrField->getFieldName (), "id" => $arrField->getFieldId (), "value" => $value, "is_disabled" => 1));
            }
        }

        $html = $objFprmBuilder->render ();

        $arrFiles = array();
        $arrFiles2 = array();

        if ( isset ($objElement->arrElement['file2']) )
        {
            $arrFiles2 = explode (",", $objElement->arrElement['file2']);
        }

        if ( isset ($objElement->arrElement['rejectionReason']) )
        {
            $rejectionReason = $objElement->arrElement['rejectionReason'];
        }

        if ( isset ($objElement->arrElement['file2']) )
        {
            $arrFiles = explode (",", $objElement->arrElement['file2']);
        }

        $arrFiles = array_merge ($arrFiles, $arrFiles2);

        if ( isset ($rejectionReason) && !empty ($rejectionReason) )
        {
            $html .= '<div class="form-group">
                <label class="col-lg-2 control-label">Reason For Rejection</label>
                <div class="col-lg-10">
           <textarea class="form-control">' . $rejectionReason . '</textarea>
        </div>';
        }

        $objAttachments = new Attachments();

        if ( isset ($arrFiles) )
        {
            $html .= '<div class="col-lg-12 pull-left">';
            foreach ($arrFiles as $file) {
                $objAttachments->setId ($file);
                $arrAttachment = $objAttachments->getAttachment ();

                if ( !empty ($arrAttachment) )
                {

                    foreach ($arrAttachment as $attachment) {

                        $html .= '<div class="file-box">
                                        <div class="file">
                                            <a href="/attachments/download/' . $attachment['id'] . '">
                                                <div class="icon">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                <div class="file-name">
                                                   ' . $attachment['filename'] . '
                                                    <br>
                                                    <small>Added:' . $attachment['uploaded_by'] . ' <br> ' . date ("M d, Y", strtotime ($attachment["date_uploaded"])) . '</small>
                                                </div>
                                            </a>
                                        </div>
                                    </div>';
                    }
                }
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function createNewElementAction ($workflow)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objWorkflow = new Workflow ($workflow, null);
        $objStep = $objWorkflow->getNextStep ();

        $arrFields = $objStep->getFields ();

        $objFprmBuilder = new FormBuilder ("AddNewForm");

        foreach ($arrFields as $arrField) {
            $objFprmBuilder->addElement (array("type" => $arrField->getFieldType (), "label" => $arrField->getLabel (), "name" => $arrField->getFieldName (), "id" => $arrField->getFieldId ()));
        }

        $html = '<div style="display:none;" class="alert alert-warning">Element could not be saved successfully </div>';
        $html .= '<div style="display:none;" id="fileWarning" class="alert alert-warning">You must upload a file</div>';
        $html .= '<div style="display:none;" id="elementSuccess" class="alert alert-success">Element was saved successfully</div>';
        $html .= $objFprmBuilder->render ();
        $html .= '<input type="hidden" id="startingStep" name="form[current_step]" value="' . $startingStep . '">';
        $html .= '<button id="saveNew" type="button" class="btn btn-primary btn-sm m-l-xs pull-right">Save</button>';
        $html .= '<button id="Close" type="button" class="btn btn-primary btn-sm pull-right">Close</button>';


        $this->view->html = $html;
    }

    public function saveNewElementAction ($workflow)
    {
        $arrErrors = array();
        $arrFiles = array();

        $objWorkflow = new Workflow ($workflow);
        $objStep = $objWorkflow->getNextStep ();
        $stepId = $objStep->getStepId ();

        if ( isset ($_FILES['fileUpload']) )
        {
            if ( isset ($_FILES['fileUpload']['name'][0]) && !empty ($_FILES['fileUpload']['name'][0]) )
            {
                foreach ($_FILES['fileUpload']['name'] as $key => $value) {

                    $fileContent = file_get_contents ($_FILES['fileUpload']['tmp_name'][$key]);

                    $arrData = array(
                        "source_id" => $_SESSION['selectedRequest'],
                        "filename" => $value,
                        "date_uploaded" => date ("Y-m-d H:i:s"),
                        "uploaded_by" => $_SESSION['user']['username'],
                        "contents" => $fileContent,
                        "files" => $_FILES,
                        "step" => $objStep
                    );

                    $objAttachments = new Attachments();
                    $objAttachments->loadObject ($arrData);
                    $id = $objAttachments->save ();
                    $arrFiles[] = $id;
                }
            }
            else
            {
                $arrErrors[] = "file";
            }
        }


        if ( empty ($arrErrors) )
        {
            $_POST['form']['source_id'] = $_SESSION['selectedRequest'];

            if ( isset ($arrFiles) && !empty ($arrFiles) )
            {
                $_POST['form']['file2'] = implode (",", $arrFiles);
            }

            $_POST['form']['status'] = "NEW";
            $_POST['form']['workflow_id'] = $workflow;
            $_POST['form']['claimed'] = $_SESSION["user"]["username"];
            $_POST['form']['dateCompleted'] = date ("Y-m-d H:i:s");

            $objElements = new Elements ($_SESSION['selectedRequest']);

            $validation = $objStep->save ($objElements, $_POST['form']);

            if ( $validation === false )
            {
                $validate['validation'] = $objStep->getFieldValidation ();
                echo json_encode ($validate);
                return false;
            }

            $this->view->disable ();


//            $objWorkflow = new Workflow (null, null, $objElements);
//            $validate = $objWorkflow->complete ($_POST['form'], $objElements);
//
//            if ( $validate === false )
//            {
//                $validate['validation'] = $objWorkflow->getObjValidation ();
//            }
//
//
//            if ( !empty ($validate['validation']) )
//            {
//                echo json_encode ($validate);
//                return false;
//            }
//
//
//
//            //$validation = $objWorkflow->complete (array("element" => $_POST['form']));
//
//            if ( !empty ($validation['validation']) )
//            {
//                echo json_encode ($validation['validation']);
//                return false;
//            }
        }
        else
        {
            echo json_encode ($arrErrors);
            return false;
        }
    }

}