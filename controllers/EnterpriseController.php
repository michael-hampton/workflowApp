<?php

use Phalcon\Mvc\View;

class EnterpriseController extends \Phalcon\Mvc\Controller
{

    /**
     *
     * @var type 
     */
    private $strMessage;

    public function multipleUploadAction ()
    {
        
    }

    public function processFilesAction ()
    {
        $this->view->disable ();

        require($_SERVER['DOCUMENT_ROOT'] . '/FormBuilder/app/models/UploadHandler.php');
        $upload_handler = new UploadHandler();
        die;
    }

    private function generateNewCSCFile ($folderName)
    {
        $arrTest = array(
            0 =>
            array(
                "location" => "location",
                "dueDate" => "31/10/2017",
                "batch" => "mike",
                "sampleRef" => "test",
                "description" => "description",
                "name" => "name",
                "workflow_id" => 7,
                "usrid" => 2
            )
        );

        $this->array2csvFile ($_SERVER['DOCUMENT_ROOT'] . "/" . $folderName . "/new2.csv", $arrTest);
    }

    private function ftpHandler ()
    {

        try {
            $objMysql = new Mysql2();

            $arrFtp = $objMysql->_select ("ftp_settings", [], []);

            if ( !isset ($arrFtp[0]) || empty ($arrFtp[0]) )
            {
                return false;
            }

            $this->generateNewCSCFile ($arrFtp[0]['folderName']);

            $ftp_server = $arrFtp[0]['ftp_address'];
            $ftp_user = $arrFtp[0]['username'];
            $ftp_pass = $arrFtp[0]['password'];

            $conn_id = ftp_connect ($ftp_server) or die ("Couldn't connect to $ftp_server");

            // login with username and password
            $login_result = ftp_login ($conn_id, $ftp_user, $ftp_pass);

            if ( $login_result === false )
            {
                throw new Exception ("Failed to connect");
            }

// get contents of the current directory
            $contents = ftp_nlist ($conn_id, "testFtp");

            $objCases = new \BusinessModel\Cases();


            $arrNewCases = [];

// output $contents
            foreach ($contents as $strFilename) {

                $tmp_handle = fopen ('php://temp', 'r+');

                ob_start ();
                $result = ftp_get ($conn_id, "php://output", $strFilename, FTP_BINARY);

                if ( $result === false )
                {
                    $this->strMessage .= "FAILED TO READ FILE";
                    return false;
                }

                $data = ob_get_contents ();
                ob_end_clean ();

                $arr = explode ("\n", $data);
                $header = explode (",", $arr[0]);


                if ( empty ($header) )
                {
                    $this->strMessage .= "HEADER IS EMPTY";
                    return false;
                }
                unset ($arr[0]);

                $arr = array_values (array_filter ($arr));

                if ( empty ($arr) )
                {
                    $this->strMessage .= "Array is empty";
                    return false;
                }

                foreach ($arr as $line) {
                    $csvData = $this->comma_separated_to_array ($line);

                    $arrCase = array("form" => array_combine ($header, $csvData));

                    $userId = $arrCase['form']['usrid'];

                    $objUser = (new \BusinessModel\UsersFactory)->getUser ($userId);

                    if ( $objUser === false )
                    {
                        $this->strMessage .= "INCORRECT USER GIVEN";
                        return false;
                    }

                    $objWorkflow = new Workflow ($arrFtp[0]['workflow_id']);

                    if ( $objWorkflow === false )
                    {
                        $this->strMessage .= "Failed to load workflow";
                        return false;
                    }

                    $arrResult = $objCases->addCase ($objWorkflow, $objUser, $arrCase, []);

                    $arrNewCases[] = $arrResult;

                    if ( $arrResult === false )
                    {
                        $this->strMessage .= "Failed to create case";
                        return false;
                    }

                    $projectId = $arrResult['project_id'];
                    $caseId = $arrResult['case_id'];

                    $objElement = new Elements ($projectId, $caseId);
                    $objCases->updateStatus ($objElement, $objUser, "COMPLETE");

                    $objStep = new WorkflowStep (null, $objElement);

                    if ( $objStep === false )
                    {
                        $this->strMessage .= "Failed to load step";
                        return false;
                    }

                    $stepId = $objStep->getStepId ();

                    if ( !is_numeric ($stepId) )
                    {
                        $this->strMessage .= "Incorrect step id given";
                        return false;
                    }

                    $formattedFilename = $projectId . "_" . date ("Ymd") . ".csv";
                    $newPath = $_SERVER['DOCUMENT_ROOT'] . "/core/public/ftp";

                    $folderId = (new \AppFolder())->createFromPath ($newPath);

                    $myfile = fopen ($newPath . "/" . $formattedFilename, "w") or die ("Unable to open file!");

                    fwrite ($myfile, $data);
                    fclose ($myfile);

                    $arrParameters = array(
                        "folderId" => $folderId,
                        "filename" => $formattedFilename,
                        "document_id" => 40,
                        "app_uid" => $projectId,
                        "document_title" => "FTP FILE ADDED",
                        "document_comment" => "",
                        "del_index" => $stepId
                    );

                    // update version
                    $result = (new DocumentVersion())->create ($arrParameters, $objUser);

                    if ( !$result )
                    {
                        $this->strMessage .= "Failed to save input document";
                        return false;
                    }
                }

                return $arrNewCases;
            }
        } catch (Exception $ex) {
            $ex->getMessage ();
        }
    }

