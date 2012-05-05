<?php
class Core_View_Helper_LoggedUser extends Core_View_Helper
{
	function loggedUser()
	{
		$this->_view->loggedUser = null;
		$auth = Core_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$this->_view->loggedUser = $auth->getIdentity();
            $this->_view->currentProfile = $this->_view->loggedUser->getProfile();
		}
		return $this->_view->render('loggedUser.phtml');
	}
}
