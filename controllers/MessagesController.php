<?php

use Phalcon\Mvc\View;

class MessagesController extends BaseController
{

    private $maxPagesBoxesUpAndDown = 5;

    public function onConstruct ()
    {
        define ("PRODUCTS_PAGE_LIMIT", 20);
        session_start ();
    }

    public function indexAction ()
    {
        $objMessages = new Messages();
        $this->view->arrWebsites = $objMessages->getAllSites ();
        $this->view->messageTypes = $objMessages->getMessageTypes ();
    }

    public function saveNewMessageAction ()
    {
        $this->view->disable ();
        $objMessages = new Messages();

        $comment = $this->request->getPost ("content", "string");
        $messageId = $this->request->getPost ("messageId", "int");
        $channel = $this->request->getPost ("channel", "string");
        $messageType = $this->request->getPost ("messageType", "int");

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/save";

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "POST", array(
            "customerId" => $_SESSION['user']['userId'],
            "comment" => $comment,
            "messageId" => $messageId,
            "messageType" => $messageType,
            "date_added" => date ("Y-m-d"),
            "channel" => $channel
                )
        );

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();
    }

    public function composeEmailAction ()
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objMessages = new Messages();
    }

    public function getMessageDetailsAction ($messageId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objMessages = new Messages();

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/getMessageById/" . $messageId;

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "GET");

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();

        $this->view->arrMessageDetails = json_decode ($arrResponseData, true);

        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/getMessageHeader/" . $messageId;

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "GET");

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();

        $arrMessageHeader = json_decode ($arrResponseData, true);
        unset ($arrMessageHeader['error']);
        $this->view->arrMessageHeader = $arrMessageHeader;
    }

    public function trashAction ()
    {
        $this->view->disable ();

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/updateStatus";
        $_POST['type'] = "trash";

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "POST", $_POST);

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();
    }

    public function sendToArchiveAction ()
    {
        $this->view->disable ();

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/updateStatus";
        $_POST['type'] = "archive";

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "POST", $_POST);

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();
    }

    public function markAsReadAction ()
    {
        $this->view->disable ();

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/updateStatus";
        $_POST['type'] = "read";

        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "POST", $_POST);

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();
    }

    public function searchMessagesAction ($page = 0, $orderByField = "id", $orderDirRule = "asc", $type = '')
    {

        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objMessages = new Messages();

        if ( !empty ($type) && $type == "archived" )
        {
            $_REQUEST["form"]["archived"] = 1;
        }
        elseif ( !empty ($type) && $type == "category" )
        {
            $_REQUEST['form']['messageType'] = $this->request->getPost ("category", "int");
        }
        elseif ( !empty ($type) && $type == "trashed" )
        {
            $_REQUEST["form"]["trashed"] = 1;
        }
        elseif ( !empty ($type) && $type == "getStatus" )
        {
            $_REQUEST["form"]["status"] = $this->request->getPost ("status", "int");
        }
        elseif ( !empty ($type) && $type == "sentMessages" )
        {
            $_REQUEST["form"]["sent"] = 1;
        }
        elseif ( !empty ($type) && $type == "website" )
        {
            $_REQUEST['form']['website'] = $_REQUEST['channel'];
        }

        require_once ($_SERVER['DOCUMENT_ROOT'] . "/phalcon/app/models/class.rest-request.php");
        $strEtailorApiResourceUri = "http://localhost/phalcon/api/v3/messages/search";
        $objApiRequest = new RestRequest ($strEtailorApiResourceUri, "POST", array(
            "params" => $_REQUEST['form'],
            "page" => $page,
            "limit" => PRODUCTS_PAGE_LIMIT,
            "order_by" => $orderByField,
            "order_dir" => $orderDirRule
                )
        );

        $objApiRequest->execute ();
        $arrResponseData = $objApiRequest->getResponseBody ();
        $arrMessages = json_decode ($arrResponseData, TRUE);
        $this->view->arrMessages = $arrMessages['messages'];
        
        $_SESSION['pagination'] = $arrMessages['pagination'];
        
        $this->view->pagination = $this->getPagination();
    }

    public function getPagination ()
    {

        /*         * ************************************************************************* */
        /*                      PAGINGATION BEGIN
         * *************************************************************************** */

        $strFunction = "jumpToPage";

        $maxPagesBoxesUpAndDown = $this->maxPagesBoxesUpAndDown;
        $totalPageBoxes = ($maxPagesBoxesUpAndDown * 2) + 1;

        $currentPageorders = $_SESSION["pagination"]["total_counter"] - (PRODUCTS_PAGE_LIMIT * $_SESSION["pagination"]["current_page"]);
        if ( $currentPageorders > PRODUCTS_PAGE_LIMIT )
            $currentPageorders = PRODUCTS_PAGE_LIMIT;

        $html_pagination = "<ul class=\"pagination pull-right\">";

        $html_pagination .= "<li onclick=\"" . $strFunction . "(0)\" class=\"footable-page-arrow\">
            <a data-page=\"first\" href=\"#first\">«</a>
        </li>";

        if ( $_SESSION["pagination"]["current_page"] > 0 )
        {

            $html_pagination .= "<li onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["current_page"] - 1) . ")\" class=\"footable-page-arrow\">
                <a data-page=\"prev\" href=\"#prev\">‹</a>
            </li>";
        }
        else
        {
            $html_pagination .= "<li class=\"footable-page-arrow disabled\">
                <a data-page=\"prev\" href=\"#prev\">«</a>
            </li>";
        }

        //$html_pagination .= "<div style=\" float: left; width: 81%; \">&nbsp;";
        if ( $_SESSION["pagination"]["total_pages"] < 10 )
        {
            for ($i = 0; $i <= $_SESSION["pagination"]["total_pages"] - 1; $i++) {

                if ( $i == $_SESSION["pagination"]["current_page"] )
                {   
                    $class = "active";
                }
                else
                {
                    $class = "";
                }

                $html_pagination .= "<li class=\"footable-page ".$class."\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }
        else
        {

            /* pages more then 11 */

            if ( $_SESSION["pagination"]["current_page"] <= 5 )
            {
                $intStartWidth = 0;
                $endStartWidth = 9;
                //echo "stage 2";
            }

            if ( $_SESSION["pagination"]["current_page"] > 5 )
            {
                $intStartWidth = $_SESSION["pagination"]["current_page"] - 5;
                $endStartWidth = $_SESSION["pagination"]["current_page"] + 5;
                //echo "stage 3";
            }

            if ( ($_SESSION["pagination"]["current_page"] + 5) >= $_SESSION["pagination"]["total_pages"] )
            {
                $intStartWidth = $_SESSION["pagination"]["total_pages"] - 10;
                $endStartWidth = $_SESSION["pagination"]["total_pages"] - 1;
                //echo "stage 4";
            }

            for ($i = $intStartWidth; $i <= $endStartWidth; $i++) {

                if ( $i == $_SESSION["pagination"]["current_page"] )
                {
                    $class = 'active';
                }
                else
                {
                    $class = '';
                }

                $html_pagination .= "<li class=\"footable-page ".$class."\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }

        //$html_pagination.=  " <div style=\"float: left; margin-top: 7px; width: 50px;\" > <img style=\" display:none;\" id=\"ajax-loader-pic\" alt=\"loading\" src=\"/images/ajax-loader.gif\"></div>";

        if ( ($_SESSION["pagination"]["current_page"] + 1) < $_SESSION["pagination"]["total_pages"] )
        {

            $html_pagination .= "<li class=\"footable-page\" onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["current_page"] + 1) . ")\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }
        else
        {

            $html_pagination .= "<li class=\"footable-page disabled\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }

        $html_pagination .= "<li class=\"footable-page-arrow\" onclick=\"" . $strFunction . "(" . ($_SESSION["pagination"]["total_pages"] - 1) . ")\">
           <a data-page=\"last\" href=\"#last\">»</a>
        </li>";

        $html_pagination .= "</ul>";

        $htmlResult = $html_pagination;

        return $htmlResult;
    }

}
