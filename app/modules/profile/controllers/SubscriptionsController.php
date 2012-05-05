<?php
class Profile_SubscriptionsController extends Core_Controller_Action
{
    public function init()
    {
        parent::init();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $this->view->profileData = null;
        $this->view->userData = null;
        $profileId = $this->_getParam('profile_id', null);
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

    public function listAction()
    {
        $subsModel = new Core_Db_SubscribeLinks();
        $this->view->subsList = $subsModel->getByProfileId($this->view->profileData->id);
    }

    public function categoryAction()
    {
        $categoryId = $this->_getParam('id', null);
        if (!$categoryId) {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('profile'));
        }
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultCategory($categoryId);
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $tagsSubscribeModel = new Core_Db_Content_Tags_SubscribeLinks();
        $tagsList = $tagsSubscribeModel->getByProfileId($this->view->currentProfileData->id);
        $tagsArray = array();
        foreach ($tagsList as $tagItem) {
            $tagsArray[] = $tagItem->tag_id;
        }
//        $this->view->newsList = $newsModel->getList($this->_getParam('page', 1), 10, null, null, null, 'public_date', 'desc')->fetchArray();
        $newsList = $newsModel->getNewsByTag($tagsArray, $this->_getParam('page', 1), 10, 'public_date', 'desc');
        $this->view->newsList = array();
        $this->view->totalCount = 0;
        if ($newsList) {
            $this->view->newsList = $newsList->fetchArray();
            $this->view->totalCount = $newsModel->getNewsByTag($tagsArray)->count();
        }

        $this->view->totalPages = ceil($this->view->totalCount/10);
        $this->view->paginatorCode = $this->view->paginator($this->_getParam('page', 1), $this->view->totalPages, $this->view->totalCount, 'subscriptions/category/'.$categoryId, '');
    }

    public function removeAction()
    {
        $subsModel = new Core_Db_SubscribeLinks();
        $id = $this->_getParam('id', null);
        $subsItem = $subsModel->getRowInfo($id);
        if ($subsItem && $subsItem->profile_id == $this->view->currentProfileData->id) {
            $subsItem->delete();
        }
        $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('subscriptions/list'));
    }
}