<?php
class Core_View_Helper_SigninUser extends Core_View_Helper
{
	function signinUser()
	{
		$this->_view->loggedUser = null;
		$auth = Core_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$this->_view->loggedUser = $auth->getIdentity();
            $this->_view->currentProfile = $this->_view->loggedUser->getProfile();
		}
		return $this->_view->render('signinUser.phtml');
	}
}
