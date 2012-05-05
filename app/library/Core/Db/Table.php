<?php
class Core_Db_Table extends Core_Db_Table_Abstract
{
	private 
		$_defaultOrderField = '',
		$_defaultOrderSequence = '',
		$_onlyNotDeleted = true,
		$_meta = null;
	protected
		$_multiSearchCondition = '',
		$_select = null;

	public function getTableName()
	{
		return  $this->info(Zend_Db_Table_Abstract::NAME);
	}

	public function checkDeletedCol()
	{
		$meta = $this->info('metadata');
		return isset($meta['deleted']);
	}

	public function getDefaultOrderField()
	{
		return $this->_defaultOrderField;
	}

	public function setDefaultOrderField($_defaultOrderField)
	{
		$this->_defaultOrderField = $_defaultOrderField;
	}

	public function getDefaultOrderSequence()
	{
		return $this->_defaultOrderSequence;
	}

	public function setDefaultOrderSequence($_defaultOrderSequence)
	{
		$this->_defaultOrderSequence = $_defaultOrderSequence;
	}

	public function select($useDefaultOrder=true)
	{
		$select = new Core_Db_Table_Select($this);
		$orderString = "";
		if ($this->getDefaultOrderField() != "") {
			$orderString = $this->getDefaultOrderField();
			if ($this->getDefaultOrderSequence() != "") {
				$orderString .= " " . $this->getDefaultOrderSequence();
			}
		}
		if ($useDefaultOrder && $orderString != "") {
			$select->order($orderString);
		}
		return $select;
	}

	public function fetchList($keyField, $valueField, $where = array())
	{
		$select = $this->select()->from($this);
		if (count($where)>0) {
			foreach ($where as $condition)
				$select->where($condition);
		}
		return $this->fetchAll($select)->fetchList($keyField, $valueField);
	}

	public function getInfoList($idKey, $valueKeys = array(), $separator = ' - ', $where = array(), $order = array())
	{
		$select = $this->select()->from($this);
		if (count($where)>0) {
			foreach ($where as $condition)
				$select->where($condition);
		}
		if (count($order)>0) {
			foreach ($order as $condition)
				$select->order($condition);
		}
		$data = $this->fetchAll($select)->fetchArray();
		$result = array();
		foreach ($data as $item) {
			$values = array();
			foreach ($valueKeys as $key) {
				if (isset($item->$key)) {
					$values[] = $item->$key;
				}
			}
			$value = implode($separator, $values);
			if (!strlen($value)) {
				$value = 'id';
			}
			$result[$item->$idKey] = $value;
		}
		return $result;
	}

	public function getRowsCount($where = null, $select=null)
	{
		if ($select != null) {
			return count($this->fetchAll($select));
		}
		$select = $this->select(false)->from($this->_name, array('count' => 'COUNT(*)'));
		if ($where !== null) {
			$this->_where($select, $where);
		}
		$ret = $this->_db->fetchCol($select);
		if (!count($ret)) {
			return 0;
		}
		return $ret[0];
	}

	public function getKeyField()
	{
		$primary_key = $this->info(self::PRIMARY);
		return $primary_key[1];
	}

	public function addData($data = null)
	{
		$key_field = $this->getKeyField();
		if (!$data || !is_array($data)) {
			throw new Zend_Exception("No set data in addData method");
		}
		$new_data = $this->createRow();
		foreach ($data as $key => &$val) {
			if (isset($new_data->$key)) {
				$new_data->$key = $val;
			}
		}
		$new_data->save($data);
		return $new_data->$key_field;
	}

	public function removeData($id)
	{
		if ($row = $this->find($id)->current()) {
			return $row->delete();
		}
		return false;
	}

	public function updateData($data = null)
	{
		$key_field = $this->getKeyField();
		$result = false;
		if (!$data || !is_array($data)) {
			return $result;
		}
		if ($row = $this->find($data[$key_field])->current()) {
			foreach ($data as $key => &$val) {
				if (isset($row->$key)) {
					$row->$key = $val;
				}
			}
			$row->save($data);
			$result = true;
		}
		return $result;
	}

