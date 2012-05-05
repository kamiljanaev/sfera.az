<?php
class Core_Db_RolesLinks_Row extends Core_Db_Table_Row
{
	private
		$_Role = null,
		$_User = null;

	public function getRole()
	{
		if ($this->_Role==null) {
			$this->_Role = $this->findParentRow('Core_Db_Roles', 'Ref_To_Role');
		}
		return $this->_Role;
	}

	public function getUser()
	{
		if ($this->_User==null) {
			$this->_User = $this->findParentRow('Core_Db_Users', 'Ref_To_User');
		}
		return $this->_User;
	}
}