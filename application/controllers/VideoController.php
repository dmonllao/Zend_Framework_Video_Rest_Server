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

        $data = $this->getRequest()->getParams();

        $video = new Application_Model_DbTable_Video();
        if (!$video->get($data['id'])) {
            $this->view->success = false;
            $this->view->failuremessage = 'The specified video does not exists';
            return;
        }

        $this->view->success = true;
        $this->view->video = $video->get();

        // Video HTML
        $this->view->video->html = $this->getVideoHTML($video);

        // Video URL
        $this->view->video->url = $this->getVideoURL($video);
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
        $config = Zend_Registry::get("config");
        $videoname = $user->id . '_' . rand(1000, 9999) . '_' .date('Ymd_H:i:s'). '.flv';
        $filename = $config["app"]["videospath"] . '/' . $videoname;
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
        $video->filename = $videoname;
        $video->timecreated = time();
        $video->save();

        $this->view->success = true;
        $this->view->video = $video->get();
    }

    public function putAction()
    {

        $data = $this->getRequest()->getParams();

        // Data required not present
        if (!isset($data['id']) || !isset($data['userid']) || !isset($data['name'])) {
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

        // Update into DB
        $video = new Application_Model_DbTable_Video();
        if (!$video->get($data['id'])) {
            $this->view->success = false;
            $this->view->failuremessage = 'The specified video does not exists';
            return;
        }

        // Check the video owner
        if ($data['userid'] != $video->userid) {
            $this->view->success = false;
            $this->view->failuremessage = 'Not owner';
            return;
        }

        // Update all the submitted values
        foreach ($data as $key => $value) {
            $video->{$key} = $value;
        }

        $video->save();

        $this->view->success = true;
        $this->view->video = $video->get();
        $this->view->video->html = $this->getVideoHTML($video);
        $this->view->video->url = $this->getVideoURL($video);
    }

    /**
     * Returns the HTML to display the video
     * @param Application_Model_DbTable_Video $video
     * @return string
     */
    private function getVideoHTML(Application_Model_DbTable_Video $video)
    {

        $videourl = $this->view->ServerUrl() . '/videos/' . $video->filename;
        $html = '<video width="320" height="240" controls="controls">
            <source src="' . $videourl . '" type="video/mp4" />
            Your browser does not support the video tag.
            </video>';
        return $html;
    }


    /**
     * Returns the video URL
     * @param Application_Model_DbTable_Video $video
     * @return string
     */
    private function getVideoURL(Application_Model_DbTable_Video $video)
    {

        $urlparams = array('controller' => 'Video', 'id' => $video->id);
        return $this->view->ServerUrl() . $this->view->url($urlparams);
    }
}
