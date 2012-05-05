<?php
class Core_Validate_Billing_Card_NoRecordExists extends Zend_Validate_Db_NoRecordExists
{
	public function __construct($id = null)
	{
		parent::__construct('view_scratch_cards', 'number', isset($id)? 'id != '.$id : null);
	}
}