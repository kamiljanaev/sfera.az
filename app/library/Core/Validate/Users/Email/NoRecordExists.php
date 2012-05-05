<?php
class Core_Validate_Users_Email_NoRecordExists extends Zend_Validate_Db_NoRecordExists
{
	public function __construct($id=null)
	{
		parent::__construct('view_users', 'email', isset($id)? 'id != '.$id : null);
	}
}