<?php
class Profile_Admin_IndexController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('profile-message-added');
        $this->_msgDelItem = $this->_translate->translate('profile-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/profile/index/');
		$this->_controllerEditUrl = '/admin/module/profile/index/edit';
		$this->_formIniFileName = 'config/forms/admin/profile.ini';
        $this->_dataModel = new Core_Db_Profiles;
        $this->_awardsLinksModel = new Core_Db_AwardsLinks;
        $this->_newsSubscribeLinksModel = new Core_Db_SubscribeLinks;
        $this->_listItems[] = 'user_id';
		$this->_listItems[] = 'lastname';
		$this->_listItems[] = 'is_real';
		$this->_listItems[] = 'is_vip';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/profile');
        $this->view->msgGridListTitle = $this->_translate->translate('profile-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('profile-admin-title');
        $this->view->msgAdminEditTitle = $this->_translate->translate('profile-admin-edit-title');
        $this->view->msgGridColumnUser = $this->_translate->translate('profile-grid-column-user');
        $this->view->msgGridColumnName = $this->_translate->translate('profile-grid-column-name');
        $this->view->msgGridColumnIsReal = $this->_translate->translate('profile-grid-column-is-real');
        $this->view->msgGridColumnIsVip = $this->_translate->translate('profile-grid-column-is-vip');
        $this->view->msgItemReal = $this->_translate->translate('profile-message-item-real');
        $this->view->msgItemNotReal = $this->_translate->translate('profile-message-item-not-real');
        $this->view->msgItemVip = $this->_translate->translate('profile-message-item-vip');
        $this->view->msgItemNotVip = $this->_translate->translate('profile-message-item-not-vip');
    }

    public function indexAction()
    {
        parent::indexAction();
        $usersModel = new Core_Db_Users();
        $authorsIdEmailList = array('' => '---')+$usersModel->getIdLoginList();
        $authorsItemsList = array();
        $this->view->authorsList = '';
        foreach ($authorsIdEmailList as $key=>$value) {
            $authorsItemsList[] = "$key:$value";
        }
        $this->view->usersList = implode(';', $authorsItemsList);

        $realIdNameList = array('' => '---', '1' => $this->view->msgItemReal, '0' => $this->view->msgItemNotReal);
        $realItemsList = array();
        $this->view->realList = '';
        foreach ($realIdNameList as $key=>$value) {
            $realItemsList[] = "$key:$value";
        }
        $this->view->realList = implode(';', $realItemsList);

        $vipIdNameList = array('' => '---', '1' => $this->view->msgItemVip, '0' => $this->view->msgItemNotVip);
        $vipItemsList = array();
        $this->view->vipList = '';
        foreach ($vipIdNameList as $key=>$value) {
            $vipItemsList[] = "$key:$value";
        }
        $this->view->vipList = implode(';', $vipItemsList);
    }

	public function addAction()
	{
        return;
		$this->_redirect('edit');
	}

	protected function editRowItem($dataRow = null, $itemName = '', $itemValue = null)
	{
		if ( ($dataRow instanceof Zend_Db_Table_Row_Abstract) && strlen($itemName) ) {
			$dataRow->__set($itemName, $itemValue);
		}
	}

	protected function editFindDataRow()
	{
		return $this->loadDataRow($this->_params->id);
	}

    protected function formFillElements($form = null)
    {
        $form = parent::formFillElements($form);
        $usersModel = new Core_Db_Users();
        $usersIdEmailList = $usersModel->getIdEmailList();
        $form->user_id->setMultiOptions($usersIdEmailList);
        if ($form->awards_id) {
            $awardsModel = new Core_Db_Awards();
            $form->awards_id->setMultiOptions($awardsModel->getIdTitleList());
        }
        if ($form->news_categories_id) {
            $categoryModel = new Core_Db_Category_Tree();
            $newsRootCategory = $categoryModel->getByAlias(Core_Db_Category_Tree::CATEGORY_NEWS_ALIAS);
            if ($newsRootCategory) {
                $newsCategoryList = $categoryModel->fetchByParent($newsRootCategory->id, 1);
                $newsCatIdTitle = array();
                foreach ($newsCategoryList as $newsCatItem) {
                    $newsCatIdTitle[$newsCatItem->id] = $newsCatItem->title;
                }
                $form->news_categories_id->setMultiOptions($newsCatIdTitle);

            }
        }
        return $form;
    }

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		$result = parent::getListItemValue($itemsRow, $itemName);
		switch($itemName) {
			case 'lastname':
				if ($itemsRow) {
					$result = $itemsRow->getFullName();
				}
				break;
            case 'is_real':
                if ($result) {
                    $result = $this->view->msgItemReal;
                } else {
                    $result = $this->view->msgItemNotReal;
                }
                break;
            case 'is_vip':
                if ($result) {
                    $result = $this->view->msgItemVip;
                } else {
                    $result = $this->view->msgItemNotVip;
                }
                break;
            case 'user_id':
                if ($itemsRow) {
                    $author = $itemsRow->getUser();
                    $result = $author->login;
                }
                break;
		}
		return $result;
	}

	protected function formActionSet($form = null, $type = 'edit')
	{
		$form = parent::formActionSet($form, $type);
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl('/admin/module/profile/index/edit/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/profile/news/add'));
		}
		return $form;
	}

    protected function beforeEditData()
    {
        $db = $this->_dataModel->getAdapter()->beginTransaction();
        return true;
    }

    protected function exceptionEditData($dataRow = null)
    {
        $db = $this->_dataModel->getAdapter()->rollBack();
        return true;
    }

    protected function afterPrepareEdit($dataRow = null)
    {
        $this->view->form->news_categories_id->setValue($dataRow->getNewsSubscribesCategoriesIds());
        $this->view->form->awards_id->setValue($dataRow->getAwardsIds());
        return true;
    }

    protected function afterEditData($dataRow = null)
    {
        $profileId = $dataRow->id;
        $this->_awardsLinksModel->deleteByProfileId($profileId);
        $awards = $this->_request->getParam('awards_id');
        foreach ($awards as $award) {
            $newAwardLink = $this->_awardsLinksModel->createRow();
            $newAwardLink->profile_id = $profileId;
            $newAwardLink->award_id = $award;
            $newAwardLink->save();
        }

        $this->_newsSubscribeLinksModel->deleteByProfileId($profileId);
        $news_cat_ids = $this->_request->getParam('news_categories_id');
        foreach ($news_cat_ids as $news_cat) {
            $newSubscribeLink = $this->_newsSubscribeLinksModel->createRow();
            $newSubscribeLink->profile_id = $profileId;
            $newSubscribeLink->category_id = $news_cat;
            $newSubscribeLink->save();
        }

        $password = $this->_getParam('password', '');
        $confirm_password = $this->_getParam('password_conf', '');
        if (strlen($password)&&($password == $confirm_password)) {
            if ($password == $confirm_password) {
                $currentUser = $dataRow->getUser();
                $currentUser->setPassword($password);
                $currentUser->save();
            } else {
                throw Zend_Exception($this->_translate->translate('profile-message-cant-change-password'));
            }
        }
        $db = $this->_dataModel->getAdapter()->commit();
        return true;
    }

    protected function beforeDeleteData()
    {
        return $this->beforeAddData();
    }

    protected function afterDeleteData()
    {
        $profileId = $this->_params->id;
        $this->_awardsLinksModel->deleteByProfileId($profileId);
        $this->_newsSubscribeLinksModel->deleteByProfileId($profileId);
        $db = $this->_dataModel->getAdapter()->commit();
        return true;
    }

    protected function exceptionDeleteData()
    {
        $db = $this->_dataModel->getAdapter()->rollBack();
        return true;
    }

}