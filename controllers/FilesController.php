<?php

use Phalcon\Mvc\View;

class FilesController extends BaseController
{

    public function indexAction ()
    {
        
    }

    public function loadAction ()
    {
        $this->view->disable ();

        define ('BASE_PATH', dirname (__FILE__));

        require_once $_SERVER['DOCUMENT_ROOT'] . "/FormBuilder/app/libraries/fileman/conf.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/core/app/library/Documents/functions.php";

        $tl = defined ('LANG') ? loadTL (LANG) : [];    // array with text language
        $re = [];    // response to output
// if(isset($_SESSION['adminlogg'])) unset($_SESSION['adminlogg']);
// if request to logg in, else, if not logged, output message (recoggnized in ajaxRe [in main.js])
        if ( isset ($_POST['name']) && isset ($_POST['pass']) )
        {
            $_POST['name'] = trim (strip_tags ($_POST['name']));
            $_POST['pass'] = trim (strip_tags ($_POST['pass']));

            // check if data form to logg in
            if ( $_POST['name'] == $admin_name && $_POST['pass'] == $admin_pass )
                $fm_conf['LOGG_IN'] = 1;
            else
                $fm_conf['LOGG_IN'] = getTL ('E_AdminData');
        }

// set to recognize in JavaScript if requiee logg in, or if logged
        if ( isset ($_SESSION['adminlogg']) && $_SESSION['adminlogg'] == $admin_name . $admin_pass )
            $fm_conf['LOGG_IN'] = 1;
        else if ( $fm_conf['LOGG_IN'] == 1 )
            $_SESSION['adminlogg'] = $admin_name . $admin_pass;
        else if ( $fm_conf['LOGG_IN'] == 0 && (isset ($_SESSION['adminlogg']) && $_SESSION['adminlogg'] != $admin_name . $admin_pass) )
        {
            unset ($_SESSION['adminlogg']);
            $fm_conf['LOGG_IN'] = getTL ('E_AdminLogg');
        }

// if the 1st request, output cofig, else set response to store output
        if ( isset ($_POST['gcd']) && $_POST['gcd'] == 'config_data' )
            $re = ['conf' => $fm_conf, 'lang' => $tl];
        else
            $re['conf']['LOGG_IN'] = $fm_conf['LOGG_IN'];    // idex LOGG_IN is needed in ajax response to check logg-in



            
// 'd'=dir-path, 'f'=file-path-name, 'n'-new dir/file path-name
        $param = [
            'd' => isset ($_POST['d']) ? fixPath ($_POST['d']) : MAIN_ROOT,
            'f' => isset ($_POST['f']) ? fixPath ($_POST['f']) : '',
            'n' => isset ($_POST['n']) ? trim (strip_tags ($_POST['n'])) : '',
            'dg' => isset ($_GET['d']) ? fixPath ($_GET['d']) : '', // to download
            'fc' => isset ($_POST['fc']) ? trim ($_POST['fc']) : ''    // content to edit file
        ];

// Class=>Method to access (p=parameters to pass, from $param)
        $ca = [
            "Dirs" => [
                "createdir" => ["createDir", 'p' => ['d', 'n']],
                "dirfiles" => ["dirFiles", 'p' => ['d']],
                "dirtree" => ["dirTree", 'p' => []],
                "deletedir" => ["deleteDir", 'p' => ['d']],
                "downloaddir" => ["downloadDir", 'p' => ['dg']],
                "movedir" => ["moveDir", 'p' => ['d', 'n']],
                "copydir" => ["copyDir", 'p' => ['d', 'n']],
                "renamedir" => ["renameDir", 'p' => ['d', 'n']]
            ],
            "Files" => [
                "copyfile" => ["copyFile", 'p' => ['f', 'n']],
                "deletefile" => ["deleteFile", 'p' => ['f']],
                "downloadfile" => ["downloadFile", 'p' => ['dg']],
                "editfile" => ["editFile", 'p' => ['f', 'fc']],
                "movefile" => ["moveFile", 'p' => ['f', 'n']],
                "renamefile" => ["renameFile", 'p' => ['f', 'n']],
                "getthumb" => ["getThumb", 'p' => ['f']],
                "upload" => ["upload", 'p' => ['d']]
            ]
        ];

// if Logged and some request access
        if ( isset ($_SESSION['adminlogg']) && isset ($_REQUEST['ca']) )
        {
            $rca = trim ($_REQUEST['ca']);

            // check and get if Dirs or Files request class, and the method
            foreach ($ca AS $cls => $ar_m) {
                foreach ($ar_m AS $k => $ar_m2) {
                    if ( $rca == $k )
                    {
                        $method = $ar_m2[0];
                        $i_param = $ar_m2['p'];    // array with indexes to get parameters from $param
                        break(2);
                    }
                }
            }

            // if defined class and method to access, get the parameters and call it
            if ( isset ($cls) && isset ($method) )
            {
                $nr_p = count ($i_param);
                $ar_param = [];
                for ($i = 0; $i < $nr_p; $i++)
                    $ar_param[] = $param[$i_param[$i]];

                include $_SERVER['DOCUMENT_ROOT'].'/core/app/library/Documents/' . strtolower ($cls) . '.php';    // file with needed class
                $re_m = call_user_func_array ('\\' . $cls . '::' . $method, $ar_param);

                // define output response
                if ( is_array ($re_m) )
                    $re = ($rca == 'dirtree') ? array_merge ($re, $re_m) : $re = $re_m;
                else if ( strpos ($re_m, '<script>') === false )
                    $re = '["' . getTL ($re_m) . $cls::$res . '"]';    // json array
                else
                    $re = $re_m;
            }
            else
                $re = getTL ('E_InvalidReq');
        }

        echo is_array ($re) ? json_encode ($re) : $re;
    }

}
