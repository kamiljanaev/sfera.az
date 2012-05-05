<?php
class Core_Acl_Role_Role implements Zend_Acl_Role_Interface
{
	private
		$_id;

	public function __construct($id = null)
	{
		$this->_id = ($id === null)?'guest':$id;
	}

	public function getRoleId()
	{
		return 'role-'.$this->_id;
	}
}