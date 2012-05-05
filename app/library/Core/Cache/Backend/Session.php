<?php
class Core_Cache_Backend_Session extends Zend_Cache_Backend implements Zend_Cache_Backend_Interface
{
	const
		NAMESPACE_NAME = "cachebackendsession";
	protected static $_namespace;

	public function __construct($options = array())
	{
		parent::__construct($options);
		if (!isset(self::$_namespace)) {
			self::$_namespace = new Zend_Session_Namespace(self::NAMESPACE_NAME);
		}
		if (!isset(self::$_namespace->data)) {
			self::$_namespace->data = array();
		}
	}

	public function load($id, $doNotTestCacheValidity = false)
	{
		if (!isset(self::$_namespace->data[$id])) {
			return false;
		}
		return self::$_namespace->data[$id];
	}

	public function test($id)
	{
		return isset(self::$_namespace->data[$id]);
	}

	public function save($data, $id, $tags = array(), $specificLifetime = false)
	{
		self::$_namespace->data[$id] = $data;
		if (count($tags) > 0) {
			$this->_log("Zend_Cache_Backend_Registry::save() : tags are unsupported by the session backend");
		}
		return true;
	}

	public function remove($id)
	{
		unset(self::$_namespace->data[$id]);
		return true;
	}

	public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
	{
		if ($mode==Zend_Cache::CLEANING_MODE_ALL) {
			self::$_namespace->data = array();
		}
		if ($mode==Zend_Cache::CLEANING_MODE_OLD) {
			$this->_log("Zend_Cache_Backend_registry::clean() : CLEANING_MODE_OLD is unsupported by the session backend");
		}
		if ($mode==Zend_Cache::CLEANING_MODE_MATCHING_TAG) {
			$this->_log("Zend_Cache_Backend_registry::clean() : tags are unsupported by the session backend");
		}
		if ($mode==Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG) {
			$this->_log("Zend_Cache_Backend_registry::clean() : tags are unsupported by the session backend");
		}
	}

	public function isAutomaticCleaningAvailable()
	{
		return false;
	}
}