<?php
class Core_SiteConfig
{
	protected
		$_configInstance = null;

	public function __construct()
	{
		$this->_configInstance = Zend_Registry::get('sitesettings');
	}

	public function  __get($name)
	{
		return (is_array($this->_configInstance)&&array_key_exists($name, $this->_configInstance))?$this->_configInstance[$name]:'';
	}

}