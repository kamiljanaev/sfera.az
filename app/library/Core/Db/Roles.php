<?php
class Core_Db_Roles extends Core_Db_Table
{
	protected
		$_name = 'view_roles',
		$_primary = 'id',
		$_rowClass = 'Core_Db_Roles_Row',
		$_rowsetClass = 'Core_Db_Roles_Rowset';

	public function getByName( $name = 'guest' )
	{
		$select = $this->select()->where( '`name` = ?', $name );
		return $this->fetchRow($select);
	}

	public function getIdNameList()
	{
		return $this->fetchList('id', 'name');
	}
}