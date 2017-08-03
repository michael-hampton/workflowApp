<?php

use Phalcon\Mvc\View;

class AttachmentsController extends BaseController
{

    public function uploadAttachedFileAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objAttachments = new \BusinessModel\Attachment();

        // Check if image file is a actual image or fake image
        if ( isset ($_FILES["file"]) && !empty ($_FILES["file"]["name"]) )
        {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/public/uploads/";
            $target_file = $target_dir . basename ($_FILES["file"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo ($target_file, PATHINFO_EXTENSION);

            // Check file size
            if ( $_FILES["file"]["size"] > 5000000 )
            {
                die ("2");
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            /* $arrAllowed = array("csv", "jpg", "png", "doc", "pdf", "jpeg", "gif");

              if ( !in_array ($imageFileType, $arrAllowed) )
              {
              die ("1");
              echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
              } */

            // Check if $uploadOk is set to 0 by an error
            if ( $uploadOk == 0 )
            {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            }
            else
            {
                $fileName = $_FILES['file']['name'];
                $fileContents = file_get_contents ($_FILES['file']['tmp_name']);

                $arrData = array(
                    "source_id" => $projectId,
                    "filename" => $fileName,
                    "date_uploaded" => date ("Y-m-d H:i:s"),
                    "uploaded_by" => $_SESSION['user']['username'],
                    "files" => $_FILES);

                $objAttachments = new \BusinessModel\Attachment();
                $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);
                $objAttachments->loadObject ($arrData, $objUser);
            }
        }
    }

    public function getAttachmentsAction ($projectId)
    {
        $objUser = (new \BusinessModel\UsersFactory)->getUser ($_SESSION['user']['usrid']);
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objAttachments = new \BusinessModel\Attachment();

        $objCases = new \BusinessModel\Cases();
        $objElements = $objCases->getCaseInfo ($projectId, 1);
        $this->view->attachmnets = $objCases->getAllUploadedDocumentsCriteria ($objElements, 1, $objUser);

        $this->view->writePermission = true;
        $this->view->projectId = $projectId;
    }

    function mime_content_type ($filename)
    {
        $result = new finfo();

        if ( is_resource ($result) === true )
        {
            return $result->file ($filename, FILEINFO_MIME_TYPE);
        }

        return false;
    }

    public function downloadAction ($id)
    {
        $this->view->disable ();
        $objInputDocument = new \BusinessModel\Step\InputDocument();


        $objAttachments = new \BusinessModel\Attachment();
        $objAttachments->setId ($id);
        $arrAttachment = $objAttachments->getAttachment ();

        $objInputDocument->downloadInputDocument ($id, $arrAttachment[0]['file_type'], $arrAttachment);
    }

    public function deleteAttachmentAction ($id, $projectUid)
    {
        $this->view->disable ();
        $objInputDocument = new \BusinessModel\Step\InputDocument();

        $appDocUid = explode ("_", $id)[0];
        $documentId = explode ("_", $id)[1];

        $objUser = (new \BusinessModel\UsersFactory)->getUser ($_SESSION['user']['usrid']);

        $blCanDelete = $objInputDocument->throwExceptionIfHaventPermissionToDelete ($projectUid, $objUser, $documentId);

        if ( $blCanDelete === false )
        {
            throw new Exception ("User doesnt have permission to delete");
        }
        
        $objDownload = new \BusinessModel\Download();
        $objDownload->removeInputDocument($appDocUid);
    }

    public function cases_ShowDocumentAction ()
    {
        $this->view->disable ();

        if ( empty ($_GET['a']) )
        {
            header ('Location: /FormBuilder/errors/error403.php');
            die ();
        }
        if ( empty ($_GET['v']) )
        {
            //Load last version of the document
            $docVersion = (new DocumentVersion())->getLastAppDocVersion ($_GET['a']);
        }
        else
        {
            $docVersion = $_GET['v'];
        }

        $objDownload = new \BusinessModel\Download();
        $objDownload->downloadInputDocument ($_GET['a'], null, $docVersion);
        die;
    }

}
