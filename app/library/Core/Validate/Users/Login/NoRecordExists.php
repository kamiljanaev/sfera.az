<?php
class Core_Validate_Users_Login_NoRecordExists extends Zend_Validate_Db_NoRecordExists
{
	public function __construct($id = null)
	{
		parent::__construct('view_users', 'login', isset($id)? 'id != '.$id : null);
	}
}