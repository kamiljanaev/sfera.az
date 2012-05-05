<?php
class Admin_ModuleController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('admin_layout');
	}

	public function indexAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$module = $this->_request->getParam('admin_module');
		$controller = 'admin_'.$this->_request->getParam('admin_controller');
		$action = $this->_request->getParam('admin_action');
		$this->_forward($action,$controller,$module);
	}
}