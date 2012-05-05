<?php
class Core_Validate_PasswordConf extends Zend_Validate_Abstract
{
	const
		PASSWORDNOTVALID = 'dbRecordExists';

	protected
		$_table = null,
		$_field1 = null,
		$_field2 = null,
		$_adapter = null,
		$_messageTemplates = array(
				self::PASSWORDNOTVALID => 'Passwords do not match'
			);

	public function __construct($table, $field1, $field2, Zend_Db_Adapter_Abstract $adapter = null)
	{
		$this->_table = $table;
		$this->_field = $field1;
		$this->_field = $field2;
		if ($adapter == null) {
			$adapter = Zend_Db_Table::getDefaultAdapter();
			if ($adapter == null) {
				throw new Exception('No default adapter was found');
			}
		}
		$this->_adapter = $adapter;
	}

	public function isValid($value)
	{
		$this->_setValue($value);
		$adapter = $this->_adapter;
		$select = $adapter->select()
				->from($this->_table)
				->where($adapter->quoteIdentifier($this->_field) . ' = ?', $value)
				->limit(1);
		$stmt = $adapter->query($select);
		$result = $stmt->fetch();
		if ($result !== null) {
			$this->_error();
			return false;
		}
		return true;
	}
}