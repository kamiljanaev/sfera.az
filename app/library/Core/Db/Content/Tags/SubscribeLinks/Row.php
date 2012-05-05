<?php
class Core_Db_Content_Tags_SubscribeLinks_Row extends Core_Db_Table_Row
{
	private
		$_Profile = null,
		$_Tag = null;

	public function getProfile()
	{
		if ($this->_Profile==null) {
			$this->_Profile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
		}
		return $this->_Role;
	}

	public function getTag()
	{
		if ($this->_Tag==null) {
			$this->_Tag = $this->findParentRow('Core_Db_Content_Tags', 'Ref_To_Tag');
		}
		return $this->_Tag;
	}
}