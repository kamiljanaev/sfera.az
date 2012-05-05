<?php
class Core_Db_Permissions_Row extends Core_Db_Table_Row
{
	private
		$_Role = null,
		$_Module = null,
		$_Controller = null,
		$_Action = null;

	public function getRole()
	{
		if ($this->_Role==null) {
			$this->_Role = $this->findParentRow('Core_Db_Roles', 'Ref_To_Role');
		}
		return $this->_Role;
	}

	public function getModule()
	{
		if ($this->_Module==null) {
			$this->_Module = $this->findParentRow('Core_Db_Modules', 'Ref_To_Module');
		}
		return $this->_Module;
	}

	public function getController()
	{
		if ($this->_Controller==null) {
			$this->_Controller = $this->findParentRow('Core_Db_Controllers', 'Ref_To_Controller');
		}
		return $this->_Controller;
	}

	public function getAction()
	{
		if ($this->_Action==null) {
			$this->_Action = $this->findParentRow('Core_Db_Actions', 'Ref_To_Action');
		}
		return $this->_Action;
	}
}