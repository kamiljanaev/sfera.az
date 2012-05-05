<?php
class Content_NewsController extends Core_Controller_Action
{
    public function addedAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
    }
    
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
            $this->view->currentProfileData = $currentProfile;
            $this->view->currentUserData = $this->_currentUser;
            $this->view->userData = $this->_currentUser;
            $this->view->profileData = $currentProfile;
            $this->view->newsForm = $this->getNewsForm();
            $this->view->formData = array();
            if ($this->_request->isPost()) {
                $formValid = true;
                $this->view->formData['title'] = $this->validateField('title');
                if (!$this->view->formData['title']['valid']) {
                    $formValid = false;
                }
                $this->view->formData['short_content'] = $this->validateField('short_content');
                if (!$this->view->formData['short_content']['valid']) {
                    $formValid = false;
                }
                $this->view->formData['content'] = $this->validateField('content');
                if (!$this->view->formData['content']['valid']) {
                    $formValid = false;
                }
                if ($formValid) {
                    $newsData = $_POST;
                    $newsData['user_id'] = $this->_currentUser->id;
                    $newsData['public_date'] = new Core_Date(time());
                    $imageObject = new Core_Image();
                    $result = $imageObject->uploadImages('/upload/');
                    $newsData['image'] = array_key_exists('image', $result)?$result['image']['path']:'';
                    $newsModel = new Core_Db_Content_News();
                    $newsRow = $newsModel->createRow();
                    $newsRow->saveData($newsData);
                    $this->view->added = true;
                    $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('news/added'));
                }
            }
        } else {
            $this->_flashMessenger->addMessage($this->_translate->translate('message-user-not-logged'));
        }
    }

    public function categoryAction()
    {

        $categoryModel = new Core_Db_Category_Tree;
        $this->view->category = $this->_getParam('category', null);
        if (!$this->view->category) {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('news'));
        }
        $this->view->newsCategory = $categoryModel->getRowInfo($this->view->category);
        $this->view->newsCategoriesList = $categoryModel->fetchByParent($this->view->newsCategory->id, 1)->fetchArray();

        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultCategory($this->view->category);
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);

        $this->view->topNewsList = $newsModel->getList(1, 5, null, null, null, 'public_date', 'desc')->fetchArray();

        $this->view->newsDataArray = array();
        $this->view->newsHotDataArray = array();
        $this->view->newsHotDataArray = $newsModel->getList(1, 2, 'is_hot', 'eq', '1', 'public_date', 'desc')->fetchArray();
        $this->view->newsDataArray = $newsModel->getList($this->_getParam('page', 1), 10, null, null, null, 'public_date', 'desc')->fetchArray();

