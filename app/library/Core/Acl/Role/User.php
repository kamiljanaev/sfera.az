<?php
class Core_Acl_Role_User implements Zend_Acl_Role_Interface
{
	private
		$_id;

	public function __construct($id)
	{
		$this->_id = ($id === null)?'guest':$id;
	}

	public function getRoleId()
	{
		return 'user-'.$this->_id;
	}
}