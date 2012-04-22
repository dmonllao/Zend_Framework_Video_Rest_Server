<?php

class App_Controller_Helper_RestContexts extends Zend_Controller_Action_Helper_Abstract
{

    protected $_contexts = array(
        'xml',
        'json',
    );

    public function preDispatch()
    {
        // If it is a controlled that supports REST methods
        // (inherited from App_Rest_Controller) overwrite the context
        $controller = $this->getActionController();
        if (!$controller instanceof App_Rest_Controller) {
            return;
        }

        $this->_initContexts();

        // Set a Vary response header based on the Accept header
        $this->getResponse()->setHeader('Vary', 'Accept');
    }

    /**
     * Sets every one of the REST methods to respond in the appropiate format
     */
    protected function _initContexts()
    {
        // ContextSwitch maps the responses to views files (xxx.json.phtml...) according to the format param
        $cs = $this->getActionController()->getHelper("contextSwitch");
        $cs->setAutoJsonSerialization(false);
        foreach ($this->_contexts as $context) {
            foreach (array('index', 'post', 'get', 'put', 'delete') as $action) {
                $cs->addActionContext($action, $context);
            }
        }
        $cs->initContext();
    }
}

