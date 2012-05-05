<?php
class Core_Db_AwardsLinks_Row extends Core_Db_Table_Row
{
	private
		$_Profile = null,
		$_Award = null;

	public function getProfile()
	{
		if ($this->_Profile==null) {
			$this->_Profile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
		}
		return $this->_Role;
	}

	public function getAward()
	{
		if ($this->_Award==null) {
			$this->_Award = $this->findParentRow('Core_Db_Awards', 'Ref_To_Award');
		}
		return $this->_Award;
	}
}