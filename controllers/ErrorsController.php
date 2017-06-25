<?php

use Phalcon\Mvc\View;

class ErrorsController extends BaseController
{
    public function error403Action()
    {
         $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
    }
}

