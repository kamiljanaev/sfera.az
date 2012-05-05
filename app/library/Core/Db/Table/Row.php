<?php
class Core_Db_Table_Row extends Core_Db_Table_Row_Abstract
{
	protected
		$_autoTrim = true;

	public function delete()
	{
		if ($this->_getTable()->checkDeletedCol()) {
			$this->deleted = new Core_Date(time());
			$this->save();
		}else {
			parent::delete();
		}
	}

    public function save()
    {
        try {
            return parent :: save();
        } catch(Zend_Exception $ex) {
            if (!($ex instanceof Zend_Db_Table_Row_Exception && $ex->getMessage() == 'Cannot refresh row as parent is missing')) {
                throw $ex;
            }
        }
    }

    public function saveData($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$val) {
                if (array_key_exists($key, $this->_data)) {
                    $this->__set($key, $val);
                }
            }
            $this->save();
        }
        return $this;
    }

    public function __set($columnName, $value)
    {
        if (!isset($value)) {
            parent::__set($columnName, null);
            return;
        }
        if (is_string($value)) {
            if ($this->_autoTrim) $value = trim($value);
        }
        if (array_key_exists($columnName, $this->_data) && ($this->_data[$columnName] instanceof Zend_Date)) {
            $this->_data[$columnName] = new Core_Date($value, Zend_Registry::get('DateFormat'));
            $this->_modifiedFields[$columnName] = 1;
        } else {
            parent::__set($columnName, $value);
        }
    }
/*
    public function __get($columnName)
    {
        if (array_key_exists($columnName, $this->_data) && ($this->_data[$columnName] instanceof Zend_Date)) {
            $dateObj = $this->_data[$columnName];
            return $dateObj->toString(Zend_Registry::get('DateFormat'));
        } else {
            return parent::__get($columnName);
        }
    }
*/
	public function toArray()
	{
		$array = parent::toArray();
		foreach ($array as $key => &$value) {
			if (is_string($value)) {
				if (get_magic_quotes_gpc() === 0) $value = stripslashes($value);
			}
			if ($value instanceof Zend_Date ) $value = $value->toString(Zend_Registry::get('DateFormat'));
		}
		return $array;
	}
}
