<?php
class Core_Auth extends Zend_Auth
{
	private
		$_identityChache = null;

	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getIdentity()
	{
		if ($this->_identityChache == null) {
			$data = parent::getIdentity();
			if ($data != null) {
				$usersModel = new Core_Db_Users();
                $this->_identityChache = $usersModel->getRowInfo($data);
			}
		}
		return $this->_identityChache;
	}
}