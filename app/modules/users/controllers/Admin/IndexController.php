<?php
class Users_Admin_IndexController extends Core_Controller_Action_CRUD
{
	protected
		$_currentUser,
		$_rolesLinksModel = null,
		$_rolesModel = null;

	public function init()
	{
		parent::init();
		$this->_rolesModel = new Core_Db_Roles;
		$this->_rolesLinksModel = new Core_Db_RolesLinks;
		$this->_currentUser = Core_Auth::getInstance()->getIdentity();
		$this->_msgAddItem = $this->_translate->translate('users-message-added');
		$this->_msgDelItem = $this->_translate->translate('users-message-deleted');
		$this->_msgNotDelItem = $this->_translate->translate('users-message-not-deleted');
		$this->_controllerUrl = $this->view->getUrl('/admin/module/users/index/');
		$this->_controllerEditUrl = '/admin/module/users/index/edit';
		$this->_dataModel = new Core_Db_Users;
		$this->_dataModel->setDefaultOrderField('login');
        $this->_listItems[] = 'login';
        $this->_listItems[] = 'email';
		$this->_listItems[] = 'role';
		$this->_dataModel->setMultiSearchCondition($this->_params, $this->_listItems);
		$this->view->model = $this;
        $this->getMessages();
	}

    protected function getMessages()
    {
        parent::getMessages();

        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/users');
        $this->view->msgGridListTitle = $this->_translate->translate('users-grid-list-title');
        $this->view->msgAdminTitle = $this->_translate->translate('users-admin-title');
        $this->view->msgAdminEditTitle = $this->_translate->translate('users-admin-edit-title');
        $this->view->msgGridColumnName = $this->_translate->translate('users-grid-column-name');
        $this->view->msgGridColumnLogin = $this->_translate->translate('users-grid-column-login');
        $this->view->msgGridColumnEmail = $this->_translate->translate('users-grid-column-email');
        $this->view->msgGridColumnRoles = $this->_translate->translate('users-grid-column-roles');
        $this->view->msgUserAlreadyExist = $this->_translate->translate('users-message-already-exist');
        $this->view->msgCantDelSelf = $this->_translate->translate('users-message-cant-del-self');
        $this->view->msgUserNotAdded = $this->_translate->translate('users-message-not-added');
    }

	public function viewAction()
	{
		parent::viewAction();
		$this->view->canDelete = ($this->_currentUser->id != $this->view->dataRow->id);
	}

	protected function getForm($type = 'edit')
	{
		$this->_formIniFileName = 'config/forms/admin/users.ini';
		$this->view->isAdding = $type != 'edit';
		$form = parent::getForm($type);
		return $form;
	}

	public function getListItemValue($itemsRow = null, $itemName = '')
	{
		$itemValue = parent::getListItemValue($itemsRow, $itemName);
		switch ($itemName) {
			case 'role':
				$userRoles = $itemsRow->getRoles();
				$result = array();
				if (count($userRoles)) {
					foreach ($userRoles as $userRole) {
						$result[] = Core_View_Helper_Link :: makeLink($userRole->name, $userRole->link());
					}
				}
				$result = implode(' ', $result);
				break;
			default:
				$result = $itemValue;
		}
		return $result;
	}

	protected function getListItemsValues($itemsRow)
	{
		$row = parent::getListItemsValues($itemsRow);
		if ($this->_currentUser instanceof Core_Db_Users_Row) {
			$row[] = ($itemsRow->id == $this->_currentUser->id)?1:0;
		} else {
			$row[] = 0;
		}
		return $row;
	}

	protected function formActionSet($form = null, $type = 'edit')
	{
		$form = parent::formActionSet($form, $type);
		if ($type == 'edit') {
			$form->setAction($this->view->getUrl($this->_controllerEditUrl.'/id/'.$this->_params->id));
		} else {
			$form->setAction($this->view->getUrl('/admin/module/users/index/add'));
		}
		return $form;
	}

	protected function formFillElements($form = null)
	{
		$form = parent::formFillElements($form);
		if ($form->role_id) {
			$rolesModel = new Core_Db_Roles();
			$form->role_id->setMultiOptions($rolesModel->getIdNameList());
			$defaultRole = $rolesModel->getByName();
			$form->role_id->setValue($defaultRole->id);
		}
		if (!$this->view->isAdding) {
            $dbRecordNotExistsEmail = new Core_Validate_Users_Email_NoRecordExists($this->_params->id);
            $dbRecordNotExistsLogin = new Core_Validate_Users_Login_NoRecordExists($this->_params->id);
			$form->password->setRequired(false);
			$form->password_conf->setRequired(false);
		} else {
            $dbRecordNotExistsEmail = new Core_Validate_Users_Email_NoRecordExists();
            $dbRecordNotExistsLogin = new Core_Validate_Users_Login_NoRecordExists();
		}

        $dbRecordNotExistsEmail->setMessage($this->view->msgUserAlreadyExist, 'recordFound');
        $form->email->addValidator($dbRecordNotExistsEmail);

        $dbRecordNotExistsLogin->setMessage($this->view->msgUserAlreadyExist, 'recordFound');
        $form->login->addValidator($dbRecordNotExistsLogin);

		$equalInputs = new Core_Validate_EqualInputs('password');
		$equalInputs->setMessage($this->view->msgValidationPwdConfirmError);
		$form->password_conf->addValidator($equalInputs);
		return $form;
	}

	protected function afterPrepareEdit($dataRow = null)
	{
		$this->view->form->role_id->setValue($dataRow->getRolesIds());
		return true;
	}

	protected function getExcludeFields()
	{
		return array('id', 'role', 'password_conf', 'save', );
	}

	protected function getExcludeFieldsForEdit()
	{
		return array('password', 'role', 'password_conf', 'save');
	}

	protected function onDelRow()
	{
		if ($this->_params->id == $this->_currentUser->id) {
			throw new Core_Exception($this->view->msgCantDelSelf);
		}

		return true;
	}

	protected function beforeAddData()
	{
		$db = $this->_dataModel->getAdapter()->beginTransaction();
		return true;
	}

	protected function afterAddData($dataRow = null)
	{
		$userId = $dataRow->id;
		$this->_rolesLinksModel->deleteByUserId($userId);
		$roles = $this->_request->getParam('role_id');
		if (!count($roles)) {
			$roles[] = $this->_rolesModel->getByName();
		}
		foreach ($roles as $role) {
			$newRoleLink = $this->_rolesLinksModel->createRow();
			$newRoleLink->user_id = $userId;
			$newRoleLink->role_id = $role;
			$newRoleLink->save();
		}
		$db = $this->_dataModel->getAdapter()->commit();
		return true;
	}

	protected function exceptionAddData($dataRow = null)
	{
		$db = $this->_dataModel->getAdapter()->rollBack();
		return true;
	}


	protected function beforeEditData()
	{
		return $this->beforeAddData();
	}

	protected function afterEditData($dataRow = null)
	{
		return $this->afterAddData($dataRow);
	}

	protected function exceptionEditData($dataRow = null)
	{
		return $this->exceptionAddData($dataRow);
	}

	protected function beforeDeleteData()
	{
		return $this->beforeAddData();
	}

	protected function afterDeleteData()
	{
		$userId = $this->_params->id;
		$this->_rolesLinksModel->deleteByUserId($userId);
		$db = $this->_dataModel->getAdapter()->commit();
		return true;
	}

	protected function exceptionDeleteData()
	{
		$db = $this->_dataModel->getAdapter()->rollBack();
		return true;
	}
}
