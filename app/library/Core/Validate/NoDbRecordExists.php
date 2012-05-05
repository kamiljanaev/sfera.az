<?php
class Core_Validate_NoDbRecordExists extends Zend_Validate_Abstract
{
	const
		NO_RECORD_EXISTS = 'NoDbRecordExists';

	protected
		$_table = null,
		$_field = null,
		$_adapter = null,
		$_messageTemplates = array(
				self::NO_RECORD_EXISTS => 'Record with value %value% already exists in table'
			);

	public function __construct($table, $field, Zend_Db_Adapter_Abstract $adapter = null)
	{
		$this->_table = $table;
		$this->_field = $field;
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
		if ($result) {
			$this->_error();
			return false;
		}
		return true;
	}
}