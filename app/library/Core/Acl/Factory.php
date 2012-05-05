<?php
class Core_Acl_Factory
{
	const
		CORE_ACL_TYPE_MODULES = 1,
		CORE_ACL_TYPE_CMS = 2;

	private static $modulesInstance = null;

	static public function &createAcl($userId = null, $type = self::CORE_ACL_TYPE_MODULES)
	{
		$sessionCache = null;
		if (Zend_Registry::isRegistered('sessionCache')) {
			$sessionCache = Zend_Registry::get('sessionCache');
		}
		switch($type) {
			default:
				if (self::$modulesInstance === null) {
					if ($sessionCache) {
						if (!$sessionCache->test('coreAclModules')) {
							self::$modulesInstance = new Core_Acl_Modules($userId, true);
							$sessionCache->save(self::$modulesInstance, 'coreAclModules');
						} else {
							self::$modulesInstance = $sessionCache->load('coreAclModules');
						}
					} else {
						self::$modulesInstance = new Core_Acl_Modules($userId, true);
					}
				}
				return self::$modulesInstance;
		}
	}

	static public function clearAcl($type = self::CORE_ACL_TYPE_MODULES)
	{
		switch($type) {
			default:
				self::$modulesInstance = null;
				if (Zend_Registry::isRegistered('sessionCache')) {
					$sessionCache = Zend_Registry::get('sessionCache');
					$sessionCache->remove('coreAclModules');
				}
		}
	}
}
