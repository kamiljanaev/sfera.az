<?php
class Zend_View_Helper_CheckPermission extends Core_View_Helper
{
	private
		$_aclManager;

	public function setView(Zend_View_Interface $view)
	{
		$this->_view = $view;
		$this->_aclManager = Core_Acl_Factory::createAcl(null, Core_Acl_Factory::CORE_ACL_TYPE_MODULES);
	}

	function checkPermission($module, $controller, $action)
	{
		return $this->_aclManager->checkPermissionByUser(null, $module, $controller, $action);
	}
}