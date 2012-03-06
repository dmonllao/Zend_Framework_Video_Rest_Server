<?php

class UserController extends Zend_Controller_Action implements App_Rest_Controller
{

    /**
     * Stores the context switcher in a public var
     */
    public function init()
    {
        $this->contextSwitch = $this->_helper->getHelper('contextSwitch');
    }

    public function indexAction()
    {
         $this->getResponse()
            ->appendBody("From indexAction() returning all articles\n");
    }

    public function getAction()
    {
        $this->getResponse()
            ->appendBody("From getAction() returning the requested article\n");

    }

    public function postAction()
    {
        $this->getResponse()
            ->appendBody("From postAction() creating the requested article\n");

    }

    public function putAction()
    {
        $this->getResponse()
            ->appendBody("From putAction() updating the requested article\n");

    }

    public function deleteAction()
    {
        $this->getResponse()
            ->appendBody("From deleteAction() deleting the requested article\n");

    }


}

