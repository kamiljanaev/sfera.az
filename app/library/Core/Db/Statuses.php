<?php
class Core_Db_Statuses extends Core_Db_Table
{
	protected
        $_name = 'view_statuses',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Statuses_Row',
		$_rowsetClass = 'Core_Db_Statuses_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_User' => array(
							'columns' => array('user_id'),
							'refTableClass'	=> 'Core_Db_Users',
							'refColumns' => array('id')
			)
		);

	public function getByUserId($userId = null)
	{
		if (!$userId) {
			return null;
		}
        $this->setDefaultOrderField('added');
        $this->setDefaultOrderSequence('desc');
		$select = $this->getSelect();
		$select->where('user_id = ?', $userId);
		return $this->fetchAll($select);
	}
}