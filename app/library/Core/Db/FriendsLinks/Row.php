<?php
class Core_Db_FriendsLinks_Row extends Core_Db_Table_Row
{
	private
		$_Profile = null,
		$_Friend = null;

	public function getProfile()
	{
		if ($this->_Profile==null) {
			$this->_Profile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
		}
		return $this->_Profile;
	}

	public function getFriend()
	{
		if ($this->_Friend==null) {
			$this->_Friend = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Friend');
		}
		return $this->_Friend;
	}
}