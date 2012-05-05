<?php
abstract class Core_Db_Table_Rowset_Abstract extends Zend_Db_Table_Rowset_Abstract
{
	public function fetchList($keyField, $valueField)
	{
		$result = array();
		$count = count($this->_data);
		for($i = 0; $i < $count; $i++) {
			$row = $this->_data[$i];
			$result[stripslashes($row[$keyField])] = stripslashes($row[$valueField]);
		}
		return $result;
	}

	public function fetchArray()
	{
		$result = array();
		$count = count($this->_data);
		for($i = 0; $i < $count; $i++) {
			$data = array(
					'table'   => $this->getTable(),
					'stored'   => true,
					'data'     => $this->_data[$i]
			);

			$rowClass = $this->getTable()->getRowClass();
			if (!class_exists($rowClass)) {
				require_once 'Zend/Loader.php';
				Zend_Loader::loadClass($rowClass);
			}
			$result[$i] = new $rowClass($data);
		}
		return $result;
	}
}
