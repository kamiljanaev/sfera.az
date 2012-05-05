<?php
class Admin_InstallController extends Zend_Controller_Action
{
	private
		$_modules,
		$_controllers,
		$_actions;

	public function init()
	{
		$this->_modules = new Core_Db_Modules;
		$this->_controllers = new Core_Db_Controllers;
		$this->_actions = new Core_Db_Actions;
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('index','json');
		$contextSwitch->clearHeaders('json');
		$contextSwitch->setAutoDisableLayout(true);
		$contextSwitch->setAutoJsonSerialization(false);
		$contextSwitch->initContext();
	}

	public function indexAction()
	{
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$currentContext = $contextSwitch->getCurrentContext();
		$this->view->response = new Core_Response();
		if ($currentContext === NULL) {
			$this->_helper->viewRenderer->setNoRender(true);
		}
		try {
			$this->installModules();
			$this->view->response->setCode(Core_Response::RESPONSE_OK);
			$this->view->response->addMessage('Инсталляция прошла успешно');
		}catch(Zend_Exception $ex) {
			$this->view->response->setCode(Core_Response::RESPONSE_ERROR);
			$this->view->response->addMessage('Ошибка инсталляции');
			$this->view->response->addMessage($ex->getMessage());
		}
	}

	private function getModulesPaths($moduleConfig)
	{
		$paths = array();
		if (!is_string($moduleConfig)) {
			foreach ($moduleConfig as $sub) {
				foreach ($sub->modules as $name) {
					$paths[$sub->root.$name.'/'] = $sub->classPrefix;
				}
			}
		} else {
			$paths[$moduleConfig] = '';
		}
		return $paths;
	}

	private function installModules()
	{
		$config = Zend_Registry::get('systemconfig');
		$paths = $this-> getModulesPaths($config->path->modules);
		foreach ($paths as $module_dir=>$prefix) {
			$dir = new DirectoryIterator($module_dir);
			$modulePrefix = (trim($prefix) != '')?trim($prefix).'_':'';
			foreach ($dir as $file) {
				if ($file->isDot() || !$file->isDir()) {
					continue;
				}

				$name = $file->getFilename();

				// Don't use SCCS directories as modules
				if (preg_match('/^[^a-z]/i', $name) || ('' == $name)) {
					continue;
				}
				$name = $modulePrefix.$name;
				//If such module doesn't exists in database then insert it
				if (null == $module = $this->_modules->fetchRow(array ('name = ?' => $name))) {
					// Adding new module
					$moduleId = $this->_modules->insert(array ('name' => $name));
				} else {
					$moduleId = $module->id;
				}
				$this->installControllersActions($name, $moduleId);
			}
		}
	}

	private function installControllersActions($module, $moduleId, $subfolder = null)
	{
		$controllersPath = $this->getFrontController()->getControllerDirectory($module) . ($subfolder ? DIRECTORY_SEPARATOR . $subfolder : '');

		//Seting right module name
		$module_orig = $module;
		$module = strtoupper($module);
		$moduleDir = new DirectoryIterator($controllersPath);

		//Scan controllers folder
		foreach ($moduleDir as $ctrlFile) {
			if ($ctrlFile->isDot()) {
				continue;
			}

			// If admin subfolder found then install admin controllers and actions
			if ($ctrlFile->isDir()) {
				if ($ctrlFile->getFileName() == 'Admin') {
					$this->installControllersActions($module_orig, $moduleId, 'Admin');
				} else {
					continue;
				}
			}

			// Check that detected file is controller
			$controllerFileName = $ctrlFile->getFilename();
			if (!preg_match('/([a-z])+Controller.php/i', $controllerFileName, $matches)) {
				continue;
			}
			if (preg_match('/^\./i', $controllerFileName)) {
				continue;
			}
			if (preg_match('/^\`/i', $controllerFileName)) {
				continue;
			}

			// Loading file and generating controller class name
			require_once($controllersPath . DIRECTORY_SEPARATOR . $controllerFileName);
			$controllerClassName = $controllerName = str_replace(".php","",$matches[0]);

			// If subfolder then add folder name to controller name
			if ($subfolder) {
				$subfolder[0] = strtoupper($subfolder[0]);
				$controllerClassName = $controllerName = $subfolder . '_' .$controllerName;
			}

			if (strtolower($module) != $this->getFrontController()->getDefaultModule()) {
				$controllerClassName = $module . '_' . $controllerName;
			}

			if (!class_exists($controllerClassName, false)) {
				continue;
			}

			$ctrlLength = strlen($controllerName);
			$ctrlKeywdLength = strlen('Controller');
			$controllerName = strtolower(substr_replace($controllerName,'',$ctrlLength-$ctrlKeywdLength,$ctrlKeywdLength));

			// If controller with such name doesn't exists then insert it into database
			if (null == $controller = $this->_controllers->fetchRow(array('name = ?' => $controllerName, 'module_id = ?' => $moduleId))) {
				$controllerId = $this->_controllers->insert(array ('name' => $controllerName, 'module_id' => $moduleId));
			} else {
				$controllerId = $controller->id;
			}

			// Get information about loaded controller
			$controllerRef = new ReflectionClass($controllerClassName);
			$controllerMethods = $controllerRef->getMethods();

			foreach ($controllerMethods as $method) {
				//Check that method is action
				$actionName = $method->getName();

				if ($method->isPublic() && preg_match('/([a-z])Action/i',$actionName)) {
					//If action with such name doesn't exists then insert it into database
					$actLength = strlen($method->getName());
					$actKeywdLength = strlen('Action');
					$actionName = strtolower(substr_replace($actionName, '', $actLength - $actKeywdLength, $actKeywdLength));

					if (null == $action = $this->_actions->fetchRow(array('name = ?' => $actionName, 'controller_id = ?' => $controllerId))) {
						$actionId = $this->_actions->insert(array ('name' => $actionName, 'controller_id' => $controllerId));
					} else {
						$actionId = $action->id;
					}
				}
			}
		} // End foreach controllers directory
	}
}