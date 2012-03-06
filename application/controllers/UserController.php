<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
         $this->getResponse()
            ->appendBody("From indexAction() returning all articles");
    }

    public function getAction()
    {
        $this->getResponse()
            ->appendBody("From getAction() returning the requested article");

    }

    public function postAction()
    {
        $this->getResponse()
            ->appendBody("From postAction() creating the requested article");

    }

    public function putAction()
    {
        $this->getResponse()
            ->appendBody("From putAction() updating the requested article");

    }

    public function deleteAction()
    {
        $this->getResponse()
            ->appendBody("From deleteAction() deleting the requested article");

    }


}









