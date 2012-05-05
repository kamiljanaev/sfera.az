<?php
class Core_Db_SubscribeLinks_Row extends Core_Db_Table_Row
{
	private
		$_Profile = null,
		$_Category = null;

	public function getProfile()
	{
		if ($this->_Profile==null) {
			$this->_Profile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
		}
		return $this->_Profile;
	}

	public function getCategory()
	{
		if ($this->_Category==null) {
			$this->_Category = $this->findParentRow('Core_Db_Category_Tree', 'Ref_To_Category');
		}
		return $this->_Category;
	}
}