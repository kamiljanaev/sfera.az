<?php
class Core_Db_Users extends Core_Db_Table
{
	protected
		$_name = 'view_users',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Users_Row',
		$_rowsetClass = 'Core_Db_Users_Rowset';

	public function getUsersInfoList()
	{
		return $this->getInfoList('id', array('l_name', 'f_name', 'm_name', 'email'), ' ', array(), array('l_name', 'f_name', 'm_name'));
	}

    public function getIdEmailList()
    {
        return $this->fetchList('id', 'email');
    }

    public function getIdLoginList()
    {
        return $this->fetchList('id', 'login');
    }

    public function getByFBID($fbId = null)
    {
        if (!$fbId) {
            return null;
        }
        $select = $this->select()->from($this)->where('fb_id = ?', $fbId);
        return $this->fetchRow($select);
    }

    public function getByTwID($twId = null)
    {
        if (!$twId) {
            return null;
        }
        $select = $this->select()->from($this)->where('tw_id = ?', $twId);
        return $this->fetchRow($select);
    }

    public function getUserByEmail($email = null, $exclude_id = null)
    {
        if (!$email) {
            return null;
        }
        $select = $this->select()->from($this)->where('email = ?', $email);
        if ($exclude_id) {
            $select->where('id <> ?', $exclude_id);
        }
        return $this->fetchRow($select);
    }

    public function getUserByLogin($login = null, $exclude_id = null)
    {
        if (!$login) {
            return null;
        }
        $select = $this->select()->from($this)->where('login = ?', $login);
        if ($exclude_id) {
            $select->where('id <> ?', $exclude_id);
        }
        return $this->fetchRow($select);
    }

	protected function getSelect()
	{
		if (!$this->_select) {
			$select = $this->select()->from($this);
			$select->setIntegrityCheck(false);
			return $select;
		}else {
			return $this->_select;
		}
	}

	protected function getSearchCondition($searchField = null, $searchOper = null, $searchString = null)
	{
		if ($searchField) {
			$searchField = $this->_name.'`.`'.$searchField;
		}
		return parent::getSearchCondition($searchField, $searchOper, $searchString);
	}

	public function setMultiSearchCondition($params, $listItems = array())
	{
		$whereStr = '1 = 1';
		if (($params->search = 'true') && (!$params->searchField) && (!$params->searchOper) && (!$params->searchString)) {
			$sarr = $_REQUEST;
			foreach ( $sarr as $fieldName=>$fieldValue) {
				if ($fieldName == 'name') {
					$whereStr .= " AND (".$this->_name.".firstname LIKE '%".$fieldValue."%' OR ".$this->_name.".lastname LIKE '%".$fieldValue."%' OR ".$this->_name.".secondname LIKE '%".$fieldValue."%')";
				} elseif (in_array($fieldName, $listItems)) {
					$whereStr .= " AND ".$this->_name.".".$fieldName." LIKE '".$fieldValue."%'";
				}
			}
		}
		$this->_multiSearchCondition = $whereStr;
	}

	public function getForActivate($hash = null)
	{
		if ($hash) {
			$select = $this->select()->from($this);
			$select->where('md5(CONCAT(id, email)) = ?', $hash);
			return $this->fetchRow($select);
		}
		return false;
	}

    public function grabOnlineUsers()
    {
        $data = array(
            'is_online' => 0
        );
        $where = 'is_online = 1 and unix_timestamp(CURRENT_TIMESTAMP) - unix_timestamp(changed) = 600';
        $this->update($data, $where);
    }
}