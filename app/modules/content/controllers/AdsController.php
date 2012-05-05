<?php
class Content_AdsController extends Core_Controller_Action
{
    public function addAction()
    {
        if ($this->_currentUser) {
            $currentProfile = $this->_currentUser->getProfile();
            if (!$currentProfile) {
                $profilesModel = new Core_Db_Profiles();
                $currentProfile = $profilesModel->createRow();
                $currentProfile->user_id = $this->_currentUser->id;
                $currentProfile->save();
            }
            $this->view->userData = $this->_currentUser;
            $this->view->adsForm = $this->getAdsForm();
            if ($this->_request->isPost()) {
                if ($this->view->adsForm->isValid($_POST)) {
                    $adsData = $_POST;
                    $adsData['user_id'] = $this->_currentUser->id;
                    $adsData['public_date'] = new Core_Date(time());
                    $imageObject = new Core_Image();
                    $result = $imageObject->uploadImages('/upload/');
                    if (!$result) {
                        $this->_flashMessenger->addMessage($this->_translate->translate('message-file-not-uploaded'));
                    }
                    $adsData['image'] = array_key_exists('image', $result)?$result['image']['path']:'';
                    $adsModel = new Core_Db_Content_Ads();
                    $adsRow = $adsModel->createRow();
                    $adsRow->saveData($adsData);
                    $this->view->added = true;
                }
            }
        } else {
            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
    }

    public function listAction()
    {
        if ($this->_currentUser) {
            $currentProfile = $this->_currentUser->getProfile();
            if (!$currentProfile) {
                $profilesModel = new Core_Db_Profiles();
                $currentProfile = $profilesModel->createRow();
                $currentProfile->user_id = $this->_currentUser->id;
                $currentProfile->save();
            }
            $this->view->currentProfileData = $this->view->profileData = $currentProfile;
            $this->view->currentUserData = $this->view->userData = $this->_currentUser;
        }
        $adsModel = new Core_Db_Content_Ads();
        $this->view->myAdsFlag = $this->_getParam('my', null);
        if ($this->view->myAdsFlag && $this->_currentUser) {
            $adsModel->setCurrentUser($this->_currentUser->id);
        } else {
            $adsModel->setDefaultStatus(Core_Db_Content_Ads::NEWS_VISIBLE);
        }
        $this->view->totalCount = $adsModel->getListCount();
        $this->view->totalPages = ceil($this->view->totalCount/10);
        $this->view->adsList = $adsModel->getList($this->_getParam('page', 1), 10);
        $this->view->paginatorCode = $this->view->paginator($this->_getParam('page', 1), $this->view->totalPages, $this->view->totalCount, 'ads');
    }

    public function showAction()
    {
        $adsModel = new Core_Db_Content_Ads;
        $adsModel->setDefaultStatus(Core_Db_Content_Ads::NEWS_VISIBLE);
        $id = $this->_request->getParam('id', null);
        $currentAd = null;
        if ($id) {
            $currentAd = $adsModel->getById($id);
        }
        $this->view->adsitem = $currentAd;
    }

    protected function getAdsForm()
    {
        $form = $this->getForm('config/forms/front/ads.ini', 'ads/add');
        return $form;
    }
}