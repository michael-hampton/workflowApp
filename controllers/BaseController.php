<?php

use Phalcon\Mvc\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseController extends \Phalcon\Mvc\Controller
{

    public function initialize ()
    {
        if ( session_id () == "" )
        {
            session_start ();
        }

        if ( (!isset ($_SESSION['user']) || empty ($_SESSION['user'])) && $_SERVER['REQUEST_URI'] != "/FormBuilder/login/login" )
        {
            header ("Location: /core/login/login");
            exit;
        }
    }

    public function checkPermissions ($permission)
    {
        $objUsers = new \BusinessModel\UsersFactory();
        $blValid = $objUsers->checkPermission ((new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']), $permission);

        return $blValid;
    }

    public function getPagination ($strFunction = "jumpToPage", $arrPagination)
    {

        $currentPageorders = $arrPagination['total'] - (PRODUCTS_PAGE_LIMIT * $arrPagination['page']);

        if ( $currentPageorders > PRODUCTS_PAGE_LIMIT )
        {
            $currentPageorders = PRODUCTS_PAGE_LIMIT;
        }

        $html_pagination = "<ul class=\"pagination pull-right\">";

        $html_pagination .= "<li onclick=\"" . $strFunction . "(0)\" class=\"footable-page-arrow\">
            <a data-page=\"first\" href=\"#first\">«</a>
        </li>";

        if ( $arrPagination['page'] > 0 )
        {

            $html_pagination .= "<li onclick=\"" . $strFunction . "(" . ($arrPagination['page'] - 1) . ")\" class=\"footable-page-arrow\">
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
        if ( $arrPagination["total_pages"] < 10 )
        {
            for ($i = 0; $i <= $arrPagination["total_pages"] - 1; $i++) {

                if ( $i == $arrPagination["page"] )
                {
                    $class = "active";
                }
                else
                {
                    $class = "";
                }

                $html_pagination .= "<li class=\"footable-page " . $class . "\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }
        else
        {

            /* pages more then 11 */

            if ( $arrPagination["page"] <= 5 )
            {
                $intStartWidth = 0;
                $endStartWidth = 9;
                //echo "stage 2";
            }

            if ( $arrPagination["page"] > 5 )
            {
                $intStartWidth = $arrPagination["page"] - 5;
                $endStartWidth = $arrPagination["page"] + 5;
                //echo "stage 3";
            }

            if ( ($arrPagination["page"] + 5) >= $arrPagination["total_pages"] )
            {
                $intStartWidth = $arrPagination["total_pages"] - 10;
                $endStartWidth = $arrPagination["total_pages"] - 1;
                //echo "stage 4";
            }

            for ($i = $intStartWidth; $i <= $endStartWidth; $i++) {

                if ( $i == $arrPagination["page"] )
                {
                    $class = 'active';
                }
                else
                {
                    $class = '';
                }

                $html_pagination .= "<li class=\"footable-page " . $class . "\" onclick=\"" . $strFunction . "(" . $i . ")\">
                    <a data-page=\"1\" href=\"#\">" . ($i + 1) . "</a>
                 </li>";
            }
        }

        //$html_pagination.=  " <div style=\"float: left; margin-top: 7px; width: 50px;\" > <img style=\" display:none;\" id=\"ajax-loader-pic\" alt=\"loading\" src=\"/images/ajax-loader.gif\"></div>";

        if ( ($arrPagination["page"] + 1) < $arrPagination["total_pages"] )
        {

            $html_pagination .= "<li class=\"footable-page\" onclick=\"" . $strFunction . "(" . ($arrPagination["page"] + 1) . ")\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }
        else
        {

            $html_pagination .= "<li class=\"footable-page disabled\">
                    <a data-page=\"1\" href=\"#\">›</a>
                 </li>";
        }

        $html_pagination .= "<li class=\"footable-page-arrow\" onclick=\"" . $strFunction . "(" . ($arrPagination["total_pages"] - 1) . ")\">
           <a data-page=\"last\" href=\"#last\">»</a>
        </li>";

        $html_pagination .= "</ul>";

        $htmlResult = $html_pagination;

        return $htmlResult;
    }

}
