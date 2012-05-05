<?php
class Core_Controller_Action_Helper_Navigation extends Zend_Controller_Action_Helper_Abstract
{
	public function preDispatch()
	{
        $uri = $_SERVER['REQUEST_URI'];
        $navigation = new Core_Navigation(Core_Navigation::LEFT_MENU);
        $navigationContainer = $navigation->getContainer();
        $this->_actionController->view->navigation($navigationContainer);
        $activeNav = $this->_actionController->view->navigation()->findByUri($uri);
        $activeNav->active = true;
	}
}