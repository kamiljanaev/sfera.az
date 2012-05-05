<?php
class Content_Admin_CategoryController extends Core_Controller_Action_CRUD
{
	private
		$delimiter = '--';

	public function init()
	{
		parent::init();
		$this->_dataModel = new Core_Db_Category_Tree();
		$this->_controllerSaveUrl = '/admin/module/content/category/save';
		$this->_formIniFileName = 'config/forms/admin/category.ini';
		$this->view->controller = $this;
		$this->_msgSaveItem = $this->_translate->translate('category-message-saved');
        $this->view->controller = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/content/category');
        $this->view->msgAdminTitle = $this->_translate->translate('category-admin-title');
    }

	public function indexAction()
	{
	}

	public function initAction()
	{
		$this->_dataModel->rebuildTreeTraversal();
		$tree = $this->_dataModel->fetchTreeById(1);
		Core_Vdie::_($tree);
	}

	public function fetchAction()
	{
		$parentId = $this->_getParam('parent_id', null);
		$tree = $this->_dataModel->fetchByParent($parentId);
		$nodes = array();
		foreach ($tree as $itemsRow) {
			$class = 'category_item';
			$leaf = array();
			$leaf['data'] = $itemsRow->title;
			$leaf['attr'] = array(
				'id' => $itemsRow->id,
				'class' => $class,
				'href' => Core_View_Helper_GetUrl::getCorrectUrl('admin/module/content/category/edit/id/'.$itemsRow->id),
				'target' => '_blank'
			);
            $descendentCount = (int) $this->_dataModel->hasDescendent($itemsRow->id);
			if ($descendentCount) {
				$leaf['state'] = 'closed';
			}
			$nodes[] = $leaf;
		}
		$this->_helper->json->sendJson($nodes);
	}

	public function addAction()
	{
		$this->_helper->_layout->disableLayout();
		$this->view->form = $this->getForm();
		$this->render('form');
	}

	public function loadAction()
	{
		$this->_helper->_layout->disableLayout();
		$dataRow = $this->editFindDataRow();
        if ($dataRow->readonly) {
            $this->render('readonly');
            return;
        }
		$this->view->itemRow = $dataRow;
		$this->view->form = $this->getForm();
		$this->beforePrepareEdit($dataRow);
		foreach ($dataRow->toArray() as $property => $value) {
			$el = $this->view->form->getElement($property);
			if ($this->checkPrepareEditItem($property, $el)) {
				$el->setValue($value);
			}
		}
		$this->afterPrepareEdit($dataRow);
		$this->render('form');
	}

	public function saveAction()
	{
		$this->_helper->_layout->disableLayout();
		$response = $this->getResponseArray();
		$dataRow = $this->editFindDataRow();
		$this->view->itemRow = $dataRow;
		$this->view->form = $this->getForm();
		if ($this->_request->isPost()) {
			if ($this->view->form->isValid($_POST)) {
				try {
					if (!$dataRow) {
						$values = $this->view->form->getValues();
						unset($values['id']);
						unset($values['order']);
						$id = $this->_dataModel->insert($values);
					} else {
						$this->beforeEditData();
						$this->beforeEditRow($dataRow);
						foreach ($this->view->form->getElements() as $element) {
							if ($this->checkRowItem($element)) {
								$elName = $element->getName();
								if (isset($dataRow->$elName)) {
									$onEditRowResult = $this->onEditRowItem($elName, $element->getValue());
									if ($onEditRowResult['result']) {
										$this->editRowItem($dataRow, $onEditRowResult['name'], $onEditRowResult['value']);
									}
								}
							}
						}
						$this->afterEditRow($dataRow);
						$this->saveDataRow($dataRow);
						$this->afterEditData($dataRow);
						$this->_dataModel->rebuildTreeTraversal();
                        $navigation = new Core_Navigation();
                        $navigation->clearNavigationCache();
					}
					$response->setCode(Core_Response::RESPONSE_OK);
					$response->addMessage($this->_msgSaveItem);
				}catch (Exception $ex) {
					Core_Vdie::_($ex);
					$this->exceptionEditData($dataRow);
					$response->setCode(Core_Response::RESPONSE_ERROR);
					$response->addMessage($ex->getMessage());
				}
			}
		}
		$this->_helper->json->sendJson($response);
	}

