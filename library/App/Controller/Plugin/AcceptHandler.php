<?php

/**
 * Plugin to check the Accept header and according to it set the format request param
 *
 * @uses Zend_Controller_Plugin_Abstract
 */
class App_Controller_Plugin_AcceptHandler extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            return;
        }

        // TODO: Discard xml
        $header = $request->getHeader('Accept');
        switch (true) {
            case (strstr($header, 'application/json')):
                $request->setParam('format', 'json');
                break;
            case (strstr($header, 'application/xml') 
                  && (!strstr($header, 'html'))):
                $request->setParam('format', 'xml');
                break;
        }
    }
}

