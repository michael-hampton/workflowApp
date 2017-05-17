<?php

class Request extends BaseModel
{

    protected $objData = array();
    public $step;
    protected $workflow;
    protected $status;
    public $data = array();
    protected $projectId;
    protected $currentStep;
    public $objProjectData = array();

    public function __construct ($projectId = null)
    {
        parent::__construct ();

        if ( $projectId != NULL )
        {
            $this->setProjectId ($projectId);

            $arrResults = $this->_select ("projects", array(), array("id" => $this->projectId));
            $this->objProjectData = $arrResults[0];

            if ( !empty ($arrResults[0]['step_data']) )
            {
                $arrData = json_decode ($arrResults[0]['step_data'], TRUE);
                $this->data = $arrData;

                /*foreach ($arrData as $key => $arrResult) {
                    
                    
                    $this->objData[$key] = array(
                        "step" => $arrResults[0]['step'],
                        "status" => $arrResults[0]['project_status'],
                        //"data" => $arrResult['data']
                    );
                }
                
                //die;
                
                if(isset($arrResults[0]['step']) && !empty($arrResults[0]['step'])) {
                    $this->currentStep = $arrResults[0]['step'];
                }*/
            }
        }
    }

    public function setStep ($step)
    {
        $this->step = $step;
        $this->objData[$this->step]["step"] = $this->step;
    }
    
    public function setCompleted($completed)
    {
        $this->objData[$this->step]["completed"] = $completed;
    }

    public function setData ($data)
    {
        $this->objData[$this->step]["data"] = $data;
    }

    public function setWorkflow ($workflow)
    {
        $this->workflow = $workflow;
        $this->objData[$this->step]["workflow"] = $this->workflow;
    }

    public function setStatus ($status)
    {
        $this->status = $status;
        $this->objData[$this->step]["status"] = $this->status;
    }

    public function save ()
    {
        $this->_update ("projects", array("step_data" => json_encode ($this->objData)), array("id" => $this->projectId));
    }

    public function setProjectId ($projectId)
    {
        $this->projectId = $projectId;
    }

}
