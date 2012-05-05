<?php
class Profile_BookmarksController extends Core_Controller_Action
{
    public function indexAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $bookmarksCategoriesModel = new Core_Db_Bookmarks_Categories();
        $this->view->categoriesList = $bookmarksCategoriesModel->getByProfileId($currentProfile->id)->fetchArray();
    }

    public function editAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $bookmarksCategoriesModel = new Core_Db_Bookmarks_Categories();
        $bookmarksModel = new Core_Db_Bookmarks();
        $this->view->categoriesList = $bookmarksCategoriesModel->getByProfileId($currentProfile->id)->fetchArray();
        $this->view->formData = array();
        $bookmarkId = $this->_getParam('id', null);
        $currentBookmark = null;
        $this->view->isEdit = false;
        $this->view->formUrl = '/bookmarks/add';
        if ($bookmarkId) {
            $currentBookmark = $bookmarksModel->getRowInfo($bookmarkId);
            $this->view->isEdit = true;
            $this->view->formUrl = '/bookmarks/edit/'.$bookmarkId;
        }
        if ($this->_request->isPost()) {
            $formValid = true;
            $this->view->formData['title'] = $this->validateField('title');
            if (!$this->view->formData['title']['valid']) {
                $formValid = false;
            }
            $this->view->formData['url'] = $this->validateField('url');
            if (!$this->view->formData['url']['valid']) {
                $formValid = false;
            }
            $this->view->formData['category_id'] = $this->validateField('category_id');
            if (!$this->view->formData['category_id']['valid']) {
                $formValid = false;
            }
            if ($formValid) {
                $bookmarksData = $_POST;
                $bookmarksData['profile_id'] = $currentProfile->id;
                if ($currentBookmark) {
                    $bookmarksRow = $currentBookmark;
                } else {
                    $bookmarksRow = $bookmarksModel->createRow();
                }
                $bookmarksRow->saveData($bookmarksData);
                $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('bookmarks/list'));
            }
        } else {
            if ($currentBookmark) {
                $this->view->formData['title']['value'] = $currentBookmark->title;
                $this->view->formData['title']['valid'] = true;
                $this->view->formData['url']['value'] = $currentBookmark->url;
                $this->view->formData['url']['valid'] = true;
                $this->view->formData['category_id']['value'] = $currentBookmark->category_id;
                $this->view->formData['category_id']['valid'] = true;
            } else {
                $bookmarkCatId = $this->_getParam('category_id', null);
                if ($bookmarkCatId) {
                    $this->view->formData['category_id']['value'] = $bookmarkCatId;
                    $this->view->formData['category_id']['valid'] = true;
                    $this->view->formUrl = '/bookmarks/add/'.$bookmarkCatId;
                }
            }
        }
    }

    public function removeAction()
    {
        $this->_helper->_layout->disableLayout();
        $bookmarkId = $this->_getParam('id', null);
        $currentBookmark = null;
        if ($bookmarkId) {
            $bookmarksModel = new Core_Db_Bookmarks();
            $currentBookmark = $bookmarksModel->getRowInfo($bookmarkId);
            if ($currentBookmark) {
                $currentBookmark->delete();
            }
        }
        $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('bookmarks/list'));
    }

    public function categoryAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $this->view->formData = array();
        $bookmarksCategoriesModel = new Core_Db_Bookmarks_Categories();
        $bookmarksCategoryId = $this->_getParam('id', null);
        $currentBookmarksCategory = null;
        $this->view->isEdit = false;
        $this->view->formUrl = '/bookmarks/category/add';
        if ($bookmarksCategoryId) {
            $currentBookmarksCategory = $bookmarksCategoriesModel->getRowInfo($bookmarksCategoryId);
            $this->view->isEdit = true;
            $this->view->formUrl = '/bookmarks/category/edit/'.$bookmarksCategoryId;
        }
        if ($this->_request->isPost()) {
            $formValid = true;
            $this->view->formData['title'] = $this->validateField('title');
            if (!$this->view->formData['title']['valid']) {
                $formValid = false;
            }
            if ($formValid) {
                $bookmarksCatData = $_POST;
                $bookmarksCatData['profile_id'] = $currentProfile->id;
                if ($currentBookmarksCategory) {
                    $bookmarksCatRow = $currentBookmarksCategory;
                } else {
                    $bookmarksCatRow = $bookmarksCategoriesModel->createRow();
                }
                $bookmarksCatRow->saveData($bookmarksCatData);
                $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('bookmarks/list'));
            }
        } else {
            if ($currentBookmarksCategory) {
                $this->view->formData['title']['value'] = $currentBookmarksCategory->title;
                $this->view->formData['title']['valid'] = true;
            }
        }
    }

    public function removecatAction()
    {
        $this->_helper->_layout->disableLayout();
        $bookmarksCategoryId = $this->_getParam('id', null);
        $currentBookmarksCategory = null;
        if ($bookmarksCategoryId) {
            $bookmarksCategoriesModel = new Core_Db_Bookmarks_Categories();
            $currentBookmarksCategory = $bookmarksCategoriesModel->getRowInfo($bookmarksCategoryId);
            if ($currentBookmarksCategory) {
                $currentBookmarksCategory->delete();
            }
        }
        $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('bookmarks/list'));
    }


}