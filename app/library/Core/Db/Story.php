<?php
class Core_Db_Story extends Core_Db_Table
{
    const
        STORY_STATUS = 1,
        STORY_FRIEND_ADD = 2,
        STORY_COMMON = 3;
    
	protected
        $_currentType = null,
        $_name = 'site_story',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Story_Row',
		$_rowsetClass = 'Core_Db_Story_Rowset';

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

	public function getByProfileId($profileId = null, $from = null, $limit = null)
	{
		if (!$profileId) {
			return null;
		}
		$select = $this->getSelect();
        $select->where('profile_id = ?', $profileId);
        $select->order('added desc');
		return $this->fetchAll($select);
	}

}