	public function getRowInfo($id)
	{
		if ($id) {
			$key_field = $this->getKeyField();
			$select = $this->select()->from($this);
			$select->where($key_field.' = ?', $id);
			return $this->fetchRow($select);
		}
		return false;
	}

	public function getAllList()
	{
		$select = $this->getSelect();
		$result = $this->fetchAll($select);
		return $result;
	}

	public function getListCount($searchField = null, $searchOper = null, $searchString = null)
	{
		$select = $this->getSelect();
		if ($whereStr = $this->getSearchCondition($searchField, $searchOper, $searchString)) {
			$select->where($whereStr);
		}
		if ($this->getMultiSearchCondition() != '') {
			$select->where($this->getMultiSearchCondition());
		}
		$result = $this->fetchAll($select)->count();
		return $result;
	}

	public function getList($page = 1, $rp = 10, $searchField = null, $searchOper = null, $searchString = null, $sortname = null, $sortorder = null)
	{
		$select = $this->getSelect();
		if ($whereStr = $this->getSearchCondition($searchField, $searchOper, $searchString)) {
			$select->where($whereStr);
		}
		if ($this->getMultiSearchCondition() != '') {
			$select->where($this->getMultiSearchCondition());
		}
		if ($sortname) {
			if ($sortorder === null) {
				$sortorder = 'desc';
			}
			$select->order($sortname." ".$sortorder);
		}
		$start = (($page-1) * $rp);
		$rp = $rp;
		$select->limit($rp, $start);

		$result = $this->fetchAll($select);
		return $result;
	}

	public function setMultiSearchCondition($params = null, $listItems = null)
	{
		if (!$params||!$listItems) {
			$whereStr = '';
		} else {
			$whereStr = '1 = 1';
			//if (($params->search = 'true') && (!$params->searchField) && (!$params->searchOper) && (!$params->searchString)) {
			if ($params->search = 'true') {
				$sarr = $_REQUEST;
				foreach ( $sarr as $fieldName=>$fieldValue) {
					if (in_array($fieldName, $listItems)) {
						$whereStr .= " AND `".$this->_name."`.`".$fieldName."` LIKE '%".$fieldValue."%'";
					}
				}
			}
		}
		$this->_multiSearchCondition = $whereStr;
	}

	public function getMultiSearchCondition()
	{
		return $this->_multiSearchCondition;
	}

	protected function getSearchCondition($searchField = null, $searchOper = null, $searchString = null)
	{
		$whereStr = false;
		if ($searchField && ($searchString !== null)) {
			switch($searchOper) {
				case 'bw':
					$whereStr = '`'.$searchField.'` like \''.$searchString.'%\'';
					break;
				case 'eq':
					$whereStr = '`'.$searchField.'` = \''.$searchString.'\'';
					break;
				case 'ne':
					$whereStr = '`'.$searchField.'` <> \''.$searchString.'\'';
					break;
				case 'lt':
					$whereStr = '`'.$searchField.'` < \''.$searchString.'\'';
					break;
				case 'le':
					$whereStr = '`'.$searchField.'` <= \''.$searchString.'\'';
					break;
				case 'gt':
					$whereStr = '`'.$searchField.'` > \''.$searchString.'\'';
					break;
				case 'ge':
					$whereStr = '`'.$searchField.'` >= \''.$searchString.'\'';
					break;
				case 'ew':
					$whereStr = '`'.$searchField.'` like \'%'.$searchString.'\'';
					break;
				default:
					$whereStr = '`'.$searchField.'` like \'%'.$searchString.'%\'';
					break;
			}
		}
		return $whereStr;
	}

	protected function getSelect()
	{
		if ($this->_select !== null) {
			return $this->_select;
		}else {
			return $this->select()->from($this);
		}
	}

	public function setSelect($select = null)
	{
		$this->_select = $select;
	}
}