    /**
     * 
     * @return boolean
     * @throws Exception
     */
    public function ftpAction ()
    {
        $this->view->disable ();

        try {
            $results = $this->ftpHandler ();

            if ( $results === false )
            {
                echo $this->strMessage;
                return false;
            }

            echo '<ul>';
            foreach ($results as $result) {
                foreach ($result as $key => $value) {
                    echo "<li><strong>$key</strong>: $value</li>";
                }
            }

            echo '</ul>';
            
        } catch (Exception $ex) {
            echo $ex->getMessage ();
        }
    }

    /**
     * @param $string - Input string to convert to array
     * @param string $separator - Separator to separate by (default: ,)
     *
     * @return array
     */
    private function comma_separated_to_array ($string, $separator = ',')
    {
        //Explode on comma
        $vals = explode ($separator, $string);

        //Trim whitespace
        foreach ($vals as $key => $val) {
            $vals[$key] = trim ($val);
        }
        //Return empty array if no items found
        //http://php.net/manual/en/function.explode.php#114273
        return array_diff ($vals, array(""));
    }

    /**
     * 
     * @param type $file
     * @param type $array
     * @return type
     */
    private function array2csvFile ($file, $array)
    {
        if ( count ($array) == 0 )
        {
            return null;
        }
        ob_start ();
        $df = fopen ($file, 'w');
        fputcsv ($df, array_keys (reset ($array)));
        foreach ($array as $row) {
            fputcsv ($df, $row);
        }
        fclose ($df);
        return ob_get_clean ();
    }

    /**
     * 
     */
    public function saveRegisterUserAction ()
    {
        $this->view->disable ();

        $_POST['status'] = 0;

        $objUser = (new \BusinessModel\UsersFactory())->create ($_POST, (new \BusinessModel\UsersFactory())->getUser (2));

        $userId = $objUser->getUserId ();
        $enc = $this->my_simple_crypt ($userId, 'e');

        $html = "<strong>External Registration</strong>"
                . "<hr>"
                . "<p>Thankyou for registering, your account has been successfully setup!</p>"
                . "<p>To log in to your account please go to our main site and enter your account information.</p>"
                . "<p>Login Url <a href='http://localhost/core/login/login'>http://localhost/core/login/login</a>"
                . "<br/> Username: " . $objUser->getUsername () . ""
                . "<br/> Password: " . $objUser->getPassword () . "</p>"
                . "<p> Please activate your account by clicking the link below, or by copying and pasting it into your browser address bar"
                . "<br/></p>"
                . "<p><a href='http://localhost/FormBuilder/enterprise/activateUser?resp=" . $enc . "'>http://localhost/FormBuilder/enterprise/activateUser?resp=" . $enc . "</a></p>";

        echo $html;

        mail ($objUser->getUser_email (), "EasyFlow External Registration", $html);
    }

