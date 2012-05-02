<?php

/**
 * Adds support for POST params on PUT method petitions
 */
class App_Controller_Helper_PutParamsGetter extends Zend_Controller_Action_Helper_Abstract
{

    public function preDispatch()
    {

        if($this->getRequest()->isPut()) {
            parse_str($this->getRequest()->getRawBody(), $params);
            foreach($params as $key => $value) {
                $this->getRequest()->setParam($key, $value);
            }
        }
    }

}

