<?php
class Core_Controller_Dispatcher_Standard extends Zend_Controller_Dispatcher_Standard
{
	public function getControllerClass(Zend_Controller_Request_Abstract $request)
	{
		$controllerName = $request->getControllerName();
		if (empty($controllerName)) {
			if (!$this->getParam('useDefaultControllerAlways')) {
				return false;
			}
			$controllerName = $this->getDefaultControllerName();
			$request->setControllerName($controllerName);
		}
		$className = $this->formatControllerName($controllerName);
		$controllerDirs = $this->getControllerDirectory();
		$module = $request->getModuleName();
		if ($this->isValidModule($module)) {
			$this->_curModule    = $module;
			$this->_curDirectory = $controllerDirs[$module];
		} elseif ($this->isValidModule($this->_defaultModule)) {
			$request->setModuleName($this->_defaultModule);
			$this->_curModule    = $this->_defaultModule;
			$this->_curDirectory = $controllerDirs[$this->_defaultModule];
		} else {
			throw new Zend_Controller_Exception('No default module defined for this application');
		}
		return $className;
	}

	public function formatClassName($moduleName, $className) {
		return $this->formatModuleName($moduleName) . '_' . $className;
	}
}