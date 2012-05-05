<?php
class Core_Db_Content_Ads extends Core_Db_Table
{
    const
        ADS_ALL_PAYED = null,
        ADS_NOT_PAYED = 0,
        ADS_PAYED = 1,
        NEWS_HIDDEN = 0,
        NEWS_VISIBLE = 1,
        NEWS_ALL = null;
	protected
		$_name = 'view_ads',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Content_Ads_Row',
		$_rowsetClass = 'Core_Db_Content_Ads_Rowset',
        $_referenceMap	= array(
            'Ref_To_User' => array(
                            'columns' => array('user_id'),
                            'refTableClass'	=> 'Core_Db_Users',
                            'refColumns' => array('id')
            ),
            'Ref_To_Category' => array(
                            'columns' => array('category_id'),
                            'refTableClass'	=> 'Core_Db_Category_Tree',
                            'refColumns' => array('id')
            )
        ),
        $_currentUser = null,
        $_defaultStatus = self::NEWS_ALL,
        $_defaultPayedStatus = self::ADS_ALL_PAYED;

    public function setPayedStatus($defaultStatus =  self::ADS_ALL_PAYED)
    {
        $this->_defaultPayedStatus = $defaultStatus;
    }

    public function getPayedStatus()
    {
        return $this->_defaultPayedStatus;
    }

    public function setCurrentUser($userId = null)
    {
        $this->_currentUser = $userId;
    }

    public function getCurrentUser()
    {
        return $this->_currentUser;
    }

    public function setDefaultStatus($defaultStatus = self::NEWS_ALL)
    {
        if (in_array($defaultStatus, array(self::NEWS_ALL, self::NEWS_HIDDEN, self::NEWS_VISIBLE))) {
            $this->_defaultStatus = $defaultStatus;
        } else {
            $this->_defaultStatus = self::NEWS_ALL;
        }
    }

    public function getDefaultStatus()
    {
        return $this->_defaultStatus;
    }

	public function getById( $id )
	{
		$select = $this->select()->where( '`id` = ?', $id );
		return $this->fetchRow($select);
	}

    public function getByUserId( $userId )
    {
        $select = $this->select()->where( '`user_id` = ?', $userId );
        return $this->fetchAll($select)->fetchArray();
    }

    public function getActualAds($category_id = null)
    {
        $select = $this->getSelect();
        if ($category_id) {
            $select->where('category_id = ?', $category_id);
        }
        $select->where('NOW() <= `to_date`');
        return $this->fetchAll($select)->fetchArray();
    }

    protected function getSelect()
    {
        $select = parent::getSelect();
        if ($this->_currentUser !== null) {
            $select->where('user_id = ?', $this->_currentUser);
        }
        if ($this->_defaultStatus !== self::NEWS_ALL) {
            $select->where('activated = ?', $this->_defaultStatus);
        }
        if ($this->_defaultPayedStatus !== self::ADS_ALL_PAYED) {
            $select->where('is_payed = ?', $this->_defaultPayedStatus);
        }
        return $select;
    }
}