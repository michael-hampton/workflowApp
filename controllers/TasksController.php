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

        $objCases = new Cases();
        $objCases->assignUsers ($objElements);
    }

    public function moveOnAction ($workflow, $id)
    {
        $this->view->disable ();
        $objElement = new Elements ($_SESSION['selectedRequest'], $id);

        $objCases = new Cases();
        $objCases->updateStatus ($objElement, "COMPLETE");
    }

    public function AssignAction ($user, $workflow, $id)
    {
        $this->view->disable ();
        $objElements = new Elements ($_SESSION['selectedRequest'], $id);

        $objCases = new Cases();
        $objCases->assignUsers ($objElements);
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

        $objElements = new Elements ($_SESSION['selectedRequest'], $id);

        $objCases = new Cases();
        $objCases->updateStatus ($objElements, "ABANDONED");
    }

    public function holdAction ($workflow, $id)
    {
        $this->view->disable ();


        $objElements = new Elements ($_SESSION['selectedRequest'], $id);
        $objCases = new Cases();
        $objCases->updateStatus ($objElements, "HELD");
    }

    public function rejectAction ($workflow, $id)
    {
        $this->view->disable ();


        $objElement = new Elements ($_SESSION['selectedRequest'], $id);
        $objCases = new Cases();
        $objCases->updateStatus ($objElement, "REJECT", $_POST['reason']);
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
                        $arrSteps[$workflowKey][$stepKey]['order_id'] = $arrStep['order_id'];

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
            $documentType = isset ($_POST['document_type']) ? $_POST['document_type'] : '';

            $objCases = new Cases();
            $arrFiles = $objCases->uploadCaseFiles ($_FILES, $_SESSION['selectedRequest'], $objStep, $documentType
            );
        }

        $arrStepData['claimed'] = $_SESSION["user"]["username"];
        $arrStepData["dateCompleted"] = date ("Y-m-d H:i;s");
        $arrStepData['status'] = "SAVED";


        if ( isset ($arrFiles) && !empty ($arrFiles) )
        {
            $_POST['form']['file2'] = implode (",", $arrFiles);
        }

        $objUser = (new UsersFactory)->getUser ($_SESSION['user']['usrid']);

        $validation = $objStep->save ($objElement, $_POST['form'], $objUser);

        if ( $validation === false )
        {
            $validate['validation'] = $objStep->getFieldValidation ();
            echo json_encode ($validate);
            return false;
        }

        if ( $completeStep == 1 )
        {
            $nextStep = $objStep->complete ($objElement, $arrStepData, $objUser);
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
        $status = "";
        $blCompleted = false;
        $this->view->review = false;
        $blCompletedStep = false;

        $objUser = (new UsersFactory ())->getUser ($_SESSION['user']['usrid']);

        if ( empty ($objUser) )
        {
            die ("BAD USER");
        }

        $objStepPermissions = new StepPermissions ($step);
        $blHasPermission = $objStepPermissions->validateUserPermissions ($objUser);
        $this->view->canSave = false;

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
                $objCases = new Cases();
                $users = $objCases->getUsersToReassign ($_SESSION['selectedRequest'], $id, $objUser, $objStep);

                $this->view->resource_allocator = false;

                foreach ($users['data'] as $user) {
                    if ( trim($user['username']) === trim($objUser->getUsername ()) )
                    {
                        $this->view->resource_allocator = true;
                        break;
                    }
                }
                
                $this->view->users = $users;
                $status = "ASSIGN";
            }
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

                if ( !isset ($objProject->object['audit_data']['elements'][$id]['steps'][$step]) &&
                        $step > $currentStatus )
                {
                    $arrRequest[$workflow]['steps'][$step] = $arrStepData;

                    $objStep->complete ($objElements, $arrStepData, $objUser);
                }

                if ( isset ($objProjects->object['elements'][$id][$workflow]['steps'][$step]['claimed']) )
                {
                    $showClaimed = true;
                    $this->view->assignedTo = $objProjects->object['elements'][$id][$workflow]['steps'][$step]['claimed'];
                }
            }
            else
            {
                $showClaimed = true;
                $this->view->assignedTo = $objProject->object['audit_data']['elements'][$id]['steps'][$step]['claimed'];

                $this->view->previousStepName = $arrWorkflowData['step_name'];
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
                die;
                return true;
            }

            return;
        }
        elseif ( $status == "CLAIM" )
        {
            $this->view->partial ("tasks/claimStep");
            die;
            return;
        }
        elseif ( $status == "ASSIGN" )
        {
            $this->view->partial ("tasks/assignUser");
            die;
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
                die;
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
            die;
        }
        elseif ( $status == "HELD" )
        {
            $this->view->held = true;
            $this->view->partial ("tasks/rejectionOverride");
            die;
        }
        elseif ( $status == "HOLD" )
        {
            $this->view->partial ("tasks/hold");
            die;
            return;
        }
        elseif ( $canReject === true && $blCompletedStep === false )
        {
            $this->view->partial ("tasks/manualRejection");
            die;
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

            /*             * ************** HTML FORM HERE ****************** */
            $html = '';
            $objForm = new Form();
            $html = $objForm->buildFormForStep ($objStep, $objUser, $_SESSION['selectedRequest'], $id);

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

    public function cases_ShowOutputDocumentAction ()
    {
        $this->view->disable ();

        $oAppDocument = new DocumentVersion();
        $Fields = $oAppDocument->load ($_GET['a'], $_GET['v'], $_GET['a'], false);
        $sAppDocUid = $Fields->getAppDocUid ();
        $sDocUid = $Fields->getDocUid ();
        $oOutputDocument = new OutputDocument();
        $doc = $oOutputDocument->retrieveByPk ($sDocUid);
        $download = $doc->getOutDocOpenType ();
        $info = pathinfo ($Fields->getAppDocFilename ());

        if ( !isset ($_GET['ext']) )
        {

            $ext = $info['extension'];
        }
        else
        {

            if ( $_GET['ext'] != '' )
            {

                $ext = $_GET['ext'];
            }
            else
            {

                $ext = $info['extension'];
            }
        }

        $ver = (isset ($_GET['v']) && $_GET['v'] != '') ? '_' . $_GET['v'] : '';
        $_SESSION['selectedRequest'] = 119;



        if ( !$ver ) //This code is in the case the outputdocument won't be versioned
            $ver = '_1';

        $realPath = $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/uploads/OutputDocuments/" . $_SESSION['selectedRequest'] . "/" . $sDocUid . $ver . "." . $ext;
        $realPath1 = $_SERVER['DOCUMENT_ROOT'] . '/FormBuilder/public/uploads/OutputDocuments/' . $info['basename'] . $ver . '.' . $ext;

        $realPath2 = $_SERVER['DOCUMENT_ROOT'] . '/FormBuilder/public/uploads/OutputDocuments/' . $info['basename'] . '.' . $ext;

        $sw_file_exists = false;

        if ( file_exists ($realPath) )
        {
            $sw_file_exists = true;
        }
        elseif ( file_exists ($realPath1) )
        {

            $sw_file_exists = true;

            $realPath = $realPath1;
        }
        elseif ( file_exists ($realPath2) )
        {

            $sw_file_exists = true;

            $realPath = $realPath2;
        }


        if ( !$sw_file_exists )
        {
            $error_message = "'" . $info['basename'] . $ver . '.' . $ext . "' " . 'ID_ERROR_STREAMING_FILE';
            echo $error_message;
            return false;
        }

        $nameFile = $info['basename'] . $ver . '.' . $ext;

        $objFileUpload = new FileUpload();
        $objFileUpload->streamFile ($realPath, true, $nameFile);

        die;
    }

}
