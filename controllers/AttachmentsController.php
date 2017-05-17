<?php

use Phalcon\Mvc\View;

class AttachmentsController extends BaseController
{

    public function uploadAttachedFileAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objAttachments = new Attachments();

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

            /*$arrAllowed = array("csv", "jpg", "png", "doc", "pdf", "jpeg", "gif");

            if ( !in_array ($imageFileType, $arrAllowed) )
            {
                die ("1");
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }*/

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
                    "contents" => $fileContents);

                $objAttachments = new Attachments();
                $objAttachments->loadObject ($arrData);
                $fileId = $objAttachments->save ();
            }
        }
    }

    public function getAttachmentsAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objAttachments = new Attachments();
        $this->view->attachmnets = $objAttachments->getAllAttachments ($projectId);
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
        $objAttachments = new Attachments();
        $objAttachments->setId ($id);
        $arrAttachment = $objAttachments->getAttachment ();

        $filedata = $arrAttachment[0]['contents'];

        if ( $filedata )
        {
            // GET A NAME FOR THE FILE
            $basename = basename ($arrAttachment[0]['filename']);

            // THESE HEADERS ARE USED ON ALL BROWSERS
            header ("Content-Type: application-x/force-download");
            header ("Content-Disposition: attachment; filename=$basename");
            header ("Content-length: " . (string) (strlen ($filedata)));
            header ("Expires: " . gmdate ("D, d M Y H:i:s", mktime (date ("H") + 2, date ("i"), date ("s"), date ("m"), date ("d"), date ("Y"))) . " GMT");
            header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");

            // THIS HEADER MUST BE OMITTED FOR IE 6+
            if ( FALSE === strpos ($_SERVER["HTTP_USER_AGENT"], 'MSIE ') )
            {
                header ("Cache-Control: no-cache, must-revalidate");
            }

            // THIS IS THE LAST HEADER
            header ("Pragma: no-cache");

            // FLUSH THE HEADERS TO THE BROWSER
            flush ();

            // CAPTURE THE FILE IN THE OUTPUT BUFFERS - WILL BE FLUSHED AT SCRIPT END
            ob_start ();
            echo $filedata;
        }
    }

}
