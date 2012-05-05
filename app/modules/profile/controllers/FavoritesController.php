<?php
class Profile_FavoritesController extends Core_Controller_Action
{
    public function indexAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $favoritesModel = new Core_Db_Favorites();
        $this->view->favoritesList = $favoritesModel->getByProfileId($currentProfile->id)->fetchArray();
    }

    public function addAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $formData = array();
        $formValid = true;
        $formData['type'] = $this->validateField('type');
        if (!$formData['type']['valid']) {
            $formValid = false;
        }
        $formData['id'] = $this->validateField('id');
        if (!$formData['id']['valid']) {
            $formValid = false;
        }
        $favoritesModel = new Core_Db_Favorites();
        if ($formValid && !$favoritesModel->isExist($currentProfile->id, $formData['type']['value'], $formData['id']['value'])) {
            $favoritesData = array();
            $favoritesData['profile_id'] = $currentProfile->id;
            $favoritesData['item_id'] = $formData['id']['value'];
            $favoritesData['type'] = $formData['type']['value'];
            $favoritesRow = $favoritesModel->createRow();
            $favoritesRow->saveData($favoritesData);
            $this->_helper->json->sendJson(array('result' => 1));
        }
        $this->_helper->json->sendJson(array('result' => 0));
    }
}