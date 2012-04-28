<?php

class Application_Model_DbTable_Video extends Application_Model_DbTable_General
{

    protected $_name = 'videos';

    /**
     * To specify the set of available fields
     * @var array
     */
    protected $_fields = array('id', 'userid', 'name', 'filename', 'timecreated');

}

