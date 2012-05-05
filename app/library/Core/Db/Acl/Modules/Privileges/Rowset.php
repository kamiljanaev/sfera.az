<?php
class Core_Db_Acl_Modules_Privileges_Rowset extends Core_Db_Table_Rowset
{
	public function getPermissionsArray()
	{
		$_permissionArray = array();
		$count = count($this->_data);
		for($i = 0; $i < $count; $i++) {
			$roleId = $this->_data[$i]['role_id'];
			$moduleName = strtolower($this->_data[$i]['module_name']);
			$controllerName = strtolower($this->_data[$i]['controller_name']);
			$actionName = strtolower($this->_data[$i]['action_name']);
			$_permissionArray[] = array('role' => $roleId, 'module' => $moduleName, 'controller' => $controllerName, 'action' => $actionName);
		}
		return  $_permissionArray;
	}
}