<?php
abstract class Core_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected
		$_rowClass = 'Core_Db_Table_Row',
		$_rowsetClass = 'Core_Db_Table_Rowset';
	private
		$callCache = array();
	protected static
		$_cache_data = array();

	public function isExists($params = array(), $excludingParams = array())
	{
		$select = $this->select()->setUseCache(false)->limit(1);
		foreach ($params as $key => $val) {
			$select->where("$key = ?", trim($val));
		}
		foreach ($excludingParams as $key => $val) {
			$select->where("$key <> ?",trim($val));
		}
		return (bool)$this->fetchRow($select);
	}

	public function __call($nm,$args)
	{
		if (preg_match("/(?<=^getBy)((\S+)(Id)|(\S+))$/",$nm,$res)) {
			if (empty($args[0])) {
				return null;
			}

			if (empty($this->callCache[$nm . $args[0]])) {
				$field = (!empty($res[3]))?(strtolower($res[2]) . '_id'):(strtolower($res[1]));
				$select = $this->select()->where($field . '=?',$args[0]);
				$this->callCache[$nm . $args[0]] = $this->fetchAll($select);
			}
			return $this->callCache[$nm . $args[0]];
		}
		throw new Exception('Invalid method Core_Db_Table::' . $nm);
	}

	public function getEmptyRowset()
	{
		$select = $this->select()->where("0");
		return $this->fetchAll($select);
	}

	protected function _fetch(Zend_Db_Table_Select $select)
	{
		$queryString = $select->__toString();
		$useCache = ($select instanceof Core_Db_Table_Select)?$select->isUseCache():true;
		if (array_key_exists($queryString, self::$_cache_data) && $useCache) {
			$data  = self::$_cache_data[$queryString];
		}
		else {
			$data  = self::$_cache_data[$queryString] = $this->_fetchReal($select);
		}
		return $data;
	}

	protected function _fetchReal(Zend_Db_Table_Select $select)
	{
		return parent::_fetch($select);
	}

}