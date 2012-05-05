<?php
class Core_Navigation
{
    const
        ALL_ITEMS = null,
        LEFT_MENU = 1,
        TOP_MENU = 2;

    private
        $_activeMenu = self::ALL_ITEMS;

	public function __construct($menu = self::ALL_ITEMS)
	{
        $this->_activeMenu = $menu;
	}

	public function getContainer()
	{
		$navigationCache = $this->getNavigationCache();
		$cacheId = 'navigation_container';
		if (!$container = $navigationCache->load($cacheId)) {
            try {
                $treeData = $this->buildTree();
                $container = new Zend_Navigation($treeData);
            } catch (Exception $e) {
                Core_Vdie::_($e->getMessage());
            }
			$navigationCache->save($container, $cacheId);
		}
		return $container;

	}

	public function buildTree()
	{
        return array();
        $catalogModel = new Core_Db_Catalog();
        $treeData = $catalogModel->fetchTreeForNavigation();
        foreach ($treeData as &$treeItem) {
            $treeItem['label'] = 'Главная';
            $treeItem['uri'] = '/';
        }
        return $treeData;
	}

	private function getNavigationCache()
	{
		$frontendOptions = array(
				'lifeTime' => null,
				'automatic_serialization' => true
		);
		$config = Zend_Registry::get('systemconfig');
		$backendOptions = array(
				'cache_dir' => $config->path->cache
		);
		return Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
	}

	public static function clearNavigationCache()
	{
		$frontendOptions = array(
				'lifeTime' => null,
				'automatic_serialization' => true
		);
		$config = Zend_Registry::get('systemconfig');
		$backendOptions = array(
				'cache_dir' => $config->path->cache
		);
		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}
}