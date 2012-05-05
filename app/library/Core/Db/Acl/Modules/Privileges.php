<?php
class Core_Db_Acl_Modules_Privileges extends Core_Db_Table
{
	protected
		$_name = 'view_permissions',
		$_primary = 'action_id',
		$_rowsetClass = 'Core_Db_Acl_Modules_Privileges_Rowset';

	public function getByRoles($rolesArray = array())
	{
		$select = $this->select()->from($this);
		$select->where('role_id in ('.join(',',$rolesArray).')');
		return $this->fetchAll($select)->getPermissionsArray();
	}
}