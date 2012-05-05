<?php
class Roles_Admin_IndexController extends Core_Controller_Action_CRUD
{
	public function init()
	{
		parent::init();
		$this->_msgAddItem = $this->_translate->translate('roles-message-added');
		$this->_msgDelItem = $this->_translate->translate('roles-message-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/roles/index/');
		$this->_controllerEditUrl = '/admin/module/roles/index/edit';
		$this->_dataModel = new Core_Db_Roles;
		$this->_dataModel->setDefaultOrderField('name');
		$this->_listItems[] = 'name';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();

        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/roles');
        $this->view->msgGridListTitle = $this->_translate->translate('roles-grid-list-title');
        $this->view->msgRolesAdminTitle = $this->_translate->translate('roles-admin-title');
        $this->view->msgRolesAdminEditTitle = $this->_translate->translate('roles-admin-edit-title');
        $this->view->msgGridColumnName = $this->_translate->translate('roles-grid-column-name');
        $this->view->msgRoleIsUsed = $this->_translate->translate('roles-message-used');
        $this->view->msgRoleAlreadyExist = $this->_translate->translate('roles-message-already-exist');
    }

	public function editAction()
	{
		if (null === $dataRow = $this->_dataModel->find($this->_params->id)->current()) {
			$this->redirectToIndex();
		}
		$this->view->form = $this->getForm();
		$this->view->form->setAction($this->view->getUrl($this->_controllerEditUrl.'/id/'.$this->_params->id));
		$sym_arr = array(":", ";", ",");
		if ($this->_request->isPost()) {
			if ($this->view->form->isValid($_POST)) {
				$exclude = array ('id', 'save');
				foreach ($this->view->form->getElements() as $element) {
					if (!in_array($element->getName(), $exclude)) {
						$elName = $element->getName();
						if (isset($dataRow->$elName)) {
							$dataRow->__set($elName, $element->getValue());
						}
					}
				}
				$dataRow->save();
				$this->redirectToIndex();
			}
		} else {
			$exclude = array ('save');
			foreach ($dataRow->toArray() as $property => $value) {
				$el = $this->view->form->getElement($property);
				if (!in_array($property, $exclude) && !empty($el)) {
					$this->view->form->getElement($property)->setValue($value);
				}
			}
		}
	}

	protected function getListItemsValues($itemRow)
	{
		$rows = parent :: getListItemsValues($itemRow);
		$rows[] = $itemRow->hasAssignedUsers();
		return $rows;
	}

	public function getListItemValue($itemRow = null, $itemName='')
	{
		$result = parent::getListItemValue($itemRow, $itemName);
		switch($itemName) {
			case 'name':
				if ($itemRow) {
					$result = Core_View_Helper_Link :: makeLink($itemRow->name, $itemRow->link());
				}
				break;
		}
		return $result;
	}

	protected function getForm($type = 'edit')
	{
		$config = new Zend_Config_Ini('config/forms/admin/roles.ini');
		$form = new Zend_Form($config);
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl('/admin/module/roles/index/edit'));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/roles/index/add/format/json'));
		}
		if ($type == 'edit') {
			$dbRecordNotExists = new Core_Validate_Roles_Name_NoRecordExists($this->_params->id);
		} else {
			$dbRecordNotExists = new Core_Validate_Roles_Name_NoRecordExists();
		}
		$dbRecordNotExists->setMessage($this->view->msgRoleAlreadyExist, 'recordFound');
		$form->name->addValidator($dbRecordNotExists);
		$form->removeDecorator('HtmlTag');

		$form->setDisplayGroupDecorators(array (
				'FormElements',
				array ('HtmlTag', array ('tag' => 'ul', 'class' => 'form')),
		));
		$displayGroups = $form->getDisplayGroups();
		foreach ($displayGroups as $displayGroup) {
			foreach ($displayGroup as $element) {
				$element->setDecorators(array(
						'ViewHelper',
						array (array('elUL'=>'Errors'), array ('class' => 'error')),
						array ('HtmlTag', array ('tag' => 'span', 'class' => 'field')),
						'Label',
						array (array('elLI'=>'HtmlTag'), array ('tag' => 'li', 'class' => 'panel')),
				));
			}
		}
		return $form;
	}

	protected function onDelRow($rowId = null)
	{
		if (!empty($rowId)) {
			$role = $this->_dataModel->find($rowId)->current();
			if ($role) {
				if ($role->hasAssignedUsers()>0) {
					throw new Core_Exception($this->view->msgRoleIsUsed);
				}
			}
		}
		return parent::onDelRow($rowId);
	}
}