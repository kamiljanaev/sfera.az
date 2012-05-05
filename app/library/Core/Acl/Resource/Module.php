<?php
class Core_Acl_Resource_Module implements Zend_Acl_Resource_Interface
{
	private
		$_moduleName,
		$_controllerName,
		$_actionName;

	public function __construct($_moduleName = null, $_controllerName = null, $_actionName = null)
	{
		$this->_moduleName = ($_moduleName === null)?'':strtolower(str_replace('-', '', $_moduleName));
		$this->_controllerName = ($_controllerName === null)?'':str_replace('-', '',$_controllerName);
		$this->_actionName = ($_actionName === null)?'':str_replace('-', '',$_actionName);
	}

	public function getResourceId()
	{
		return $this->_moduleName.':'.$this->_controllerName;
	}
}
