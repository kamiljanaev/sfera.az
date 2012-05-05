<?php
class Core_Controller_Action_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
	const
		DEFAULT_ROLE_NAME = 'guest',
		DEFAULT_AUTH_URL = '/auth',
		DEFAULT_ACCESS_DENIED_URL = '/denied';

	private
		$_accessDeniedUrl,
		$_redirectUrl;

	public function init()
	{
		$helper = new Core_View_Helper_GetUrl;
		$this->_redirectUrl = $helper->getUrl(Zend_Registry::get('systemconfig')->site->get('auth_url', self::DEFAULT_AUTH_URL));
		$this->_accessDeniedUrl = $helper->getUrl(Zend_Registry::get('systemconfig')->site->get('access_denied_url', self::DEFAULT_ACCESS_DENIED_URL));
	}

	public function preDispatch()
	{
		$redirector = Zend_Controller_Action_HelperBroker::getExistingHelper('redirector');
		$request = $this->getRequest();
		$logged_by = $request->getParam('logged_by');
		if ($logged_by != null) {
			$authAdapter = new Core_Auth_QuickAdapter('md5hash');
			$authAdapter->setIdentity($logged_by)->setCredential('credential');
			$auth = Core_Auth::getInstance();
			$result = $auth->authenticate($authAdapter);
			if ($result->isValid()) {
				Core_Cart::getInstance()->setUser(Core_Auth::getInstance()->getIdentity());
			}
		}
		$r = $this->getRequest();
		$aclManager = Core_Acl_Factory::createAcl(null, Core_Acl_Factory::CORE_ACL_TYPE_MODULES);
		$allowed = $aclManager->checkPermissionByUser(null, $r->getModuleName(), $r->getControllerName(), $r->getActionName());
		if (!$allowed) {
			$_SESSION['referer'] = $r->REQUEST_URI;
			if (Core_Auth::getInstance()->getIdentity()) {
				$redirector->gotoUrl($this->_accessDeniedUrl, array ('prependBase'=>false));
			} else {
				$redirector->gotoUrl($this->_redirectUrl, array ('prependBase'=>false));
			}
		}
	}
}