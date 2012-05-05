<?php
class Core_Db_Bookmarks_Categories extends Core_Db_Table
{
	protected
        $_currentType = null,
        $_name = 'view_bookmarks_category',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Bookmarks_Categories_Row',
		$_rowsetClass = 'Core_Db_Bookmarks_Categories_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			)
		);

	public function getByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		$select = $this->getSelect();
		$select->where('profile_id = ?', $profileId);
		return $this->fetchAll($select);
	}
}