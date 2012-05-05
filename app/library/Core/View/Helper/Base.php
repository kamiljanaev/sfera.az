<?php
class Core_View_Helper_Base extends Core_View_Helper
{
	public function __construct()
	{
		$viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer');
		if (is_null($viewRenderer->view)) {
			$viewRenderer->init();
		}
		$this->_view = $viewRenderer->view;
	}
}