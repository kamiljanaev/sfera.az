<?php
class Content_Admin_NewsController extends Core_Controller_Action_CRUD
{
    private
        $delimiter = '--';

	public function init()
	{
		parent::init();
        $this->_msgAddItem = $this->_translate->translate('news-message-added');
        $this->_msgDelItem = $this->_translate->translate('news-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/content/news/');
		$this->_controllerEditUrl = '/admin/module/content/news/edit';
		$this->_formIniFileName = 'config/forms/admin/news.ini';
		$this->_dataModel = new Core_Db_Content_News;
		$this->_dataModel->setDefaultOrderField('public_date');
		$this->_dataModel->getDefaultOrderSequence('desc');
		$this->_listItems[] = 'public_date';
        $this->_listItems[] = 'title';
        $this->_listItems[] = 'category_id';
		$this->_listItems[] = 'image';
        $this->_listItems[] = 'user_id';
        $this->_listItems[] = 'is_hot';
        $this->_listItems[] = 'activated';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/content/news');
        $this->view->msgGridListTitle = $this->_translate->translate('news-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('news-admin-title');
        $this->view->msgAdminEditTitle = $this->_translate->translate('news-admin-edit-title');
        $this->view->msgAdminAddTitle = $this->_translate->translate('news-admin-add-title');
        $this->view->msgAdminViewTitle = $this->_translate->translate('news-admin-view-title');
        $this->view->msgGridColumnTitle = $this->_translate->translate('news-grid-column-title');
        $this->view->msgGridColumnDate = $this->_translate->translate('news-grid-column-date');
        $this->view->msgGridColumnImage = $this->_translate->translate('news-grid-column-image');
        $this->view->msgGridColumnUser = $this->_translate->translate('news-grid-column-user');
        $this->view->msgGridColumnCategory = $this->_translate->translate('news-grid-column-category');
        $this->view->msgGridColumnModerate = $this->_translate->translate('news-grid-column-moderate');
        $this->view->msgGridColumnIsHot = $this->_translate->translate('news-grid-column-is-hot');
        $this->view->msgConfirmActivateItem = $this->_translate->translate('news-message-confirm-activate');
        $this->view->msgItemIsHot = $this->_translate->translate('news-message-item-is-hot');
        $this->view->msgItemIsNotHot = $this->_translate->translate('news-message-item-not-hot');
        $this->view->msgItemActivated = $this->_translate->translate('news-message-item-activated');
        $this->view->msgItemNotActivated = $this->_translate->translate('news-message-item-not-activated');
        $this->view->msgItemNotActivate = $this->_translate->translate('news-message-item-not-activate');
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

        $hotIdNameList = array('' => '---', '1' => $this->view->msgItemIsHot, '0' => $this->view->msgItemIsNotHot);
        $hotItemsList = array();
        $this->view->hotList = '';
        foreach ($hotIdNameList as $key=>$value) {
            $hotItemsList[] = "$key:$value";
        }
        $this->view->hotList = implode(';', $hotItemsList);

        $treeModel = new Core_Db_Category_Tree();
        $tree = $treeModel->fetchTree(Core_Db_Category_Tree::CATEGORY_NEWS);
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
        if ($itemName == 'public_date') {
            $year = $this->_getParam('dt_year');
            $month = $this->_getParam('dt_month');
            $day = $this->_getParam('dt_day');
            $hour = $this->_getParam('dt_hour');
            $min = $this->_getParam('dt_min');
            $pub_date = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';
            $pub_date = new Zend_Date();
            $pub_date->set($year, Zend_Date::YEAR);
            $pub_date->set($month, Zend_Date::MONTH);
            $pub_date->set($day, Zend_Date::DAY);
            $pub_date->set($hour, Zend_Date::HOUR);
            $pub_date->set($min, Zend_Date::MINUTE);
            $dataRow->public_date = $pub_date;
//            echo $dataRow->public_date;
//            die;
        } else {
            if ( ($dataRow instanceof Zend_Db_Table_Row_Abstract) && strlen($itemName) ) {
                $dataRow->__set($itemName, $itemValue);
            }

        }
	}

	protected function editFindDataRow()
	{
		return $this->loadDataRow($this->_params->id);
	}

    protected function loadDataRow($rowId = null)
    {
        if (!$rowId) {
            return $this->_dataModel->createRow();
        } else {
            $dataRow = $this->getDataRow($rowId);
            $publicDate = new Zend_Date($dataRow->public_date);
            $this->view->dt_year = $publicDate->get(Zend_Date::YEAR);
            $this->view->dt_month = $publicDate->get(Zend_Date::MONTH);
            $this->view->dt_day = $publicDate->get(Zend_Date::DAY);
            $this->view->dt_hour = $publicDate->get(Zend_Date::HOUR);
            $this->view->dt_min = $publicDate->get(Zend_Date::MINUTE);
//            Core_Vdie::_($dataRow->public_date);
            return $dataRow;
        }
    }
    protected function formFillElements($form = null)
    {
        $form = parent::formFillElements($form);
        $usersModel = new Core_Db_Users();
        $authorsIdEmailList = $usersModel->getIdEmailList();
        $form->user_id->setMultiOptions($authorsIdEmailList);

        $treeModel = new Core_Db_Category_Tree();
        $tree = $treeModel->fetchTree(Core_Db_Category_Tree::CATEGORY_NEWS);
        foreach ($tree as $treeItem) {
            $parentsValues[$treeItem->id] = $this->buildDelimiter($this->delimiter, $treeItem->tree_depth).$treeItem->title;
        }
        $form->category_id->setMultiOptions($parentsValues);

        $tagsModel = new Core_Db_Content_Tags;
        $this->view->tagsList = $tagsModel->getByType(Core_Db_Category_Tree::CATEGORY_NEWS);
        $this->view->currentTagsList = array();
        if ($this->_params->id) {
            $tagsLinksModel = new Core_Db_Content_TagsLinks;
            $tagsLinksList = $tagsLinksModel->getByItemId($this->_params->id, Core_Db_Category_Tree::CATEGORY_NEWS);
            foreach ($tagsLinksList as $tagsListItem) {
                $this->view->currentTagsList[] = $tagsListItem->tag_id;
            }
        }

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
            case 'is_hot':
                if ($result) {
                    $result = $this->view->msgItemIsHot;
                } else {
                    $result = $this->view->msgItemIsNotHot;
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
			$form->setAction($this->view->getUrl('/admin/module/content/news/edit/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/content/news/add'));
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

    protected function afterEditData($dataRow = null)
    {
        $newId = $dataRow->id;
        $tagsLinksModel = new Core_Db_Content_TagsLinks;
        $tagsLinksModel->deleteByItemId($newId, Core_Db_Content_Tags::TAG_TYPE_NEWS);
        $tags = $this->_request->getParam('tags');
        foreach ($tags as $tag) {
            $newTagLink = $tagsLinksModel->createRow();
            $newTagLink->tag_id = $tag;
            $newTagLink->type_id = Core_Db_Content_Tags::TAG_TYPE_NEWS;
            $newTagLink->item_id = $newId;
            $newTagLink->save();
        }
        $db = $this->_dataModel->getAdapter()->commit();
        $this->recalculateTags();
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
        $this->recalculateTags();
        return true;
    }

    protected function exceptionDeleteData()
    {
        $db = $this->_dataModel->getAdapter()->rollBack();
        return true;
    }

    protected function recalculateTags()
    {
        $tagsModel = new Core_Db_Content_Tags;
        $tagsModel->recalculateTags();
    }

}