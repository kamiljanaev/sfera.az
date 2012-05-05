<?php
class Core_Db_Permissions extends Core_Db_Table
{
	protected
		$_name = 'sys_permissions',
		$_rowClass = 'Core_Db_Permissions_Row',
		$_rowsetClass = 'Core_Db_Permissions_Rowset';

	private
		$_roleId = null;

	protected
		$_referenceMap	= array(
			'Ref_To_Role' => array(
							'columns' => array('role_id'),
							'refTableClass'	=> 'Core_Db_Roles',
							'refColumns' => array('id')
			),
			'Ref_To_Module' => array(
							'columns' => array('module_id'),
							'refTableClass'	=> 'Core_Db_Modules',
							'refColumns' => array('id')
			),
			'Ref_To_Controller' => array(
							'columns' => array('controller_id'),
							'refTableClass'	=> 'Core_Db_Controllers',
							'refColumns' => array('id')
			),
			'Ref_To_Action' => array(
							'columns' => array('action_id'),
							'refTableClass'	=> 'Core_Db_Actions',
							'refColumns' => array('id')
			)
		);

	public function setRoleId($roleId = null)
	{
		$this->_roleId = $roleId;
	}

	public function getRoleId()
	{
		return $this->_roleId;
	}

	public function fetchAvailable($roleId = null)
	{
		$sel = $this->select()
				->setIntegrityCheck(false)
				->from(array ('a' => 'sys_actions'), array ('action_id' => 'id', 'name_action' => 'name'))
				->join(array ('c' => 'sys_controllers'), 'a.controller_id = c.id', array ('controller_id' => 'id', 'name_controller' => 'name'))
				->join(array ('m' => 'sys_modules'), 'c.module_id = m.id', array ('module_id' => 'id', 'name_module' => 'name'))
				->order('m.name')
				->order('c.name')
				->order('a.name');
		if ($roleId) {
			$sel->joinLeft(
					array ('p' => 'sys_permissions'),
					'm.id = p.module_id AND c.id = p.controller_id AND a.id = p.action_id AND ' . $this->_db->quoteInto('p.role_id = ?', $roleId),
					array ('permitted' => new Zend_Db_Expr('p.role_id IS NOT NULL'))
			);
		}

		return $this->fetchAll($sel);
	}

	public function checkPermission($roleId, $moduleName, $controllerName, $actionName)
	{
		$checker = Core_Db_Permissions_Checker :: getInstance();
		return $checker->checkPermission($roleId, $moduleName, $controllerName, $actionName);
	}

	public function checkPermissionById($roleId, $actionId)
	{
		$select = $this->select()
				->where('role_id = ?', $roleId)
				->where('action_id = ?', $actionId);
		return (bool)$this->fetchAll($select)->count();
	}
}