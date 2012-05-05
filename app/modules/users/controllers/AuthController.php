<?php
class Users_AuthController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->login = true;
		$this->_helper->layout->setLayout('layout');
	}

	public function indexAction()
	{
		$this->view->title = $this->_translate->translate('message-auth-title');
	}

	public function loginAction()
	{
		$message = '';
		if ($this->_request->isPost()) {
			$data = array(
					'login' => trim($this->_request->getParam('login')),
					'password'  => trim($this->_request->getParam('password'))
			);
			if (empty($data['login']) || empty($data['password'])) {
				$message = $this->_translate->translate('message-empty-credentials');
			} else {
				$authAdapter = new Core_Auth_Adapter('login');
				$authAdapter->setIdentity($data['login'])->setCredential($data['password']);
				$auth = Core_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);
				if ($result->isValid()) {
					$userRow = Core_Auth::getInstance()->getIdentity();
					if ($data['remember']) {
						Zend_Session::rememberMe();
					}
					if (Zend_Registry::isRegistered('sessionCache')) {
						$sessionCache = Zend_Registry::get('sessionCache');
						$sessionCache->remove('coreAclModules');
					}
					$aclManager = Core_Acl_Factory::createAcl(null, Core_Acl_Factory::CORE_ACL_TYPE_MODULES);
					$allowed = $aclManager->checkPermissionByUser(null, 'admin', 'index', 'index');
					if ($this->_request->referer_url) {
						$this->_redirect($this->_request->referer_url);
					} else if (isset($_SESSION['referer'])) {
						$this->_redirect($_SESSION['referer']);
					} else {
						$url = $this->view->getUrl('/');
						$this->_redirect($url, array('prependBase' => false));
					}
				}else {
					$message = $this->_translate->translate('message-incorrect-credentials');
				}
			}
		}
		$this->view->message = $message;
		$this->render('index');
	}

	public function logoutAction()
	{
		$auth = Core_Auth::getInstance();
		$auth->clearIdentity();
		Zend_Session::destroy();
		if (Zend_Registry::isRegistered('sessionCache')) {
			$sessionCache = Zend_Registry::get('sessionCache');
			$sessionCache->remove('coreAclModules');
		}
		$this->_redirect($this->view->getUrl(),array('prependBase'=>false));
	}

	public function deniedAction()
	{
		$this->view->title = $this->_translate->translate('message-access-denied');
	}
}