	protected function onEditRowItem($itemName = null, $itemValue = null)
	{
		$result = parent::onEditRowItem($itemName, $itemValue);
		if ($itemName == 'parent_id') {
			if (!$itemValue) {
				$result['result'] = false;
			}
		}
		return $result;
	}

	public function editAction()
	{
		$this->_helper->_layout->disableLayout();
		parent::editAction();
	}

	public function deleteAction()
	{
		$this->_helper->_layout->disableLayout();
		$response = $this->getResponseArray();
		try {
			if ($this->onDelRow($this->_params->id)) {
				$this->_dataModel->removeData($this->_params->id);
				$this->afterDeleteData();
				$response->setCode(Core_Response::RESPONSE_OK);
				$response->addMessage($this->_msgDelItem);
			} else {
				$response->setCode(Core_Response::RESPONSE_ERROR);
				$response->addMessage($this->_msgNotDelItem);
			}
		}catch (Zend_Exception $ex) {
			$this->exceptionDeleteData();
			$response->setCode(Core_Response::RESPONSE_ERROR);
			$response->addMessage($ex->getMessage());
		}
		$this->_helper->json->sendJson($response);
	}

	public function moveAction()
	{
		$this->_helper->_layout->disableLayout();
		$response = $this->getResponseArray();
		try {
			$objectId = $this->_getParam('id', null);
			$parentId = $this->_getParam('pid', null);
			$position = $this->_getParam('pos', 0);
			if ($objectId) {
				if (!$this->_dataModel->moveNode($objectId, $parentId, $position)) {
					throw new Zend_Exception('[MOVE] Object moving error');
				}
			} else {
				throw new Zend_Exception('[MOVE] Object id is null');
			}
			$response->setCode(Core_Response::RESPONSE_OK);
			$response->addMessage($this->_translate->translate('message-node-moved'));
		}catch (Zend_Exception $ex) {
			$response->setCode(Core_Response::RESPONSE_ERROR);
			$response->addMessage($ex->getMessage());
		}
		$this->_helper->json->sendJson($response);
	}

	protected function formActionSet($form = null, $type = 'save')
	{
		if (!($form instanceof Zend_Form)) {
			throw Core_Controller_Action_CRUD_Exception_InstanceNotForm();
		}
		$form->setAction($this->view->getUrl($this->_controllerSaveUrl.'/id/'.$this->_params->id));
		return $form;
	}

	protected function formFillElements($form = null)
	{
		$form = parent::formFillElements($form);
		$tree = $this->_dataModel->fetchTree();
//        $tree = $this->_dataModel->fetchByParent();
		foreach ($tree as $treeItem) {
            $parentsValues[$treeItem->id] = $this->buildDelimiter($this->delimiter, $treeItem->tree_depth).$treeItem->title;
//            $parentsValues[$treeItem->id] = $treeItem->title;
		}
/*		$currentId = $this->_getParam('id', null);
		if ($currentId) {
			$currentTreeItem = $this->_dataModel->getRowInfo($currentId);
            $subTree = $this->_dataModel->fetchTree($currentTreeItem);
			$subParentsValues = array();
			foreach ($subTree as $subTreeItem) {
				$subParentsValues[$subTreeItem->id] = $this->buildDelimiter($this->delimiter, $subTreeItem->tree_depth).$subTreeItem->title;
			}
			$parentsValues = array_diff($parentsValues, $subParentsValues);
		}*/
		$form->parent_id->setMultiOptions($parentsValues);
		return $form;
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

	protected function editFindDataRow()
	{
		return $this->getDataRow($this->_params->id);
	}

}
