<?php
class Core_Acl_Modules extends Core_Acl
{
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

	protected function preparePrivilege($privilege)
	{
		return str_replace("-","",$privilege);
	}

	public function checkPermissionByRole($roleId, $moduleName, $controllerName, $actionName)
	{
		$role = new Core_Acl_Role_Role($roleId);
		$resource = new Core_Acl_Resource_Module($moduleName, $controllerName, $actionName);
		return $this->checkPermission($role, $resource, $actionName);
	}

	public function checkPermissionByUser($userId, $moduleName, $controllerName, $actionName)
	{
		if (!$userId) {
			$userId = $this->getCurrentUser();
		}
		$role = new Core_Acl_Role_User($userId);
		$resource = new Core_Acl_Resource_Module($moduleName, $controllerName, $actionName);
		return $this->checkPermission($role, $resource, $actionName);
	}
}
