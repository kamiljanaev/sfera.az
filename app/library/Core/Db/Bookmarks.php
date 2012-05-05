<?php
class Core_Db_Bookmarks extends Core_Db_Table
{
	protected
        $_currentType = null,
        $_name = 'view_bookmarks',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Bookmarks_Row',
		$_rowsetClass = 'Core_Db_Bookmarks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			),
            'Ref_To_Category' => array(
                            'columns' => array('category_id'),
                            'refTableClass'	=> 'Core_Db_Bookmarks_Categories',
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

    public function getByCategoryId($categoryId = null)
    {
        if (!$categoryId) {
            return null;
        }
        $select = $this->getSelect();
        $select->where('category_id = ?', $categoryId);
        return $this->fetchAll($select);
    }
}