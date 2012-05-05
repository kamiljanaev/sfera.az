<?php
class Core_Db_Table_Select extends Zend_Db_Table_Select
{
	private
		$_delFlag,
		$_doUseCache = false;

	public function setUseCache($doUse)
	{
		$this->_doUseCache = $doUse;
		return $this;
	}

	public function isUseCache()
	{
		return $this->_doUseCache;
	}
}