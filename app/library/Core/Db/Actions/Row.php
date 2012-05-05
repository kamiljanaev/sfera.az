<?php
class Core_Db_Actions_Row extends Core_Db_Table_Row
{
	private
		$_Controller = null;

	public function getController()
	{
		if ($this->_Controller==null) {
			$this->_Controller = $this->findParentRow('Core_Db_Controllers', 'Ref_To_Controller');
		}
		return $this->_Controller;
	}
}