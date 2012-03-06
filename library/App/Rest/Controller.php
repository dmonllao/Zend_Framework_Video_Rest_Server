<?php


/**
 * Interface to identify which controllers allows REST responses in json or xml
 */
interface App_Rest_Controller 
{

    /**
     * Compulsory in order to initialize the context switcher
     */
    public function init();
}