//        $this->view->newsList = $newsModel->getList($this->_getParam('page', 1), 10, null, null, null, 'public_date', 'desc')->fetchArray();

        $this->view->totalCount = $newsModel->getListCount();
        $this->view->totalPages = ceil($this->view->totalCount/10);
        $this->view->paginatorCode = $this->view->paginator($this->_getParam('page', 1), $this->view->totalPages, $this->view->totalCount, 'news/category/'.$this->view->category);
    }

    public function tagsAction()
    {

        $tagsModel = new Core_Db_Content_Tags;
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $this->view->tag = $this->_getParam('tag', null);
        $this->view->newsTag = $tagsModel->getRowInfo($this->view->tag);
        if (!$this->view->newsTag) {
            $this->_redirect(Core_View_Helper_GetUrl::getCorrectUrl('news'));
        }
        $this->view->placeholder('bodyClass')->set(' class="tag'.$this->view->tag.'"');
        $this->view->newsDataArrayLast = $newsModel->getNewsByTag($this->view->tag, $this->_getParam('page', 1), 10, 'public_date', 'desc')->fetchArray();
        $this->view->newsDataArrayView = $newsModel->getNewsByTag($this->view->tag, $this->_getParam('page', 1), 10, 'view_count', 'desc')->fetchArray();
        $this->view->newsDataArrayRating = $newsModel->getNewsByTag($this->view->tag, $this->_getParam('page', 1), 10, 'rating', 'desc')->fetchArray();
        $this->view->totalCount = $newsModel->getCountNewsByTag($this->view->tag);
        $this->view->totalPages = ceil($this->view->totalCount/10);
        $this->view->paginatorCode = $this->view->paginator($this->_getParam('page', 1), $this->view->totalPages, $this->view->totalCount, 'news/tags/'.$this->view->tag);
    }

    public function listAction()
    {
        $newsModel = new Core_Db_Content_News();
        $this->view->myNewsFlag = $this->_getParam('my', null);
        if ($this->view->myNewsFlag && $this->_currentUser) {
            $newsModel->setCurrentUser($this->_currentUser->id);
        } else {
            $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        }
        $this->view->totalCount = $newsModel->getListCount();
        $this->view->totalPages = ceil($this->view->totalCount/10);
        $this->view->newsList = $newsModel->getList($this->_getParam('page', 1), 10);
        $this->view->paginatorCode = $this->view->paginator($this->_getParam('page', 1), $this->view->totalPages, $this->view->totalCount, 'news/list');
    }

    public function showAction()
    {
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $newsModel = new Core_Db_Content_News;
        $newsModel->setDefaultStatus(Core_Db_Content_News::NEWS_VISIBLE);
        $id = $this->_request->getParam('id', null);
        $currentNew = null;
        if ($id) {
            $currentNew = $newsModel->getById($id);
            $this->view->placeholder('bodyClass')->set(' class="single '.$currentNew->getCategory()->alias.'"');
        }
        $currentNew->addViewCounter();
        $this->view->newsitem = $currentNew;
        $this->view->newstags = $currentNew->getTags();
        $newsModel->setDefaultCategory($currentNew->category_id);
        $this->view->newsHotDataArray = array();
        $this->view->newsHotDataArray = $newsModel->getList(1, 4, 'is_hot', 'eq', '1', 'public_date', 'desc')->fetchArray();
    }

    protected function getNewsForm()
    {
        $form = $this->getForm('config/forms/front/news.ini', 'news/add');
        return $form;
    }

    public function subscribeAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $formData = array();
            $formValid = true;
            $formData['id'] = $this->validateField('id');
            if (!$formData['id']['valid']) {
                $formValid = false;
            }
            $formData['type'] = $this->validateField('type');
            if ($formValid) {
                $subsData = array();
                $subsData['profile_id'] = $currentProfile->id;
                $subsData['category_id'] = $formData['id']['value'];
                $subsModel = new Core_Db_SubscribeLinks();
                if ($formData['type']['value'] == 'follow') {
                    if (!$subsModel->isExist($currentProfile->id, $formData['id']['value'])) {
                        $subsRow = $subsModel->createRow();
                        $subsRow->saveData($subsData);
                    }
                } else {
                    if ($subsModel->isExist($currentProfile->id, $formData['id']['value'])) {
                        $subsRow = $subsModel->getByProfileIdCategoryId($currentProfile->id, $formData['id']['value']);
                        if ($subsRow) {
                            $subsRow->delete();
                        }
                    }
                }
                $this->_helper->json->sendJson(array('result' => 1));
            }
        $this->_helper->json->sendJson(array('result' => 0));
    }

    public function tagsubscribeAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $formData = array();
            $formValid = true;
            $formData['id'] = $this->validateField('id');
            if (!$formData['id']['valid']) {
                $formValid = false;
            }
            $formData['type'] = $this->validateField('type');
            if ($formValid) {
                $subsData = array();
                $subsData['profile_id'] = $currentProfile->id;
                $subsData['tag_id'] = $formData['id']['value'];
                $subsModel = new Core_Db_Content_Tags_SubscribeLinks;
                if ($formData['type']['value'] == 'follow') {
                    if (!$subsModel->isExist($currentProfile->id, $formData['id']['value'])) {
                        $subsRow = $subsModel->createRow();
                        $subsRow->saveData($subsData);
                    }
                } else {
                    if ($subsModel->isExist($currentProfile->id, $formData['id']['value'])) {
                        $subsRow = $subsModel->getByProfileIdTagId($currentProfile->id, $formData['id']['value']);
                        if ($subsRow) {
                            $subsRow->delete();
                        }
                    }
                }
                $this->_helper->json->sendJson(array('result' => 1));
            }
        $this->_helper->json->sendJson(array('result' => 0));
    }

    public function ratingAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $formData = array();
            $formValid = true;
            $formData['id'] = $this->validateField('id');
            if (!$formData['id']['valid']) {
                $formValid = false;
            }
            $formData['value'] = $this->validateField('value');
            if (!$formData['value']['valid']) {
                $formValid = false;
            }
            if ($formValid) {
                $ratingData = array();
                $ratingData['profile_id'] = $currentProfile->id;
                $ratingData['type_id'] = Core_Db_Ratings::R_NEWS;
                $ratingData['item_id'] = $formData['id']['value'];
                $ratingData['ip'] = (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
                if ($formData['value']['value'] == 'up') {
                    $ratingData['value'] = 1;
                } elseif ($formData['value']['value'] == 'down') {
                    $ratingData['value'] = -1;
                } else {
                    $this->_helper->json->sendJson(array('result' => 0));
                    return;
                }
                $newsModel = new Core_Db_Content_News;
                $curentNewItem = $newsModel->getRowInfo($ratingData['item_id']);
                if ($curentNewItem) {
                    $ratingsModel = new Core_Db_Ratings();
                    if (!$ratingsModel->isExist($currentProfile->id, $ratingData['item_id'], $ratingData['type_id'])) {
                        $ratingRow = $ratingsModel->createRow();
                        $ratingRow->saveData($ratingData);
                        $curentNewItem->recalculateRating();
                    }
                    $this->_helper->json->sendJson(array('result' => $curentNewItem->rating));
                    return;
                }
            }
        $this->_helper->json->sendJson(array('result' => 0));
    }

    public function getratingAction()
    {
        $this->_helper->_layout->disableLayout();
        $currentProfile = $this->checkCurrentProfile();
        $this->view->profileData = $currentProfile;
        $this->view->userData = $this->_currentUser;
        $this->view->currentProfileData = $currentProfile;
        $this->view->currentUserData = $this->_currentUser;
        $id = $this->validateField('id');
        $ratingHelper = new Core_View_Helper_GetRatingValueNews;
        $rating = $ratingHelper->getRatingValueNews($id);
        $this->_helper->json->sendJson(array('result' => $rating));
    }

}