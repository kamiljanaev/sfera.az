<?php
class Core_Acl extends Zend_Acl
{
	const
		DEFAULT_ROLE_NAME = 'guest';

	protected
		$_currentRoles = array(),
		$_currentUser = null;

	public function __construct($userId, $processActiveUser = false)
	{
		if (!$userId&&$processActiveUser) {
			$this->setCurrentUser($this->getActiveUser());
		} else {
			$this->setCurrentUser($userId);
		}
		$this->setCurrentRoles($this->getUserRolesArray($this->getCurrentUser()));
		$this->buildAcl();
	}

	public function getCurrentUser()
	{
		return $this->_currentUser;
	}

	public function setCurrentUser($currentUser = null)
	{
		$this->_currentUser = $currentUser;
	}

	public function getCurrentRoles()
	{
		return $this->_currentRoles;
	}

	public function setCurrentRoles($currentRoles = array())
	{
		$this->_currentRoles = $currentRoles;
	}

	public function addRole($role, $parents = null)
	{
		if ($parents&&!$this->hasRole($parents)) {
			parent::addRole($parents);
			$parents = $this->getRole($parents);
		}
		if (!$this->hasRole($role)) {
			return parent::addRole($role, $parents);
		} else {
			return $this->getRole($role);
		}
	}

	public function addResource($resource, $parent = null)
	{
		if ($resource&&$this->has($resource)&&$parent&&$this->has($parent)) {
			$this->updateResource($resource, $parent);
			return $this->get($resource);
		}
		if ($parent&&!$this->has($parent)) {
			parent::addResource($parent);
		}
		if ($resource&&!$this->has($resource)) {
			return parent::addResource($resource, $parent);
		} else {
			return $this->get($resource);
		}
	}

	public function updateResource($resource, $parent = null)
	{
		$resourceId = $resource->getResourceId();

		$resourceParent = null;

		if (null !== $parent) {
			$resourceParentId = $parent->getResourceId();
			$resourceParent = $this->get($resourceParentId);
			$this->_resources[$resourceParentId]['children'][$resourceId] = $resource;
		}

		$this->_resources[$resourceId] = array(
				'instance' => $resource,
				'parent'   => $resourceParent
		);

		return $this;
	}

	protected function buildAcl()
	{
		$modulesPrivileges = new Core_Db_Acl_Modules_Privileges();
		$privilegesList = $modulesPrivileges->getByRoles($this->getCurrentRoles());
		$userRole = $this->addUserRole($this->getCurrentUser());
		foreach ($privilegesList as $privilegeItem) {
			$roleRole = new Core_Acl_Role_Role($privilegeItem['role']);
			$moduleResource = new Core_Acl_Resource_Module($privilegeItem['module'], $privilegeItem['controller'], $privilegeItem['action']);
			$modulePrivilege = $this->preparePrivilege($privilegeItem['action']);
			$this->addPermission($userRole, $moduleResource, $modulePrivilege);
			$this->addPermission($roleRole, $moduleResource, $modulePrivilege);
		}
	}

	protected function addPermission($role, $resource = null, $privilege = null)
	{
		$this->addRole($role);
		if ($resource) {
			$this->addResource($resource);
		}
		return $this->allow($role, $resource, $privilege);
	}

	protected function addDeny($role, $resource = null, $privilege = null)
	{
		$this->addRole($role);
		if ($resource) {
			$this->addResource($resource);
		}
		return $this->deny($role, $resource, $privilege);
	}

	protected function checkPermission($role, $resource = null, $privilege = null)
	{
		$this->addRole($role);
		if ($resource) {
			$this->addResource($resource);
		}
		return $this->isAllowed($role, $resource, $this->preparePrivilege($privilege));
	}

	protected function preparePrivilege($privilege)
	{
		return $privilege;
	}

	protected function addUserRole($userId)
	{
		$userRole = new Core_Acl_Role_User($userId);
		$this->addRole($userRole);
		return $userRole;
	}

	protected function addRoleRole($roleId)
	{
		$roleRole = new Core_Acl_Role_Role($roleId);
		$this->addRole($roleRole);
		return $roleRole;
	}

	private function getActiveUser()
	{
		$activeUserId = null;
		if ($user = Core_Auth::getInstance()->getIdentity()) {
			$activeUserId = $user->id;
		}
		return $activeUserId;
	}

	private function getUserRolesArray($userId = null)
	{
		$rolesArray = array();
		if ($userId) {
			$usersModel = new Core_Db_Users();
			$currentUserRowset = $usersModel->find($userId);
			if ($currentUserRowset->count()) {
				$currentUser = $currentUserRowset->current();
				$rolesArray = $currentUser->getRolesIds();
			}
		}
		if (!count($rolesArray)) {
			$roles = new Core_Db_Roles;
			if (null == $role = $roles->getByName( self::DEFAULT_ROLE_NAME )) {
				throw new Core_Controller_Action_Helper_Auth_Exception('Default role with name "'.self::DEFAULT_ROLE_NAME.'" not exists.');
			}
			$roleId = $role->id;
			$rolesArray = array($roleId);
		}
		return $rolesArray;
	}
}
