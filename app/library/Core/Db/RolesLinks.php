<?php
class Core_Db_RolesLinks extends Core_Db_Table
{
	protected
		$_name = 'sys_roleslinks',
		$_primary = 'id',
		$_rowClass = 'Core_Db_RolesLinks_Row',
		$_rowsetClass = 'Core_Db_RolesLinks_Rowset';

	protected
		$_referenceMap	= array(
			'Ref_To_User' => array(
							'columns' => array('user_id'),
							'refTableClass'	=> 'Core_Db_Users',
							'refColumns' => array('id')
			),
			'Ref_To_Role' => array(
							'columns' => array('role_id'),
							'refTableClass'	=> 'Core_Db_Roles',
							'refColumns' => array('id')
			)
		);

	public function getByUserId($userId = null)
	{
		if (!$userId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('user_id = ?', $userId);
		return $this->fetchAll($select);
	}

	public function getByRoleId($roleId = null)
	{
		if (!$roleId) {
			return null;
		}
		$select = $this->select()->from($this);
		$select->where('role_id = ?', $roleId);
		return $this->fetchAll($select);
	}

	public function deleteByUserId($userId = null)
	{
		if (!$userId) {
			return null;
		}
		return $this->delete('user_id = '.$userId);
	}
}