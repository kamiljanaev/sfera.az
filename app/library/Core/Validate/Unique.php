<?php
class Core_Validate_Unique extends Zend_Validate_Abstract
{
	const
		NOT_UNIQUE = 'notUnique';

	protected 
		$_table,
		$_field,
		$_baseTableName,
		$_id;

	protected function __construct(Core_Db_Table $table, $field, $baseTableName, $id = null)
	{
		$this->_messages = array();
		$this->_messageTemplates = array(self::NOT_UNIQUE => "is not unique");
		$this->_table = $table;
		$this->_field = $field;
		$this->_baseTableName = $baseTableName;
		$this->_id = $id;
	}

	public function isValid($value)
	{
		if ($this->_validate($value)) {
			return true;
		} else {
			$this->_error(self::NOT_UNIQUE);
			return false;
		}
	}

	private  function _validate($value)
	{
		$db = $this->_table->getAdapter();
		$table = 'view_' . $this->_baseTableName . 's';
		$langTable = 'view_' . $this->_baseTableName . 's_lang';
		$select = $this->_table->select()
				->from($this->_table)
				->setIntegrityCheck(false)
				->join($langTable, $db->quoteIdentifier($table) . '.`id` = ' . $db->quoteIdentifier($langTable) . '.' . $db->quoteIdentifier($this->_baseTableName . '_id'))
				->where($db->quoteIdentifier($langTable) . '.' . $db->quoteIdentifier($this->_field) . '=?',$value)
				->limit(1);
		if ($this->_id != null) {
			$select->where($db->quoteIdentifier($table) . '.`id` != ?',$this->_id);
		}
		return count($this->_table->fetchRow($select)) ? false : true ;
	}
}