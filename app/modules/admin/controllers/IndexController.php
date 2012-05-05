<?php
class Admin_IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('admin_layout');
	}

	public function indexAction()
	{
	}
}