<?php
class Core_Db_Roles_Row extends Core_Db_Table_Row
{
	public function hasAssignedUsers()
	{
		return count($this->findDependentRowset('Core_Db_RolesLinks', 'Ref_To_Role'));
	}

	public function link($action='view')
	{
		return Core_View_Helper_Link :: role($this->id, $action);
	}
}