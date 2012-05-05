<?php
class Core_Db_FriendsLinks extends Core_Db_Table
{
	protected
		$_name = 'site_friendslinks',
		$_primary = 'id',
		$_rowClass = 'Core_Db_FriendsLinks_Row',
		$_rowsetClass = 'Core_Db_FriendsLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_Profile' => array(
							'columns' => array('profile_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			),
			'Ref_To_Friend' => array(
							'columns' => array('friend_id'),
							'refTableClass'	=> 'Core_Db_Profiles',
							'refColumns' => array('id')
			)
		);

    public function addFriendsLink($profileId = null, $friendId = null)
    {
        if (!$profileId) {
            return false;
        }
        if (!$friendId) {
            return false;
        }
        $currentLink = $this->getFriendsLink($profileId, $friendId);
        if (!$currentLink) {
            $newLink = $this->createRow();
            $newLink->profile_id = $profileId;
            $newLink->friend_id = $friendId;
            $newLink->save();
            return $newLink;
        } else {
            return $currentLink;
        }
    }

    public function getFriendsLink($profileId = null, $friendId = null)
    {
        if (!$profileId) {
            return false;
        }
        if (!$friendId) {
            return false;
        }
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        $select->where('friend_id = ?', $friendId);
        return $this->fetchRow($select);
    }


    public function getByProfileId($profileId = null, $limit = null)
    {
        if (!$profileId) {
            return null;
        }
        $select = $this->select()->from($this);
        $select->where('profile_id = ?', $profileId);
        if ($limit) {
            $select->limit($limit);
        }
        return $this->fetchAll($select);
    }

	public function getByFriendId($friendId = null, $limit = null)
	{
		if (!$friendId) {
			return null;
		}
		$select = $this->select()->from($this);
        $select->where('friend_id = ?', $friendId);
        if ($limit) {
            $select->limit($limit);
        }
		return $this->fetchAll($select);
	}

	public function deleteByProfileId($profileId = null)
	{
		if (!$profileId) {
			return null;
		}
		return $this->delete('profile_id = '.$profileId);
	}
}