<?php
abstract class Core_Db_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract
{
	private
		$_TIME_TYPES = array ('datetime', 'date', 'time', 'timestamp', 'year');

	public function init()
	{
		$meta = $this->_getMeta();
		foreach ($meta as $colName => $data) {
			if (in_array($data['DATA_TYPE'], $this->_TIME_TYPES) && !empty($this->_data[$colName])) {
				switch ($data['DATA_TYPE']) {
					case 'datetime':
					case 'timestamp':
						$format = Zend_Date::ISO_8601;
						break;
					case 'time':
						$format = 'HH:mm:ss';
						break;
					case 'date':
						$format = 'YYYY-MM-dd';
						break;
					case 'year':
						$format = Zend_Date::YEAR_8601;
						break;
				}
				$this->_data[$colName] = new Core_Date($this->_data[$colName], $format);
			}
		}
	}

	public function save()
	{
		$diff = array_intersect_key($this->_data, $this->_modifiedFields);
		$meta = $this->_getMeta();
		foreach ($diff as $colName => &$colData) {
			if (in_array($meta[$colName]['DATA_TYPE'], $this->_TIME_TYPES) && $colData instanceof Zend_Date) {
				switch ($meta[$colName]['DATA_TYPE']) {
					case 'datetime':
					case 'timestamp':
						$format = 'YYYY-MM-dd HH:mm:ss';
						break;
					case 'time':
						$format = 'HH:mm:ss';
						break;
					case 'date':
						$format = 'YYYY-MM-dd';
						break;
					case 'year':
						$format = Zend_Date::YEAR_8601;
						break;
				}
                if (!($colData instanceof Zend_Date)) {
                    $colData = new Zend_Date($colData, $format);
                }
                $this->_data[$colName] = $colData->get($format);
			}
		}
		return parent::save();
	}

	public function translate($value) 
	{
		if (Zend_Registry::isRegistered('Zend_Translate')) {
			$translator = Zend_Registry::get('Zend_Translate');
			return $translator->translate($value);
		} else {
			return $value;
		}
	}

	protected function _getMeta()
	{
		return $this->_getTable()->info(Core_Db_Table_Abstract::METADATA);
	}

    public function findDependentRowset($dependentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
    {
        $db = $this->_getTable()->getAdapter();

        if (is_string($dependentTable)) {
            try {
                @Zend_Loader::loadClass($dependentTable);
            } catch (Zend_Exception $e) {
                require_once 'Zend/Db/Table/Row/Exception.php';
                throw new Zend_Db_Table_Row_Exception($e->getMessage());
            }
            $dependentTable = new $dependentTable(array('db' => $db));
        }
        if (! $dependentTable instanceof Zend_Db_Table_Abstract) {
            $type = gettype($dependentTable);
            if ($type == 'object') {
                $type = get_class($dependentTable);
            }
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Dependent table must be a Zend_Db_Table_Abstract, but it is $type");
        }

        if ($select === null) {
            $select = $dependentTable->select();
        } else {
            $select->setTable($dependentTable);
        }

        $map = $this->_prepareReference($dependentTable, $this->_getTable(), $ruleKey);

        for ($i = 0; $i < count($map[Zend_Db_Table_Abstract::COLUMNS]); ++$i) {
            $parentColumnName = $db->foldCase($map[Zend_Db_Table_Abstract::REF_COLUMNS][$i]);
            $value = $this->_data[$parentColumnName];
            // Use adapter from dependent table to ensure correct query construction
            $dependentDb = $dependentTable->getAdapter();
            $dependentInfo = $dependentTable->info();
            $dependentColumnName = $dependentDb->foldCase($map[Zend_Db_Table_Abstract::COLUMNS][$i]);
            $dependentTableName = $dependentDb->quoteIdentifier($dependentInfo[Core_Db_Table_Abstract::NAME], true);
            $dependentColumn = $dependentDb->quoteIdentifier($dependentColumnName, true);
            $type = $dependentInfo[Zend_Db_Table_Abstract::METADATA][$dependentColumnName]['DATA_TYPE'];
            $select->where("$dependentTableName.$dependentColumn = ?", $value, $type);
        }
        return $dependentTable->fetchAll($select);
    }

    public function findParentRow($parentTable, $ruleKey = null, Zend_Db_Table_Select $select = null)
    {
        $db = $this->_getTable()->getAdapter();
        if (is_string($parentTable)) {
            try {
                @Zend_Loader::loadClass($parentTable);
            } catch (Zend_Exception $e) {
                require_once 'Zend/Db/Table/Row/Exception.php';
                throw new Zend_Db_Table_Row_Exception($e->getMessage());
            }
            $parentTable = new $parentTable(array('db' => $db));
        }
        if (! $parentTable instanceof Zend_Db_Table_Abstract) {
            $type = gettype($parentTable);
            if ($type == 'object') {
                $type = get_class($parentTable);
            }
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Parent table must be a Zend_Db_Table_Abstract, but it is $type");
        }

        if ($select === null) {
            $select = $parentTable->select();
        } else {
            $select->setTable($parentTable);
        }

        $map = $this->_prepareReference($this->_getTable(), $parentTable, $ruleKey);

        for ($i = 0; $i < count($map[Zend_Db_Table_Abstract::COLUMNS]); ++$i) {
            $dependentColumnName = $db->foldCase($map[Zend_Db_Table_Abstract::COLUMNS][$i]);
            $value = $this->_data[$dependentColumnName];
            // Use adapter from parent table to ensure correct query construction
            $parentDb = $parentTable->getAdapter();
            $parentInfo = $parentTable->info();
            $parentColumnName = $parentDb->foldCase($map[Zend_Db_Table_Abstract::REF_COLUMNS][$i]);
            $parentColumn = $parentDb->quoteIdentifier($parentColumnName, true);
            $parentTableName = $parentDb->quoteIdentifier($parentInfo[Core_Db_Table_Abstract::NAME], true);
            $type = $parentInfo[Zend_Db_Table_Abstract::METADATA][$parentColumnName]['DATA_TYPE'];
            $select->where("$parentTableName.$parentColumn = ?", $value, $type);
        }
        try {
            return $parentTable->fetchRow($select);
        }
        catch(Exception $e) {
            return null;
        }
    }

}
