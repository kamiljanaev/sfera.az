<?php
class Content_Admin_AdsController extends Core_Controller_Action_CRUD
{
    private
        $delimiter = '--';

	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('ads-message-added');
        $this->_msgDelItem = $this->_translate->translate('ads-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/content/ads/');
		$this->_controllerEditUrl = '/admin/module/content/ads/edit';
		$this->_formIniFileName = 'config/forms/admin/ads.ini';
		$this->_dataModel = new Core_Db_Content_Ads;
		$this->_dataModel->setDefaultOrderField('public_date');
		$this->_dataModel->getDefaultOrderSequence('desc');
		$this->_listItems[] = 'public_date';
        $this->_listItems[] = 'title';
        $this->_listItems[] = 'category_id';
		$this->_listItems[] = 'image';
        $this->_listItems[] = 'user_id';
        $this->_listItems[] = 'activated';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/content/ads');
        $this->view->msgGridListTitle = $this->_translate->translate('ads-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('ads-admin-title');
        $this->view->msgAdminEditTitle = $this->_translate->translate('ads-admin-edit-title');
        $this->view->msgAdminAddTitle = $this->_translate->translate('ads-admin-add-title');
        $this->view->msgAdminViewTitle = $this->_translate->translate('ads-admin-view-title');
        $this->view->msgGridColumnTitle = $this->_translate->translate('ads-grid-column-title');
        $this->view->msgGridColumnDate = $this->_translate->translate('ads-grid-column-date');
        $this->view->msgGridColumnImage = $this->_translate->translate('ads-grid-column-image');
        $this->view->msgGridColumnUser = $this->_translate->translate('ads-grid-column-user');
        $this->view->msgGridColumnCategory = $this->_translate->translate('ads-grid-column-category');
        $this->view->msgGridColumnModerate = $this->_translate->translate('ads-grid-column-moderate');
        $this->view->msgConfirmActivateItem = $this->_translate->translate('ads-message-confirm-activate');
        $this->view->msgItemActivated = $this->_translate->translate('ads-message-item-activated');
        $this->view->msgItemNotActivated = $this->_translate->translate('ads-message-item-not-activated');
        $this->view->msgItemNotActivate = $this->_translate->translate('ads-message-item-not-activate');
    }

    public function indexAction()
    {
        parent::indexAction();
        $usersModel = new Core_Db_Users();
        $authorsIdEmailList = array('' => '---')+$usersModel->getIdEmailList();
        $authorsItemsList = array();
        $this->view->authorsList = '';
        foreach ($authorsIdEmailList as $key=>$value) {
            $authorsItemsList[] = "$key:$value";
        }
        $this->view->authorsList = implode(';', $authorsItemsList);

        $activatedIdNameList = array('' => '---', '1' => $this->view->msgItemActivated, '0' => $this->view->msgItemNotActivated);
        $activatedItemsList = array();
        $this->view->activatedList = '';
        foreach ($activatedIdNameList as $key=>$value) {
            $activatedItemsList[] = "$key:$value";
        }
        $this->view->activatedList = implode(';', $activatedItemsList);

        $treeModel = new Core_Db_Category_Tree();
        $tree = $treeModel->fetchTree(Core_Db_Category_Tree::CATEGORY_ADS);
        foreach ($tree as $treeItem) {
            $parentsValues[$treeItem->id] = $this->buildDelimiter($this->delimiter, $treeItem->tree_depth).$treeItem->title;
        }
        $catIdNameList = array('' => '---')+$parentsValues;
        $this->view->categoryList = '';
        foreach ($catIdNameList as $key=>$value) {
            $catItemsList[] = "$key:$value";
        }
        $this->view->categoryList = implode(';', $catItemsList);
    }

	public function addAction()
	{
		$this->_redirect('edit');
	}

	protected function editRowItem($dataRow = null, $itemName = '', $itemValue = null)
	{
        if ($itemName == 'image') {
            $imagesClass = new Core_Image;
            $result = $imagesClass->uploadImages('/upload/');
            if ($result&&array_key_exists('image', $result)) {
                $itemValue = $result['image']['path'];
            }
        }
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
        $authorsIdEmailList = $usersModel->getIdEmailList();
        $form->user_id->setMultiOptions($authorsIdEmailList);

        $treeModel = new Core_Db_Category_Tree();
        $tree = $treeModel->fetchTree(Core_Db_Category_Tree::CATEGORY_ADS);
        foreach ($tree as $treeItem) {
            $parentsValues[$treeItem->id] = $this->buildDelimiter($this->delimiter, $treeItem->tree_depth).$treeItem->title;
        }
        $form->category_id->setMultiOptions($parentsValues);


        return $form;
    }

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		$result = parent::getListItemValue($itemsRow, $itemName);
		switch($itemName) {
			case 'title':
				if ($itemsRow) {
					$result = Core_View_Helper_Link :: makeLink($itemsRow->title, $itemsRow->link());
				}
				break;
			case 'image':
				if ($itemsRow) {
					$result = Core_Image :: getImageHtmlCode($itemsRow->image, Core_Image :: LISTS);
				}
				break;
            case 'activated':
                if ($result) {
                    $result = $this->view->msgItemActivated;
                } else {
                    $result = '<span class="notActivated">' . Core_View_Helper_Link :: makeLink($this->view->msgItemNotActivate, $itemsRow->link('activate'), array('id' => 'activate-'.$itemsRow->id)) . '</span>';
                }
                break;
            case 'user_id':
                if ($itemsRow) {
                    $author = $itemsRow->getUser();
                    $result = $author->email;
                }
                break;
            case 'category_id':
                if ($itemsRow) {
                    $category = $itemsRow->getCategory();
                    if ($category) {
                        $result = $category->title;
                    } else {
                        $result = '';
                    }
                }
                break;
		}
		return $result;
	}

	protected function formActionSet($form = null, $type = 'edit')
	{
		$form = parent::formActionSet($form, $type);
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl('/admin/module/content/ads/edit/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/content/ads/add'));
		}
		return $form;
	}

    public function activateAction()
    {
        $this->_helper->_layout->disableLayout();
        $response = $this->getResponseArray();
        try {
            $dataRow = $this->editFindDataRow();
            $dataRow->activateNew();
            $response->setCode(Core_Response::RESPONSE_OK);
            $response->addMessage($this->view->msgItemActivated);
        }catch (Zend_Exception $ex) {
            $this->exceptionDeleteData();
            $response->setCode(Core_Response::RESPONSE_ERROR);
            $response->addMessage($ex->getMessage());
        }
        $this->_helper->json->sendJson($response);
    }

    private function buildDelimiter($delimiter = '', $count = 0)
    {
        $i = 0;
        $result = '';
        while ($i < $count) {
            $result .= $delimiter;
            $i++;
        }
        return $result;
    }
}