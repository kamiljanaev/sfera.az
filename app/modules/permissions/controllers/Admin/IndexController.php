<?php
class Permissions_Admin_IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->params = $this->_params = $this->getParams();
		$this->view->response = $this->getResponseArray();
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('module','json')
				->setAutoDisableLayout(true)
				->setAutoJsonSerialization(false)->initContext();
        $this->getMessages();
        $this->view->currentRole = $this->_params->role_id;
	}

    protected function getMessages()
    {
        $this->view->msgButtonBack = $this->_translate->translate('button-back');
        $this->view->msgButtonInstall = $this->_translate->translate('button-install');
        $this->view->msgButtonClearACLCache = $this->_translate->translate('button-clear-acl-cache');
        $this->view->msgLabelSelectRole = $this->_translate->translate('label-select-role');
        $this->view->msgLabelCurrentRole = $this->_translate->translate('label-current-role');
        $this->view->msgAdminTitle = $this->_translate->translate('permissions-admin-title');
        $this->view->msgBackUrl = $this->view->getUrl('/admin/module/roles');
        $this->view->msgTreeBaseUrl = $this->view->getUrl('/admin/module/roles');

    }

	public function indexAction()
	{
		$roles = new Core_Db_Roles();
		$this->view->rolesSelect = $roles->fetchList('id', 'name');
	}

	public function clearCacheAction()
	{
		$this->_helper->_layout->disableLayout();
		if (Zend_Registry::isRegistered('sessionCache')) {
			$sessionCache = Zend_Registry::get('sessionCache');
			$sessionCache->remove('coreAclCms');
			$sessionCache->remove('coreAclModules');
		}
		$json_result = new Core_Response();
		$json_result->code = 1;
		$json_result->addMessage('Кеш с списком прав доступов очищен');
		$this->_helper->json->sendJson($json_result);
	}

    public function fetchAction()
    {
        $this->_helper->_layout->disableLayout();
        $modulesModel = new Core_Db_Modules();
        $controllersModel = new Core_Db_Controllers();
        $actionsModel = new Core_Db_Actions();
        $permissionsModel = new Core_Db_Permissions();
        $modulesList = $modulesModel->getAllList();
        $modulesData = array();
        foreach ($modulesList as $moduleItem) {
            $module = array();
            $module['data'] = $moduleItem->name;
            $module['attr'] = array(
                'id' => $moduleItem->id,
                'class' => 'module_item'
            );
            $module['state'] = 'closed';
            $controllersList = $controllersModel->getByModuleId($moduleItem->id);
            $controllersData = array();
            foreach ($controllersList as $controllerItem) {
                $controller['data'] = $controllerItem->name;
                $controller['attr'] = array(
                    'id' => $controllerItem->id,
                    'class' => 'controller_item'
                );
                $controller['state'] = 'closed';
                $actionsList = $actionsModel->getByControllerId($controllerItem->id);
                $actionsData = array();
                foreach ($actionsList as $actionItem) {
                    $action['data'] = $actionItem->name;
                    $action['attr'] = array(
                        'id' => $actionItem->id,
                        'class' => 'controller_item'
                    );
                    if ($this->view->currentRole) {
                        $action['checked'] = $permissionsModel->checkPermissionById($this->view->currentRole, $actionItem->id);
                    }else {
                        $action['checked'] = false;
                    }
                    $actionsData[] = $action;
                }
                $controller['actions'] = $actionsData;
                $controllersData[] = $controller;
            }
            $module['controllers'] = $controllersData;
            $modulesData[] = $module;
        }
        $this->view->modulesData = $modulesData;
    }

	public function moduleAction()
	{
		$permissionsModel = new Core_Db_Permissions();
		$modulesModel = new Core_Db_Modules();
		$controllersModel = new Core_Db_Controllers();
		$actionsModel = new Core_Db_Actions();
		$rolesModel = new Core_Db_Roles();
		$roleId = $this->_params->role_id;
		$actionId = $this->_params->action_id;
		$permit = $this->_params->permit;
		$this->view->response->actionId = $actionId;
		$this->view->response->setCode(Core_Response::RESPONSE_ERROR);

		if (!$roleId||!$actionId) {
			$this->view->response->addMessage('Неизвестная роль или действие');
			$this->render('result');
			return;
		}
		if (!($currentAction = $actionsModel->find($actionId))) {
			$this->view->response->addMessage('Действие не существует');
			$this->render('result');
			return;
		}
		$currentAction = $currentAction->current();
		if (!($currenRole = $rolesModel->find($roleId))) {
			$this->view->response->addMessage('Роль не существует');
			$this->render('result');
			return;
		}
		$currenRole = $currenRole->current();
		if (!($currentController = $currentAction->findParentRow($controllersModel))) {
			$$this->view->response->addMessage('Контроллер не существует');
			$this->render('result');
			return;
		}
		if (!($currentModule = $currentController->findParentRow($modulesModel))) {
			$this->view->response->addMessage('Модуль не существует');
			$this->render('result');
			return;
		}
		$moduleId = $currentModule->id;
		$controllerId = $currentController->id;
		if ($permit == 'true') {
			if (!($permissionsModel->find($roleId, $moduleId, $controllerId, $actionId)->count())) {
				if (!$permissionsModel->addData(array('role_id' => $roleId, 'module_id' => $moduleId, 'controller_id' => $controllerId, 'action_id' => $actionId))) {
					$this->view->response->addMessage('Ошибка при добавлении разрешения');
					$this->render('result');
					return;
				}
			}else {
				$this->view->response->setCode(Core_Response::RESPONSE_OK);
				$this->view->response->addMessage('Разрешение уже существует');
				$this->render('result');
				return;
			}
			$this->view->response->setCode(Core_Response::RESPONSE_OK);
			$this->view->response->addMessage('Разрешение добавлено');
			$this->render('result');
			return;
		}else {
			if ($permissions = $permissionsModel->find($roleId, $moduleId, $controllerId, $actionId)) {
				foreach ($permissions as $permission) {
					$permission->delete();
				}
			}
			$this->view->response->setCode(Core_Response::RESPONSE_OK);
			$this->view->response->addMessage('Рарешение удалено');
			$this->render('result');
			return;
		}
		$this->render('result');
	}

	public function sectionsAction()
	{
		$roles = new Core_Db_Roles();
		$this->view->rolesSelect = $roles->fetchList('id', 'name');
		$this->view->currentRole = $this->_params->role_id;
	}

	protected function getParams()
	{
		$role_id   	    = $this->_getParam('role_id', -1);
		$action_id      = $this->_getParam('action_id', null);
		$section_id     = $this->_getParam('section_id', null);
		$permit         = $this->_getParam('permit', null);
		return new Core_Params(array('role_id'=>$role_id,
						'action_id'=>$action_id,
						'section_id'=>$section_id,
						'permit'=>$permit
		));
	}

	protected function getResponseArray()
	{
		return new Core_Response();
	}
}