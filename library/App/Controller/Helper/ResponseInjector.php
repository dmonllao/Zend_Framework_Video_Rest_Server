<?php

/**
 * Injects the response into the view to manage headers from there
 */
class App_Controller_Helper_ResponseInjector extends Zend_Controller_Action_Helper_Abstract
{

    public function preDispatch()
    {
        $this->_actionController->view->response = $this->getResponse();
    }

}

