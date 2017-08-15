<?php
use Phalcon\Mvc\Controller;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseController extends  \Phalcon\Mvc\Controller {
    public function initialize ()
    {
        if(  session_id () == "") {
            session_start();
        }
        
        if((!isset($_SESSION['user']) || empty($_SESSION['user'])) && $_SERVER['REQUEST_URI'] != "/FormBuilder/login/login") {
            header("Location: /core/login/login");
            exit;
        }
    }
    
    public function checkPermissions($permission)
    {
        $objUsers = new \BusinessModel\UsersFactory();
        $blValid = $objUsers->checkPermission((new \BusinessModel\UsersFactory())->getUser($_SESSION['user']['usrid']), $permission);
               
       return $blValid;
    }
}

