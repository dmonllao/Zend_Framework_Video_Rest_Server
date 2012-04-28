<?php

class VideoController extends Zend_Controller_Action implements App_Rest_Controller
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function getAction()
    {
        // action body
    }

    public function postAction()
    {
        $data = $this->getRequest()->getParams();

        // Data required not present
        if (!isset($data['userid']) || !isset($data['videodata'])) {
            $this->view->success = false;
            $this->view->failedmessage = 'Missing data';
            return;
        }

        // Check if the user exists
        $user = new Application_Model_DbTable_User();
        if (!$user->get($data['userid'])) {
            $this->view->success = false;
            $this->view->failuremessage = 'The specified user does not exists';
            return;
        }

        // Store the video into the filesystem
        $config->videospath = '/var/www/videos';
        $filename = $config->videospath . '/' . $user->id . '_' . rand(1000, 9999) . '_' .date('Ymd_H:i:s'). '.flv';
        if (file_exists($filename)) {
            $this->view->success = false;
            $this->view->failedmessage = 'Existing video file';
            return;
        }

        // Opening the stream to save it
        $filehandler = fopen($filename, 'wb');
        stream_filter_append($filehandler, 'convert.base64-decode', STREAM_FILTER_WRITE);

        // Taking care of http://developer.android.com/reference/android/util/Base64.html#URL_SAFE
        $data = str_replace(array('-', '_'), array('+', '/'), $data['videodata']);

        // Saving into FS
        fwrite($filehandler, $data);
        fpassthru($filehandler);
        rewind($filehandler);
        fclose($filehandler);

        if (!file_exists($filename)) {
            $this->view->success = false;
            $this->view->failedmessage = 'There was a problem getting the video';
            return;
        }
        chmod($filename, 0777);

        // Insert video into DB
        $video = new Application_Model_DbTable_Video();
        $video->userid = $user->id;
        $video->name = "";        // We will assign the name later
        $video->filename = $filename;
        $video->timecreated = time();
        $video->save();

        $this->view->success = true;
        $this->view->video = $video->get();
    }

    public function putAction()
    {
        // action body
    }


}






