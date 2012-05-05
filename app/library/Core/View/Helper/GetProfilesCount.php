<?php
class Core_View_Helper_GetProfilesCount extends Core_View_Helper
{
	function getProfilesCount()
	{
        $profileModel = new Core_Db_Profiles;
        return $profileModel->getRowsCount();
	}
}
