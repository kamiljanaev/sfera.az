<?php
class Core_Db_Favorites extends Core_Db_Table
{
    const
        FW_NEWS = 1,
        FW_ADS = 2,
        FW_BLOGS = 3;
    
	protected
        $_currentType = null,
        $_name = 'site_favorites',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Favorites_Row',
		$_rowsetClass = 'Core_Db_Favorites_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			)
		);

    public function isExist($profileId = null, $typeId = null, $itemId = null)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('type = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        if ($this->fetchRow($select) instanceof Core_Db_Favorites_Row) {
            return true;
        } else {
            return false;
        }
    }

    public function getByProfileId($profileId = null)
    {
        if (!$profileId) {
            return null;
        }
        $select = $this->getSelect();
        $select->where('profile_id = ?', $profileId);
        return $this->fetchAll($select);
    }

    public function getByItemId($itemId = null, $typeId = null)
    {
        if (!$itemId) {
            return null;
        }
        if (!$typeId) {
            return null;
        }
        $select = $this->getSelect();
        $select->where('item_id = ?', $itemId);
        $select->where('type = ?', $typeId);
        return $this->fetchAll($select);
    }

}