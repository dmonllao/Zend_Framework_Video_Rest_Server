<?php

class Application_Model_DbTable_User extends Application_Model_DbTable_General
{

    protected $_name = 'users';

    /**
     * To specify the set of available fields
     * @var array
     */
    protected $_fields = array('id', 'username', 'password', 'email', 'timecreated', 'lastaccess');


}

