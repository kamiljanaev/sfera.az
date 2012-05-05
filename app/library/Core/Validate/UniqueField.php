<?php
class Core_Validate_UniqueField extends Zend_Validate_Abstract
{
	const
		RECORD_EXISTS = 'dbRecordExists';

	protected
		$_table = null,
		$_field = null,
		$_adapter = null,
		$_messageTemplates = array(
				self::RECORD_EXISTS => 'Record with this value  already exists'
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
				->limit(1)
		;
		$stmt = $adapter->query($select);
		$result = $stmt->fetch();
		if ($result) {
			$this->_error();
			return false;
		}
		return true;
	}
}