    public function userRegistrationAction ()
    {
        
    }

    /**
     * 
     * @throws Exception
     */
    public function activateUserAction ()
    {
        $decrypted = $this->my_simple_crypt ($_GET['resp'], 'd');

        if ( $decrypted !== '' && is_numeric ($decrypted) )
        {
            $objMysql = new Mysql2();
            $checkUser = $objMysql->_select ("user_management.poms_users", [], ["usrid" => $decrypted]);

            if ( !isset ($checkUser[0]) || empty ($checkUser[0]) )
            {
                throw new Exception ("User is disabled");
            }

            $blResponse = $objMysql->_update ("user_management.poms_users", ["status" => 1], ["usrid" => $decrypted]);

            if ( $blResponse !== FALSE )
            {
                $_SESSION['user'] = $checkUser[0];
                header ("Location: /FormBuilder/inbox/inbox");
                exit;
            }
        }
    }

    /**
     * Encrypt and decrypt
     * 
     * @author Nazmul Ahsan <n.mukto@gmail.com>
     * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
     *
     * @param string $string string to be encrypted/decrypted
     * @param string $action what to do with this? e for encrypt, d for decrypt
     */
    private function my_simple_crypt ($string, $action = 'e')
    {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash ('sha256', $secret_key);
        $iv = substr (hash ('sha256', $secret_iv), 0, 16);

        if ( $action == 'e' )
        {
            $output = base64_encode (openssl_encrypt ($string, $encrypt_method, $key, 0, $iv));
        }
        else if ( $action == 'd' )
        {
            $output = openssl_decrypt (base64_decode ($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    /**
     * 
     */
    public function restoreArchiveAction ()
    {
        $this->view->disable ();

        $arrTables = array(
            0 => array(
                "table" => "projects",
                "database" => "task_manager",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/projects_" . $_REQUEST['parentId'] . ".sql",
                "where" => "id='" . $_REQUEST['parentId'] . "'"
            ),
            1 => array(
                "table" => "workflow_data",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/workflow_" . $_REQUEST['parentId'] . ".sql",
                "where" => "object_id='" . $_REQUEST['parentId'] . "'",
                "whereArr" => ["object_id" => $_REQUEST['parentId']]
            ),
            2 => array(
                "table" => "app_message",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/messages_" . $_REQUEST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_REQUEST['parentId'] . "'"
            ),
            3 => array(
                "table" => "app_event",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/events_" . $_REQUEST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_REQUEST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_REQUEST['parentId']]
            ),
            4 => array(
                "table" => "abe_request",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/abeRequest_" . $_REQUEST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_REQUEST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_REQUEST['parentId']]
            ),
            5 => array(
                "table" => "app_delay",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/delay_" . $_REQUEST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_REQUEST['parentId'] . "'"
            )
        );

        $op_data = '';
        $message = 'Restored ';

        $objMysql = new Mysql2();

        foreach ($arrTables as $arrTable) {
            $lines = file ($arrTable['file']);

            foreach ($lines as $line) {
                if ( substr ($line, 0, 2) == '--' || $line == '' || substr ($line, 0, 2) == '/*' )//This IF Remove Comment Inside SQL FILE
                {
                    continue;
                }
                $op_data .= $line;
                if ( substr (trim ($line), -1, 1) == ';' )//Breack Line Upto ';' NEW QUERY
                {
                    $message .= $arrTable['table'] . " ";
                    $op_data = str_replace ("INSERT INTO", "INSERT INTO " . $arrTable['database'] . ".", $op_data);

                    //$op_data = str_replace(" ", "", $op_data);
                    $objMysql->_query ($op_data, [], false);
                    $op_data = '';
                }
            }
        }

        echo $message;
    }

    /**
     * 
     */
    public function saveArchiveAction ()
    {
        $this->view->disable ();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/core/app/library/Archive.php';



        $arrTables = array(
            0 => array(
                "table" => "app_message",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/messages_" . $_POST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_POST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_POST['parentId']]
            ),
            1 => array(
                "table" => "app_event",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/events_" . $_POST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_POST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_POST['parentId']]
            ),
            2 => array(
                "table" => "workflow_data",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/workflow_" . $_POST['parentId'] . ".sql",
                "where" => "object_id='" . $_POST['parentId'] . "'",
                "whereArr" => ["object_id" => $_POST['parentId']]
            ),
            3 => array(
                "table" => "abe_request",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/abeRequest_" . $_POST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_POST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_POST['parentId']]
            ),
            4 => array(
                "table" => "projects",
                "database" => "task_manager",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/projects_" . $_POST['parentId'] . ".sql",
                "where" => "id='" . $_POST['parentId'] . "'",
                "whereArr" => ["id" => $_POST['parentId']]
            ),
            5 => array(
                "table" => "app_delay",
                "database" => "workflow",
                "file" => $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/archive/delay_" . $_POST['parentId'] . ".sql",
                "where" => "APP_UID='" . $_POST['parentId'] . "'",
                "whereArr" => ["APP_UID" => $_POST['parentId']]
            )
        );

        $arrCase = (new \BusinessModel\Cases())->getCaseInfo ($_POST['parentId'], $_POST['caseId']);

        $appCacheView = new BusinessModel\AppCacheView();
        //$appCacheView->setAppCreateDate($arrCase->g);
        $appCacheView->setAppCurrentUser ($arrCase->setCurrent_user ($_SESSION['user']['username']));
        $appCacheView->setAppProTitle ($arrCase->getWorkflowName ());
        $appCacheView->setAppCurrentUser ($arrCase->getCurrent_user ());
        $appCacheView->setAppTitle ($arrCase->getName ());
        $appCacheView->setAppUid ($arrCase->getId ());
        $appCacheView->setAppStatus ($arrCase->getStatus ());
        $appCacheView->setProUid ($arrCase->getWorkflow_id ());
        $appCacheView->setUsrUid ($arrCase->getAddedBy ());
        $appCacheView->setAppTasTitle ($arrCase->getCurrentStep ());
        $appCacheView->setAppNumber ($arrCase->getParentId ());
        $appCacheView->setDelTaskDueDate ($arrCase->getDueDate ());
        $appCacheView->setAppFinishDate ($arrCase->getDateCompleted ());
        $appCacheView->setTasUid ($arrCase->getCurrentStepId ());

        $appCacheView->save ();

        $message = 'Case was routed successfully </br>';

        foreach ($arrTables as $arrTable) {
            $dumpSettings = array(
                'no-data' => false,
                'skip-comments' => true,
                'include-tables' => array($arrTable['table']),
                'add-drop-table' => false,
                'single-transaction' => true,
                'lock-tables' => false,
                'add-locks' => false,
                'no-autocommit' => false,
                'extended-insert' => false,
                'complete-insert' => true,
                'disable-keys' => false,
                'skip-triggers' => true,
                'add-drop-trigger' => false,
                'routines' => false,
                'databases' => false,
                'add-drop-database' => false,
                'hex-blob' => true,
                'no-create-info' => true,
                'where' => $arrTable['where']
            );
            $dump = new Mysqldump (
                    "mysql:host=localhost;dbname=" . $arrTable['database'], "phalcon", "Password123", $dumpSettings);
            $dump->start ($arrTable['file']);

            $message .= $_POST['parentId'] . " - " . $arrTable['table'] . " archived successfully";

            $objMysql = new Mysql2();
            $objMysql->_delete ($arrTable['database'] . "." . $arrTable['table'], $arrTable['whereArr']);
        }

        echo $message;
    }

    /**
     * 
     */
    public function archiveCasesAction ()
    {
        $objCases = new \BusinessModel\Cases();
        $this->view->arrCases = $objCases->getList ();
        $this->view->archivedCases = (new \BusinessModel\AppCacheView())->searchArchive ();
    }

    public function reportTablesAction ()
    {
        $objAdditionalTables = new \BusinessModel\Table();
        $arrTables = $objAdditionalTables->getTables ();

        $tables = [];

        foreach ($arrTables as $arrTable) {
            $tables[$arrTable['PMT_UID']]['id'] = $arrTable['PMT_UID'];
            $tables[$arrTable['PMT_UID']]['name'] = $arrTable['PMT_TAB_NAME'];
        }

        $this->view->tableList = json_encode ($tables);

        $objMysql = new Mysql2();

        $this->view->arrReports = $objMysql->_select ("report_tables.report_builder");

        $reportJSON = [];

        foreach ($this->view->arrReports as $report) {
            $reportJSON[$report['id']] = $report;
        }

        $this->view->reportJSON = json_encode ($reportJSON);

        $arrTableData = [];
        $arrGields = [];

        foreach ($arrTables as $arrTable) {
            $arrTableData[$arrTable['PMT_UID']]['name'] = $arrTable['PMT_TAB_NAME'];
            $arrTableData[$arrTable['PMT_UID']]['id'] = $arrTable['PMT_UID'];

            $arrGields[$arrTable['PMT_UID']] = $arrTable['FIELDS'];
        }

        $this->view->tables = $arrTableData;
        $this->view->fields = $arrGields;
    }

    private function random_string ($length)
    {
        $key = '';
        $keys = array_merge (range (0, 9), range ('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand ($keys)];
        }

        return $key;
    }

    public function previewReportAction ()
    {
        $this->view->disable ();

        $objMysql = new Mysql2();
        $result = $objMysql->_query ($_POST['sqlPreview']);
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/csv/" . $this->random_string (50) . ".csv";

        $this->array2csvFile ($filename, $result);

        echo json_encode (array("result" => $result, "filename" => $filename));
    }

    public function saveReportBuilderAction ()
    {
        $this->view->disable ();

        $objMysql = new Mysql2();

        if ( isset ($_POST['reportId']) && trim ($_POST['reportId']) !== "" && is_numeric ($_POST['reportId']) )
        {
            $objMysql->_update ("report_tables.report_builder", [
                "ADD_TAB_UID" => $_POST['table'],
                "title" => $_POST['title'],
                "description" => $_POST['description'],
                "selectColumns" => json_encode ($_POST['selectColumns']),
                "filters" => json_encode ($_POST['filters']),
                "orderColumns" => json_encode ($_POST['order']),
                "sqlPreview" => $_POST['sqlPreview']
                    ], ["id" => $_POST['reportId']]
            );
        }
        else
        {
            $objMysql->_insert ("report_tables.report_builder", [
                "ADD_TAB_UID" => $_POST['table'],
                "title" => $_POST['title'],
                "description" => $_POST['description'],
                "selectColumns" => json_encode ($_POST['selectColumns']),
                "filters" => json_encode ($_POST['filters']),
                "orderColumns" => json_encode ($_POST['order']),
                "sqlPreview" => $_POST['sqlPreview']
                    ]
            );
        }
    }

    public function batchRoutingAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);

        $consolidatedCases = new ConsolidatedCases();
        $this->view->arrTabs = $consolidatedCases->getListTabs ();
        $this->view->arrCases = $consolidatedCases->getCases ();
    }

    public function saveBatchRoutingAction ()
    {
        $this->view->disable ();

        if ( !isset ($_POST['batches']) || empty ($_POST['batches']) || !is_array ($_POST['batches']) )
        {
            return false;
        }

        $objCases = new \BusinessModel\Cases();
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        foreach ($_POST['batches'] as $batch) {

            $objElement = new Elements ($batch['projectId'], $batch['caseId']);
            $objCases->updateStatus ($objElement, $objUser, "COMPLETE");
        }
    }

}
