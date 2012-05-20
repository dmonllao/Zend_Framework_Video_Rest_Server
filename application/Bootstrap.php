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

    /**
     * Adds the application.ini settings to the registry
     */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    /**
     * Loads all the controller helpers
     */
    protected function _initActionHelpers()
    {

        // TODO Find out what's happening with addPrefix
//         Zend_Controller_Action_HelperBroker::addPrefix('App_Controller_Helper');

        // Adds PUT param support
        $putparams = new App_Controller_Helper_PutParamsGetter();
        Zend_Controller_Action_HelperBroker::addHelper($putparams);

        // Injects the  Zend_Controller_Response_Abstract object to the view
        $responseinjector = new App_Controller_Helper_ResponseInjector();
        Zend_Controller_Action_HelperBroker::addHelper($responseinjector);

        // Redirect the petition to one or another method depending on the HTTP method of the request
        $contexts = new App_Controller_Helper_RestContexts();
        Zend_Controller_Action_HelperBroker::addHelper($contexts);
    }
}

