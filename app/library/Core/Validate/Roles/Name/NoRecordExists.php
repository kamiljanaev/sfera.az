<?php
class Core_Validate_Roles_Name_NoRecordExists extends Zend_Validate_Db_NoRecordExists
{
	public function __construct($id = null)
	{
		parent::__construct('view_roles', 'name', isset($id)? 'id != '.$id : null);
	}
}