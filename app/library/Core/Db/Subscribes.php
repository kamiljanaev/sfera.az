<?php
class Core_Db_Subscribes extends Core_Db_Table
{
    const
        SUBSCRIBE_NEWS = 1;

	protected
        $_currentType = null,
        $_name = 'view_subscribes',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Subscribes_Row',
		$_rowsetClass = 'Core_Db_Subscribes_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_User' => array(
							'columns' => array('user_id'),
							'refTableClass'	=> 'Core_Db_Users',
							'refColumns' => array('id')
			)
		);

    public function setCurrentType($typeId = null)
    {
        $this->_currentType = $typeId;
    }

    public function getCurrentType()
    {
        return $this->_currentType;
    }

	public function getByUserId($userId = null)
	{
		if (!$userId) {
			return null;
		}
		$select = $this->getSelect();
		$select->where('user_id = ?', $userId);
		return $this->fetchAll($select);
	}

    protected function getSelect()
    {
        $select = parent::getSelect();
        if ($this->_currentType !== null) {
            $select->where('type_id = ?', $this->_currentType);
        }
        return $select;
    }
}