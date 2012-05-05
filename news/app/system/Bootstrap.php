<?php
require_once 'Zend/Loader/Autoloader.php';

class Bootstrap
{
	private
		$_configArray = null,
		$_systemconfig = null,
		$_fileCache = null,
		$_sessionCache = null,
		$_db;

	public function run($systemconfig)
	{
		$this->_configArray = $systemconfig;
		try {
			$this->setLoader();
			$this->setConfig();
			$this->setCache();
			$this->setDbAdapter();
			$this->setRegistry();
			$this->setLogger();
			$this->setRedirector();
			$this->setView();
			$this->setDebugger();
			$this->getController()->dispatch();
		} catch (Zend_Controller_Dispatcher_Exception $e) {
			$this->_error404($e);
		} catch (Exception $e) {
			$this->_errorReport($e);
			$this->_error503($e);
		}
	}

	private function _error404($e)
	{
		$this->_errorPage(404, 'Not Found', $e);
	}
	
	private function _error503($e)
	{
		$this->_errorPage(503, 'Service Unavailable', $e);
	}

	private function _errorPage($num, $text, $e)
	{
		header("HTTP/1.0 $num $text");
		$enginePath = $this->_configArray['path']['views'] . $num . '.html';
		if (is_readable($enginePath)) {
			readfile($enginePath);
		} else {
			echo 'Error '.$num.' '.$text;
		}
		if ($this->_configArray['debug']['on']) {
			echo "<pre>$e</pre>";
		}
		exit();
	}

	private function _errorReport($e)
	{
		$config = $GLOBALS['systemconfig'];
		ob_start();
		print_r($e->getMessage);
		print_r($e->getTraceAsString());
		$str = ob_get_contents() . "\r\n";
		ob_end_clean();
		if ($this->_configArray['error']['loggin']) {
			$log = new Zend_Log(new Zend_Log_Writer_Stream($this->_configArray['error']['logFile']));
			$log->emerg($str);
		}
		if ($this->_configArray['error']['emailNotification']) {
			$to = $this->_configArray['error']['emailTo'];
			$subject = '['.$_SERVER['HTTP_HOST'].'] crashed at '.date('d/m/Y H:i:s');
			mail($to, $subject, $str);
		}
	}

	public function setLoader()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);
	}

	public function setConfig()
	{
		$this->_systemconfig = new Zend_Config($this->_configArray);
        $locale = $this->_systemconfig->languages->default;
		$locale = new Zend_Locale($locale);
		Zend_Registry::set('Zend_Locale', $locale);
    }

	public function setCache()
	{
		$path = $this->_systemconfig->path->cache;
		$frontendOptions = array(
				'lifetime' => 10800,
				'ignore_user_abort' => true,
				'automatic_serialization' => true
		);
		$backendOptions  = array(
				'cache_dir'                => $path
		);
		$this->_fileCache = Zend_Cache::factory('Core',
				'File',
				$frontendOptions,
				$backendOptions);
		Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_fileCache);
//		Core_Controller_Router_Rewrite::setDefaultRouteCache($this->_fileCache);

		$frontendOptions = array(
				'caching' => true,
				'lifetime' => null,
				'ignore_user_abort' => true,
				'automatic_serialization' => true
		);
		$backendOptions  = array();
		$this->_sessionCache = Zend_Cache::factory('Core',
				'Core_Cache_Backend_Session',
				$frontendOptions,
				$backendOptions, false, true);
	}

	public function setDbAdapter()
	{
		$this->_db = Zend_Db::factory($this->_systemconfig->db);
		Zend_Db_Table_Abstract::setDefaultAdapter($this->_db);
	}

	public function setRegistry()
	{
		Zend_Registry::set('systemconfig', $this->_systemconfig);
		Zend_Registry::set('fileCache', $this->_fileCache);
		Zend_Registry::set('sessionCache', $this->_sessionCache);
		Zend_Registry::set('db', $this->_db);
		$settingsModel = new Core_Db_Settings();
		$sitesettings = $settingsModel->fetchList('name', 'value');
		Zend_Registry::set('sitesettings', $sitesettings);
		if (array_key_exists('date_format', $sitesettings)) {
			$dateFormat = $sitesettings['date_format'];
		} else {
			$dateFormat = 'dd MMMM yyyy HH:mm';
		}
		Zend_Registry::set('DateFormat', $dateFormat);
	}

	public function setRedirector()
	{
		$redirector = new Zend_Controller_Action_Helper_Redirector();
		$redirector->setPrependBase(false);
		Zend_Controller_Action_HelperBroker::addHelper($redirector);
	}

	public function setView()
	{
		$layoutPath = $this->_systemconfig->path->layouts;
		Zend_Layout::startMvc(array(
				'layoutPath' => $layoutPath,
				'layout' => 'layout',
		));
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$view->baseUrl = $this->_systemconfig->url->base;
		$view->addFilterPath('Core/View/Filter', 'Core_View_Filter');
		$view->setEncoding($this->_systemconfig->common->charset);
		$layout->setView($view);
	}

	public function getController()
	{
		$controller = Zend_Controller_Front::getInstance();
		$controller->registerPlugin(new Core_Controller_Plugin_Init);
        Zend_Controller_Action_HelperBroker::addHelper(new Core_Controller_Action_Helper_Auth);
        $languageHelper = new Core_Controller_Action_Helper_Language($this->_systemconfig->languages->locales->toArray(), $this->_systemconfig->path->app);
        Zend_Controller_Action_HelperBroker::addHelper($languageHelper);
		$controller->setRequest(new Core_Controller_Request_Http());
		$controller->setDispatcher(new Core_Controller_Dispatcher_Standard());
		$controller->addModuleDirectory($this->_systemconfig->path->modules);
		$controller->setBaseUrl($this->_systemconfig->url->base);
		$controller->throwexceptions(true);
		$controller->setRouter($this->getRouter());
		return $controller;
	}

	public function addModuleDirectory($front, $path, $modulePrefix = '')
	{
		try {
			$dir = new DirectoryIterator($path);
		}catch(Exception $e) {
			throw new Zend_Controller_Exception("Directory $path not readable: ".$path);
		}
		$modulePrefix = ($modulePrefix!='')?$modulePrefix.'_':'';
		foreach ($dir as $file) {
			if ($file->isDot() || !$file->isDir()) {
				continue;
			}
			$module    = $modulePrefix.$file->getFilename();
			if (preg_match('/^[^a-z]/i', $module) || ('CVS' == $module)) {
				continue;
			}
			$moduleDir = $file->getPathname() . DIRECTORY_SEPARATOR . $front->getModuleControllerDirectoryName();
			$front->addControllerDirectory($moduleDir, $module);
		}
	}

	public function getRouter()
	{
		require($this->_systemconfig->path->system . 'routes.php');
		if (!($router instanceof Zend_Controller_Router_Abstract)) {
			throw new Exception('Incorrect config file: routes');
		}
		return $router;
	}

	public function setLogger()
	{
		Core_Logger::init();
	}

	public function setDebugger()
	{
		if ($this->_systemconfig->debug->on) {
			Core_Debug::setEnabled(true);
			Core_Debug::getGenerateTime('Begin');
			$profiler = new Core_Db_Profiler_Firebug('Profiler');
			$profiler->setEnabled(true);
			$db = Zend_Registry::get('db');
			$db->setProfiler($profiler);
		}
	}
}