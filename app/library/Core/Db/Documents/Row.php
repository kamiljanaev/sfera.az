<?php
class Core_Db_Documents_Row extends Core_Db_Table_Row
{
    protected
        $_currentUser = null,
        $_currentProfile = null;
    
	public function link($action='view')
	{
		return Core_View_Helper_Link :: document($this->id, $action);
	}

    public function getUser()
    {
        if (!($this->_currentUser instanceof Core_Db_Users_Row)) {
            $this->_currentUser = $this->findParentRow('Core_Db_Users', 'Ref_To_User');
        }
        return $this->_currentUser;
    }

    public function getProfile()
    {
        if (!($this->_currentProfile instanceof Core_Db_Users_Row)) {
            $this->_currentProfile = $this->findParentRow('Core_Db_Profiles', 'Ref_To_Profile');
        }
        return $this->_currentProfile;
    }
}
