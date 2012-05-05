<?php
class Core_Db_Controllers_Row extends Core_Db_Table_Row
{
	private
		$_Module = null;

	public function getModule()
	{
		if ($this->_Module==null) {
			$this->_Module = $this->findParentRow('Core_Db_Modules', 'Ref_To_Module');
		}
		return $this->_Module;
	}
}