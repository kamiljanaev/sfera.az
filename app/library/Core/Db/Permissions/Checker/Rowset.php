<?php
class Core_Db_Permissions_Checker_Rowset extends Core_Db_Table_Rowset
{
	public function getPermissionCacheArray()
	{
		$_permissionCache = array();
		$count = count($this->_data);
		for($i = 0; $i < $count; $i++) {
			$roleId = $this->_data[$i]['role_id'];
			$moduleName = strtolower($this->_data[$i]['module_name']);
			$controllerName = strtolower($this->_data[$i]['controller_name']);
			$actionName = strtolower($this->_data[$i]['action_name']);

			if (!array_key_exists($roleId, $_permissionCache)) {
				$_permissionCache[$roleId]=array();
			}
			if (!array_key_exists($moduleName, $_permissionCache[$roleId])) {
				$_permissionCache[$roleId][$moduleName]=array();
			}
			if (!array_key_exists($controllerName, $_permissionCache[$roleId][$moduleName])) {
				$_permissionCache[$roleId][$moduleName][$controllerName]=array();
			}
			if (!array_key_exists($actionName, $_permissionCache[$roleId][$moduleName][$controllerName])) {
				$_permissionCache[$roleId][$moduleName][$controllerName][$actionName] = 1;
			}
		}
		return  $_permissionCache;
	}
}