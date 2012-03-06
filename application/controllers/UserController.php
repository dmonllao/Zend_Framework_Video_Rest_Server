<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->action = 'index de user';
    }

    public function getAction()
    {
        // action body
    }


}



