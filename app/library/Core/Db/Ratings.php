<?php
class Core_Db_Ratings extends Core_Db_Table
{
    const
        R_NEWS = 1,
        R_BLOGS = 2;

	protected
		$_name = 'view_ratings',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Ratings_Row',
		$_rowsetClass = 'Core_Db_Ratings_Rowset';
	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			)
		);

    public function isExist($profileId = null, $itemId = null, $typeId = null)
    {
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('type_id = ?', $typeId);
        $select->where('item_id = ?', $itemId);
        if ($this->fetchRow($select) instanceof Core_Db_Ratings_Row) {
            return true;
        } else {
            return false;
        }
    }

	public function getVotesByUserId($profileId = null, $itemId = null, $typeId = null)
	{
		if ($profileId) {
			$select = $this->select()->from($this);
			$select->where( '`profile_id` = ?', $profileId);
            if ($itemId) {
                $select->where( '`item_id` = ?', $itemId);
            }
            if ($typeId) {
                $select->where( '`type_id` = ?', $typeId);
            }
			return $this->fetchAll($select)->fetchArray();
		}
		return array();
	}

	public function getVotesByIp($ip = null, $itemId = null, $typeId = null)
	{
		if ($ip) {
			$select = $this->select()->from($this);
			$select->where( '`ip` = ?', $ip);
            if ($itemId) {
                $select->where( '`item_id` = ?', $itemId);
            }
            if ($typeId) {
                $select->where( '`type_id` = ?', $typeId);
            }
			return $this->fetchAll($select)->fetchArray();
		}
		return array();
	}

}