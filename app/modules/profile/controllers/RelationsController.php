<?php
class Profile_RelationsController extends Core_Controller_Action
{
    public function init()
    {
        parent::init();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $this->view->profileData = null;
        $this->view->userData = null;
        $profileId = $this->_getParam('id', null);
        if ($profileId) {
            $profilesModel = new Core_Db_Profiles();
            $this->view->profileData = $profilesModel->getRowInfo($profileId);
            if (!$this->view->profileData) {
                throw new Zend_Controller_Dispatcher_Exception($this->_translate->translate('message-profile-not-found'));
            }
            $this->view->userData = $this->view->profileData->getUser();
        } elseif (!$profileId&&$this->_currentUser) {
            $this->view->profileData = $currentProfile;
            $this->view->userData = $this->_currentUser;
        } else {
            throw new Zend_Controller_Dispatcher_Exception($this->_translate->translate('message-user-not-logged'));
        }
    }

    public function friendsAction()
    {
        $this->view->profilesList = $this->view->profileData->getFriends();
    }

    public function subscribersAction()
    {
        $this->view->profilesList = $this->view->profileData->getSubscribers();
    }
}