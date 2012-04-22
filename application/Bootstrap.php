<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


    /**
     * Allows the route of REST petitions to the appropiate method
     *
     * @link http://framework.zend.com/manual/en/zend.controller.router.html#zend.controller.router.routes.rest
     */
    protected function _initRestRoute()
    {
        $this->bootstrap('frontController');
        $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route($frontController);
        $frontController->getRouter()->addRoute('default', $restRoute);
    }


    protected function _initActionHelpers()
    {

        // Redirect the petition to one or another method depending on the HTTP method of the request
        $contexts = new App_Controller_Helper_RestContexts();
        Zend_Controller_Action_HelperBroker::addHelper($contexts);
    }
}

