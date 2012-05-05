<?php
class Core_View_Helper_IsLoggedUser extends Core_View_Helper
{
	function isLoggedUser()
	{
		$auth = Core_Auth::getInstance();
		if ($auth->hasIdentity()) {
			return true;
		}
		return false;
	}
}
