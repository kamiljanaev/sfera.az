<?php
class Core_Auth_QuickAdapter extends Core_Auth_Adapter
{
	protected function _authenticateCreateSelect()
	{
		$dbSelect = $this->_usersModel->select();
		$dbSelect->where($this->_usersModel->getAdapter()->quoteIdentifier($this->_identityColumn, true) . ' = ?', $this->_identity);
		return $dbSelect;
	}
}