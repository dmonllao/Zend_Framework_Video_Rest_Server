<?php

class UserController extends Zend_Controller_Action implements App_Rest_Controller
{

    /**
     * Gets the system users
     */
    public function indexAction()
    {
         $this->getResponse()
            ->appendBody("From indexAction() returning all articles\n");
    }

    /**
     * Gets a specific user
     */
    public function getAction()
    {
        $this->getResponse()
            ->appendBody("From getAction() returning the requested article\n");

    }

    /**
     * Creates an user
     */
    public function postAction()
    {
        // Received data
        $data = $this->getRequest()->getParams();

        // Data required not present
        if (!isset($data['email']) || !isset($data['password'])) {
            $this->view->success = false;
            $this->view->failedmessage = 'Missing data';
            return;
        }

        $user = new Application_Model_DbTable_User();

        // Create the user
        if (!$user->get(array('username' => $data['email']))) {

            $user->username = $data['email'];
            $user->password = $this->passwordFromString($data['password']);
            $user->email = $data['email'];
            $user->timecreated = time();
            $user->lastaccess = time();
            $user->save();

        // If it already exists
        } else {

            // Checking password
            if (!$this->checkPassword($user)) {
                $this->view->success = false;
                $this->view->failedmessage = 'Wrong user';
                return;
            }

            $user->lastaccess = time();
            $user->save();
        }

        $this->view->success = true;
        $this->view->user = $user->get();
    }

    /**
     * Updates an user
     */
    public function putAction()
    {
        $this->getResponse()
            ->appendBody("From putAction() updating the requested article\n");
    }

    /**
     * Deletes an user
     */
    public function deleteAction()
    {
        $this->getResponse()
            ->appendBody("From deleteAction() deleting the requested article\n");
    }


    /**
     * Checks the user password against the submitted password
     * @param Application_Model_DbTable_User $user
     * @return boolean
     */
    protected function checkPassword(Application_Model_DbTable_User $user)
    {

        $submittedpassword = $this->passwordFromString($this->getRequest()->getParam("password"));
        if ($user->password == $submittedpassword) {
            return true;
        }

        return false;
    }

    /**
     * Formats the plain password to a database storable password including a salt
     *
     * @param string $password
     * @return string
     */
    protected function passwordFromString($password)
    {
        // TODO A configurable salt
        return md5('qwsed' . $password);
    }
}

