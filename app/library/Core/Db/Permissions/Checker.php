<?php
class Core_Db_Permissions_Checker extends Core_Db_Permissions
{
	protected
		$_name = 'view_permissions',
		$_primary = 'action_id',
		$_rowsetClass = 'Core_Db_Permissions_Checker_Rowset';
	private static
		$_instance = null;
	private static
		$_permissionCache = array();

	static public function & getInstance()
	{
		if (! isset (self::$_instance)) {
			self::$_instance = new self;
			self::$_instance->fillPermissionCache();

		}
		return self::$_instance;
	}

	private function fillPermissionCache()
	{
		self::$_permissionCache = $this->fetchAll()->getPermissionCacheArray();
	}

	public function checkPermission($roleId, $moduleName, $controllerName, $actionName)
	{
		$moduleName = strtolower(str_replace('-', '', $moduleName));
		$controllerName = strtolower(str_replace('-', '', $controllerName));
		$actionName = strtolower(str_replace('-', '', $actionName));

		return array_key_exists($roleId, self::$_permissionCache) &&
				array_key_exists($moduleName, self::$_permissionCache[$roleId]) &&
				array_key_exists($controllerName, self::$_permissionCache[$roleId][$moduleName]) &&
				array_key_exists($actionName, self::$_permissionCache[$roleId][$moduleName][$controllerName]);
	}
}