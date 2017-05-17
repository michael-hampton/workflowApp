<?php
use Phalcon\Mvc\View;
class LoginController extends  \Phalcon\Mvc\Controller {
    public function loginAction()
    {
         
    }
     
    public function doLoginAction()
    {
        session_start();
        require_once $_SERVER['DOCUMENT_ROOT'].'/phalcon/app/models/Login.php';
        $this->view->disable();
        $objLogin = new Login();
        
      
        $blLogin = $objLogin->executeLogin($_REQUEST['username'], $_REQUEST['password']);
        
        if($blLogin === TRUE) {
            $arrUser = $objLogin->getUserByUsername($_REQUEST['username']);
            $_SESSION['user'] = $arrUser[0];
            
            header("Location: /FormBuilder/index/index");
            exit;
        }
        
        //$objLogin->login($_REQUEST['username'], $_REQUEST['password']);
    }
    
    public function logoutAction()
    {
        $this->view->disable();
        session_start();
        
        session_destroy();
        
        header("Location: /FormBuilder/index/index");
        die;
    }
}
